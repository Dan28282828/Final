<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $module_id = $_POST['module_id']; 

    $sql = "INSERT INTO comments (module_id, content) VALUES ('$module_id', '$comment')";

    if ($conn->query($sql) === TRUE) {
     
        header("Location: view_modules.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


$conn->close();
?>
