<?php
// Start output buffering to avoid issues with header redirection
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['password'], $_POST['role']) &&
        !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['role'])) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into 'users' table
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            // Redirect to login page
            header("Location: home_page.html");
            exit(); // Ensure no further code runs
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();

// End output buffering and flush output
ob_end_flush();
?>
