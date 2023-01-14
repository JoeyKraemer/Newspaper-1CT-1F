<?php
session_start();
if(isset($_SESSION['user'])){
    $user_id = intval($_SESSION['user']);
}
else{
    header('Location: login.php');
    exit;
}
$dbname = "gemorskos";
try{
    $dbHandler = new PDO("mysql:host={$_ENV["DB_SERVER"]}; dbname=$dbname; charset=utf8", $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
}
catch(Exception $ex){
    echo "The following exception has occurred $ex";
}

$stmt = $dbHandler->prepare("SELECT * FROM Users WHERE user_id=:sessionId");
$stmt->bindParam("sessionId",$user_id,PDO::PARAM_INT);
$stmt->execute();
$stmt->bindColumn("email_address",$userEmail);

$result = $stmt->fetchall();
var_dump($result);
?>
<form action="passwordChange.php" method="post">
    <input type="email" name="email" id="email" value="<?php echo $userEmail; ?>">
    <input type="password" name="pass" id="pass">
    <input type="submit" id="submit" value="Change passsword"> 
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = password_hash(filter_input(INPUT_POST,"pass"),PASSWORD_BCRYPT);
    $email = filter_input(INPUT_POST,"email");
    $stmt = $dbHandler->prepare("UPDATE Users SET user_password=:newPassword WHERE email_address=:userMail");
    $stmt->bindParam("newPassword",$password,PDO::PARAM_STR);
    $stmt->bindParam("userMail",$email,PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->execute()) {
       echo "Password has been changed.";
       echo "<p><a href='index.php'>Go back </a></p>";
    }
}
?>