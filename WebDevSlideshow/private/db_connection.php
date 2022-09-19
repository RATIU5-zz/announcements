<?php
    // Create database connection
    define("DB_SERVER", "localhost");
    define("DB_USER", "SlideshowMaster");
    define("DB_PASS", "pjGie7ox3CsRrJfM");
    define("DB_NAME", "slides");

    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    // Test for connection
    if(mysqli_connect_errno()){
        die("Database connection failed: " . mysqli_connect_error());
    }
?>