<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: dashboard.php"); // Redirect to the dashboard or another appropriate page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $user = [
        "username" => $username,
        "email" => $email,
        "password" => $password,
        "role" => "user", // Default role
    ];

    $users = json_decode(file_get_contents("users.json"), true);
    $users[] = $user;
    file_put_contents("users.json", json_encode($users));

    header("Location: login.php");
    exit();
}
?>

<!-- Registration Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Register</button>
    </form>
    <hr/>
   <p>Already have an account, <a href="login.php">Login here</a></p>
</body>
</html>
