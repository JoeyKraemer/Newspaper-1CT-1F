<!doctype html>
<html>
<head>
    <meta charset="UTF8">
    <title> Gemorskos header </title>
    <link rel= "stylesheet" href="css/login.css">
</head>
<body>
    <div id="container">
        <main>
            <div id="content">
                <h1> Gemorskos Intranet </h1>
                <p> Please enter the required information and follow the instructions sent to your inbox for resetting your password </p>
                <form action="loginProcess.php" method="POST">  
                    <input type="email" name="email" id="email" placeholder="Email">
                    <input type="password" name="pass" id="pass" placeholder="Password">
                    <input type="submit" id="submit" value="Log In"> 
                </form>
                <a href="recover.php"> Forgot your password? </a>  
                <?php
                if($wrong = filter_input(INPUT_GET,"retry",FILTER_VALIDATE_BOOLEAN)) {
                    var_dump($wrong);
                    if ($wrong === TRUE) {
                    ?>
                    <p id="retryPhrase">Your username or your password is incorrect</p>
                    <?php
                    }
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>