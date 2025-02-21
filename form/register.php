<?php
session_start();
include("db.php");

$registration_success = false; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Insert data into users table
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $registration_success = true; // Set success flag
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST">
    <input type="text" name="username" placeholder="Enter username" required>
    <input type="email" name="email" placeholder="Enter email" required>
    <input type="password" name="password" placeholder="Enter password" required>
    <button type="submit" class="btn">Sign Up</button>
</form>

<?php if (isset($registration_success) && $registration_success): ?>
    <div class="success-message">Registration successful! <a href='login.php'>Login here</a></div>
<?php endif; ?>

    </div>
</body>
</html>
