<?php
session_start();
if(isset($_SESSION['user'])){
    header('Location: calendar.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/headerStyle.css">
   <title>Gemorskos</title>
</head>

<body>
    <div id="container">
        <header>
            <p> Gemorskos </p>
            <nav>
                <ul>
                    <li> <a href="privateFilesPage.php"> <img src="img/folder.svg" alt="filesbutton"/> </a> </li>
                    <li> <a href="calendar.php"> <img src="img/calendar.svg" alt="calendarbutton"/> </a> </li>
                    <li> <a href="profilePage.php"> <img src="img/person.svg" alt="profilebutton"/> </a> </li>
                </ul>
            </nav>
        </header>

        <main> main content </main>
        <footer> footer </footer>
    </div>
</body>
</html>
