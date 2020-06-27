<?php
include '../classes/session.php';
if (($session->logged_in) && ($database->isPermissionAllowed($session->username, 'dashboard'))) {
    ?>

    <div class="container-fluid">

        <nav id="navhupgo" class="navbar navbar-expand justify-content-start navbar-red bg-red-white topbar mb-1 shadow">

            <div class="d-none d-sm-block h6 left">TITHING</div>

            <!-- Topbar Navbar -->
            <ul class="nav navbar-nav">


                <!-- Nav Item - Alerts -->


                <!-- Nav Item - Messages -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <button class="nav-btn dropdown-toggle"  id="messagesDropdown" role="button" data-toggle="modal" data-target="#edit-member-modal" title="Edit Member Info" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-edit fa-fw"></i>  <span class=" text-xs">Edit Member</span>

                    </button>

                </li>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <button class="nav-btn dropdown-toggle" id="userDropdown" role="button" data-toggle="modal" data-target="#add-member-modal" title="Add New Member" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-plus fa-fw"></i><span class=" text-xs">Add Member</span>
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
            <h1 class="h3 mb-0 text-gray-500">Details</h1>
          </div>
          <div id="card_to_display"></div>



                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-3">
                            <div class="text-center">
                                <h1 class="h4 text-gray-500 mb-4">Add New Transaction</h1>
                            </div>
                            <form id="tithe_form" class="user">

                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="search_code" id="search_code" autocomplete="off" placeholder="Search for Code, Phone, or Name...">
                                </div>
                              
                                    <!-- Row to contain the radio  -->
                                    <div class="row m-1">
                                        <div class=" col-xl-4 col-xs-4 col-sm-4 col-sm-4 col-lg-4">
                                            <div class = "radio">
                                                <label>
                                                    <input type = "radio" name = "donation_radio" id = "tithe_radio" value = "tithe" > Tithe
                                                </label>
                                            </div>
                                        </div>
                                        <div class=" col-xl-4 col-xs-4 col-sm-4 col-sm-4 col-lg-4">
                                            <div class = "radio">
                                                <label>
                                                    <input type = "radio" name = "donation_radio" id ="dues_radio" value = "dues">
                                                    Dues (Funeral)
                                                </label>
                                            </div>
                                        </div>
                                        <div class=" col-xl-4 col-xs-4 col-sm-4 col-sm-4 col-lg-4">
                                            <div class = "radio">
                                                <label>
                                                    <input type = "radio" name = "donation_radio" id ="donation_radio" value = "donation" checked>
                                                    Donation
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                            <!-- end of Row for the radio -->



                                <div class="form-group" id="month_div"></div>
                                <div id="dues_benfiters"></div>
                                <div id="donations"><?php $database->getProjectDonation() ?></div>
                              


                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="amount" id="amount" placeholder="Amount Paid">
                                    
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="description" id="description" placeholder="Small SMS message">
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
} else {
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

<!-- <link href="vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/> -->
<style>
.typeahead{
    background-color: #fff;
    min-width: 300px;
    border-radius:5px;
}

.typeahead li{
    padding: 5px;
    border:1px dotted #eee;
}

.typeahead li.active{
    background-color:#eee;
}
</style>


<script type="text/javascript">
   $(document).ready(function(){
    //('#start_date').data('daterangepicker').remove();
    alertify.set('notifier','position', 'top-right');
/**
 * 
 * 
 * The search starts from here
 * 
 */
    $('#search_code').typeahead({
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

/**
 * 
 * This changes the auto complete text bring up users specific information
 * 
 * This populate the cards for specific user base on searches
 */
updater:function(item){
    var mydata = "card="+item.substr(0,6);
//alertify.prompt(mydata);
    jQuery.ajax({
			type: "POST", // HTTP method POST or GET
			url: "modules/detailed_response.php", //Where to make Ajax calls
			dataType:"html", // Data type, HTML, json etc.
			data:mydata, //Form variables
			success:function(response){
				//on success, hide  element user wants to delete.
				$('#card_to_display').html(response);
			},
			error:function (xhr, ajaxOptions, thrownError){
				//On error, we alert user
				alert(thrownError);
			}
			});

    return item.substr(0,6);
}
 });

 $("#tithe_form").submit(function(event){
          validateTitheInput();
          
          return false;
       });

       /**
        * xamppfiles/htdocs/tithing/modules/registerMembers.php on line 62

        * Function for submitting the tithe, dues and donation forms
        *
        * 
        */
       function submitMemberForm(){
           var data = $('form#tithe_form').serialize();
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

                  alertify.error("error");
              }
           });
           //alert(data);
       }



       function validateTitheInput(){
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

submitMemberForm();
clearInputs();
       }

       function clearInputs(){
           $('#search_code').val("");
           $('#amount').val("");
           $('#month').val("");
           $('#search_benefiter').val("");
           $("#donation_radio").is(":checked")

       }

/**
 * 
 * The radio buttons functionalities goes here
 * 
 */

$("#tithe_radio, #dues_radio, #donation_radio").change(function () {                
    if ($("#tithe_radio").is(":checked")) {
        $("#month_div").append("<select class=\"form-control form-control-user\" id=\"month\" name=\"month\"><option selected >\"Month to be Paid\"</option><?php for($m=0; $m<12; $m++){echo '<option>'.date('F-Y', strtotime($m.' months')).'</option>';}?></select>");
        $("#dues_benfiters").empty();
        $("#donations").empty();

    }else if($("#dues_radio").is(":checked")){
        $("#dues_benfiters").append("<div class=\"input-group mb-lg-3\"><input type=\"text\" class=\"form-control form-control-user\" name=\"search_benefiter\" id=\"search_benefiter\" autocomplete=\"off\" placeholder=\"Search Benefiters...\"><div class=\"input-group-append\" id=\"add_benefiters\"><button class=\"btn btn-danger\" type=\"button\"><i class=\"fas fa-plus fa-sm\"></i></button></div></div>");
        $("#month_div").empty();
        $("#donations").empty();
    }else if($("#donation_radio").is(":checked")){
        $("#donations").append('<?php $database->getProjectDonation() ?>');
        $("#month_div").empty();
        $("#dues_benfiters").empty();   
    }
});



$('body').on('click', '#search_project', function() { 

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



/***
 * 
 * 
 * This part for the benefiters button and search 
 * 
 * 
 * 
 */

//Dealing with the addon for the benefiter 

$(document).on('click', '#add_benefiters', function(){

    alertify.prompt("Enter Benefiter Name", "Benefiters Name", "", 
    function(evt, value) { 
        var data = 'benefiter_add='+value;
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


//This searches for the benefits
$('body').on('click', '#search_benefiter', function() { 

$(this).typeahead({
    hint:true,
    minLength:1,
    displayKey:'Benefiter',
  source: function(query, result)
  {
   $.ajax({
    url:"modules/search_benefiter.php",
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
}

 });
 
 
 });


});
</script>