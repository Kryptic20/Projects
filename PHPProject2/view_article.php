<?php
session_start();
// Connect to the database
$servername = "localhost";
$username = "root";  // Database username
$password = "Eduv4821047";  // Database password
$dbname = "news_website";  // Database name

// Create connection
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Get article ID from URL and validate it
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 1;
    
    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Initialize $content as null
    $content = null;
    
    if ($result->num_rows > 0) {
        $content = $result->fetch_assoc();
    }
    
    // Close the statement and connection
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    // Log the error and show a user-friendly message
    error_log($e->getMessage());
    die("An error occurred. Please try again later.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($content['title'] ?? 'Article Not Found'); ?> - News Website</title>
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
        }
        .meta {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        .content {
            line-height: 1.8;
        }
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            color: #333;
            text-decoration: none;
        }
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
        <?php if ($content): ?>
            <article>
                <h1><?php echo htmlspecialchars($content['title'] ?? ''); ?></h1>
                <div class="meta">
                    <span>Author: <?php echo htmlspecialchars($content['author'] ?? ''); ?></span> |
                    <span>Published on <?php echo htmlspecialchars($content['timestamp'] ?? ''); ?></span> |
                    <span>Category: <?php echo htmlspecialchars($content['post_type'] ?? ''); ?></span>
                </div>
                <div class="content">
                    <?php echo nl2br(htmlspecialchars($content['content'] ?? '')); ?>
                </div>
            </article>
            <?php if (isset($_SESSION['user_id'])): ?>
                <p><a href="edit_article.php?id=<?php echo htmlspecialchars($content['post_id'] ?? ''); ?>">Edit this article</a></p>
            <?php endif; ?>
        <?php else: ?>
            <div class="error-message">
                Article not found.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
