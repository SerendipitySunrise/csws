<?php
include('../includes/db_connect.php'); 
session_start(); 

// SECURITY: Simple check to ensure only logged-in admins can access this page
// TODO: Replace this with your actual, robust authentication logic (e.g., checking user role).
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // header('Location: admin_login.php'); // Uncomment this line to enforce login
    // exit();
}

// --- Utility Function: Render Stars ---
function render_stars($rating) {
    $output = '';
    $max_stars = 5;
    $rating = max(0, min($max_stars, intval($rating))); // Ensure rating is between 0 and 5

    for ($i = 1; $i <= $max_stars; $i++) {
        if ($i <= $rating) {
            // Full star (rated)
            $output .= '<i class="fas fa-star full-star"></i>';
        } else {
            // Empty star (unrated)
            $output .= '<i class="far fa-star empty-star"></i>';
        }
    }
    return $output;
}


// --- 1. Feedback Deletion Logic ---
$message = '';
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "<div class='alert success'>Feedback ID {$delete_id} deleted successfully.</div>";
        // Redirect to remove the query parameter after deletion
        header('Location: feedbacks.php?deleted=true');
        exit();
    } else {
        $message = "<div class='alert error'>Error deleting feedback: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Check for the success message after redirect
if (isset($_GET['deleted']) && $_GET['deleted'] === 'true') {
    $message = "<div class='alert success'>Feedback deleted successfully.</div>";
}


// --- 2. Fetch All Feedbacks ---
$feedback = [];
// CORRECTED SQL to match your new schema
$sql = "SELECT feedback_id, user_id, rating, comment, created_at 
        FROM feedback
        ORDER BY created_at DESC";
        
$result = $conn->query($sql);

if ($result === false) {
    $db_error = "SQL Error: " . $conn->error;
    error_log($db_error);
    $message = "<div class='alert error'>Error fetching data: {$conn->error}</div>";
} elseif ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $feedback[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedbacks - Admin</title>
    <link rel="stylesheet" href="../assets/css/navbar.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        /* General Layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #1c1c1c; /* Dark background */
            color: #ccc;
            margin: 0;
        }

        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 60px; /* Adjust based on navbar height */
        }

        .feedback-main {
            max-width: 1000px;
            width: 95%;
            margin: 20px auto;
            padding: 20px;
            background-color: #2e2e2e; /* Card background */
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        }

        .page-title {
            color: #e67e22; /* Accent color */
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 25px;
            border-bottom: 2px solid #3a3a3a;
            padding-bottom: 10px;
        }

        /* Message Alerts */
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .success {
            background-color: #2ecc71;
            color: #1e8449;
        }
        .error {
            background-color: #e74c3c;
            color: #c0392b;
        }

        /* Feedback Card Styling */
        .feedback-list {
            display: grid;
            gap: 20px;
        }

        .feedback-card {
            background-color: #3a3a3a;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #e67e22;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #555;
        }

        .feedback-user-info strong {
            color: #fff;
            font-size: 1.1em;
        }
        
        .feedback-rating {
            font-size: 1.1em;
        }
        
        .full-star {
            color: #ffc107; /* Gold color for rated stars */
        }
        
        .empty-star {
            color: #777; /* Gray color for empty stars */
        }

        .feedback-info span {
            font-size: 0.8em;
            color: #aaa;
        }

        .feedback-message {
            margin-top: 10px;
            padding: 10px;
            background-color: #2e2e2e;
            border-radius: 5px;
            white-space: pre-wrap; /* Preserve formatting in the message */
        }
        
        .feedback-message p {
            margin: 0;
            color: #ccc;
        }

        .feedback-actions {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .delete-btn {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 1.2em;
            transition: color 0.1s;
        }

        .delete-btn:hover {
            color: #c0392b;
        }
        
        /* No Feedback State */
        .no-feedback {
            text-align: center;
            padding: 50px;
            color: #999;
            font-style: italic;
            background-color: #3a3a3a;
            border-radius: 8px;
        }

    </style>
    
    <script>
        function confirmDeletion(id) {
            if (confirm("Are you sure you want to permanently delete Feedback ID " + id + "? This action cannot be undone.")) {
                window.location.href = 'feedbacks.php?delete_id=' + id;
            }
        }
    </script>
</head>
<body>
    <header class="main-header">
        <nav class="main-nav">
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_orders.php">Orders</a></li>
                <li><a href="admin_menu.php">Menu</a></li>
                <li><a href="inventory.php">Inventory</a></li>
                <li><a href="feedbacks.php" class="active">Feedbacks</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="page-container">
        <main class="feedback-main">
            <h1 class="page-title">Customer Feedbacks</h1>
            
            <?php echo $message; // Display any success or error message ?>

            <div class="feedback-list">
                <?php if (empty($feedback)): ?>
                    <p class="no-feedback">
                        <i class="fas fa-inbox"></i> No customer feedback has been submitted yet.
                    </p>
                <?php else: ?>
                    <?php foreach ($feedback as $feedback): ?>
                        <div class="feedback-card">
                            <div class="feedback-actions">
                                <button 
                                    class="delete-btn" 
                                    title="Delete Feedback"
                                    onclick="confirmDeletion(<?php echo $feedback['feedback_id']; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            
                            <div class="feedback-header">
                                <div class="feedback-user-info">
                                    <strong>User ID #<?php echo htmlspecialchars($feedback['user_id']); ?></strong> 
                                </div>
                                <div class="feedback-rating">
                                    <?php echo render_stars($feedback['rating']); ?>
                                </div>
                            </div>
                            
                            <div class="feedback-info">
                                <span>Feedback ID: <?php echo htmlspecialchars($feedback['feedback_id']); ?></span>
                                <span>Submitted: <?php echo date('M j, Y h:i A', strtotime($feedback['created_at'])); ?></span>
                            </div>
                            
                            <div class="feedback-message">
                                <p><?php echo nl2br(htmlspecialchars($feedback['comment'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
