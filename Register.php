<?php include('Server.php'); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2 Factor Authentication</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Register</h2>
    </div>

    <form method="post" action="Register.php">

        <?php include('errors.php'); ?>

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo $Username; ?>">
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="text" name="email"  value="<?php echo $Email; ?>">
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password_1">
        </div>

        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" name="password_2">
        </div>

        <div class="input-group">
            <button type="submit" name="register" class="btn">Register</button>
        </div>

        <p>
           Already a user? <a href = "Login.php">Sign-in</a>
        </p>

    </form>
</body>
</html>