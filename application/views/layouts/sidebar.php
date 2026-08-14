<div class="sidebar" data-color="purple" data-background-color="white" data-image="<?php echo base_url();?>assets/img/sidebar-1.jpg">
			<div class="logo">
				<a href="#" class="simple-text logo-normal">
					<img src="<?php echo base_url();?>assets/img/logo.png" alt="Verify fa logo">
				</a>
			</div>
			<div class="sidebar-wrapper">
				<ul class="nav">
					<li class="nav-item <?php echo $page_title=='Dashboard'?'active':'';?>  ">
						<a class="nav-link" href="<?php echo base_url();?>index.php/dashboard"> <i class="material-icons">dashboard</i>
							<p>Dashboard</p>
						</a>
					</li>
					<li class="nav-item <?php echo $page_title=='Plan Cycle'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/plancycle"> <i class="fas fa-chart-pie"></i>
							<p>Plan Cycle</p>
						</a>
					</li>
					<li class="nav-item <?php echo $page_title=='Reports'?'active':'';?>">
						<a class="nav-link" href="<?php echo base_url();?>index.php/dashboard/reports"> <i class="far fa-file"></i>
							<p>Report</p>
						</a>
					</li>
					<li class="nav-item <?php echo $page_title=='Exceptions'?'active':'';?>"">
						<a class="nav-link" href="<?php echo base_url();?>index.php/dashboard/exceptions"> <i class="material-icons">Exceptions</i>
							<p>Exceptions</p>
						</a>
					</li>
					<li class="nav-item ">
						<a class="nav-link" href="#"> <i class="fas fa-hands-helping"></i>
							<p>Helpdesk</p>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="main-panel">
			<!-- Navbar -->
			<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
				<div class="container-fluid">
					<div class="navbar-wrapper"> <a class="navbar-brand text-white" href="#pablo"><?php echo get_CompanyName($this->company_id); ?></a>
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
										<p class="d-lg-none d-md-block">Account</p>
									</a>
									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile"> <a class="dropdown-item" href="<?php echo base_url();?>"><?php echo $_SESSION['logged_in']['name'];?><br/>(<?php if(isset($this->user_type) && $this->user_type!=''){echo $this->user_type==1?'Verifier':($this->user_type==0?'Manager':($this->user_type==2?'Process Owner':($this->user_type==3?'Item Owner':'Admin')));}else{echo 'Admin';} ?>)</a>
										<a class="dropdown-item" href="#">Change Role</a>
										<div class="dropdown-divider"></div> <a class="dropdown-item" href="<?php echo base_url();?>index.php/login/logout">Log out</a>
									</div>
								</li>
							</li>	
						</ul>
					</div>
				</div>
			</nav>
			<!-- End Navbar -->