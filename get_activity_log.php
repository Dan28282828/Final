<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; 
$password = "";    
$dbname = "safe_haven"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode([]);
    exit();
}

$sql = "SELECT al.action_type, al.target_user_name, u.fullName as admin_name, al.action_performed_at 
        FROM activity_log al
        JOIN users u ON al.action_performed_by = u.id
        ORDER BY al.action_performed_at DESC";

$result = $conn->query($sql);
$logs = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}

echo json_encode($logs);

$conn->close();
?>
