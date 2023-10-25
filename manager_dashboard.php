<?php
session_start();

if (isset($_SESSION["user"]) && $_SESSION["user"]["role"] === "manager") {
    $username = $_SESSION["user"]["username"];
    $role = $_SESSION["user"]["role"];
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>Your role is: <?php echo $role; ?></p>

    <!-- Add content specific to the manager's dashboard here -->
    
    <a href="logout.php">Logout</a>
</body>
</html>
