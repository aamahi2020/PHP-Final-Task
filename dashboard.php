<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include(__DIR__ . '/classes/Database.php');
include(__DIR__ . '/classes/PasswordGenerator.php');

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $site = $_POST['site'];

    // Generate password
    $generator = new PasswordGenerator();
    $password = $generator->generate(9, 2, 3, 2, 2); // 9 chars: 2 lowercase, 3 uppercase, 2 numbers, 2 special

    $sql = "INSERT INTO passwords (user_id, website, password, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId, $site, $password]);

    echo "<p style='color:green;'>Password generated and saved for $site!</p>";
}

// Fetch saved passwords
$sql = "SELECT website, password, created_at FROM passwords WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <form method="POST">
        <label>Website / App Name:</label><br>
        <input type="text" name="site" required><br><br>
        <input type="submit" value="Generate & Save Password">
    </form>

    <h3>Your Saved Passwords</h3>
    <?php if (count($passwords) > 0): ?>
        <table>
            <tr>
                <th>Website</th>
                <th>Password</th>
                <th>Created At</th>
            </tr>
            <?php foreach ($passwords as $entry): ?>
                <tr>
                    <td><?php echo htmlspecialchars($entry['website']); ?></td>
                    <td><?php echo htmlspecialchars($entry['password']); ?></td>
                    <td><?php echo $entry['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No passwords saved yet.</p>
    <?php endif; ?>

    <br><a href="logout.php">Logout</a>
</body>
</html>
