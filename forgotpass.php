<?php
/**
 * ForgotPass.php
 *
 * This page is for those users who have forgotten their
 * password and want to have a new password generated for
 * them and sent to the email address attached to their
 * account in the database. The new password is not
 * displayed on the website for security purposes.
 *
 * Note: If your server is not properly setup to send
 * mail, then this page is essentially useless and it
 * would be better to not even link to this page from
 * your website.
 
 */
include("include/classes/session.php");
?>
<!DOCTYPE html>
<html style="/*background:url('images/fslide1.jpg') no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    top:0;
    bottom: 0;
    left: 0;
    right: 0;*/
      "


      >
    <head>
        <meta charset="UTF-8">
        <!--Android Version Chrome, opera and OS-->
        <meta name="theme-color" content="#ff5722">
        <!--For windows Phones-->
        <meta name="msapplication-navbutton-color" content="#ff5722">
        <!--For iOS-->
        <meta name="apple-mobile-web-app-status-bar-style" content="#ff5722">

        <title>Reset Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script type="text/javascript" src="include/java/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
        
        
        <!--Alert stuff starts from this point-->
        <script type="text/javascript"  src="include/java/jquery_alert_files/jQuery.js"></script>
        <script type="text/javascript"  src="include/java/jquery_alert_files/jquery.ui.draggable.js"></script>
        <script type="text/javascript"  src="include/java/jquery_alert_files/jquery.alerts.js"></script>
        <link type="text/css"  href="include/java/jquery_alert_files/jquery.alerts.css" rel="stylesheet" media="screen">
       
        
        <link href="include/styles/w3.css" rel="stylesheet" type="text/css">
        <link href="include/styles/attendant-styles.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> 
        <link rel="icon" type="image/icon" href="images/frimpslogo.png"/>
    </head>

    <body class="index">

<?php
/**
 * Forgot Password form has been submitted and no errors
 * were found with the form (the username is in the database)
 */
if(isset($_SESSION['forgotpass'])){
   /**
    * New password was generated for user and sent to user's
    * email address.
    */
   if($_SESSION['forgotpass']){
      echo "<h1>New Password Generated</h1>";
      echo "<p>Your new password has been generated "
          ."and sent to the email <br>associated with your account. "
          ."<a href=\"index.php\">Main</a>.</p>";
      
      }
   /**
    * Email could not be sent, therefore password was not
    * edited in the database.
    */
   else{
      echo "<h1>New Password Failure</h1>";
      echo "<p>There was an error sending you the "
          ."email with the new password,<br> so your password has not been changed. "
          ."<a href=\"index.php\">Main</a>.</p>";
   }
       
   unset($_SESSION['forgotpass']);
}
else{

/**
 * Forgot password form is displayed, if error found
 * it is displayed.
 */
?>
  <div class="login-container w3-container">

<?php echo $form->error("user"); ?>
<form action="process.php" class="w3-small signin-form" method="POST">
    <div class="company-name">Reset Password </div><span class="w3-small" style="color:whitesmoke;">A new password will be generated for you and sent to the email address
associated with your account, all you have to do is enter your
username</span>

    <div class="w3-group">
<input type="text" name="user" maxlength="30" value="<?php echo $form->value("user");  ?>"  class="w3-input" required="true">
<label class="w3-label">Username <?php echo $form->error("user"); ?></label>
    </div>
<input type="hidden" name="subforgot" value="1">
<input type="submit" class="w3-btn w3-deep-orange" value="Get New Password">

<a href="index" style="color:navy; text-decoration: none;">Login</a>
</form>

<?php
}
?>
  </div>
</body>
</html>
