<?php

/**
 * Database.php
 * 
 * The Database class is meant to simplify the task of accessing
 * information from the website's database.
 *
 */
require 'constants.php';

class MySQLDB {
    var $random_salt_length = 32;
    var $connection;         //The MySQL database connection
    var $num_active_users;   //Number of active users viewing site
    var $num_active_guests;  //Number of active guests viewing site
    var $num_members;        //Number of signed-up users

    /* Note: call getNumMembers() to access $num_members! */

    /* Class constructor */

    function MySQLDB() {

        $this->connection = mysqli_connect("localhost", "root", "", "gracebaptist") or die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());

        /* Make connection to database */
        /*
          $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
          mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
         */

        /**
         * Only query database to find out number of members
         * when getNumMembers() is called for the first time,
         * until then, default value set.
         */
        $this->num_members = -1;

        if (TRACK_VISITORS) {
            /* Calculate number of users at site */
            $this->calcNumActiveUsers();

            /* Calculate number of guests at site */
            $this->calcNumActiveGuests();
        }
    }


/**
* Creates a unique Salt for hashing the password
* 
* @return
*/
function getSalt(){
	//global $random_salt_length;
	return bin2hex(openssl_random_pseudo_bytes($this->random_salt_length));
}
 
/**
* Creates password hash using the Salt and the password
* 
* @param $password
* @param $salt
* 
* @return
*/
function concatPasswordWithSalt($password,$salt){
	//global $random_salt_length;
	if($this->random_salt_length % 2 == 0){
		$mid = $this->random_salt_length / 2;
	}
	else{
		$mid = ($this->random_salt_length - 1) / 2;
	}
 
	return
	substr($salt,0,$mid - 1).$password.substr($salt,$mid,$this->random_salt_length - 1);
 
        }

    /**
     * confirmUserPass - Checks whether or not the given
     * username is in the database, if so it checks if the
     * given password is the same password in the database
     * for that user. If the user doesn't exist or if the
     * passwords don't match up, it returns an error code
     * (1 or 2). On success it returns 0.
     */
    function confirmUserPass($username, $password) {
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $q = "SELECT password, salt FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysqli_query($this->connection, $q);
        if (!$result || (mysqli_num_rows($result) < 1)) {
            //echo "<div class='btn btn-danger'>Username and password Error</div>";
            return 1; //Indicates username failure
        }

        /* Retrieve password from result, strip slashes */
        $dbarray = mysqli_fetch_array($result);
        $salt= $dbarray['salt'] = stripslashes($dbarray['salt']);
        $dbarray['password'] = stripslashes($dbarray['password']);
$hashPasswordDB = $dbarray['password'];
$md5HashPassword = md5($password);
$myHashPasswordDB = password_verify($this->concatPasswordWithSalt($password,$salt),$hashPasswordDB);

        $password = stripslashes($password);

        /* Validate that password is correct */
        if (($md5HashPassword == $dbarray['password']) || ($myHashPasswordDB == $dbarray['password'])) {
            //echo "<div class='btn btn-success'>Logged In</div>";
            return 0; //Success! Username and password confirmed
        } else {
            //echo "<div class='btn btn-danger'>Password Error</div>";
            return 2; //Indicates password failure
        }
    }

    /**
     * confirmUserID - Checks whether or not the given
     * username is in the database, if so it checks if the
     * given userid is the same userid in the database
     * for that user. If the user doesn't exist or if the
     * userids don't match up, it returns an error code
     * (1 or 2). On success it returns 0.
     */
    function confirmUserID($username, $userid) {
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $q = "SELECT userid FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysqli_query($this->connection, $q);
        if (!$result || (mysqli_num_rows($result) < 1)) {
            return 1; //Indicates username failure
        }

        /* Retrieve userid from result, strip slashes */
        $dbarray = mysqli_fetch_array($result);
        $dbarray['userid'] = stripslashes($dbarray['userid']);
        $userid = stripslashes($userid);

        /* Validate that userid is correct */
        if ($userid == $dbarray['userid']) {
            return 0; //Success! Username and userid confirmed
        } else {
            return 2; //Indicates userid invalid
        }
    }

    /**
     * usernameTaken - Returns true if the username has
     * been taken by another user, false otherwise.
     */
    function usernameTaken($username) {
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        $q = "SELECT username FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysqli_query($this->connection, $q);
        return (mysqli_num_rows($result) > 0);
    }

    /**
     * usernameBanned - Returns true if the username has
     * been banned by the administrator.
     */
    function usernameBanned($username) {
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }
        $q = "SELECT username FROM " . TBL_BANNED_USERS . " WHERE username = '$username'";
        $result = mysqli_query($this->connection, $q);
        return (mysqli_num_rows($result) > 0);
    }

    /**
     * addNewUser - Inserts the given (username, password, email, and other details)
     * info into the database. Appropriate user level is set.
     * Returns true on success, false otherwise.
     */
    function addNewUser($username, $password, $email, $fname, $sname, $phone, $dob, $address, $city, $state_region, $country, $comment) {
        $time = time();
        /* If admin sign up, give admin user level */
        if (strcasecmp($username, ADMIN_NAME) == 0) {
            $ulevel = ADMIN_LEVEL;
        } else {
            $ulevel = MASTER_LEVEL;
        }
        $q = "INSERT INTO " . TBL_USERS . " VALUES ('$username', '$password', '0', '$ulevel', '$email', '$time',  '$fname', '$sname', '$phone', '$dob', '$address', '$city', '$state_region', '$country', '$comment')";
        return mysqli_query($this->connection, $q);
    }

    // add new Master
    function addNewMaster($username, $password, $phone, $fname, $parent_directory) {

        $time = time();

        	//Get a unique Salt
		$salt         = $this->getSalt();
		
		//Generate a unique password Hash
		$passwordHash = password_hash($this->concatPasswordWithSalt($password,$salt),PASSWORD_DEFAULT);
		
        //$ulevel = MASTER_LEVEL;   //3
        $q = "INSERT INTO " . TBL_USERS . " VALUES ('$username', '$passwordHash', '0','$phone', '$time', '$parent_directory', '$fname', '0', '$salt')";
        return mysqli_query($this->connection, $q);
    }

   
    /**
     * updateUserField - Updates a field, specified by the field
     * parameter, in the user's row of the database.
     */
    function updateUserField($username, $field, $value) {
        $q = "UPDATE " . TBL_USERS . " SET " . $field . " = '$value' WHERE username = '$username'";
        return mysqli_query($this->connection, $q);
    }

    function updateUserPassword($username, $password, $salt) {
        $q = "UPDATE " . TBL_USERS . " SET password = '$password', salt = '$salt' WHERE username = '$username'";
        return mysqli_query($this->connection, $q);
    }

    /**
     * getUserInfo - Returns the result array from a mysql
     * query asking for all information stored regarding
     * the given username. If query fails, NULL is returned.
     */
    function getUserInfo($username) {
        $q = "SELECT * FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysqli_query($this->connection, $q);
        /* Error occurred, return given name by default */
        if (!$result || (mysqli_num_rows($result) < 1)) {
            return NULL;
        }
        /* Return result array */
        $dbarray = mysqli_fetch_array($result);
        return $dbarray;
    }

    function getUserOnly($username) {
        $q = "SELECT username FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysqli_query($this->connection, $q);
        /* Error occurred, return given name by default */
        if (!$result || (mysqli_num_rows($result) < 1)) {
            return NULL;
        }
        /* Return result array */
        $dbarray = mysqli_fetch_array($result);
        return $dbarray;
    }

    /**
     * getNumMembers - Returns the number of signed-up users
     * of the website, banned members not included. The first
     * time the function is called on page load, the database
     * is queried, on subsequent calls, the stored result
     * is returned. This is to improve efficiency, effectively
     * not querying the database when no call is made.
     */
    function getNumMembers() {
        if ($this->num_members < 0) {
            $q = "SELECT * FROM " . TBL_USERS;
            $result = mysqli_query($this->connection, $q);
            $this->num_members = mysqli_num_rows($result);
        }
        return $this->num_members;
    }

    function format_to_two($value) {
        if ($value == "") {
            $value = "0.00";
        } else {
            return number_format($value, 2, ".", ",");
        }
    }

    /**
     * calcNumActiveUsers - Finds out how many active users
     * are viewing site and sets class variable accordingly.
     */
    function calcNumActiveUsers() {
        /* Calculate number of users at site */
        $q = "SELECT * FROM " . TBL_ACTIVE_USERS;
        $result = mysqli_query($this->connection, $q);
        $this->num_active_users = mysqli_num_rows($result);
    }

    /**
     * calcNumActiveGuests - Finds out how many active guests
     * are viewing site and sets class variable accordingly.
     */
    function calcNumActiveGuests() {
        /* Calculate number of guests at site */
        $q = "SELECT * FROM " . TBL_ACTIVE_GUESTS;
        $result = mysqli_query($this->connection, $q);
        $this->num_active_guests = mysqli_num_rows($result);
    }

    /**
     * addActiveUser - Updates username's last active timestamp
     * in the database, and also adds him to the table of
     * active users, or updates timestamp if already there.
     */
    function addActiveUser($username, $time) {
        $q = "UPDATE " . TBL_USERS . " SET timestamp = '$time' WHERE username = '$username'";
        mysqli_query($this->connection, $q);

        if (!TRACK_VISITORS)
            return;
        $q = "REPLACE INTO " . TBL_ACTIVE_USERS . " VALUES ('$username', '$time')";
        mysqli_query($this->connection, $q);
        $this->calcNumActiveUsers();
    }

    /* addActiveGuest - Adds guest to active guests table */

    function addActiveGuest($ip, $time) {
        if (!TRACK_VISITORS)
            return;
        $q = "REPLACE INTO " . TBL_ACTIVE_GUESTS . " VALUES ('$ip', '$time')";
        mysqli_query($this->connection, $q);
        $this->calcNumActiveGuests();
    }

    /* These functions are self explanatory, no need for comments */

    /* removeActiveUser */

    function removeActiveUser($username) {
        if (!TRACK_VISITORS)
            return;
        $q = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE username = '$username'";
        mysqli_query($this->connection, $q);
        $this->calcNumActiveUsers();
    }

    /* removeActiveGuest */

    function removeActiveGuest($ip) {
        if (!TRACK_VISITORS)
            return;
        $q = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE ip = '$ip'";
        mysqli_query($this->connection, $q);
        $this->calcNumActiveGuests();
    }

    /* removeInactiveUsers */

    function removeInactiveUsers() {
        if (!TRACK_VISITORS)
            return;
        $timeout = time() - USER_TIMEOUT * 60;
        $q = "DELETE FROM " . TBL_ACTIVE_USERS . " WHERE timestamp < $timeout";
        mysqli_query($this->connection, $q);
        $this->calcNumActiveUsers();
    }

    /* removeInactiveGuests */

    function removeInactiveGuests() {
        if (!TRACK_VISITORS)
            return;
        $timeout = time() - GUEST_TIMEOUT * 60;
        $q = "DELETE FROM " . TBL_ACTIVE_GUESTS . " WHERE timestamp < $timeout";
        mysqli_query($this->connection, $q);
        $this->calcNumActiveGuests();
    }

    /**
     * query - Performs the given query on the database and
     * returns the result, which may be false, true or a
     * resource identifier.
     */
    function query($query) {
        return mysqli_query($this->connection, $query);
    }

    /* Getting the prices end here so anything below here is different activity. */

    /* Getting the station names from the user who is logged into the system */

    /* Checking that the user is assign to a station */

    function is_assigned($station, $user) {
        $wow = "SELECT * FROM assignment WHERE (`username` = '$user' AND `station_name` = '$station')";
        $result = mysqli_query($this->connection, $wow);

        $rows = mysqli_num_rows($result);

        return $rows;
    }

