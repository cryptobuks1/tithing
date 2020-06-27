<?php
include '../classes/session.php';
if (($session->logged_in) && ($database->isPermissionAllowed($session->username, 'newproject'))){

?>

<!-- Begin Page Content -->
        <div class="container-fluid">

         <nav class="navbar navbar-expand justify-content-start navbar-red bg-red-white topbar mb-1 shadow">

         

          <!-- Topbar Navbar -->
          <ul class="nav navbar-nav">

         
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <button class="nav-btn" id="add_new_project" title="Add New Project" >
                <i class="fas fa-plus fa-fw"></i> <span class=" text-xs">New Project</span>
                
              </button>
             
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <button class="nav-btn dropdown-toggle" id="collect_cash" title="Collect Cash for Project" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-money-bill-alt fa-fw"></i> <span class=" text-xs">Contribute</span>
              
              </button>
            
            </li>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <button class="nav-btn dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-file-invoice-dollar fa-fw"></i>
              </button>
              
            </li>

          </ul>

        </nav>

      
        <div class="card border-0 shadow-lg my-2">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-3">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h4 class="h4 mb-0 text-gray-500">Projects</h4>
          </div>
          <div class="row">
          <div class="col col-12" id="display_projects"></div>
</div>
</div>
</div>  
<!-- the form to be viewed as dialog-->
<div id="cool"></div>
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

<script type="text/javascript">
$(document).ready(function(){

  window.setInterval(function () {
            getNotifications();
        }, 2000);


        function getNotifications(){
  alertify.set('notifier','position', 'top-right');
  $.ajax({
              type: "GET",
              url: "modules/get_projects.php",
              cache: false,
             
              dataType: "html",
              success: function(response){
                $("#display_projects").html(response);
                
              },
              error: function(){

                  alert("error");
              }
           }); 
        }



alertify.genericDialog || alertify.dialog('genericDialog',function(){
    return {
        main:function(content){
            this.setContent(content);
        },
        setup:function(){
            return {
                focus:{
                    element:function(){
                        return this.elements.body.querySelector(this.get('selector'));
                    },
                    select:true
                },
                options:{
                  modal:true,
                    basic:true,
                    transition:'zoom',
                    maximizable:false,
                    resizable:false,
                    padding:false
                }
            };
        },
        settings:{
            selector:undefined
        }
    };
});

$("#collect_cash").click(function(){
//force focusing password box
//$("#cool").append("<form id=\"loginForm\"><fieldset><label> Username </label><input type=\"text\" value=\"Mohammad\"/><label> Password </label><input type=\"password\" value=\"password\"/><input type=\"submit\" value=\"Login\"/></fieldset></form>");

alertify.genericDialog ($("<form autocomplete=\"off\" id=\"project_submit\" class=\"col-12\"><h3 class=\"h3\">Collect Cash</h3>\n\
<div class=\"form-group\"><input class=\"form-control\" name=\"project_name\" id=\"project_name\" type=\"text\" placeholder=\"Search project name or number...\"/></div>\n\
<div class=\"form-group\"><input class=\"form-control\" name=\"group_name\" id=\"group_name\" type=\"text\" placeholder=\"Group or fellowship name...\"/></div>\n\
<div class=\"form-group\"><input class=\"form-control\" name=\"amount\" id=\"amount\" type=\"text\" placeholder=\"Amount\"/></div>\n\
<div class=\"form-group\"><input class=\"form-control\" name=\"description\" id=\"description\" type=\"text\" placeholder=\"Description\"/></div>\n\
<div class=\"form-group\"><button type=\"submit\" class=\"btn btn-success\" id=\"submit_project_cash\">Submit</button></form></div>")[0]);
});

$(document).on("click", "#submit_project_cash", function(){
          //validateTitheInput();
          submitProjectCashForm();
          
          return false;
       });

 
function submitProjectCashForm(){

  if($("#project_name").val() === ""){
  
  alertify.warning("Project name empty");
   return false;
 }
 
 if($("#group_name").val() === ""){
 
  alertify.warning("Group name empty");
   return false;
 }

 if($("#amount").val() === ""){
  
  alertify.warning("Amount empty");
   return false;
 }
 alertify.confirm('Are you sure??', function(){
     var data = $('form#project_submit').serialize();

           $.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: data,
              success: function(response){
                  alertify.message(response);
                  $("#project_name").val("");
                  $("#group_name").val("");
                  $("#amount").val("");
                  $("#description").val("");
              },
              error: function(){

                  alertify.error("error");
              }
           });
           //alert(data);

  });
      
}


       $(document).on("click", "#submit_project_name", function(){
          //validateTitheInput();
          submitProjectNameForm();
          
          return false;
       });


       function submitProjectNameForm(){
if($("#add_project_name").val() === ""){
  
 alertify.warning("Project Name Empty");
  return false;
}

if($("#add_target_amount").val() === ""){

 alertify.warning("Project target  empty");
  return false;


}

     var data = $('form#project_submit').serialize();

           $.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: data,
              success: function(response){
                  alertify.message(response);
                  $("#project_name").val("");
                  $("#group_name").val("");
                  $("#amount").val("");
                  $("#description").val("");
              },
              error: function(){

                  alertify.error("error");
              }
           });
           //alert(data);

           alertify.confirm('Are you sure to save project name and Target?', function(){
             var data = $('form#project_name_submit').serialize();
//alert(data);
           $.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: data,
              success: function(response){
                  alertify.message(response);
$("#add_project_name").val("");
$("#add_target_amount").val("");
              },
              error: function(){

                  alertify.error("error");
              }
           });
           //alert(data);
           });
           
       }
