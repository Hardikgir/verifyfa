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
            <h4 class="page-title">Edit Company</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
      <?php foreach($companydata as $row){
         $company_shortcode = $row->short_code;
         ?>
              <form action="<?php echo base_url();?>index.php/edit-save-entity" method="post" id="validate">
              <input type="hidden" name="company_id" id="company_id" class="form-control" required="" value="<?php echo $row->id;?>">

                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter Company Name" required="" value="<?php echo $row->company_name;?>">
                    <p id="companyalert" style="color:red;font-weight:bold;"></p>

                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Company Short Code</label>
                    <input type="text" name="short_code" id="short_code" class="form-control" placeholder="Enter Company Short Code" required="" value="<?php echo $row->short_code;?>">
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
// $("#short_code").change(function(){
//    var company_shortcode = $(this).val();
//    $.post("<?php echo base_url();?>index.php/check-company-shortcode", {company_shortcode: company_shortcode}, function(result){
//         if(result > 0 ){
//             $("#shortcodealert").html("Shortcode Already Exist");
//             $("#short_code").val('');
//         }else{
//            $("#shortcodealert").html("");
//         }
//   });

// });

function check_validation(){
    if($("#company_name").val()==""){
      $("#companyalert").html("Please Enter Company Name");
      $("#company_name").focus();
      return false;
    }

    if($("#short_code").val()==""){
      $("#shortcodealert").html("Please Enter Shortcode");
      $("#short_code").focus();
      return false;
    }
        var minLength = 3;
        var maxLength = 5;
        var char = $("#short_code").val();
        var charLength = $("#short_code").val().length;
        if(charLength < minLength){
            $('#shortcodealert').text('Length is short, minimum '+minLength+' required.');
            return false;
        }
        if(charLength > maxLength){
            $('#shortcodealert').text('Length is not valid, maximum '+maxLength+' allowed.');
            $(this).val(char.substring(0, maxLength));
            return false;
        }

var company_shortcode = $("#short_code").val();
if(company_shortcode !='<?php echo $company_shortcode?>'){

   $.post("<?php echo base_url();?>index.php/check-company-shortcode", {company_shortcode: company_shortcode}, function(result){
        if(result > 0 ){
            $("#shortcodealert").html("Shortcode Already Exist");
            $("#short_code").val('');
            $("#short_code").focus();
            
        }else{
            $("#validate").submit();
        }
  });
}else{
  $("#validate").submit();
}
}
</script>