//This is to get the stations names in arrey 
    function get_station_name($user) {
        $q = "SELECT * FROM `assignment` WHERE  `username` = '$user'";
        $result = mysqli_query($this->connection, $q);
        while ($row = mysqli_fetch_assoc($result)) {

            return $row['station_name'];
        }
    }

    /* Getting the station names from the user who is logged into the system */

    function get_current_time($table, $column, $station) {
        $q = "SELECT MAX($column) FROM $table WHERE station_name ='$station' ";
        $result = mysqli_query($this->connection, $q);

        while ($row = mysqli_fetch_assoc($result)) {

            return $row['MAX(' . $column . ')'];
        }
    }

    function get_permission_links($username) {
        $q = "SELECT * FROM ecka_modules LEFT JOIN ecka_grant_access ON ecka_modules.module_id = ecka_grant_access.permission_id WHERE ecka_grant_access.username = '$username' ORDER BY sort ASC";
        $result = mysqli_query($this->connection, $q);

        $num_rows = mysqli_num_rows($result);

        for ($i = 0; $i < $num_rows; $i++) {
            mysqli_data_seek($result, $i);
            $row = mysqli_fetch_row($result);

            //Get module and permission
            $moduleId = $row[0]; //moduleId
            $sort = $row[1]; // Sort Order
            $language = $row[2]; // The language to be displayed on the menu
            $logo = $row[3]; // The icon to display on the menu
            $id = $row[4];
            $permissionId = $row[5]; // The permissionon id
            $usernames = $row[6]; // Username can be accessed here.

            echo " <li class=\"nav-item\"><a class=\"nav-link\" ng-href=\"#!/" . $moduleId . "\"><i class=\"fas fa-fw " . $logo . "\"></i><span>" . $language . "</span></a></li>";
        }
    }


    function getPermissionCheck() {
        $q = "SELECT * FROM ecka_modules WHERE module_id != 'logout' ORDER BY sort ASC";
        $result = mysqli_query($this->connection, $q);

        $num_rows = mysqli_num_rows($result);

        for ($i = 0; $i < $num_rows; $i++) {
            mysqli_data_seek($result, $i);
            $row = mysqli_fetch_row($result);

            //Get module and permission
            $moduleId = $row[0]; //moduleId
            $sort = $row[1]; // Sort Order
            $language = $row[2]; // The language to be displayed on the menu
          
            echo "<div class=\"custom-control custom-checkbox\">"
            ."<input type=\"checkbox\" value=\"$moduleId\" class=\"custom-control-input\" id=\"$moduleId\">"
            ."<label class=\"custom-control-label\" for=\"$moduleId\">$language</label></div><hr/> ";

                   }
    }

    function isPermissionAllowed($username, $pagename) {
        $q = "SELECT * FROM ecka_modules LEFT JOIN ecka_grant_access ON ecka_modules.module_id = ecka_grant_access.permission_id WHERE ecka_grant_access.username = '$username' AND ecka_modules.module_id = '$pagename' ORDER BY sort ASC";
        $result = mysqli_query($this->connection, $q);
        $row = mysqli_num_rows($result);

        if ($row == 1) {
            return true;
        }
    }

    function isAccessGranted($username, $pagename){
        $q = "SELECT * FROM ecka_grant_access WHERE username = '$username' AND permission_id = '$pagename'";
        $result = mysqli_query($this->connection, $q);
        $row = mysqli_num_rows($result);

        if($row === 1) {
            // Means that the value  result match and therefore need to be deleted if empty field is submitted
            return 1;
        }else{
            return 0;
        }
    }

function insertAccessControl($username, $pagename){
$insertQuery ="INSERT INTO `ecka_grant_access`(`id`, `permission_id`, `username`) VALUES (null, '$pagename', '$username')";
$result = mysqli_query($this->connection, $insertQuery);
if(!$result){
    echo "<div class='btn btn-danger'>".$username." Access not granted for ".$pagename."</div>"; 
}else{
    echo "<div class='btn btn-success'>".$username." Access granted for ".$pagename."</div>";
}
}

