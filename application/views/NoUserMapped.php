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


.canvasjs-chart-toolbar button{
    display: none;
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

    <!-- <h2 style="text-align: center;font-weight:bold">No Role Assigned.</h2> -->
            
<p style="text-align: center;font-weight:bold;margin-top: 25px;">Contact your Group Admin for login related issues</p>
    <?php
$this->load->view('layouts/scripts');
// $this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
<?php  die;}?>

<?php }?>




<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<?php 

?>

