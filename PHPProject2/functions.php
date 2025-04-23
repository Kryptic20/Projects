<?php
require_once 'config.php';

/**
 * Validate user input for registration
 *
 * @param string $name
 * @param string $surname
 * @param string $gender
 * @param string $dob
 * @param string $email
 * @param string $contact
 * @return bool
 */
function validateInput($name, $surname, $gender, $dob, $email, $contact) {
    if (empty($name) || empty($surname) || empty($gender) || empty($dob) || empty($email)) {
        return false;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    if (!in_array($gender, ['Male', 'Female', 'Other'])) {
        return false;
    }
    
    $date = DateTime::createFromFormat('Y-m-d', $dob);
    if (!$date || $date->format('Y-m-d') !== $dob) {
        return false;
    }
    
    if (!empty($contact) && !preg_match("/^[0-9]{10}$/", $contact)) {
        return false;
    }
    
    return true;
}

/**
 * Generate a random password
 *
 * @param int $length
 * @return string
 */
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

/**
 * Log messages to file
 *
 * @param string $message
 * @param string $level
 * @return void
 */
function logMessage($message, $level = 'INFO') {
    $logEntry = date('Y-m-d H:i:s') . " [$level] $message" . PHP_EOL;
    error_log($logEntry, 3, LOG_FILE);
}

/**
 * Sanitize user input
 *
 * @param string $input
 * @return string
 */
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Send email using configured SMTP settings
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return bool
 */
function sendEmail($to, $subject, $message) {
    require_once 'path/to/PHPMailer/PHPMailerAutoload.php';
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port = SMTP_PORT;
    
    $mail->setFrom(SMTP_USER, 'News Website');
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;
    
    if(!$mail->send()) {
        logMessage('Email could not be sent. Mailer Error: ' . $mail->ErrorInfo, 'ERROR');
        return false;
    } else {
        logMessage('Email sent to: ' . $to);
        return true;
    }
}

/**
 * Check if user is logged in
 *
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Redirect user to a specific page
 *
 * @param string $page
 * @return void
 */
function redirect($page) {
    header("Location: " . BASE_URL . "/$page");
    exit();
}

/**
 * Get current user ID
 *
 * @return int|null
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Debug function to print variables
 *
 * @param mixed $var
 * @return void
 */
function debug($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

?>
