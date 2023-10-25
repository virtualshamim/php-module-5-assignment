<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: dashboard.php"); // Redirect to the dashboard or another appropriate page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $users = json_decode(file_get_contents("users.json"), true);

    if (is_array($users)) {
        foreach ($users as $user) {
            if ($user["email"] === $email && password_verify($password, $user["password"])) {
                $_SESSION["user"] = $user;
                header("Location: dashboard.php");
                exit();
            }
        }
    }
    
    echo "Invalid credentials";
}
?>
<!-- Login Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <hr/>
   <p>No Account, <a href="register.php">Register here</a></p>
</body>
</html>
