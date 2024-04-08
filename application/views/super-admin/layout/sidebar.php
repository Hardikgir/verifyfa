<style>
.page-title{    
	font-size: 30px;
    font-weight: bold;
	width: auto;float: left;
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
						<a class="nav-link" href="<?php echo base_url();?>index.php/super-admin-dashboard"> <i class="material-icons">dashboard</i>
							<p>Dashboard</p>
						</a>
					</li>
					 <li class="nav-item <?php echo $page_title=='Manage Subscription'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-subscription"> <i class="fas fa-chart-pie"></i>
							<p>Manage Subscription</p>
						</a>
					</li>
					
					<li class="nav-item <?php echo $page_title=='Manage User'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/manage-user"> <i class="far fa-user"></i>
							<p>Manage Registration</p>
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
				<div class="container-fluid">
					<div class="navbar-wrapper"> <a class="navbar-brand text-white" href="#pablo"><?php echo $this->session->userdata('super_admin_name');?></a>
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
							<li class="nav-item">								
								<li class="nav-item dropdown">
									<a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="material-icons text-white">person</i>
										<p class="d-lg-none d-md-block"><?php echo $this->session->userdata('super_admin_name');?></p>
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
										<div class="dropdown-divider"></div> <a class="dropdown-item" href="<?php echo base_url();?>index.php/logout-superadmin">Log out</a>
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