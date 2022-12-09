<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="CalendarCSS.css">
    <link type="text/css" rel="stylesheet" href="normalize.css">
    <title>Title</title>
</head>

<div id="container">

    <header>
    </header>
    <main>
        <div class="WelcomeUser">
            <h2>Welcome, Firstname</h2>
        </div>

<div id="calendar">
    <div id="dateAndEvents">
        <div id="date"><span style="font-size: 3em;">22</span><h3>November <br> 2022</h3></div>
        <div class="eventList">
            <u>Upcoming Events</u>
            <ul>Local Festival</ul>
                <li>10:00-18:00</li>
                <li>Event info</<li>
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
                echo "<td>$i</td>";
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
</body>
</html>

