<?php
// Database connection details
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

// Check if form data is sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['newPassword']) &&
        !empty($_POST['username']) && !empty($_POST['newPassword'])) {

        // Get form data
        $username = $_POST['username'];
        $newPassword = $_POST['newPassword'];

        // Check if the user exists
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing the query: " . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists, proceed to update the password
            $hashed_password = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update password in the 'users' table
            $updateSql = "UPDATE users SET password = ? WHERE username = ?";
            $updateStmt = $conn->prepare($updateSql);
            if ($updateStmt === false) {
                die("Error preparing the update query: " . $conn->error);
            }

            // Bind parameters and execute
            $updateStmt->bind_param("ss", $hashed_password, $username);
            if ($updateStmt->execute()) {
                echo "Password updated successfully!";
            } else {
                echo "Error updating password: " . $updateStmt->error;
            }

            // Close the update statement
            $updateStmt->close();
        } else {
            echo "No user found with that username.";
        }

        // Close the user query statement
        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

// Close the database connection
$conn->close();
?>
