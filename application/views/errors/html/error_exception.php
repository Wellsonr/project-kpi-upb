<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body>
    <h1>PHP Error</h1>

    <p>Severity: <?php echo isset($severity) ? $severity : ''; ?></p>
    <p>Message: <?php echo isset($message) ? $message : ''; ?></p>
    <p>Filename: <?php echo isset($filepath) ? $filepath : ''; ?></p>
    <p>Line Number: <?php echo isset($line) ? $line : ''; ?></p>
</body>
</html>