function deleteAccessControl($username, $pagename){
$deleteQuery = "DELETE FROM `ecka_grant_access` WHERE permission_id ='$pagename' AND username='$username'";
$result = mysqli_query($this->connection, $deleteQuery);
if(!$result){
    echo "<div class='btn btn-danger'>".$username." Access not revoked for ".$pagename."</div>"; 
}else{
    echo "<div class='btn btn-success'>".$username." Access revoked for ".$pagename."</div>";
}
}


    /*
     * The api Section and it will be on last section of the page always. 
     * The api will be responsible for retreiving data for other applications like the mobile app and the websites
     * 
     * 
     *      */


    function getEmployeeData($request) {
        $q = "SELECT * FROM members WHERE card_number = '$request'";
        $results = mysqli_query($this->connection, $q);

        $data = array();
        while ($row = mysqli_fetch_array($results)) {
            $data = array(
                "fname" => $row['first_name'],
                "surname" => $row['last_name'],
                "phone" => $row['phone'],
                "dob" => $row['dob'],
                "email" => $row['email_address'],
                "zone" => $row['zone'],
                "fship" => $row['fship']
            );
        }
        echo json_encode($data);
    }

    function isUserExist($userId){
        $selectQuery =  "SELECT * FROM `members` WHERE `members`.card_number = '$userId'"; 
        //$sumSelectQuery= "SELECT SUM()";
        $result = mysqli_query($this->connection, $selectQuery);
        $row = mysqli_fetch_assoc($result);
        if(!$result && mysqli_num_rows($result)<= 0){
            return false;
        }else{
            return true;
        }
    }
    function returnUserName($userId){
        $selectQuery =  "SELECT * FROM `members` WHERE `members`.card_number = '$userId'"; 
        //$sumSelectQuery= "SELECT SUM()";
        $result = mysqli_query($this->connection, $selectQuery);
        $row = mysqli_fetch_assoc($result);
        if(!$result && mysqli_num_rows($result)<= 0){
return false;
        }else{
            return $row['first_name']." ".$row['last_name'];
        }


    }

    function getAllReport($from, $to){
        $selectQuery =  "SELECT * FROM `members` RIGHT JOIN `donations` ON `members`.card_number = donations.user_id WHERE (donations.date_paid >= '$from' AND donations.date_paid <= '$to') AND `del`= 0 UNION ALL SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON members.card_number = tithe_contribution.card_number WHERE (tithe_contribution.date_paid >= '$from' AND tithe_contribution.date_paid <= '$to') AND (`del`= 0) UNION ALL SELECT * FROM `members`  RIGHT JOIN `dues_contribution`   ON members.card_number = dues_contribution.user_id WHERE (dues_contribution.date_paid >= '$from' AND dues_contribution.date_paid <= '$to') AND (`del`=0) UNION ALL SELECT * FROM `members` RIGHT JOIN `projects` ON members.card_number = projects.user_id WHERE (projects.date_paid >= '$from' AND projects.date_paid <= '$to') AND (`del`=0) ORDER BY date_paid DESC"; 
        //$sumSelectQuery= "SELECT SUM()";
        $result = mysqli_query($this->connection, $selectQuery);
        $num_rows = mysqli_num_rows($result);
        $total_amount= $amount=0;
        if(!$result || $num_rows <=0){
            echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\"><div class='btn btn-info'>No Data available</div>";
        }else{
     echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\">"
     ."<h6 class=\"h4\">Report for All Transactions from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))."</h6>"
     ."<table class=\"table table-hover table-striped\"><thead class=\"thead-dark\"><tr><th scope=\"col\">Date</th><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Trans. Type</th><th scope=\"col\">Reason</th><th scope=\"col\">Amount</th></tr></thead><tbody>";
                        while($row = mysqli_fetch_row($result)){
            
                       
                        $first_name= $row[1];
                        $surname = $row[2];
                        $card_number = $row[14];
                        $id=$row[10]; 
                        $amount = $row[11];
                        $purpose = $row[12];
                        $date_paid = $row[15];
                        $trans_type = $row[16]; 
                        
                        if($trans_type == 'project'){
                            $purpose = $this->getProjectName($purpose);
                        }
                      //for($i=0; $i<=$num_rows; $i++){
        $total_amount+= $amount;
    //}
                     echo "<tr class=\"danger\"><td>".date("d.m.Y", strtotime($date_paid))."</td><td>$first_name"." $surname</td><td>$card_number</td><td>$trans_type</td><td>$purpose</td><td>Ghc " .$this->format_to_two($amount)."</td></tr>";
        
    } 
                      
    echo "<tr class=\"thead-dark\"><th>Total</th><td></td><td></td><td></td><td></td><th class=\" text-bold\">Ghc " .$this->format_to_two($total_amount)."</th></tr>";
        


    echo "</table></div></div>";
    
        }
    
    }


    function getFellowshipReport($fellowship, $from, $to){
        if($fellowship == ""){
            $header = "<h6 class=\"h4\">Report for Non Fellowship Transactions from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))."</h6>";
   
        }else{
            $header = "<h6 class=\"h4\">Report for ".$fellowship." Fellowship Transactions from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))."</h6>";
   
        }



        $selectQuery =  "SELECT * FROM `members` RIGHT JOIN `donations` ON `members`.card_number = donations.user_id WHERE (donations.date_paid >= '$from' AND donations.date_paid <= '$to') AND `del`= 0 AND members.fship ='$fellowship' UNION ALL SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON members.card_number = tithe_contribution.card_number WHERE (tithe_contribution.date_paid >= '$from' AND tithe_contribution.date_paid <= '$to') AND (`del`= 0)  AND members.fship ='$fellowship' UNION ALL SELECT * FROM `members`  RIGHT JOIN `dues_contribution`   ON members.card_number = dues_contribution.user_id WHERE (dues_contribution.date_paid >= '$from' AND dues_contribution.date_paid <= '$to') AND (`del`=0)  AND members.fship ='$fellowship' UNION ALL SELECT * FROM `members` RIGHT JOIN `projects` ON members.card_number = projects.user_id  WHERE (projects.date_paid >= '$from' AND projects.date_paid <= '$to') AND (`del`=0)  AND members.fship ='$fellowship' ORDER BY date_paid DESC"; 
        //$sumSelectQuery= "SELECT SUM()";
        $result = mysqli_query($this->connection, $selectQuery);
        $num_rows = mysqli_num_rows($result);
        $total_amount= $amount=0;
        if(!$result || $num_rows <=0){
            echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\"><div class='btn btn-info'>No Data available</div>";
        }else{
     echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\">"
     .$header ."<table class=\"table table-hover table-striped\"><thead class=\"thead-dark\"><tr><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Trans. Type</th><th scope=\"col\">Reason</th><th scope=\"col\">Amount</th></tr></thead><tbody>";
                        while($row = mysqli_fetch_row($result)){
            
                       
                        $first_name= $row[1];
                        $surname = $row[2];
                        $card_number = $row[14];
                        $id=$row[10]; 
                        $amount = $row[11];
                        $purpose = $row[12];
                        $date_paid = $row[15];
                        $trans_type = $row[16]; 
                        
                        if($trans_type == 'project'){
                            $purpose = $this->getProjectName($purpose);
                        }
                      //for($i=0; $i<=$num_rows; $i++){
        $total_amount+= $amount;
    //}
                     echo "<tr class=\"danger\"><td>$first_name"." $surname</td><td>$card_number</td><td>$trans_type</td><td>$purpose</td><td>Ghc " .$this->format_to_two($amount)."</td></tr>";
        
    } 
                      
    echo "<tr class=\"thead-dark\"><th>Total</th><td></td><td></td><td></td><th class=\" text-bold\">Ghc " .$this->format_to_two($total_amount)."</th></tr>";
        


    echo "</table></div></div>";
    
        }
    
    }

    function getMemberReport($member, $from, $to){

        if($member == ""){
            $header= "<h6 class=\"h4\">Report for Members Contribution from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))."</h6>";
            $selectQuery =  "SELECT * FROM `members` RIGHT JOIN `donations` ON `members`.card_number = donations.user_id WHERE (donations.date_paid >= '$from' AND donations.date_paid <= '$to') AND `del`= 0 UNION ALL SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON members.card_number = tithe_contribution.card_number WHERE (tithe_contribution.date_paid >= '$from' AND tithe_contribution.date_paid <= '$to') AND (`del`= 0) UNION ALL SELECT * FROM `members`  RIGHT JOIN `dues_contribution` ON members.card_number = dues_contribution.user_id WHERE (dues_contribution.date_paid >= '$from' AND dues_contribution.date_paid <= '$to') AND (`del`=0) UNION ALL SELECT * FROM `members` INNER JOIN `projects` ON members.card_number = `projects`.`user_id` WHERE (projects.date_paid >= '$from' AND projects.date_paid <= '$to') AND projects.del =0 ORDER BY date_paid DESC"; 

        }else{
            $header ="<h6 class=\"h4\">Report for Member Contribution from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))." for <b>".$this->returnUserName($member)."</b></h6>";
            $selectQuery =  "SELECT * FROM `members` RIGHT JOIN `donations` ON `members`.card_number = donations.user_id WHERE (donations.date_paid >= '$from' AND donations.date_paid <= '$to') AND `del`= 0 AND donations.user_id ='$member' UNION ALL SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON members.card_number = tithe_contribution.card_number WHERE (tithe_contribution.date_paid >= '$from' AND tithe_contribution.date_paid <= '$to') AND (`del`= 0)  AND tithe_contribution.card_number ='$member' UNION ALL SELECT * FROM `members`  RIGHT JOIN `dues_contribution`   ON members.card_number = dues_contribution.user_id WHERE (dues_contribution.date_paid >= '$from' AND dues_contribution.date_paid <= '$to') AND (`del`=0)  AND dues_contribution.user_id ='$member' UNION ALL SELECT * FROM `members` INNER JOIN `projects` ON members.card_number = `projects`.`user_id` WHERE (projects.date_paid >= '$from' AND projects.date_paid <= '$to') AND projects.del =0 AND projects.user_id = '$member' ORDER BY date_paid DESC"; 
     
        }
        $result = mysqli_query($this->connection, $selectQuery);
        $num_rows = mysqli_num_rows($result);
        $total_amount= $amount=0;
        if(!$result || $num_rows <=0){
            echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\"><div class='btn btn-info'>No Data available</div>";
        }else{
     echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\">"
     .$header
     ."<table class=\"table table-hover table-striped\"><thead class=\"thead-dark\"><tr><th scope=\"col\">Date</th><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Trans. Type</th><th scope=\"col\">Reason</th><th scope=\"col\">Amount</th></tr></thead><tbody>";
                        while($row = mysqli_fetch_row($result)){
            
                       
                        $first_name= $row[1];
                        $surname = $row[2];
                        $card_number = $row[14];
                        $id=$row[10]; 
                        $amount = $row[11];
                        $purpose = $row[12];
                        $date_paid = $row[15];
                        $trans_type = $row[16]; 
                        
                        if($trans_type == 'project'){
                            $purpose = $this->getProjectName($purpose);
                        }
                      //for($i=0; $i<=$num_rows; $i++){
        $total_amount+= $amount;
    //}
                     echo "<tr class=\"danger\"><td>".date("d.m.Y", strtotime($date_paid))."</td><td>$first_name"." $surname</td><td>$card_number</td><td>$trans_type</td><td>$purpose</td><td>Ghc " .$this->format_to_two($amount)."</td></tr>";
        
    } 
                      
    echo "<tr class=\"thead-dark\"><th>Total</th><td></td><td></td><td></td><td></td><th class=\" text-bold\">Ghc " .$this->format_to_two($total_amount)."</th></tr>";
        


    echo "</table></div></div>";
    
        }
    
    }

