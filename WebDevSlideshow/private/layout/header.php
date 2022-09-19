<?php include_once("../private/db_connection.php"); ?>
<?php require_once("../private/session.php"); ?>
<?php include_once("../private/functions.php"); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Web Dev Slideshow</title>
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="../public/assets/css/styles.css" rel="stylesheet" type="text/css">
        <link href="../public/assets/css/w3.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <header class="bg-dark">
            <div class="container d-flex justify-content-center">
                <nav class="navbar navbar-dark navbar-expand-lg">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="../public/index.php#slideshow">Home</a>
                        </li>

                        <?php

                        if(logged_in()){ ?>
                        <li class="nav-item"><a class="nav-link" href="slides.php">Slides</a></li>
                        
                        <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                        
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>

                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </header>