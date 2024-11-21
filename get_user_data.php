<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "safe_haven"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['username'])) {
    $user_username = $_GET['username'];
    
    
    $stmt = $conn->prepare("SELECT fullName, address, birthdate, age, gender, email, username FROM users WHERE username = ?");
    $stmt->bind_param("s", $user_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        echo json_encode($userData); 
    } else {
        echo json_encode(["error" => "User not found."]);
    }

    $stmt->close(); 
}

$conn->close();
?>
