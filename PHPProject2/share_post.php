<?php
// share_post.php

// Database connection (replace with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "Eduv4821047";
$dbname = "news_website";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a new post or article
function createPost($post_type, $title, $content, $image_url = null, $is_article = false) {
    global $conn;
    $post_type = $conn->real_escape_string($post_type);
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $image_url = $conn->real_escape_string($image_url);
    $is_article = $is_article ? 1 : 0;

    $sql = "INSERT INTO posts (post_type, title, content, image_url, is_article) 
            VALUES ('$post_type', '$title', '$content', '$image_url', $is_article)";

    if ($conn->query($sql) === TRUE) {
        return "New " . ($is_article ? "article" : "post") . " created successfully";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to get posts by post_type
function getPostsByType($post_type, $limit = 5) {
    global $conn;
    $post_type = $conn->real_escape_string($post_type);

    $sql = "SELECT * FROM posts WHERE post_type = '$post_type' ORDER BY post_id DESC LIMIT $limit";
    $result = $conn->query($sql);

    $posts = array();
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    return $posts;
}

// Handle form submission for creating a new post or article
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_type = $_POST["post_type"];
    $title = $_POST["title"];
    $content = $_POST["content"];
    $image_url = $_POST["image_url"];
    $is_article = isset($_POST["is_article"]) ? true : false;

    echo createPost($post_type, $title, $content, $image_url, $is_article);
}

// Display form to create a new post or article
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Post or Article</title>
</head>
<body>
    <h1>Share a New Post or Article</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="post_type">Post Type:</label>
        <select name="post_type" id="post_type" required>
            <option value="sports">Sports</option>
            <option value="business">Business</option>
            <option value="entertainment">Entertainment</option>
            <option value="jobs">Jobs</option>
            <option value="article">Article</option>
        </select><br><br>

        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required><br><br>

        <label for="content">Content:</label><br>
        <textarea name="content" id="content" rows="10" cols="50" required></textarea><br><br>

        <label for="image_url">Image URL (optional):</label>
        <input type="url" name="image_url" id="image_url"><br><br>

        <label for="is_article">Is this an article?</label>
        <input type="checkbox" name="is_article" id="is_article"><br><br>

        <input type="submit" value="Share Post/Article">
    </form>

    <h2>Recent Posts and Articles</h2>
    <?php
    $post_types = ['sports', 'business', 'entertainment', 'jobs', 'article'];
    foreach ($post_types as $type) {
        echo "<h3>" . ucfirst($type) . "</h3>";
        $posts = getPostsByType($type);
        if (count($posts) > 0) {
            foreach ($posts as $post) {
                echo "<h4>{$post['title']}</h4>";
                echo "<p>" . (strlen($post['content']) > 200 ? substr($post['content'], 0, 200) . "..." : $post['content']) . "</p>";
                if ($post['image_url']) {
                    echo "<img src='{$post['image_url']}' alt='Post image' style='max-width: 300px;'><br>";
                }
                echo "<small>Post Type: {$post['post_type']}</small>";
                if ($post['is_article']) {
                    echo " | <strong>Article</strong>";
                }
                echo "<hr>";
            }
        } else {
            echo "<p>No posts of this type yet.</p>";
        }
    }
    ?>
</body>
</html>
<?php>
$conn->close();
?>
