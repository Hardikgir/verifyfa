<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('registered-user/layout/header');
$this->load->view('registered-user/layout/sidebar');
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
            <h4 class="page-title">Transfer My Account</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url();?>index.php/transfer-account-save" method="post" id="transfer_form">
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">My Email</label>
                    <input type="email" name="current_email" id="current_email" class="form-control" placeholder="My Current Email" required="" readonly value="<?php echo $this->session->userdata("registered_user_email");?>">
                </div>
              
                </div><br>

                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Transfer Account Email:</label>
                    <input type="email" name="transfer_email" id="transfer_email" class="form-control" placeholder="Enter Transfer Email ID" required>
                </div>
                </div><br>
               
                <div class="row">
                <div class="col-md-12 form-row">
                <button type="reset" class="btn btn-danger">Cancel</button>
                <button type="button" class="btn btn-success" onclick="transfer_submission()">Transfer Now</button>
                <button type="submit" class="btn btn-success" id="successtransfer" style="display:none">save</button>
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
$this->load->view('registered-user/layout/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('registered-user/layout/footer');
?>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
       
        function transfer_submission(){
            if($("#transfer_email").val()==""){
            $("#successtransfer").trigger("click");
        }else{
          swal({
            title: "Are you sure?",
            text: "Transfer Your Account to given Email-ID",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
            //   $("#transfer_form").submit();
            $("#successtransfer").trigger("click");
            } else {
              swal("Your data is safe!");
            }
          });
        }
          }
    </script>

