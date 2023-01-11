<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="CalendarCSS.css">
    <link type="text/css" rel="stylesheet" href="normalize.css">
    <title>Title</title>
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
        <div id="date"><span style="font-size: 3em;" id="calendarDate">10</span><h3>January <br> 2023</h3></div>
        <div class="eventList">
            <u>Upcoming Events</u>
            <div id = "Events"> <span style="..." id="EventsData">-</span><li>-</li></div>
            <ul>Local Festival</ul>
                <div id = "Festival"> <span style="..." id="FestivalDate">-</span><li>-</li></div>
                <div id = "Festival"> <span style="..." id="FestivalInfo">-</span><li>-</li></div>

            <br>
        <u>My Schedule</u>
        <ul>Local Festival</ul>
            <li>10:00-18:00</li>
            <li>Event info</li>
        </div>
    </div>
    <table border='0'>
        <?php
        $date = getdate();

        $mday = $date['mday'];
        $mon = $date['mon'];
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
        echo("<td>Sun</td>");
        echo("<td>Mon</td>");
        echo("<td>Tue</td>");
        echo("<td>Wed</td>");
        echo("<td>Thu</td>");
        echo("<td>Fri</td>");
        echo("<td>Sat</td>");
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
                echo("<td class='bg-blue'><a href='#' title='Detail of day'>$d</a></td>");
            else
                echo("<td class='$bg'><a href='#' title='Detail of day'>$d</a></td>");


            $startDay++;
            if($startDay > 6 && $d < $lastDay){
                $startDay = 0;
                echo("</tr>");
                echo("<tr>");
            }
        }
        echo("</tr>");
        // TODO: Handle PHP events, buttons for calendar via js, connect with database
        ?>
    </table>
    </main>
</div>
<script src="js/index.js"</script>
</body>
</html>

