<?php

include(__DIR__ . '/classes/PasswordGenerator.php');

$generatedPassword = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $length     = (int) $_POST['length'];
    $lowercase  = (int) $_POST['lowercase'];
    $uppercase  = (int) $_POST['uppercase'];
    $numbers    = (int) $_POST['numbers'];
    $special    = (int) $_POST['special'];

    
    if (($lowercase + $uppercase + $numbers + $special) > $length) {
        $generatedPassword = "Error: Character counts exceed the total length.";
    } else {
        $generator = new PasswordGenerator();
        $generatedPassword = $generator->generate($length, $lowercase, $uppercase, $numbers, $special);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Generator</title>
</head>
<body>
    <h2>Password Generator</h2>

    <form method="POST" action="">
        <label>Total Length:</label><br>
        <input type="number" name="length" min="1" required><br><br>

        <label>Lowercase Letters:</label><br>
        <input type="number" name="lowercase" min="0" required><br><br>

        <label>Uppercase Letters:</label><br>
        <input type="number" name="uppercase" min="0" required><br><br>

        <label>Numbers:</label><br>
        <input type="number" name="numbers" min="0" required><br><br>

        <label>Special Characters:</label><br>
        <input type="number" name="special" min="0" required><br><br>

        <input type="submit" value="Generate Password">
    </form>

    <?php if (!empty($generatedPassword)): ?>
        <h3>Generated Password:</h3>
        <p style="font-weight: bold;"><?php echo htmlspecialchars($generatedPassword); ?></p>
    <?php endif; ?>

    <br><a href="dashboard.php">Go to Dashboard</a>
</body>
</html>
