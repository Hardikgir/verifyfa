<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');

?>
	<div class="content">
				<div class="container-fluid">
				<form action="<?php echo base_url();?>index.php/dashboard/reports" method="post" class="bg-white">
                <br>
                <div class="row">
                <div class="col-md-2 form-row">
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">Select Company</label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        <option value="">Select Company</option>

                        <?php foreach($company_data_list as $row_com_list){
							$selected = ''; 
								if(isset($_POST['company_id']) && $_POST['company_id'] == $row_com_list->company_id){
									$selected = 'selected';
								}
                             $company_n=get_company_row($row_com_list->company_id);
                            ?>
                        <option value="<?php echo $company_n->id;?>" <?php echo $selected; ?>><?php echo $company_n->company_name.'('. $company_n->short_code.')';?></option>
						

                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">Select Location</label>
                    <select name="location_id" id="company_location" class="form-control">
                        <option value="">Select Location</option>
						<?php
						if(!empty($location_data_list)){
							foreach($location_data_list as $row_loc_list){

							$locrow=$this->plancycle->get_locrow($row_loc_list->location_id);
							$check_location_assigned=check_location_assigned($company_id,$row_loc_list->location_id,$user_id);
								
							$selected = ''; 
								if(isset($_POST['location_id']) && $_POST['location_id'] == $row_loc_list->location_id){
									$selected = 'selected';
								}
							 $company_n=get_company_row($row_loc_list->company_id);
							?>
						<option value="<?php echo $locrow->id;?>" <?php echo $selected; ?>><?php echo $locrow->location_name;?></option>	
						<?php } 
						}
						?>
                    </select>
					 
                </div>
                <div class="col-md-2 form-row">
                <button type="submit" class="btn btn-success">GO</button>
              </div>
                </div><br>
               
                
                <br>
        </form>
					<div class="row">
					
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<?php if(isset($_SESSION['error_message']))
							{
							?>
							<div class="alert alert-danger">
								<?php echo $_SESSION['error_message']['message']; ?>
							</div>
							<?php 
							}
							?>
							<?php
										if(count($projects)>0)
										{
										?>
							<div class="card">
								
								<div class="card-header card-header-primary ">
									<h4 class="card-title">Reports </h4> 
								</div>
								<form action="<?php echo base_url(); ?>index.php/dashboard/generateReport" method="POST">
									<div class="card-body">
									<input type="hidden" value="<?php echo $_POST['company_id'];?>" name="company_id">
									<input type="hidden" value="<?php echo $_POST['location_id'];?>" name="location_id">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="project" class="optradio" checked> Project based </label></span>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="consolidated" class="text-center optradio"> Consolidated </label></span>
													</div>
												</div>
											</div>
										</div>
										<div class="row my-3">
											<div class="col-md-12">
												<div class="form-group">
													<select class="browser-default custom-select" name="projectSelect" id="projectSelect" required>
														<option value="" selected>Project ID & Name<span id="mandatory_star">*</span>
														</option>
														<?php
														foreach($projects as $project)
														{

														
														?>
														<option value="<?php echo $project->id;?>"><?php echo $project->project_name.' ('.$project->project_id.')';?></option>
														<?php
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="row my-3">
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" name="reporttype" id="reporttype" required>
														<option value="" selected>Report Type <span id="mandatory_star">*</span>
														</option>
														<option value="1">Scope Summary & Detailed Report</option>
														<!-- <option value="2">Addtional Detail</option> -->
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" name="projectstatus" id="projectstatus" required>
														<option value="" selected>Project Status <span id="mandatory_star">*</span>
														</option>
														<option value="0">In Process</option>
														<option value="3">Finished Verification</option>
														<option value="1">Completed</option>
														<option value="2">Cancelled</option>
													</select>
													
												</div>
											</div>
										</div>
										<div class="row my-3">
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" name="verificationstatus" id="verificationstatus" required>
														<option value="" selected>Verification Status<span id="mandatory_star">*</span>
														</option>
														<option value="1">All</option>
														<option value="Verified">Verified</option>
														<option value="Not-Verified">Not Verified</option>
													</select>
												</div>
											</div>
											<!-- <div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" name="reportOutput">
														<option selected>Report Output<span id="mandatory_star">*</span>
														</option>
														<option value="1">On Screen</option>
														<option value="2">PDF</option>
														<option value="3">Excel CSV</option>
													</select>
												</div>
											</div> -->
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" multiple id="reportHeaders" name="reportHeaders[]" required>
														<option value="">Report Headers<span id="mandatory_star">*</span>
														</option>
														<option value="all" selected>All</option>
														<?php
														
															for($i=9;$i<count($project_headers);$i++)
															{
																if($project_headers[$i]->keyname!='id')
																{
														?>
														<option value="<?php echo $project_headers[$i]->keyname; ?>"><?php echo ucwords(str_replace("_"," ",$project_headers[$i]->keyname)); ?></option>
														<?php
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										
									</div>
									
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="text-center">
													<?php if(count($projects)>0)
													{
													?>
														<input type="hidden" name="original_table_name" value="<?php echo $projects[0]->original_table_name;?>">
														<button type="submit" class="btn pull-right-sec my-4">Generate Report</button>
													<?php 
													}
													else
													{
														echo "Projects are not available";
													}
													?>
												</div>
											</div>
										</div>
									</div>
                                    <?php if(count($projects)>0)
                                    {
                                    ?>
									<div class="row">
										<div class="col-md-12">
											<div class="text-center" style="border-top:1px solid #cccccc;padding:5px;">
												<a class="btn pull-right-sec my-4" href="<?php echo base_url().$projects[0]->original_file;?>"><i class="fa fa-file-excel"></i> Download original FAR</a>
											</div>
										</div>
									</div>
                                    <?php 
                                    }else{
										?>
										<h1 style="text-align:center;color: #9bc8f2;">No Data to Show</h1>
										<?php
									}
									
									?>
                                    ?>
									<div class="clearfix"></div>
								</form>
							</div>
							<?php
                                        }
											?>
	                       
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--seccion table-->
	</div>
	</div>
<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/reportscript');
$this->load->view('layouts/footer');
?>

<script>

document.getElementById('company_id').onchange = function() {
    var company_id = this.value;
    var fd = new FormData();
    fd.append('company_id',[company_id]);
    jQuery.ajax({
      url: "<?php echo base_url();?>index.php/plancycle/getlocationdatareport",
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

$("#reporttype").change(function(){
	if($(this).val() == '2'){
	$("#projectstatus").hide();
	$("#verificationstatus").hide();
	$("#reportHeaders").hide();

	$("#projectstatus").removeAttr('required');
	$("#verificationstatus").removeAttr('required');
	$("#reportHeaders").removeAttr('required');

	}else{
	 $("#projectstatus").show();
	$("#verificationstatus").show();
	$("#reportHeaders").show();
	$("#projectstatus").attr('required',true);
	$("#verificationstatus").attr('required',true);
	$("#reportHeaders").attr('required',true);
	}
})
</script>