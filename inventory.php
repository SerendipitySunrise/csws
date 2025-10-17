<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Inventory Management</title>
    <!-- Link to the custom CSS for this page -->
    <link rel="stylesheet" href="inventory.css">
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
        <h2>INVENTORY MANAGEMENT</h2>

        <!-- Update Stock Button -->
        <a href="#" class="update-stock-btn">
            <i class="fas fa-arrow-up"></i> UPDATE STOCK MANUALLY
        </a>

        <!-- Table Wrapper (The dark card) -->
        <div class="table-wrapper">
            
            <!-- Search and Filter Controls -->
            <div class="table-controls">
                
                <!-- Search Box -->
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search by name...">
                </div>

                <!-- Title for the main table block -->
                <h3 class="current-stock-title">Current Stock Levels</h3>

                <!-- Category Filter -->
                <div class="category-filter">
                    <label for="category-select">Category</label>
                    <select id="category-select" class="filter-select">
                        <option value="all">All</option>
                        <option value="dairy">Dairy</option>
                        <option value="coffee">Coffee Beans</option>
                        <option value="syrup">Syrups</option>
                    </select>
                </div>
            </div>

            <!-- Inventory Table -->
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">NAME</th>
                        <th style="width: 15%;">CATEGORY</th>
                        <th style="width: 15%;">ITEMS</th>
                        <th style="width: 20%;">PAR LEVEL</th>
                        <th style="width: 15%;">STATUS</th>
                        <th style="width: 10%;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP: Loop through inventory items -->
                    <tr>
                        <td>Oat Milk</td>
                        <td>5 GAL</td>
                        <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                        <td>10 GAL Pastry</td>
                        <td><span class="status-tag critical">CRITICAL - REORDER</span></td>
                        <td class="action-icons">
                            <i class="fas fa-edit edit-icon"></i>
                            <i class="fas fa-history history-icon"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>Espresso Beans</td>
                        <td>2 KG</td>
                        <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                        <td>5 KG</td>
                        <td><span class="status-tag low">LOW - ORDER SOON</span></td>
                        <td class="action-icons">
                            <i class="fas fa-edit edit-icon"></i>
                            <i class="fas fa-history history-icon"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>Whole Stuo Beans</td>
                        <td>2 KG</td>
                        <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                        <td>5 GAL</td>
                        <td><span class="status-tag good">GOOD</span></td>
                        <td class="action-icons">
                            <i class="fas fa-edit edit-icon"></i>
                            <i class="fas fa-history history-icon"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>Vanilla Syrup</td>
                        <td>12 KG</td>
                        <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                        <td>5 BOTTLE</td>
                        <td><span class="status-tag good">GOOD</span></td>
                        <td class="action-icons">
                            <i class="fas fa-edit edit-icon"></i>
                            <i class="fas fa-history history-icon"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>Cinnamon Swirls</td>
                        <td>6 CT</td>
                        <td><label class="switch"><input type="checkbox" checked><span class="slider"></span></label></td>
                        <td>5 GON</td>
                        <td></td> <!-- Empty Status field -->
                        <td class="action-icons">
                            <i class="fas fa-edit edit-icon"></i>
                            <i class="fas fa-history history-icon"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Low Stock Alerts Panel (Separate dark box at the bottom) -->
        <div class="low-stock-alerts-panel">
            <h4>LOW STOCK ALERTS:</h4>
            <p>Oat Milk, Espresso Beans</p>
        </div>

    </main>

</body>
</html>
