<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<style>
table th,table td{
	padding:5px;
	font-size: 0.875rem;
}
.btn-blue{
	background: #5B96CE !important;
    color: white !important;
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
									<div class="row">
                                    	<div class="col-md-6">
                                    		<h4 class="card-title">Reports </h4> 
                                    	</div>
                                    	<div class="col-md-6">
                                    		<a href="<?php echo base_url(); ?>index.php/dashboard/exceptions"><button class="btn btn-round pull-right">Back</button></a>
                                    	</div>
                                    </div>
                                    	
								</div>
								<?php 
								if($data['type']=='project')
								{
								?>
								<form>
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
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="consolidated" class="text-center optradio" disabled> Consolidated </label></span>
													</div>
												</div>
											</div>
										</div>
										<div class="row my-3">
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Exception Category:</label>
                                                    <input type="text" class="form-control" value="Qty Validation Status">
												</div>
                                            </div>
                                            <div class="col-md-6">
												<div class="form-group">
                                                    <label>Project ID:</label>
                                                    <input type="text" class="form-control" value="<?php echo $data['project'][0]->project_id;?>">
												</div>
											</div>
										</div>
										<div class="row my-3">
                                            
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Project Name:</label>
                                                    <input type="text" class="form-control" value="<?php echo $data['project'][0]->project_name;?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Period of Verification(Days):</label>
                                                    <input type="text" class="form-control" value="<?php echo $data['project'][0]->period_of_verification;?>">
												</div>
											</div>
										</div>
										<div class="row my-3">
                                            <div class="col-md-6">
												<div class="form-group">
                                                    <label>Start Date:</label>
                                                    <input type="text" class="form-control" value="<?php echo date('d/m/yy',strtotime($data['project'][0]->start_date));?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Due Date:</label>
                                                    <input type="text" class="form-control" value="<?php echo date('d/m/yy',strtotime($data['project'][0]->due_date));?>">
												</div>
											</div>
											
										</div>
										<div class="row my-3">
											<?php
											$verifier=explode(',',$data['project'][0]->project_verifier);
											$verifier_name="";
											for($ii=0;$ii<count($verifier);$ii++)
											{
												if($ii==count($verifier)-1)
												{
													$verifier_name.=get_UserName($verifier[$ii]);
												}
												else
												{
													$verifier_name.=get_UserName($verifier[$ii]).", ";
												}
											}
											?>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Allocated Users:</label>
                                                    <input type="text" class="form-control" value="<?php echo $verifier_name;?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Project Status:</label>
                                                    <?php 
                                                    	if($data['project'][0]->status==0){
	                                                    	$statusProject='In Process';
	                                                    }
	                                                    else if($data['project'][0]->status==1)
	                                                    {
		                                                    $statusProject='Completed';
		                                                }
		                                                else if($data['project'][0]->status==2)
		                                                {
			                                                $statusProject='Cancelled';
			                                            }
			                                            else
			                                            {
				                                            $statusProject='Finished Verification';
				                                        }
				                                    ?>
                                                    <input type="text" class="form-control" value="<?php echo $statusProject;?>">
												</div>
											</div>
									</div>
									</div>
									
									<div class="clearfix"></div>
								</form>
								<div class="col-md-12" style="overflow-x:scroll;">
									<table border="1">
										<tr>
											<th rowspan="2">Allocated Item Category</th>
											<th colspan="2">To be Verified</th>
											<th colspan="2" style="background-color:#FFF2CC;">Verified</th>
											<th colspan="2" style="background-color:#FFF2CC;">Verified as Equal</th>
											<th colspan="2" style="background-color:#FFF2CC;">Short Found</th>
											<th colspan="2" style="background-color:#FFF2CC;">Excess Found</th>
											<th colspan="2">Remaining to be Verified</th>
										</tr>
										<tr>
											
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Line Items</th>
											
										</tr>
										<?php
										$totalAmount=0;
										$totalItems=0;
										$verifiedTotalAmount=0;
										$verifiedTotalItems=0;
										$shortTotalAmount=0;
										$shortTotalItems=0;
										$equalTotalAmount=0;
										$equalTotalItems=0;
										$excessTotalAmount=0;
										$excessTotalItems=0;
										$remainingTotalAmount=0;
										$remainingTotalItems=0;
										foreach($data['all'] as $allcat)
										{
											$verifiedAmount=0;
											$verifiedItems=0;
											$shortAmount=0;
											$shortItems=0;
											$equalAmount=0;
											$equalItems=0;
											$excessAmount=0;
											$excessItems=0;
											$remainingAmount=0;
											$remainingItems=0;
											$totalAmount=$totalAmount+$allcat->total_amount;
											$totalItems=$totalItems+$allcat->total_items;
											foreach($data['verified'] as $verified)
											{
												if($verified->item_category==$allcat->item_category)
												{
													$verifiedAmount=$verified->total_amount;
													$verifiedItems=$verified->total_items;
													$verifiedTotalAmount=$verifiedTotalAmount+$verifiedAmount;
													$verifiedTotalItems=$verifiedTotalItems+$verifiedItems;
													if($verified->total_items < $allcat->total_items && $verified->total_items > 0)
													{
														$shortAmount=$allcat->total_amount-$verified->total_amount;
														$shortItems=$allcat->total_items-$verified->total_items;
														$shortTotalAmount=$shortTotalAmount+$shortAmount;
														$shortTotalItems=$shortTotalItems+$shortItems;
													}
													if($verified->total_items > $allcat->total_items)
													{
														$excessAmount=$allcat->total_amount-$verified->total_amount;
														$excessItems=$verified->total_items-$allcat->total_items;
														$excessTotalAmount=$excessTotalAmount+$excessAmount;
														$excessTotalItems=$excessTotalItems+$excessItems;
													}
													if($verified->total_items < 1)
													{
														$remainingAmount=$allcat->total_amount;
														$remainingItems=$allcat->total_items;
														$remainingTotalAmount=$remainingTotalAmount+$remainingAmount;
														$remainingTotalItems=$remainingTotalItems+$remainingItems;	
													}
													
												}

											}
											foreach($data['verifiedequal'] as $verifiedeq)
											{
												if($verifiedeq->item_category==$allcat->item_category)
												{
													$equalAmount=$verifiedeq->total_amount;
													$equalItems=$verifiedeq->total_items;
													$equalTotalAmount=$equalTotalAmount+$equalAmount;
													$equalTotalItems=$equalTotalItems+$equalItems;
												}
											}
											if($_SESSION['reportData']['verification_status']=='Not-Verified')
											{
												$remainingAmount=$allcat->total_amount;
												$remainingItems=$allcat->total_items;
												$remainingTotalAmount=$remainingTotalAmount+$remainingAmount;
												$remainingTotalItems=$remainingTotalItems+$remainingItems;
											}
												
										
										?>
										<tr>
											<td><?php echo $allcat->item_category; ?></td>
											<td><?php echo $allcat->total_amount!=0?getmoney_format(round(($allcat->total_amount/100000),2)):$allcat->total_amount; ?></td>
											<td><?php echo $allcat->total_items; ?></td>
											<td><?php echo $verifiedAmount!=0?getmoney_format(round(($verifiedAmount/100000),2)):$verifiedAmount; ?></td>
											<td><?php echo $verifiedItems; ?></td>
											<td><?php echo $equalAmount!=0?getmoney_format(round(($equalAmount/100000),2)):$equalAmount;?></td>
											<td><?php echo $equalItems; ?></td>
											<td><?php echo $shortAmount!=0?getmoney_format(round(($shortAmount/100000),2)):$shortAmount; ?></td>
											<td><?php echo $shortItems; ?></td>
											<td><?php if($excessAmount>0){echo getmoney_format(round(($excessAmount/100000),2));}else if($excessAmount<0){ echo '-'.getmoney_format(round(($excessAmount/100000),2));}else {echo 0;} ?></td>
											<td><?php echo $excessItems; ?></td>
											<td><?php echo $remainingAmount!=0?getmoney_format(round(($remainingAmount/100000),2)):$remainingAmount; ?></td>
											<td><?php echo $remainingItems; ?></td>
										</tr>
										<?php
										}
										?>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<th><?php echo "Grand Total"; ?></th>
											<th><?php echo $totalAmount!=0?getmoney_format(round(($totalAmount/100000),2)):$totalAmount; ?></th>
											<th><?php echo $totalItems; ?></th>
											<th><?php echo $verifiedTotalAmount!=0?getmoney_format(round(($verifiedTotalAmount/100000),2)):$verifiedTotalAmount; ?></th>
											<th><?php echo $verifiedTotalItems; ?></th>
											<th><?php echo $equalTotalAmount!=0?getmoney_format(round(($equalTotalAmount/100000),2)):$equalTotalAmount; ?></th>
											<th><?php echo $equalTotalItems; ?></th>
											<th><?php echo $shortTotalAmount!=0?getmoney_format(round(($shortTotalAmount/100000),2)):$shortTotalAmount; ?></th>
											<th><?php echo $shortTotalItems; ?></th>
											<th><?php if($excessTotalAmount>0){echo getmoney_format(round(($excessTotalAmount/100000),2));}else if($excessTotalAmount<0){ echo '-'.getmoney_format(round(($excessTotalAmount/100000),2));}else {echo 0;} ?></th>
											<th><?php echo $excessTotalItems; ?></th>
											<th><?php echo $remainingTotalAmount!=0?getmoney_format(round(($remainingTotalAmount/100000),2)):$remainingTotalAmount; ?></th>
											<th><?php echo $remainingTotalItems; ?></th>
										</tr>
										<tr>
											<th><?php echo "% to Grand Total"; ?></th>
											<th>100%</th>
											<th>100%</th>
											<th><?php echo round(($verifiedTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($verifiedTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($equalTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($equalTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($shortTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($shortTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($excessTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($excessTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($remainingTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($remainingTotalItems/$totalItems)*100,2); ?>%</th>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<th>Click for detailed report</th>
											<th colspan="2"></th>
											<th colspan="2"><?php if($verifiedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionThreeVerifiedReport">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($equalTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionThreeEqualReport">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($shortTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionThreeShortReport">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($excessTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionThreeExcessReport">Download as Annexure</a> <?php }?></td>
											<th colspan="2"><?php if($remainingTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionThreeRemainingReport">Download as Annexure</a> <?php }?></td>
										</tr>
										
									</table>
								</div>
								<?php
								}
								?>
								<div class="col-md-12 text-center mt-3"><a href="<?php echo base_url(); ?>index.php/dashboard/exceptions"><button class="btn btn-round btn-blue">Back</button></a></div>
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
<style>
	table th,table td
	{
		text-align: center;
		height:40px;
	}
	</style>
<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/reportscript');
$this->load->view('layouts/footer');
?>