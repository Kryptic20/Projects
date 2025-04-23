<?php
session_start();
$conn = new mysqli("localhost", "root", "Eduv4821047", "news_website");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Retrieve user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: dashboard.php");
            exit();
        }
    }
}

// Check for login errors
$error = isset($_GET['error']) ? $_GET['error'] : '';
$errorMessage = '';
if ($error == 1) {
    $errorMessage = "Invalid email or password. Please try again.";
} elseif ($error == 2) {
    $errorMessage = "You must be logged in to view the dashboard.";
}

// Remember email if it was submitted
$rememberedEmail = isset($_COOKIE['remembered_email']) ? $_COOKIE['remembered_email'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 300px;
        }
        h1 {
            color: #333;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .input-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .remember-me input {
            margin-right: 0.5rem;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #1557b0;
        }
        .error-message {
            color: #d32f2f;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($errorMessage): ?>
            <p class="error-message"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <form action="login_process.php" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($rememberedEmail); ?>">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" 
                       <?php echo $rememberedEmail ? 'checked' : ''; ?>>
                <label for="remember">Remember me</label>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
