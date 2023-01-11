<!doctype html>
<html>
<head>
    <meta charset="UTF8">
    <title> Gemorskos header </title>
    <link rel= "stylesheet" href="css/reset.css">
</head>
<body>

    <div id="container">
        <main>
        <div id="content">
        
            <h1> Gemorskos Intranet </h1>
            
<?php /*
$selector = $_GET["selector"];
$validator = $_GET["validator"];

//check if tokens exist inside the url
if (empty($selector) || empty($validator)) {
    echo "We couldn't validate your request!";
} else {
    if (ctype_xdigit($selector) !== false && (ctype_xdigit($validator) !== false) { */
?>

            <p> Welcome back. Please enter your new password and go back to login. </p>

            <form action="actionreset.php" method="POST">
            <input type="hidden" name="selector" value="<?php echo $selector; ?>">
            <input type="hidden" name="validator" value="<?php echo $validator; ?>">
            
            <input type="password" name="pwd" id="pass1" placeholder="Password">
            <input type="password" name="pwd-repeat" id="pass2" placeholder="Confirm Password">
            
            <input type="submit" name="reset-password-submit" id="submit" value="Submit"> 
            </form>
            
            <button type="button"> <a href="login.php"> Back to login </a> </button>
            
            <?php
            if (isset($_GET["newpwd"])) {
                if ($_GET["newpwd"] == "passwordupdated") {
                    echo '<p> Your password has been reset! </p> ';
                }
            }
            
            if (isset($_GET["newpwd"])) {
                if ($_GET["newpwd"] == "pwdempty") {
                    echo '<p> Your password cannot be empty! </p> ';
                }
            }
            
            if (isset($_GET["newpwd"])) {
                if ($_GET["newpwd"] == "newpwd=pwdnotsame") {
                    echo '<p> Your passwords are not the same! </p> ';
                }
            }
            ?>

<?php
    }
}
?>       
        </div>		
        </main>
    </div>
</body>
</html>