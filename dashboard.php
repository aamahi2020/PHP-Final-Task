<?php
session_start();
include(__DIR__ . '/classes/Database.php');
include(__DIR__ . '/classes/PasswordGenerator.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$db = new Database();
$conn = $db->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set_parameters'])) {
    $_SESSION['password_params'] = [
        'length' => (int)$_POST['length'],
        'lowercase' => (int)$_POST['lowercase'],
        'uppercase' => (int)$_POST['uppercase'],
        'numbers' => (int)$_POST['numbers'],
        'special' => (int)$_POST['special']
    ];
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_password'])) {
    $site = $_POST['site'];
    $userId = $_SESSION['user_id'];
    $params = $_SESSION['password_params'] ?? null;

    if ($params) {
        $generator = new PasswordGenerator();
        $password = $generator->generate(
            $params['length'],
            $params['lowercase'],
            $params['uppercase'],
            $params['numbers'],
            $params['special']
        );

        $sql = "INSERT INTO passwords (user_id, website, password, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$userId, $site, $password])) {
            $message = "Password generated and saved successfully!";
        } else {
            $message = "Error saving password.";
        }
    } else {
        $message = "Please set password parameters first.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_parameters'])) {
    unset($_SESSION['password_params']);
    header("Location: dashboard.php");
    exit();
}

$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM passwords WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$savedPasswords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <!-- Step 1: Show password parameter form if not set -->
    <?php if (!isset($_SESSION['password_params'])): ?>
        <h3>Set Password Parameters</h3>
        <form method="POST">
            <label>Length:</label><br>
            <input type="number" name="length" required><br><br>

            <label>Lowercase Letters:</label><br>
            <input type="number" name="lowercase" required><br><br>

            <label>Uppercase Letters:</label><br>
            <input type="number" name="uppercase" required><br><br>

            <label>Numbers:</label><br>
            <input type="number" name="numbers" required><br><br>

            <label>Special Characters:</label><br>
            <input type="number" name="special" required><br><br>

            <input type="submit" name="set_parameters" value="Set Parameters">
        </form>
    <?php else: ?>
        <!-- Step 2: Password save form -->
        <h3>Generate & Save Password</h3>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <form method="POST">
            <label>Website / App Name:</label><br>
            <input type="text" name="site" required><br><br>

            <input type="submit" name="save_password" value="Generate & Save Password">
        </form>

        <form method="POST">
            <input type="submit" name="reset_parameters" value="Change Password Parameters">
        </form>
    <?php endif; ?>

    <!-- Display saved passwords -->
    <h3>Saved Passwords</h3>
    <?php if (count($savedPasswords) > 0): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Website</th>
                <th>Password</th>
                <th>Created At</th>
            </tr>
            <?php foreach ($savedPasswords as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['website']); ?></td>
                    <td><?php echo htmlspecialchars($row['password']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No passwords saved yet.</p>
    <?php endif; ?>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
