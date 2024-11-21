<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="logo">
                <span>Safe Haven Admin</span>
            </div>
            <div class="links">
                <a href="user_management.html">User Management</a>
                <a href="reports.html">Reports</a>
            </div>
            <div class="hamburg">
                <i class="fas fa-bars" id="hamburgButton"></i>
                <div id="hamburgDropdown" class="hamburg-dropdown">
                    <a href="#" id="logoutBtn">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="main-container">
        <h2>Admin Dashboard</h2>
        <p>Manage users and view reports.</p>
    </div>

    <script>
        document.getElementById('logoutBtn').addEventListener('click', function() {
            alert('You have logged out.');
            window.location.href = 'login.html';
        });

        document.getElementById('hamburgButton').addEventListener('click', function() {
            const dropdown = document.getElementById('hamburgDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
    </script>
</body>
</html>