<?php
include('classes/Database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $aesKey = bin2hex(random_bytes(16));

    $secretKey = $password;
    $iv = substr(hash('sha256', $secretKey, true), 0, 16);
    $encryptedKey = openssl_encrypt($aesKey, 'aes-256-cbc', $secretKey, 0, $iv);
    $db = new Database();
    $conn = $db->connect();

    $sql = "INSERT INTO users (username, password, aes_key) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$username, $passwordHash, $encryptedKey])) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: Unable to register user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
</head>
<body>
    <h2>Signup Form</h2>
    <form method="POST" action="">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Signup">
    </form>
    <br>
    <a href="login.php">Already have an account? Login here</a>
</body>
</html>

