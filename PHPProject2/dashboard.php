<?php
session_start();

$email = $_SESSION['email'];

// Simulate fetching recent news articles from a database
function getRecentArticles($limit = 5) {
    // In a real application, this would be a database query
    return [
        ['id' => 1, 'title' => 'Breaking News: Major Event Unfolds', 'date' => '2023-05-15'],
        ['id' => 2, 'title' => 'Technology Giant Announces New Product', 'date' => '2023-05-14'],
        ['id' => 3, 'title' => 'Sports Team Wins Championship', 'date' => '2023-05-13'],
        ['id' => 4, 'title' => 'Economic Report Shows Positive Trends', 'date' => '2023-05-12'],
        ['id' => 5, 'title' => 'Environmental Initiative Launched Globally', 'date' => '2023-05-11'],
    ];
}

$recentArticles = getRecentArticles();

// Simulate fetching user's drafted articles
function getUserDrafts($email) {
    // In a real application, this would be a database query
    return [
        ['id' => 101, 'title' => 'My Thoughts on Current Events', 'last_edited' => '2023-05-10'],
        ['id' => 102, 'title' => 'Analysis of Recent Sports Developments', 'last_edited' => '2023-05-09'],
    ];
}

$userDrafts = getUserDrafts($email);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Dashboard - Your News Website</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 1200px; margin: auto; background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; }
        .user-info { text-align: right; margin-bottom: 20px; }
        .recent-news, .user-drafts { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .action-buttons { display: flex; justify-content: space-between; margin-top: 20px; }
        .btn { padding: 10px 15px; background-color: #333; color: white; text-decoration: none; border-radius: 3px; }
        .btn:hover { background-color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info">
            <p>Welcome, <?php echo htmlspecialchars($email); ?> | <a href="logout.php">Logout</a></p>
        </div>
        
        <h1>News Dashboard</h1>
        
        <div class="recent-news">
            <h2>Recent News Articles</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Date Published</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($recentArticles as $article): ?>
                <tr>
                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                    <td><?php echo htmlspecialchars($article['date']); ?></td>
                    <td><a href="view_article.php?id=<?php echo $article['id']; ?>">View</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <div class="user-drafts">
            <h2>Your Draft Articles</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Last Edited</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($userDrafts as $draft): ?>
                <tr>
                    <td><?php echo htmlspecialchars($draft['title']); ?></td>
                    <td><?php echo htmlspecialchars($draft['last_edited']); ?></td>
                    <td><a href="edit_article.php?id=<?php echo $draft['id']; ?>">Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <div class="action-buttons">
            <a href="create_article.php" class="btn">Create New Article</a>
            <a href="manage_categories.php" class="btn">Manage Categories</a>
            <a href="user_profile.php" class="btn">Edit Profile</a>
            <a href="change_password.php" class="btn">Change Password</a>
        </div>
    </div>
</body>
</html>
