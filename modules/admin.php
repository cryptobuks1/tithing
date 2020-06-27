<?php
/**
 * Admin.php
 *
 * This is the Admin Center page. Only administrators
 * are allowed to view this page. This page displays the
 * database table of users and banned users. Admins can
 * choose to delete specific users, delete inactive users,
 * ban users, update user levels, etc.

 */
include("../include/classes/session.php");

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayUsers() {
    global $database;
    $q = "SELECT username, userlevel, email, timestamp, parent_directory "
            . "FROM " . TBL_USERS . " ORDER BY userlevel DESC, username";
    $result = $database->query($q);
    /* Error occurred, return given name by default */
    $num_rows = mysqli_num_rows($result);
    if (!$result || ($num_rows < 0)) {
        echo "Error displaying info";
        return;
    }
    if ($num_rows == 0) {
        echo "Database table empty";
        return;
    }
    /* Display table contents */
 
    echo "<table class=\"w3-table w3-bordered w3-striped w3-card-8 w3-tiny\"><tr><td><b>Username</b></td><td><b>Level</b></td><td><b>Email</b></td><td><b>Last Active</b></td><td><b>Group</b></td></tr>\n";

    for ($i = 0; $i < $num_rows; $i++) {
        mysqli_data_seek($result, $i);
        $row = mysqli_fetch_row($result);
        $uname = $row[0]; //username
        $ulevel = $row[1]; //userlevel
        $email = $row[2]; //email
        $time = $row[3]; //timestamp
        $parent = $row[4]; //parent directory
        echo "<tr><td>$uname</td><td>$ulevel</td><td>$email</td><td>".date("d-M-Y",$time)."</td><td>$parent</td></tr>\n";
    }
    echo "</table>";
}

/**
 * displayBannedUsers - Displays the banned users
 * database table in a nicely formatted html table.
 */
function displayBannedUsers() {
    global $database;
    $q = "SELECT username,timestamp "
            . "FROM " . TBL_BANNED_USERS . " ORDER BY username";
    $result = $database->query($q);
    /* Error occurred, return given name by default */
    $num_rows = mysqli_num_rows($result);
    if (!$result || ($num_rows < 0)) {
        echo "Error displaying info";
        return;
    }
    if ($num_rows == 0) {
        echo "Database table empty";
        return;
    }
    /* Display table contents */
    
    echo "<table class=\"w3-table w3-bordered w3-striped w3-card-8 w3-tiny\"><tr><td><b>Username</b></td><td><b>Time Banned</b></td></tr>\n";
    for ($i = 0; $i < $num_rows; $i++) {
        mysqli_data_seek($result, $i);
        $row = mysqli_fetch_row($result);
        $uname = $row[0]; //username
        $time = $row[1]; //timestamp
        echo "<tr><td>$uname</td><td>$time</td></tr>\n";
    }
    echo "</table>";
}

