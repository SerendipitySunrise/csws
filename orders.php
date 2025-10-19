<?php
// CRITICAL: This file MUST exist and contain the MySQL connection logic, 
// setting up a variable named $conn (e.g., $conn = new mysqli(...)).
// It is assumed to be in the parent directory relative to admin/
include('../includes/db_connect.php');

// --- 1. Utility Functions ---

/**
 * Maps the database status strings to corresponding CSS classes and readable text.
 * @param string $db_status The status value from the database (e.g., 'Pending').
 * @return array Contains 'class' and 'text' for display.
 */
function getStatusDisplay($db_status) {
    // Standardizing status names based on DB schema for CSS classes
    $status_lower = strtolower($db_status);
    return match($status_lower) {
        'pending' => ['class' => 'status-new', 'text' => 'Pending'],
        'preparing' => ['class' => 'status-accepted', 'text' => 'Preparing'],
        'completed' => ['class' => 'status-completed', 'text' => 'Completed'],
        'cancelled' => ['class' => 'status-rejected', 'text' => 'Cancelled'],
        default => ['class' => 'status-unknown', 'text' => 'Unknown'],
    };
}

/**
 * Fetches orders from the database based on a time filter and an optional sort order.
 *
 * @param mysqli $conn The database connection object.
 * @param string $time_filter 'daily', 'weekly', 'monthly', or 'all'.
 * @param string $sort_by 'date_desc' (default) or 'customer_asc'.
 * @return array Array of orders or empty array on failure.
 */
function fetchFilteredOrders($conn, $time_filter = 'all', $sort_by = 'date_desc') {
    $date_condition = "";

    // Determine the date condition for the SQL query based on the filter
    // NOTE: This assumes 'created_at' is a DATETIME or TIMESTAMP column.
    switch (strtolower($time_filter)) {
        case 'daily':
            // Orders created today (compares only the date part)
            $date_condition = "WHERE DATE(o.created_at) = CURDATE()";
            break;
        case 'weekly':
            // Orders created in the last 7 days (including today)
            $date_condition = "WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            break;
        case 'monthly':
            // Orders created in the last 30 days
            $date_condition = "WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            break;
        case 'all':
        default:
            $date_condition = ""; // No filter applied
            break;
    }
    
    // Determine the SQL ORDER BY clause based on sort parameter
    $order_clause = "";
    switch (strtolower($sort_by)) {
        case 'customer_asc':
            // Sort by customer name A-Z, then by newest date (secondary sort)
            $order_clause = "u.name ASC, o.created_at DESC";
            break;
        case 'date_desc':
        default:
            // Default sort: Newest order first
            $order_clause = "o.created_at DESC";
            break;
    }


    $sql = "
        SELECT 
            o.order_id, 
            o.delivery_type, 
            o.total_price,
            o.payment_method,
            o.status, 
            o.created_at,
            o.user_id,
            u.name AS customer_name
        FROM 
            orders o
        LEFT JOIN 
            users u ON o.user_id = u.user_id 
        $date_condition
        ORDER BY 
            $order_clause;
    ";

    $result = $conn->query($sql);

    if ($result === false) {
        error_log("SQL Error: " . $conn->error);
        return [];
    }

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    return $orders;
}

