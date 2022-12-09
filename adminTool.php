<!DOCTYPE HTML>
<?php
//placeholders
$dbname = "webapplication";
$username = "root";
$password = "qwerty";
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

        // shows the users database
        switch ($_GET['view']) {

            case 'TypesOfStaff':

                // prepares the query and fetches the results
                $stmt = $handler->prepare("SELECT type_of_staff_id, type_of_staff_description FROM `TypesOfStaff` ORDER BY type_of_staff_id LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();

                echo "
                <h1>Account Types</h1>
                <table id='typeTable'>
                    <tr>
                        <th> Type </th>
                        <th> Description </th>
                    </tr>
                ";

                // adds the staff entries to the table
                foreach ($result as $entry) {
                    echo "
                        <tr class='{$entry['type_of_staff_id']}'>
                            <td> {$entry['type_of_staff_id']} </td>
                            <td> <div class='description'>{$entry['type_of_staff_description']}</div></td>
                            <!-- <td> <a href='javascript:updateStaffType({$entry['type_of_staff_id']})'> save changes </a> </td> --> <!-- editing this might be outside the scope -->
                        </tr>
                    ";
                }

                echo "</table>";

                break;

            case 'Roles':

                // prepares the query and fetches the results
                $stmt = $handler->prepare("SELECT role_id, role_name, role_description  FROM `Roles` ORDER BY role_id LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();

                echo "
                <h1>Roles</h1>
                <table>
                    <tr>
                        <th> Role </th>
                        <th> Description </th>
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
                    <form id='addRoleForm' action='adminTool.php?show={$show}&page={$page}&view=Roles' method='post'>
                    <input type='hidden' name='id' value='null'/>
                    <label>Name: <input type='text' name='role_name'/></label> <br/>
                    <label>Description: <textarea name='role_description'></textarea></label> <br/>
                    <input type='hidden' name='requestType' value='createRole'/>
                    <input type='submit' value='add'/>
                    </form>
                ";


                break;

            case 'Event':

                // prepares the query and fetches the results
                $stmt = $handler->prepare("SELECT event_id, event_name, event_description, location_street, location_postal_code, location_city, claimed  FROM `Event` LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();

                echo "
                    <h1>Events</h1>
                    <table>
                        <tr>
                            <th> Event Number </th>
                            <th> Name </th>
                            <th> Description </th>
                            <th> Street </th>
                            <th> Postal Code </th>
                            <th> City </th>
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
                    <form id='addEventForm' action='adminTool.php?show={$show}&page={$page}&view=Event' method='post'>
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

                // prepares the query and fetches the results
                $stmt = $handler->prepare("SELECT user_id, user_name, password_change_date, first_name, last_name, email_address, type_of_staff, user_role  FROM `User` LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();

                echo "
                    <h1>Users</h1>
                    <table>
                        <tr>
                            <th> ID </th>
                            <th> Username </th>
                            <th> Password Change Date </th>
                            <th> First Name </th>
                            <th> Last Name </th>
                            <th> Email Address </th>
                            <th> Account Type </th>
                            <th> User Role </th>
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
                                    <label><input type='radio' name='type' class='radio1' value='1'" . (($entry['type_of_staff'] == 1) ? "checked='true'" : '') . "/> manager</label>    <!-- PLACEHOLDER VALUES -->     <!-- these can possibly be dynamically allocated, however support for additional roles or account types is not in our scope -->
                                    <label><input type='radio' name='type' class='radio2' value='2'" . (($entry['type_of_staff'] == 2) ? "checked='true'" : '') . "/> freelancer</label>
                                    <label><input type='radio' name='type' class='radio3' value='3'" . (($entry['type_of_staff'] == 3) ? "checked='true'" : '') . "/> in-house</label>
                                </td>
                                <td>
                                    <label><input type='radio' name='role' class='radio4' value='1'" . (($entry['user_role'] == 1) ? "checked='true'" : '') . "/> photographer</label>    <!-- PLACEHOLDER VALUES -->
                                    <label><input type='radio' name='role' class='radio5' value='2'" . (($entry['user_role'] == 2) ? "checked='true'" : '') . "/> editor</label>
                                    <label><input type='radio' name='role' class='radio6' value='3'" . (($entry['user_role'] == 3) ? "checked='true'" : '') . "/> writer</label>
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
                    <form id='addUserForm' action='adminTool.php?show={$show}&page={$page}&view=User' method='post'>
                    <input type='hidden' name='id' value='null'/>
                    <label>username:<input type='text' name='user_name'></label><br/>
                    <label>first name:<input type='text' name='first_name'></label><br/>
                    <label>last name:<input type='text' name='last_name'></label><br/>
                    <label>email adress:</label><input type='text' name='email_address'></label><br/>
                    <label>user type: </label> <br/>
                    <label><input type='radio' name='type_of_staff' value=1 /> manager</label><br/>
                    <label><input type='radio' name='type_of_staff' value=2 /> freelance</label><br/>
                    <label><input type='radio' name='type_of_staff' value=3 /> in-house</label><br/>
                    <label>user role:</label> <br/>
                    <label><input type='radio' name='user_role' value=1 /> photographer</label><br/>
                    <label><input type='radio' name='user_role' value=2 /> editor</label><br/>
                    <label><input type='radio' name='user_role' value=3 /> writer</label><br/>
                    <input type='hidden' name='requestType' value='createUser'/>
                    <input type='submit' value='add'/>
                    </form>
                ";

                break;

            case 'EventDetails':
                $stmt = $handler->prepare("SELECT event_details_id, event_id, user_id  FROM `Event_Details` LIMIT :show OFFSET :offset");
                $stmt->bindParam('show', $show, PDO::PARAM_INT);
                $stmt->bindParam('offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll();

                echo "
                <h1>Event Details</h1>
                <table>
                    <tr>
                        <th> Event Details ID </th>
                        <th> Event </th>
                        <th> User </th>
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


