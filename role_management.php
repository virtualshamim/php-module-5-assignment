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

// Function to write user data to JSON file
function saveUsersData($data) {
    file_put_contents("users.json", json_encode($data));
}

if (isset($_SESSION["user"]) && $_SESSION["user"]["role"] === "admin") {
    $username = $_SESSION["user"]["username"];
    $role = $_SESSION["user"]["role"];
    $users = getUsersData();

    // Add new user
    if (isset($_POST["new_username"]) && isset($_POST["new_email"]) && isset($_POST["new_password"])) {
        $newUsername = $_POST["new_username"];
        $newEmail = $_POST["new_email"];
        $newPassword = $_POST["new_password"];
        $newRole = $_POST["new_role"];
    
        // Check if the email already exists
        $emailExists = false;
        foreach ($users as $existingUser) {
            if ($existingUser["email"] === $newEmail) {
                $emailExists = true;
                break;
            }
        }
    
        if ($emailExists) {
            echo "Email already exists.";
        } else {
            $newUser = [
                "username" => $newUsername,
                "email" => $newEmail,
                "password" => password_hash($newPassword, PASSWORD_BCRYPT),
                "role" => $newRole,
            ];
    
            $users[] = $newUser;
            saveUsersData($users);
        }
    }

    // Delete user
    if (isset($_POST["delete_user_id"])) {
        $deleteUserId = $_POST["delete_user_id"];
        if ($deleteUserId !== 0) {
            if ($users[$deleteUserId]["email"] !== $_SESSION["user"]["email"]) {
                unset($users[$deleteUserId]);
                $users = array_values($users);
                saveUsersData($users);
            } else {
                echo "You can't Delete your own account.";
            }
        } else {
            echo "Invalid user ID.";
        }
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>Your role is: <?php echo $role; ?></p>
    <hr />

    <!-- Create New User Form -->
    <form method="POST" action="role_management.php">
        <h2>Create New User</h2>
        <input type="text" name="new_username" placeholder="Username" required>
        <input type="email" name="new_email" placeholder="Email" required>
        <input type="password" name="new_password" placeholder="Password" required>
        <select name="new_role">
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="user">User</option>
        </select>
        <button type="submit">Create User</button>
    </form>
    <hr /><br />

    <!-- Display Users in a Table -->
    <table>
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    <?php
    $loggedInUserId = -1;
    foreach ($users as $key => $user) {
        if ($user["email"] === $_SESSION["user"]["email"]) {
            $loggedInUserId = $key;
        }
    }

    foreach ($users as $key => $user) : ?>
        <tr>
            <td><?php echo $user["username"]; ?></td>
            <td><?php echo $user["email"]; ?></td>
            <td><?php echo $user["role"]; ?></td>
            <td>
                <?php if ($key === $loggedInUserId) : ?>
                    (Your Account)
                <?php elseif ($loggedInUserId !== -1) : ?>
                    <a href="edit_user.php?edit_user_id=<?php echo $key; ?>">Edit</a>
                    <form method="POST" action="role_management.php">
                        <input type="hidden" name="delete_user_id" value="<?php echo $key; ?>">
                        <button type="submit">Delete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
    <hr />
    <a href="logout.php">Logout</a>
</body>
</html>
