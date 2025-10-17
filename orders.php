<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Orders Management</title>
    <!-- Link to the custom CSS for this page -->
    <link rel="stylesheet" href="orders.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
    <header class="navbar">
        <div class="logo-container">
            <i class="fas fa-mug-hot"></i>
            <span class="logo-text">ADMIN PANEL</span>
        </div>
        <nav>
            <a href="dashboard.html" class="nav-item">Dashboard</a>
            <a href="#" class="nav-item">Orders</a>
            <a href="#" class="nav-item">Inventory</a>
            <a href="#" class="nav-item">Staff</a>
        </nav>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <main class="page-content">
        <h2>ORDERS MANAGEMENT</h2>

        <!-- The 'Add New Product' button seems to be an error in the design for an Orders page,
             but I'll keep the styling and text as shown in the image. -->
        <a href="#" class="add-product-btn">
            <i class="fas fa-plus"></i> ADD NEW PRODUCT
        </a>

        <!-- Table Wrapper (The dark card) -->
        <div class="table-wrapper">
            
            <!-- Search and Filter Controls -->
            <div class="table-controls">
                
                <!-- Search Box 1: General Search -->
                <div class="search-box general-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search">
                </div>

                <!-- Filter by Status -->
                <div class="filter-group">
                    <div class="filter-label">Filter by Status</div>
                    <div class="filter-options">
                        <!-- Radio buttons for status filters -->
                        <div class="status-option">
                            <input type="radio" id="pending" name="status-filter" checked>
                            <label for="pending">Pending</label>
                        </div>
                        <div class="status-option">
                            <input type="radio" id="completed" name="status-filter">
                            <label for="completed">Completed</label>
                        </div>
                    </div>
                </div>

                <!-- Search Box 2: Search by name or ID -->
                <div class="search-box id-search">
                    <input type="text" placeholder="Search by name or ID">
                </div>
            </div>

            <!-- Orders Table -->
            <table class="orders-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">ORDER ID</th>
                        <th style="width: 15%;">CATEGORY</th>
                        <th style="width: 15%;">ITEMS</th>
                        <th style="width: 25%;">CUSTOMER/DETAILS</th>
                        <th style="width: 10%;">STATUS</th>
                        <th style="width: 15%;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Row 1: Preparing Order -->
                    <tr>
                        <td>ID #2023-00128</td>
                        <td>Espresso</td>
                        <td>
                            <span class="item-count">2x Cold Brew Macchiato</span><br>
                            <span class="item-count">1x Everything Bagel</span>
                        </td>
                        <td>Joshua Chen</td>
                        <td>
                            <span class="status-tag preparing">PREPARING</span>
                        </td>
                        <td>$21.75</td>
                    </tr>
                    <!-- Example Row 2: Completed Order -->
                    <tr>
                        <td>ID #2023-00127</td>
                        <td>Pastry</td>
                        <td><span class="item-count">3x Cinnamon Rolls</span></td>
                        <td>Jane Doe</td>
                        <td>
                            <span class="status-tag completed">COMPLETED</span>
                        </td>
                        <td>$10.50</td>
                    </tr>
                    <!-- Example Row 3: With Action Buttons (Placeholder Row to match button placement) -->
                    <tr>
                        <td>ID #2023-00126</td>
                        <td>Drip</td>
                        <td><span class="item-count">1x Decaf Americano</span></td>
                        <td>-</td>
                        <td>
                            <span class="status-tag pending">PENDING</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn update-btn">UPDATE STATUS</button>
                                <button class="btn cancel-btn">CANCEL ORDER</button>
                            </div>
                        </td>
                    </tr>
                    <!-- Placeholder Row to match the original image's density, adjusting columns to match data -->
                    <tr>
                        <td>Joshua Chen</td>
                        <td>$4.95</td>
                        <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                        <td>2x Cold Brew Macchiato</td>
                        <td>
                            <span class="status-tag preparing">PREPARING</span>
                        </td>
                        <td>$21.50</td>
                    </tr>
                    <tr>
                        <td>Cold Brew Brwed Coffee</td>
                        <td>$4.25</td>
                        <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                        <td>1x Everything Bagel</td>
                        <td></td>
                        <td>$11.50</td>
                    </tr>
                    <tr>
                        <td>Cinnamon Swirl Pastiry</td>
                        <td>$3.50</td>
                        <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                        <td></td>
                        <td><span class="status-tag completed">COMPLETED</span></td>
                        <td>$3.75</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>
