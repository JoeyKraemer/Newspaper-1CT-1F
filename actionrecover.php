<?php /*
 
session_start();
// connection file

 $DB_host = "localhost";
$DB_user = "root";
$DB_pass = "qwerty";
$DB_name = "";

try
{
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}
*/

<?php

if(isset($_POST["reset-request-submit"])) {
    
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);
    
    //this is the url the user will receive by mail
    $url = "www.gemorskos/reset.php?selector=" . $selector . "&validator=" . bin2hex($token);
    
    //expire date for the token (an hour)
    $expires = date("U") + 1800;
    
    //connect to database
    require 'dbh.inc.php';
    
    $userEmail = $_POST["email"];
    
    //delete any existing token from the same user (if an user tries to get an email sent twice without reseting their pass first)
    $sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error!";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
    }
    
    $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error!";
        exit();
    } else {
        $hashedToken = password_hash($token, PASSWORD_DEFAULT); //hash the token for security
        mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
        mysqli_stmt_execute($stmt);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    //sending the email
    $to = $userEmail;
    
    //the subject of the mail user receives
    $subject = 'Reset your password for Gemorskos';
    
    //the mail user receives
    $message = '<p> We received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email. </p>';
    $message .='<p> Here is your password reset link: <br>';
    $message .='<a href="'.$url.'">' .$url.' </a></p>';
    
    //info sent with the email
    $headers = "From: Gemorskos <germoskos@gmail.com>\r\n";
    $headers .= "Repy-To: germoskos@gmail.com\r\n";
    $headers .= "Content-typeL text/html\r\n";
    
    mail($to, $subject, $message, $headers); //this function requires a working mail server running  
    
    header("Location: recover.php?reset=success");
    
    
} else {
    header("Location: login.php");
}
