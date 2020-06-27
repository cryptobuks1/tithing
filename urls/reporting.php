<?php
include '../classes/session.php';
if (($session->logged_in) && ($database->isPermissionAllowed($session->username, 'reporting'))){

?>
<!-- Begin Page Content -->
        <div class="container-fluid">

        <div class="card border-0 shadow-lg my-2">
            <div class="card-body p-3">
              <form id="reporting_form">
                         <div class="row" id="fellowship_run">
        
         <div class=" col-xl-3 col-xs-3 col-sm-3 col-sm-3 col-lg-3">
         <select class="browser-default custom-select" name="menu_options" id="menu_options">
  <option value="member">Member</option>
  <option value="tithe">Tithe</option>
  <option value="dues">Dues</option>
  <option value="donation">Donation</option>
  <option value="project">Project</option>
  <option value="fellowship">Fellowship</option>
  <option value="all" selected>All</option>
</select>
                                        </div>

                                        <div class=" col-xl-3 col-xs-3 col-sm-3 col-sm-3 col-lg-3">
                                        <div class="form-group" id="dynamic_input">
                                              </div>
                                </div>

                                <div class=" col-xl-4 col-xs-4 col-sm-4 col-sm-4 col-lg-4">
                                        <div class="input-group">
                                        <div class="input-group-prepend">
    <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
  </div>
                                    <input type="text" class="form-control" name="start_date" id="start_date" autocomplete="off" placeholder="Start Date (19/07/1985)...">
                                </div>
                                </div>

                      

                                <div class=" col-xl-1 col-xs-1 col-sm-1 col-sm-1 col-lg-1">
                                       
                                    <button class="btn btn-success" name="generate" id="generate" value="Generate">Generate</button>
                             
                                </div>
                                <div class=" col-xl-1 col-xs-1 col-sm-1 col-sm-1 col-lg-1" id="printer">
                                      
                                   </div>

         </div>


         </form>

        
        

        </div>
</div>
<div id="print_me">
<div class="report-holder" id="report-holder">
</div>
</div>
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


        <script>
        $(document).ready(function(){


          /**
           * This is the print function for angular Js The normal print function doesn't work as expected. So this is the best way to deal with it
           */
          $("body").on('click', '#printer', function(){
          var printContents = document.getElementById('print_me').innerHTML;
            var popupWin = window.open('', '_blank', 'width=800,height=800,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no,top=50');
            popupWin.window.focus();
            popupWin.document.open();
            popupWin.document.write('<!DOCTYPE html><html><head><title>Grace Baptist Print</title>' 
                                    +'<link rel="stylesheet" type="text/css" href="css/sb-admin-2.css" />' 
                                    +'</head><body onload="window.print(); window.close();"><div>' 
                                    + printContents + '</div></html>');
            popupWin.document.close();

         // $scope.printDiv = function (div) {
  // var docHead = document.head.outerHTML;
  // var printContents = document.getElementById('print_me').outerHTML;
  // var winAttr = "location=yes, statusbar=no, menubar=no, titlebar=no, toolbar=no,dependent=no, width=865, height=600, resizable=yes, screenX=200, screenY=200, personalbar=no, scrollbars=yes";

  // var newWin = window.open("", "_blank", winAttr);
  // var writeDoc = newWin.document;
  // writeDoc.open();
  // writeDoc.write('<!doctype html><html>' + docHead + '<body onLoad="window.print()">' + printContents + '</body></html>');
  // writeDoc.close();
  // newWin.focus();
//}
          });

          var start = moment().subtract(29, 'days');
    var end = moment();

    // function cb(start, end) {
    //     $('#start_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    // }
    $('#start_date').click(function(){
      $('#printer').empty();
    });

    $('#start_date').daterangepicker({
      "timePickerSeconds": true,
        startDate: start,
        endDate: end,
        locale: { 
    format: 'YYYY-MM-DD',
    

  },
        "opens": "center",
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    // cb(start, end);

       //   $("#start_date").daterangepicker();
          $('select').on('change', function() {
         

$('#printer').empty();
            if(this.value == 'fellowship'){
              $("#dynamic_input").empty();
              $("#dynamic_input").append("<input type=\"text\" class=\"form-control form-control-user\" name=\"search_fellowship\" id=\"search_fellowship\" autocomplete=\"off\" placeholder=\"Search fellowship...\">")
            }else if(this.value == 'project'){
              $("#dynamic_input").empty();
              $("#dynamic_input").append("<input type=\"text\" class=\"form-control form-control-user\" name=\"search_project\" id=\"search_project\" autocomplete=\"off\" placeholder=\"Search Project...\">")
            }else if(this.value == 'dues'){
              $("#dynamic_input").empty();
              $("#dynamic_input").append("<input type=\"text\" class=\"form-control form-control-user\" name=\"search_benefiters\" id=\"search_benefiters\" autocomplete=\"off\" placeholder=\"Search benefiters...\">")
            }else if(this.value == 'tithe'){
              $("#dynamic_input").empty();
              $("#dynamic_input").append("<input type=\"text\" class=\"form-control form-control-user\" name=\"search_month\" id=\"search_month\" autocomplete=\"off\" placeholder=\"Search Month/Year...\">")
            }else if(this.value == 'member'){
              $("#dynamic_input").empty();
              $("#dynamic_input").append("<input type=\"text\" class=\"form-control form-control-user\" name=\"search_member\" id=\"search_member\" autocomplete=\"off\" placeholder=\"Search Card Number, Name, Phone...\">")
            }else if(this.value == 'donation'){
              $("#dynamic_input").empty();
              //$("#dynamic_input").append("<input type=\"text\" class=\"form-control form-control-user\" name=\"search_donation\" id=\"search_member\" autocomplete=\"off\" placeholder=\"Search Card Number, Name, Phone...\">")
            }else if(this.value == 'all'){
              $("#dynamic_input").empty();
               }
  //alert( this.value );
});

//Typeahead element for each of the searches goes here


//This searches for the benefits
$('body').on('click', '#search_benefiters', function() { 

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

//Search projects

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



/**
 * 
 * Search fellowships 
 * 
 * 
 */

$('body').on('click', '#search_fellowship', function() { 

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
    

    return item.substr(0,6);
}
 });
});



 /**
  * 
  * Search members
  * 
  */


$('body').on('click', '#search_member', function() { 

$(this).typeahead({
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
    

    return item.substr(0,6);
}
 });
});


/**
 * 
 * Search date year
 * 
 */


$('body').on('click', '#search_month', function() { 

$(this).typeahead({
  source: function(query, result)
  {
   $.ajax({
    url:"modules/search_year_month.php",
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




$("body").on('click',"#search_benefiters", function(){
  $("#printer").empty();
});
$("body").on('click',"#search_month", function(){
  $("#printer").empty();
});
$("body").on('click',"#search_member", function(){
  $("#printer").empty();
});

$("body").on('click',"#search_fellowship", function(){
  $("#printer").empty();
});
$("body").on('click',"#search_project", function(){
  $("#printer").empty();
});


$('#generate').click(function(){
var menu_options= $("#menu_options").val();
var fellowship = $("#fellowship").val();
var project= $("#project").val();
var dues= $("#dues").val();
var tithe= $("#tithe").val();
var member= $("#member").val();
var all= $("#all").val();
var start_date= $("#start_date").val();
//$('form#accessControl').serialize()
var myData= $('form#reporting_form').serialize();//+"menu_options="+menu_options+"&fellowship="+fellowship+"&project="+project+"&dues="+dues+"&tithe="+tithe+"&member="+member+"&all="+all+"&start_date="+start_date;
var second = start_date.split(" - ");
var start = second[0];
var ending = second[1];
//alert("Starting Date: "+start+" \n Ending Date: "+ ending);
//alert(myData);

$.ajax({
              type: "POST",
              url: "modules/registerMembers.php",
              cache: false,
              data: myData,
              success: function(response){
                 $("#report-holder").html(response); 
                 $("#printer").append("<button class=\"btn btn-success\"  name=\"print\" id=\"print\">Print</button>");       
//0246261027
              },
              error: function(){
                  
                  alert("error");
              }
           });
});




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
  <script type="text/javascript" src="vendor/date-range/moment.min.js"></script>
<script type="text/javascript" src="vendor/date-range/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="vendor/date-range/daterangepicker.css" />
