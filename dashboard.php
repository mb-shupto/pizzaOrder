<?php
session_start();
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.html");
    exit();
}

// Get user details
$stmt = $conn->prepare("SELECT first_name, last_name, email, address, phone_number FROM customers WHERE customer_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Pizza Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #e63946;
            --secondary-color: #f1faee;
            --dark-color: #1d3557;
        }
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: var(--dark-color);
        }
        .sidebar {
            background-color: var(--secondary-color);
            min-height: calc(100vh - 56px);
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-pizza {
            background-color: var(--primary-color);
            border: none;
        }
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-color) 100%);
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">MegaBytes Pizza</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Welcome, <?php echo htmlspecialchars($user['first_name']); ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar p-4">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="order_pizza.php" class="list-group-item list-group-item-action">Order Pizza</a>
                    <a href="order_history.php" class="list-group-item list-group-item-action">Order History</a>
                    <a href="profile.php" class="list-group-item list-group-item-action">My Profile</a>
                </div>
            </div>
            <div class="col-md-9 p-4">
                <div class="welcome-banner p-4 mb-4">
                    <h2>Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
                    <p class="mb-0">Ready for your next delicious pizza?</p>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Quick Order</h5>
                                <form action="process_order.php" method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Pizza Type</label>
                                        <select class="form-select" name="pizza_type">
                                            <option value="margherita">Margherita ($10.99)</option>
                                            <option value="pepperoni">Pepperoni ($12.99)</option>
                                            <option value="vegetarian">Vegetarian ($8.99)</option>
                                            <option value="sausage">Sausage ($8.99)</option>
                                            <option value="chicken delight">Chicken Delight ($10.99)</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Size</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="size" id="small" value="small" checked>
                                                <label class="form-check-label" for="small">Small</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="size" id="medium" value="medium">
                                                <label class="form-check-label" for="medium">Medium (+$2)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="size" id="large" value="large">
                                                <label class="form-check-label" for="large">Large (+$4)</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="size" id="extraLarge" value="extraarge">
                                                <label class="form-check-label" for="large">Extra Large (+$6)</label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-pizza">Order Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">My Information</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                    <strong>Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . htmlspecialchars($user['last_name'])) ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number']); ?>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?>
                                    </li>
                                </ul>
                                <a href="profile.php" class="btn btn-outline-primary mt-3">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Orders</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- PHP would fetch real orders here -->
                                <tr>
                                    <td colspan="5" class="text-center">No recent orders</td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="order_history.php" class="btn btn-pizza">View All Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>