<?php
include 'classes/session.php';


if (($session->logged_in)){
    global $database;

?>
<!DOCTYPE html>
<html lang="en" ng-app="ssd">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" type="image/icon" href="img/gracelogo.png"/>
  <title>Grace</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css"/>
  <link href="vendor/bootstrap/datepicker.css" rel="stylesheet" type="text/css"/>
  <link type="text/css"  href="vendor/alertifyjs/css/alertify.css" rel="stylesheet" media="screen">
  <link type="text/css"  href="vendor/alertifyjs/css/themes/default.css" rel="stylesheet" media="screen">
  <!-- <link type="text/css" href="vendor/angular/angularPrint.css" rel="stylesheet" media="print"> -->
 
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">

</head>

<body id="page-top" >

  <!-- Page Wrapper -->
  <div  id="wrapper">

    <!-- Sidebar -->
    <div ng-include='"templates/sidebar-admin.php"'></div>
        <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="noprint navbar navbar-expand navbar-light bg-white topbar mb-1 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search 
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" ng-model="search" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>-->
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                         <!-- <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<li class="nav-item dropdown no-arrow d-sm-none">
                <i class="fas fa-search fa-fw"></i>
              </a>
              Dropdown - Messages 
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>-->

           
            <!-- Nav Item - Messages -->
            <div class="topbar-divider d-none d-sm-block"></div>

            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle"href="#" data-toggle="modal" data-target="#logoutModal" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-power-off fa-fw"></i>
               Logout
              </a>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            
          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div ng-view></div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class=" noprint sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Powered by Hupgo Systems <?php echo date("Y"); ?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <div id="add-member-modal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add New Member</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
                    <form autocomplete="off" id="memberForm" name="members" role="form">
                        <div class="modal-body">

                        <div class="form-group">
                                 
                                 <input type="text" name="card_number" autocomplete="off" id="search_card" class="form-control" placeholder="Card Number">
                            </div>

                            <div class="form-group">
                                                                 
                                <input type="text" name="first_name" autocomplete="off" id="first_name" class="form-control" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                 
                                 <input type="text" name="surname_name" id="surname" class="form-control" placeholder="Surname">
                            </div>
                            <div class="form-group">
                               
                                 <input type="tel" name="phone_number" id="phone" class="form-control"  maxlength="10" placeholder="Phone Number">
                            </div>
                            <div class="form-group">
                                 
                                 <input type="text" name="email_address" id="email" class="form-control" placeholder="Email">
                            </div>

                            <div class="form-group">
                               
                                 <input type="text" name="dob" id="dob" class="form-control" data-toggle="datepicker" placeholder="Date of Birth">
                            </div>


                            <div class="form-group">
                               
                                 <input type="text" name="zone" id="zone" class="form-control" placeholder="Zone">
                            </div>


                            <div class="input-group">
                               
                                 <input type="text" name="fship" id="fship" class="form-control" placeholder="F'Ship">
                                 <div class="input-group-append" id="add_fship">
                                   <button class="btn btn-danger" type="button"><i class="fas fa-plus fa-sm"></i></button>
                                </div>
                              
                            </div>

                            
                        </div>
                        <div class="modal-footer">
                            <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" id="member_submit" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>


      <!-- Edit Member Modal -->
      <div id="edit-member-modal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Member</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
                    <form autocomplete="off" id="editMemberForm" name="members" role="form">
                            <div class="modal-body">

                            <div class="form-group" ondblclick="document.getElementById('edit_search_card').disabled=false;">                                 
                                 <input type="text" name="edit_card_number" autocomplete="off" id="edit_search_card" class="form-control" placeholder="Card Number">
                            </div>

                            <div class="form-group">                                                 
                                <input type="text" name="edit_first_name" autocomplete="off" id="edit_first_name" class="form-control" placeholder="First Name">
                            </div>

                            <div class="form-group">                                 
                                 <input type="text" name="edit_surname_name" id="edit_surname" class="form-control" placeholder="Surname">
                            </div>

                            <div class="form-group">                               
                                 <input type="tel" name="edit_phone_number" id="edit_phone" class="form-control"  maxlength="10" placeholder="Phone Number">
                            </div>

                            <div class="form-group">                                 
                                 <input type="text" name="edit_email" id="edit_email" class="form-control" placeholder="Email">
                            </div>

                            <div class="form-group">                               
                                 <input type="text" name="edit_dob" id="edit_dob" class="form-control" data-toggle="datepicker" placeholder="Date of Birth">
                            </div>

                            <div class="form-group">                               
                                 <input type="text" name="edit_zone" id="edit_zone" class="form-control" placeholder="Zone">
                            </div>


                            <div class="input-group">                               
                                 <input type="text" name="edit_fship" id="edit_fship" class="form-control" placeholder="F'Ship">
                                 <div class="input-group-append" id="add_fship">
                                   <button class="btn btn-danger" type="button"><i class="fas fa-plus fa-sm"></i></button>
                                </div>                              
                            </div>

                            
                        </div>
                        <div class="modal-footer">
                            <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" id="edit_member_submit" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- Access Control Modal -->
<div id="access-control-modal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Access Control</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
                    <form autocomplete="off" id="accessControl" name="accessControl" role="form">
                            <div class="modal-body">

                           
                            <div class="form-group">                                                 
                                <input type="text" name="username" id="username" class="form-control" placeholder="Search Username...">
                            </div>
<?php
$database->getPermissionCheck();
             ?>               
                        </div>
                        <div class="modal-footer">
                            <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" id="access_control_submit" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- Change Password Modal -->
<div id="change-pass-modal" class="modal fade" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                   <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
                    <form autocomplete="off" id="changePassword" name="password_change" role="form">
                            <div class="modal-body">

                            <div class="form-group">                                                 
                                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Current Password">
                            </div>

                            <div class="form-group">                                 
                                 <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password">
                            </div>

                            <div class="form-group">                               
                                 <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control"  maxlength="10" placeholder="Confirm New Password">
                            </div>



                            
                        </div>
                        <div class="modal-footer">
                            <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" id="change_password_submit" value="Change">
                        </div>
                    </form>
                </div>
            </div>
        </div>


  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" id="logout" href="process">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
 
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/bootstrap/dist/bootstrap3-typeahead.min.js"></script>
  <script src="vendor/bootstrap/datepicker.js"></script>
 


  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="vendor/alertifyjs/alertify.js"></script>
  
  
        
       
  <!-- Angular plugin for the project -->
  <script src="vendor/angular/angular.min.js"></script>
  
  <script src="vendor/angular/angular-route.min.js"></script>
  <!-- <script src="vendor/angular/angularPrint.js"></script> -->

   <!-- Our Website Javascripts -->
   <script src="js/main.js"></script>
   

   <style>
   
   .datepicker{
    z-index: 1100 !important;
}
   </style>
   <script type="text/javascript">
   $(document).ready(function(){
    

    $('#search_card').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"modules/fetch_numbers.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
},

//This changes the auto complete text bring up users specific information
updater:function(item){
  

    return item;
}
 });

