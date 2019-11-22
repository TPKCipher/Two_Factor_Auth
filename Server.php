<?php

if (!isset($_SESSION))
{
    session_start();
}

$Username = "";
$Email = "";
$errors = array();
$randVerification = "";
$Verification = "";
$db = mysqli_connect('localhost', 'root', '', 'software_security_twofactor');

if (isset($_POST['register']))
{
    $Username = mysqli_real_escape_string($db, $_POST['username']);
    $Email = mysqli_real_escape_string($db, $_POST['email']);
    $Password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $Password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    if (empty($Username))
    {
        array_push($errors, "Username is Required");
    }
    if (empty($Email))
    {
        array_push($errors, "Email is Required");
    }
    if (empty($Password_1))
    {
        array_push($errors, "Password is Required");
    }
    if ($Password_1 != $Password_2)
    {
        array_push($errors, "Passwords must match");
    }

    if (count($errors) == 0)
    {

        $password = md5($Password_1);
        $sql = "INSERT INTO users (username, email, Password) 
                    VALUES ('$Username', '$Email', '$password')";
        mysqli_query($db, $sql);

        $_SESSION['username'] = $Username;
        $_SESSION['success'] = "You have successfully registered a User";

        header('location: success.php');
        filter_var_array($_POST, FILTER_SANITIZE_STRING);
    }

}

//  Login
if (isset($_POST['login']))
{
    $Username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($Username))
    {
        array_push($errors, "Username is Required");
    }
    if (empty($password))
    {
        array_push($errors, "Password is Required");
    }


        if (count($errors) == 0)    // Entries in all fields
    {
        // Check for user
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username = '$Username' AND password = '$password'";
        $result = mysqli_query($db, $query);

        if (mysqli_num_rows($result) == 1)
        {
            // Send Verification email
            $query = "SELECT email FROM users WHERE username = '$Username' AND password = '$password'"; // get email
            $result = mysqli_query($db, $query);

            $randVerification = rand(1000,9999);    // make code
            sendEmail($Email, $randVerification);   // Send Email

            // Go to verification page
            $_SESSION['verify'] = "Please enter 4-digit verification code emailed to you.";
            header('location: verify.php');
        }
    }
}

if (isset($_POST['verify']))
{
    // Code Entered
    $Verification = mysqli_real_escape_string($db, $_POST['code']);

    if ($randVerification != $Verification)
    {
        array_push($errors, "Invalid verification code");   // Wrong Code
    }

    if (count($errors) == 0) // Not wrong code
    {
        // Go to logged in state
        $_SESSION['username'] = $Username;
        $_SESSION['success'] = "You have successfully logged in.";
        header('location: success.php');
    }
}


// logout
if (isset($_GET['logout']))
{
    session_destroy();
    unset($_SESSION['username']);
    header('location: Login.php');
}

function sendEmail($Email, $randVerification)
{
    require_once('PHPMailer/PHPMailerAutoload.php');
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = '465';
    $mail->isHTML();
    $mail->Username = 'codetester2000@gmail.com';
    $mail->Password = 'SoftwareSafetySecurity360';
    $mail->SetFrom('codetester2000@gmail.com');
    $mail->Subject = '4 - Digit Verification';
    $mail->Body = 'Enter this code ... '.$randVerification;
    $mail->addAddress($Email);
    $mail->Send();
    return $randVerification;
}

?>