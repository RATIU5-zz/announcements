<?php

    require_once("../private/db_connection.php");
    require_once("../private/session.php");
    require_once("../private/functions.php");
    $admin = find_admin_username();
    foreach($admin as $admin) {
        $adminUsername = $admin['username'];
        $adminID = $admin['userID'];
    }
    if(logged_in() && $_SESSION['loggedIn'] === $adminUsername) {
        // $admin can see all users
        $users = find_all_users();
    } elseif(logged_in()) {
        // user can only see themself
        $users = find_user_by_username2($_SESSION['loggedIn']);
    } else {
        confirm_logged_in();
    }

    include("../private/layout/header.php");

?>

<body class="bg-light">

    <section class="section">
        <h1 class="section-header uncenter">Users</h1>
    </section>

    <div class="users-box min-vh-100">
        <?php echo message("crudMessage"); ?>
        <section class="section">
            <table class="users-table">
                <tr>
                    <th>User</th>
                </tr>

                <?php foreach($users as $user) { ?>
                
                <tr>
                    <td><?php echo $user['username']; ?></td>
                    <td class="table-button-box">
                        <form class="table-button" action="#editUserModal" method="post">
                            <!-- pass userID to prepopulate modal with current data -->
                            <input type="hidden" name="userID" value="<?php echo $user['userID']; ?>">
                            <input class="btn btn-dark" type="submit" name="edit" value="Edit">
                        </form>
                    </td>

                    <td class="table-button-box table-button-box-right">
                        <form class="table-button" action="#deleteUserModal" method="post">
                            <!-- pass userID to delete database record -->
                            <input type="hidden" name="userID" value="<?php echo $user['userID']; ?>">
                            <?php if($user['userID'] !== $adminID) { ?>
                                <input class="btn btn-dark" type="submit" name="delete" value="Delete">
                            <?php } ?>
                        </form>
                    </td>
                </tr>

                <?php } ?>

            </table>

            <div style="margin: 20px 0px;">
                <a class="btn btn-dark" href="#addUserModal" role="button">Add A New User</a>
            </div>

            <div id="addUserModal" class="modalDialog userModalDialog">
                <div><a href="#close" title="Close" class="close">X</a>
                    <!-- Content for modal -->
                    <div class="modal-header p-0 border-0">
                        <h2>Add User</h2>
                    </div>
                    <div class="login User mt-0">
                        <?php echo message("crudMessage"); ?>
                        <form action="addUser.php"  method="POST" enctype="multipart/form-data">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password">
                            <input class="btn btn-primary" role="button" type="submit" name="addUser" value="Add User">
                        </form>
                    </div><!-- end login User -->
                </div><!-- end close -->
            </div><!-- end openModal -->

            <div id="editUserModal" class="modalDialog userModalDialog">
                <?php
                if(isset($_POST['userID']) && !empty($_POST['userID'])) { 
                    
                        $user = find_user_by_id($_POST['userID']);
                    ?>
                    <div><a href="#close" title="Close" class="close">X</a>
                        <!-- Content for modal -->
                        <div class="modal-header p-0 border-0">
                            <h2>Edit Slide</h2>
                        </div>
                        <div class="login User scroll mt-0">
                            <?php echo message("crudMessage"); ?>

                            <?php foreach ($user as $user) { ?>

                            <form action="updateUser.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="userID" value="<?php echo $user['userID']; ?>">
                                <input type="hidden" name="username" value="<?php echo $user['username']; ?>">
                                <label for="newUsername">Username</label>
                                <input type="text" name="newUsername" id="newUsername" value="<?php echo $user['username']; ?>">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" value="">
                                <input class="btn btn-primary" role="button" type="submit" name="updateUser" value="Save Changes">
                            </form>

                            <?php } ?>

                            <a class="btn btn-secondary w-100 mt-1" href="users.php">Cancel</a>

                        </div><!-- end login User -->
                    </div><!-- end close -->
                <?php } ?>
            </div><!-- end openModal -->

            <div id="deleteUserModal" class="modalDialog userModalDialog">
                <?php
                if(isset($_POST['userID']) && !empty($_POST['userID'])) { 
                    
                        $user = find_user_by_id($_POST['userID']);
                    ?>
                    <div><a href="#close" title="Close" class="close">X</a>
                        <!-- Content for modal -->
                        <div class="modal-header p-0 border-0">
                            <h2>Delete User</h2>
                        </div>
                        <div class="login User scroll mt-0">
                            <h4>Are you sure you want to DELETE this user?</h4>
                            <?php echo message("crudMessage"); ?>

                            <?php foreach ($user as $user) { ?>

                            <p>User: <?php echo $user['username']; ?></p>
                            
                            <form class="btn btn-danger w-100 mb-1" action="deleteUser.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="userID" value="<?php echo $user['userID']; ?>">
                                <input class="btn btn-danger" type="submit" name="deleteUser" value="DELETE">
                            </form>

                            <?php } ?>

                            <a class="btn btn-secondary w-100" href="users.php">Cancel</a>

                        </div><!-- end login User -->
                    </div><!-- end close -->
                <?php } ?>
            </div><!-- end openModal -->
        </section><!-- end Slide section-->
    </div>
    
    <?php include("../private/layout/footer.php"); ?>

</body>

