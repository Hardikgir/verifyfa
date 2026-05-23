<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('super-admin/layout/header');
$this->load->view('super-admin/layout/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
?>
<style>
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    width: 100% !important;
    margin: 0 !important;
    margin-bottom: 20px!important;

}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Create New Subscription</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
 <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url();?>index.php/save-subscription" method="post">
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Title<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="title" id="subscription-title" class="form-control" placeholder="Enter Subscription Title" required>
                    <p class="alert-msg" id="alertmsg-subs-title"></p>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Sub-Title<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="subtitle" class="form-control" placeholder="Enter Subscription Title" required>
                </div>
                </div>
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Breif Description</label>
                    <textarea name="description" class="editornew" placeholder="Enter Breif Description" style="width:100%"></textarea>
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Entities allowed to be added<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="allowed_entities_no" class="form-control" placeholder="Number of Entities allowed to be added" required min="1">
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Locations under each entity allowed to be added<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="location_each_entity" class="form-control" placeholder="Number of Locations under each entity allowed to be added" required min="1">
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Users associated with Registered User<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="user_number_register" class="form-control" placeholder="Number of Users associated with Registered User" required min="1">
                </div>

                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Line Items to be available for verification from a singe upload<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="line_item_avaliable" class="form-control" placeholder="Number of Line Items to be available for verification from a singe upload" required min="1">
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Highlights</label>
                    <textarea name="highlights" class="editornew1" placeholder="Enter Other Highlights:" style="width:100%;"></textarea>
                </div>
                </div>
                

                <div class="row">
                <div class="col-md-4 form-row">
                    <label class="form-label"> Validity From (Date)<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="date" name="validity_from" id="validity_from" class="form-control input-daterange" required>
                </div>

                <div class="col-md-4 form-row">
                    <label class="form-label">Validity To (Date)<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="date" name="validity_to" id="validity_to" class="form-control"  required>
                </div>

                <div class="col-md-4 form-row">
                    <label class="form-label">Subscription Type<span style="color:red;font-size: 20px;">*</span></label>
                    <select name="time_subscription" class="form-control"  required>
                        <option value="">Select Subscription Type</option>
                        <option value="days">Day</option>
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                </div>
                
          
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Amount<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="amount" class="form-control" placeholder="Subscription Amount" required>
                </div>
                </div>
                
                
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">About Payment</label>
                    <textarea name="about_payment" class="editornew4" placeholder="Enter About Payment" ></textarea>
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Terms and Conditions:</label>
                    <textarea name="terms_condition" class="editornew2" placeholder="Enter Terms and Conditions" ></textarea>
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Footnotes:</label>
                    <textarea name="foot_notes" class="editornew3" placeholder="Enter Footnotes"></textarea>
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-12 form-row">
           <a href="">
                <button type="submit" class="btn btn-success">Save</button>
             </a>
              </div>
                </div>
                
              </form>
					</div>			
				</section>
				</div>
			</div>
			<!-- Section: Testimonials v.2 -->
		
		</div>	
		
			</div>
		</div>
	</div>
	
</div>	
<?php
$this->load->view('super-admin/layout/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('super-admin/layout/footer');
?>
<script>
 $(function(){
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var minDate= year + '-' + month + '-' + day;

    $('#validity_from').attr('min', minDate);
    $('#validity_to').attr('min', minDate);

});
</script>


<script>
    $('#validity_from').change(function(){
        $('#validity_to').val("");
    if($('#validity_from').val()!=''){

    var dtToday = new Date($('#validity_from').val());

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var minDate= year + '-' + month + '-' + day;

    $('#validity_to').attr('min', minDate);
    }
});



$("#subscription-title").change(function(){
  var title = $(this).val();
  $.post("<?php echo base_url();?>index.php/check-subscrition", {title: title}, function(result){
    if(result > 0){
    $("#alertmsg-subs-title").html("Subscription Title Already Exist");
    $("#subscription-title").val("");
    }else{
        $("#alertmsg-subs-title").html("");

    }
  });
});
</script>