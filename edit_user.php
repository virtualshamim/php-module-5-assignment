<?php
session_start();

// Function to read user data from JSON file
function getUsersData() {
    $userData = [];
    if (file_exists("users.json")) {
        $userData = json_decode(file_get_contents("users.json"), true);
    }
    return $userData;
}

if (isset($_SESSION["user"]) && $_SESSION["user"]["role"] === "admin") {
    $users = getUsersData();

    if (isset($_GET["edit_user_id"])) {
        $editUserId = $_GET["edit_user_id"];
        $userToEdit = $users[$editUserId];
    } else {
        echo "Invalid user ID.";
        exit();
    }

    // Handle the form submission to update user details
    if (isset($_POST["edit_user_id"])) {
        $editUserId = $_POST["edit_user_id"];
        $newUsername = $_POST["edit_username"];
        $newEmail = $_POST["edit_email"];
        $newRole = $_POST["edit_role"];

        // Update user details
        $users[$editUserId]["username"] = $newUsername;
        $users[$editUserId]["email"] = $newEmail;
        $users[$editUserId]["role"] = $newRole;

        // Save updated data to the JSON file
        file_put_contents("users.json", json_encode($users));

        // Redirect back to the role management page
        header("Location: role_management.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST" action="edit_user.php?edit_user_id=<?php echo $editUserId; ?>">
        <input type="hidden" name="edit_user_id" value="<?php echo $editUserId; ?>">
        <input type="text" name="edit_username" placeholder="Username" value="<?php echo $userToEdit["username"]; ?>" required>
        <input type="email" name="edit_email" placeholder="Email" value="<?php echo $userToEdit["email"]; ?>" required>
        <select name="edit_role">
            <option value="admin" <?php if ($userToEdit["role"] === "admin") echo "selected"; ?>>Admin</option>
            <option value="manager" <?php if ($userToEdit["role"] === "manager") echo "selected"; ?>>Manager</option>
            <option value="user" <?php if ($userToEdit["role"] === "user") echo "selected"; ?>>User</option>
        </select>
        <button type="submit">Update</button>
    </form>
</body>
</html>
