<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $fullName = $_POST['profileFullName'];
    $address = $_POST['profileAddress'];
    $birthdate = $_POST['profileBirthdate'];
    $age = $_POST['profileAge'];
    $gender = $_POST['profileGender'];
    $email = $_POST['profileEmail'];  
    $username = $_POST['profileUsername'];
    $password = $_POST['profilePassword'];  
   
    $stmt = $conn->prepare("UPDATE users 
                            SET fullName=?, address=?, birthdate=?, age=?, gender=?, email=?, password=? 
                            WHERE username=?");
    $stmt->bind_param("sssiisss", $fullName, $address, $birthdate, $age, $gender, $email, $password, $username);

    if ($stmt->execute()) {
        echo "Profile updated successfully!";
        header('Location: dashboard.html'); 
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close(); 
}

$conn->close();
?>
