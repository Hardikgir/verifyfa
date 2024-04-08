<style>
.page-title{    
	font-size: 30px;
    font-weight: bold;
	width: auto;float: left;
	}
	.button {
        border: 1px transparent;
        color: #000;
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
          background-color: #fff;
          -webkit-box-shadow: 0 0 3px #eee;
        }
        50% {
          background-color: #fff;
          -webkit-box-shadow: 0 0 15px #eee;
        }
        100% {
          background-color: #fff;
          -webkit-box-shadow: 0 0 3px #eee;
        }
      }
      @keyframes glowing {
        0% {
          background-color: #fff;
          box-shadow: 0 0 3px #eee;
        }
        50% {
          background-color: #fff;
          box-shadow: 0 0 15px #eee;
        }
        100% {
          background-color: #fff;
          box-shadow: 0 0 3px #eee;
        }
      }
	  .form-control {
  
  padding: 5px !important;
}
</style>
<div class="sidebar" data-color="purple" data-background-color="white" data-image="<?php echo base_url();?>assets/img/sidebar-1.jpg">
			<div class="logo">
				<a href="#" class="simple-text logo-normal">
					<img src="<?php echo base_url();?>assets/img/logo.png" alt="Verify fa logo">
				</a>
			</div>
			<div class="sidebar-wrapper">
				<ul class="nav">
					<li class="nav-item <?php echo $page_title=='Dashboard'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/registered-user-dashboard"> <i class="material-icons">dashboard</i>
							<p>Dashboard</p>
						</a>
					</li>
					 <li class="nav-item <?php echo $page_title=='My Subscription'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/registered-user-subscription"> <i class="fas fa-chart-pie"></i>
							<p>My Subscription</p>
						</a>
					</li>

					<li class="nav-item <?php echo $page_title=='My Profile'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/registered-user-profile"> <i class="fas fa-chart-pie"></i>
							<p>My Profile</p>
						</a>
					</li>

					<!-- <li class="nav-item <?php echo $page_title=='Manage User'?'active':'';?>">
						<a class="nav-link" href="#"> <i class="far fa-user"></i>
							<p>Manage User</p>
						</a>
					</li> -->

					<li class="nav-item <?php echo $page_title=='Transfer Account'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/transfer-account"> <i class="fas fa-exchange-alt"></i>
							<p>Transfer My Account</p>
						</a>
					</li>

					<li class="nav-item <?php echo $page_title=='Change Password'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/registered-user-change-password"> <i class="far fa-edit"></i>
							<p>Change Password</p>
						</a>
					</li>

					
					<!-- <li class="nav-item <?php echo $page_title=='Exceptions'?'active':'';?>">
						<a class="nav-link" href="#"> <i class="fas fa-hands-helping"></i>
							<p>Manage Projects</p>
						</a>
					</li> -->
					<!--<li class="nav-item ">
						<a class="nav-link" href="#"> <i class="fas fa-hands-helping"></i>
							<p>Helpdesk</p>
						</a>
					</li> -->
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<!-- Navbar -->
			<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
				<div class="container-fluid mb-2">
					<div class="navbar-wrapper"> <a class="navbar-brand text-white" href="#pablo">	Hello <?php echo get_textday();?> <span style="font-size: 1.125rem;font-weight: bold;"><?php echo $this->session->userdata("registered_user_first_name")." ".$this->session->userdata("registered_user_last_name"); ?> </span>
				    <?php 
					$registered_user_id = $this->session->userdata("registered_user_id");
					$rguserrow=registered_user_row($registered_user_id);
					// echo date("Y-m-d g:i:a");
					?>
				
				<br>
				<span style="font-size: 1rem;"> This subscription is registered in the name of <?php echo $rguserrow->organisation_name;?> (<?php echo $rguserrow->entity_code;?>).</sapn>
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
							<li>   
							<a class="nav-link" href="<?php echo base_url();?>index.php/registered-user-as-admin/<?php echo $this->session->userdata('registered_user_id');?>">  
								 <button type="button" class="button">Continue as Group  Admin</button>
                              </a>
                          </li>
							<li class="nav-item">								
								<li class="nav-item dropdown">
									<a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="material-icons text-white">person</i>
										<p class="d-lg-none d-md-block"><?php echo $this->session->userdata('super_admin_name');?></p>
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
										<div class="dropdown-divider"></div> <a class="dropdown-item" href="<?php echo base_url();?>index.php/logout-registereduser">Log out</a>
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