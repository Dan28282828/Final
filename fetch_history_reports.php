session_start();

$servername = "localhost";
$username = "root"; 
$password = "";  
$dbname = "safe_haven"; 

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Get the logged-in user's ID
$user_id = $_SESSION['name'];

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : '';

// Base SQL query to get reports for the logged-in user (filter by user_id)
$sql = "SELECT * FROM reports WHERE name = ?";  

// If there's a search term, filter by name or email
if ($searchTerm) {
    $searchTerm = '%' . $searchTerm . '%';
    $sql .= " AND (name LIKE ? OR id LIKE ?)";
}

// If there's a date filter, apply it
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

// Debugging: echo the query to verify it's correct
// echo $sql;

// Prepare and bind the parameters
$stmt = $conn->prepare($sql);

// Adjust the binding logic based on filters
if ($searchTerm && $dateFilter) {
    $stmt->bind_param('ssss', $name, $searchTerm, $searchTerm, $currentDate); 
} elseif ($searchTerm) {
    $stmt->bind_param('ss', $name, $searchTerm); 
} elseif ($dateFilter) {
    $stmt->bind_param('ss', $name, $currentDate); 
} else {
    $stmt->bind_param('s', $name); 
}

// Execute the query and fetch results
$stmt->execute();
$result = $stmt->get_result();

$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

// Return the reports as JSON
echo json_encode($reports);

$stmt->close();
$conn->close();
