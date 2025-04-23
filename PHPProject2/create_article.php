<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real application, you would validate and sanitize input, then save to database
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    
    // Simulating article save (replace with actual database insertion)
    $success = true; // Assume save is successful
    
    if ($success) {
        $message = "Article successfully created!";
    } else {
        $error = "Error creating article. Please try again.";
    }
}

// List of categories (in a real app, this might come from a database)
$categories = ['Jobs', 'Sport', 'Entertainment', 'Business'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Article - News Dashboard</title>
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
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 20px;
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
        <h1>Create New Article</h1>
        
        <?php
        if (isset($message)) {
            echo "<div class='message'>$message</div>";
        }
        if (isset($error)) {
            echo "<div class='error'>$error</div>";
        }
        ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="title">Article Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="content">Article Content:</label>
            <textarea id="content" name="content" required></textarea>
            
            <input type="submit" value="Create Article">
        </form>
    </div>
</body>
</html>
