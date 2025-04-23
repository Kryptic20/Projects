<?php
// Start the session at the very beginning of the script
session_start();

// Include database connection
require 'includes/db.php';  // Ensure this file returns a PDO connection

// Function to generate a random password
function generateRandomPassword($length = 12) {
    return bin2hex(random_bytes($length / 2));
}

function logEmail($to, $subject, $message, $headers) {
    $logEntry = date('Y-m-d H:i:s') . " - To: $to - Subject: $subject\n";
    $logEntry .= "Message:\n$message\n";
    $logEntry .= "Headers:\n$headers\n\n";
    
    $logFile = 'email_log.txt';
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    
    return true; // Always return true to simulate successful sending
}

function sendRegistrationEmail($to, $name, $surname, $password) {
    $subject = "Welcome to News Website";
    $message = "Dear $name $surname,\n\nYour account has been successfully created on our news website.\n";
    $message .= "Here are your login details:\n";
    $message .= "Email: $to\n";
    $message .= "Password: $password\n\n";
    $message .= "Please change your password after logging in.\n\nThank you for joining us!";

    $headers = 'From: noreply@newswebsite.com' . "\r\n" .
               'Reply-To: noreply@newswebsite.com' . "\r\n" .
               'X-Mailer: PHP/' . phpversion();

    return logEmail($to, $subject, $message, $headers);
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $dob = $_POST['dob'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $gender = $_POST['gender'];
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Email already exists");
        }
        
       // Generate a random temporary password for the user
       $password = generateRandomPassword(); // e.g., c00cf1d92f76
       $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash it before storing
        
        // Insert user into the database
        $stmt = $pdo->prepare("INSERT INTO users (name, surname, gender, dob, email, contact, password, newsletter) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $surname, $gender, $dob, $email, $contact, $hashed_password, $newsletter]);
        
        // Send email with login credentials
        if (sendRegistrationEmail($email, $name, $surname, $password)) {
            $_SESSION['registration_success'] = true;
            $_SESSION['user_email'] = $email;
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            throw new Exception("Registration successful, but there was an error sending the email. Please contact support.");
        }
    } catch (Exception $e) {
        $error_message = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* ... (CSS remains unchanged) ... */
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php
        if (!empty($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="surname">Surname:</label>
                <input type="text" id="surname" name="surname" required>
            </div>
            <div>
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="contact">Contact Number:</label>
                <input type="tel" id="contact" name="contact" required>
            </div>
            <div>
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                    <option value="prefer_not_to_say">Prefer not to say</option>
                </select>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" id="newsletter" name="newsletter" value="1">
                <label for="newsletter">Subscribe to our newsletter</label>
            </div>
            <div>
                <input type="submit" value="Register">
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>
