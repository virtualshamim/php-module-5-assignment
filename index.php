<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
</head>
<body>
    <h1>Welcome to Our Crew Project</h1>

    <?php if (isset($_SESSION["user"])) : ?>
        <p>Hello, <?php echo $_SESSION["user"]["username"]; ?>!</p>
        <p>Your role is: <?php echo $_SESSION["user"]["role"]; ?></p>
        <a href="dashboard.php">Go to Dashboard</a>
        <a href="logout.php">Logout</a>
    <?php else : ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</body>
</html>