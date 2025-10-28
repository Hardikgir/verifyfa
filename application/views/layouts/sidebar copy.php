<style>
		.content-new{background:#fff;}
		.dataTables_length label{ text-align:left;}
		.form-row{text-align: left;}
		.form-label{width:100%;color: #5ca1e2;font-weight: bold;}
		.bmd-form-group{width:100%;}
		.ck-editor{ width:100% !important;}
</style>
<style>
.page-title{    
	font-size: 30px;
    font-weight: bold;
	width: auto;float: left;
	}
	.button {
        border: 1px transparent;
        color: #eeeeee;
        cursor: pointer;
        display: inline-block;
        font-family: Arial;
        font-size: 20px;
        padding: 8px 30px;
        text-align: center;
        text-decoration: none;
        margin-left: 20px;
        -webkit-animation: glowing 1300ms infinite;
        -moz-animation: glowing 1300ms infinite;
        -o-animation: glowing 1300ms infinite;
        animation: glowing 1300ms infinite;
      }
      @-webkit-keyframes glowing {
        0% {
          background-color: #0091b2;
          -webkit-box-shadow: 0 0 3px #4caf50;
        }
        50% {
          background-color: #21c7ed;
          -webkit-box-shadow: 0 0 15px #4caf50;
        }
        100% {
          background-color: #0091b2;
          -webkit-box-shadow: 0 0 3px #4caf50;
        }
      }
      @keyframes glowing {
        0% {
          background-color: #4caf50;
          box-shadow: 0 0 3px #4caf50;
        }
        50% {
          background-color: #4caf50;
          box-shadow: 0 0 15px #4caf50;
        }
        100% {
          background-color: #61b982;
          box-shadow: 0 0 3px #61b982;
        }
      }
	  table.dataTable thead th, table.dataTable thead td {
    padding: 10px 18px;
    border-bottom: 1px solid #111;
    text-align: left;
}
td{
    text-align: left;
}
.form-check .form-check-label {
    cursor: pointer;
    padding-left: 0px;
    position: relative;
    color: #5ca1e3;
    font-size: 15px;
    font-weight: bold;
}
.notall{
	background: white;
    padding: 6px;
    color: #027aec;
    font-weight: bold;
}
.inner{
	height: 200px !important;
	min-height: 200px !important;
    overflow-y: auto !important;

}
.form-control:invalid {
    background-image: linear-gradient(to top, #f44336 2px, rgba(244, 67, 54, 0) 2px), linear-gradient(to top, #d2d2d2 1px, rgba(210, 210, 210, 0) 1px);
}

select, select.form-control {
    -moz-appearance: none;
    -webkit-appearance: none;
}
.form-control, label, input::placeholder {
    line-height: 1.1;
}
.form-control, .is-focused .form-control {
    background-image: linear-gradient(to top, #5CA1E2 2px, rgba(156, 39, 176, 0) 2px), linear-gradient(to top, #d2d2d2 1px, rgba(210, 210, 210, 0) 1px);
}
.form-control {
  
    padding: 5px !important;
}
</style>
<?php 

$user_id=$this->user_id;
$entity_code=$this->admin_registered_entity_code;
$user_role_addmin_cnt=get_user_role_cnt_admin($user_id,$entity_code);
$user_role_manager_cnt=get_user_role_cnt_managers($user_id,$entity_code);
$countuserrole =countuserrole($user_id);
$session111=$this->session->userdata('logged_in');
$user_role_subadmin_cnt=user_role_subadmin_cnt($user_id,$entity_code);


?>
	 	<div class="sidebar" data-color="purple" data-background-color="white" data-image="<?php echo base_url();?>assets/img/sidebar-1.jpg">
			<div class="logo">
				<a href="#" class="simple-text logo-normal">
					<img src="<?php echo base_url();?>assets/img/logo.png" alt="Verify fa logo">
				</a>
			</div>
			<div class="sidebar-wrapper">

			

			<ul class="nav">

				
				<?php 
				// if($this->main_role == '5' && $countuserrole == '0'){ ?>
					<!-- <li class="nav-item <?php if(($page_title=='Dashboard') || ($page_title=='Manage Notification') || ($page_title =='Manage Entity') ||  ($page_title =='Manage Location')|| ($page_title =='Manage Department')  || ($page_title =='Manage User')  || ($page_title =='User Maping')){echo 'active';}?>">
						<a class="nav-link"> 
							<p>Admin Function</p>
						</a>
					</li>
					<hr class="hr" />
					<li class="nav-item <?php echo $page_title=='Dashboard'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/dashboard"> <i class="material-icons">dashboard</i>
							<p>Dashboard</p>
						</a>
					</li>
					<li class="nav-item <?php echo $page_title=='Manage Entity'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-entity"> <i class="fa fa-building"></i>
							<p>Manage Company</p>
						</a>
					</li>

					<li class="nav-item <?php echo $page_title=='Manage Location'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-location"> <i class="fa fa-location-arrow"></i>
							<p>Manage Location</p>
						</a>
					</li>
					<li class="nav-item <?php echo $page_title=='Manage Department'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-department"> <i class="fa fa-road"></i>
							<p>Manage Department</p>
						</a>
					</li>
					<li class="nav-item <?php echo $page_title=='Manage User'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-user-admin"> <i class="fa fa-users"></i>
							<p>Manage User</p>
						</a>
					</li>

					<li class="nav-item <?php echo $page_title=='User Maping'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-user-role"><i class="fa fa-user-circle"></i>
							<p>Manage User Role</p>
						</a>
					</li>

					<li class="nav-item <?php echo $page_title=='Manage Notification'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-notification"> <i class="fa fa-bell"></i>
							<p>Manage Notification</p>
						</a>
					</li>
					 -->

				<?php 
			// }else{ 
				?>	
<?php 
$usercntrole=Count_user_role();
$role_base_title = 'Admin';
// $dashboard_url = base_url().'index.php/dashboard';
$dashboard_url = base_url().'index.php/dashboard/admin';
if($usercntrole == '3' || $usercntrole == '2' || $usercntrole == '1'){
	$role_base_title = 'User';
	$dashboard_url = base_url().'index.php/dashboard/User';
}


if($usercntrole == '4'){
	echo '<li>Sub Admin</li>';
}
if($usercntrole == '5'){
	echo '<li>Group Admin</li>';
}
if($usercntrole == '0'){
	echo '<li>Manager</li>';
}
if($usercntrole == '2'){
	echo '<li>Process Owner</li>';
}
if($usercntrole == '3'){
	echo '<li>Entity Owner</li>';
}
if($usercntrole == '1'){
	echo '<li>Verifier</li>';
}



if($usercntrole > 0){ 
	
if($usercntrole != '3'){
	?>	
					<li class="nav-item <?php if(($page_title=='Dashboard') || ($page_title=='User Dashboard') || ($page_title=='Manage Notification')  || ($page_title =='Manage Entity') ||  ($page_title =='Manage Location')|| ($page_title =='Manage Department')  || ($page_title =='Manage User')  || ($page_title =='User Maping')){echo 'active';}?>">
						<a class="nav-link"> 
							<p><?php echo $role_base_title; ?> Function</p>
						</a>
					</li>
					<hr class="hr" />
					<li class="nav-item <?php if(($page_title=='Dashboard')){ echo 'active'; } ?>">
						<a class="nav-link" href="<?php echo $dashboard_url; ?>"> <i class="material-icons">dashboard</i>
							<p><?php echo $role_base_title; ?> Dashboard</p>
						</a>
					</li>
					<?php } ?>
	<?php /*
					<li class="nav-item <?php if(($page_title=='Project Dashboard') || ($page_title=='Project Detail')){echo 'active';}?><?php if(($page_title=='')){ echo 'active'; } ?>">
						<a class="nav-link" href="<?php echo base_url().'index.php/dashboard/project'; ?>"> <i class="material-icons">dashboard</i>
							<p>Project Dashboard</p>
						</a>
					</li> */ ?>

      <?php 
		if(($user_role_addmin_cnt > 0)){ 
		?>

                   <li class="nav-item <?php echo $page_title=='Manage Entity'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-entity"> <i class="fa fa-building"></i>
							<p>Manage Company</p>
						</a>
					</li>

					<li class="nav-item <?php echo $page_title=='Manage Location'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-location"> <i class="fa fa-location-arrow"></i>
							<p>Manage Location</p>
						</a>
					</li>

					<li class="nav-item <?php echo $page_title=='Manage Department'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-department"><i class="fa fa-road"></i>
							<p>Manage Department</p>
						</a>
					</li>

					<li class="nav-item <?php echo $page_title=='Manage User'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-user-admin"> <i class="fa fa-users"></i>
							<p>Manage User</p>
						</a>
					</li>
					<?php } ?>
		
       <?php if(($user_role_addmin_cnt > 0) || ($user_role_subadmin_cnt > 0) ){ 
		$update_pfcnt= check_proile_update($this->user_id);
		if($update_pfcnt->profile_update =='0'){
			?>
			<li class="nav-item <?php echo $page_title=='User Maping'?'active':'';?>  ">
						<a class="nav-link" href="#" onclick="profile_ntupdate('<?php echo $this->user_id;?>')"><i class="fa fa-user-circle"></i>
							<p>Manage User Role</p>
						</a>
					</li>
	 <?php
		}else{
		?>
					<li class="nav-item <?php echo $page_title=='User Maping'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-user-role"><i class="fa fa-user-circle"></i>
							<p>Manage User Role</p>
						</a>
					</li>
		<?php }
	} ?>

					<?php if(($user_role_addmin_cnt > 0) || ($user_role_manager_cnt > 0) ){ ?>
						<li class="nav-item <?php echo $page_title=='Manage Notification'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-notification"><i class="fa fa-bell"></i>
							<p>Manage Notification</p>
						</a>
					</li>
                    <?php } ?>

					
					<?php if(($user_role_addmin_cnt > 0) || ($user_role_manager_cnt > 0) ){ ?>
						<li class="nav-item <?php echo $page_title=='Manage My Issue'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-my-issue"><i class="fa fa-bug"></i>
							<p>Manage My Issue</p>
						</a>
					</li>
                    <?php } ?>

					<?php if(($user_role_addmin_cnt > 0) || ($user_role_manager_cnt > 0) ){ ?>
						<li class="nav-item <?php echo $page_title=='Manage Issue For Me'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-issue-for-me"><i class="fa fa-bug"></i>
							<p>Issue For Me</p>
						</a>
					</li>
                    <?php } ?>
					
					<?php if($usercntrole != '3'){ ?>

					<li class="nav-item  <?php if(($page_title=='Plan Cycle') || ($page_title =='Reports') ||  ($page_title =='Excpetions')){echo 'active';}?>">
						<a class="nav-link"> 
							<p>Activity Function</p>
						</a>
					</li>
					<hr class="hr" />

					<?php } ?>

					<?php /*
					<li class="nav-item <?php echo $page_title=='Activity Dashboard'?'active':'';?> ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/dashboard"> <i class="material-icons">dashboard</i>
							<p>Dashboard</p>
						</a>
					</li>
					
					<li class="nav-item <?php echo $page_title=='User Dashboard'?'active':'';?> ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/dashboard/User"> <i class="material-icons">dashboard</i>
							<p>Dashboard</p>
						</a>
					</li> */ ?>

					<?php /*
					<li class="nav-item <?php echo $page_title=='User Dashboard'?'active':'';?> ">
						<a class="nav-link" href="<?php echo $dashboard_url;?>"> <i class="material-icons">dashboard</i>
							<p>Dashboard</p>
						</a>
					</li> */ ?>

					
          <?php 
          //manager role Menu//
			if($user_role_manager_cnt > 0){ 
 	       ?>
		
					<li class="nav-item <?php echo $page_title=='Plan Cycle'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/plancycle"> <i class="fas fa-chart-pie"></i>
							<p>Plan Cycle</p>
						</a>
					</li>
		<?php } 
         //manager role Menu//
         ?>
					<li class="nav-item <?php echo $page_title=='Reports'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/dashboard/reports"> <i class="far fa-file"></i>
							<p>Report</p>
						</a>
					</li>
					<li class="nav-item <?php echo $page_title=='Excpetions'?'active':'';?>"">
						<a class="nav-link" href="<?php echo base_url();?>index.php/dashboard/exceptions"> <i class="material-icons">Exceptions</i>
							<p>Exceptions</p>
						</a>
					</li>
					<li class="nav-item ">
						<a class="nav-link" href="#"> <i class="fas fa-hands-helping"></i>
							<p>Helpdesk</p>
						</a>
					</li>
					
					<?php 
				} ?>

					
 <?php 
// }
 ?>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<!-- Navbar -->
			<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
				<div class="container-fluid">
					<div class="navbar-wrapper"> 
						<a class="navbar-brand text-white p-0" href="#pablo">
							Hello <?php echo get_textday();?> <span style="font-size: 1.125rem;font-weight: bold;"><?php echo $this->name; ?> </span>
							<?php
					// if(isset($session111['is_register_as_login']) && $session111['is_register_as_login'] == '1'){ 
						$admin_registered_user_id = $session111['admin_registered_user_id'];
						$rguserrow=registered_user_row($admin_registered_user_id);
						?>
					
					<br>
					<span style="font-size: 1rem;"> This subscription is registered in the name of <?php echo $rguserrow->organisation_name;?> (<?php echo $rguserrow->entity_code;?>).</sapn>
				<?php 
				// }
				 ?>
				</a>
					</div>
					<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation"> <span class="sr-only">Toggle navigation</span>
						<span class="navbar-toggler-icon icon-bar"></span>
						<span class="navbar-toggler-icon icon-bar"></span>
						<span class="navbar-toggler-icon icon-bar"></span>
					</button>
					<div class="collapse navbar-collapse justify-content-end">
						<form class="navbar-form">							
						</form>
						<ul class="navbar-nav">


						<!-- for notification -->
					<?php 
					
					$main_notification= get_all_notification_by_userspecific($entity_code);
					// echo '<pre>last_query ';
					// print_r($this->db->last_query());
					// echo '</pre>';
					// exit();
					?>
						<li class="nav-item">								
								<li class="nav-item dropdown">
									<a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #fff;"> <i class="fa fa-bell"></i><span id="notificationcnt" class="123213">(<?php echo count($main_notification); ?>)</span>
									</a>
<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile" style="overflow-y: scroll;height: 350px;"> 


<?php 
$i=0;
$j=0;
$unread_count = 0;
foreach($main_notification as $main_notification_row){
	$i++;
	$userrownt= get_user_row($main_notification_row->created_by);
	$cntmain_notify= check_main_notificationread($this->user_id,$main_notification_row->id);
	// if($cntmain_notify == '0'){
		$i++;
	?>
	<a class="dropdown-item d-block" href="<?php echo base_url();?>index.php/view-reply-notofication/<?php echo $main_notification_row->id;?>?main_not=1" 
		<?php 
		if($cntmain_notify != '0'){ 
			
			echo 'style="background: #abb5bd;color:#fff;font-size: 18px;font-weight: bold;border-bottom: 2px solid #11589b;margin: 0;"';
		}else{
			$unread_count++;
			echo 'style="background: #5ca1e2;color:#fff;font-size: 18px;font-weight: bold;border-bottom: 2px solid #11589b;margin: 0;"';
		} ?>
		><?php echo ucfirst($userrownt->firstName).' '.ucfirst($userrownt->lastName);?> Broadcast a New <?php echo $main_notification_row->type;?><br>
		<p style="font-size: 15px;font-weight: normal;" class="pb-0 mb-0"> <?php echo $main_notification_row->title;?> <span style="color:blue;">Check now<span></p>
		<p style="margin: 0;padding: 0;font-size: 12px;"><b>At:</b> <?php echo date("d-M-Y g:i:a", strtotime($main_notification_row->created_at));?></p>  
	</a>
<?php 
// }
 ?>

<?php 
// $usercntrole=Count_user_role();
if($usercntrole == 0){ 
	?>	
	No Role Assigned.
	<?php die; } ?>




<?php
/*
$allreply = get_all_reply($main_notification_row->id);
foreach($allreply as $reply_row){ 
	$userrownt1= get_user_row($reply_row->reply_from);
    $cnt_reply_user=get_cntreply_user($this->user_id,$reply_row->id);
	if($cnt_reply_user == 0){
		$j=$i+1;
	?>
	<a class="dropdown-item d-block" href="<?php echo base_url();?>index.php/view-reply-notofication/<?php echo $main_notification_row->id;?>?reply_msg=1&&reply_msg_id=<?php echo $reply_row->id;?>" style="background: #5ca1e2;color:#fff;font-size: 18px;
		font-weight: bold;border-bottom: 2px solid #11589b;margin: 0;"><?php echo ucfirst($userrownt1->firstName).' '.ucfirst($userrownt1->lastName);?> a New Reply <?php echo $main_notification_row->title;?><br>
		<p style="font-size: 15px;font-weight: normal;" class="pb-0 mb-0"><span style="color:blue;">Check now<span></p>   
		<p style="margin: 0;padding: 0;font-size: 12px;"><b>At:</b> <?php echo date("d-M-Y g:i:a", strtotime($reply_row->reply_at));?></p>  

	</a>
 <?php } }
 */
 ?>

<?php } ?>
<p class="notall"><a href="<?php echo base_url();?>index.php/view-all-notification">See All Notification</a></p>
<script>
document.getElementById("notificationcnt").innerHTML='(<?php echo $unread_count;?>)'; 
</script>
</div>					</li>
							</li>	
                    <!-- for notification -->

							<li class="nav-item">								
								<li class="nav-item dropdown">
									<a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="material-icons text-white">person</i>
										<p class="d-lg-none d-md-block">Account</p>
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile"> <a class="dropdown-item" href="<?php echo base_url();?>"><?php echo $_SESSION['logged_in']['name'];?><br/></a>
									<!-- (<?php if(isset($this->user_type) && $this->user_type!=''){echo $this->user_type==1?'Verifier':($this->user_type==0?'Manager':($this->user_type==2?'Process Owner':($this->user_type==3?'Item Owner':'Admin')));}else{} ?> -->
										<!-- <a class="dropdown-item" href="#">Change Role</a> -->
										<div class="dropdown-divider"></div> <a class="dropdown-item" href="<?php echo base_url();?>index.php/my-profile/">My Profile</a>
										<div class="dropdown-divider"></div> <a class="dropdown-item" href="<?php echo base_url();?>index.php/change-my-password">Change Password</a>


										<div class="dropdown-divider"></div> <a class="dropdown-item" href="<?php echo base_url();?>index.php/login/logout">Log out</a>
									</div>
								</li>
							</li>	
						</ul>
					</div>
				</div>
			</nav>
			<div class="card-body px-lg-5 py-lg-5 mt-4">
							<div class="alert alert-success" style="<?php echo $this->session->flashdata('success')!=''?'':'display:none;'; ?>" role="alert">
								<?php echo $this->session->flashdata('success'); ?>
							</div>
			<!-- End Navbar -->