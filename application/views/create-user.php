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
            <h4 class="page-title">Create New User</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url();?>index.php/save-admin-user" method="post" id="validate">
                
              <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstName" id="firstName" class="form-control" placeholder="Enter First Name" required="">
                    <p id="firstNamealert" style="color:red;font-weight:bold;"></p>


                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Enter Last Name" required="">
                    <p id="lastNamealert" style="color:red;font-weight:bold;"></p>

                </div>
                </div><br>
                <div class="row">
             
                <div class="col-md-6 form-row">
                    <label class="form-label">Email ID</label>
                    <input type="text" name="userEmail" id="userEmail" class="form-control" placeholder="Enter Email ID" required="">
                    <p id="emailalert" style="color:red;font-weight:bold;"></p>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Phone no</label>
                    <input type="number" name="phone_no" id="phone_no" class="form-control" placeholder="Enter Phone Number" required="">
                    <p id="phone_noalert" style="color:red;font-weight:bold;"></p>
                    
                </div>
                </div><br>

                <div class="row">
               
                <div class="col-md-6 form-row">
                    <label class="form-label">Select Department</label>
                    <select name="department_id" id="department_id" class="form-control" required="">
                    <option value="">Select Department</option>
                    <?php foreach($department as $row){ ?>
                    <option value="<?php echo $row->id;?>"><?php echo $row->department_name;?></option>
                    <?php } ?>
                    <p id="department_idalert" style="color:red;font-weight:bold;"></p>

                </select>
                
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" id="designation" class="form-control" placeholder="Enter Designation" required="">
                    <p id="designationalert" style="color:red;font-weight:bold;"></p>

                </div>
                </div><br>

                <div class="row">
               
               <div class="col-md-6 form-row">
                   <label class="form-label">Select Company</label>
                   <select name="company_id" id="company_id" class="form-control" required>
                       <option value="">Select Company</option>
                       <?php foreach($company_data_list as $row_com_list){ 
                           ?>
                       <option value="<?php echo $row_com_list->id;?>"><?php echo $row_com_list->company_name.'('. $row_com_list->short_code.')';?></option>

                       <?php }?>
                   </select>
                   <p id="company_idalert" style="color:red;font-weight:bold;"></p>

               </div>
              
               <div class="col-md-6 form-row">
                   <label class="form-label">Select Location</label>
                   <select name="location_id" id="company_location" class="form-control">
                   </select>
                   <p id="company_locationalert" style="color:red;font-weight:bold;"></p>

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
function check_validation(){
    if($("#firstName").val()==""){
      $("#firstNamealert").html("Please Enter First Name");
      $("#firstName").focus();
      return false;
    }else{
      $("#firstNamealert").html("");

    }

    if($("#lastName").val()==""){
      $("#lastNamealert").html("Please Enter Last Name");
      $("#lastName").focus();
      return false;
    }else{
            $('#lastNamealert').text('');
        }
       
    if($("#userEmail").val()==""){
      $("#emailalert").html("Please Enter Email ID");
      $("#userEmail").focus();
      return false;
    }else{
            $('#emailalert').text('');
        }

    if($("#phone_no").val()==""){
      $("#phone_noalert").html("Please Enter Phone Number");
      $("#phone_no").focus();
      return false;
    }else{
            $('#phone_noalert').text('');
        }
   
    if($("#department_id").val()==""){
      $("#department_id_alert").html("Please Select Department");
      $("#department_id").focus();
      return false;
    }else{
          $('#department_id_alert').text('');
        }

    if($("#designation").val()==""){
      $("#designationalert").html("Please Enter Designation");
      $("#department_id").focus();
      return false;
    }else{
          $('#designationalert').text('');
        }
    
    if($("#company_id").val()==""){
      $("#company_idalert").html("Please Select Company");
      $("#company_id").focus();
      return false;
    }else{
          $('#company_idalert').text('');
        }

    if($("#company_location").val()==""){
      $("#company_locationalert").html("Please Select Company");
      $("#company_location").focus();
      return false;
    }else{
          $('#company_locationalert').text('');
        }

        var userEmail = $("#userEmail").val();
        $.post("<?php echo base_url();?>index.php/check-admin-userEmail", {userEmail: userEmail}, function(result){
        if(result == 1 ){
            $("#emailalert").html("Email ID Already Exist");
            $("#userEmail").val('');
            $("#userEmail").focus();
            return false;
        }else{
            $("#validate").submit();
        }
  });
}
</script>


<script>
$("#userEmail").change(function(){
   var userEmail = $(this).val();
   $.post("<?php echo base_url();?>index.php/check-admin-userEmail", {userEmail: userEmail}, function(result){
        if(result == 1 ){
            $("#emailalert").html("Email ID Already Exist");
            $("#userEmail").val('')
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
<script>
document.getElementById('company_id').onchange = function() {
    var company_id = this.value;
    var fd = new FormData();
    fd.append('company_id',[company_id]);
    $.ajax({
      url: "<?php echo base_url();?>index.php/plancycle/getlocationdata",
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      success: function(data) {
        // console.log(data);
        $('#company_location').find('option').remove().end().append(data);
      }
    });
}
</script>