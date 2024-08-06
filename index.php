<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['uname'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember']) ? true : false;

    $result = mysqli_query($conn, "SELECT * FROM user WHERE uname = '$username'");
    $user = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) == 1) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            if ($rememberMe) {
                setcookie('username', $username, time() + (86400 * 60), "/"); 
            } else {
                setcookie('username', '', time() - 3600, "/");
            }

            $_SESSION['user_id'] = $user['id'];

            header("Location: manage.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}

$storedUsername = isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <marquee behavior="scroll" direction="left">
        <h2>Login To Manage Student Information </h2>
        </marquee>
    </header>
    <div class="container-login">
        <form action="" method="POST" class="form-container">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <label for="uname">Username:</label>
            <input type="text" id="uname" name="uname" value="<?php echo $storedUsername; ?>" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="remember">
                <input type="checkbox" id="remember" name="remember" <?php echo isset($_COOKIE['username']) ? 'checked' : ''; ?>>
                Remember me
            </label><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