/**
 * Undocumented function
 *This function returns the month of year that is paid for each individual 
 * @param [type] $month_year
 * @param [type] $from
 * @param [type] $to
 * @return void
 */
    function getTithes($month_year, $from, $to){
        if($month_year == ""){
            $header= "<h6 class=\"h4\">Report for all Tithe Contribution from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))."</h6>";
            $selectQuery =   "SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON `members`.card_number = `tithe_contribution`.card_number WHERE (`tithe_contribution`.date_paid >= '$from' AND `tithe_contribution`.date_paid <= '$to') AND `del`= 0 ORDER BY `tithe_contribution`.`date_paid` DESC";

        }else{
            $header ="<h6 class=\"h4\">Report for Tithe Contribution from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))." for ".$month_year."</h6>";
          $selectQuery =   "SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON `members`.card_number = `tithe_contribution`.card_number WHERE (`tithe_contribution`.date_paid >= '$from' AND `tithe_contribution`.date_paid <= '$to') AND `del`= 0 AND `month`='$month_year' ORDER BY `tithe_contribution`.`date_paid` DESC";
     
        }
         $result = mysqli_query($this->connection, $selectQuery);
      $num_rows = mysqli_num_rows($result);
      $total_amount= $amount=0;
      if(!$result || $num_rows <=0){
          echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\"><div class='btn btn-info'>No Data available</div>";
      }else{
   echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\">"
   .$header
   ."<table class=\"table table-hover table-striped\"><thead class=\"thead-dark\"><tr><th>Date:</th><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Reason</th><th scope=\"col\">Amount</th></tr></thead><tbody>";
                      while($row = mysqli_fetch_row($result)){
          
                     
                      $first_name= $row[1];
                      $surname = $row[2];
                      $card_number = $row[14];
                      $id=$row[10]; 
                      $amount = $row[11];
                      $purpose = $row[12];
                      $trans_type = $row[16]; 
                      $date_paid = $row[15];
                      
                      if($trans_type == 'project'){
                          $purpose = $this->getProjectName($purpose);
                      }
                    //for($i=0; $i<=$num_rows; $i++){
      $total_amount+= $amount;
  //}
                   echo "<tr class=\"danger\"><td>".date("d.m.Y", strtotime($date_paid))."</td><td>$first_name"." $surname</td><td>$card_number</td><td>$purpose</td><td>Ghc " .$this->format_to_two($amount)."</td></tr>";
      
  } 
                    
  echo "<tr class=\"thead-dark\"><th>Total</th><td></td><td></td><td></td><th class=\" text-bold\">Ghc " .$this->format_to_two($total_amount)."</th></tr>";
      


  echo "</table></div></div>";
  
      }
   
    }

function getProjects($projectNumber, $from, $to){
        if($projectNumber == ""){
            $header= "<h6 class=\"h4\">Report for all Project Contribution from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))."</h6>";
            $selectQuery =   "SELECT * FROM `projects` WHERE (date_paid >= '$from' AND date_paid <= '$to') AND (`del`=0) ORDER BY `date_paid` DESC";

        }else{
            $header ="<h6 class=\"h4\">Report for Project Contribution from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))." for <b>".$this->getProjectName($projectNumber)."</b></h6>";
          $selectQuery =   "SELECT * FROM `projects` WHERE (date_paid >= '$from' AND date_paid <= '$to') AND (`del`=0) AND `project_id`='$projectNumber' ORDER BY `date_paid` DESC";
     
        }
         $result = mysqli_query($this->connection, $selectQuery);
      $num_rows = mysqli_num_rows($result);
      $total_amount= $amount=0;
      if(!$result || $num_rows <= 0){
          echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\"><div class='btn btn-info'>No Data available</div>";
      }else{
   echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\">"
   .$header
   ."<table class=\"table table-hover table-striped\"><thead class=\"thead-dark\"><tr><th>Date:</th><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Reason</th><th scope=\"col\">Amount</th></tr></thead><tbody>";
                      while($row = mysqli_fetch_row($result)){
          
                     
                      $id= $row[0];
                      $project_id = $row[2];
                      $description = $row[3];
                    
                      $amount = $row[1];
                      $user_id = $row[4];
                      $trans_type = $row[6]; 
                      $date_paid = $row[5];
                      $deletion = $row[7];
                      
                      if($this->isUserExist($user_id)){
$username = $this->returnUserName($user_id);

                      }else{
                        $username = "";
                      }
                      
                          $purpose = $this->getProjectName($project_id);
                      
                    //for($i=0; $i<=$num_rows; $i++){
      $total_amount+= $amount;
  //}
                   echo "<tr class=\"danger\"><td>".date("d.m.Y", strtotime($date_paid))."</td><td>$username</td><td>$user_id</td><td>$purpose</td><td>Ghc " .$this->format_to_two($amount)."</td></tr>";
      
  } 
                    
  echo "<tr class=\"thead-dark\"><th>Total</th><td></td><td></td><td></td><th class=\" text-bold\">Ghc " .$this->format_to_two($total_amount)."</th></tr>";
      


  echo "</table></div></div>";
  
      }
   
    }


