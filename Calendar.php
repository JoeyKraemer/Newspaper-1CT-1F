<?php
session_start();
if (!isset($_SESSION["month"])) {
    $_SESSION["month"] = date("m");
}
try{
    $dbHandler = new PDO("mysql:host=mysql;dbname=gemorskos;charset=utf8", "root", "qwerty");
}
catch(Exception $ex){
    echo "The following exception has occurred $ex";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/CalendarCSS.css">
    <link type="text/css" rel="stylesheet" href="css/normalize.css">
    <title>Title</title>
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>
<body>

<div id="container">
    <header>
        <p> Gemorskos </p>
        <nav>
            <ul>
                <li> <a href="#"> <img src="../img/folder.svg" alt="filesbutton"/> </a> </li>
                <li> <a href="#"> <img src="../img/calendar.svg" alt="calendarbutton"/> </a> </li>
                <li> <a href="#"> <img src="../img/person.svg" alt="profilebutton"/> </a> </li>
            </ul>
        </nav>
    </header>
        <?php
        if(isset($dbHandler)) {
            try {
                $welcomeUser = "Welcome, " . $_GET['user_id'];
                $userstmt = $dbHandler->prepare("SELECT * FROM `Users` WHERE user_id = 7");
                $userstmt->bindParam('user_id', $welcomeUser, PDO::PARAM_STR);

                $userstmt->execute();
                if ($user = $userstmt->fetch(PDO::FETCH_OBJ)) {
                    echo "<div class='WelcomeUser'><h2>Welcome," . $user . "</h2></div>";
                }
            } catch (Exception $e) {

            }
        }
        ?>
    <div id="calendar">
        <div id="dateAndEvents">
            <?php
            $today = "";
            $months = array('January','February','March','April','May','June','July','August','September','October','November','December');

            if($_SERVER['REQUEST_METHOD'] === 'GET') {
                // set date
                if (empty($_GET["event_date"])) {
                    $today = date("d");
                } else {
                    $today = $_GET["event_date"];
                }

                if (isset($_GET["month"])) {
                    if ($_GET["month"][0] == "+") {
                        if ($_SESSION["month"] < 11) {
                            $_SESSION["month"] += intval($_GET["month"][1]);
                        }
                    } else {
                        if ($_SESSION["month"] >= 1) {
                            $_SESSION["month"] -= intval($_GET["month"][1]);
                        }
                    }
                }

                echo '<div id="date"><span style="font-size: 3em;" id="calendarDate">' . $today . '</span>        
                          <div class="monthSelect">
                              <form action="Calendar.php" method="get">
                                  <input type="submit" value="<">
                                  <input type="hidden" name="month" value="-1">
                              </form>
                              <p style="margin: 0"><h3>'. $months[$_SESSION["month"]] . '<br>' . date("o") . '</h3></p>
                              <form action="Calendar.php" method="get">
                                  <input type="submit" value=">">
                                  <input type="hidden" name="month" value="+1">
                              </form>
                          </div>
                      </div>';
            }
            ?>
            <div class="eventList">
                <?php
                if(isset($dbHandler)){
                    try {
                        $currDate = date("o") . "-" . date("m") . "-" . $today;
                        $stmt = $dbHandler->prepare("SELECT * FROM `Events` WHERE event_date = :event_date");
                        $stmt->bindParam("event_date", $currDate, PDO::PARAM_STR);

                        $stmt->execute();
                        $value = $stmt->fetch(PDO::FETCH_OBJ);
                        if ($stmt->rowCount() > 0) {
                            echo "<br>
                                  <u>Upcoming Events</u>
                                  <ul>" . $value->event_name . "</ul>
                                  <li>" . $value->event_description . "</li>
                                  <li>" . $value->event_time . " "  . $value->event_date . "</li>
                                  <li>" . $value->location_street . " " . $value->location_city . ", " . $value->location_postal_code . "</li>";
                        }
                    }
                    catch(Exception $ex){
                        echo "The following exception has occurred $ex";
                    }
                }
                else{
                    echo "No events planned";
                }
                ?>
            </div>
        </div>
        <table border='0'>

            <?php
            $date = getdate();

            $mday = $date['mday'];
            $mon = $_SESSION["month"] +1;
            $wday = $date['wday'];
            $month = $date['month'];
            $year = $date['year'];

            $dayCount = $wday;
            $day = $mday;

            while($day > 0) {
                $days[$day--] = $dayCount--;
                if($dayCount < 0)
                    $dayCount = 6;
            }

            $dayCount = $wday;
            $day = $mday;

            if(checkdate($mon,31,$year))
                $lastDay = 31;
            elseif(checkdate($mon,30,$year))
                $lastDay = 30;
            elseif(checkdate($mon,29,$year))
                $lastDay = 29;
            elseif(checkdate($mon,28,$year))
                $lastDay = 28;

            while($day <= $lastDay) {
                $days[$day++] = $dayCount++;
                if($dayCount > 6)
                    $dayCount = 0;
            }

            // Days to highlight
            $day_to_highlight = array(8, 9, 10, 11, 12, 22,23,24,25,26);

            echo("<tr>");
            echo("</tr>");
            echo("<tr>");
            echo("<td>S</td>");
            echo("<td>M</td>");
            echo("<td>T</td>");
            echo("<td>W</td>");
            echo("<td>T</td>");
            echo("<td>F</td>");
            echo("<td>S</td>");
            echo("</tr>");

            $startDay = 0;
            $d = $days[1];

            echo("<tr>");
            while($startDay < $d) {
                echo("<td></td>");
                $startDay++;
            }

            for ($d=1;$d<=$lastDay;$d++) {
                if (in_array( $d, $day_to_highlight))
                    $bg = "bg-green";
                else
                    $bg = "bg-white";
                // Highlights the current day
                if($d == $mday)
                    echo("<td class='bg-blue'><a href='?event_date=$d' title='Detail of day'>$d</a></td>");
                else
                    echo("<td class='$bg'><a href='?event_date=$d' title='Detail of day'>$d</a></td>");


                $startDay++;
                if($startDay > 6 && $d < $lastDay){
                    $startDay = 0;
                    echo("</tr>");
                    echo("<tr>");
                }
            }
            echo("</tr>");
            // TODO: Welcome user, connect header footer
            ?>
        </table>
    </div>
    <footer>
    </footer>
</div>
</body>
</html>