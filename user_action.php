<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$action = $_POST['action'];
$user = $_POST['username'];

if ($action === 'accept') {
    // Code to accept the user (e.g., update a status in the database)
    $stmt = $conn->prepare("UPDATE users SET status = 'accepted' WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'User accepted.']);
} elseif ($action === 'delete') {
    // Code to delete the user from the database
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true, 'message' => 'User deleted.']);
}

$conn->close();
?>
