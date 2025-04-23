<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Log the logout
error_log("User logged out: " . $_SESSION['user_email']);

// Redirect to the login page
header("Location: login.php");
exit();
?>
