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
            <h4 class="page-title">Brodcast New Notification</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url();?>index.php/save-brodcast-message" method="post">
                
              <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Notification Type</label>
                    <select name="type" id="type" class="form-control" required="">
                        <option value="">Select Notification Type</option>
                        <option value="Message">Message</option>
                        <option value="Issue">Issue</option>
                    </select>

                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Notification Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" required="">
                </div>
                </div><br>

                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Notification Description</label>
                    <textarea  name="description" id="description" class="form-control editornew" placeholder="Enter Notification Description"></textarea>
                </div>
                </div><br>
                
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
$("#userEmail").change(function(){
   var userEmail = $(this).val();
   $.post("<?php echo base_url();?>index.php/check-admin-userEmail", {userEmail: userEmail}, function(result){
        if(result == 1 ){
            $("#emailalert").html("Email ID Already Exist");
            $("#userEmail").val('',)
        }else{
           $("#emailalert").html("");
        }
  });
});
</script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    function alertentity(){

 swal({
  title: "Your Location Create Limit Cross Kindly Connect with Super Admin!",
  text: "",
  icon: "warning",
  buttons: true,
})

}
</script>
