<?php
header(header: 'Content-Type: text/csv');
header(header: 'Content-Disposition: attachment; filename="admin_report.csv"');

$output = fopen(filename: 'php://output', mode: 'w');

fputcsv(stream: $output, fields: ['User ID', 'Username', 'Email', 'Report Count', 'Module Count']);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT u.id, u.username, u.email, COUNT(r.id) AS report_count, COUNT(m.id) AS module_count
        FROM users u
        LEFT JOIN reports r ON u.id = r.user_id
        LEFT JOIN modules m ON u.id = m.user_id
        GROUP BY u.id";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv(stream: $output, fields: $row);
}

fclose(stream: $output);
$conn->close();
?>
