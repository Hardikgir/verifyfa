<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>


<link href="<?php echo base_url();?>assets/multipledropdown/multiselect.css" rel="stylesheet"/>
<script src="<?php echo base_url();?>assets/multipledropdown/multiselect.min.js"></script>
<style>
    /* example of setting the width for multiselect */
    #selectUserType_multiSelect {
        width: 100%;
    }
    .multiselect-wrapper ul li.active label{
        color: white;
    }
    /* .multiselect-wrapper ul li.active{
        background-color: #5ca1e2;
    } */

    .multiselect-count {
        background-color: #5ca1e2;
        color:white;
    }

</style>
<style>
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    width: 100% !important;
    margin: 0 !important;
}
.usertypeselection {
    margin: 15px 0px;
}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid">

		<div class="container-fluid content-new">

		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Brodcast New Notification</h4>
       </div>

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url(); ?>index.php/save-brodcast-message" method="post">

                <div class="row">

                    <div class="col-md-12 form-row SectionUserType usertypeselection" id="SectionUserType" class="">
                        <label class="form-label">UserType</label>
                        <select name="selectUserType[]" id="selectUserType" class="form-control" multiple>
                            <option value="GropAdmin">GropAdmin</option>
                            <option value="SubAdmin">SubAdmin</option>
                            <option value="EntityOwner">EntityOwner</option>
                            <option value="ProcessOwner">ProcessOwner</option>
                            <option value="Manager">Manager</option>
                            <option value="Verify">Verify</option>
                            
                        </select>
                    </div>

                    <div class="col-md-6 form-row SectionGropAdmin usertypeselection" id="SectionGropAdmin">
                        <label class="form-label">GropAdmin</label>
                        <select name="selectGropAdmin[]" id="selectGropAdmin" class="form-control" multiple>
                            <?php foreach($all_GroupAdmin as $GroupAdminkey=>$GroupAdminvalue){
                                echo '<option value="'.$GroupAdminvalue->id.'">'.$GroupAdminvalue->firstName.' '.$GroupAdminvalue->lastName.'</option>';    
                            } ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6 form-row SectionSubAdmin usertypeselection" id="SectionSubAdmin">
                        <label class="form-label">SubAdmin</label>
                        <select name="selectSubAdmin[]" id="selectSubAdmin" class="form-control" multiple>
                            <?php foreach($all_SubAdmin as $SubAdminkey=>$SubAdminvalue){
                                echo '<option value="'.$SubAdminvalue->id.'">'.$SubAdminvalue->firstName.' '.$SubAdminvalue->lastName.'</option>';    
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-6 form-row SectionEntityOwner usertypeselection" id="SectionEntityOwner">
                        <label class="form-label">EntityOwner</label>
                        <select name="selectEntityOwner[]" id="selectEntityOwner" class="form-control" multiple>
                            <?php foreach($all_EntityOwner as $EntityOwnerkey=>$EntityOwnervalue){
                                echo '<option value="'.$EntityOwnervalue->id.'">'.$EntityOwnervalue->firstName.' '.$EntityOwnervalue->lastName.'</option>';    
                            } ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6 form-row SectionProcessOwner usertypeselection" id="SectionProcessOwner">
                        <label class="form-label">ProcessOwner</label>
                        <select name="selectProcessOwner[]" id="selectProcessOwner" class="form-control" multiple>
                            <?php foreach($all_ProcessOwner as $ProcessOwnerkey=>$ProcessOwnervalue){
                                echo '<option value="'.$ProcessOwnervalue->id.'">'.$ProcessOwnervalue->firstName.' '.$ProcessOwnervalue->lastName.'</option>';    
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-6 form-row SectionManager usertypeselection" id="SectionManager">
                        <label class="form-label">Manager</label>
                        <select name="selectManager[]" id="selectManager" class="form-control" multiple>
                            <?php foreach($all_Manager as $Managerkey=>$Managervalue){
                                echo '<option value="'.$Managervalue->id.'">'.$Managervalue->firstName.' '.$Managervalue->lastName.'</option>';    
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-6 form-row SectionVerify usertypeselection" id="SectionVerify">
                        <label class="form-label">Verify</label>
                        <select name="selectVerify[]" id="selectVerify" class="form-control" multiple>
                            <?php foreach($all_Verify as $Verifykey=>$Verifyvalue){
                                echo '<option value="'.$Verifyvalue->id.'">'.$Verifyvalue->firstName.' '.$Verifyvalue->lastName.'</option>';    
                            } ?>
                        </select>
                    </div>

                </div>

                <br>
                <br>

              <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Notification Type</label>
                    <select name="type" id="type" class="form-control" required="">
                        <option value="">Select Notification Type</option>
                        <option value="Notification">Notification</option>
                        <option value="Message">Message</option>
                        <option value="Issue">Issue</option>
                    </select>
                </div>

                <div class="col-md-6 form-row">
                    <label class="form-label">Notification Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" required="">
                </div>
                </div><br>

                <div class="row">
                <div class="col-md-12 form-row">
                    <label class="form-label">Notification Description</label>
                    <textarea  name="description" id="description" class="form-control editornew" placeholder="Enter Notification Description"></textarea>
                </div>
                </div><br>

                <div class="row">
                <div class="col-md-12 form-row">
                <button type="reset" class="btn btn-danger">Cancel</button>
                <button type="submit" class="btn btn-success">Save</button>
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
$("#userEmail").change(function(){
   var userEmail = $(this).val();
   $.post("<?php echo base_url(); ?>index.php/check-admin-userEmail", {userEmail: userEmail}, function(result){
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

	document.multiselect('#selectUserType')
    .setCheckBoxClick("checkboxAll", function(target, args) {
        console.log("target")
        console.log(target)
        console.log("args")
        console.log(args)
        console.log("Checkbox 'Select All 11' was 11 clicked and got value ", args.checked);
    })
    .setCheckBoxClick("1", function(target, args) {
        console.log("target")
        console.log(target)
        console.log("args")
        console.log(args)
        console.log("Checkbox for item with value '1' 222 was clicked and got value ", args.checked);
    });



    document.multiselect('#selectGropAdmin')
    .setCheckBoxClick("checkboxAll", function(target, args) {
        console.log("Checkbox 'Select All 22' was clicked and got value ", args.checked);
    })
    .setCheckBoxClick("1", function(target, args) {
        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
    });

    document.multiselect('#selectSubAdmin')
    .setCheckBoxClick("checkboxAll", function(target, args) {
        console.log("Checkbox 'Select All 33' was clicked and got value ", args.checked);
    })
    .setCheckBoxClick("1", function(target, args) {
        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
    });

    document.multiselect('#selectEntityOwner')
    .setCheckBoxClick("checkboxAll", function(target, args) {
        console.log("Checkbox 'Select All 44' was clicked and got value ", args.checked);
    })
    .setCheckBoxClick("1", function(target, args) {
        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
    });

    document.multiselect('#selectProcessOwner')
    .setCheckBoxClick("checkboxAll", function(target, args) {
        console.log("Checkbox 'Select All 55' was clicked and got value ", args.checked);
    })
    .setCheckBoxClick("1", function(target, args) {
        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
    });

    document.multiselect('#selectManager')
    .setCheckBoxClick("checkboxAll", function(target, args) {
        console.log("Checkbox 'Select All 66' was clicked and got value ", args.checked);
    })
    .setCheckBoxClick("1", function(target, args) {
        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
    });

    document.multiselect('#selectVerify')
    .setCheckBoxClick("checkboxAll", function(target, args) {
        console.log("target")
        console.log(target)
        console.log("args")
        console.log(args)
        console.log("Checkbox 'Select All 77' was clicked and got value ", args.checked);
    })
    .setCheckBoxClick("1", function(target, args) {
        console.log("target")
        console.log(target)
        console.log("args")
        console.log(args)
        console.log("Checkbox for item with value '1' was clicked and got value ", args.checked);
    });
	
</script>
