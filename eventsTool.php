<!DOCTYPE HTML>
<html>
    <head>
        <title>Admin Tool</title>
        <meta charset="UTF-8">
        <script type="text/javascript" src="scripts/adminTool.js"></script>
        <link rel="stylesheet" type="text/css" href="css/adminTool.css">
    </head>

    <body>
        <?php
        //placeholder
        $dbname = "gemorskos";
        $userLoggedIn = true;
        $permissions = true;
        $defaultPass = "user123"; // default pass for all users (they will be asked to change it asap)
        try{
            $handler = new PDO("mysql:host={$_ENV["DB_SERVER"]}; dbname=$dbname; charset=utf8", $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        }
        catch(Exception $ex){
            print $ex;
        }
        
        echo "<div id='main'>";
        if($userLoggedIn && $permissions) { 

            //Data manipulation functionality
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $errorMsg = [];
                $deleteFlag = 0; // admin can remove roles/ events/ users if they change all fields to empty

                switch ($_POST["requestType"]) {
                    case 'editEvent':

                        $event_id = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_NUMBER_INT);
                        if(empty($event_id)){
                            $errorMsg[] = "an id could not be fetched";
                        }

                    case 'addEvent':

                        //event name gets set
                        $event_name = filter_input(INPUT_POST, 'event_name', FILTER_SANITIZE_SPECIAL_CHARS);
                        if ($event_name === null || $event_name === false) {
                            $errorMsg[] = "something went wrong while fetching the event name";
                        } elseif (empty($event_name)) {
                            $errorMsg[] = "an event name is required";
                            $deleteFlag++;
                        } else {
                            if (strlen($event_name) > 70) {
                                $errorMsg[] = "only a max of 70 characters is allowed for the event name";
                            }

                            $stmt = $handler->prepare("SELECT count(event_name) FROM `Events` WHERE event_name = '{$event_name}'");
                            $stmt->execute();
                            $count = $stmt->fetch()[0];
                            if($count != 0){
                                $errorMsg[] = "event name has to be unique";
                            }
                        }



                        // event description gets set
                        $event_description = filter_input(INPUT_POST, 'event_description', FILTER_SANITIZE_SPECIAL_CHARS);
                        if ($event_description === null || $event_description === false) {
                            $errorMsg[] = "something went wrong while fetching the event description";
                        } elseif (empty($event_description)) {
                            $errorMsg[] = "a role description is required";
                            $deleteFlag++;
                        }

                        // street name gets set
                        $location_street = filter_input(INPUT_POST, 'location_street');
                        if($location_street === null || $location_street === false){
                            $errorMsg[] = "something went wrong while fetching the street location";
                        } else if(empty($location_street)){
                            $location_street = null;
                            $deleteFlag++;
                        } else {
                            if(preg_match("/[^a-zA-Z1-9\s]/", $location_street)){
                                $errorMsg[] = "only letters, numbers and spaces are allowed for the street name";
                            }
                            if(strlen($location_street) > 50){
                                $errorMsg[] = "the street name only allows a max of 50 characters";
                            }
                        }

                        // postal code gets set
                        $location_postal_code = filter_input(INPUT_POST, 'location_postal_code');
                        if($location_postal_code === null || $location_street === false){
                            $errorMsg[] = "something went wrong while fetching the postal code";
                        } elseif(empty($location_postal_code)) {
                            $location_postal_code = null;
                            $deleteFlag++;
                        } else {
                            if (!preg_match('/^\d{4}\w{2}$/', $location_postal_code)) {
                                $errorMsg[] = "a valid (dutch) postal code is required with the format NNNNAA";
                            }
                        }

                        // city name gets set
                        $location_city = filter_input(INPUT_POST, 'location_city');
                        if ($location_city === null || $location_city === false) {
                            $errorMsg[] = "something went wrong while fetching the city location";
                        } elseif(empty($location_city)) {
                            $location_city = null;
                            $deleteFlag++;
                        } else {
                            if(preg_match("/[^a-zA-Z1-9\s]/", $location_city)){
                                $errorMsg[] = "only letters, number and spaces are allowed for the city name";
                            }
                            if (strlen($location_city) > 30) {
                                $errorMsg[] = "only a max of 30 characters is allowed in the city name";
                            }
                        }

                        // event time gets set
                        $event_time = filter_input(INPUT_POST, 'event_time');
                        if ($event_time === null || $event_time === false){
                            $errorMsg[] = "something went wrong while fetching the event time";
                        } elseif(empty($event_time)) {
                            $event_time = null;
                            $deleteFlag++;
                        }

                        // event date gets set
                        $event_date = filter_input(INPUT_POST, 'event_date');
                        if ($event_date === null || $event_date === false){
                            $errorMsg[] = "something went wrong while fetching the event date";
                        } elseif(empty($event_date)) {
                            $event_date = null;
                            $deleteFlag++;
                        }

                        //event max participant gets set, removed errors due to conflict with empty input
                        $event_max_participant = filter_input(INPUT_POST, 'event_max_participant', FILTER_VALIDATE_INT);
                        if($event_max_participant === null || $event_max_participant === false){
                            $event_max_participant = null;
                            $deleteFlag++;
                        }

                        // active status gets set
                        $active = filter_input(INPUT_POST, 'active', FILTER_VALIDATE_BOOL);
                        if($active === null){
                            $active = 0;
                        }

                        //if nothing is filled in while in edit mode, will delete the entry
                        if($deleteFlag == 8 && isset($event_id)){
                            $stmt = $handler->prepare("SELECT count(user_id) FROM `Event_Details` WHERE event_id = $event_id");
                            $stmt->execute();
                            $result = $stmt->fetch()[0];

                            // if people have already signed in, delete their Event_Details
                            if ($result != 0){
                                $stmt = $handler->prepare("DELETE FROM `Event_Details` WHERE event_id = $event_id");
                                $stmt->execute();
                            }

                            $stmt = $handler->prepare("DELETE FROM `Events` WHERE event_id = :event_id");
                            $stmt->bindParam('event_id', $event_id, PDO::PARAM_INT);
                            $stmt->execute();
                            echo "entry has been successfully deleted <br/>";
                            break;
                        }

                        // counting errors
                        if(!count($errorMsg) == 0){
                            echo "<div class='errormessage'>";
                            foreach($errorMsg as $msg){
                                echo "<t>$msg</t><br/>";
                            }
                            echo "</div>";
                            break;
                        }

                        // pushing changes
                        if(isset($event_id)){
                            $stmt = $handler->prepare('UPDATE `Events` SET 
                            event_name = :event_name, 
                            event_description = :event_description, 
                            location_street = :location_street, 
                            location_postal_code = :location_postal_code, 
                            location_city = :location_city,
                            event_time = :event_time,
                            event_date = :event_date,
                            event_max_participant = :event_max_participant,
                            active = :active
                            WHERE event_id = :event_id');
                            $stmt->bindParam('event_id', $event_id, PDO::PARAM_INT);
                        } else {
                            $stmt = $handler->prepare('INSERT INTO `Events`
                            (event_name, event_description, location_street, location_postal_code, location_city, event_time, event_date, event_max_participant, active) VALUES
                            (:event_name, :event_description, :location_street, :location_postal_code, :location_city, :event_time, :event_date, :event_max_participant, :active)
                            ');
                        }
                        $stmt->bindParam('event_name', $event_name, PDO::PARAM_STR);
                        $stmt->bindParam('event_description', $event_description, PDO::PARAM_STR);
                        $stmt->bindParam('location_street', $location_street, ($location_street ? PDO::PARAM_STR : PDO::PARAM_NULL));
                        $stmt->bindParam('location_postal_code', $location_postal_code, ($location_postal_code? PDO::PARAM_STR : PDO::PARAM_NULL));
                        $stmt->bindParam('location_city', $location_city, ($location_city? PDO::PARAM_STR : PDO::PARAM_NULL));
                        $stmt->bindParam('event_time', $event_time, ($event_time? PDO::PARAM_STR : PDO::PARAM_NULL));
                        $stmt->bindParam('event_date', $event_date, ($event_date? PDO::PARAM_STR : PDO::PARAM_NULL));
                        $stmt->bindParam('event_max_participant', $event_max_participant, ($event_max_participant? PDO::PARAM_INT : PDO::PARAM_NULL));
                        $stmt->bindParam('active', $active, PDO::PARAM_BOOL);
                        $stmt->execute();
                        break;
                }
            }

            //show functionality

            $show = 25;  // default of 25 entries
            if (isset($_GET["show"])) {
                if (is_numeric($_GET["show"])) {
                    $show = $_GET["show"];
                }
            }

            $page = 1;  // default page 1
            if (isset($_GET["page"])) { // get current page of entries
                if (is_numeric($_GET["page"])) {
                    $page = (int)$_GET["page"];
                }
            }

            $offset = (($page - 1) * $show);

            if(isset($_GET['search'])){
                $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
            }

            echo "
                <div id='main2'>
                    <div id='searchbar'>
                        <br/>
                        <form action='eventsTool.php' method='get'>
                            <input type='hidden' name='show' value=$show>
                            <input type='hidden' name='page' value=$page>
                            <input type='text' name='search' placeholder='search'/>
                            <input type='submit'/>
                        </form>
                    </div>
            ";

            $returnLink = "eventsTool.php?show={$show}&page={$page}" . (isset($search)?"&page={$search}":"");


            $sort = 'event_id';
            if (isset($_GET["sort"])){
                switch ($_GET["sort"]) {
                    case 'name':
                        $sort = 'event_name';
                        break;
                    case 'description':
                        $sort = 'event_description';
                        break;
                    case 'street':
                        $sort = 'location_street';
                        break;
                    case 'code':
                        $sort = 'location_postal_code';
                        break;
                    case 'city':
                        $sort = 'location_city';
                        break;
                    case 'date':
                        $sort = 'event_date';
                        break;
                    case 'time':
                        $sort = 'event_time';
                        break;
                    case 'max':
                        $sort = 'event_max_participant';
                        break;
                }
            }

            // adds a WHERE parameter in order to search for the customers custom input
            $where = "";
            if(isset($search) AND $search){
                $items = ['event_description', 'location_street', 'location_postal_code', 'location_city'];
                $where = " WHERE event_name LIKE '%{$search}%'";
                foreach($items as $item){
                    $where .= " OR $item LIKE '%{$search}%'";
                }
            }

            // prepares the query and fetches the results
            $stmt = $handler->prepare("SELECT event_id, event_name, event_description, location_street, location_postal_code, location_city, event_date, event_time, event_max_participant, active  FROM `Events`{$where} ORDER BY $sort LIMIT :show OFFSET :offset");
            $stmt->bindParam('show', $show, PDO::PARAM_INT);
            $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll();

            echo "
                <h1>Events</h1>
                <table>
                    <tr>
                        <th> <a href='{$returnLink}&sort=id'>Event Number</a> </th>
                        <th> <a href='{$returnLink}&sort=name'>Name</a> </th>
                        <th> <a href='{$returnLink}&sort=description'>Description</a> </th>
                        <th> <a href='{$returnLink}&sort=street'>Street</a> </th>
                        <th> <a href='{$returnLink}&sort=code'>Postal Code</a> </th>
                        <th> <a href='{$returnLink}&sort=city'>City</a> </th>
                        <th> <a href='{$returnLink}&sort=date'>Date</a> </th>
                        <th> <a href='{$returnLink}&sort=time'>Time</a> </th>
                        <th> <a href='{$returnLink}&sort=max'>Max participants</a> </th>
                        <th> active </th>
                        <th> Edit </th>
                    <tr/>
                ";

            // adds the events to the table
            foreach ($result as $entry) {
                $active = '';
                if($entry['active']){
                    $active = 'checked';
                }
                echo "
                    <tr class='{$entry['event_id']}'>
                        <td> {$entry['event_id']} </td>
                        <td> <div contenteditable class='event_name'>{$entry['event_name']}</div> </td>
                        <td> <div contenteditable class='event_description'>{$entry['event_description']}</div> </td>
                        <td> <div contenteditable class = 'location_street'>{$entry['location_street']}</div> </td>
                        <td> <div contenteditable class = 'location_postal_code'>{$entry['location_postal_code']}</div> </td>
                        <td> <div contenteditable class = 'location_city'>{$entry['location_city']}</div> </td>
                        <td> <input type='date' class='event_date' value='{$entry['event_date']}'></input></td>
                        <td> <input type='time' class='event_time' value='{$entry['event_time']}'></input></td>
                        <td> <div contenteditable class = 'event_max_participant'>{$entry['event_max_participant']}</div> </td>
                        <td> <input type='checkbox' class='active' $active autocomplete='off'></td>
                        <td> <a href='javascript:updateEvent({$entry['event_id']})'> save changes </a> </td>
                    </tr>
                ";
            }

            $stmt = $handler->prepare("SELECT count(event_id) FROM `Events`");
            $stmt-> execute();
            $result = $stmt->fetch();

            echo "
                </table>
                <div id='pageNav'>
                    <button ". ($page<=1?'disabled' : '') ." onclick=\"window.location.href='eventsTool.php?show={$show}&page=". ($page-1) ."';\"> back </button>
                    <button ". ($page < ceil($result['0']/$show)? '' : 'disabled') ." onclick=\"window.location.href='eventsTool.php?show={$show}&page=". ($page+1) ."';\"> next </button>
                </div>
                
                <h2>add event</h2>
                <form id='addEventForm' action='{$returnLink}' method='post'>
                <input type='hidden' name='event_id' value='null'/>
                <label>event name: </label><input type='text' name='event_name'/> <br/>
                <label>event description: </label><textarea name='event_description'></textarea> <br/>
                <label>street: </label><input type='text' name='location_street'/> <br/>
                <label>postal code: </label><input type='text' name='location_postal_code'/> <br/>
                <label>city: </label><input type='text' name='location_city'/> <br/>
                <label>date: </label><input type='date' name='event_date'/> <br/>
                <label>time: </label><input type='time' name='event_time'/> <br/>
                <label>max participants: </label><input type='number' name='event_max_participant'/> <br/>
                <label>active?: </label><input type='checkbox' name='active' checked='true'/> <br/>
                <input type='hidden' name='requestType' value='addEvent'/>
                <input type='submit' value = 'add'/>
                </form>
            ";

        }
        ?>
    </body>


</html>