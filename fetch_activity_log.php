<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT id, username, login_time, logout_time FROM activity_log ORDER BY login_time DESC";
$result = mysqli_query($conn, $query);

$activityLog = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $activityLog[] = $row;
    }
}

echo json_encode($activityLog);

mysqli_close($conn);
?>
