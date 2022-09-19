<?php 

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    $admin = find_admin_username();
    foreach($admin as $admin) {
        $admin = $admin['username'];
    }
    if(logged_in() && $_SESSION['loggedIn'] === $admin) {
        // continue ::: $admin is allowed to add new users
    } elseif(logged_in()) {
        // users can't add any new users
        setCrudMessage("You don't have the authority to add users");
        redirect_to('users.php');
    } else {
        confirm_logged_in();
    }

    // check for post request
    if (isset($_POST['addUser']) && !empty($_POST['addUser'])) {
        // uses data from the form to make the following database operations work
        $users = [];
        $users['username'] = $_POST['username'] ?? '';
        $users['password'] = $_POST['password'] ?? '';

        $user = array_map('mysql_prep', $users);

        // insert steps                
        $query = "INSERT INTO users 
        (username, password) values
        (\"". $user['username'] ."\", 
        \"". password_hash($user['password'], PASSWORD_BCRYPT) ."\")"; 

        // run the query
        mysqli_query($db, $query);
                
        // test for mysql error and output message to user page either way
        if (mysqli_error($db) == '') {
            // Success on data getting into database
            setCrudMessage("User added successfully.");
            redirect_to('users.php');
        } else {
            // Failure on data getting into database ::: database requires unique usernames
            setCrudMessage("The user could not be added. error: " . mysqli_error($db));
            redirect_to('users.php');
        }
    } else {
        // no request was sent
        setCrudMessage("Please use form to add user.");
        redirect_to('users.php');
    }
?>