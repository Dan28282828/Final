<?php

$servername = "localhost";
$username = "root";  
$password = "";     
$dbname = "safe_haven";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$description = $_POST['description'];

$filePath = null;
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $targetDir = "uploads/"; 
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $filePath = $targetDir . basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $filePath);
}

$sql = "INSERT INTO reports (name, email, description, file_path) VALUES ('$name', '$email', '$description', '$filePath')";

if ($conn->query($sql) === TRUE) {
  
    echo "<script>
            alert('Your report has been submitted successfully!');
            window.location.href = 'report.html';
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
