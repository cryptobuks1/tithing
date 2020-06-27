<?php
include '../classes/session.php';
global $database;
global $session;
?> 

<!-- Sidebar -->
    <ul class="noprint navbar-nav bg-gradient-primary sidebar sidebar-dark accordion border-right-hupgo" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a style="background-color:#fff;"class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
          <img style="width:45px; height:45px;" src="img/gracelogo.png"/>
          <!-- <i class="fas fa-laugh-wink"></i> -->
        </div>
        <div class=" noprint sidebar-brand-text mx-3">GRACE BAPTIST</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">
      
<?php
/*

 * Adding the side bar navigation base on the permissions granted to the users
 *  */
$database->get_permission_links($session->username);
     
        ?>
      <!-- Nav Item - Dashboard -->
     
      

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

      
    </ul>
    <!-- End of Sidebar -->


<script src="js/metisMenu.js"></script>
<!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>