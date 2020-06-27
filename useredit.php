<?php
/**
 * UserEdit.php
 *
 * This page is for users to edit their account information
 * such as their password, email address, etc. Their
 * usernames can not be edited. When changing their
 * password, they must first confirm their current password.

 */
include("include/classes/session.php");
?>

<html>
    <head>
    <meta charset="UTF-8">
    <!--Android Version Chrome, opera and OS-->
    <meta name="theme-color" content="#ff5722">
    <!--For windows Phones-->
    <meta name="msapplication-navbutton-color" content="#ff5722">
    <!--For iOS-->
    <meta name="apple-mobile-web-app-status-bar-style" content="#ff5722">

    <title><?php echo $session->username ?></title> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="include/java/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
    <script type="text/javascript" src="include/java/moment.js"></script>
    <script type="text/javascript" src="include/java/moment.min.js"></script>
    <script type="text/javascript" src="include/java/prefixfree.min.js"></script>


    <link href="include/styles/w3.css" rel="stylesheet" type="text/css">
    <link href="include/styles/attendant-styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
    <link rel="icon" type="image/icon" href="images/frimpslogo.png"/>
    </head>
    <body>

        <?php
        
       

        /**
         * User has submitted form without errors and user's
         * account has been edited successfully.
         */
        function edit_form() {
            
             global $form, $session;
            ?>

            <div style="background: #FFF" class="w3-container login-container">

                <?php
                if ($form->num_errors > 0) {
                    echo "<td><font size=\"2\" color=\"#ff0000\">" . $form->num_errors . " error(s) found</font></td>";
                }
                ?>
                <form action="process.php" class="w3-small w3-card-2 w3-padding-left signin-form" method="POST">

                    <div class=" top-headings w3-center" >User Account Edit : <?php echo $session->username; ?></div>

                    <div class="w3-group">

                        <input style="background: #FFF ;color: #000" class="w3-input" type="password" name="curpass" maxlength="30" value="<?php echo $form->value("curpass"); ?>">
                        <label class="w3-label">Current Password: <?php echo $form->error("curpass"); ?></label>
                    </div>



                    <div class="w3-group">
                        <input style="background: #FFF; color:#000" class="w3-input" type="password" name="newpass" maxlength="30" value="<?php echo $form->value("newpass"); ?>">
                        <label class="w3-label">New Password:  <?php echo $form->error("newpass"); ?></label>
                    </div>


                    <div class="w3-group">
                        <input style="background: #FFF; color:#000" class="w3-input" type="text" name="email" maxlength="50" value="<?php
                        if ($form->value("email") == "") {
                            echo $session->userinfo['email'];
                        } else {
                            echo $form->value("email");
                        }
                        ?>">
                        <label class="w3-label">Email: <?php echo $form->error("email"); ?></label>
                    </div>


                    <input type="hidden" name="subedit" value="1">
                    <input class="w3-deep-orange w3-btn" style="padding: 7px;" type="submit" value="Edit Account">

                </form>
            </div>
      
        <?php
    }

    if (isset($_SESSION['useredit'])) {
        unset($_SESSION['useredit']);
        
        if (($session->logged_in) && ($session->isMember())) {

            require __DIR__ . '/headMember.php';

           echo "<h1>User Account Edit Success!</h1>";
        echo "<p><b>$session->username</b>, your account has been successfully updated. "
        . "<a href=\"index.php\">Main</a>.</p>";
            
            
        }elseif (($session->logged_in) && ($session->isAgent())) {
            
             require __DIR__ . '/headAgent.php';
             
             echo "<h1>User Account Edit Success!</h1>";
        echo "<p><b>$session->username</b>, your account has been successfully updated. "
        . "<a href=\"index.php\">Main</a>.</p>";
        
        }elseif (($session->logged_in) && ($session->isMaster())) {
            
        require __DIR__ . '/headMaster.php';
        
        echo "<h1>User Account Edit Success!</h1>";
        echo "<p><b>$session->username</b>, your account has been successfully updated. "
        . "<a href=\"index.php\">Main</a>.</p>";
        
        }elseif (($session->logged_in) && ($session->isAdmin())) {
            
             require __DIR__ . '/headAdmin.php';
             
            echo "<h1>User Account Edit Success!</h1>";
        echo "<p><b>$session->username</b>, your account has been successfully updated. "
        . "<a href=\"index.php\">Main</a>.</p>";
        }
    
    
    } else {
        ?>

        <?php
        /**
         * If user is not logged in, then do not display anything.
         * If user is logged in, then display the form to edit
         * account information, with the current email address
         * already in the field.
         */
        if (($session->logged_in) && ($session->isMember())) {

            require __DIR__ . '/headMember.php';

            edit_form();
            
            
        }elseif (($session->logged_in) && ($session->isAgent())) {
            
             require __DIR__ . '/headAgent.php';
             
             edit_form();
        
        }elseif (($session->logged_in) && ($session->isMaster())) {
            
        require __DIR__ . '/headMaster.php';
        edit_form();
        
        }elseif (($session->logged_in) && ($session->isAdmin())) {
            
             require __DIR__ . '/headAdmin.php';
             edit_form();
        }
    }
    ?>
   <script>
            function w3_open() {
                document.getElementsByClassName("w3-sidenav")[0].style.display = "block";
            }
            function w3_close() {
                document.getElementsByClassName("w3-sidenav")[0].style.display = "none";
            }
       
        </script>
        
        <?php
 require __DIR__ . '/gen_footer.php';?>
 
</body>
</html>
