<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
			<div class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
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
												<h4 class="card-title">Allocate Resources</h4> 
											</div>	
											<div class="col-md-6  col-sm-6 col-xs-6 ">
												<form id="msform">
													<!-- progressbar -->
													<ul id="progressbar" class="text-center">
														<li class="active"></li>
														<li class="active"></li>
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
									<table class="table table-sm small">
										<thead class="text-center thead-dark">
											<tr>
												<th>#</th>
												<th>Project ID</th>
												<th>Project Name</th>
												<th>Project Verifier</th>
												<th>Project Categories</th>
												<th>Project Status</th>
											</tr>
										</thead>
										<tbody class="text-center">
											<?php
											$i=0;
											foreach($projects as $pro)
											{ 
											?>
											<tr>
												<td><?php echo ++$i;?></td>
												<td><?php echo $pro->project_id;?></td>
												<td>
													<?php echo $pro->project_name;?><br/>
													<?php echo "(Allocated ".$pro->TotalQuantity." of ".$pro->masterTotal.")"; ?>
												</td>
												<td>
													<?php 
														$k=0;
														$expverifier=explode(',',$pro->project_verifier);
														foreach($expverifier as $ver)
														{
															if($k==0)
																echo get_UserName($ver);
															else
																echo ', '.get_UserName($ver);
														}
													?>
												</td>
												<td><?php echo $pro->item_category;?></td>
												<td><?php echo $pro->status==0 ? "In-Process":($pro->status==3?"Verification Finished":"Cancelled");?></td>
												
											</tr>
											<?php 
											}
											?>
										</tbody>
									</table>
									<form method="POST" action="<?php echo base_url();?>index.php/plancycle/plancyclestepthree">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<div class="text-center">
														<input type="hidden" value="<?php echo $projects[0]->company_id;?>" name="company_name">
														<input type="hidden" value="<?php echo $projects[0]->project_location;?>" name="company_location">
														<input type="hidden" value="<?php echo $projects[0]->original_table_name;?>" name="table_name">
														<?php
														if($allocation_status[0]->Remaining > 0)
														{
															echo '<button type="submit" class="btn pull-right-sec my-4">Allocate More Resources</button>';
														}
														else
														{
															echo '<a style="font-weight:bold;color:green;font-siz:18px;text-align:center;border-top:1px dashed green;padding-top:5px;" class="my-4">All items are allocated</a>';
														}
														?>
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
									<form id="uploadForm" method="POST" action="<?php echo base_url();?>index.php/plancycle/addcycle" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" id="company_name" name="company_name">
                                                        <option selected>Select Company Name</option>
                                                        <?php
                                                        foreach($company as $co)
                                                        {
                                                            echo '<option value="'.$co->id.'">'.$co->company_name.'</option>';
                                                        } 
                                                        ?>
                                                    </select>
                                                    
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<select class="browser-default custom-select" id="company_location" name="company_location">
                                                        <option selected>Select Unit Location</option>
                                                        <?php
                                                        foreach($locations as $loc)
                                                        {
                                                            echo '<option value="'.$loc->id.'">'.$loc->location_name.'</option>';
                                                        } 
                                                        ?>
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

<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/planningscript');
$this->load->view('layouts/footer');
?>