<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safe_haven";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $searchQuery = $conn->real_escape_string($searchQuery); 
    $sql = "SELECT * FROM modules WHERE title LIKE '%$searchQuery%' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM modules ORDER BY created_at DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Haven</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
           @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

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
}

nav {
    width: 100%;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 15px 0;
    margin-bottom: 20px;
    position: relative;
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
    max-width: 800px;
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

.search-bar {
    width: 100%;
    max-width: 400px;
    margin-bottom: 20px;
}

.search-bar input {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 1rem;
}

.module {
    background-color: #fff;
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 100%;
}

.module h3 {
    font-size: 1.4rem;
    color: #03a14a;
    margin-bottom: 10px;
    cursor: pointer;
}

.module p {
    font-size: 1rem;
    color: #555;
    line-height: 1.6;
}

img, video {
    margin-top: 10px;
    border-radius: 4px;
    max-width: 100%;
    height: auto;
}

.module-content {
    display: none; 
    margin-top: 10px;
}

.module.collapsed .module-content {
    display: none;
}

.module.expanded .module-content {
    display: block;
}


    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <div class="logo">
                <span>Safe Haven</span>
            </div>
            <div class="links">
                <a href="report.html">Report</a>
                <a href="view_modules.php">Modules</a>
                <a href="dashboard.html">Map</a>
                <a href="emergency.html">Emergency</a>
            </div>
            <div class="hamburg">
                <i class="fas fa-bars" id="hamburgButton"></i>
                <div id="hamburgDropdown" class="hamburg-dropdown">
                    <a href="edit_profile.html">Edit Profile</a>
                    <a href="user_history.html">History</a>
                    <a href="#" id="logoutBtn">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>Modules</h2>
        
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search modules by title..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        </form>

        <?php if ($searchQuery): ?>
            <p>Showing results for: <strong>"<?php echo htmlspecialchars($searchQuery); ?>"</strong></p>
        <?php endif; ?>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='module collapsed' id='module-" . $row['id'] . "'>";
                echo "<h3 onclick='toggleModule(" . $row['id'] . ")'>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<div class='module-content'>";
                echo "<p>" . htmlspecialchars($row['content']) . "</p>";

                if ($row['media_path']) {
                    $mediaPath = htmlspecialchars($row['media_path']);
                    if (preg_match('/\.(jpg|jpeg|png|gif)$/', $mediaPath)) {
                        echo "<img src='$mediaPath' alt='Module Media'>";
                    } elseif (preg_match('/\.(mp4|avi|mov)$/', $mediaPath)) {
                        echo "<video controls><source src='$mediaPath' type='video/mp4'></video>";
                    }
                }

                // Comment section removed here
                
                echo "</div>"; // Close module-content
                echo "</div>"; // Close module
            }
        } else {
            echo "<p>No modules found.</p>";
        }
        ?>
    </div>

    <script>
        function toggleModule(moduleId) {
            const moduleElement = document.getElementById('module-' + moduleId);
            if (moduleElement.classList.contains('collapsed')) {
                moduleElement.classList.remove('collapsed');
                moduleElement.classList.add('expanded');
            } else {
                moduleElement.classList.remove('expanded');
                moduleElement.classList.add('collapsed');
            }
        }

        const hamburgButton = document.getElementById('hamburgButton');
        const hamburgDropdown = document.getElementById('hamburgDropdown');
        hamburgButton.addEventListener('click', () => {
            hamburgDropdown.style.display = hamburgDropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.getElementById('logoutBtn').addEventListener('click', function() {
            alert('You have logged out.');
            window.location.href = 'login.html';
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
