<?php
session_start(); // Ensure the session is started

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Get user ID from the session
    $userId = $_SESSION['user_id'];

    // Get the current time
    $logoutTime = date('Y-m-d H:i:s');

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "safe_haven";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update logout time in the activity_log table
    $sql = "UPDATE activity_log SET logout_time = ? WHERE user_id = ? AND logout_time IS NULL";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('si', $logoutTime, $userId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
}
?>
