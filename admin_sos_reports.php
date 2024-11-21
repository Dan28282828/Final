<?php

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "safe_haven"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM sos_reports ORDER BY timestamp DESC";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

$table_rows = '';
if ($result->num_rows > 0) {
    
    while($row = $result->fetch_assoc()) {
        $table_rows .= "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['latitude']}</td>
                            <td>{$row['longitude']}</td>
                            <td>{$row['phone_number']}</td>
                            <td>{$row['timestamp']}</td>
                        </tr>";
    }
} else {
    $table_rows = "<tr><td colspan='5'>No SOS reports found.</td></tr>";
}

$conn->close();
?>

<script>
   
    document.querySelector('tbody').innerHTML = `<?php echo $table_rows; ?>`;
</script>
