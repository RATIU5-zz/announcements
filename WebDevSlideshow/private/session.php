<?php

    session_start();

    // get needed message from the session
    function getMessage($type) {

        // check if a message of this type is saved in the session
        if(isset($_SESSION[$type])) {
            // save message into variable
            $message = htmlentities($_SESSION[$type]);

            // delete message from session so it can't be used more than once
            unset($_SESSION[$type]);
            return $message;
        }
        // return false if there is no message
        return false;
    }

    // get the message type and display its contents
    function message($type) {

        // don't remove this
        $message = getMessage($type);

        if($message) {
            $output = "<div class=\"message\">";
            $output .= $message;
            $output .= "</div>";
        } else {
            $output = "";
        }
        return $output;
    }

    function setLoginMessage($message) {
        // set login errors for display
        $_SESSION['loginMessage'] = $message;
    }

    function setCrudMessage($message) {
        // set crud error/success messages for display
        $_SESSION['crudMessage'] = $message;
    }
?>