/**
 * Function for retrieving the Donations values
 */

    function getDonations($from, $to){
        
            $header ="<h6 class=\"h4\">Report for Donations from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))."</h6>";
          $selectQuery =   "SELECT * FROM `members` RIGHT JOIN `donations` ON `members`.card_number = `donations`.user_id WHERE (`donations`.date_paid >= '$from' AND `donations`.date_paid <= '$to') AND `del`= 0 ORDER BY `donations`.`date_paid` DESC";
     
    
         $result = mysqli_query($this->connection, $selectQuery);
      $num_rows = mysqli_num_rows($result);
      $total_amount= $amount=0;
      if(!$result || $num_rows <=0){
          echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\"><div class='btn btn-info'>No Data available</div>";
      }else{
   echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\">"
   .$header
   ."<table class=\"table table-hover table-striped\"><thead class=\"thead-dark\"><tr><th>Date:</th><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Reason</th><th scope=\"col\">Amount</th></tr></thead><tbody>";
                      while($row = mysqli_fetch_row($result)){
          
                     
                      $first_name= $row[1];
                      $surname = $row[2];
                      $card_number = $row[14];
                      $id=$row[10]; 
                      $amount = $row[11];
                      $purpose = $row[12];
                      $trans_type = $row[16]; 
                      $date_paid = $row[15];
                      
                      if($trans_type == 'project'){
                          $purpose = $this->getProjectName($purpose);
                      }
                    //for($i=0; $i<=$num_rows; $i++){
      $total_amount+= $amount;
  //}
                   echo "<tr class=\"danger\"><td>".date("d.m.Y", strtotime($date_paid))."</td><td>$first_name"." $surname</td><td>$card_number</td><td>$purpose</td><td>Ghc " .$this->format_to_two($amount)."</td></tr>";
      
  } 
                    
  echo "<tr class=\"thead-dark\"><th>Total</th><td></td><td></td><td></td><th class=\" text-bold\">Ghc " .$this->format_to_two($total_amount)."</th></tr>";
      


  echo "</table></div></div>";
  
      }
   
    }

    /**
     * Undocumented function
     *This function returns the report for the dues collection on single instances
     * @param [type] $benefiter
     * @param [type] $from
     * @param [type] $to
     * @return void
     */
    function getDues($benefiter, $from, $to){
        if($benefiter == ""){
            $header= "<h6 class=\"h4\">Report for all Dues Contribution from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))."</h6>";
            $selectQuery =   "SELECT * FROM `members` RIGHT JOIN `dues_contribution` ON `members`.card_number = `dues_contribution`.user_id WHERE (`dues_contribution`.date_paid >= '$from' AND `dues_contribution`.date_paid <= '$to') AND `del`= 0 ORDER BY `dues_contribution`.`date_paid` DESC";

        }else{
            $header ="<h6 class=\"h4\">Report for Dues Contribution from ".date("d F Y", strtotime($from))." to ". date("d F Y", strtotime($to))." for ".$benefiter."</h6>";
          $selectQuery =   "SELECT * FROM `members` RIGHT JOIN `dues_contribution` ON `members`.card_number = `dues_contribution`.user_id WHERE (`dues_contribution`.date_paid >= '$from' AND `dues_contribution`.date_paid <= '$to') AND `del`= 0 AND `benefiter`='$benefiter' ORDER BY `dues_contribution`.`date_paid` DESC";
     
        }
         $result = mysqli_query($this->connection, $selectQuery);
      $num_rows = mysqli_num_rows($result);
      $total_amount= $amount=0;
      if(!$result || $num_rows <=0){
          echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\"><div class='btn btn-info'>No Data available</div>";
      }else{
   echo "<div class=\"card border-0 shadow-sm my-3\"><div class=\"card-body p-3\" id=\"display_editor\">"
   .$header
   ."<table class=\"table table-hover table-striped\"><thead class=\"thead-dark\"><tr><th>Date:</th><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Reason</th><th scope=\"col\">Amount</th></tr></thead><tbody>";
                      while($row = mysqli_fetch_row($result)){
          
                     
                      $first_name= $row[1];
                      $surname = $row[2];
                      $card_number = $row[14];
                      $id=$row[10]; 
                      $amount = $row[11];
                      $purpose = $row[12];
                      $trans_type = $row[16]; 
                      $date_paid = $row[15];
                      
                      if($trans_type == 'project'){
                          $purpose = $this->getProjectName($purpose);
                      }
                    //for($i=0; $i<=$num_rows; $i++){
      $total_amount+= $amount;
  //}
                   echo "<tr class=\"danger\"><td>".date("d.m.Y", strtotime($date_paid))."</td><td>$first_name"." $surname</td><td>$card_number</td><td>$purpose</td><td>Ghc " .$this->format_to_two($amount)."</td></tr>";
      
  } 
                    
  echo "<tr class=\"thead-dark\"><th>Total</th><td></td><td></td><td></td><th class=\" text-bold\">Ghc " .$this->format_to_two($total_amount)."</th></tr>";
      


  echo "</table></div></div>";
  
      }
   
    }
/**
 * Undocumented function
 * This function returns 
 *
 * @param [int] $request
 * @param [string] $trans_type
 * @return void
 */
function getTransaction($request, $trans_type){
    if($trans_type == 'dues'){
        $q = "SELECT * FROM dues_contribution WHERE (id = '$request') AND (`del`=0)";
    }elseif($trans_type == 'project'){
        $q = "SELECT * FROM projects WHERE (id = '$request') AND (`del`=0)";
    }elseif($trans_type == 'tithe'){
        $q = "SELECT * FROM tithe_contribution WHERE (id = '$request') AND (`del`=0)";
    }elseif($trans_type == 'donation'){
        $q = "SELECT * FROM donations WHERE (id = '$request') AND (`del`=0)";
    }
    
        $results = mysqli_query($this->connection, $q);

        $data = array();
        while ($row = mysqli_fetch_array($results)) {
            if($row[6] == 'project'){
                $reason = $row[2].": ".$this->getProjectName($row[2]);
            }else{
                $reason = $row[2];
            }
            $data = array(
                "id" => $row[0],
                "amount" => $row[1],
                "reason" => $reason,
                "desc" => $row[3],
                "user_id" => $row[4],
                "date" => $row[5],
                "type" => $row[6]
            );
        }
        echo json_encode($data);
}


    function getProjectName($id){
        $selectQuery =  "SELECT * FROM project_names WHERE id='$id'"; 
        $result = mysqli_query($this->connection, $selectQuery);
        if(!$result || mysqli_num_rows($result) <= 0){
            return "No Data";
        }else{
            $row = mysqli_fetch_array($result);

            return $row['project_name'];
            
        }
    }
    function getEditors(){
    $selectQuery =  "SELECT * FROM `members` RIGHT JOIN `donations` ON `members`.card_number = donations.user_id WHERE `del`=0 UNION ALL SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON members.card_number = tithe_contribution.card_number WHERE `del`=0 UNION ALL SELECT * FROM `members`  RIGHT JOIN `dues_contribution`   ON members.card_number = dues_contribution.user_id WHERE `del`=0 UNION ALL SELECT * FROM `members` RIGHT JOIN `projects` ON members.card_number = projects.user_id WHERE `del` = 0 ORDER BY date_paid DESC LIMIT 100"; 
    $result = mysqli_query($this->connection, $selectQuery);
    $num_rows = mysqli_num_rows($result);
    if(!$result || $num_rows <=0){
        echo "<div class='btn btn-info'>No Data available</div>";
    }else{
 echo "<table class=\"table table-hover\"><thead class=\"thead-dark\"><tr><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Amount</th><th scope=\"col\">Reason</th><th scope=\"col\">Trans Type</th><th></th></tr></thead><tbody>";
                    while($row = mysqli_fetch_row($result)){
        
                   
                    $first_name= $row[1];
                    $surname = $row[2];
                    $card_number = $row[14];
                    $id=$row[10]; 
                    $amount = $row[11];
                    $purpose = $row[12];
                    $trans_type = $row[16]; 
                    
                    if($trans_type == 'project'){
                        $purpose = $this->getProjectName($purpose);
                    }
                  
                 echo "<tr class=\"danger\" id=\"item_$id\"><td>$first_name". " $surname</td><td>$card_number</td><td>Ghc ".$this->format_to_two($amount)."</td><td>$purpose</td><td>$trans_type</td><td> <button class=\"buttonedit-trans text-info\" id=\"$trans_type-$id\" title=\"Edit\"><i class=\"fas fa-edit pr-2\"></i></button><button class=\"buttondel-trans text-danger\" id=\"$trans_type-$id\" title=\"Delete\"><i class=\"fas fa-trash pl-2\"></button></i></td></tr>";
    
}
echo "</table>";

    }
    

}

