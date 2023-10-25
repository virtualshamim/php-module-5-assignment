<?php
session_start();

if (isset($_SESSION["user"])) {
    $role = $_SESSION["user"]["role"];
    if ($role === "admin") {
        header("Location: role_management.php");
        exit();
    } elseif ($role === "manager") {
        header("Location: manager_dashboard.php");
        exit();
    } else {
        header("Location: user_dashboard.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
