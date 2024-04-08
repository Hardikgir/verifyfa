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
            <h4 class="page-title">My Profile</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
            <?php foreach($profile_data as $row){ ?>
              <form action="<?php echo base_url();?>index.php/save-my-profile" method="post">
              <input type="hidden" name="user_id" value="<?php echo $row->id;?>" required="">
              <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstName" value="<?php echo $row->firstName;?>" id="firstName" class="form-control" placeholder="Enter First Name" required="">

                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastName" value="<?php echo $row->lastName;?>" id="lastName" class="form-control" placeholder="Enter Last Name" required="">
                </div>
                </div><br>
                <div class="row">
             
                <div class="col-md-6 form-row">
                    <label class="form-label">Email ID</label>
                    <input type="text" name="userEmail" id="userEmail"  value="<?php echo $row->userEmail;?>"  class="form-control" placeholder="Enter Email ID" required="" disabled>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Phone no</label>
                    <input type="number" name="phone_no" id="phone_no" value="<?php echo $row->phone_no;?>"  class="form-control" placeholder="Enter Phone Number" required="">
                </div>
                </div><br>

<div class="row">

<div class="col-md-6 form-row">
    <label class="form-label">Select Department</label>
    <select name="department_id" id="department_id" class="form-control" required="">
    <option value="">Select Department</option>
    <?php foreach($department as $deptrow){ ?>
    <option value="<?php echo $deptrow->id;?>" <?php if($deptrow->id == $row->department_id ){ echo "selected";} ?>><?php echo $deptrow->department_name;?></option>
    <?php } ?>
</select>
</div>
<div class="col-md-6 form-row">
    <label class="form-label">Designation</label>
    <input type="text" name="designation" id="designation" class="form-control" placeholder="Enter Designation" required="" value="<?php echo $row->designation;?>">
</div>
</div><br>

<div class="row">

<div class="col-md-6 form-row">
    <label class="form-label">Select Company</label>
    <select name="company_id" id="company_id" class="form-control" required>
        <option value="">Select Company</option>
        <?php foreach($company_data_list as $row_com_list){ 
            ?>
        <option value="<?php echo $row_com_list->id;?>" <?php if($row->company_id == $row_com_list->id){ echo "selected"; }?>><?php echo $row_com_list->company_name.'('. $row_com_list->short_code.')';?></option>

        <?php }?>
    </select>
</div>

<div class="col-md-6 form-row">
    <label class="form-label">Select Location</label>
    <select name="location_id" id="company_location" class="form-control" required>
<?php 
if($user_row->location_id !="0"){

   $comloc=get_location_all_user($row->company_id);
  foreach($comloc as $rowcomloc){
?>
        <option value="<?php echo $rowcomloc->id;?>" <?php if($rowcomloc->id == $row->location_id){echo "selected";}?>><?php echo $rowcomloc->location_name;?></option>
<?php } ?>
<?php } ?>
    </select>
</div>
</div><br>

                
                <div class="row">
                <div class="col-md-12 form-row">
                <button type="submit" class="btn btn-success">Update My Profile</button>
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