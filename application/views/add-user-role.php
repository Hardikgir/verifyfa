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
            <h4 class="page-title">Add User Role</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url();?>index.php/assign-user-role-save" method="post">
                
    <?php 
    // if(($user_role_addmin_cnt > 0)){
       ?>      
      
                <div class="row">
                <div class="col-md-4 form-row">
                    <label class="form-label">Select User </label>
                    <select name="user_id" id="user_id" class="form-control my-select"  data-live-search="true"  required="">
                    <option value="">Select User</option>
                    <?php
                    if(($user_role_addmin_cnt > 0)){
                    foreach($user as $row){ ?>
                    <option value="<?php echo $row->id;?>"><?php echo $row->firstName.' '.$row->lastName;?></option>
                      <?php } }else{ 
                         $company_id_n =get_user_role_company($this->user_id);
                         $location_id_n =get_user_role_location($this->user_id);
                         $user_row=get_user_role_row($company_id_n,$location_id_n);
                         foreach($user_row as $row){ ?>
                        ?>
                        <option value="<?php echo $row->id;?>"><?php echo $row->firstName.' '.$row->lastName;?></option>

                        
                        <?php }
                      } ?>
                </select>
                </div>
                 <div class="col-md-4 form-row">
                    <label class="form-label">Select Company</label>
                    <select name="company_id" id="company_id" class="form-control my-select"  data-live-search="true"  required="">
                    <option value="">Select Company</option>
                    <?php foreach($comapny as $row){ ?>
                    <option value="<?php echo $row->id;?>"><?php echo $row->company_name.'('.$row->short_code.')';?></option>
                    <?php } ?>
                </select>
                </div>

                <div class="col-md-4 form-row">
                    <label class="form-label">Select Location</label>
                  <select name="location_id" id="location_id" class="form-control my-select"  data-live-search="true"  required="">
                    <option value="">Select Location</option>
                  </select>
                </div>
                </div><br>
<?php 
// else{ 
    // $company_id_n =get_user_role_company_all($this->user_id);
    // $location_id_n =get_user_role_location($this->user_id);

  ?>

  <!-- <div class="row">
              <div class="col-md-4 form-row">
                    <label class="form-label">Select Company</label>
                    <select name="company_id" id="company_id_sub_admin" class="form-control my-select"  data-live-search="true"  required="">
                    <option value="">Select Company</option>
                    <?php 
                    // foreach($company_id_n as $row){ 
                    //    $company_row=get_company_row($row->company_id); 

                      ?>
                    <?php 
                  // } ?>
                </select>
                </div>
                
                <div class="col-md-4 form-row">
                    <label class="form-label">Select Location</label>
                  <select name="location_id" id="location_id" class="form-control my-select"  data-live-search="true"  required="">
                    <option value="">Select Location</option>
                  </select>
                </div>


                <div class="col-md-4 form-row">
                    <label class="form-label">Select User</label>
                    <select name="user_id" id="user_id" class="form-control my-select"  data-live-search="true"  required="">
                    <option value="">Select User</option>
                </select>
                </div>
                 

               
                </div><br> -->

<?php 
// } ?>


                
<div id="userrow"></div>
<div class="row">
<div class="col-md-12 form-row">
<label class="form-label">Select Role</label>
</div>
<?php if(($user_role_addmin_cnt > 0)){ ?>
<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" id="admin" value="5" name="user_role[]">
  <label class="form-check-label" for="admin">
    Group Admin
  </label>
</div>
</div>

<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" class="newcheck" id="subadmin" value="4" name="user_role[]">
  <label class="form-check-label" for="subadmin">
    Sub Admin
  </label>
</div>
</div>
<?php } ?>

<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" class="newcheck" id="Manager" value="0"  name="user_role[]">
  <label class="form-check-label" for="Manager">
  Manager
  </label>
</div>
 </div>
<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" class="newcheck" id="Processowener"  value="2"  name="user_role[]">
  <label class="form-check-label" for="Processowener">
  Process Owner
  </label>
</div>
</div>
<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" class="newcheck" id="entityowner"  value="3"  name="user_role[]">
  <label class="form-check-label" for="entityowner">
  Entity Owner
  </label>
</div>
</div>
<div class="col-md-2 form-row">
 <div class="form-check">
  <input type="checkbox" class="newcheck" id="verifyer" value="1"  name="user_role[]">
  <label class="form-check-label" for="verifyer">
  Verifier
  </label>
</div>
</div>
</div>
    </div><br>
    <div class="row">
    <div class="col-md-12 form-row">
    <button type="submit" class="btn btn-success">Assign User</button>
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
$("#company_id").change(function(){
   var company_id = $(this).val();
   $.post("<?php echo base_url();?>index.php/get-company-location", {company_id: company_id}, function(result){

       $("#location_id").html(result);
        $('.my-select').selectpicker();
$('.my-select').selectpicker('refresh')
  });

});
<?php if(($user_role_addmin_cnt <= 0)){ ?>   

$("#company_id_sub_admin").change(function(){
   var company_id = $(this).val();
   var location_id ='<?php echo $location_id_n;?>';
   $.post("<?php echo base_url();?>index.php/get-company-location-sub-admin", {company_id: company_id,location_id:location_id}, function(result){

       $("#location_id").html(result);
        $('.my-select').selectpicker();
$('.my-select').selectpicker('refresh')
  });

});
<?php } ?>
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
$("#user_id, #company_id, #location_id").change(function(){
   var user_id = $("#user_id").val();
   var company_id = $("#company_id").val();
   var location_id = $("#location_id").val();
   if(user_id !='' && company_id!='' && location_id!=''){
   $.post("<?php echo base_url();?>index.php/get-role", {user_id: user_id,company_id:company_id,location_id:location_id}, function(result){
    result1=JSON.parse(result);
    $.each(result1, function(i, item) {
        var role =item.user_role;
        var main_data = role.split(",");
        if(main_data.includes('0')){  $("#Manager").prop('checked', true); }else{ $("#Manager").prop('checked', false)}
        if(main_data.includes('1')){  $("#verifyer").prop('checked', true); }else{ $("#verifyer").prop('checked', false)}
        if(main_data.includes('2')){  $("#Processowener").prop('checked', true); }else{ $("#Processowener").prop('checked', false)}
        if(main_data.includes('3')){  $("#entityowner").prop('checked', true); }else{ $("#entityowner").prop('checked', false)}
        if(main_data.includes('4')){  $("#subadmin").prop('checked', true); }else{ $("#subadmin").prop('checked', false)}
        if(main_data.includes('5')){  $("#admin").prop('checked', true); }else{ $("#admin").prop('checked', false)}
        console.log(main_data);
     });
  });
   }

});
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
      $("#company_id").html('<option value="">Select Company</option><?php foreach($comapny as $row){ ?><option value="<?php echo $row->id;?>"><?php echo $row->company_name."(".$row->short_code.")";?></option><?php } ?>');

      $("#location_id").html('<option value="" class="other">Select Location</option>');
     

      $('.my-select').selectpicker('refresh')

    
  }

})
  </script>