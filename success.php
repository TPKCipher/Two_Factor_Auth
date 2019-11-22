<?php include('Server.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2 Factor Authentication</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="header">
    <h2>Success</h2>
</div>

<div class="content">
    <?php if (isset($_SESSION['success'])): ?>
        <h3>
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </h3>
    <?php endif ?>

    <?php if (isset($_SESSION['username'])): ?>
        <p>
            Welcome <strong><?php echo $_SESSION['username']; ?></strong>
        </p>
        <p>
            <a href="success.php?logout='1'" style="color: red;">Logout</a>
        </p>
    <?php endif ?>
</div>

</body>
</html>