/**
 * search username typeahead starts from here
 */
$('#username').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"modules/fetch_usernames.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
},

//This changes the auto complete text bring up users specific information
updater:function(item){
  

    return item;
}
 });



 $('#fship, #edit_fship').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"modules/search_fship.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
},

//This changes the auto complete text bring up users specific information
updater:function(item){
  

    return item;
}
 });



// Dealing with the date picker
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear()-18;

today = ''+dd + '/' + mm + '/' + yyyy+'';

//alert(today);
 $('[data-toggle="datepicker"]').datepicker({
        autoHide: true,
        zIndex: 2048,
        format: 'dd/mm/yyyy',
        endDate:today,
      });


       $("#memberForm").submit(function(event){
        alertify.confirm('Adding Member', 
        'You can\'t undo the Card number or Edit after saving. Are you sure you want save...', 
        function(){ submitMemberForm(); 
        }
                , function(){ alertify.error('Cancel')
                
        });
          
          return false;
       });
       
       function submitMemberForm(){

        if($("#search_card").val() ===""){
    alertify.warning("Card Number empty");
    $("#search_card").focus();
    return false;
}

if($("#first_name").val() == ""){
    alertify.warning("First Name empty");
    $("#first_name").focus();
    return false;
}


if($("#surname").val() ===""){
    alertify.warning("Surname empty");
    $("#surname").focus();
    return false;
}

if($("#phone").val() ===""){
    alertify.warning("Phone Number empty");
    $("#phone").focus();
    return false;
}


if($("#dob").val() === ""){
    alertify.warning("Date of Birth empty");
    $("#dob").focus();
    return false;
}


// if($("#zone").val() === ""){
//     alertify.warning("Member Zone empty");
//     $("#zone").focus();
//     return false;
// }


// if($("#fship").val() === ""){
//     alertify.warning("Fellowship empty");
//     $("#fship").focus();
//     return false;
// }



           var data = $('form#memberForm').serialize();
           $.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: data,
              success: function(response){
                 alertify.message(response);
              },
              error: function(){
                  
                  alert("error");
              }
           });
           //alert(data);

           clearInputs()
       }    
       


       function clearInputs(){
           $('#search_card').val("");
           $('#first_name').val("");
           $('#surname').val("");
           $('#phone').val("");
           $('#email').val("");
           $('#dob').val("");
           $('#zone').val("");
           $('#fship').val("");

       }

       //Dealing with the addon for the followship

