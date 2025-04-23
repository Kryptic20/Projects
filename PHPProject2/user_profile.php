<?php
session_start();
$servername = "localhost"; // Your server details
$username = "root";        // Your database username
$password = "Eduv4821047";            // Your database password
$dbname = "news_website";   // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in by checking the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if no session is found
    header('Location: login.php');
    exit();
}

// Fetch user data from the database using the user ID from the session
$userId = $_SESSION['user_id'];
$sql = "SELECT name, surname, dob, contact, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing the SQL query: " . $conn->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $userProfile = $result->fetch_assoc();
} else {
    // If no user found, handle accordingly
    echo "User not found.";
    exit();
}

// Handle form submission and update the profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated form values
    $updatedName = $_POST['name'];
    $updatedSurname = $_POST['surname'];
    $updatedDob = $_POST['dob'];
    $updatedContact = $_POST['contact'];
    $updatedEmail = $_POST['email'];

    // Update user data in the database
    $updateSql = "UPDATE users SET name = ?, surname = ?, dob = ?, contact = ?, email = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);

    if (!$updateStmt) {
        die("Error preparing the SQL query for update: " . $conn->error);
    }

    $updateStmt->bind_param("sssssi", $updatedName, $updatedSurname, $updatedDob, $updatedContact, $updatedEmail, $userId);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        echo "Profile updated successfully!";
        // Optionally, reload the updated user data
        $userProfile['name'] = $updatedName;
        $userProfile['surname'] = $updatedSurname;
        $userProfile['dob'] = $updatedDob;
        $userProfile['contact'] = $updatedContact;
        $userProfile['email'] = $updatedEmail;
    } else {
        echo "No changes made to the profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Profile</title>
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
    <div class="container">
        <h1>Edit Your Profile</h1>

        <!-- Form for editing user profile -->
        <form action="user_profile.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userProfile['name']); ?>" required>

            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($userProfile['surname']); ?>" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($userProfile['dob']); ?>" required>

            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($userProfile['contact']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email']); ?>" required>

            <button type="submit" class="btn">Save Changes</button>
        </form>

        <br><a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
