<?php
$servername = "localhost";
$username = "root"; 
$password = "";  
$dbname = "safe_haven"; 

$conn = new mysqli($servername, $username, $password, $dbname);

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

$sql = "SELECT * FROM reports WHERE 1";  

if ($searchTerm) {
    $searchTerm = '%' . $searchTerm . '%';  
    $sql .= " AND (name LIKE ? OR email LIKE ?)";
}

if ($dateFilter) {
    $currentDate = date('Y-m-d');
    if ($dateFilter == 'last_week') {
        $sql .= " AND created_at >= DATE_SUB(?, INTERVAL 1 WEEK)";
    } elseif ($dateFilter == 'last_month') {
        $sql .= " AND created_at >= DATE_SUB(?, INTERVAL 1 MONTH)";
    } elseif ($dateFilter == 'last_year') {
        $sql .= " AND created_at >= DATE_SUB(?, INTERVAL 1 YEAR)";
    }
}

$stmt = $conn->prepare($sql);

if ($searchTerm) {
    $stmt->bind_param('ss', $searchTerm, $searchTerm); 
}

if ($dateFilter) {
    $stmt->bind_param('s', $currentDate); 
}

$stmt->execute();

$result = $stmt->get_result();
$reports = [];

while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

echo json_encode($reports);

$stmt->close();
$conn->close();
?>
