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

// Check if the uploads directory exists; if not, create it
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle book deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $bookId = $_POST['book_id'];

    // Retrieve the file and image paths before deleting the book
    $sql = "SELECT file_path, image_path FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $stmt->bind_result($filePath, $imagePath);
    $stmt->fetch();
    $stmt->close();

    // Delete the files from the uploads directory
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Delete the book from the database
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        echo "<p>Book deleted successfully!</p>";

        // Rearrange the IDs to maintain sequence
        $resetSql = "SET @num = 0;
                     UPDATE books SET id = (@num := @num + 1);
                     ALTER TABLE books AUTO_INCREMENT = 1;";
        if ($conn->multi_query($resetSql)) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
        }
    } else {
        echo "<p>Error deleting book: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Handle book addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    if (isset($_POST['title'], $_POST['author'], $_FILES['file'], $_FILES['image'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $file = $_FILES['file'];
        $image = $_FILES['image'];

        // Validate the file and image upload
        if ($file['error'] === UPLOAD_ERR_OK && $image['error'] === UPLOAD_ERR_OK) {
            $filePath = $uploadDir . basename($file['name']);
            $imagePath = $uploadDir . basename($image['name']);

            // Move the uploaded files to the designated directory
            if (move_uploaded_file($file['tmp_name'], $filePath) && move_uploaded_file($image['tmp_name'], $imagePath)) {
                // Insert the book data into the database, including the image path
                $sql = "INSERT INTO books (title, author, file_path, image_path) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $title, $author, $filePath, $imagePath);

                if ($stmt->execute()) {
                    echo "<p>Book added successfully!</p>";
                } else {
                    echo "<p>Error: " . $stmt->error . "</p>";
                }

                $stmt->close();
            } else {
                echo "<p>Error uploading file or image.</p>";
            }
        } else {
            echo "<p>Error in file or image upload.</p>";
        }
    } else {
        echo "<p>Please fill in all fields.</p>";
    }
}

// Fetch and display existing books
$sql = "SELECT id, title, author, file_path, image_path FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .manage-books-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .book-list {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .delete-button {
            background-color: #f44336;
            border: none;
            padding: 5px 10px;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-button:hover {
            background-color: #e53935;
        }
        img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="manage-books-container">
        <h2>Manage Books</h2>
        <form method="POST" action="manage_books.php" enctype="multipart/form-data">
            <div class="input-group">
                <label for="title">Book Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="input-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" required>
            </div>
            <div class="input-group">
                <label for="file">Upload Book File:</label>
                <input type="file" id="file" name="file" accept=".pdf,.epub,.mobi" required>
            </div>
            <div class="input-group">
                <label for="image">Upload Book Image:</label>
                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png" required>
            </div>
            <button type="submit" name="add">Add Book</button>
        </form>

        <div class="book-list">
            <h3>Existing Books</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>File</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['title']}</td>
                                    <td>{$row['author']}</td>
                                    <td><a href='{$row['file_path']}' download>Download</a></td>
                                    <td><img src='{$row['image_path']}' alt='Book Image'></td>
                                    <td>
                                        <form method='POST' style='display:inline-block;'>
                                            <input type='hidden' name='book_id' value='{$row['id']}'>
                                            <button type='submit' name='delete' class='delete-button'>Delete</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No books found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
