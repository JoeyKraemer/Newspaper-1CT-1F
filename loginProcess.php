<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST,"email",FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST,"pass");
    $dbname = "gemorskos";
    try {
        $dbhandler = new PDO("mysql:host={$_ENV["DB_SERVER"]}; dbname=$dbname; charset=utf8", $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
    } catch (Exception $ex){
        print $ex;
    }  

    $stmt = $dbhandler->prepare("SELECT * FROM User WHERE email_address = :email");
    $stmt->bindParam("email",$email,PDO::PARAM_STR);
    $stmt->execute();

    $stmt->bindColumn("email_address",$dbEmail);
    $stmt->bindColumn("user_password",$dbPassword);

    $stmt->fetch();
    if (!is_null($dbEmail)) {
        if (password_verify($password,$dbPassword)) {
            $_SESSION["user"] = $email;
            Header("Location:index.php");
        }
        else{
            Header("Location:login.php?retry=TRUE");
            //include "login.php?password=wrong";
        }
    }
    else {
        Header("Location:login.php?retry=TRUE");
       // include "login.php?username=wrong";
    }
}
else {
    echo "refresh the page";
}
?>
