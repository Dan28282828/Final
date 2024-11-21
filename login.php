<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "safe_haven";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit();
}

$stmt = $conn->prepare("SELECT id, password, role, status FROM users WHERE username = ?");
$stmt->bind_param("s", $data['username']);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($userId, $storedPassword, $role, $status);
    $stmt->fetch();

    if ($status === 'disabled') {
        echo json_encode(['success' => false, 'message' => 'Your account is disabled. Please contact support.']);
        $stmt->close();
        $conn->close();
        exit();
    }

    if ($data['password'] === $storedPassword) {
       
        $loginTime = date('Y-m-d H:i:s');
        $logQuery = $conn->prepare("INSERT INTO activity_log (user_id, username, login_time) VALUES (?, ?, ?)");
        $logQuery->bind_param("iss", $userId, $data['username'], $loginTime);
        $logQuery->execute();

        echo json_encode(['success' => true, 'is_admin' => $role === 'admin']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username not found.']);
}

$stmt->close();
$conn->close();
?>
