<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');

?>
<style>
select option:disabled {
    color: #cccccc;
    
}
</style>
	<div class="content">
				<div class="container-fluid">
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
							<div class="card">
								
								<div class="card-header card-header-primary">
									<h4 class="card-title">Reports </h4> 
								</div>
                                <?php 
                                if(count($projects)>0)
                                {
                                ?>
								
								<form method="POST" action="<?php echo base_url(); ?>index.php/dashboard/generateExceptionReport">
									<div class="card-body">
										
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
														<option value="" selected>Project ID & Name<span class="mandatory_star">*</span>
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
													<select class="browser-default custom-select" name="exceptioncategory" id="exceptioncategory" required>
														<option value="" selected>Exception Category <span class="mandatory_star">*</span>
														</option>
														<option value="1">Condition of Item</option>
														<option value="2" disabled>Changes/ Updations of Items</option>
														<option value="3">Qty Validation Status</option>
														<option value="4">Updated with Verification Remarks</option>
														<option value="5">Updated with Item Notes</option>
														<option value="6" disabled>Calculate Risk Exposure</option>
														<option value="7" disabled>Marked for Review</option>
														<option value="8">Mode of Verification</option>
														<option value="9" disabled>Duplicate Item Codes verified</option>
														<option value="10" disabled>Duplicate Item Codes Identified</option>
														<option value="11" disabled>Revalidation Status</option>
														
														
														
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" name="projectstatus" id="projectstatus" required>
														<option value="" selected>Project Status <span class="mandatory_star">*</span>
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
														<option value="" selected>Verification Status<span class="mandatory_star">*</span>
														</option>
														<option value="1">All</option>
														<option value="Verified">Verified</option>
														<option value="Not-Verified">Not Verified</option>
													</select>
												</div>
											</div>
											
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" multiple name="reportHeaders[]" required>
														<option value="">Report Headers<span class="mandatory_star">*</span>
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
										
                                        <div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<div class="text-center">
														<input type="hidden" name="original_table_name" value="<?php echo $projects[0]->original_table_name;?>">
														<button type="submit" class="btn pull-right-sec my-4">Generate Report</button>
														
													</div>
												</div>
											</div>
										</div>
									</div>
									
									
                                    <div class="clearfix"></div>
								</form>
								<?php
                                }
                                else
                                {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="text-center">
                                                Projects are not available
                                            </div>
                                        </div>
                                    </div>
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
	</div>
	<!--seccion table-->
	</div>
	</div>
<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/reportscript');
$this->load->view('layouts/footer');
?>