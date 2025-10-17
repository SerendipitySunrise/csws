<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Dashboard</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php
        // include 'config.php';
        // session_start();
        // if (!isset($_SESSION['user_id'])) {
        //     header('Location: login.php');
        //     exit();
        // }
    ?>
    
    <header class="navbar">
        <div class="logo-container">
            <i class="fas fa-mug-hot"></i> <span class="logo-text">ADMIN PANEL</span>
        </div>
        <nav>
            <a href="#" class="nav-item">Dashboard</a>
            <a href="#" class="nav-item">Orders</a>
            <a href="#" class="nav-item">Inventory</a>
            <a href="#" class="nav-item">Staff</a>
        </nav>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <main class="dashboard-content">
        <h2>TODAY'S OVERVIEW</h2>

        <div class="cards-container">
            <div class="card sales-card">
                <h3>TOTAL SALES TODAY</h3>
                <p class="main-metric">$1,250.85</p>
                <p class="sub-metric increase">
                    <i class="fas fa-caret-up"></i> +12% vs. Yesterday
                </p>
            </div>

            <div class="card orders-card">
                <h3>NEW ORDERS TODAY</h3>
                 <p class="main-metric">58</p>
            </div>
        </div>
        
        <div class="secondary-info-container">
            <div class="card top-products-card">
                <h3>TOP 5 SELLING PRODUCTS</h3>
                <div class="product-list">
                    <div class="product-item">
                        <span class="product-name">Iced Caramel Macchiato</span>
                        <span class="product-sales"> (120 sold)</span>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="product-item">
                        <span class="product-name">Cinnamon Swirl</span>
                        <span class="product-sales"> (95 sold)</span>
                        <div class="progress-bar-container">
                             <div class="progress-bar" style="width: 79%;"></div> 
                        </div>
                    </div>
                    <div class="product-item">
                        <span class="product-name">Cold Brew</span>
                        <span class="product-sales"> (70 sold)</span>
                        <div class="progress-bar-container">
                             <div class="progress-bar" style="width: 58%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card low-stock-card">
                <h3>LOW STOCK ALERTS</h3>
                <p class="stock-item critical">Oat Milk - <span class="status-critical">CRITICAL</span></p>
                <p class="stock-item low">Espresso Beans - <span class="status-low">LOW</span></p>
                <p class="stock-item low">Chai Syrup - <span class="status-low">LOW</span></p>
            </div>
        </div>
    </main>

    </body>
</html>
