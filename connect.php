<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required = ['firstName', 'lastName', 'email', 'password', 'address', 'phoneNumber', 'gender'];
    $errors = [];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst($field) . " is required";
        }
    }
    
    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate gender
    if (!in_array($_POST['gender'], ['m', 'f', 'o'])) {
        $errors[] = "Invalid gender selection";
    }
    
    // If errors exist, display them
    if (!empty($errors)) {
        die("<div class='alert alert-danger'><ul><li>" . implode("</li><li>", $errors) . "</li></ul></div>");
    }
    
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'pizzaorder');
    
    if ($conn->connect_error) {
        die("<div class='alert alert-danger'>Connection failed: " . $conn->connect_error . "</div>");
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT email FROM customers WHERE email = ?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("<div class='alert alert-danger'>This email is already registered</div>");
    }
    $stmt->close();
    
    // Hash password
    $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Insert new customer
    $stmt = $conn->prepare("INSERT INTO customers 
        (first_name, last_name, email, password, address, phone_number, gender) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssss", 
        $_POST['firstName'],
        $_POST['lastName'],
        $_POST['email'],
        $hashedPassword,
        $_POST['address'],
        $_POST['phoneNumber'],
        $_POST['gender']
    );
    
    if ($stmt->execute()) {
        // Success - redirect or show message
        echo "<div class='alert alert-success'>Registration successful! Welcome to our pizza family!</div>";
        // You could redirect with: header("Location: welcome.php");
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Not a POST request
    header("Location: index.html");
    exit();
}
?>