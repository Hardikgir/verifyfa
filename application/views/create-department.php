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
            <h4 class="page-title">Create Department</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url();?>index.php/save-department-data" method="post" id="validate">
                
                <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Department Name</label>
                    <input type="text" name="department_name" id="department_name" class="form-control" placeholder="Enter Department Name" required="">
                    <p id="deptalert" style="color:red;font-weight:bold;"></p>

                </div>

                <div class="col-md-6 form-row">
                    <label class="form-label">Department Shortcode</label>
                    <input type="text" name="department_shortcode" id="short_code" class="form-control" placeholder="Enter Department Shortcode" required="">
                    <p id="shortcodealert" style="color:red;font-weight:bold;"></p>

                </div>
               
                </div><br>
               
                <div class="row">
                <div class="col-md-12 form-row">
                <button type="reset" class="btn btn-danger">Cancel</button>
                <button type="button" class="btn btn-success" onclick="return check_validation();">Save</button>
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
// $("#department_name").change(function(){
//    var department_name = $(this).val();
//    $.post("<?php echo base_url();?>index.php/check-department-name", {department_name: department_name}, function(result){
//         if(result == 1 ){
//             $("#deptalert").html("Deaprtment Already Exist");
//             $("#department_name").val('',)
//         }else{
//            $("#deptalert").html("");
//         }
//   });
// });
</script>
<script>

function check_validation(){
    if($("#department_name").val()==""){
        $("#deptalert").html("Please Enter Department Name");
        $("#department_name").val();
        $("#department_name").focus();
        return false;

    }else{
        var department_name = $("#department_name").val();
        $.post("<?php echo base_url();?>index.php/check-department-name", {department_name: department_name}, function(result){
                if(result == 1 ){
                    $("#deptalert").html("Deaprtment Already Exist");
                    $("#department_name").val('',)
                }else{
                $("#deptalert").html("");
                }
        });
    }
    if($("#short_code").val() ==""){
        $("#shortcodealert").html("Please Enter Shortcode");
        $("#short_code").focus();
        return false;
    }
    
   var department_shortcode = $("#short_code").val();
   $.post("<?php echo base_url();?>index.php/check-department-shortcode", {department_shortcode: department_shortcode}, function(result){
        if(result > 0 ){
            $("#shortcodealert").html("Shortcode Already Exist");
            $("#short_code").focus();
        }else{
            $("#validate").submit();
        }
  });
}
</script>

