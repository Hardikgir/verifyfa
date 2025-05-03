<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<style>
	.modal-dialog {
    max-width: 1000px;
    margin: 1.75rem auto;
}
</style>


			<div class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header card-header-primary">
									
									<!-- MultiStep Form -->
									
									<div class="container">
										<?php 
										if(empty($projects))
										{
										?>	
										<div class="row">	
											<div class="col-md-6 col-sm-6 col-xs-6">
												<h4 class="card-title">Upload Verification Document </h4> 
											</div>	
											<div class="col-md-6  col-sm-6 col-xs-6 ">
												<form id="msform">
													<!-- progressbar -->
													<ul id="progressbar" class="text-center">
														<li class="active"></li>
														<li class=""></li>
														<li class=""></li>
														<li class=""></li>
														<!-- <p class="text-center" style="font-weight:bold; margin:0;padding: 10px;"> Entity Name: <?php echo $company_name;?> Location Name: <?php echo $company_location;?> </p> -->
													</ul>
												</form>
											</div>
										</div>
										<?php 
										}
										else
										{
										?>
										<div class="row">									
											<div class="col-md-6 col-sm-6 col-xs-6">
												<h4 class="card-title">Upload Verification Document & Allocate Resources</h4> 
											</div>	
											<div class="col-md-6  col-sm-6 col-xs-6 ">
												<form id="msform">
													<!-- progressbar -->
													<ul id="progressbar" class="text-center">
														<li class="active"></li>
														<li class=""></li>
														<li class=""></li>
														<li class=""></li>
													</ul>
												</form>
											</div>
										</div>
										<?php 
										}
										?>
										
									</div>
									<!-- MultiStep Form -->
	
								</div>
								<div class="card-body">
									<?php 
									if(!empty($projects))
									{

									?>

									<form id="uploadForm" method="POST" action="<?php echo base_url();?>index.php/plancycle/addcycle" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" id="company_name_new" name="company_name">
                                                        <option selected>Select Company Name</option>
                                                        <?php
                                                        foreach($companydata as $datanew){
															$company_row=get_company_row($datanew->company_id);
															?>
															  <option value="<?= $company_row->id; ?>"><?= $company_row->company_name; ?></option>
															<?php
														}
                                                        ?>
                                                    </select>
                                                    
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" id="company_location_new" name="company_location">
                                                       
													</select>
												</div>
											</div>
										</div>

											<div id="documentupload" style="display:none;">
												<div class="row">
													<div class="col-md-4"></div>
													<div class="col-md-4 my-4">
														<input type="file" class="fileinput" id="project_file" name="project_file">
													</div>
													<div class="col-md-4"></div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<div class="text-center">
																<button type="button"  id="continuePlan" class="btn pull-right-sec my-4">NEXT</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											
										<div class="clearfix"></div>
									</form>
									

									
									<div id="show_all_result_by_id" style="display:none;">


									</div>


									<?php 
									}
									else
									{
									?>
									<form id="uploadForm" method="POST" action="<?php echo base_url();?>index.php/plancycle/addcycle" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" id="company_name" name="company_name">
                                                        <option selected>Select Company Name</option>
                                                        <?php
                                                        foreach($companydata as $datanew){
															$company_row=get_company_row($datanew->company_id);
															?>
															  <option value="<?= $company_row->id; ?>"><?= $company_row->company_name; ?></option>
															<?php
														}
                                                        ?>
                                                    </select>
                                                    
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" id="company_location" name="company_location">
                                                       
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4"></div>
											<div class="col-md-4 my-4">
												<input type="file" class="fileinput" id="project_file" name="project_file">
											</div>
											<div class="col-md-4"></div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<div class="text-center">
														<button type="button"  id="continuePlan" class="btn pull-right-sec my-4">NEXT</button>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</form>
									
									

									<?php 
									}
									?>

									<div class="row" id="totalLineContent" style="display:none">
										<div class="col-md-12">
											<div class="text-center" style="color:red">
												<p>Important to Note : Your Current Subscription plan allows you to upload <b><?php if(!empty($payment_history)){ echo $payment_history->line_item_avaliable; } ?></b> rows of data. If more rows are needed to be uploaded, kindly upgrade your subscription plan.</p>
											</div>
										</div>
									</div>

									<div class="row" id="Downloadsample" style="display:none">
										<div class="col-md-12">
											<div class="text-center" style="border-top:1px solid #cccccc;padding:5px;">
												<a class="btn pull-right-sec my-4" href="<?php echo base_url()."sample.xlsx";?>"><i class="fa fa-file-excel"></i> Download Sample File</a>
											</div>
										</div>
									</div>

									

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- MODEL BOX-1 -->
			<div class="modal fade " id="modalPlanningForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header text-center">
							<h4 class="modal-title w-100 font-weight-bold">Confirmation before file upload </h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body mx-3">
							<form id="confirmationmodalform">
								<div class="container">
									<div class="row">
										<div class="col-md-6">
											<div class="radio pop_up_label">
												<label>
													<input type="radio" id="singleworksheet" name="worksheet" checked>Single worksheet</label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="radio pop_up_label">
												<label>
													<input type="radio" id="multipleworksheet" name="worksheet" disabled>Multiple worksheets</label>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="pop_up_label">
										<input type="checkbox" id="cnfmandatory">Confirm the MANDATORY headers have been included in the file to Upload.</label>
								</div>
								<div class="form-group">
									<label class="pop_up_label">
										<input type="checkbox" id="cnfmerge">Confirm the header line has NO merged fields.</label>
								</div>
								<div class="form-group">
									<label class="pop_up_label">
										<input type="checkbox" id="cnflines">Confirm the header line starts from 5th ROW of the worksheet.</label>
								</div>
								<div class="form-group">
									<label class="pop_up_label">
										<input type="checkbox" id="confblank">Confirm there are NO BLANK headers in the file to upload.</label>
								</div>
								<div class="form-group">
									<label class="pop_up_label">
										<input type="checkbox" id="confformat">Confirm if the data is as per the FORMAT suggested in the sample file.</label>
								</div>
						</div> <span class="text-center" class="pop_up_label"> If any of the above confirmations are invalidated, upload will not be successful.</span>
						<div class="container">
							<div class="row">
								<div class="col-md-6">
									<div class="modal-footer d-flex justify-content-center">
										<button class="btn pull-right" data-dismiss="modal">Cancel<i class="fas fa-times ml-1"></i>
										</button>
									</div>
								</div>
								<div class="col-md-6">
									<div class="modal-footer d-flex justify-content-center"> <a href="javascript:void(0)" class="btn pull-right" id="confirmUpload">Proceed<i class="fas fa-check ml-1"></i></a>
									</div>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!--seccion table--->
	</div>
	</div>

	<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none"  id="contactmodel" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold;">Add Update Contact Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <form action="<?php echo base_url();?>index.php/plancycle/save_contact_detail" method="post">
	  <input type="hidden" value="" id="contactproject_id" name="project_id">

      <div class="modal-body">
			<div class="row">
				<div class="col-md-6">
					<lable>Name</lable>
					<input type="text" name="name" id="name" class="form-control" placeholder="Enter Name Here">
					</div>
				<div class="col-md-6">
					<lable>Email</lable>
					<input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Here">
					<br></div>
				<div class="col-md-6">
					<lable>Phone Number</lable>
					<input type="number" name="phone" id="phone" class="form-control" placeholder="Enter Phone Number Here">
					</div>
				<br>
				<div class="col-md-6">
					<lable>Designation</lable>
					<input type="text" name="designation" id="designation" class="form-control" placeholder="Enter Designation Here">
					<br></div>
            </div>	
      </div>
      <div class="modal-footer text-center">
        <button type="submit" class="btn btn-primary">Save & Update</button>
      </div>
	  </form>

	  <div class="modal-body">
		<table class="table table-sm small">
		<thead class="text-center thead-dark">	
		<tr>
				
			<td>Name </td>
			<td>Email ID</td>
			<td>Phone Number</td>
			<td>Designation</td>
			<td>Action</td>
			</tr>
		</thead>
 <?php 
