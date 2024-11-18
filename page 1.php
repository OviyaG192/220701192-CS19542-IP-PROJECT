<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .available-books-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        a.download-link {
            color: #007bff;
            text-decoration: none;
        }
        a.download-link:hover {
            text-decoration: underline;
        }
        img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
        }
        .book-details {
            display: flex;
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .book-cover {
    width: 40%;            /* Adjust the width of the cover container */
    padding: 20px;         /* Add padding for some spacing */
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f7f7f7;
    min-height: 400px;      /* Ensure a decent height */
    box-sizing: border-box;
}

.book-cover img {
    max-width: 100%;        /* Make sure it uses the entire width if needed */
    max-height: 100%;       /* Prevent it from overflowing the container */
    object-fit: contain;    /* Keep the aspect ratio and avoid cropping */
    border-radius: 10px;    /* Slight border rounding */
}


        .book-info {
            padding: 20px;
            width: 60%;
        }

        .book-info h2 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #007bff;
        }

        .book-info p {
            font-size: 16px;
            color: #333;
        }

        .book-info .author {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .action-buttons {
            margin-top: 20px;
        }

        .action-buttons button {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .action-buttons .read {
            background-color: #007bff;
            color: white;
        }

        /* Related Books Section */
        .related-books {
            margin: 50px auto;
            max-width: 900px;
        }

        .related-books h3 {
            text-align: center;
            color: #007bff;
        }

        .book-list {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .book-card {
    width: 180px;            /* Slightly wider for better fit */
    margin: 15px;            /* Add some space between cards */
}

        .book-card img {
    width: 100%;             /* Take up full width of the card */
    height: 200px;           /* Set a fixed height to keep proportions consistent */
    object-fit: contain;     /* Ensure the image fits without cropping */
    border-radius: 5px;
    background-color: #f0f0f0; /* Optional: Background to fill empty space */
}


        .book-card a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .footer {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="available-books-container">
        <h2>Available Books</h2>
        <!-- Book Details Section -->
        <div class="book-details">
            <div class="book-cover">
                <img src="the great gatsby.jpeg" alt="The Great Gatsby Cover">
            </div>
            <div class="book-info">
                <h2>The Great Gatsby</h2>
                <p class="author">by F. Scott Fitzgerald</p>
                <p><strong>Genre:</strong> Fiction</p>
                <p><strong>Publication Year:</strong> 1925</p>
                <p><strong>Description:</strong> The Great Gatsby is a novel set in the Roaring Twenties that tells the story of Jay Gatsby's unrequited love for Daisy Buchanan.</p>
                <div class="action-buttons">
                <a href="https://en.wikipedia.org/wiki/The_Great_Gatsby">
      <button>Read me</button>
</a>
                </div>
            </div>
        </div>

        <!-- Related Books Section -->
        <div class="related-books">
            <h3>Related Books</h3>
            <div class="book-list">
                <div class="book-card">
                    <img src="https://m.media-amazon.com/images/I/81gepf1eMqL._AC_UF1000,1000_QL80_.jpg" alt="To Kill a Mockingbird">
                    <a href="https://en.wikipedia.org/wiki/To_Kill_a_Mockingbird">To Kill a Mockingbird</a>
                </div>
                <div class="book-card">
                    <img src="The Night Circus.jpeg" alt="The Night Circus">
                    <a href="https://www.goodreads.com/book/show/9361589-the-night-circus">The Night Circus</a>
                </div>
                <div class="book-card">
                    <img src="the girl with the dragon tatoo.jpeg" alt="The Girl with the Dragon Tattoo">
                    <a href="https://en.wikipedia.org/wiki/The_Girl_with_the_Dragon_Tattoo_(2011_film)">The Girl with the Dragon Tattoo</a>
                </div>
                <div class="book-card">
                    <img src="Moby Dick by Herman Melville Typography Wall Art.jpeg" alt="Moby Dick">
                    <a href="#">Moby Dick</a>
                </div>
            </div>
        </div>

        <!-- Database Connection and Table Section (PHP) -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Image</th>
                    <th>Download</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection details
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "library";

                // Create connection to MySQL
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch all books from the books table
                $sql = "SELECT id, title, author, image_path, file_path FROM books";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Loop through and display each book
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['author']}</td>
                                <td><img src='{$row['image_path']}' alt='Book Image'></td>
                                <td><a class='download-link' href='{$row['file_path']}' download>Download</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No books available at the moment.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>
