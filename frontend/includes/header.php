<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Skill Swap</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
    </style>
</head>
<body>

<?php if (isset($_SESSION['user'])) : ?>
    <a href="logout.php" class="logout-link" 
       onclick="return confirm('Are you sure you want to logout?');">
       Logout
    </a>
<?php endif; ?>