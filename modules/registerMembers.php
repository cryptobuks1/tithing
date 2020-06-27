<?php

include("../classes/session.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $database;
global $session;
$message = "";


//For the tithe submission 
if(isset($_POST['search_code']) && $_POST['donation_radio']==="tithe"){
        $search_code = strip_tags($_POST['search_code']);
    $amount = strip_tags($_POST['amount']);
    $month = strip_tags($_POST['month']);
    $description = strip_tags($_POST['description']);

    if($database->checkTithePayment($search_code, $month)){
        $message = "<div class='btn btn-warning'>This user have already paid for this Month & Year</div>";
        echo $message;
        return;
    }

$database->submitTithe($search_code, $amount, $month, $description);

//echo "<div class='btn btn-danger'>Tithes</div>";

//for the submission of dues
}elseif(isset($_POST['search_code']) && $_POST['donation_radio']==="dues"){
    $search_code = strip_tags($_POST['search_code']);
    $amount = strip_tags($_POST['amount']);
    $benefiter = strip_tags($_POST['search_benefiter']);
    $description = strip_tags($_POST['description']);

    // Check whether the user have already paid for the dues for a specific benefiter
    if($database->checkDuesPayment($search_code, $benefiter)){
        $message = "<div class='btn btn-warning'>This person have already paid for this Benefiter.</div>";
        echo $message;
        return;
    }


$database->submitDues($search_code, $amount, $benefiter, $description);

//for the submission of donations
}elseif(isset($_POST['search_code']) && $_POST['donation_radio']==="donation"){
//SmS api will be inserted here at the right time. I know is not that difficult to implement though
$search_code = strip_tags($_POST['search_code']);
    $amount = strip_tags($_POST['amount']); 
    $description = strip_tags($_POST['description']);
    if(isset($_POST['search_project'])){
        $search_project = strip_tags($_POST['search_project']);

    $submitted = $database->submitProjectGroupVal($search_project, $search_code, $amount, $description);
        $messageSMS = "Your Donation contribution of Ghc".$amount." have been recieved.".$description.". Thank you";
        $sms= $database->sendSMS($messageSMS, $search_code, "GBC Donates");
echo $sms." ".$submitted;  

}else{
    
   $database->submitDonations($search_code, $amount, $description);
    }


//echo "<div class='btn btn-danger'>Donations</div>";



/**
 * 
 * //Submitting new project name
 * /xamppfiles/htdocs/tithing/modules/registerMembers.php on line 78
 */
}elseif(isset($_POST['add_project_name']) && isset($_POST['add_target_amount'])){
    
    $project_name= strip_tags($_POST['add_project_name']);
    $project_target = strip_tags($_POST['add_target_amount']);


    if($database->checkProject($project_name)){
echo "<div class='btn btn-warning'>There seems to exist Similar Porject names</div>";
return;
    }
    $submitted = $database->submitProjectName($project_name, $project_target);
    echo $submitted;
    
}elseif(isset($_POST['project_name']) && isset($_POST['amount'])){
//submiting project amount for groups.
    $project_name= strip_tags($_POST['project_name']);
    $amount = strip_tags($_POST['amount']);
    $group_name = strip_tags($_POST['group_name']);
    $description = strip_tags($_POST['description']);

    $submitted = $database->submitProjectGroupVal($project_name, $group_name, $amount, $description);

echo $submitted;

}elseif(isset($_POST['benefiter_add']) ){
$benefiter_add = strip_tags($_POST['benefiter_add']);

if($database->checkBenefiters($benefiter_add)){
    $message = "<div class='btn btn-warning'>Similar name already Exist</div>";
    echo $message;
    
    return;
}

if($database->submitBenefiters($benefiter_add)) {
    $message = "<div class='btn btn-success'>Added</div>";
} else {
    $message = "<div class='btn btn-danger'>Record not Saved</div>";
}
echo $message;



/**
 * Registring new fellowship
 * 
 */
}elseif(isset($_POST['fellowship_add'])){

    $fellowship_add = strip_tags($_POST['fellowship_add']);

    if($database->checkFellowship($fellowship_add)){
        $message = "<div class='btn btn-warning'>Similar name already Exist</div>";
        echo $message;
        
        return;
    }
    
    if($database->submitFellowship($fellowship_add)) {
        $message = "<div class='btn btn-success'>Added</div>";
    } else {
        $message = "<div class='btn btn-danger'>Record not Saved</div>";
    }
    echo $message;




/**
 * 
 * 
 * For registration of new members
 */    

}elseif((isset($_POST['first_name']) && !empty($_POST['first_name'])) && (isset($_POST['surname_name']) && !empty($_POST['surname_name'])) && (isset($_POST['phone_number']) && !empty($_POST['phone_number'])) && isset($_POST['dob']) && isset($_POST['card_number'])) {

    $firstname = strip_tags($_POST['first_name']);
    $surname = strip_tags($_POST['surname_name']);
    $phone_number = strip_tags($_POST['phone_number']);
    $email_address = strip_tags($_POST['email_address']);
    $zone = strip_tags($_POST['zone']);
    $dob=strip_tags($_POST['dob']);
    $fship=strip_tags($_POST['fship']);
    $card_number = strip_tags($_POST['card_number']);
    
    
if($database->checkPhoneNumber($phone_number)){
    $message = "<div class='btn btn-warning'>Phone number already Exist</div>";
    echo $message;
    
    return;
}

if($database->checkCardNumber($card_number)){
    $message = "<div class='btn btn-warning'>Card number already Exist</div>";
    echo $message;
    
    return;
}


  
    if($database->registerMembers($firstname, $surname, $phone_number, $email_address, $zone, $card_number, $dob, $fship)) {
        $message = "<div class='btn btn-success'>Member Added</div>";
    } else {
        $message = "<div class='btn btn-danger'>Record not Saved</div>";
    }
    echo $message;


}elseif((isset($_POST['edit_first_name']) && !empty($_POST['edit_first_name'])) && (isset($_POST['edit_surname_name']) && !empty($_POST['edit_surname_name'])) && (isset($_POST['edit_phone_number']) && !empty($_POST['edit_phone_number'])) && isset($_POST['edit_dob']) && isset($_POST['edit_zone']) && isset($_POST['edit_card_number']))
{
    $firstname = strip_tags($_POST['edit_first_name']);
    $surname = strip_tags($_POST['edit_surname_name']);
    $phone_number = strip_tags($_POST['edit_phone_number']);
    $email_address = strip_tags($_POST['edit_email']);
    $zone = strip_tags($_POST['edit_zone']);
    $dob=strip_tags($_POST['edit_dob']);
    $fship=strip_tags($_POST['edit_fship']);
    $card_number = strip_tags($_POST['edit_card_number']);

$database->updateMembers($firstname, $surname, $phone_number, $email_address, $zone, $card_number, $dob, $fship);



}elseif(isset($_POST['recordToDisable'])){
    $id=strip_tags($_POST['recordToDisable']);
    $database->disableProject($id);

}elseif(isset($_POST['recordToEnable'])){
    $id=strip_tags($_POST['recordToEnable']);
    $database->enableProject($id);

    /**
     * Editor pages stuff from here
     * 
     * If isset edit_card and isset edit_id
     * 
     * This comes with plenty options available to be edited
     */
}elseif(isset($_POST['edit_card']) && isset($_POST['edit_id'])){
    $edit_card = strip_tags($_POST['edit_card']);
    $edit_type = strip_tags($_POST['edit_type']);
    $amount= strip_tags($_POST['amount']);    
    $edit_id=strip_tags($_POST['edit_id']);


    if($edit_type == 'dues'){
        $purpose = strip_tags($_POST['edit_benefiter']);
       }elseif($edit_type == 'project'){
        $purpose = strip_tags($_POST['edit_project']);

        // if($database->checkDuesPayment($edit_card, $purpose)){
        //     echo "<div class='btn btn-danger'>This user have already paid for the month and year selected</div>";
        //     return false;
        // }
      

    }elseif($edit_type == 'tithe'){
        
        $purpose = strip_tags($_POST['edit_month']);
        
        // if($database->checkTithePayment($edit_card, $purpose)){
        //     echo "<div class='btn btn-danger'>This user have already paid for the month and year selected</div>";
        //     return false;
        // }
     }elseif($edit_type == 'donation'){
        $purpose = strip_tags($_POST['edit_donation']);
    }
    
    $database->updateTransaction($edit_card, $edit_id, $amount, $purpose, $edit_type);

}elseif(isset($_POST['recordToDel']) && isset($_POST['transType'])){
    $recordToDel = strip_tags($_POST['recordToDel']);
    $transType = strip_tags($_POST['transType']);

    $database->delTransaction($recordToDel, $transType);
    /**
     * Configuration stuff starts from here
     * 
     * with the registration of new personnel to operate the system
     */
}elseif(isset($_POST['full_name']) && isset($_POST['username'])){

    $full_name = strip_tags($_POST['full_name']);
    $phone = strip_tags($_POST['phone']);
    $username= strip_tags($_POST['username']);    
    $password=strip_tags($_POST['password']);
    $confirm_pass = strip_tags($_POST['confirm_pass']);
$session->SessionRegister($username, $password, $confirm_pass, $full_name, $phone);



    // $message = "<div class='btn btn-danger'>Config save drive</div>";
    // echo $message;
}elseif(isset($_POST['new_password']) && isset($_POST['current_password'])){

    $old_pass = strip_tags($_POST['current_password']);
    $new_pass = strip_tags($_POST['new_password']);
$username = $_SESSION['username'];
//echo $message = "<div class='btn btn-danger'>$username</div>";
   $confirmPass= $database->confirmUserPass($username, $old_pass);
   if($confirmPass == 0){
       $salt = $database->getSalt();
		//Generate a unique password Hash
		$passwordHash = password_hash($database->concatPasswordWithSalt($new_pass, $salt),PASSWORD_DEFAULT);
   if($database->updateUserPassword($username, $passwordHash, $salt)){
    echo "Password Changed";
       //indicate its available
   }
   }elseif($confirmPass == 1){

    echo "Username not found";
       //username not found
   }else{
    echo "Password not found";
       //password not found
   }
   
}elseif(isset($_POST['username']) && isset($_POST['dashboard']) && isset($_POST['profile']) && isset($_POST['newproject'])){
    $username = strip_tags($_POST['username']);
    $dashboard = strip_tags($_POST['dashboard']);
    $editor = strip_tags($_POST['editor']);
    $reporting= strip_tags($_POST['reporting']);    
    $newproject=strip_tags($_POST['newproject']);
    $config = strip_tags($_POST['config']);
    $profile = strip_tags($_POST['profile']);
 //$permission = $database->isAccessGranted($username, $dashboard);
 $dashpermission = $database->isAccessGranted($username, "dashboard");
 $editorpermission = $database->isAccessGranted($username, "editor");
 $reportingpermission = $database->isAccessGranted($username, "reporting");
 $newprojectpermission = $database->isAccessGranted($username, "newproject");
 $configpermission = $database->isAccessGranted($username, "config");
 $profilepermission = $database->isAccessGranted($username, "profile");

if($dashboard == ""){
   $dashboard = "dashboard";
  
    if($dashpermission == 1){
        $database->deleteAccessControl($username, $dashboard);
    }
}elseif($dashboard == "dashboard"){
  
    if($dashpermission == 0){
    $database->insertAccessControl($username, $dashboard);
    }
}





if($editor == ""){
    $editor = "editor";
   
     if($editorpermission == 1){
         $database->deleteAccessControl($username, $editor);
     }
 }elseif($editor == "editor"){
   
     if($editorpermission == 0){
     $database->insertAccessControl($username, $editor);
     }
 }
 
 if($reporting == ""){
    $reporting = "reporting";
    
     if($reportingpermission ==1){
         $database->deleteAccessControl($username, $reporting);
     }
 }elseif($reporting == "reporting"){
   
     if($reportingpermission == 0){
     $database->insertAccessControl($username, $reporting);
     }
 }
 
 
 if($newproject == ""){
    $newproject = "newproject";
    
     if($newprojectpermission == 1){
         $database->deleteAccessControl($username, $newproject);
     }
 }elseif($newproject=="newproject"){
   
     if($newprojectpermission == 0){
     $database->insertAccessControl($username, $newproject);
     }
 }
 
 
 
 if($config == ""){
    $config = "config";
   
     if($configpermission == 1){
         $database->deleteAccessControl($username, $config);
     }
 }elseif($config == "config"){
   
     if($configpermission == 0){
     $database->insertAccessControl($username, $config);
     }
 }
 
 if($profile == ""){
    $profile = "profile";
    
     if($profilepermission == 1){
         $database->deleteAccessControl($username, $profile);
     }
 }elseif($profile == "profile"){
   
     if($profilepermission == 0){
     $database->insertAccessControl($username, $profile);
     }
 }
    
}elseif(isset($_POST['menu_options']) && $_POST['menu_options'] =="all" && isset($_POST['start_date'])){
    $menu_options = strip_tags($_POST['menu_options']);
    $start_date = strip_tags($_POST['start_date']);
    $date = explode(" - ", $start_date);
$from = $date[0]." 00:00:00";
$to = $date[1]." 23:59:59";


$database->getAllReport($from, $to);


}elseif(isset($_POST['menu_options']) && isset($_POST['search_fellowship']) && isset($_POST['start_date'])){
    $menu_options = strip_tags($_POST['menu_options']);
    $start_date = strip_tags($_POST['start_date']);
    $search_fellowship = strip_tags($_POST['search_fellowship']);
    $date = explode(" - ", $start_date);
    $from = $date[0]." 00:00:00";
    $to = $date[1]." 23:59:59";
$database->getFellowshipReport($search_fellowship, $from, $to);
   //echo $menu_options." and ".$start_date." last ".$search_fellowship;
}elseif(isset($_POST['menu_options']) && isset($_POST['search_project']) && isset($_POST['start_date'])){
    $menu_options = strip_tags($_POST['menu_options']);
    $start_date = strip_tags($_POST['start_date']);
    $search_project = strip_tags($_POST['search_project']);
    $date = explode(" - ", $start_date);
    $from = $date[0]." 00:00:00";
    $to = $date[1]." 23:59:59";

$database->getProjects($search_project, $from, $to);

}elseif(isset($_POST['menu_options']) && isset($_POST['search_benefiters']) && isset($_POST['start_date'])){
    $menu_options = strip_tags($_POST['menu_options']);
    $start_date = strip_tags($_POST['start_date']);
    $search_benefiters = strip_tags($_POST['search_benefiters']);
    $date = explode(" - ", $start_date);
    $from = $date[0]." 00:00:00";
    $to = $date[1]." 23:59:59";
$database->getDues($search_benefiters, $from, $to);

}elseif(isset($_POST['menu_options']) && isset($_POST['search_month']) && isset($_POST['start_date'])){
   $menu_options = strip_tags($_POST['menu_options']);
    $start_date = strip_tags($_POST['start_date']);
    $search_month = strip_tags($_POST['search_month']);
    $date = explode(" - ", $start_date);
    $from = $date[0]." 00:00:00";
    $to = $date[1]." 23:59:59";
$database->getTithes($search_month, $from, $to);

}elseif(isset($_POST['menu_options']) && isset($_POST['search_member']) && isset($_POST['start_date'])){
    $menu_options = strip_tags($_POST['menu_options']);
    $start_date = strip_tags($_POST['start_date']);
    $search_member = strip_tags($_POST['search_member']);
    $date = explode(" - ", $start_date);
    $from = $date[0]." 00:00:00";
    $to = $date[1]." 23:59:59";
$database->getMemberReport($search_member, $from, $to);

}elseif(isset($_POST['menu_options']) && $_POST['menu_options']=="donation" && isset($_POST['start_date'])){
    $menu_options = strip_tags($_POST['menu_options']);
     $start_date = strip_tags($_POST['start_date']);
     $date = explode(" - ", $start_date);
     $from = $date[0]." 00:00:00";
     $to = $date[1]." 23:59:59";
 $database->getDonations($from, $to);
 }


else{

     $message = "<div class='btn btn-danger'>There can't be empty fields</div>";

    echo $message;
}
