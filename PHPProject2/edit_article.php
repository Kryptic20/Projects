<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";  // Database username
$password = "Eduv4821047";  // Database password
$dbname = "news_website";  // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Get article ID from URL or set a default ID
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

//Write SQL query to fetch article from database
$sql = "SELECT * FROM posts WHERE post_id = $post_id";

//Execute the query and fetch data
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch article data
    $article = $result->fetch_assoc();
} else {
    echo "No article found.";
}

// List of categories (in a real app, this might come from a database)
$categories = ['Sport', 'Jobs','Entertainment', 'Business'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real app, you'd update this in the database
    $article['title'] = $_POST['title'];
    $article['category'] = $_POST['category'];
    $article['content'] = $_POST['content'];
    $message = "Article updated successfully.";   
}

//Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - News Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 200px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 4px;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
        <h1>Edit Article</h1>
        
        <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="title">Article Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
            
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $post_type): ?>
                    <option value="<?php echo htmlspecialchars($post_type); ?>" <?php if ($post_type === $article['post_type']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($post_type); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <label for="content">Article Content:</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($article['content']); ?></textarea>
            
            <input type="submit" value="Update Article">
        </form>
    </div>
</body>
</html>
