<?php include('Server.php'); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2 Factor Authentication</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="header">
    <h2>Login</h2>
</div>

<form method="post" action="Login.php">

    <?php include('errors.php'); ?>

    <div class="input-group">
        <label>Username</label>
        <input type="text" name="username">
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="text" name="password">
    </div>

    <div class="input-group">
        <button type="submit" name="login" class="btn">Login</button>
    </div>

    <p>
        Not a user? <a href = "Register.php">Sign-up</a>
    </p>

</form>
</body>
</html>
