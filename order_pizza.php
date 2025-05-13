<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Pizza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include same styles as dashboard -->
</head>
<body>
    <!-- Similar navbar as dashboard -->
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Build Your Pizza</h4>
                    </div>
                    <div class="card-body">
                        <form action="process_order.php" method="post">
                            <!-- Pizza selection form -->
                            <div class="mb-3">
                                <label class="form-label">Pizza Type</label>
                                <select class="form-select" name="pizza_type" required>
                                    <option value="" selected disabled>Select a pizza</option>
                                    <option value="margherita">Margherita ($10.99)</option>
                                    <option value="pepperoni">Pepperoni ($12.99)</option>
                                    <!-- More options -->
                                </select>
                            </div>
                            
                            <!-- Size selection -->
                            <div class="mb-3">
                                <label class="form-label">Size</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="size" id="small" value="small" checked>
                                    <label class="form-check-label" for="small">Small (10")</label>
                                </div>
                                <!-- Medium and large options -->
                            </div>
                            
                            <!-- Toppings -->
                            <div class="mb-3">
                                <label class="form-label">Toppings ($1 each)</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="toppings[]" id="pepperoni" value="pepperoni">
                                    <label class="form-check-label" for="pepperoni">Pepperoni</label>
                                </div>
                                <!-- More toppings -->
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>