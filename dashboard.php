<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include(__DIR__ . '/classes/Database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $site = $_POST['site'];
    $password = $_POST['password'];

    $date = date("Y-m-d H:i:s");

    $db = new Database();
    $conn = $db->connect();

    $sql = "INSERT INTO saved_passwords (user_id, site, password_value, created_at) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$userId, $site, $password, $date])) {
        echo "Password saved successfully!<br><br>";
    } else {
        echo "Error saving password.<br><br>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <form method="POST" action="">
        <label for="site">Website / App Name:</label><br>
        <input type="text" name="site" required><br><br>

        <label for="password">Password (generated or custom):</label><br>
        <input type="text" name="password" required><br><br>

        <input type="submit" value="Save Password">
    </form>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