// --- 2. Handle Status Updates (POST Logic) ---

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['order_id'])) {
    
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];
    $new_status = "";

    // CRITICAL FIX: Ensure DB connection is active
    if (!isset($conn) || $conn->connect_error !== null) {
        error_log("Attempted status update but DB connection failed.");
        exit(); 
    }

    if ($action === 'update_status') {
        // --- 2a. Fetch current status securely using prepared statement ---
        $current_status_sql = "SELECT status FROM orders WHERE order_id = ?";
        $stmt_fetch = $conn->prepare($current_status_sql);
        
        if ($stmt_fetch) {
            $stmt_fetch->bind_param("s", $order_id);
            $stmt_fetch->execute();
            $current_result = $stmt_fetch->get_result();
            
            if ($current_result && $current_result->num_rows > 0) {
                 $current_status = $current_result->fetch_assoc()['status'];
                 
                 // Define the status progression: 'Pending' -> 'Preparing' -> 'Completed'
                 $new_status = match($current_status) {
                    'Pending' => 'Preparing',
                    'Preparing' => 'Completed',
                    default => null, // Stop progression if status is final
                 };
            }
            $stmt_fetch->close();
        } else {
             error_log("Error preparing status fetch statement: " . $conn->error);
        }

    } elseif ($action === 'cancel_order') {
        $new_status = 'Cancelled';
    }

    if ($new_status) {
        // --- 2b. Update status securely using prepared statement ---
        $update_sql = "UPDATE orders SET status = ? WHERE order_id = ?";
        $stmt_update = $conn->prepare($update_sql);

        if ($stmt_update) {
            $stmt_update->bind_param("ss", $new_status, $order_id);
            
            if ($stmt_update->execute()) {
                // Success: Redirect to refresh and preserve state
                $current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
                $current_sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
                header("Location: adminorders.php?filter=" . urlencode($current_filter) . "&sort=" . urlencode($current_sort) . "&success=status_updated");
                exit();
            } else {
                 error_log("Error executing update: " . $stmt_update->error);
                 echo "<script>console.error('Error executing update: " . $stmt_update->error . "');</script>";
            }
            $stmt_update->close();
        } else {
             error_log("Error preparing update statement: " . $conn->error);
             echo "<script>console.error('Error preparing update statement: " . $conn->error . "');</script>";
        }
    }
}

// --- 3. Execute Fetching and Prepare Sort Button Logic ---
// Get the current filters from the URL
$current_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$current_sort = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';

$orders = [];
$db_error = null;
if (isset($conn) && $conn->connect_error === null) {
    // Pass both filter and sort to the updated function
    $orders = fetchFilteredOrders($conn, $current_filter, $current_sort); 
} else {
    $db_error = "Database connection object (\$conn) not available or connection failed. Please check `../db_connect.php`.";
    if (isset($conn) && $conn->connect_error !== null) {
        $db_error .= " MySQL Error: " . $conn->connect_error;
    }
}

// Logic for the Alphabetical Sort Button (A-Z by Customer Name)
$is_sorted_by_customer = ($current_sort === 'customer_asc');

