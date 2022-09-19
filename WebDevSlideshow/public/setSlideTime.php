<?php 

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    confirm_logged_in();

    // check for post request
    if(isset($_POST['setTime']) && !empty($_POST['setTime'])) {

        $times = [];
        $times['timeID'] = $_POST['timeID'] ?? '';
        $times['duration'] = $_POST['duration'] ?? '10';

        // apply mysqli_real_escape_string to all entries of $_POST array
        $time = array_map('mysql_prep', $times);

        // insert steps            
        $query = "UPDATE time SET 
        duration = \"". $time['duration'] ."\"  
        where timeID = ". $time['timeID'];

        // run the query
        mysqli_query($db, $query);
            
        // test for mysql error and output message to slides page either way
        if (mysqli_error($db) == '') {
            // successfully set the duration of the slides
            setCrudMessage("Duration set successfully.");
            redirect_to("slides.php");
        } else {
            // Failure on data getting into database
            setCrudMessage("The duration could not be set. error: " . mysqli_error($db));
            redirect_to("slides.php");
        }
    } else {
        // didn't receive a post request
        setCrudMessage("Please use form to set duration.");
        redirect_to('slides.php');
    }
?>
