<?php
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
    <link type="text/css" rel="stylesheet" href="CalendarCSS.css">
    <link type="text/css" rel="stylesheet" href="normalize.css">
    <title>Title</title>
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
</head>
<body>
<div id="container">

    <header>
    </header>
    <main>
        <div class="WelcomeUser">
            <h2>Welcome, Firstname</h2>
        </div>

<div id="calendar">
    <div id="dateAndEvents">
        <div id="date"><span style="font-size: 3em;" id="calendarDate">22</span><h3>November <br> 2022</h3></div>
        <div class="eventList">
            <?php
            if(isset($dbHandler)){
                try {
                    $stmt = $dbHandler->prepare("SELECT * FROM `Events` WHERE event_date = :event_date");
                    $stmt->bindParam("event_date", $_GET['event_date'], PDO::PARAM_STR);

                    $stmt->execute();
                    $value = $stmt->fetch();
                    $stmt->rowCount() > 0;

                }
                catch(Exception $ex){
                    echo "The following exception has occurred $ex";
                }
            }
            else{
                echo "No events planned";
            }

            var_dump($value);
            ?>
        <br>
        <u>My Schedule</u>
        <ul>Local Festival</ul>
            <li>10:00-18:00</li>
            <li>Event info</li>
        </div>
    </div>
    <div id="daysAndWeeks">
        <table>
            <tr>
                <th>S</th>
                <th>M</th>
                <th>T</th>
                <th>W</th>
                <th>T</th>
                <th>F</th>
                <th>S</th>
            </tr>
            <?

            echo "<tr>";
            for ($i = 1; $i<=30; $i++) {
                echo "<td data-day='$i'>$i</td>";
                if ($i % 7 == 0) {
                    echo "</tr><tr>";
                }
                if ($i == 30) {
                    for ($j=0;$j<5;$j++) {
                        echo "<td></td>";
                    }
                    echo "</tr>";
                }
            }

            ?>
        </table>
    </div>
</div>
</main>
    <footer>

    </footer>
</div>
<script>
    $('td').on('click', function(){
        let selecteDate = $(this).data('day');
        let dateTarget = $('#calendarDate');

        dateTarget.text(selecteDate)
    });
</script>
</body>
</html>

