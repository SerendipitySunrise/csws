<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Product Management</title>
    <link rel="stylesheet" href="assets/css/admin.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Specific styles for the product management page */
        .page-content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-content h2 {
            margin: 25px 0 15px 0;
            font-size: 1.8em;
            color: var(--text-light);
        }

        /* --- Add New Product Button --- */
        .add-product-btn {
            display: inline-flex;
            align-items: center;
            background-color: var(--orange);
            color: var(--bg-dark);
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 25px;
            transition: background-color 0.3s;
        }

        .add-product-btn i {
            margin-right: 8px;
        }

        .add-product-btn:hover {
            background-color: #e68a2e; 
        }

        /* --- Table Container --- */
        .table-wrapper {
            background-color: var(--card-bg);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* --- Table Header/Search/Filter --- */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 20px;
        }

        .search-box {
            position: relative;
            flex-grow: 1;
            max-width: 400px;
        }

        .search-box i {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: var(--text-faded);
        }

        .search-box input {
            width: 100%;
            padding: 12px 12px 12px 40px; /* Padding for the icon */
            background-color: #383838;
            border: none;
            border-radius: 4px;
            color: var(--text-light);
            font-size: 1em;
            outline: none;
        }
        
        .filter-select {
            background-color: #383838;
            color: var(--text-light);
            padding: 12px 15px;
            border-radius: 4px;
            border: none;
            font-size: 1em;
            min-width: 150px;
            text-align: center;
            -webkit-appearance: none; /* Removes default dropdown arrow in Chrome */
            -moz-appearance: none; /* Removes default dropdown arrow in Firefox */
            appearance: none; /* Removes default dropdown arrow */
            /* Note: The design uses a simple text field, so a simple input/text is used instead of a true select box */
        }
        
        .category-filter {
            background-color: #202020;
            padding: 10px 15px;
            border-radius: 4px;
            color: var(--text-light);
            font-size: 1em;
            border: 1px solid #444;
        }


        /* --- Product Table --- */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            color: var(--text-light);
        }

        .product-table th, .product-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #444; /* Dark separator line */
        }

        .product-table th {
            color: var(--text-faded);
            font-size: 0.9em;
            font-weight: 500;
            text-transform: uppercase;
        }

        .product-table tr:hover {
            background-color: #353535; /* Subtle hover effect */
        }

        .product-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Specific column alignments */
        .product-table td:nth-child(4), /* Price column */
        .product-table td:nth-child(5), /* Availability column */
        .product-table td:nth-child(6) /* Actions column */
        {
            text-align: center;
        }


        /* --- Action Elements --- */
        .edit-btn {
            background-color: #444;
            color: var(--text-light);
            padding: 5px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 10px;
        }

        .edit-btn:hover {
            background-color: #555;
        }

        /* --- Toggle Switch Styling (Reused from previous example, but important here) --- */
        .switch {
            position: relative;
            display: inline-block;
            width: 45px; /* Width of the switch */
            height: 25px; /* Height of the switch */
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--red-critical); /* Red (OFF state) */
            transition: 0.4s;
            border-radius: 25px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 17px;
            width: 17px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--green-up); /* Green (ON state) */
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }

        /* Specific text styles for availability */
        .availability-on {
            color: var(--green-up);
        }
        
        .availability-off {
            color: var(--text-faded);
        }

        .sold-text {
            color: var(--text-faded);
            font-size: 0.9em;
        }
    </style>
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
        <h2>PRODUCT MANAGEMENT</h2>

        <a href="#" class="add-product-btn">
            <i class="fas fa-plus"></i> ADD NEW PRODUCT
        </a>

        <div class="table-wrapper">
            
            <div class="table-controls">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search by name...">
                </div>
                <div class="category-filter">Category: All</div>
            </div>

            <table class="product-table">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>CATEGORY</th>
                        <th>PRICE</th>
                        <th>AVAILABILITY</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Iced Caramel Macchiato</td>
                        <td>Espresso</td>
                        <td>$4.95</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <button class="edit-btn">Edit</button>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Cold Brew Brewed Coffee</td>
                        <td>$4.25</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>Pastry</td>
                        <td>
                            <button class="edit-btn">Edit</button>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Cold Brew</td>
                        <td>$3.25 <span class="sold-text">95 sold</span></td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td></td> <td>
                            <button class="edit-btn">Edit</button>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Cinnamon Swirl Pastiry</td>
                        <td>$5.50</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td></td>
                        <td>
                            <button class="edit-btn">Edit</button>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Decaf Americano</td>
                        <td>$3.75</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td></td>
                        <td>
                            <button class="edit-btn">Edit</button>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                            <span class="availability-off">OFF</span>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>

    </main>

    </body>
</html>
