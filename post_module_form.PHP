<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM modules ORDER BY created_at DESC");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_module'])) {
    $module_id = $_POST['module_id'];

    $stmt = $conn->prepare("DELETE FROM modules WHERE id = ?");
    $stmt->bind_param("i", $module_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_new'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $mediaPath = '';

    if (isset($_FILES['media']) && $_FILES['media']['error'] === 0) {
        $mediaPath = 'uploads/' . basename($_FILES['media']['name']);
        move_uploaded_file($_FILES['media']['tmp_name'], $mediaPath);
    }

    $stmt = $conn->prepare("INSERT INTO modules (title, content, media_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $mediaPath);
    $stmt->execute();
    $stmt->close();
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Modules</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Clicker+Script&family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        * {
            padding: 0;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        body {
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 30px 0;
        }

        nav {
            width: 100%;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .nav-container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo span {
            font-size: 1.5rem;
            font-weight: bold;
            color: #03f554;
        }

        .links a {
            font-size: 1.1rem;
            color: #333;
            margin: 0 15px;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .links a:hover {
            color: #03f554;
        }

        .hamburg {
            position: relative;
            display: inline-block;
            cursor: pointer;
            color: #0e0d0d;
            font-size: 3rem;
            padding: 15px;
        }

        .hamburg-dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: rgba(252, 252, 252, 0.8);
            min-width: 150px;
            border-radius: 5px;
            box-shadow: 0 8px 16px rgba(20, 18, 18, 0.2);
            z-index: 1;
        }

        .hamburg-dropdown a {
            color: rgb(19, 39, 23);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 1rem;
        }

        .hamburg-dropdown a:hover {
            background-color: #03f554;
        }

        @media (max-width: 768px) {
            .links {
                display: none;
            }

            .hamburg {
                display: inline-block;
            }
        }

        .container {
            width: 90%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 20px 0;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }

        .module {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            position: relative; 
        }

        .delete-button {
            position: absolute;
            top: 10px;    
            right: 10px;  
            background-color: #FF6347;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .delete-button:hover {
            background-color: #FF4500;
        }

        form {
            width: 100%;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        input[type="text"], input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="submit"] {
            background-color: #03f554;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 10px;
            font-size: 1rem;
        }

        input[type="submit"]:hover {
            background-color: #02c543;
        }
    </style>
</head>
<body>
    <nav>
    <div class="nav-container">
            <div class="logo">
                <span>Safe Haven
                </span>
            </div>
            <div class="links">
                <a href="admindash.html">Dashboard</a>
                <a href="admin_report_history.html">Reports</a>
                <a href="post_module_form.PHP">Learning Materials</a>
                <a href="manage_users.html">Users</a>
            </div>
            <div class="hamburg">
                <i class="fas fa-bars" id="hamburgButton"></i>
                <div id="hamburgDropdown" class="hamburg-dropdown">
                    <a href="activity_log.html" id="activity_log">Activity Log</a>
                    <a href="#" id="logoutBtn">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Manage Modules</h2>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Module Title" required>
            <textarea name="content" placeholder="Module Content" required></textarea>
            <input type="file" name="media">
            <input type="submit" name="submit_new" value="Post Module">
        </form>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='module'>";
              
                echo "<form action='' method='POST' style='display:inline;'>";
                echo "<input type='hidden' name='module_id' value='" . $row['id'] . "'>";
                echo "<input type='submit' name='delete_module' value='Delete' class='delete-button' onclick='return confirmDelete()'>";
                echo "</form>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
                if ($row['media_path']) {
                    echo "<img src='" . htmlspecialchars($row['media_path']) . "' alt='Module Media'>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No modules found.</p>";
        }
        ?>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this module?");
        }

        document.getElementById('hamburgButton').addEventListener('click', function() {
            const dropdown = document.getElementById('hamburgDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.getElementById('logoutBtn').addEventListener('click', function() {
            alert('You have logged out.');
            window.location.href = 'login.html';
        });
    </script>
</body>
</html>
