<?php
session_start();

$categories = ['Jobs', 'Sport', 'Entertainment', 'Business'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_category'])) {
        $new_category = trim($_POST['new_category']);
        if (!empty($new_category) && !in_array($new_category, $categories)) {
            // In a real app, you'd add this to the database
            $categories[] = $new_category;
            $message = "Category '$new_category' added successfully.";
        } else {
            $error = "Category already exists or is invalid.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - News Dashboard</title>
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
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #f8f8f8;
            margin-bottom: 5px;
            padding: 10px;
            border-radius: 3px;
        }
        form {
            margin-top: 20px;
        }
        input[type="text"] {
            width: 70%;
            padding: 8px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .message, .error {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
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
        <h1>Manage Categories</h1>
        
        <?php
        if (isset($message)) echo "<div class='message'>$message</div>";
        if (isset($error)) echo "<div class='error'>$error</div>";
        ?>
        
        <h2>Existing Categories</h2>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li><?php echo htmlspecialchars($category); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <h2>Add New Category</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="new_category" required placeholder="Enter new category name">
            <input type="submit" value="Add Category">
        </form>
    </div>
</body>
</html>

