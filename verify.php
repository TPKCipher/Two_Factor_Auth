<?php include('Server.php'); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2 Factor Authentication</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="header">
    <h2>Login Verification</h2>
</div>

<form method="post" action="verify.php">
    <?php if (isset($_SESSION['verify'])): ?>
        <h3>
            <?php
            echo $_SESSION['verify'];
            unset($_SESSION['verify']);
            ?>
        </h3>
    <?php endif ?>
    <?php include('errors.php'); ?>

    <div class="input-group">
        <label>4 - Digit Verification</label>
        <input type="text" name="code">
    </div>

    <div class="input-group">
        <button type="submit" name="verify" class="btn">Verify</button>
    </div>

</form>
</body>
</html>