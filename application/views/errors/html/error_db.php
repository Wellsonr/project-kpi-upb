<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
<head><title>Database Error</title></head>
<body>
<h1><?php echo $heading; ?></h1>
<?php foreach ((is_array($message) ? $message : array($message)) as $error): ?>
<p><?php echo $error; ?></p>
<?php endforeach; ?>
</body>
</html>
