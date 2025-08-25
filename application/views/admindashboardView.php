
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
 $user_id=$this->user_id;
 $entity_code=$this->admin_registered_entity_code;
$user_role_manager_cnt=get_user_role_cnt_managers($user_id,$entity_code);
$user_role_admin_cnt=get_user_role_cnt_admin($user_id,$entity_code);


 
?>
<style>
    .divtextn{
        text-align: center;
    color: #9d9191;
    font-size: 65px;
    margin-top: 20%;
    font-weight: bold;
    }
    </style>


<style>
	.card-header{
	    font-size: 20px;
    font-weight: bold;
    min-height: 67px;
	background: #5ca1e2 !important;
    color: #fff;
	min-height: 100px;
	}
	.card-header h2 {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}
	.card-txt{
		font-size: 50px;
	}
	.table-bordered th, .table-bordered td {
    border: 1px solid rgb(0 0 0);
	color: #000;
    padding: 10px;
}
 .txt-cardp{
	font-size: 20px;
    color: #000;
    font-weight: bold;
 }
.card-body-n{
	min-height: 200px !important;
}

	</style>



<div class="content">

<?php 
$usercntrole=Count_user_role();
if($this->main_role != '5'){
if($usercntrole == 0){ 
	?>	
    <div class="divtextn">
	No Role Assigned.
    </div>
    <?php
$this->load->view('layouts/scripts');
// $this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
<?php  die;}?>
<!-- <div class="divtextn">
	No Role Assigned.
    </div> -->
<?php }?>


<div class="container-fluid">

<div class="row">
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h2>Subscription Plan Details</h2>
         </div>
         <div class="card-body card-body-n" style="padding: 2px 1px;">
            <?php $plan_row=get_plan_row(2);?>
            <p class="txt-cardp" style="text-align: center;"><?php echo $subscription_plan_details->title;?></p>
            <ul>
               <li>Activation Date: <b><?php echo date("d-M-Y",strtotime($registered_user_plan_details->plan_start_date));?></b></li>
            </ul>
            <p class="txt-cardp" style="text-align: center;">Plan Brief:</p>
            <ul>
               <li>No. of Entities – <b><?php echo $subscription_plan_details->allowed_entities_no;?></b></li>
               <li>No. of Locations under each Entity – <b><?php echo $subscription_plan_details->location_each_entity;?></b></li>
               <li>Total No. of Users – <b><?php echo $subscription_plan_details->user_number_register;?></b></li>
               <li>Line Item Available – <b><?php echo $subscription_plan_details->line_item_avaliable;?></b></li>
            </ul>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h2>Current Subscription Valid till</h2>
         </div>
         <div class="card-body card-body-n">
            <h2 style="text-align: center;font-weight: bold;">
               <?php echo date("d-M-Y",strtotime($registered_user_plan_details->plan_end_date));?>
            </h2>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header ">
            <h2>Current Subscription Expiring in next</h2>
         </div>
         <div class="card-body card-body-n">
            <h2 style="text-align: center;font-weight: bold;">
               <?php
                  if($registered_user_plan_details->plan_end_date < date("Y-m-d")){
                  }else{
                  $time_remain=get_diff_twodate($registered_user_plan_details->plan_end_date);
                  ?>
               <?php echo $time_remain;?> Left
               <?php } ?>
            </h2>           
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h2>No. of Companies added</h2>
         </div>
         <div class="card-body card-body-n">
            <h2 style="text-align: center;font-weight: bold;">
               <?php echo $total_company_count; ?>
            </h2>
            <?php 
               $Companies_count = (int)$subscription_plan_details->allowed_entities_no-(int)$total_company_count;
               ?>
            <p class="txt-cardp" style="text-align: center;"><?php echo " (",$Companies_count." remaining)"; ?></p>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h2>No. of Locations added</h2>
         </div>
         <div class="card-body card-body-n">
            <h2 style="text-align: center;font-weight: bold;">
               <?php echo $total_company_locations_count; ?>
            </h2>
            <?php 
               $Location_count = (int)$subscription_plan_details->location_each_entity-(int)$total_company_locations_count;
               ?>
            <p class="txt-cardp" style="text-align: center;"><?php echo " (".$Location_count." remaining)"; ?></p>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h2>No. of Users added</h2>
         </div>
         <div class="card-body card-body-n">
            <h2 style="text-align: center;font-weight: bold;">
               <?php echo $total_users_count; ?>
            </h2>
            <?php 
               $user_count = (int)$subscription_plan_details->user_number_register-(int)$total_users_count;
               ?>
            <p class="txt-cardp" style="text-align: center;"><?php echo " (",$user_count." remaining)"; ?></p>
         </div>
      </div>
   </div>
</div>
</div>

<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
