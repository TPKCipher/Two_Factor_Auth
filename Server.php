<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

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
//$db = mysqli_connect('localhost', 'root', 'newpassword', 'securityproject');

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
        $sql = "INSERT INTO users (username, email, password) 
                    VALUES ('{$Username}', '{$Email}', '{$password}')";
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
        $query = "SELECT * FROM users WHERE username = '{$Username}' AND password = '{$password}'";
        $result = mysqli_query($db, $query);


        if (mysqli_num_rows($result) == 1)
        {
            // Send Verification email
            $query = "SELECT email FROM users WHERE username = '{$Username}' AND password = '{$password}'"; // get email
            $result = mysqli_query($db, $query);

            $Email = mysqli_fetch_array($result)['email'];


            $randVerification = rand(1000,9999);    // make code
            $_SESSION['verificationnum'] = $randVerification;
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

    if ($_SESSION['verificationnum'] != $Verification)
    {
        array_push($errors, "Invalid verification code");   // Wrong Code

        session_destroy();
        unset($_SESSION['username']);
        unset($_SESSION['verificationnum']);
        header('location: Login.php');
    }

    if (count($errors) == 0) // Not wrong code
    {
        // Go to logged in state
        $_SESSION['username'] = $Username;
        $_SESSION['success'] = "You have successfully logged in.";
        unset($_SESSION['verificationnum']);
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
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username = 'codetester2000@gmail.com';
        $mail->Password = 'SoftwareSafetySecurity360';                              // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->SetFrom('codetester2000@gmail.com');
        $mail->addAddress($Email);


        // Content
        //$mail->isHTML();
        $mail->Subject = '4 - Digit Verification';
        $mail->Body = 'Enter this code ... '.$randVerification;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return $randVerification;
}
?>