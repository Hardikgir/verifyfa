<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
	<meta name="author" content="Creative Tim">
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<title>ADMIN SIGN IN</title>
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
	<script>
		(function(w, d, s, l, i) {
		      w[l] = w[l] || [];
		      w[l].push({
		        'gtm.start': new Date().getTime(),
		        event: 'gtm.js'
		      });
		      var f = d.getElementsByTagName(s)[0],
		        j = d.createElement(s),
		        dl = l != 'dataLayer' ? '&l=' + l : '';
		      j.async = true;
		      j.src =
		        '../../../../www.googletagmanager.com/gtm5445.html?id=' + i + dl;
		      f.parentNode.insertBefore(j, f);
		    })(window, document, 'script', 'dataLayer', 'GTM-NKDMSK6');
	</script>
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
				<div class="col-lg-7 col-md-7">
					<div class="card bg-secondary border-0 mb-0 check-use">
						<div class="card-header bg-transparent pb-5">
							<div class="text-muted text-center mt-2 mb-3">
								<h2>Verifyfa User Change Password</h2>
							</div>
							<div class="btn-wrapper text-center">
								<img src="<?php echo base_url();?>assets/img/logo.png" alt="Verify fa logo">
							</div>
						</div>
						<div class="card-body px-lg-5 py-lg-5">
							<?php 
							
							?>
							<div class="alert alert-danger" style="<?php echo $this->session->flashdata('error_message')!=''?'':'display:none;'; ?>" role="alert">
								<?php echo $this->session->flashdata('error_message'); ?>
							</div>
							<form role="form" method="post" action="<?php echo base_url();?>index.php/Login/updatePasswordFromForget">
								
								 <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">New Password</label>
                    <input type="password" name="passworf" id="password" class="form-control" placeholder="Enter New password" required="">
                </div>
                <div class="col-md-12 form-row">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password" id="cpassword" class="form-control" placeholder="Enter Confirm Password" required="">
                </div>
                <p id="checkpassalert" style="color:red;font-weight:bold;"></p>
                </div>
							
								<div class="row mt-3">
									<div class="col-6"><a href="<?php echo base_url();?>" class="text-color"><small>Login</small></a>
									</div>
									
								</div>



                                <div class="form-group mt-3">
								<b class="text-danger">Password must contain at least one number, one uppercase and a lowercase letter and a special character and at least 8 characters</b>
                                </div>
                                <div class="text-center">
									<button type="submit" id="update_password_btn" class="btn btn-primary ">Update Password</button>
								</div>
							</form>
							
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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script>  
$('#password, #cpassword').change(function() {  
  var pw1 = $("#password").val();  
  var pw2 = $("#cpassword").val();  
  if(pw2!='' && pw2!=''){
   if(pw1 != pw2)  {   
     $("#password").val('');
     $("#cpassword").val('');
     $("#checkpassalert").html("Passwords and confirm password did not match");  
	 $("#update_password_btn").prop('disabled', true);
    }else {  
     $("#checkpassalert").html("");  
	  $("#update_password_btn").prop('disabled', false);

     }  
}
})
</script>
	
	
</body>

</html>