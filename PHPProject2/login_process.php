<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "Eduv4821047", "news_website");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the "Remember Me" cookie is set and valid
if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    
    // Find the user by token
    $stmt = $conn->prepare("SELECT id, email FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User found, log them in automatically
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit();
    }
}

// Login process when form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email and password from the login form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember_me = isset($_POST['remember_me']); // Check if "Remember Me" is checked

    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit();
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT id,email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password correct, start the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // If "Remember Me" is checked, set a cookie and generate a token
            if ($remember_me) {
                // Generate a secure token
                $token = bin2hex(random_bytes(16));

                // Store the token in the database
                $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->bind_param("si", $token, $user['id']);
                $stmt->execute();

                // Set the cookie to last for 30 days
                setcookie("remember_me", $token, time() + (86400 * 30), "/"); // 86400 = 1 day
            }

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "No account found with that email.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
