<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
	<meta name="author" content="Creative Tim">
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<title><?php echo $title;?></title>
	<meta property="og:site_name" content="Creative Tim" />
	<!-- Favicon -->
	<link rel="icon" href="<?php echo base_url();?>assets/img/brand/favicon.png" type="image/png">
	<!-- Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
	<!-- Icons -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/nucleo.css" type="text/css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/all.min.css" type="text/css">
	<!-- Argon CSS -->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/argon.min9f1e.css?v=1.1.0" type="text/css">
	<!-- Google Tag Manager -->
</head>

<body class="bg-default">
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
	<!-- Main content -->
	<div class="main-content">
		<!-- Header -->
		<!-- Page content -->
		<div class="container form-top">
			<div class="row justify-content-center">
				<div class="col-lg-5 col-md-7">
					<div class="card bg-secondary border-0 mb-0 check-use">
						<div class="card-header bg-transparent pb-5">
							<div class="text-muted text-center mt-2 mb-3">
							</div>
							<div class="btn-wrapper text-center">
								<img src="<?php echo base_url();?>assets/img/logo.png" alt="Verify fa logo">
							</div>
						</div>
						<div class="card-body px-lg-5 py-lg-5">

							<div class="alert alert-sucess" style="<?php echo $this->session->flashdata('transfermsg')!=''?'':'display:none;'; ?>" role="alert">
								<?php echo $this->session->flashdata('transfermsg'); ?>
							</div>
							
                            <div class="alert alert-sucess"  role="alert" style="text-align: center;font-size: 23px;padding: 0;color: #5ca1e2;font-weight: bold;">
								Account Transfered Successfully Done Please Login Again
							</div>
								<div class="text-center">
                                <a href="<?php echo base_url();?>index.php/registered-user-login">
                                 <button type="button" class="btn btn-success ">Login Now</button>
                                </a>
								</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Core -->
	<script src="<?php echo base_url();?>assets/vendor/jquery/dist/jquery.min.js"></script>
	<script src="<?php echo base_url();?>assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url();?>assets/vendor/js-cookie/js.cookie.js"></script>
	<script src="<?php echo base_url();?>assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
	<script src="<?php echo base_url();?>assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
	<!-- Argon JS -->
	<script src="<?php echo base_url();?>assets/js/argon.min9f1e.js?v=1.1.0"></script>
	<!-- Demo JS - remove this in your project -->
	<script src="<?php echo base_url();?>assets/js/demo.min.js"></script>
	
	
</body>

</html>