if ($is_sorted_by_customer) {
    // Currently sorted A-Z by Customer Name. Link should switch back to Date (Newest First).
    $alpha_sort_url = "adminorders.php?filter=" . urlencode($current_filter) . "&sort=date_desc";
    $alpha_sort_icon = "fa-clock"; // Icon indicating sorting by date/time (default)
    $alpha_sort_tooltip = "Currently: Customer Name (A-Z). Click to sort by Newest First.";
} else {
    // Currently sorted by Date. Link should switch to A-Z Customer Name sort.
    $alpha_sort_url = "adminorders.php?filter=" . urlencode($current_filter) . "&sort=customer_asc";
    $alpha_sort_icon = "fa-sort-alpha-down"; // Standard A-Z sort icon
    $alpha_sort_tooltip = "Sort by Customer Name (A-Z)";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/order.css">
    <link rel="stylesheet" href="../assets/css/navbar.css"> 
    <!-- Placeholder image URL is non-functional, replacing with standard favicon -->
    <link rel="icon" type="image/x-icon" href="https://placehold.co/16x16/000000/FFFFFF?text=☕">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Inline styles to ensure status badges look correct if CSS files are missing -->
    <style>
        /* ADDED STYLES FOR CONFIRMATION MODAL */
        .confirmation-modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); }
        .confirmation-modal-content { background-color: #fff; margin: 15% auto; padding: 25px; border-radius: 10px; width: 80%; max-width: 400px; box-shadow: 0 5px 25px rgba(0,0,0,0.5); text-align: center; }
        .confirmation-modal-actions { margin-top: 20px; }
        .confirmation-modal-actions button { margin: 0 10px; transition: background-color 0.2s, transform 0.1s; }
        .confirmation-modal-actions button:hover { transform: translateY(-1px); box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        /* END ADDED STYLES */

        .status-badge {
            display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; color: white;
        }
        /* Status classes mapped to DB ENUMs */
        .status-new { background-color: #3b82f6; } /* Pending */
        .status-accepted { background-color: #f59e0b; } /* Preparing */
        .status-completed { background-color: #059669; } /* Completed */
        .status-rejected { background-color: #ef4444; } /* Cancelled */
        .status-unknown { background-color: #6b7280; } /* Default */

        /* Modal styles included for basic functionality */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fff; margin: 5% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 600px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .close-btn { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; border: none; background: none; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
        .modal-info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem; flex-wrap: wrap; }
        .modal-info-row p { margin: 5px 0; }
        .order-items-list { list-style: disc; margin-left: 20px; padding-left: 0; margin-bottom: 15px; }
        .modal-status-update { border-top: 1px solid #eee; padding-top: 15px; margin-top: 15px; }
        .status-action-btn { padding: 8px 15px; border-radius: 6px; margin-right: 10px; cursor: pointer; font-weight: 600; transition: background-color 0.2s; }
        .update-btn { background-color: #1d4ed8; color: white; border: none; }
        .cancel-btn { background-color: #f87171; color: white; border: none; }
        .action-view-details:hover { cursor: pointer; color: #1d4ed8; }
    </style>
</head>
<body>

    <div class="page-container">
        <!-- Header Section -->
        <header class="main-header">
            <div class="logo">
                <i class="fas fa-coffee"></i>
            </div>
            
            <nav class="main-nav" id="main-nav">
                <ul>
                    <li><a href="dashboard.php" class="nav-link">DASHBOARD</a></li>
                    <li><a href="adminorders.php" class="nav-link active">ORDER</a></li>
                    <li><a href="inventory.php" class="nav-link">INVENTORY</a></li>
                    <li><a href="products.php" class="nav-link">PRODUCTS</a></li>
                    <li><a href="feedbacks.php" class="nav-link">FEEDBACK & REVIEW</a></li> 					 
                    <li class="mobile-logout">
                        <a href="#" class="nav-link logout-button-mobile">LOGOUT</a>
                    </li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <a href="#" class="logout-button">LOGOUT</a>
                
                <div class="burger-menu" id="burger-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
                <!-- Placeholder for profile image -->
                <img src="https://placehold.co/40x40/5a67d8/ffffff?text=ADM" alt="User Profile" class="profile-image" style="border-radius: 50%; object-fit: cover;">
                
                <div class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge">3</span>
                </div>
            </div>
        </header>

        <main class="orders-management">
            <h1 class="page-title">Customer Orders</h1>
            
            <!-- Database Error Display -->
            <?php if ($db_error): ?>
                <div style="padding: 15px; margin-bottom: 20px; background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 5px; font-weight: 500;">
                    <i class="fas fa-exclamation-triangle mr-2"></i> **DATABASE ERROR:** <?php echo htmlspecialchars($db_error); ?>
                    <br>Please ensure your `../db_connect.php` file is correctly set up and the database contains the required tables (`orders`, `users`).
                </div>
            <?php endif; ?>

            <div class="orders-card">
                <div class="order-controls">
                    <div class="filter-group">
                        <!-- ALPHABETICAL SORT BUTTON -->
                        <a href="<?php echo htmlspecialchars($alpha_sort_url); ?>" class="icon-btn" title="<?php echo htmlspecialchars($alpha_sort_tooltip); ?>">
                            <i class="fas <?php echo htmlspecialchars($alpha_sort_icon); ?>"></i>
                        </a>
                        
                        <!-- Filter button (currently cosmetic, could open a filter modal) -->
                        <button class="icon-btn"><i class="fas fa-filter"></i></button>
                        <button class="filter-btn">Filter</button>
                    </div>
                    
                    <!-- UPDATED DATE FILTER LOGIC: Preserves the current sort parameter -->
                    <div class="date-filter">
                        <select name="filter_period" onchange="window.location.href='adminorders.php?filter=' + this.value + '&sort=<?php echo urlencode($current_sort); ?>';">
                            <option value="all" <?php if ($current_filter === 'all') echo 'selected'; ?>>All Orders</option>
                            <option value="daily" <?php if ($current_filter === 'daily') echo 'selected'; ?>>Daily</option>
                            <option value="weekly" <?php if ($current_filter === 'weekly') echo 'selected'; ?>>Weekly</option>
                            <option value="monthly" <?php if ($current_filter === 'monthly') echo 'selected'; ?>>Monthly</option>
                        </select>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th class="col-order-id">ORDER ID</th>
                                <th>CUSTOMER</th>
                                <th>METHOD</th>
                                <th>PAYMENT / TIME SLOT (Assumed)</th>
                                <th class="col-created">CREATED</th>
                                <th class="col-status">LAST STATUS</th>
                                <th class="col-actions"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- PHP Loop to display dynamic order data -->
                            <?php if (count($orders) > 0): ?>
                                <?php foreach ($orders as $order): 
                                    $order_id_safe = htmlspecialchars($order['order_id']);
                                    $status_info = getStatusDisplay($order['status']);
                                    // Format timestamp
                                    $created_at = new DateTime($order['created_at']);
                                    $date_part = $created_at->format('d M Y');
                                    $time_part = $created_at->format('h:i A');

                                    // Determine customer name fallback
                                    $customer_display = $order['customer_name'] ?? 'Guest';
                                    if ($customer_display === 'Guest' && !empty($order['user_id'])) {
                                        $customer_display .= ' (ID: ' . htmlspecialchars($order['user_id']) . ')';
                                    }
                                ?>
                                <tr class="order-row" data-order-id="<?php echo $order_id_safe; ?>">
                                    <td class="order-id"><?php echo $order_id_safe; ?></td>
                                    <td><?php echo htmlspecialchars($customer_display); ?></td>
                                    <td><?php echo htmlspecialchars(ucwords($order['delivery_type'])); ?></td>
                                    <td>
                                        <!-- Using payment_method here as 'time_slot' column is missing -->
                                        <?php echo htmlspecialchars($order['payment_method'] ?? 'Immediately'); ?>
                                    </td>
                                    <td>
                                        <div class="date-time">
                                            Date: <?php echo $date_part; ?><br>
                                            Time: <?php echo $time_part; ?>
                                        </div>
                                    </td>
                                    <td class="status-cell">
                                        <span class="status-badge <?php echo $status_info['class']; ?>">
                                            <?php echo $status_info['text']; ?>
                                        </span>
                                    </td>
                                    <td class="actions-cell">
                                        <!-- Ellipsis icon to trigger the details modal via JavaScript -->
                                        <i class="fas fa-ellipsis-v action-view-details" data-order-id="<?php echo $order_id_safe; ?>"></i>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="order-row">
                                    <td colspan="7" style="text-align: center; padding: 20px; color: #777;">
                                        No customer orders found for the **<?php echo htmlspecialchars(ucwords($current_filter)); ?>** filter with the current sort applied.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Order Details Modal -->
    <div id="orderDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Order Details - <span id="modalOrderId">#---</span></h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-info-row">
                    <p><strong>Customer:</strong> <span id="modalCustomerName">Loading...</span></p>
                    <p><strong>Total Price:</strong> <span id="modalTotalAmount">P0.00</span></p>
                </div>
                <div class="modal-info-row">
                    <p><strong>Delivery Type:</strong> <span id="modalOrderMethod">Loading...</span></p>
                    <p><strong>Payment Method:</strong> <span id="modalTimeSlot">Loading...</span></p>
                </div>
                <div class="modal-info-row">
                    <p><strong>Created:</strong> <span id="modalCreatedDate">---</span> at <span id="modalCreatedTime">---</span></p>
                    <p><strong>Delivery Address:</strong> <span id="modalDeliveryAddress">N/A</span></p>
                </div>

                <hr>
                
                <h3>Items</h3>
                <!-- NOTE: Items list requires another table (e.g., order_items) and an AJAX call for real data -->
                <ul id="modalOrderItems" class="order-items-list">
                    <li>(Item list requires AJAX fetch from `order_items` table)</li>
                    </ul>
                
                <hr>

                <div class="modal-status-update">
                    <p><strong>Current Status:</strong> <span id="modalCurrentStatus" class="status-badge status-new">PENDING</span></p>
                    <div class="status-actions">
                        <!-- Form to advance the status (e.g., from Pending to Preparing) -->
                        <form method="POST" style="display: inline-block;" id="advanceStatusForm">
                            <input type="hidden" name="order_id" id="updateStatusInput" value="">
                            <input type="hidden" name="action" value="update_status">
                            <button type="submit" class="status-action-btn update-btn" id="advanceStatusButton">ADVANCE STATUS</button>
                        </form>
                        <!-- Form to cancel/reject the order -->
                        <form method="POST" style="display: inline-block;" id="cancelOrderForm">
                            <input type="hidden" name="order_id" id="cancelOrderInput" value="">
                            <input type="hidden" name="action" value="cancel_order">
                            <button type="button" class="status-action-btn cancel-btn" id="cancelOrderButton">
                                REJECT/CANCEL ORDER
                            </button>
                        </form>
                    </div>
                    <div id="statusMessage" style="color:#777; font-style:italic; margin-top:10px; display:none;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="confirmation-modal">
        <div class="confirmation-modal-content">
            <h3 id="confirmationModalTitle">Confirm Action</h3>
            <p id="confirmationModalText">Are you sure?</p>
            <div class="confirmation-modal-actions" id="confirmationModalActions">
                
            </div>
        </div>
    </div>

    <!-- External JS files are included at the end -->
    <!-- Assuming these files exist. If not, the application needs them. -->
    <script src="../assets/js/Navbar.js"></script>
    <script src="../assets/js/order.js"></script> 
    
    <!-- Inline script to hook up action buttons to forms/modal -->
    <script>
        // Custom confirmation modal logic (replaces alert() and confirm())
        function showConfirmationModal(title, text, confirmCallback) {
            const modal = document.getElementById('confirmationModal');
            document.getElementById('confirmationModalTitle').textContent = title;
            document.getElementById('confirmationModalText').textContent = text;
            const actions = document.getElementById('confirmationModalActions');
            actions.innerHTML = '';

            const confirmBtn = document.createElement('button');
            confirmBtn.textContent = 'Confirm';
            confirmBtn.className = 'status-action-btn cancel-btn'; 
            confirmBtn.onclick = () => {
                modal.style.display = 'none';
                confirmCallback();
            };

            const cancelBtn = document.createElement('button');
            cancelBtn.textContent = 'Cancel';
            cancelBtn.className = 'status-action-btn update-btn';
            cancelBtn.style.backgroundColor = '#ccc';
            cancelBtn.style.color = '#333';
            cancelBtn.onclick = () => {
                modal.style.display = 'none';
            };

            actions.appendChild(cancelBtn);
            actions.appendChild(confirmBtn);
            modal.style.display = 'block';
        }


        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('orderDetailsModal');
            const closeBtn = modal.querySelector('.close-btn');
            const actionViewDetails = document.querySelectorAll('.action-view-details');
            const updateStatusInput = document.getElementById('updateStatusInput');
            const cancelOrderInput = document.getElementById('cancelOrderInput');
            const cancelOrderButton = document.getElementById('cancelOrderButton'); 
            const advanceStatusButton = document.getElementById('advanceStatusButton');
            const statusMessage = document.getElementById('statusMessage');

            // Attach event listener for the cancel button with confirmation
            cancelOrderButton.addEventListener('click', (e) => {
                e.preventDefault();
                const orderId = cancelOrderInput.value;
                showConfirmationModal(
                    'Confirm Order Cancellation', 
                    `Are you sure you want to REJECT/CANCEL order #${orderId}? This action cannot be undone.`,
                    () => {
                        // Submit the form if confirmed
                        document.getElementById('cancelOrderForm').submit();
                    }
                );
            });

            // Attach event listener for the advance button with confirmation (optional, but good practice)
             advanceStatusButton.addEventListener('click', (e) => {
                e.preventDefault();
                const orderId = updateStatusInput.value;
                const nextAction = advanceStatusButton.textContent;
                showConfirmationModal(
                    'Confirm Status Change', 
                    `Are you sure you want to proceed and change the status of order #${orderId} to '${nextAction}'?`,
                    () => {
                        // Submit the form if confirmed
                        document.getElementById('advanceStatusForm').submit();
                    }
                );
            });


            // --- Client-Side Mock Data & Modal Interaction ---
            function getOrderDetails(orderId) {
                // Find the corresponding row in the visible table to grab the current status and general info
                const row = document.querySelector(`.order-row[data-order-id='${orderId}']`);
                const statusBadge = row.querySelector('.status-badge');
                
                // MOCK data for the details modal (replace with server-side fetch in a production app)
                const mockOrder = {
                    customer_name: row.cells[1].textContent.trim(),
                    delivery_type: row.cells[2].textContent.trim(),
                    payment_method: row.cells[3].textContent.trim(),
                    // Mocking other required fields
                    total_price: "P" + (Math.random() * 500 + 100).toFixed(2),
                    delivery_address: row.cells[2].textContent.trim().toLowerCase() === 'delivery' ? '123 Coffee Lane, City (Mock Address)' : 'In-Store Pickup',
                    current_status_text: statusBadge.textContent.trim(),
                    current_status_class: statusBadge.className.replace('status-badge', '').trim(),
                    
                    items: [
                        { name: "Latte (Large)", quantity: 1, price: 150.00 },
                        { name: "Blueberry Muffin", quantity: 1, price: 85.00 },
                    ]
                };
                return mockOrder;
            }

            // --- Open Modal Handler ---
            actionViewDetails.forEach(icon => {
                icon.addEventListener('click', (e) => {
                    const orderId = e.target.getAttribute('data-order-id');
                    const details = getOrderDetails(orderId);
                    
                    // 1. Populate Hidden Inputs for Status Forms
                    updateStatusInput.value = orderId;
                    cancelOrderInput.value = orderId;

                    // 2. Populate Modal Content
                    document.getElementById('modalOrderId').textContent = '#' + orderId;
                    document.getElementById('modalCustomerName').textContent = details.customer_name;
                    document.getElementById('modalOrderMethod').textContent = details.delivery_type;
                    document.getElementById('modalTimeSlot').textContent = details.payment_method;
                    document.getElementById('modalTotalAmount').textContent = details.total_price;
                    document.getElementById('modalDeliveryAddress').textContent = details.delivery_address;
                    
                    // Populate Date/Time
                    const dateCellContent = e.target.closest('.order-row').querySelector('.date-time').innerHTML;
                    const dateMatch = dateCellContent.match(/Date: (.*?)<br>/);
                    const timeMatch = dateCellContent.match(/Time: (.*?)\s*$/);

                    if (dateMatch && timeMatch) {
                        document.getElementById('modalCreatedDate').textContent = dateMatch[1].trim();
                        document.getElementById('modalCreatedTime').textContent = timeMatch[1].trim();
                    }


                    // 3. Update the current status badge
                    const currentStatusSpan = document.getElementById('modalCurrentStatus');
                    currentStatusSpan.className = 'status-badge ' + details.current_status_class;
                    currentStatusSpan.textContent = details.current_status_text;

                    // 4. Populate Items List (using mock list for now)
                    const itemsList = document.getElementById('modalOrderItems');
                    itemsList.innerHTML = '';
                    details.items.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = `${item.quantity}x ${item.name} (P${item.price.toFixed(2)})`;
                        itemsList.appendChild(li);
                    });

                    // 5. Update Status Action Buttons visibility/state (UX Improvement)
                    statusMessage.style.display = 'none';

                    if (details.current_status_text === 'Completed' || details.current_status_text === 'Cancelled') {
                        // Order is final
                        advanceStatusButton.style.display = 'none';
                        cancelOrderButton.style.display = 'none';
                        statusMessage.textContent = details.current_status_text === 'Cancelled' 
                            ? 'This order was cancelled and cannot be reopened.'
                            : 'This order has been completed.';
                        statusMessage.style.display = 'block';
                    } else if (details.current_status_text === 'Preparing') {
                        // Next action is 'Completed'
                        advanceStatusButton.textContent = 'MARK AS COMPLETED';
                        advanceStatusButton.style.display = 'inline-block';
                        cancelOrderButton.style.display = 'inline-block';
                    } else if (details.current_status_text === 'Pending') {
                        // Next action is 'Preparing'
                        advanceStatusButton.textContent = 'START PREPARING';
                        advanceStatusButton.style.display = 'inline-block';
                        cancelOrderButton.style.display = 'inline-block';
                    }
                    
                    // Display the modal
                    modal.style.display = 'block';
                });
            });

            // --- Close Modal Handler ---
            closeBtn.onclick = () => {
                modal.style.display = 'none';
            };

            // Close modal when clicking outside
            window.onclick = (event) => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
                if (event.target == document.getElementById('confirmationModal')) {
                    document.getElementById('confirmationModal').style.display = 'none';
                }
            };
        });
    </script>
</body>
</html>
