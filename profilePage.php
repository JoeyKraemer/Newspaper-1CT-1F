<?php
session_start();
if(isset($_SESSION['user'])){
    $user_ID = intval($_SESSION['user']);
}
else{
    header('Location: login.php');
    exit;
}

try{
    $dbHandler = new PDO("mysql:host=mysql;dbname=gemorskos;charset=utf8", "root", "qwerty");
}
catch(Exception $ex){
    echo "The following exception has occurred $ex";
}

$stmt = $dbHandler->prepare("SELECT photo FROM `Users`");
$stmt->execute();
$photos = $stmt->setFetchMode(PDO ::FETCH_OBJ);
foreach ($stmt->fetchall() as $photo) {
    $image = $photo->photo;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Page</title>
    <link rel="stylesheet" href="css/profilePage.css">
    <link rel="stylesheet" href="css/headerStyle.css">
</head>
<body>
<div id="gridContainer">
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
<div class='falseHeader'></div>
    <main>
        <div id="content">
            <img src="upload/<?php echo $image ?>" alt="" id="circle">
            <?php
            $stmt = $dbHandler->prepare("SELECT first_name, last_name FROM `Users`
                                        WHERE Users.user_id = :userid");
            $stmt->bindParam('userid', $user_ID, PDO::PARAM_STR);
            $stmt->execute();
            $table = $stmt->setFetchMode(PDO::FETCH_OBJ);

            foreach ($stmt->fetchall() as $tables) {

                echo '<h1>' . $tables->first_name . ' ' . $tables->last_name . '</h1>';

            }
            ?>
            <div id="accountDetails">
                <?php

                $stmt = $dbHandler->prepare("SELECT user_role, type_of_staff  FROM `Users`
                                             WHERE Users.user_id = :userid");
                $stmt->bindParam('userid', $user_ID, PDO::PARAM_STR);
                $stmt->execute();
                $job = $stmt->setFetchMode(PDO::FETCH_OBJ);
                foreach ($stmt->fetchall() as $jobs) {

                    echo '<h2> Your Role: ' . $jobs->user_role . ' ' . '</h2>';
                    echo '<h2>  Jobs:</h2>';
                }
                $stmt = $dbHandler->prepare("SELECT Events.event_name, Events.event_description, Events.event_date 
                                                    FROM Events, Event_Details, Users
                                                    WHERE Events.event_id = Event_Details.event_id
                                                    AND Event_Details.user_id = Users.user_id
                                                    AND Users.user_id = :userid;");
                $stmt->bindParam('userid', $user_ID, PDO::PARAM_STR);
                $stmt->execute();
                $jobName = $stmt->setFetchMode(PDO::FETCH_OBJ);
                foreach ($stmt->fetchall() as $jobName) {
                    echo '<div class ="jobBox">';
                    echo '<p>' . $jobName->event_name . '    ' . '</p>';
                    echo '<p>' . $jobName->event_description . '    ' . '</p>';
                    echo '<p>' . $jobName->event_date . '    ' . '</p>';
                    echo '</div>';
                }
                ?>
                <a href="calendar.php"> Calendar/schedule </a>
                <a href="passwordChange.php"> Change passsword</a>
                <a href="recover.php"> Forgot your password? </a>
                <h2>Change your profile picture</h2>
                <form action="file.php" enctype="multipart/form-data" method="POST">
                    <input type="file" name="file">
                    <input type="submit" name="submit">
                </form>
            </div>
        </div>
    </main>
    <footer>
    <?php
    // check whether account can use adminTool
    $stmt = $dbHandler->prepare("SELECT type_of_staff FROM `Users` WHERE user_id = :user_id");
    $stmt->bindParam('user_id', $user_ID, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();

    if($result[0] == 1){
        echo "<a href='adminTool.php'>go to adminTool</a>";
    }
    ?>
    <a href='login.php'> Log Out </a>
    </footer>
</div>
</body>
</html>