function getEditorUser($userId){
    $selectQuery =  "SELECT * FROM `members` RIGHT JOIN `donations` ON `members`.card_number = donations.user_id WHERE (members.card_number = '$userId') AND `del`= 0 UNION ALL SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON members.card_number = tithe_contribution.card_number WHERE (members.card_number = '$userId') AND (`del`= 0) UNION ALL SELECT * FROM `members`  RIGHT JOIN `dues_contribution`   ON members.card_number = dues_contribution.user_id WHERE (members.card_number = '$userId') AND (`del`=0) UNION ALL SELECT * FROM `members` RIGHT JOIN `projects` ON members.card_number = projects.user_id WHERE (projects.user_id = '$userId' OR members.card_number = '$userId') AND (`del`=0) ORDER BY date_paid DESC LIMIT 100"; 
    $result = mysqli_query($this->connection, $selectQuery);
    $num_rows = mysqli_num_rows($result);
    if(!$result || $num_rows <=0){
        echo "<div class='btn btn-info'>No Data available</div>";
    }else{
 echo "<table class=\"table table-hover\"><thead class=\"thead-dark\"><tr><th scope=\"col\">User Name</th><th scope=\"col\">Card Number</th><th scope=\"col\">Amount</th><th scope=\"col\">Reason</th><th scope=\"col\">Trans Type</th><th></th></tr></thead><tbody>";
                    while($row = mysqli_fetch_row($result)){
        
                   
                    $first_name= $row[1];
                    $surname = $row[2];
                    $card_number = $row[6];
                    $id=$row[10]; 
                    $amount = $row[11];
                    $purpose = $row[12];
                    $trans_type = $row[16]; 
                    
                    if($trans_type == 'project'){
                        $purpose = $this->getProjectName($purpose);
                    }
                  
                 echo "<tr class=\"danger\" id=\"item_$id\"><td>$first_name". " $surname</td><td>$card_number</td><td>Ghc ".$this->format_to_two($amount)."</td><td>$purpose</td><td>$trans_type</td><td> <button class=\"buttonedit-trans text-info\" id=\"$trans_type-$id\" title=\"Edit\"><i class=\"fas fa-edit pr-2\"></i></button><button class=\"buttondel-trans text-danger\" id=\"$trans_type-$id\" title=\"Delete\"><i class=\"fas fa-trash pl-2\"></button></i></td></tr>";
    
}
echo "</table>";

    }

}
    
//Function to get the grace baptist church members to the dashboard
    function getDetailedResponse($card_number){
        $selectQuery = "SELECT * FROM `members` RIGHT JOIN `donations` ON `members`.card_number = donations.user_id WHERE (members.card_number = '$card_number' AND del = 0) UNION ALL SELECT * FROM `members` RIGHT JOIN `tithe_contribution` ON members.card_number = tithe_contribution.card_number WHERE (members.card_number = '$card_number' AND del= 0) UNION ALL SELECT * FROM `members`  RIGHT JOIN `dues_contribution`   ON members.card_number = dues_contribution.user_id WHERE (members.card_number = '$card_number' AND del=0) ORDER BY date_paid DESC LIMIT 1";
        //$selectQuery="SELECT * FROM members LEFT JOIN tithe_contribution ON members.card_number = tithe_contribution.card_number WHERE members.card_number = '$card_number' ORDER BY tithe_contribution.date_paid DESC LIMIT 1";
        $result = mysqli_query($this->connection, $selectQuery);
        $num_rows = mysqli_num_rows($result);
    
     
     if($num_rows >= 1){
            $row = mysqli_fetch_row($result);
            $id = $row[0];
            $first_name = $row[1]; //getting the first name from the database
            $surname = $row[2];  //getting the surname values from the database
            $phone = $row[3]; //Getting the date of birth of the employee
            $email = $row[4];  //Getting the address values from the database
            $zone = $row[5]; //Getting the region id corresponding to this table
            $card_number = $row[6];
            $date_registered = $row[7]; //Getting the telephone number 
            $dob=$row[8];
            $fship=$row[9];
            $id2 = $row[10];   //Getting the email Address of the employee

            //secondphone = $row[9];
            $amount = $row[11]; // Getting the time register this user
            $month_paid = $row[12];  //Getting the image of the users
            $description = $row[13]; //Getting the profession id for the employee
            $card_number_sec= $row[14];
            $date_paid = $row[15];

          
            $completename = $fullname = $first_name . " " . $surname;
            $new_date = date("j-m-Y", strtotime($date_paid));

            echo "<div class=\"col-xl-12 col-md-12 mb-12\">
            <div class=\"card border-left-primary shadow h-100 py-2\">
              <div class=\"card-body\">
                <div class=\"row no-gutters align-items-center\">
                  <div class=\"col mr-2\">
                  
                    <div class=\"h5 font-weight-bold text-primary text-uppercase mb-0\">$fullname</div>
                    <div class=\"text-ms mb-0 font-weight-bold text-gray-800\">$phone @ $email</div>
                    <div class=\"text-ms mb-0 font-weight-bold text-gray-800\">#$card_number Zone: $zone</div>
                    <div class=\"text-ms mb-0 font-weight-bold text-danger\"><span class=\"text-xs text-gray-800\">Last Payment:</span> Ghc $amount, <span class=\"text-xs text-gray-800\"> on</span> $new_date, <span class=\"text-xs text-gray-800\">for $month_paid</span> </div>

                  </div>
                  <div class=\"col-auto\">
                    <i class=\"fas fa-calendar fas fa-user fws text-gray-300\"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>";
     }else{ 
        echo "<div class=\"col-xl-12 col-md-12 mb-12\">
        <div class=\"card border-left-primary shadow h-100 py-2\">
          <div class=\"card-body\">
            <div class=\"row no-gutters align-items-center\">
              <div class=\"col mr-2\">
              
                <div class=\"h5 font-weight-bold text-danger text-uppercase mb-0\">User Transactions are empty</div>
               
              </div>
              <div class=\"col-auto\">
                <i class=\"fas fa-calendar fas fa-user fws text-gray-300\"></i>
              </div>
            </div>
          </div>
        </div>
      </div>";
     }
    }

    function getProjectDetails(){
        $selectQuery= "SELECT project_names.id, project_names.project_name, project_names.target, SUM(projects.amount),  project_names.status  FROM `project_names` INNER JOIN `projects` ON `project_names`.`id` = `projects`.`project_id` WHERE `projects`.`del`= 0 GROUP BY project_names.id ORDER BY `projects`.`date_paid` DESC LIMIT 20";
        $result = mysqli_query($this->connection, $selectQuery);
        $num_rows = mysqli_num_rows($result);

        if(!$result || $num_rows <=0){
echo "<div class='btn btn-info'>You don't Have any Projects</div>";
        }else{
            echo "<table class=\"table table-hover\"><thead class=\"thead-dark\"><tr><th scope=\"col\">Project Name</th><th scope=\"col\">Target</th><th scope=\"col\">Amount Obtained</th><th scope=\"col\">Variance</th><th scope=\"col\">Status</th></tr></thead><tbody>";
            while($row = mysqli_fetch_row($result)){

           
            $id = $row[0];
            $project_name = $row[1];
            $status = $row[4]; 
            $target = $row[2];
            $amount = $row[3];

            $posNeg = $amount - $target;
if($posNeg <= -1){
$post ="<i class=\"text-danger\">Ghc " .$this->format_to_two($posNeg). "</i>";
}elseif($posNeg == 0){
    $post ="<i class=\"text-grey\">Ghc " .$this->format_to_two($posNeg). "</i>";
}else{
    $post ="<i class=\"text-success\">Ghc " .$this->format_to_two($posNeg). "</i>";
}

          
            if($status =="enabled"){
                $status = "<button class=\"btn btn-success disable_button\" title=\"disabled-$id\" id=\"disable-$id\">Disable</button>";
                echo "<tr class=\"danger\"><td>$project_name</td><td>Ghc " .$this->format_to_two($target). "</td><td>Ghc " .$this->format_to_two($amount). "</td><td>$post</td><td>$status</td></tr>";

           
            }elseif($status == "disabled"){
                $status = "<button class=\"btn btn-disabled enable_button\" id=\"enable-$id\">Enable</button>";
                echo "<tr><td>$project_name</td><td>Ghc " .$this->format_to_two($target). "</td><td>Ghc " .$this->format_to_two($amount). "</td><td>$post</td><td>$status</td></tr>";

            }
 }

 echo "</tbody></table>";
        }
}

