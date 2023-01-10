
// all functions document selects use .replace("<br>", "") to fetch the value, due to a bug where <br> gets added to the form whenever the field becomes empty
function updateRole(id)
{
    selection = document.getElementsByClassName(id)[0];
    role_name = selection.getElementsByClassName('role_name')[0].innerHTML.replace("<br>", "");
    role_description = selection.getElementsByClassName('role_description')[0].innerHTML.replace("<br>", "");
    document.forms["addRoleForm"].elements["role_name"].value = role_name;
    document.forms["addRoleForm"].elements["role_description"].value = role_description;
    document.forms["addRoleForm"].elements["role_id"].value = id;
    document.forms["addRoleForm"].elements["requestType"].value = "editRole";
    document.forms["addRoleForm"].submit();

}

function updateEvent(id){
    selection = document.getElementsByClassName(id)[0];
    event_name = selection.getElementsByClassName('event_name')[0].innerHTML.replace("<br>", "");
    event_description = selection.getElementsByClassName('event_description')[0].innerHTML.replace("<br>", "");
    location_street = selection.getElementsByClassName('location_street')[0].innerHTML.replace("<br>", "");
    location_postal_code = selection.getElementsByClassName('location_postal_code')[0].innerHTML.replace("<br>", "");
    location_city = selection.getElementsByClassName('location_city')[0].innerHTML.replace("<br>", "");
    event_time = selection.getElementsByClassName('event_time')[0].value;
    event_date = selection.getElementsByClassName('event_date')[0].value;
    event_max_participant = selection.getElementsByClassName('event_max_participant')[0].innerHTML.replace("<br>", "");
    active = selection.getElementsByClassName('active')[0].checked;

    document.forms["addEventForm"].elements["event_id"].value = id;
    document.forms["addEventForm"].elements["event_name"].value = event_name;
    document.forms["addEventForm"].elements["event_description"].value = event_description;
    document.forms["addEventForm"].elements["location_street"].value = location_street;
    document.forms["addEventForm"].elements["location_postal_code"].value = location_postal_code;
    document.forms["addEventForm"].elements["location_city"].value = location_city;
    document.forms["addEventForm"].elements["event_time"].value = event_time;
    document.forms["addEventForm"].elements["event_date"].value = event_date;
    document.forms["addEventForm"].elements["event_max_participant"].value = event_max_participant;
    document.forms["addEventForm"].elements["active"].checked = Boolean(active);
    document.forms["addEventForm"].elements["requestType"].value = "editEvent";
    document.forms["addEventForm"].submit();


}

// sends a query with the edited information of a user
function updateUser(id)
{
    // assign values based on the text inside the cells of the table after a user has edited it
    selection = document.getElementsByClassName(id)[0];

    password_change_date = selection.getElementsByClassName('password_change_date')[0].innerHTML.replace("<br>", "");
    first_name = selection.getElementsByClassName('first_name')[0].innerHTML.replace("<br>", "");
    last_name = selection.getElementsByClassName('last_name')[0].innerHTML.replace("<br>", "");
    email_address = selection.getElementsByClassName('email_address')[0].innerHTML.replace("<br>", "");

    select = selection.getElementsByClassName('type')[0]
    type = select.options[select.selectedIndex].value;

    select = selection.getElementsByClassName('role')[0]
    role = select.options[select.selectedIndex].value;

    active = selection.getElementsByClassName('active')[0].checked;


    // adds the information to the form and submits it
    document.forms["addUserForm"].elements["first_name"].value = first_name;
    document.forms["addUserForm"].elements["last_name"].value = last_name;
    document.forms["addUserForm"].elements["email_address"].value = email_address;
    document.forms["addUserForm"].elements["type_of_staff"].value = type;
    document.forms["addUserForm"].elements["user_role"].value = role;
    document.forms["addUserForm"].elements["user_id"].value = id;
    document.forms["addUserForm"].elements["active"].checked = Boolean(active);
    document.forms["addUserForm"].elements["requestType"].value = "editUser";
    document.forms["addUserForm"].submit();
}
