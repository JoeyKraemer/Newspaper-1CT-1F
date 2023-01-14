<!doctype html>
<html>
<head>
    <meta charset="UTF8">
    <title> Gemorskos header </title>
    <link rel= "stylesheet" href="css/recover.css">
</head>
<body>
    <div id="container">

        <main>
        <div id="content">
        
            <h1> Gemorskos Intranet </h1>
            
            <p> Please enter the required information and follow the instructions sent to your inbox for resetting your password. </p>

            <form action="actionrecover.php" method="POST">  
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="text" name="idNumber" id="idNumber" placeholder="Employee ID Number">

            <input type="submit" id="submit" name="reset-request-submit" value="Submit"> 
            </form>
            
            <button type="button"> <a href="login.php"> Back to login </a> </button>
            
            <?php
            if (isset($_GET["reset"])) {
                if ($_GET["reset"] == "success") {
                    echo '<p> Check your e-mail! </p> ';
                }
            }
            ?>
            
        </div>		
        </main>

    </div>
</body>
</html>