/**
 * Undocumented function
 *This functio automatically generates and add option for donation on the transaction page
 * @return void
 */
function getProjectDonation(){
    $selectQuery="SELECT * FROM project_names WHERE status='enabled'";
       $results = mysqli_query($this->connection, $selectQuery);
       if(mysqli_num_rows($results) < 1){
           echo "";
       }else{
           echo "<div class=\"input-group mb-lg-3\"><input type=\"text\" class=\"form-control form-control-user\" name=\"search_project\" id=\"search_project\" autocomplete=\"off\" placeholder=\"Search Projects...\"></div>";
       }
}
      
    //function for checking the existance of a particular phone number registered on the system
    function checkPhoneNumber($phone){
       $selectQuery="SELECT phone FROM members WHERE phone='$phone'";
       $results = mysqli_query($this->connection, $selectQuery);
       if(mysqli_num_rows($results)== 1){
           return true;
       }
    }

        //function for checking the existance of a particular phone number registered on the system
        function checkCardNumber($card_number){
            $selectQuery="SELECT card_number FROM members WHERE card_number='$card_number'";
            $results = mysqli_query($this->connection, $selectQuery);
            if(mysqli_num_rows($results)== 1){
                return true;
            }
         }

         function checkBenefiters($nameSequence){
            $selectQuery="SELECT `benefiter_name` FROM benefiters WHERE `benefiter_name`='$nameSequence'";
            $results = mysqli_query($this->connection, $selectQuery);
            if(mysqli_num_rows($results)== 1){
                return true;
            }
         }
         function checkTithePayment($card_number, $month){
            $selectQuery="SELECT * FROM `tithe_contribution` WHERE (card_number ='$card_number' AND month ='$month') AND (del=0)";
            $results = mysqli_query($this->connection, $selectQuery);
            if(mysqli_num_rows($results)== 1){
                return true;
            }
         }


         function checkDuesPayment($card_number, $benefiter){
            $selectQuery="SELECT * FROM `dues_contribution` WHERE (user_id ='$card_number' AND benefiter = '$benefiter') AND (del = 0)";
            $results = mysqli_query($this->connection, $selectQuery);
            if(mysqli_num_rows($results)== 1){
                return true;
            }
         }

         function checkFellowship($nameSequence){
            $selectQuery="SELECT `fship_name` FROM fellowship WHERE `fship_name`='$nameSequence'";
            $results = mysqli_query($this->connection, $selectQuery);
            if(mysqli_num_rows($results)== 1){
                return true;
            }
         }

         function checkProject($nameSequence){
            $selectQuery="SELECT `project_name` FROM project_names WHERE `project_name`='$nameSequence'";
            $results = mysqli_query($this->connection, $selectQuery);
            if(!$results ||mysqli_num_rows($results)== 1){
                return true;
            }
         }
    

         function updateMembers($first_name, $last_name, $phone, $email, $zone, $card_number, $dob, $fship){
             $updateQuery = "UPDATE `members` SET `first_name`='$first_name',`last_name`='$last_name',`phone`='$phone',`email_address`='$email',`zone`='$zone',`dob`='$dob',`fship`='$fship' WHERE `card_number` = '$card_number'";
             $results = mysqli_query($this->connection, $updateQuery);
             if(!$results){
                 $message = "<div class='btn btn-danger'>Record not Updated</div>";
                
            } else {
                $message = "<div class='btn btn-success'>Member Updated</div>";
            
             }
                echo $message;
         }

         function updateTransaction($card_number, $id, $amount, $purpose, $type){
            if($type == 'dues'){
                $updateQuery = "UPDATE `dues_contribution` SET `amount_paid`='$amount', `benefiter`='$purpose' WHERE `id`='$id'";
            }elseif($type == 'project'){
                $updateQuery = "UPDATE `projects` SET `amount`='$amount', `project_id`='$purpose' WHERE `id`='$id'";
            }elseif($type == 'tithe'){
                $updateQuery = "UPDATE `tithe_contribution` SET `amount_paid`= '$amount', `month`='$purpose'  WHERE `id` = '$id'";
            }elseif($type == 'donation'){
                $updateQuery = "UPDATE `donations` SET `amount_paid`='$amount', `donation`= '$purpose' WHERE id = '$id'";
            }
            $results = mysqli_query($this->connection, $updateQuery);
            if(!$results){
                $message = "<div class='btn btn-danger'>Transaction Not Updated</div>";
               
           } else {
               $message = "<div class='btn btn-success'>Transaction Updated</div>";
           
            }
               echo $message;

         }

         function disableProject($id){
            $updateQuery = "UPDATE `project_names` SET `status`='disabled' WHERE id='$id'";
            $results = mysqli_query($this->connection, $updateQuery);
            if(!$results){
                $message = "<div class='btn btn-danger'>Project not Deactivated</div>";
               
           } else {
               $message = "<div class='btn btn-success'>Project Deactivated</div>";
           
            }
               echo $message;
         }

         function enableProject($id){
            $updateQuery = "UPDATE `project_names` SET `status`='enabled' WHERE id='$id'";
            $results = mysqli_query($this->connection, $updateQuery);
            if(!$results){
                $message = "<div class='btn btn-danger'>Project not Activated</div>";
               
           } else {
               $message = "<div class='btn btn-success'>Project Activated</div>";
           
            }
               echo $message;
         }

         function delTransaction($trans_id, $type){
            if($type == 'dues'){
                $updateQuery = "UPDATE `dues_contribution` SET `del`=1 WHERE `id`='$trans_id'";
            }elseif($type == 'project'){
                $updateQuery = "UPDATE `projects` SET `del`=1 WHERE `id`='$trans_id'";
            }elseif($type == 'tithe'){
                $updateQuery = "UPDATE `tithe_contribution` SET `del`=1 WHERE `id`='$trans_id'";
            }elseif($type == 'donation'){
                $updateQuery = "UPDATE `donations` SET `del`=1 WHERE `id`='$trans_id'";
            } 

            $results = mysqli_query($this->connection, $updateQuery);
            if(!$results){
                $message = "<div class='btn btn-danger'>Transaction Not Deleted</div>";
               
           } else {
               $message = "<div class='btn btn-success'>Transaction Deleted</div>";
           
            }
               echo $message;
         }

         //Function for submitting the Tithes
    function submitTithe($card_number, $amount, $month, $description){
        $message= $sms = "";
  $insertQuery = "INSERT INTO `tithe_contribution`(`id`, `amount_paid`, `month`, `description`, `card_number`, `date_paid`, `type`, `del`) VALUES (null, '$amount', '$month', '$description', '$card_number', null, 'tithe', 0)";
  $results = mysqli_query($this->connection, $insertQuery);
  if(!$results)
  {
    $message = "<div class='btn btn-danger'>Tithe not Recieved</div>";

  }else{
    $message = "<div class='btn btn-success'>Tithe Received. Thank you</div>";

    $messageSMS = "Your tithe contribution of Ghc".$amount." for ".$month." have been recieved.".$description.". Thank you";
    $sms= $this->sendSMS($messageSMS, $card_number, "GBC Tithe");

  }
  echo $sms." and ".$message;
}

    //Function for submitting the dues
    function submitDues($card_number, $amount, $benefiter, $description){
        $message= $sms = "";


        $insertQuery = "INSERT INTO `dues_contribution`(`id`, `amount_paid`, `benefiter`, `description`, `user_id`,  `date_paid`, `type`, `del`) VALUES (null,'$amount', '$benefiter', '$description', '$card_number',  null, 'dues', 0)";
        $results = mysqli_query($this->connection, $insertQuery);
if(!$results)
  {
    $message = "<div class='btn btn-danger'>Dues not Recieved</div>";

  }else{
    $message = "<div class='btn btn-success'>Dues Received for ".$benefiter.". Thank you</div>";

    $messageSMS = "Your Dues contribution of Ghc".$amount." for ".$benefiter." have been recieved.".$description.". Thank you";
    $sms= $this->sendSMS($messageSMS, $card_number, "GBC Dues");

  }
  echo $sms." and ".$message;
}

          //function for submitting Other Donations
