<!doctype html>
<html>
<head>
    <meta charset="UTF8">
    <title> Gemorskos header </title>
    <link rel= "stylesheet" href="css/recover.css">
</head>
<body>
    <!-- grid -->
    <div id="container">

        <!-- main section -->
        <main>
        <div id="content">
        
            <h1> Gemorskos Intranet </h1>
            
            <p> Please enter the required information and follow the instructions sent to your inbox for resetting your password </p>

            <!-- form -->
            <form action="actionrecover.php" method="POST">  
            <input type="email” name="email" id="email" placeholder="Email">
            <input type="text" name="idNumber" id="idNumber" placeholder="Employee ID Number">

            <input type="submit" id="submit" value="Submit"> 
            </form>
            
            <button type="button"> <a href="login.php"> Back to login </a> </button>
            
        </div>		
        </main>

    </div>
</body>
</html>