<?php

// === Helpful Imported Functions === \\

function u($string="") {
  return urlencode($string);
}

function h($string="") {
  return htmlspecialchars($string);
}

function redirect_to($location) {
  header("Location: " . $location);
  exit;
}

function is_post_request() {
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

// check for query error
function confirm_query($result_set) {
    if(!$result_set){
        die("Database query failed: " . mysqli_connect_error());
    }
}

// sanitize form input for mysql queries
function mysql_prep($string) {
    global $db;

    $escaped_string = mysqli_real_escape_string($db, $string);
    return $escaped_string;
}

function shift_slide_positions($start_pos, $end_pos, $current_id=0) {
    global $db;

    if($start_pos == $end_pos) { return; }

    $sql = "UPDATE slides ";
    if($start_pos == 0) {
      // new item, +1 to items greater than $end_pos
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . mysql_prep($end_pos) . "' ";
    } elseif($end_pos == 0) {
      // delete item, -1 from items greater than $start_pos
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . mysql_prep($start_pos) . "' ";
    } elseif($start_pos < $end_pos) {
      // move later, -1 from items between (including $end_pos)
      $sql .= "SET position = position - 1 ";
      $sql .= "WHERE position > '" . mysql_prep($start_pos) . "' ";
      $sql .= "AND position <= '" . mysql_prep($end_pos) . "' ";
    } elseif($start_pos > $end_pos) {
      // move earlier, +1 to items between (including $end_pos)
      $sql .= "SET position = position + 1 ";
      $sql .= "WHERE position >= '" . mysql_prep($end_pos) . "' ";
      $sql .= "AND position < '" . mysql_prep($start_pos) . "' ";
    }
    // exclude the current_id in the SQL WHERE clause
    $sql .= "AND slideID != '" . mysql_prep($current_id) . "' ";

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
        return true;
    } else {
      // UPDATE failed
      echo mysqli_error($db);
      exit;
    }
}

// === Find queries === \\

// get all slides from the database
function find_all_slides() {
    global $db;

    // Build and perform database query
    $query = "SELECT * FROM slides ORDER BY position ASC";
    $results = mysqli_query($db, $query);
    // Test if there was a query error
    confirm_query($results);
    return $results;
}

// get slide by id
function find_slide_by_id($id) {
    global $db;

    // Build and perform database query
    $query = "SELECT * FROM slides WHERE slideID ='" . $id . "'";
    $result = mysqli_query($db, $query);
    // Test if there was a query error
    confirm_query($result);
    return $result;
}

function find_all_users() {
    global $db;

    // Build and perform database query
    $query = "SELECT * FROM users";
    $results = mysqli_query($db, $query);
    // Test if there was a query error
    confirm_query($results);
    return $results;
}

function find_user_by_id($id) {
    global $db;

    // Build and perform database query
    $query = "SELECT * FROM users WHERE userID ='" . $id . "'";
    $results = mysqli_query($db, $query);
    // Test if there was a query error
    confirm_query($results);
    return $results;
}

// get all information about a user not just username
function find_user_by_username2($username) {
    global $db;

    // Build and perform database query
    $query = "SELECT * FROM users WHERE username = '{$username}'";
    $results = mysqli_query($db, $query);
    // Test if there was a query error
    confirm_query($results);
    return $results;
}

// get the current user from the database using their username
function find_user_by_username($username) {
    global $db;

    /* Prepared statement, stage 1: prepare */
    if (!($stmt = mysqli_prepare($db, "Select * FROM users WHERE username = ? LIMIT 1"))) {
        echo "Prepare failed: (" . mysqli_errno($db) . ") " . mysqli_error($db);
    }

    /* Prepared statement, stage 2: bind parameters */
    $stmt->bind_param( 's', $username );

    /* Prepared statement, stage 3: execute statement*/
    if(!$result = $stmt->execute()){
        return null;
    } else {
        //get result from previously executed statement - old $result variable no longer needed
        $result = mysqli_fetch_assoc($stmt->get_result());

        return $result;
    }
}

function find_admin_username() {
    global $db;

    // Build and perform database query
    $query = "SELECT * FROM users WHERE userID ='" . 1 . "'";
    $results = mysqli_query($db, $query);
    // Test if there was a query error
    confirm_query($results);
    return $results;
}

function find_time() {
    global $db;

    // Build and perform database query
    $query = "SELECT * FROM time WHERE timeID ='" . 1 . "'";
    $results = mysqli_query($db, $query);
    // Test if there was a query error
    confirm_query($results);
    return $results;
}

// === Check user == \\

// check if user still logged in
function logged_in() {
    return isset($_SESSION['loggedIn']);
}

// check if user is logged in and make an error message if not
function confirm_logged_in() {
    if(!logged_in()) {
        setLoginMessage("Please log in");
        redirect_to('login.php');
    }
}

// check form password against database hashed password
function password_check($password, $existing_hash) {
    // existing hash contains format and salt at start
    $hash = crypt($password, $existing_hash);
    if ($hash === $existing_hash) {
        return true;
    } else {
        return false;
    }
}

// try to log in user using posted form data
function attempt_login($username, $password) {
    $user = find_user_by_username($username);

    if($user) {
        // found user, now check password
        if(password_check($password, $user['password'])) {
            // Remove password field from array
            unset($user['password']);
            // password matches, now return the user
            return $user;
        } else {
            // password does not match
            return false;
        }
    } else {
        // user not found
        return false;
    }
}

?>