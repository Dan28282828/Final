<?php
session_start();
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

if (!isset($_SESSION['user_id'], $_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'No active session.']);
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$logoutTime = date('Y-m-d H:i:s');

if (!$userId || !$username) {
    echo json_encode(['success' => false, 'message' => 'Session variables are missing.', 'user_id' => $userId, 'username' => $username]);
    exit();
}

echo json_encode(['debug' => 'Logged in user:', 'user_id' => $userId, 'username' => $username]);

$updateLogQuery = $conn->prepare("UPDATE activity_log SET logout_time = ? WHERE user_id = ? AND username = ? AND logout_time IS NULL ORDER BY id DESC LIMIT 1");
$updateLogQuery->bind_param("sis", $logoutTime, $userId, $username);

if ($updateLogQuery->execute()) {
   
    if ($updateLogQuery->affected_rows > 0) {
        
        echo json_encode(['success' => true, 'message' => 'Logout time recorded successfully.']);
    } else {
        
        echo json_encode(['success' => false, 'message' => 'No matching session found for logout.']);
    }
} else {
   
    echo json_encode(['success' => false, 'message' => 'Failed to execute query.', 'error' => $updateLogQuery->error]);
}

session_unset();
session_destroy();

$updateLogQuery->close();
$conn->close();
?>