function submitDonations($card_number, $amount, $description){
$message= $sms = "";


    $insertQuery = "INSERT INTO `donations` (`id`, `amount_paid`, `donation`, `description`, `user_id`,  `date_paid`, `type`, `del`) VALUES (null, '$amount', '', '$description', '$card_number',  null, 'donation', 0)";
    $results = mysqli_query($this->connection, $insertQuery);
    
    if(!$results)
      {
        $message = "<div class='btn btn-danger'>Donation not Recieved</div>";
    
      }else{
        $message = "<div class='btn btn-success'>Donation Received. Thank you</div>";
    
        $messageSMS = "Your Donation contribution of Ghc".$amount." have been recieved.".$description.". Thank you";
        $sms= $this->sendSMS($messageSMS, $card_number, "GBC Donates");
    
      }
      echo $sms." and ".$message;
    }

    //Function for submitting the Benefiters
    function submitBenefiters($benefiter_name){
            $insertQuery = "INSERT INTO `benefiters`(`id`, `benefiter_name`, `date_added`) VALUES (null, '$benefiter_name', null)";
            mysqli_query($this->connection, $insertQuery);
            return true;
    }



            //Function for submitting the Benefiters
    function submitFellowship($fship_name){
                $insertQuery = "INSERT INTO `fellowship`(`id`, `fship_name`, `date_added`) VALUES (null, '$fship_name', null)";
                mysqli_query($this->connection, $insertQuery);
                return true;
    }


    
          //Function for submitting the Benefiters
    function submitProjectName($project_name, $target){
      $insertQuery =   "INSERT INTO `project_names`(`id`, `project_name`, `status`, `target`, `date_added`) VALUES (null, '$project_name', 'enabled', '$target',  null)";
            //"INSERT INTO `benefiters`(`id`, `benefiter_name`, `date_added`) VALUES (null, '$benefiter_name', null)";
            $results = mysqli_query($this->connection, $insertQuery);
            if (!$results) {
                return "<div class='btn btn-danger'>Project not added</div>";
            }else{
                return "<div class='btn btn-success'>New Project added Success</div>";
             }
           
    }

           //Function for submitting the Benefiters
           function submitProjectGroupVal($project_name, $group, $amount, $disc){
            $insertQuery =   "INSERT INTO `projects`(`id`, `amount`, `project_id`, `description`, `user_id`, `date_paid`, `type`, `del`) VALUES (null, '$amount', '$project_name', '$disc', '$group', null, 'project', 0)";
                  //"INSERT INTO `benefiters`(`id`, `benefiter_name`, `date_added`) VALUES (null, '$benefiter_name', null)";
                  $results = mysqli_query($this->connection, $insertQuery);
                  if (!$results) {
                      return "<div class='btn btn-danger'>Value not added</div>";
                  }else{
                      return "<div class='btn btn-success'>New Group value Received</div>";
                   }
                 
          }

    // Function to save new users or entrants
    function registerMembers($firstName, $surname, $phone, $email, $zone, $cardNumber, $dob, $fship) {
        $insertQuery = "INSERT INTO `members`(`id`, `first_name`, `last_name`, `phone`, `email_address`, `zone`, `card_number`, `dob`, `fship`) VALUES (null, '$firstName', '$surname', '$phone', '$email', '$zone', '$cardNumber', '$dob', '$fship')";
        $updateQuery = "UPDATE `potential_card_numbers` SET `usages`='1' WHERE `potential_numbers`='$cardNumber'";
        mysqli_query($this->connection, $insertQuery);
        mysqli_query($this->connection, $updateQuery);
         return true;
        
    }

    function getUrlContent($url) {
        fopen("cookies.txt", "w");
        $parts = parse_url($url);
        $host = $parts['host'];
        $ch = curl_init();
        $header = array('GET /1575051 HTTP/1.1',
            "Host: {$host}",
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language:en-US,en;q=0.8',
            'Cache-Control:max-age=0',
            'Connection:keep-alive',
            'Host:adfoc.us',
            'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36',
        );
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    
        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
   

/**
 * 
 *The sms is call to duety here 
 *
 *
 */
    function sendSMS($message, $userId, $messageId){
        $selectQuery="SELECT phone, first_name FROM members WHERE card_number='$userId'";
            $results = mysqli_query($this->connection, $selectQuery);
            if (!$results || (mysqli_num_rows($results) < 1)) {
                return "<div class='btn btn-danger'>Phone Number not found</div>";
            }else{
                $row = mysqli_fetch_assoc($results);   
                 $phone = $row['phone'];
                 $first_name= $row['first_name'];
                 //encode the message
                $msg = urlencode("Hello ".$first_name.", ".$message);
                $key ="Y2h9sS9Gsu9MviK1jMAVYjr9b";  // Remember to put your own API Key here";

            //prepare your url
            $url = "https://apps.mnotify.net/smsapi?"
            . "key=$key"
            . "&to=$phone"
            . "&msg=$msg"
            . "&sender_id=$messageId";
          //  . "&date_time=$date_time";
$responseUrl = $this->getUrlContent($url) ;//Response contains the response from mNotify
$response = json_decode($responseUrl);
$codeJson = $response->code;
if($codeJson =='1000'){
    return "<div class='btn btn-success'>SMS Delivered</div>";
}elseif($codeJson =='1003'){
    return "<div class='btn btn-danger'>Insufficient SMS balance</div>";
}else{
    return "<div class='btn btn-danger'>Error in sending SMS. Contact your provider $codeJson</div>";
    }    
            
    }
}

}

/* Create database connection */
$database = new MySQLDB;
?>