$(document).on('click', '#add_fship', function(){

alertify.prompt("Fellowship", "Enter Name of Fellowship", "", 
function(evt, value) {
  if(value.length <1){
    alertify.error("Followship name is empty");
    return false;
  }
  
    var data = 'fellowship_add='+value;
//alert(data);

       $.ajax({
          type: "POST",
          url: "modules/registerMembers.php",
          cache: false,
          data: data,
          success: function(response){
             alertify.success(response);
            
          },
          error: function(){

              alert("error");
          }
       });
 },
function() { alertify.error('Canceled') });



});




$('#edit_search_card').typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"modules/search_fetch.php",
    method:"POST",
    data:{query:query},
    dataType:"json",
    success:function(data)
    {
     result($.map(data, function(item){
      return item;
     }));
    }
   })
},

//This changes the auto complete text bring up users specific information
updater:function(item){
  
  var mydata = "edit_card="+item.substr(0,6);
//alertify.prompt(mydata);
    jQuery.ajax({
			type: "POST", // HTTP method POST or GET
			url: "modules/getMembers.php", //Where to make Ajax calls
			dataType:"json", // Data type, HTML, json etc.
			data:mydata, //Form variables
			success:function(response){
				//on success, hide  element user wants to delete.
        $('#edit_first_name').val(response["fname"]);
        $('#edit_surname').val(response["surname"]);
        $('#edit_phone').val(response["phone"]);
        $('#edit_email').val(response["email"]);
        $('#edit_dob').val(response["dob"]);
        $('#edit_zone').val(response["zone"]);
        $('#edit_fship').val(response["fship"]);
        $("#edit_search_card").prop('disabled', true);




        

			},
			error:function (xhr, ajaxOptions, thrownError){
				//On error, we alert user
				alert(thrownError);
			}
      });
      
  return item.substr(0,6);
}
 });

  


 $("#editMemberForm").submit(function(event){
  $("#edit_search_card").prop('disabled', false);
        alertify.confirm('Editing Member', 
        'Are you sure you want Update user data...', 
        function(){ submitEditMemberForm(); 
        }
                , function(){ alertify.error('Cancel')
                
        });
          
          return false;
       });
       
       function submitEditMemberForm(){

        if($("#edit_search_card").val() ===""){
    alertify.warning("Card Number empty");
    $("#edit_search_card").focus();
    return false;
}

if($("#edit_first_name").val() == ""){
    alertify.warning("First Name empty");
    $("#edit_first_name").focus();
    return false;
}


if($("#edit_surname").val() ===""){
    alertify.warning("Surname empty");
    $("#edit_surname").focus();
    return false;
}

if($("#edit_phone").val() ===""){
    alertify.warning("Phone Number empty");
    $("#edit_phone").focus();
    return false;
}


if($("#edit_dob").val() === ""){
    alertify.warning("Date of Birth empty");
    $("#edit_dob").focus();
    return false;
}


// if($("#edit_zone").val() === ""){
//     alertify.warning("Member Zone empty");
//     $("#edit_zone").focus();
//     return false;
// }


// if($("#edit_fship").val() === ""){
//     alertify.warning("Fellowship empty");
//     $("#edit_fship").focus();
//     return false;
// }



           var data = $('form#editMemberForm').serialize();
           //alert(data);
           $.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: data,
              success: function(response){
                 alertify.message(response);
              },
              error: function(){
                  
                  alert("error");
              }
           });
           //alert(data);

           clearInputs()
       }    
       


       function clearInputs(){
           $('#edit_search_card').val("");
           $('#edit_first_name').val("");
           $('#edit_surname').val("");
           $('#edit_phone').val("");
           $('#edit_email').val("");
           $('#edit_dob').val("");
           $('#edit_zone').val("");
           $('#edit_fship').val("");

       }


   
       $("#accessControl").submit(function(event){  
        alertify.confirm('Access Control', 
        'User will be able to access the allowed permissions...', 
        function(){ submitAccessControlForm(); 
        }
                , function(){ alertify.error('Cancel')
                
        });
          
          return false;
       });
       
