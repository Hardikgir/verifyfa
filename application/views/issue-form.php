<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
?>


<link href="<?php echo base_url(); ?>assets/multipledropdown/multiselect.css" rel="stylesheet"/>
<script src="<?php echo base_url(); ?>assets/multipledropdown/multiselect.min.js"></script>
<style>
    #selectUserType_multiSelect {
        width: 100%;
    }
    .multiselect-wrapper ul li.active label{
        color: white;
    }
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
            <h4 class="page-title">New Issue</h4>
       </div>

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
              <form action="<?php echo base_url(); ?>index.php/save-brodcast-message" method="post">

                <div class="row ">
                    <div class="col-md-12 form-row SectionUserType" id="SectionTypeofIssue">
                        <label class="form-label">Type of Issue</label>
                        <input type="radio" name="IssueType" class="mx-1" onchange="SelectTypeofIssue(this)" value="general"><lable class="mr-3">General Issue</lable>
                        <input type="radio" name="IssueType" class="mx-1" onchange="SelectTypeofIssue(this)" value="projectbase"><lable class="mr-3">Project Based Issue</lable>
                    </div>
                </div>

                <div class="row " id="SectionProject" style="display:none">

                    <div class="col-md-6 my-4 form-row SectionLocation" id="SectionLocation">
                        <label class="form-label">Location</label>
                        <select name="SelectLocation" id="SelectLocation" onchange="SectionLocationFun(this)" class="form-control">
                            <option>Select Location</option>
                            <?php foreach($locationdata as $locationdata_value){
                                echo '<option value="'.$locationdata_value->id.'">'.$locationdata_value->location_name.'</option>';
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-6 my-4 form-row SectionLocation" id="SectionLocation">
                        <label class="form-label">Location</label>
                        <select name="SelectLocation" id="SelectLocation" onchange="SectionLocationFun(this)" class="form-control">
                            <option>Select Location</option>
                            <?php foreach($locationdata as $locationdata_value){
                                echo '<option value="'.$locationdata_value->id.'">'.$locationdata_value->location_name.'</option>';
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-6 my-4 form-row SectionProject1" id="SectionProject1">
                        <label class="form-label">Project</label>
                        <select name="issueofproject" id="selectProject" onchange="SelectProject(this)" class="form-control">
                            <option>Select Project</option>
                            
                        </select>
                    </div>

                    <div class="col-md-6 my-4 form-row SectionManager" >
                        <label class="form-label">Reporting Person (Manager)</label>
                        <select name="selectManager" id="selectManager" class="form-control">
                            <option>Select Manager</option>
                            <?php /* foreach ($all_Manager as $Managerkey => $Managervalue) {
                                    echo '<option value="' . $Managervalue->id . '">' . $Managervalue->firstName . ' ' . $Managervalue->lastName . '</option>';
                            } */ ?>
                        </select>
                    </div>

                </div>


                <div class="row " id="SectionGropAdmin" style="display:none">
                    <div class="col-md-12 my-4 form-row SectionGropAdmin"  >
                        <label class="form-label">Reporting Person (Group Admin)</label>
                        <select name="selectGropAdmin" id="selectGropAdmin" class="form-control">
                            <option>Select Group Admin</option>
                            <?php foreach ($all_GroupAdmin as $GroupAdminkey => $GroupAdminvalue) {
                                    echo '<option value="' . $GroupAdminvalue->id . '">' . $GroupAdminvalue->firstName . ' ' . $GroupAdminvalue->lastName . '</option>';
                            }?>
                        </select>
                    </div>
                </div>



              

                <div class="row my-4">
                    <div class="col-md-12 form-row">
                        <label class="form-label">Issue Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter Title" required="">
                    </div>
                </div>


                <div class="row my-4">
                    <div class="col-md-12 form-row">
                        <label class="form-label">Issue Description</label>
                        <textarea  name="description" id="description" class="form-control editornew" placeholder="Enter Issue Description"></textarea>
                    </div>
                </div>

                <div class="row my-4">
                    <div class="col-md-12 form-row">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="title" id="title" class="form-control" placeholder="Enter Title" required="">
                    </div>
                </div>

                <div class="row">
                <div class="col-md-12 form-row">
                <button type="reset" class="btn btn-danger">Cancel</button>
                <button type="submit" class="btn btn-success">Save</button>
              </div>
                </div>


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
    function SectionLocationFun(event){
        var location_id = $(event).val();
        var fd = new FormData();
        fd.append('location_id',[location_id]);
        $.ajax({
        url: "<?php echo base_url(); ?>index.php/plancycle/getprojectbylocation",
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: fd,
        success: function(data) {
           $('#selectProject').find('option').remove().end().append(data);
        }
        });
    }


    function SelectProject(event){
        var project_id = $(event).val();
        var location_id = $("#SelectLocation").val();
        var fd = new FormData();
        fd.append('project_id',[project_id]);
        fd.append('location_id',[location_id]);
        $.ajax({
        url: "<?php echo base_url(); ?>index.php/plancycle/getreportinguserbylocation",
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: fd,
        success: function(data) {
            $('#selectManager').find('option').remove().end().append(data);
        }
        });
    }
    function SelectTypeofIssue(event){
        var TypeofIssue_value = $(event).val();
        if (TypeofIssue_value == 'general') {
            // $("#SectionManager").css('display','none')
            $("#SectionGropAdmin").css('display','block')
            $("#SectionProject").css('display','none')
        }
        else if (TypeofIssue_value == 'projectbase') {
            $("#SectionGropAdmin").css('display','none')
            // $("#SectionManager").css('display','block')
            $("#SectionProject").css('display','block')
        }
    }
</script>
