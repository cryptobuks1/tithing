<?php
include '../classes/session.php';
if (($session->logged_in) && ($database->isPermissionAllowed($session->username, 'employee'))){

?>

<!-- Begin Page Content -->
        <div class="container-fluid">

        <nav class="navbar navbar-expand justify-content-center navbar-red bg-red-white topbar mb-1 shadow">

         

          <!-- Topbar Navbar -->
          <ul class="nav navbar-nav">

         
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <button class="nav-btn dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-plus fa-fw"></i>
                
              </button>
             
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <button class="nav-btn dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-edit fa-fw"></i>
              
              </button>
            
            </li>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <button class="nav-btn dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-folder fa-fw"></i>
              </button>
              
            </li>

          </ul>

        </nav>
            <div ng-app="">
                <table class="table table-bordered">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                        <th>DOB</th>
                        <th>City</th>
                        <th>Country</th>
                    </tr>
                    <tr ng-repeat="user in users">
                        <td>{{user.fname}}</td>
                        <td ng-bind= ' user'></td>
                    </tr>
                </table>
            </div>
           
            
            <input type="text" ng-model='user'>
        </div>
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
          
  
  
 
<!--      <script>
         function userCtrl($scope,$http) {
              console.log("Usersssss Controller reporting for duty.");
            var url = "../app_api/getemployee.php";

            $https.get(url).then( function(response) {
               $scope.users = response.data;
            });
         }
      </script>-->
