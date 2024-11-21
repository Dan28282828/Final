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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_POST['id'];

    if (!empty($userId)) {
       
        $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
        $stmt->bind_param("i", $userId);  
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'User promoted to admin successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error promoting user: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    }
}

$conn->close();
?>
