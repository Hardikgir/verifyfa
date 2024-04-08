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
            <h4 class="page-title">Edit User</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
          <?php foreach($user as $user_row){?>
              <form id="validate" action="<?php echo base_url();?>index.php/edit-admin-user-save" method="post">
                <input type="hidden" name="user_id" value="<?php echo $user_row->id;?>" required>
              <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstName" id="firstName" class="form-control" placeholder="Enter First Name" required="" value="<?php echo $user_row->firstName;?>">
                    <p id="firstNamealert" style="color:red;font-weight:bold;"></p>


                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Enter Last Name" required="" value="<?php echo $user_row->lastName;?>">
                    <p id="lastNamealert" style="color:red;font-weight:bold;"></p>

                </div>
                </div><br>
                <div class="row">
             
                <div class="col-md-6 form-row">
                    <label class="form-label">Email ID</label>
                    <input type="text" name="userEmail" id="userEmail" class="form-control" placeholder="Enter Email ID" required="" value="<?php echo $user_row->userEmail;?>">
                    <p id="emailalert" style="color:red;font-weight:bold;"></p>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Phone no</label>
                    <input type="number" name="phone_no" id="phone_no" class="form-control" placeholder="Enter Phone Number" required="" value="<?php echo $user_row->phone_no;?>">
                    <p id="phone_noalert" style="color:red;font-weight:bold;"></p>

                </div>
                </div><br>

                <div class="row">
               
                <div class="col-md-6 form-row">
                    <label class="form-label">Select Department</label>
                    <select name="department_id" id="department_id" class="form-control" required="">
                    <option value="">Select Department</option>
                    <?php foreach($department as $row){ ?>
                    <option value="<?php echo $row->id;?>" <?php if($row->id==$user_row->department_id ){ echo "selected";} ?>><?php echo $row->department_name;?></option>
                    <?php } ?>
                </select>
                <p id="department_idalert" style="color:red;font-weight:bold;"></p>

                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" id="designation" class="form-control" placeholder="Enter Designation" required="" value="<?php echo $user_row->designation;?>">
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
                        <option value="<?php echo $row_com_list->id;?>" <?php if($user_row->company_id == $row_com_list->id){ echo "selected"; }?>><?php echo $row_com_list->company_name.'('. $row_com_list->short_code.')';?></option>

                        <?php }?>
                    </select>
                   <p id="company_idalert" style="color:red;font-weight:bold;"></p>

                </div>
               
                <div class="col-md-6 form-row">
                    <label class="form-label">Select Location</label>
                    <select name="location_id" id="company_location" class="form-control" required>
             <?php 
                if($user_row->location_id !="0"){
                
                   $comloc=get_location_all_user($user_row->company_id);
                  foreach($comloc as $rowcomloc){
                ?>
                        <option value="<?php echo $rowcomloc->id;?>" <?php if($rowcomloc->id == $user_row->location_id){echo "selected";}?>><?php echo $rowcomloc->location_name;?></option>
                <?php } ?>
                <?php } ?>
                    </select>
                   <p id="company_locationalert" style="color:red;font-weight:bold;"></p>

                </div>
                </div><br>
                <!-- <div class="row">
                    <div class="col-md-12">
                    <table class="display dataTable no-footer" style="width: 100%;" role="grid">
                    <thead>
                        <tr>
                        <th>Company Name/Shortcode</th>
                        <th>Location Name/Shortcode</th>
                        <th>Role</th>
                    </tr>
                    </thead>
                    <tbody>
       <?php foreach($user_role as $row){
              
                $company_id=$row->company_id;
                $company_row=get_company_row($company_id);
                $location_id=$row->location_id;
                $location_row= get_location_row($location_id);
                $user_role=$row->user_role;
                $user_role_exp=explode(",",$user_role);

                ?>
                        <tr>
                        <td><?php echo $company_row->company_name;?>/<?php echo $company_row->short_code;?></td>
                        <td><?php echo $location_row->location_name;?>/<?php echo $location_row->location_shortcode;?></td>
                        <td>
<?php 
     if (in_array("5", $user_role_exp))
      {
      echo "Admin, ";
      }
    if (in_array("0", $user_role_exp))
      {
      echo "Manager, ";
      }

      if (in_array("2", $user_role_exp))
      {
      echo "Process Owner, ";
      }
      if (in_array("3", $user_role_exp))
      {
      echo "Entity Owner, ";
      }
      if (in_array("1", $user_role_exp))
      {
      echo "VERIFIER";
      }
  ?>
  </td>
                    </tr>
 <?php } ?>
                    </tbody>
                    </table>
                    </div>
                </div> -->
                <div class="row">
                <div class="col-md-12 form-row">
                <button type="button" class="btn btn-success" onclick="return check_validation();">Save</button>
              </div>
                </div>
                <br>
              
					</div></form>	
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
        if(result > '1'){
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