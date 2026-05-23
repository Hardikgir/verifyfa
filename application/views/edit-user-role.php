<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">



<style>
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    width: 100% !important;
    margin: 0 !important;
}
.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
    color: #999;
    background: #fff;
    margin-bottom: 0;
}
</style>
<?php 
$user_id=$this->user_id;
$entity_code=$this->admin_registered_entity_code;
$user_role_addmin_cnt=get_user_role_cnt_admin($user_id,$entity_code);
?>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid">
	
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Edit User Role</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
            <?php foreach($role_data as $row_role){
                 $userid=$row_role->user_id;
                $userrow= get_user_row($userid);
                $company_id=$row_role->company_id;
                $company_row=get_company_row($company_id);
                $location_id=$row_role->location_id;
                $location_row= get_location_row($location_id);
                $user_role=$row_role->user_role;
                $user_role_exp=explode(",",$user_role);
              ?>
              <form action="<?php echo base_url();?>index.php/edit-user-role-save" method="post">
                
             <input type="hidden" name="role_id" value="<?php echo $row_role->id;?>">
                <div class="row">
                <div class="col-md-4 form-row">
                    <label class="form-label">Select User</label>
                    <select name="user_id" id="user_id" class="form-control my-select" required="" readonly="">
                    <option value="<?php echo $userrow->id;?>"><?php echo $userrow->firstName.' '.$userrow->lastName;?></option>
                </select>
                </div>
                 <div class="col-md-4 form-row">
                    <label class="form-label">Select Company</label>
                    <select name="company_id" id="company_id" class="form-control my-select"  required="" readonly="">
                    <?php if($row_role->company_id=='0'){
                      echo '<option value="0">All</option>';
                    }else{?>
                    <option value="<?php echo $company_row->id;?>" class="other"><?php echo $company_row->company_name.'('.$company_row->short_code.')';?></option>
                    <?php } ?>
                </select>
                </div>

                <div class="col-md-4 form-row">
                    <label class="form-label">Select Location</label>
                  <select name="location_id" id="location_id" class="form-control my-select"  readonly required="">
                  <?php if($row_role->location_id=='0'){
                      echo '<option value="0">All</option>';
                    }else{?>

                    <option value="<?php echo $location_row->id;?>"><?php echo $location_row->location_name.'('.$location_row->location_shortcode.')';?></option>
                    <?php } ?>
                  </select>
                </div>
                </div><br>
<div id="userrow"></div>
<div class="row">
<div class="col-md-12 form-row">
<label class="form-label">Select Role</label>
</div>
<?php if(($user_role_addmin_cnt > 0) ){ ?>

<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" id="admin" value="5" name="user_role[]" <?php if (in_array("5", $user_role_exp)){ echo "checked"; } ?>>
  <label class="form-check-label" for="admin">
    Group Admin
  </label>
</div>
</div>
<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" class="newcheck" id="subadmin" value="4" name="user_role[]" <?php if (in_array("4", $user_role_exp)){ echo "checked"; } ?> <?php if (in_array("5", $user_role_exp)){ echo "disabled"; } ?>> 
  <label class="form-check-label" for="subadmin">
    Sub Admin
  </label>
</div>
</div>
<?php } ?>


<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" id="Manager" class="newcheck" value="0"  name="user_role[]" <?php if (in_array("0", $user_role_exp)){ echo "checked"; } ?> <?php if (in_array("5", $user_role_exp)){ echo "disabled"; } ?>>
  <label class="form-check-label" for="Manager">
  Manager
  </label>
</div>
 </div>
<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" id="Processowener" class="newcheck" value="2"  name="user_role[]" <?php if (in_array("2", $user_role_exp)){ echo "checked"; } ?> <?php if (in_array("5", $user_role_exp)){ echo "disabled"; } ?>>
  <label class="form-check-label" for="Processowener">
  Process Owner
  </label>
</div>
</div>
<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" id="entityowner" class="newcheck" value="3"  name="user_role[]" <?php if (in_array("3", $user_role_exp)){ echo "checked"; } ?> <?php if (in_array("5", $user_role_exp)){ echo "disabled"; } ?>>
  <label class="form-check-label" for="entityowner">
  Entity Owner
  </label>
</div>
</div>
<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" id="verifyer" value="1" class="newcheck" name="user_role[]" <?php if (in_array("1", $user_role_exp)){ echo "checked"; } ?> <?php if (in_array("5", $user_role_exp)){ echo "disabled"; } ?>>
  <label class="form-check-label" for="verifyer">
  Verifier
  </label>
</div>
</div>
</div>
    </div><br>
    <div class="row">
    <div class="col-md-12 form-row">
    <button type="submit" class="btn btn-success">Update User Role</button>
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
$("#company_id").change(function(){
   var company_id = $(this).val();
   $.post("<?php echo base_url();?>index.php/get-company-location", {company_id: company_id}, function(result){

       $("#location_id").html(result);
        $('.my-select').selectpicker();
$('.my-select').selectpicker('refresh')
  });

});
</script>

<script>
$("#user_id").change(function(){
   var user_id = $(this).val();
   $.post("<?php echo base_url();?>index.php/get-user_detail", {user_id: user_id}, function(result){
       $("#userrow").html(result);
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

$('.my-select').selectpicker();

</script>

<script>
  $("#admin").click(function(){

  
if ($(this).prop('checked')==true){ 
      $(".newcheck").prop("disabled","true");
      $("#company_id").html('<option value="0" class="other">All</option>');
      $("#location_id").html('<option value="0" class="other">All</option>');
      $('.my-select').selectpicker('refresh')

  }else{
    $(".newcheck").removeAttr("disabled");

      $(".all").css("display","none");
      $("#company_id").html('<option value="<?php echo $company_row->id;?>" class="other"><?php echo $company_row->company_name.'('.$company_row->short_code.')';?></option>');
      $("#location_id").html(' <option value="<?php echo $location_row->id;?>"><?php echo $location_row->location_name.'('.$location_row->location_shortcode.')';?></option>');

     

      $('.my-select').selectpicker('refresh');

    
  }

})
  </script>