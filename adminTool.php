<!DOCTYPE HTML>
<?php
//placeholders
$dbname = "webapplication";
include 'adminPass.php';
$userLoggedIn = true;
$permissions = true;
$defaultPass = "user123"; // default pass for all users (they will be asked to change it asap)
try{
    $handler = new PDO("mysql:host=mysql; dbname=$dbname; charset=utf8", $username, $password);
}
catch(Exception $ex){
    print $ex;
}
?>
<head>
    <script type="text/javascript" src="scripts/adminTool.js"></script>
</head>
<nav>
    <a href="adminTool.php?view=TypesOfStaff">Staff Types</a> <br/>
    <a href="adminTool.php?view=Roles">Roles</a> <br/>
    <a href="adminTool.php?view=Event">Events</a> <br/>
    <a href="adminTool.php?view=User">Users</a> <br/>
    <a href="adminTool.php?view=EventDetails">Event details</a> <br/>
</nav>

<?php
//Data manipulation functionality
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $errorMsg = [];
    $deleteFlag = 0; // admin can remove roles/ events/ users if they change all fields to empty
    switch ($_POST["requestType"]) {

        // Roles
        case 'editRole':
            $role_id = filter_input(INPUT_POST, 'role_id', FILTER_SANITIZE_NUMBER_INT);
            if(empty($role_id)){
                $errorMsg[] = "an id could not be fetched";
            }
        //for adding roles and editing roles
        case 'addRole':

            //role name gets set
            $role_name = filter_input(INPUT_POST, 'role_name');
            if($role_name === null || $role_name === false){
                $errorMsg[] = "something went wrong while fetching the role name";
            } elseif (empty($role_name) || $role_name == "<br>") {
                // if input is empty, the javascript code returns <br> for some reason
                $errorMsg[] = "a role name is required";
                $deleteFlag++;
            } else {
                if(preg_match("/[^a-zA-Z\s]/", $role_name)){
                    $errorMsg[] = "only letters and spaces are allowed";
                }
                if(strlen($role_name) > 25) {
                    $errorMsg[] = "only a max of 25 characters is allowed for the role name";
                }
                $andCond="";
                if(isset($role_id)){
                    $andCond="AND NOT role_id = $role_id";
                }
                $stmt = $handler->prepare("SELECT role_name FROM `Roles` WHERE role_name = :role_name $andCond");
                $stmt->bindParam('role_name', $role_name, PDO::PARAM_STR);
                $stmt->execute();
                if (count($stmt->fetchAll()) != 0){
                    $errorMsg[] = "that name has already been used";
                }
            }

            //role description gets set
            $role_description = filter_input(INPUT_POST, 'role_description', FILTER_SANITIZE_SPECIAL_CHARS);
            if ($role_description === null || $role_description === false) {
                $errorMsg[] = "something went wrong";
            } else {
                // when leaving the text box blank and using javascript:updateRole(id), for some reason it returns <br> to role_description
                if (empty($role_description) || $role_description == "&#60;br&#62;"){
                    $errorMsg[] = "a role description is required";
                    $deleteFlag++;
                }
            }

            //if nothing is filled in while in edit mode, will delete the entry
            if($deleteFlag == 2 && isset($role_id)){

                //edge case protection for when attempting to remove role already assigned to user
                $stmt = $handler->prepare("SELECT user_id FROM `User` WHERE user_role = :role_id");
                $stmt->bindParam('role_id', $role_id, PDO::PARAM_INT);
                $stmt->execute();

                if (count($stmt->fetchAll()) != 0){
                    echo "you cannot delete this role since an user has it assigned to them";
                } else {
                    $stmt = $handler->prepare("DELETE FROM `Roles` WHERE role_id = :role_id");
                    $stmt->bindParam('role_id', $role_id, PDO::PARAM_INT);
                    $stmt->execute();
                    echo "entry has been successfully deleted <br/>";
                }
                break;
            }

            //counting errors
            echo "<div class=errormessage>";
            if(!count($errorMsg) == 0){
                foreach($errorMsg as $msg){
                    echo "$msg <br/>";
                }
                break;
            }
            echo "</div>";

            //pushing changes
            if (isset($role_id)){
                $stmt = $handler->prepare("UPDATE `Roles` SET role_name = :role_name, role_description = :role_description WHERE role_id = :role_id");
                $stmt->bindParam("role_id", $role_id, PDO::PARAM_INT);
            } else {
                $stmt = $handler->prepare("INSERT INTO `Roles` (role_name, role_description) VALUES (:role_name, :role_description)");

            }
            $stmt->bindParam("role_name", $role_name, PDO::PARAM_STR);
            $stmt->bindParam("role_description", $role_description, PDO::PARAM_STR);
            $stmt->execute();

            break;

        // Events
        case 'editEvent':

            $event_id = filter_input(INPUT_POST, 'event_id', FILTER_SANITIZE_NUMBER_INT);
            if(empty($event_id)){
                $errorMsg[] = "an id could not be fetched";
            }

        case 'addEvent':

            //event name gets set
            $event_name = filter_input(INPUT_POST, 'event_name');
            if ($event_name === null || $event_name === false) {
                $errorMsg[] = "something went wrong while fetching the event name";
            } elseif (empty($event_name) || $event_name == "<br>") {
                $errorMsg[] = "an event name is required";
                $deleteFlag++;
            } else {
                if(preg_match("/[^a-zA-Z\s]/", $event_name)){
                    $errorMsg[] = "only letters and spaces are allowed for the event name";
                }
                if (strlen($event_name) > 70) {
                    $errorMsg[] = "only a max of 70 characters is allowed for the event name";
                }
                $andCond = "";
                if (isset($event_id)) {
                    $andCond = "AND NOT event_id = $event_id";
                }
                $stmt = $handler->prepare("SELECT event_name FROM `Event` WHERE event_name = :event_name $andCond");
                $stmt->bindParam('event_name', $event_name, PDO::PARAM_STR);
                $stmt->execute();
                if (count($stmt->fetchAll()) != 0) {
                    $errorMsg[] = "that name has already been used";
                }
            }



            // event description gets set
            $event_description = filter_input(INPUT_POST, 'event_description', FILTER_SANITIZE_SPECIAL_CHARS);
            if ($event_description === null || $event_description === false) {
                $errorMsg[] = "something went wrong while fetching the event description";
            } elseif (empty($event_description) || $event_description == "&#60;br&#62;") {
                $errorMsg[] = "a role description is required";
                $deleteFlag++;
            }

            // street name gets set
            $location_street = filter_input(INPUT_POST, 'location_street');
            if($location_street === null || $location_street === false){
                $errorMsg[] = "something went wrong while fetching the street location";
            } else if(empty($location_street) || $location_street == "<br>"){
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
            } elseif(empty($location_postal_code) || $location_postal_code == '<br>') {
                $location_postal_code = null;
                $deleteFlag++;
            } else {
                if (!preg_match('/^\d{4}\w{2}$/', $location_postal_code)) {
                    $errorMsg[] = "a valid (dutch) postal code is required with the format NNNNAA";
                }
            }

            // city name gets set
            $location_city = filter_input(INPUT_POST, 'location_city');
            echo $location_city;
            if ($location_city === null || $location_city === false) {
                $errorMsg[] = "something went wrong while fetching the city location";
            } elseif(empty($location_city)||$location_city == "<br>") {
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

            //if nothing is filled in while in edit mode, will delete the entry
            if($deleteFlag == 5 && isset($event_id)){
                $stmt = $handler->prepare("DELETE FROM `Event` WHERE event_id = :event_id");
                $stmt->bindParam('event_id', $event_id, PDO::PARAM_INT);
                $stmt->execute();
                echo "entry has been successfully deleted <br/>";
                break;
            }

            // counting errors
            echo "<div class=errormessage>";
            if(!count($errorMsg) == 0){
                foreach($errorMsg as $msg){
                    echo "$msg <br/>";
                }
                break;
            }
            echo "</div>";

            // pushing changes
            if(isset($event_id)){
                $stmt = $handler->prepare('UPDATE `Event` SET 
                event_name = :event_name, 
                event_description = :event_description, 
                location_street = :location_street, 
                location_postal_code = :location_postal_code, 
                location_city = :location_city 
                WHERE event_id = :event_id');
                $stmt->bindParam('event_id', $event_id, PDO::PARAM_INT);
            } else {
                $stmt = $handler->prepare('INSERT INTO `Event`
                (event_name, event_description, location_street, location_postal_code, location_city, claimed) VALUES
                (:event_name, :event_description, :location_street, :location_postal_code, :location_city, 0)
                ');
            }
            $stmt->bindParam('event_name', $event_name, PDO::PARAM_STR);
            $stmt->bindParam('event_description', $event_description, PDO::PARAM_STR);
            $stmt->bindParam('location_street', $location_street, ($location_postal_code ? PDO::PARAM_STR : PDO::PARAM_NULL));
            $stmt->bindParam('location_postal_code', $location_postal_code, ($location_postal_code? PDO::PARAM_STR : PDO::PARAM_NULL));
            $stmt->bindParam('location_city', $location_city, ($location_postal_code? PDO::PARAM_STR : PDO::PARAM_NULL));
            $stmt->execute();
            break;

        // Users
        case 'editUser':
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);
            if(empty($user_id)){
                $errorMsg[] = "an id could not be fetched";
            }

        case 'addUser':

            // username gets set
            $user_name = filter_input(INPUT_POST, 'user_name');
            if($user_name === null || $user_name === false){
                $errorMsg[] = "something went wrong while fetching the user name";
            }elseif(empty($user_name) || $user_name == "<br>") {
                $errorMsg[] = "a user name is required";
                $deleteFlag++;
            } elseif (preg_match("/[^a-zA-Z1-9\s]/", $user_name)) {
                $errorMsg[] = "only letters, numbers and spaces are allowed for the username";
            } else {
                if(strlen($user_name) > 25) {
                    $errorMsg[] = "only a max of 25 characters is allowed for the username";
                }
                $andCond="";
                if(isset($user_id)){
                    $andCond="AND NOT user_id = $user_id";
                }
                $stmt = $handler->prepare("SELECT user_name FROM `User` WHERE user_name = :user_name $andCond");
                $stmt->bindParam('user_name', $user_name, PDO::PARAM_STR);
                $stmt->execute();
                if (count($stmt->fetchAll()) != 0){
                    $errorMsg[] = "that name has already been used";
                }
            }

            // first name gets set
            $first_name = filter_input(INPUT_POST, 'first_name');
            if($first_name === null || $first_name === false) {
                $errorMsg[] = "something went wrong while fetching the first name";
            } elseif (empty($first_name) || $first_name == '<br>') {
                $errorMsg[] = "first name must be filled in";
                $deleteFlag++;
            } else {
                if(preg_match("/[^a-zA-Z\s]/", $first_name)){
                    $errorMsg[] = "only (standard) letters and spaces are allowed in the first name";
                }
                if(strlen($first_name) > 25){
                    $errorMsg[] = "first name may only be a max of 25 characters";
                }
            }

            // last name gets set
            $last_name = filter_input(INPUT_POST, 'last_name');
            if($last_name === null || $last_name === false) {
                $errorMsg[] = "something went wrong while fetching the last name";
            } elseif (empty($last_name) || $last_name == '<br>') {
                $errorMsg[] = "last name must be filled in";
                $deleteFlag++;
            } else {
                if(preg_match("/[^a-zA-Z\s]/", $last_name)){
                    $errorMsg[] = "only (standard) letters and spaces are allowed in the last name";
                }
                if(strlen($last_name) > 25){
                    $errorMsg[] = "last name may only be a max of 25 characters";
                }
            }

            // email gets set
            $email_address = filter_input(INPUT_POST, 'email_address');
            if($email_address === null || $email_address === false) {
                $errorMsg[] = "something went wrong while fetching the email address";
            } else if (empty($email_address) || $email_address == '<br>') {
                $errorMsg[] = "email address must be filled in";
                $deleteFlag++;
            } else if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)){
                $errorMsg[] = "the email address you have provided is incorrect";
            } elseif (strlen($email_address) > 50) {
                $errorMsg[] = "email may not be more than 50 characters";
            }
                $andCond = "";
                if (isset($user_id)) {
                    $andCond = "AND NOT user_id = $user_id";
                }
                $stmt = $handler->prepare("SELECT email_address FROM `User` WHERE email_address = :email_address $andCond");
                $stmt->bindParam('email_address', $email_address, PDO::PARAM_STR);
                $stmt->execute();
                if (count($stmt->fetchAll()) != 0) {
                    $errorMsg[] = "that email has already been used";
                }

            // type gets set
            $type_of_staff = filter_input(INPUT_POST, 'type_of_staff');
            if($type_of_staff === null || $type_of_staff === false){
                $errorMsg[] = "something went wrong while fetching staff type";
            } elseif (!filter_var($type_of_staff, FILTER_VALIDATE_INT)) {
                $errorMsg[] = "type of staff does not seem to be a number";
            } else {
                $stmt = $handler->prepare("SELECT type_of_staff_id FROM `TypesOfStaff` WHERE type_of_staff_id = :type_of_staff");
                $stmt->bindParam("type_of_staff", $type_of_staff, PDO::PARAM_INT);
                $stmt->execute();
                if(count($stmt->fetchAll()) != 1){
                    $errorMsg[] = "the type of staff is out of bounds";
                }
            }

            // role gets set
            $user_role = filter_input(INPUT_POST, 'user_role');
            if($user_role === null || $user_role === false){
                $errorMsg[] = "something went wrong while fetching the user role";
            } elseif (!filter_var($user_role, FILTER_VALIDATE_INT)) {
                $errorMsg[] = "user role does not seem to be a number";
            } else {
                $stmt = $handler->prepare("SELECT role_id FROM `Roles` WHERE role_id = :user_role");
                $stmt->bindParam("user_role", $user_role, PDO::PARAM_INT);
                $stmt->execute();
                if(count($stmt->fetchAll()) != 1){
                    $errorMsg[] = "the user role is out of bounds";
                }
            }

            //if nothing is filled in while in edit mode, will delete the entry
            if($deleteFlag == 4 && isset($user_id)){
                $stmt = $handler->prepare("DELETE FROM `User` WHERE user_id = :user_id");
                $stmt->bindParam('user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                echo "entry has been successfully deleted <br/>";
                break;
            }

            // counting errors
            echo "<div class=errormessage>";
            if(!count($errorMsg) == 0){
                foreach($errorMsg as $msg){
                    echo "$msg <br/>";
                }
                break;
            }

            if(isset($user_id)){
                $stmt = $handler->prepare('UPDATE `User` SET
                user_name = :user_name,
                first_name = :first_name,
                last_name = :last_name,
                email_address = :email_address,
                type_of_staff = :type_of_staff,
                user_role = :user_role
                WHERE user_id = :user_id');
                $stmt->bindParam('user_id', $user_id);
            } else {
                $stmt = $handler->prepare('INSERT INTO `User`
                (user_name, user_password, password_change_date, first_name, last_name, email_address, type_of_staff, user_role) VALUES
                (:user_name, :user_password, (NOW()), :first_name, :last_name, :email_address, :type_of_staff, :user_role)
                ');
                $hashedPass = password_hash($password, PASSWORD_BCRYPT);
                $stmt->bindParam('user_password', $hashedPass, PDO::PARAM_STR);
            }

            $stmt->bindParam('user_name', $user_name, PDO::PARAM_STR);
            $stmt->bindParam('first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam('last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam('email_address', $email_address, PDO::PARAM_STR);
            $stmt->bindParam('type_of_staff', $type_of_staff, PDO::PARAM_INT);
            $stmt->bindParam('user_role', $user_role, PDO::PARAM_INT);
            $stmt->execute();

            break;


        default:
            echo("something went wrong while fetching requestType");
            break;
    }
}


//show functionality
if($userLoggedIn && $permissions) { // get amount of entries to show

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


    if(isset($_GET['view'])){
        $view = $_GET['view'];
        $returnLink = "adminTool.php?view={$view}&show={$show}&page={$page}";


        // shows the users database
        switch ($view) {

            case 'TypesOfStaff':

                $sort = 'type_of_staff_id';
                if (isset($_GET["sort"])){
                    if ($_GET["sort"] == 'description') {
                        $sort = 'type_of_staff_description';
                    }
                }

                // prepares the query and fetches the results
                $stmt = $handler->prepare("SELECT type_of_staff_id, type_of_staff_description FROM `TypesOfStaff` ORDER BY $sort LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();


                echo "
                <h1>Account Types</h1>
                <table id='typeTable'>
                    <tr>
                        <th> <a href='{$returnLink}&sort=id'>Type</a> </th>
                        <th> <a href='{$returnLink}&sort=description'>Description</a> </th>
                    </tr>
                ";

                // adds the staff entries to the table
                foreach ($result as $entry) {
                    echo "
                        <tr class='{$entry['type_of_staff_id']}'>
                            <td> {$entry['type_of_staff_id']} </td>
                            <td> <div class='description'>{$entry['type_of_staff_description']}</div></td>
                            <!-- <td> <a href='javascript:updateStaffType({$entry['type_of_staff_id']})'> save changes </a> </td> --> <!-- allowing edits for this might be outside the scope -->
                        </tr>
                    ";
                }

                echo "</table>";

                break;

            case 'Roles':

                $sort = 'role_id';
                if (isset($_GET["sort"])){
                    switch ($_GET["sort"]) {
                        case 'name':
                            $sort = 'role_name';
                            break;
                        case 'description':
                            $sort = 'role_description';
                            break;
                    }
                }

                // prepares the query and fetches the results
                $stmt = $handler->prepare("SELECT role_id, role_name, role_description  FROM `Roles` ORDER BY $sort LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();

                echo "
                <h1>Roles</h1>
                <table>
                    <tr>
                        <th> <a href='{$returnLink}&sort=id'>Role</a> </th>
                        <th> <a href='{$returnLink}&sort=name'>Name</a> </th>
                        <th> <a href='{$returnLink}&sort=description'>Description</a> </th>
                        <th> Edit </th>
                    </tr>
                ";

                foreach ($result as $entry) {
                    echo "
                        <tr class='{$entry['role_id']}'>
                            <td> {$entry['role_id']} </td>
                            <td> <div contenteditable class='role_name'>{$entry['role_name']}</div></td>
                            <td> <div contenteditable class='role_description'>{$entry['role_description']}</div></td>
                            <td> <a href='javascript:updateRole({$entry['role_id']})'> save changes </a> </td>
                        </tr>
                    ";
                }

                $stmt = $handler->prepare("SELECT count(role_id) FROM `Roles`");
                $stmt-> execute();
                $result = $stmt->fetch();

                echo "
                    </table>
                    <button ". ($page<=1?'disabled' : '') ." onclick=\"window.location.href='adminTool.php?view={$view}&show={$show}&page=". $page-1 ."';\"> back </button>
                    <button ". ($page < ceil($result['0']/$show)? '' : 'disabled') ." onclick=\"window.location.href='adminTool.php?view={$view}&show={$show}&page=". $page+1 ."';\"> next </button>
                    
                    <h2>add role</h2>
                    <form id='addRoleForm' action='{$returnLink}' method='post'>
                    <input type='hidden' name='role_id' value='null'/>
                    <label>Name: <input type='text' name='role_name'/></label> <br/>
                    <label>Description: <textarea name='role_description'></textarea></label> <br/>
                    <input type='hidden' name='requestType' value='addRole'/>
                    <input type='submit' value='add'/>
                    </form>
                ";


                break;

            case 'Event':

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
                    }
                }

                // prepares the query and fetches the results
                $stmt = $handler->prepare("SELECT event_id, event_name, event_description, location_street, location_postal_code, location_city, claimed  FROM `Event` ORDER BY $sort LIMIT :show OFFSET :offset");
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
                            <th> claimed? </th>
                            <th> Edit </th>
                        <tr/>
                    ";

                // adds the events to the table
                foreach ($result as $entry) {
                    echo "
                        <tr class='{$entry['event_id']}'>
                            <td> {$entry['event_id']} </td>
                            <td> <div contenteditable class='event_name'>{$entry['event_name']}</div> </td>
                            <td> <div contenteditable class='event_description'>{$entry['event_description']}</div> </td>
                            <td> <div contenteditable class = 'location_street'>{$entry['location_street']}</div> </td>
                            <td> <div contenteditable class = 'location_postal_code'>{$entry['location_postal_code']}</div> </td>
                            <td> <div contenteditable class = 'location_city'>{$entry['location_city']}</div> </td>
                            <td> <div class = 'claimed'>". ($entry['claimed']?'yes':'no') ."</div> </td>
                            <td> <a href='javascript:updateEvent({$entry['event_id']})'> save changes </a> </td>
                        </tr>
                    ";
                }

                $stmt = $handler->prepare("SELECT count(event_id) FROM `Event`");
                $stmt-> execute();
                $result = $stmt->fetch();

                echo "
                    </table>
                    <button ". ($page<=1?'disabled' : '') ." onclick=\"window.location.href='adminTool.php?view={$view}&show={$show}&page=". $page-1 ."';\"> back </button>
                    <button ". ($page < ceil($result['0']/$show)? '' : 'disabled') ." onclick=\"window.location.href='adminTool.php?view={$view}&show={$show}&page=". $page+1 ."';\"> next </button>
                    
                    <h2>add event</h2>
                    <form id='addEventForm' action='{$returnLink}' method='post'>
                    <input type='hidden' name='event_id' value='null'/>
                    <label>event name: <input type='text' name='event_name'/></label> <br/>
                    <label>event description: <textarea name='event_description'></textarea></label> <br/>
                    <label>street: <input type='text' name='location_street'/></label> <br/>
                    <label>postal code: <input type='text' name='location_postal_code'/></label> <br/>
                    <label>city: <input type='text' name='location_city'/></label> <br/>
                    <input type='hidden' name='requestType' value='addEvent'/>
                    <input type='submit' value = 'add'/>
                    </form>
                ";


                break;

            case 'User':

                $sort = 'user_id';
                if (isset($_GET["sort"])){
                    switch ($_GET["sort"]) {
                        case 'name':
                            $sort = 'user_name';
                            break;
                        case 'date':
                            $sort = 'password_change_date';
                            break;
                        case 'first':
                            $sort = 'first_name';
                            break;
                        case 'last':
                            $sort = 'last_name';
                            break;
                        case 'email':
                            $sort = 'email_address';
                            break;
                        case 'type':
                            $sort = 'type_of_staff';
                            break;
                        case 'role':
                            $sort = 'user_role';
                            break;
                    }
                }

                // prepares the query and fetches the results
                $stmt = $handler->prepare("SELECT user_id, user_name, password_change_date, first_name, last_name, email_address, type_of_staff, user_role  FROM `User` ORDER BY $sort LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();

                $stmt = $handler->prepare("SELECT role_id, role_name FROM `Roles` ORDER BY role_id ASC");
                $stmt->execute();
                $roles = $stmt->fetchAll();

                echo "
                    <h1>Users</h1>
                    <table>
                        <tr>
                            <th> <a href='{$returnLink}&sort=id'>ID</a> </th>
                            <th> <a href='{$returnLink}&sort=name'>Username</a> </th>
                            <th> <a href='{$returnLink}&sort=date'>Password Change Date</a> </th>
                            <th> <a href='{$returnLink}&sort=first'>First Name</a> </th>
                            <th> <a href='{$returnLink}&sort=last'>Last Name</a> </th>
                            <th> <a href='{$returnLink}&sort=email'>Email Address</a> </th>
                            <th> <a href='{$returnLink}&sort=type'>Account Type</a> </th>
                            <th> <a href='{$returnLink}&sort=role'>User Role</a> </th>
                            <th> Edit </th>
                        <tr/>
                        ";

                // adds the users to the table
                foreach ($result as $entry) {
                    echo "
                        <tr class='{$entry['user_id']}'>
                            <form> <!-- form added to prevent radio buttons from influencing other users -->
                                <td> {$entry['user_id']} </td>
                                <td> <div contenteditable class='user_name'>{$entry['user_name']}</div> </td>
                                <td> <div class='password_change_date'>{$entry['password_change_date']}</div> </td>
                                <td> <div contenteditable class = 'first_name'>{$entry['first_name']}</div> </td>
                                <td> <div contenteditable class = 'last_name'>{$entry['last_name']}</div> </td>
                                <td> <div contenteditable class = 'email_address'>{$entry['email_address']}</div> </td>
                                <td>
                                    <select class='type' autocomplete='off'>
                                        <option value='1' " . (($entry['type_of_staff'] == 1) ? "selected" : '') . ">manager</option>
                                        <option value='2' " . (($entry['type_of_staff'] == 2) ? "selected" : '') . ">in-house</option>
                                        <option value='3' " . (($entry['type_of_staff'] == 3) ? "selected" : '') . ">freelancer</option>
                                    </select>
                                </td>
                                <td>
                                    <select class='role' autocomplete='off'>
                                        ";

                    // dynamically adds roles
                    foreach($roles as $role){
                        echo "<option value='{$role['role_id']}'" . (($entry['user_role'] == $role['role_id']) ? "selected" : '') . ">{$role['role_name']}</option>";
                    }
                    echo "
                                    </select>
                                </td>
                                <td> <a href='javascript:updateUser({$entry['user_id']})'> save changes </a> </td>
                            </form>
                        </tr>
                    ";
                }

                $stmt = $handler->prepare("SELECT count(user_id) FROM `User`");
                $stmt-> execute();
                $result = $stmt->fetch();

                // creates the "add new user" form (this also serves as the form for modifying users)
                echo "
                    </table>
                    <button ". ($page<=1?'disabled' : '') ." onclick=\"window.location.href='adminTool.php?view={$view}&show={$show}&page=". $page-1 ."';\"> back </button>
                    <button ". ($page < ceil($result['0']/$show)? '' : 'disabled') ." onclick=\"window.location.href='adminTool.php?view={$view}&show={$show}&page=". $page+1 ."';\"> next </button>
                    
                    <h2>add user</h2>
                    <form id='addUserForm' action='{$returnLink}' method='post'>
                    <input type='hidden' name='user_id' value='null'/>
                    <label>username:<input type='text' name='user_name'></label><br/>
                    <label>first name:<input type='text' name='first_name'></label><br/>
                    <label>last name:<input type='text' name='last_name'></label><br/>
                    <label>email adress:</label><input type='text' name='email_address'></label><br/>
                    <label>user type: </label> <br/>
                    <select name='type_of_staff' autocomplete='off'>
                        <option value='1'>manager</option>
                        <option value='2'>in-house</option>
                        <option value='3' selected>freelancer</option>
                    </select> <br/>
                    <label>user role:</label> <br/>
                    <select name='user_role' autocomplete='off'>
                    ";

                // dynamically adds roles
                foreach($roles as $role){
                    echo "<option value='{$role['role_id']}'>{$role['role_name']}</option>";
                }
                echo "
                    </select> <br/>
                    <input type='hidden' name='requestType' value='addUser'/>
                    <input type='submit' value='add'/>
                    </form>
                ";

                break;

            case 'EventDetails':

                $sort = 'event_details_id';
                if (isset($_GET["sort"])){
                    switch ($_GET["sort"]) {
                        case 'event':
                            $sort = 'event_id';
                            break;
                        case 'user':
                            $sort = 'user_id';
                            break;
                    }
                }

                $stmt = $handler->prepare("SELECT event_details_id, event_id, user_id  FROM `Event_Details` ORDER BY $sort LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();

                echo "
                <h1>Event Details</h1>
                <table>
                    <tr>
                        <th> <a href='{$returnLink}&sort=id'>Event Details ID</a> </th>
                        <th> <a href='{$returnLink}&sort=event'>Event</a> </th>
                        <th> <a href='{$returnLink}&sort=user'>User</a> </th>
                    </tr>
                ";

                foreach ($result as $entry) {
                    echo "
                        <tr class='{$entry['event_details_id']}'>
                            <td> {$entry['event_details_id']} </td>
                            <td> <div contenteditable class='event'>{$entry['event_id']}</div></td>
                            <td> <div contenteditable class='user'>{$entry['user_id']}</div></td>
                        </tr>
                    ";
                }

                echo "</table>";

                break;
        }
    }
}
$handler = null;

?>


