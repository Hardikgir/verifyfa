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
            <h4 class="page-title">Edit Company Location</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
    <!-- Carousel Wrapper -->
    <?php foreach($location_data as $row_loc){ 
        $location_shortcode=$row_loc->location_shortcode;
        ?>
              <form action="<?php echo base_url();?>index.php/edit-save-location" method="post" id="validate">
                <input type="hidden" name="location_id" value="<?php echo $row_loc->id;?>" required>
              <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Select Company</label>
                    <select name="company_id" id="company_id" class="form-control" required="">
                    <option value="">Select Comapny</option>
                    <?php foreach($company_data as $row){ ?>
                    <option value="<?php echo $row->id;?>" <?php if( $row_loc->company_id == $row->id){ echo "selected";}?>><?php echo $row->company_name;?>(<?php echo $row->short_code;?>)</option>
                    <?php } ?>
                    </select>
                    <p id="companyalert" style="color:red;font-weight:bold;"></p>

                </div>
              
                </div><br>
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Location Name</label>
                    <input type="text" name="location_name" id="location_name" class="form-control" placeholder="Enter Location Name" required="" value="<?php echo $row_loc->location_name;?>">
                    <p id="namealert" style="color:red;font-weight:bold;"></p>

                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Location Short Code</label>
                    <input type="text" name="location_shortcode" id="location_shortcode" class="form-control" placeholder="Enter Location Short Code" required="" value="<?php echo $row_loc->location_shortcode;?>">
                    <p id="shortcodealert" style="color:red;font-weight:bold;"></p>
                </div>
                </div><br>
               
                <div class="row">
                <div class="col-md-12 form-row">
                <button type="button" class="btn btn-success" onclick="return check_validation();">Save</button>
              </div>
                </div>
                <br>
              </div>
           </form>
           <?php } ?>	
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
// $("#location_shortcode").change(function(){
//    var location_shortcode = $(this).val();
//    $.post("<?php echo base_url();?>index.php/check-location-shortcode", {location_shortcode: location_shortcode}, function(result){
//         if(result > 0 ){
//             $("#shortcodealert").html("Shortcode Already Exist");
//             $("#location_shortcode").val('',)
//         }else{
//            $("#shortcodealert").html("");
//         }
//   });

// });


function check_validation(){
var location_shortcode = $("#location_shortcode").val();
var company_id = $("#company_id").val();

if($("#company_id").val()==''){
    $("#companyalert").html("Please Select Company");
    $("#company_id").focus();
    return false;
}
if($("#location_name").val()==''){
    $("#namealert").html("Please Enter Name");
    $("#namealert").focus();
    return false;
}
if(location_shortcode ==''){
    $("#shortcodealert").html("Please Enter Shortcode");
    $("#location_shortcode").focus();
    return false;
}else{
    if(location_shortcode !='<?php echo $location_shortcode?>'){

   $.post("<?php echo base_url();?>index.php/check-location-shortcode", {location_shortcode: location_shortcode,company_id:company_id}, function(result){
        if(result > 0 ){
            $("#shortcodealert").html("Shortcode Already Exist");
            // $("#location_shortcode").val('',)
        }else{
            $("#validate").submit();
        }
  });
}else{
    $("#validate").submit();

}
}
}
</script>
