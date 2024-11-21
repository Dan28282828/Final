<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "safe_haven"; 


$conn = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$totalUsersQuery = "SELECT COUNT(*) as total_users FROM users";
$totalReportsQuery = "SELECT COUNT(*) as total_reports FROM reports";
$totalModulesQuery = "SELECT COUNT(*) as total_modules FROM modules";

$totalUsersResult = mysqli_query($conn, $totalUsersQuery);
$totalReportsResult = mysqli_query($conn, $totalReportsQuery);
$totalModulesResult = mysqli_query($conn, $totalModulesQuery);

$totalUsers = mysqli_fetch_assoc($totalUsersResult)['total_users'];
$totalReports = mysqli_fetch_assoc($totalReportsResult)['total_reports'];
$totalModules = mysqli_fetch_assoc($totalModulesResult)['total_modules'];

echo json_encode([
    'totalUsers' => $totalUsers,
    'totalReports' => $totalReports,
    'totalModules' => $totalModules
]);

mysqli_close($conn);
?>