if(!empty($projects))
{
?>
<tbody id="contact-data"  class="text-center">
</tbody>
<?php } ?>


       </table>
     </div>

    </div>
  </div>
</div>

<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/planningscript');
$this->load->view('layouts/footer');
?>
<script>
document.getElementById('company_name').onchange = function() {
	var company_id = this.value;
	var fd = new FormData();
	fd.append('company_id',[company_id]);
	jQuery.ajax({
	  url: "<?php echo base_url();?>index.php/plancycle/getlocationdatanew",
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
<script>
document.getElementById('company_name_new').onchange = function() {
	var company_id = this.value;
	var fd = new FormData();
	fd.append('company_id',[company_id]);
	jQuery.ajax({
	  url: "<?php echo base_url();?>index.php/plancycle/getlocationdatanew",
	  type: 'POST',
	  cache: false,
	  contentType: false,
	  processData: false,
	  data: fd,
	  success: function(data) {
		// console.log(data);
		$('#company_location_new').find('option').remove().end().append(data);
	  }
	});
}

document.getElementById('company_location_new').onchange = function() {
	var location_id = this.value;
	selectElement = document.querySelector('#company_name_new');
	var company_id = selectElement.value;
	var fd = new FormData();
	fd.append('company_id',[company_id]);
	fd.append('location_id',[location_id]);
	jQuery.ajax({
	  url: "<?php echo base_url();?>index.php/plancycle/getlocationdatadata",
	  type: 'POST',
	  cache: false,
	  contentType: false,
	  processData: false,
	  data: fd,
	  success: function(data) {
		console.log(data);
		if(data == "uploaddoc"){
			var documentupload = document.getElementById("documentupload");
			documentupload.style.display = "block";
			var show_all_result_by_id = document.getElementById("show_all_result_by_id");
			show_all_result_by_id.style.display = "none";
			var Downloadsample = document.getElementById("Downloadsample");
			Downloadsample.style.display = "block";
			
			var totalLineContent = document.getElementById("totalLineContent");
			totalLineContent.style.display = "block";

			
			
		}else{
			var documentupload = document.getElementById("documentupload");
			documentupload.style.display = "none";
			var show_all_result_by_id = document.getElementById("show_all_result_by_id");
			show_all_result_by_id.style.display = "block";
			$("#show_all_result_by_id").html(data);

			var Downloadsample = document.getElementById("Downloadsample");
			Downloadsample.style.display = "block";

		}

		// $('#company_location_new').find('option').remove().end().append(data);
	  }
	});


}
</script>

<script>

function save_contact_detail(project_id) {
	$("#contactproject_id").val(project_id);
	$.post("<?php echo base_url();?>index.php/plancycle/get_contact_detail", {project_id: project_id}, function(result){
		// if(result !='0'){
			$("#contact-data").html(result);
		// var json = $.parseJSON(result);
		// $("#name").val(json[0].name);
		// $("#email").val(json[0].email);
		// $("#phone").val(json[0].phone);
		// $("#designation").val(json[0].designation);
	//   }
	  $("#contactmodel").click();
  });
}
</script>