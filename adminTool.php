<!DOCTYPE HTML>
<?php
//placeholders
$dbname = "webapplication";
include 'adminPass.php';
$userLoggedIn = true;
$permissions = true;

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

                echo "
                    </table>
                    
                    <h2>add role</h2>
                    <form id='addRoleForm' action='{$returnLink}' method='post'>
                    <input type='hidden' name='id' value='null'/>
                    <label>Name: <input type='text' name='role_name'/></label> <br/>
                    <label>Description: <textarea name='role_description'></textarea></label> <br/>
                    <input type='hidden' name='requestType' value='createRole'/>
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

                echo "
                    </table>
                    <h2>add event</h2>
                    <form id='addEventForm' action='{$returnLink}' method='post'>
                    <input type='hidden' name='event_id' value='null'/>
                    <label>event name: <input type='text' name='event_name'/></label> <br/>
                    <label>event description: <textarea name='event_description'></textarea></label> <br/>
                    <label>street: <input type='text' name='location_street'/></label> <br/>
                    <label>postal code: <input type='text' name='location_postal_code'/></label> <br/>
                    <label>city: <input type='text' name='location_city'/></label> <br/>
                    <input type='hidden' name='requestType' value='createEvent'/>
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

                // creates the "add new user" form (this also serves as the form for modifying users)
                echo "
                    </table>
                    
                    <h2>add user</h2>
                    <form id='addUserForm' action='{$returnLink}' method='post'>
                    <input type='hidden' name='id' value='null'/>
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
                    <input type='hidden' name='requestType' value='createUser'/>
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

?>


