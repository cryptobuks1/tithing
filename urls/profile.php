<?php
include '../classes/session.php';

if (($session->logged_in) && ($database->isPermissionAllowed($session->username, 'profile'))){

?>


<div class="container-fluid">

<nav id="navhupgo" class="navbar navbar-expand justify-content-start navbar-red bg-red-white topbar mb-1 shadow">

    <div class="d-none d-sm-block h6 left">Profile</div>

    <!-- Topbar Navbar -->
    <ul class="nav navbar-nav">


        <!-- Nav Item - Alerts -->


      

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <button class="nav-btn dropdown-toggle" id="userDropdown" role="button" data-toggle="modal" data-target="#change-pass-modal" title="Change Password" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-edit fa-fw"></i><span class=" text-xs">Change Password</span>
                
            </button>

        </li>

        <li id="messages"></li>

    </ul>

</nav>



<div class="card border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-7">
                <div class="p-3">
      
  <?php
  
  global  $database, $session;
  $req_user = trim($_SESSION['username']);
  $req_user_info = $database->getUserInfo($req_user);
  
  /* Logged in user viewing own account */
if(strcmp($session->username,$req_user) == 0){
 echo "<div class=\"d-sm-flex align-items-center justify-content-between mb-4\">
 <h1 class=\"h3 mb-0 text-gray-500\">My Account</h1>
</div>";
}
/* Visitor not viewing own account */
else{
    echo "<div class=\"d-sm-flex align-items-center justify-content-between mb-4\">
    <h1 class=\"h3 mb-0 text-gray-500\">User Info</h1>
   </div>";}

echo "<table class=\"table table-dark\"><tr><th>Username:</th><td> ".$req_user_info['username']."</td></tr>";

echo "<tr><th>Name:</th><td> ".$req_user_info['first_name']."</td></tr>";
echo "<tr><th>Phone Number:</th><td> ".$req_user_info['phone']."</td></tr>";


  ?>
  
                </div>
            </div>



           

        </div>
    </div>
</div>

</div>

<?php 

?>
</div>



        </div>
<script>
$(document).ready(function(){

 // alertify.error("error");
        $("#submit").click(function(){
          submitMemberForm();//validateTitheInput();
          
          return false;
       });

       /**
        * xamppfiles/htdocs/tithing/modules/registerMembers.php on line 62

        * Function for submitting the tithe, dues and donation forms
        *
        * 
        */
       function submitMemberForm(){
   
        if($("#full_name").val() ===""){
    alertify.warning("Name can\'t be empty");
    $("#full_name").focus();
    return false;
}

if($("#username").val() == "" || $("#username").val().length < 5 ){
    alertify.warning("Username should be atleast 5 Characters");
    $("#username").focus();
    return false;
}


if($("#phone").val() ==="" || $("#phone").val().length < 10 || $("#phone").val().length >10 ){
    alertify.warning("Phone number should be 10 Digits");
    $("#phone").focus();
    return false;
}

if($("#password").val() ===""){
    alertify.warning("Password empty");
    $("#password").focus();
    return false;
}


if($("#confirm_pass").val() === ""){
    alertify.warning("Confirm Password");
    $("#confirm_pass").focus();
    return false;
}

if($("#password").val() !==  $("#confirm_pass").val()){
    alertify.warning("Password don't match!!!");
    $("#password").focus();
    return false;
}




           var data = $('form#personnel_form').serialize();
// alert(data);
// return false;

           $.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: data,
              success: function(response){
                  alertify.message(response);
              },
              error: function(){

                  alertify.error("error");
              }
           });
           //alert(data);
       }



      
if($("#search_code").val() ===""){
    alertify.warning("Card Number empty");
    $("#search_code").focus();
    return false;
}

if($("#month").val() == "\"Month to be Paid\""){
    alertify.warning("Month not Selected");
    //$("#month").focus();
    return false;
}


if($("#amount").val() ===""){
    alertify.warning("Amount is empty");
    $("#amount").focus();
    return false;
}

if($("#description").val() ===""){
    alertify.warning("Message is empty");
    $("#description").focus();
    return false;
}


if($("#search_benefiter").val() === ""){
    alertify.warning("Benefiter not Selected");
    $("#search_benefiter").focus();
    return false;
}

if($("#search_project").val() === ""){
    alertify.warning("Project Can't be empty");
    $("#search_project").focus();
    return false;
}



//        function clearInputs(){
//            $('#search_code').val("");
//            $('#amount').val("");
//            $('#month').val("");
//            $('#search_benefiter').val("");
//            $("#donation_radio").is(":checked")

     //  }
       });
</script>
<?php 

}else{ 
    ?>
    
    
    
   <!-- Begin Page Content -->
        <div class="container-fluid">
           <!-- 404 Error Text -->
          <div class="text-center">
            <div class="error mx-auto" data-text="404">404</div>
            <p class="lead text-gray-800 mb-5">Page Not Found</p>
            <p class="text-gray-500 mb-0">It looks like you are accessing page a page without permission...</p>
            <a ng-href="home">&larr; Back to Dashboard</a>
          </div>

        </div>
<?php


}

?>

        <!-- /.container-fluid -->
        
        
  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-de
