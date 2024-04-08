<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('super-admin/layout/header');
$this->load->view('super-admin/layout/sidebar');
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
            <h4 class="page-title">Edit Subscription Details</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
 <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
            <?php foreach($plan as $row) { ?>
               <form action="<?php echo base_url();?>index.php/edit-save-subscription" method="post">
               <input type="hidden" name="id" value="<?php echo $row->id;?>">
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Title<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="title" id="subscription-title" class="form-control" placeholder="Enter Subscription Title" required value="<?php echo $row->title;?>" readonly>
                    <p class="alert-msg" id="alertmsg-subs-title"></p>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Sub-Title<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="subtitle" class="form-control" placeholder="Enter Subscription Title" required  value="<?php echo $row->subtitle;?>" readonly>
                </div>
                </div><br>
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Breif Description</label>
                    <textarea name="description" class="editornew" placeholder="Enter Breif Description" style="width:100%"><?php echo $row->description;?></textarea>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Entities allowed to be added<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="allowed_entities_no" class="form-control" placeholder="Number of Entities allowed to be added" required value="<?php echo $row->allowed_entities_no;?>" min="1" readonly>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Locations under each entity allowed to be added<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="location_each_entity" class="form-control" placeholder="Number of Locations under each entity allowed to be added" required  value="<?php echo $row->location_each_entity;?>" min="1" readonly>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Users associated with Registered User<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="user_number_register" class="form-control" placeholder="Number of Users associated with Registered User" required value="<?php echo $row->user_number_register;?>" min="1" readonly>
                </div>

                <div class="col-md-6 form-row">
                    <label class="form-label">Number of Line Items to be available for verification from a singe upload<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="line_item_avaliable" class="form-control" placeholder="Number of Line Items to be available for verification from a singe upload" required value="<?php echo $row->line_item_avaliable;?>" min="1" readonly>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Highlights</label>
                    <textarea name="highlights" class="editornew1" placeholder="Enter Other Highlights:" style="width:100%;"><?php echo $row->highlights;?></textarea>
                </div>
                </div>
              

                <div class="row">
                <div class="col-md-4 form-row">
                    <label class="form-label"> Validity From (Date)<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="date" name="validity_from"  id="validity_from" class="form-control" required value="<?php echo $row->validity_from;?>" readonly>
                </div>

                <div class="col-md-4 form-row">
                    <label class="form-label">Validity To (Date)<span style="color:red;font-size: 20px;">*</span></label>
                    <input type="date" name="validity_to" id="validity_to" class="form-control"  required value="<?php echo $row->validity_to;?>">
                </div>

                <div class="col-md-4 form-row">
                    <label class="form-label">Subscription Type <span style="color:red;font-size: 20px;">*</span></label>
                    <select name="time_subscription" class="form-control"  required readonly>
                        <option value="<?php echo $row->time_subscription;?>"><?php echo $row->time_subscription;?></option>
                    </select>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Amount <span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="amount" class="form-control" placeholder="Subscription Amount" required value="<?php echo $row->amount;?>" readonly>
                </div>
                </div>
              
              <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">About Payment</label>
                    <textarea name="about_payment"  class="editornew4" class="form-control" placeholder="Enter About Payment" ><?php echo $row->about_payment;?></textarea>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Terms and Conditions:</label>
                    <textarea name="terms_condition" class="editornew2" placeholder="Enter Terms and Conditions" ><?php echo $row->terms_condition;?></textarea>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Footnotes:</label>
                    <textarea name="foot_notes" class="editornew3" placeholder="Enter Footnotes"><?php echo $row->foot_notes;?></textarea>
                </div>
                </div>
              
                <div class="row">
                <div class="col-md-12 form-row">
                <button type="submit" class="btn btn-success">Update</button>
              </div>
                </div>
              
              </form>
              <?php } ?>
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