$(document).on('click', '#add_new_project', function(){
//force focusing password box
//$("#cool").append("<form id=\"loginForm\"><fieldset><label> Username </label><input type=\"text\" value=\"Mohammad\"/><label> Password </label><input type=\"password\" value=\"password\"/><input type=\"submit\" value=\"Login\"/></fieldset></form>");

alertify.genericDialog ($("<form autocomplete=\"off\" id=\"project_name_submit\" class=\"col-12\"><h3 class=\"h3\">Add New Project</h3>\n\
<div class=\"form-group\"><input class=\"form-control\" name=\"add_project_name\" id=\"add_project_name\" type=\"text\" placeholder=\"Name of Project\"/></div>\n\
<div class=\"form-group\"><input class=\"form-control\" name=\"add_target_amount\" id=\"add_target_amount\" type=\"text\" placeholder=\"Target Amount in Ghc\"/></div>\n\
<div class=\"form-group\"><button type=\"submit\" class=\"btn btn-success\" id=\"submit_project_name\">Submit</button></form></div>")[0]);
 
       // var data = 'project_add='+value;
//alert(data);

});












$('body').on('click', '#project_name', function() { 

$(this).typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"modules/search_project.php",
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

/**
 * 
 * This changes the auto complete text bring up users specific information
 * 
 * This populate the cards for specific user base on searches
 */
updater:function(item){
    

    return item.substr(0,1);
}
 });
});




$('body').on('click', '#group_name', function() { 

$(this).typeahead({
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

/**
 * 
 * This changes the auto complete text bring up users specific information
 * 
 * This populate the cards for specific user base on searches
 */
updater:function(item){
    

    return item;
}
 });
});




$('body').on('click', '.disable_button', function(e) { 

  e.preventDefault();
		 var clickedID = this.id.split('-'); //Split ID string (Split works as PHP explode)
		 var DbNumberID = clickedID[1]; //and get number from array
     alertify.confirm('Are you sure to Disable the project?', function(){ 
       	 var myData = 'recordToDisable='+ DbNumberID; //build a post data structure
//alert(myData);

$.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: myData,
              success: function(response){
                  alertify.message(response);
// $("#add_project_name").val("");
// $("#add_target_amount").val("");
              },
              error: function(){

                  alertify.error("error");
              }
           });
      });
	

});


$('body').on('click', '.enable_button', function(e) { 

e.preventDefault();
   var clickedID = this.id.split('-'); //Split ID string (Split works as PHP explode)
   var DbNumberID = clickedID[1]; //and get number from array
   alertify.confirm('Are you sure to Enable the project?', function(){
     var myData = 'recordToEnable='+ DbNumberID; //build a post data structure
//alert(myData);

$.ajax({
            type: "POST",
            url: "modules/registerMembers.php",
            cache: false,
            data: myData,
            success: function(response){
                alertify.message(response);
// $("#add_project_name").val("");
// $("#add_target_amount").val("");
            },
            error: function(){

                alertify.error("error");
            }
         });
    });
   

});

});

</script>

        <!-- /.container-fluid -->
        
        
  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-de
