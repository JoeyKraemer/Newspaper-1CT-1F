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
<?php
    if(isset($dbHandler)){
        try {
            $stmt = $dbHandler->prepare("SELECT * FROM `Events`");
            $stmt->execute();
        }
        catch(Exception $ex){
            echo "The following exception has occurred $ex";
        }
    }
?>
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
            if(isset($stmt)){
                if($stmt->rowCount() > 0){
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $result){
                        echo "<u>Upcoming Events</u>";
                        echo "<ul>" . $result["event_name"] . "</ul>";
                        echo "<li>" . $result["event_description"] . "</li>";
                        echo "<li>" . $result["location_street"] . "</li>";
                        echo "<li>" . $result["location_postal_code"] . "</li>";
                        echo "<li>" . $result["location_city"] . "</li>";
                        echo "<li>" . $result["event_time"] . "</li>";
                        echo "<li>" . $result["event_date"] . "</li>";
                        echo "<li>" . $result["event_max_participant"] . "</li>";
                        echo "<li>" . $result["active"] . "<  /li>";
                    }
                }
                else{
                    echo "No events scheduled";
                }
            }
            ?>
        <br>
            <?php
            if(isset($dbHandler)){
                try {
                    $stmt = $dbHandler->prepare("SELECT * FROM `Events`");
                    $stmt->execute();
                }
                catch(Exception $ex){
                    echo "The following exception has occurred $ex";
                }
            }
            ?>
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

