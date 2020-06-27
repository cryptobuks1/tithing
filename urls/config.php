<?php
include '../classes/session.php';

if (($session->logged_in) && ($database->isPermissionAllowed($session->username, 'config'))){

 
  function generate_numbers($start, $count, $digits){ 
    
    global $database;
    //$results= array();
    for($n = $start; $n<$start + $count; $n++){
        $results= str_pad($n, $digits, "0", STR_PAD_LEFT); 

        $query="INSERT INTO potential_card_numbers (id, potential_numbers) VALUES (NULL, '$results')";
        $re = mysqli_query($database->connection, $query);
    echo $results."<br>";
      }
    
}

function sendSMS($message, $receipient, $id_header){
  //defining the parameters
$key = "Y2h9sS9Gsu9MviK1jMAVYjr9b";  // Remember to put your own API Key here
$to = "$receipient";
$msg = "$message";
$sender_id = "$id_header"; //11 Characters maximum
//$date_time = "2017-05-02 00:59:00";

//encode the message
$msg = urlencode($msg);

//prepare your url
$url = "https://apps.mnotify.net/smsapi?"
            . "key=$key"
            . "&to=$to"
            . "&msg=$msg"
            . "&sender_id=$sender_id";
          //  . "&date_time=$date_time";
$response = file_get_contents($url) ;
echo $response;
//response contains the response from mNotify
}

$url= 'https://apps.mnotify.net/smsapi/balance?key=Y2h9sS9Gsu9MviK1jMAVYjr9b';
$sms = $database->getUrlContent($url)


?>


<div class="container-fluid">

<nav id="navhupgo" class="navbar navbar-expand justify-content-start navbar-red bg-red-white topbar mb-1 shadow">

    <div class="d-none d-sm-block h6 left">Config</div>

    <!-- Topbar Navbar -->
    <ul class="nav navbar-nav">


        <!-- Nav Item - Alerts -->


        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <button class="nav-btn dropdown-toggle"  id="messagesDropdown" role="button" data-toggle="modal" data-target="#access-control-modal" title="Access Control" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-secret fa-fw"></i> <span class=" text-xs">Access Control</span>

            </button>

        </li>

       

    

        <li id="messages"></li>

    </ul>

</nav>



<div class="card border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-6">
                <div class="p-3">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">

                 

    <h1 class="h3 mb-0 text-gray-500">Personnel Details</h1>

     <div>Available SMS</div><span class="btn btn-danger"><?php echo $sms ?> unit(s)</span>
  </div>
  <form id="personnel_form" class="user" autocomplete="off">


<div class="form-group">
    <input type="text" class="form-control form-control-user" name="full_name" id="full_name" placeholder="Full Name">
    </div>

<div class="form-group">
    <input type="text" class="form-control form-control-user" name="phone" id="phone" placeholder="Phone">
</div>

<div class="form-group">
    <input type="text" class="form-control form-control-user" name="username" id="username" autocomplete="off" placeholder="Username...">
</div>

<div class="form-group">
    <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password">
    </div>

<div class="form-group">
    <input type="password" class="form-control form-control-user" name="confirm_pass" id="confirm_pass" placeholder="Confirm Password">
</div>


     <button type="submit" name="submint" id="submit" class="btn btn-primary btn-user btn-block">Submit</button>




                    </form>

                </div>
            </div>



           

        </div>
    </div>
</div>

</div>

<?php 


//s$database->sendSMS("Thank you", "000010", "GBC Tithe");
//sendSMS("This message goes to My wife Jennifer. I really love you. Always remember that no matter what.", "0244941958", "GOIL ISSUE");
// for($m=0; $m<12; $m++){
//     if($m <=5){
//         echo '<option>'.date('F-Y', strtotime($m. 'month')).'</option>';

//     }else{
        
//         echo '<option>'.date('F-Y', strtotime(-$m. 'month')).'</option>';

//     }
 

//  }

//  $d = new DateTime('now');
//  $d->modify('first day of next month');
//  echo $d->format('F-Y')
//generate_numbers("1", "10000", "6");
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
