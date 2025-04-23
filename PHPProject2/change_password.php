<?php
require 'session.php';
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $user_id]);

    echo "Password updated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        form { display: flex; flex-direction: column; }
        label { margin-bottom: 10px; }
        input[type="text"], input[type="email"], input[type="date"] { padding: 10px; margin-bottom: 20px; width: 100%; border: 1px solid #ccc; border-radius: 3px; }
        .btn { padding: 10px 15px; background-color: #333; color: white; text-decoration: none; border-radius: 3px; border: none; cursor: pointer; }
        .btn:hover { background-color: #555; }
    </style>
</head>
<body>
<h1>Change Password</h1>
<form method="POST">
    <label>New Password:</label><input type="password" name="new_password" required><br>
    <button type="submit">Change Password</button>
    
    <br><a href="dashboard.php">Back to Dashboard</a>
</form>
</body>
</html>
