<?php
session_start();

// 1. Include database connection
require __DIR__ . '/db_connect.php';

// 2. Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['status' => 'error', 'message' => 'Method not allowed']));
}

// 3. Set JSON header
header('Content-Type: application/json');

try {
    // 4. Validate inputs
    if (empty($_POST['email'] ?? '')) {
        throw new Exception('Email is required');
    }
    if (empty($_POST['password'] ?? '')) {
        throw new Exception('Password is required');
    }

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // 5. Database query
    $stmt = $conn->prepare("SELECT customer_id, first_name, last_name, password 
                          FROM customers 
                          WHERE email = ?");
    if (!$stmt) {
        throw new Exception('Database error');
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // 6. Check user exists
    if ($result->num_rows === 0) {
        throw new Exception('Invalid email or password');
    }

    $user = $result->fetch_assoc();

    // 7. Verify password
    if (!password_verify($password, $user['password'])) {
        throw new Exception('Invalid email or password');
    }

    // 8. Set session data
    $_SESSION = [
        'user_id' => $user['customer_id'],
        'user_name' => $user['first_name'] . ' ' . $user['last_name'],
        'logged_in' => true
    ];

    // 9. Return success response
    echo json_encode([
        'status' => 'success',
        'redirect' => 'dashboard.php'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
exit();
?>