function submitAccessControlForm(){

if($("#username").val() ===""){
    alertify.warning("Username is required");
    $("#username").focus();
    return false;
}
var dashboard ="";
var editor ="";
var reporting ="";
var newproject ="";
var config ="";
var profile ="";

if($('#dashboard').is(":checked")){
  dashboard = $('#dashboard').val(); 

}
if($('#editor').is(":checked")){
  editor = $('#editor').val(); 

}
if($('#reporting').is(":checked")){
  reporting = $('#reporting').val(); 

}
if($('#newproject').is(":checked")){
  newproject = $('#newproject').val(); 

}
if($('#config').is(":checked")){
  config = $('#config').val(); 

}
if($('#profile').is(":checked")){
  profile = $('#profile').val(); 

}

           var data = $('form#accessControl').serialize()+"&dashboard="+dashboard+"&editor="+editor+"&reporting="+reporting+"&newproject="+newproject+"&config="+config+"&profile="+profile;
            //alert(data);
           //return false;
           $.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: data,
              success: function(response){
                 
                 //$("#logout").trigger('click');
if(response == "Password Changed"){
  alertify.success(response+", You are being loggout!!!");
  window.setInterval(function () {
            window.location.href = $("#logout").attr('href');
        }, 2000);
}else{
  alertify.warning(response);
}
               
                

              },
              error: function(){
                  
                  alert("error");
              }
           });
           //alert(data);

       } 
   


   $("#changePassword").submit(function(event){  
        alertify.confirm('Changing Password', 
        'Are you sure you want change your Password...', 
        function(){ submitChangePasswordForm(); 
        }
                , function(){ alertify.error('Cancel')
                
        });
          
          return false;
       });
       
function submitChangePasswordForm(){

if($("#current_password").val() ===""){
    alertify.warning("Current password is required");
    $("#current_password").val("");
    $("#current_password").focus();
    return false;
}

if($("#new_password").val() ===""){
    alertify.warning("New Password Empty"); 
    $("#current_password").val("");
    $("#new_password").val("");
    $("#new_password").focus();
    return false;
}


if($("#confirm_new_password").val() === ""){
    alertify.warning("Confirm Password Empty");
    $("#current_password").val("");
    $("#new_password").val("");
    $("#confirm_new_password").val("");
    $("#confirm_new_password").focus();
    return false;
}




if($("#new_password").val() != $("#confirm_new_password").val()){
    alertify.warning("New Password not Match!!!");
    $("#current_password").val("");
    $("#new_password").val("");
    $("#confirm_new_password").val("");
    $("#new_password").focus();
    return false;
}



           var data = $('form#changePassword').serialize();
          //  alert(data);
          //  return false;
           $.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: data,
              success: function(response){
                 
                 //$("#logout").trigger('click');
if(response == "Password Changed"){
  alertify.success(response+", You are being loggout!!!");
  window.setInterval(function () {
            window.location.href = $("#logout").attr('href');
        }, 2000);
}else{
  alertify.warning(response);
}
               
                

              },
              error: function(){
                  
                  alert("error");
              }
           });
           //alert(data);

       }    
       


      });

 
   </script>
</body>

</html>

<?php
}else{
    header("location: index");
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

