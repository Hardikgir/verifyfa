<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<style>
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    width: 100% !important;
    margin: 0 !important;
}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid">
	
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Change Your Password</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url();?>index.php/admin-user-passwod-save" method="post">
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_pass" id="current_pass" class="form-control" placeholder="Enter Current password" required="">
                    <p id="oldpasswordalert" style="color:red;font-weight:bold;"></p>
                </div>
              
                </div><br>

                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">New Password</label>
                    <input type="password" name="passworf" id="password" class="form-control" placeholder="Enter New password" required="">
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password" id="cpassword" class="form-control" placeholder="Enter Confirm Password" required="">
                </div>
                <p id="checkpassalert" style="color:red;font-weight:bold;"></p>
                </div><br>

                <b class="text-danger text-left ml-2">Password must contain at least one number, one uppercase and a lowercase letter and a special character and at least 8 characters</b>
               
                <div class="row">
                <div class="col-md-12 form-row">
                <button type="reset" class="btn btn-danger">Cancel</button>
                <button type="submit" class="btn btn-success">Save</button>
              </div>
                </div>
                <br>
              
					</div></form>			
				</div></section>
				</div>
			</div>
			<!-- Section: Testimonials v.2 -->
		
		</div>
			<!-- Section: Testimonials v.2 -->
		
		</div>	
		
			</div>
		</div>
	</div>
	
</div>	
<?php
$this->load->view('layouts/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('layouts/footer');
?>

<script>
$("#current_pass").change(function(){
   var current_pass = $(this).val();
   $.post("<?php echo base_url();?>index.php/admin-user-check-pass", {current_pass: current_pass}, function(result){
        if(result== 0 ){
            $("#oldpasswordalert").html("incorrect Current Password");
            $("#current_pass").val('',)
        }else{
           $("#oldpasswordalert").html("");
        }
  });

});
</script>

<script>  
$('#password, #cpassword').change(function() {  
  var pw1 = $("#password").val();  
  var pw2 = $("#cpassword").val();  
  if(pw2!='' && pw2!=''){
   if(pw1 != pw2)  {   
     $("#password").val('');
     $("#cpassword").val('');
     $("#checkpassalert").html("Passwords and confirm password did not match");  
    }else {  
     $("#checkpassalert").html("");  

     }  
}
})
</script>
