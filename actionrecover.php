<?php

    //Include required PHPMailer files
	require 'includes/PHPMailer.php';
	require 'includes/SMTP.php';
	require 'includes/Exception.php';
    //Define name spaces
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

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
    
    //sending the email using php mailer    
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->Host = "smtp.gmail.com";
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	$mail->Port = "587";
	$mail->Username = "gemorskosnews@gmail.com";
	$mail->Password = "frwumimtviqgjzgt";
	$mail->Subject = "Reset your password for Gemorskos";
	$mail->setFrom('gemorskosnews@gmail.com');
	$mail->isHTML(true);
    
    //the mail user receives
    $mail->Body = '<p> We received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email. </p>
    <p> Here is your password reset link: <br> <a href="'.$url.'">' .$url.' </a></p>';
    
    //mail is sent to:
    $mail->addAddress($userEmail);
    
    if ($mail->Send()) { 
        header("Location: recover.php?reset=success");
    } 
    
} else {
    header("Location: login.php");
}