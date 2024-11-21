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

if (!isset($data['username'], $data['password'], $data['fullName'], $data['address'], $data['birthdate'], $data['age'], $data['gender'], $data['email'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO users (fullName, address, birthdate, age, gender, username, password, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ssisssss", $data['fullName'], $data['address'], $data['birthdate'], $data['age'], $data['gender'], $data['username'], $data['password'], $data['email']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
