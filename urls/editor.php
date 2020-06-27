<?php
include '../classes/session.php';
if (($session->logged_in) && ($database->isPermissionAllowed($session->username, 'editor'))){

?>
<!-- Begin Page Content -->
        <div class="container-fluid">
<nav class="navbar navbar-expand justify-content-center navbar-red bg-red-white topbar mb-1 shadow-sm">


          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
                <input type="text"  id="search_phone_editor" class="form-control bg-light border-0 small" autocomplete="off" placeholder="Search Name, Phone, or Code...">
              <div class="input-group-append">
                <button class="btn btn-danger" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
         </nav>
                                                                                                                                                                                                                                                                                                                                                                                                                                                             


            <!-- Row for each single data on single payment -->
<div class="card border-0 shadow-sm my-3">
            <div class="card-body p-3" id="display_editor">
                <!-- Nested Row within Card Body -->
                <?php $database->getEditors(); ?>
            </div>       
</div>





<!-- end of fluid container -->
        </div>

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
    alertify.set('notifier','position', 'top-right');


    $('#search_phone_editor').typeahead({
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
updater:function(item){
    var mydata = "card_number="+item.substr(0, 6);
//alert(mydata);
    jQuery.ajax({
			type: "POST", // HTTP method POST or GET
			url: "modules/getTransaction.php", //Where to make Ajax calls
			dataType:"html", // Data type, HTML, json etc.
			data:mydata, //Form variables
			success:function(response){
				//on success, hide  element user wants to delete.
				$('#display_editor').html(response);
			},
			error:function (xhr, ajaxOptions, thrownError){
				//On error, we alert user
				alert(thrownError);
			}
			});

    return item.substr(0, 6);
}
 });

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




 $('body').on('click', '.buttonedit-trans', function(e) { 

e.preventDefault();
   var clickedID = this.id.split('-'); //Split ID string (Split works as PHP explode)
   var DbNumberID = clickedID[1]; //and get number from array
   var DbTransType = clickedID[0];
   //alertify.confirm('Are you sure to Disable the project?', function(){ 
        var myData = 'recordToEdit='+ DbNumberID+'&transType='+DbTransType; //build a post data structure
//alert(myData);

$.ajax({
            type: "POST",
            url: "modules/getTransaction.php",
            cache: false,
            data: myData,
            dataType:'json',
            success: function(response){
               // alertify.message(response['amount']);
                $("#display_editor").empty();
var purpose=""
                if(response["type"] =="tithe"){
               purpose=  "<select class=\"form-control form-control-user\" id=\"edit_month\" name=\"edit_month\"><option selected >"+response["reason"]+"</option><?php for($m=0; $m<12; $m++){echo '<option>'.date('F-Y', strtotime('-'.$m.' months')).'</option>';}?></select>";
                }else if(response["type"] =="project"){
              purpose =   "<div class=\"form-group\"><input type=\"text\" class=\"form-control form-control-user\" value=\""+response["reason"]+"\" name=\"edit_project\" id=\"edit_project\" placeholder=\"Purpose\"></div>";
                }else if(response["type"] =="donation"){
              purpose =   "<div class=\"form-group\"><input type=\"text\" class=\"form-control form-control-user\" value=\""+response["reason"]+"\" name=\"edit_donation\" id=\"edit_donation\" placeholder=\"Purpose\"></div>";
                }else if(response["type"] =="dues"){
              purpose =   "<div class=\"form-group\"><input type=\"text\" class=\"form-control form-control-user\" value=\""+response["reason"]+"\" name=\"edit_benefiter\" id=\"edit_benefiter\" placeholder=\"Purpose\"></div>";
                }
                $("#display_editor").append("<button id=\"back_button\" class=\"back_button text-danger h4\"><i class=\"fa fa-backspace\"></i></button><div class=\"col-lg-6\">\n\
                        <div class=\"p-3\">\n\
                            <div class=\"text-center\">\n\
                                <h1 class=\"h4 text-gray-500 mb-4\">Edit Transaction</h1></div>\n\
                            <form id=\"edit_trans_form\" class=\"user\">\n\
                                <div class=\"form-group\">\n\
                                    <input type=\"text\" class=\"form-control form-control-user\" value=\""+response["user_id"]+"\" name=\"edit_card\" id=\"edit_card\" autocomplete=\"off\" placeholder=\"Card Number\">\n\
                                </div>\n\
                                <div class=\"form-group\">\n\
                                    <input type=\"text\" class=\"form-control form-control-user\" value=\""+response["type"]+"\" name=\"edit_type\" id=\"edit_type\" autocomplete=\"off\" placeholder=\"Type\">\n\
                                </div>\n\
                                <div class=\"form-group\">\n\
                                    <input type=\"text\" class=\"form-control form-control-user\"value=\""+response["amount"]+"\" name=\"amount\" id=\"amount\" placeholder=\"Amount\">\n\
                            </div>"+purpose+"\n\
                               <div class=\"form-group\">\n\
                                    <input type=\"hidden\" class=\"form-control form-control-user\" value=\""+response["id"]+"\" name=\"edit_id\" id=\"edit_id\" placeholder=\"Purpose\">\n\
                                </div>\n\
                                <div id=\"submit\" class=\"btn btn-primary btn-user btn-block submit_edit_trans\">Submit</div>\n\
                            </form>\n\
                        </div>\n\
                    </div>\n\
");

$("#edit_card").prop('disabled', true);
$("#edit_type").prop('disabled', true);

                
                   },
            error: function(){

                alertify.error("error");
            }
        // });
    });


});


$('body').on('click', '.buttondel-trans', function(e) { 

e.preventDefault();
   var clickedID = this.id.split('-'); //Split ID string (Split works as PHP explode)
   var DbNumberID = clickedID[1]; //and get number from array
   var DbTransType = clickedID[0];
   alertify.confirm('Are you sure to Delete this Tranaction? You can\'t undo this!!!', function(){ 
        var myData = 'recordToDel='+ DbNumberID+'&transType='+DbTransType; //build a post data structure
alert(myData);
$('#item_'+DbNumberID).addClass( "btn-danger" ); //change background of this element by adding class
		$(this).hide(); //hide currently clicked delete button
		 
$.ajax({
            type: "POST",
            url: "modules/registerMembers.php",
            cache: false,
            data: myData,
            dataType:'html',
            success: function(response){
              // alertify.message(response);
               $('#item_'+DbNumberID).fadeOut(3600);
                   },
            error: function(){

                alertify.error("error");
            }
         });
    });
});

$(document).on("click", ".submit_edit_trans", function(){
  //submitTransForm()
  var edit_card = $("#edit_card").val();
  var edit_type = $("#edit_type").val();
  var amount = $("#amount").val();
  var edit_month= $("#edit_month").val();
  var edit_benefiter= $("#edit_benefiter").val();
  var edit_project= $("#edit_project").val();
  var edit_donation = $("#edit_donation").val();
  var edit_id = $("#edit_id").val();
  if(edit_type === 'project'){
    var edit_project_id = edit_project.split(": ");
        edit_project = edit_project_id[0];
  }
  
  alertify.confirm('Are you sure to Edit this Transaction?', function(){ 
  var data = "edit_card="+edit_card+"&edit_type="+edit_type+"&amount="+amount+"&edit_id="+edit_id+"&edit_month="+edit_month+"&edit_benefiter="+edit_benefiter+"&edit_donation="+edit_donation+"&edit_project="+edit_project;
//alert(data);
  //    return false;
    
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
  });
             });


       

$(document).on('click', '.back_button', function() { 
location.reload();
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



$('body').on('click', '#edit_project', function() { 

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
    

    return item;
}
 });
});



});

        
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
      