/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if (!$session->isAdmin()) {
    header("Location: ../index.php");
} else {
    /**
     * Administrator is viewing page, so display all
     * forms.
     */
    ?>

<?php include '../admin/admin-header.php'; ?>

            <div class="main-content">


                <div class="single-containers w3-container w3-card-2">     
                    <h1>Admin Center</h1>

                    <font size="4">Logged in as <b><?php echo $session->username; ?></b></font><br><br>
                    Back to [<a href="../index.php">Main Page</a>]
                </div>
    <?php
    /**
     * Display Users Table
     */
    ?>
                <div class="single-containers w3-container w3-card-2">       
                    <h3>Users Table Contents:</h3>
                <?php
                displayUsers();
                ?>
                </div>

                    <?php
                    /**
                     * Update User Level
                     */
                    ?>
                <div class="single-containers w3-container w3-tiny w3-card-2">
                    <h3>Update User Level</h3>
                <?php echo $form->error("upduser"); ?>

                    <form action="adminprocess.php" method="POST">
                       
                                    Username:
                                    <input type="text" name="upduser" maxlength="30" value="<?php echo $form->value("upduser"); ?>">
                              
                                    Level:
                                    <select name="updlevel">
                                        <option value="4">4
                                        <option value="3">3
                                        <option value="2">2
                                        <option value="1">1

                                    </select>
                               
                                   
                                    <input type="hidden" name="subupdlevel" value="1">
                                    <input type="submit" class="w3-btn w3-deep-orange" style="padding: 7px;" value="Update Level">
                            
                    </form>
                </div>



    <?php
    /**
     * Delete User
     */
    ?>
                <div class="single-containers w3-container w3-tiny w3-card-2">
                    <h3>Delete User</h3>
                <?php echo $form->error("deluser"); ?>
                    <form action="adminprocess.php" method="POST">
                        Username:<br>
                        <input type="text" name="deluser" maxlength="30" value="<?php echo $form->value("deluser"); ?>">
                        <input type="hidden" name="subdeluser" value="1">
                        <input type="submit" class="w3-btn w3-deep-orange" style="padding: 7px;" value="Delete User">
                    </form>
                </div>
    <?php
    /**
     * Delete Inactive Users
     */
    ?>
                <div class="single-containers w3-container w3-tiny w3-card-2">
                    <h3>Delete Inactive Users</h3>
                    This will delete all users (not administrators), who have not logged in to the site<br>
                    within a certain time period. You specify the days spent inactive.<br><br>

                    <form action="adminprocess.php" method="POST">
                      
                                    Days:<br>
                                    <select name="inactdays">
                                        <option value="3">3
                                        <option value="7">7
                                        <option value="14">14
                                        <option value="30">30
                                        <option value="100">100
                                        <option value="365">365
                                    </select>
                            
                                 
                                    <input type="hidden" name="subdelinact" value="1">
                                    <input type="submit" class="w3-btn w3-deep-orange" style="padding: 7px;" value="Delete All Inactive">

                           
                    </form></div>


    <?php
    /**
     * Ban User
     */
    ?>

                <div class="single-containers w3-container w3-tiny w3-card-2">
                    <h3>Ban User</h3>
                <?php echo $form->error("banuser"); ?>
                    <form action="adminprocess.php" method="POST">
                        Username:<br>
                        <input type="text" name="banuser" maxlength="30" value="<?php echo $form->value("banuser"); ?>">
                        <input type="hidden" name="subbanuser" value="1">
                        <input type="submit" class="w3-btn w3-deep-orange" style="padding: 7px;" value="Ban User">
                    </form></div>

    <?php
    /**
     * Display Banned Users Table
     */
    ?>
                <div class="single-containers w3-container w3-tiny w3-card-2">
                    <h3>Banned Users Table Contents:</h3>
                <?php
                displayBannedUsers();
                ?></div>

                    <?php
                    /**
                     * Delete Banned User
                     */
                    ?>
                <div class="single-containers w3-container w3-tiny w3-card-2 w3-margin-bottom">
                    <h3>Delete Banned User</h3>
                <?php echo $form->error("delbanuser"); ?>
                    <form action="adminprocess.php" method="POST">
                        Username:<br>
                        <input type="text" name="delbanuser" maxlength="30" value="<?php echo $form->value("delbanuser"); ?>">
                        <input type="hidden" name="subdelbanned" value="1">
                        <input type="submit" class="w3-btn w3-deep-orange" style="padding: 7px;" value="Delete Banned User">
                    </form>
                </div>
            </div>

            <script>
                function w3_open() {
                    document.getElementsByClassName("w3-sidenav")[0].style.display = "block";
                }
                function w3_close() {
                    document.getElementsByClassName("w3-sidenav")[0].style.display = "none";
                }
            </script>
            <?php
 require __DIR__ . '/../gen_footer.php';?>
  
        </body>
    </html>
    <?php
}
?>

