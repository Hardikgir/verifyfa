<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
error_reporting(0);
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
                                                    <input type="text" class="form-control" value="Condition of Items">
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
											<th colspan="2" style="background-color:#FFF2CC;">Good Condition</th>
											<th colspan="2" style="background-color:#FFF2CC;">Damaged</th>
											<th colspan="2" style="background-color:#FFF2CC;">Scrapped</th>
											<th colspan="2" style="background-color:#FFF2CC;">Missing</th>
											<th colspan="2" style="background-color:#FFF2CC;">Shifted</th>
											<th colspan="2" style="background-color:#FFF2CC;">Not in Use</th>
											<th colspan="2">Remaining to be Verified</th>
										</tr>
										<tr>
											
											<th>Amount(in Lacs)</th>
											<th>Number of Qty</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Qty</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Qty</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Qty</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Qty</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Qty</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Qty</th>
											<th>Amount(in Lacs)</th>
											<th>Number of Qty</th>
											
										</tr>
										<?php
										$totalAmount=0;
										$totalItems=0;
										$goodTotalAmount=0;
										$goodTotalItems=0;
										$damagedTotalAmount=0;
										$damagedTotalItems=0;
										$scrappedTotalAmount=0;
										$scrappedTotalItems=0;
										$missingTotalAmount=0;
										$missingTotalItems=0;
										$shiftedTotalAmount=0;
										$shiftedTotalItems=0;
										$notinuseTotalAmount=0;
										$notinuseTotalItems=0;
										$remainingTotalAmount=0;
										$remainingTotalItems=0;
										$remainitemstotal=0;
										foreach($data['all'] as $allcat)
										{
											$goodAmount=0;
											$goodItems=0;
											$damagedAmount=0;
											$damagedItems=0;
											$scrappedAmount=0;
											$scrappedItems=0;
											$missingAmount=0;
											$missingItems=0;
											$shiftedAmount=0;
											$shiftedItems=0;
											$notinuseAmount=0;
											$notinuseItems=0;
											$remainingAmount=0;
											$remainingItems=0;
											$totalAmount=$totalAmount+$allcat->total_amount;
											$totalItems=$totalItems+$allcat->total_qty;
											foreach($data['good'] as $good)
											{
												if($good->item_category==$allcat->item_category)
												{
													$goodAmount=$good->total_amount;
													$goodItems=$good->good_qty;
													$goodTotalAmount=$goodTotalAmount+$goodAmount;
													$goodTotalItems=$goodTotalItems+$goodItems;
												}
											}
											foreach($data['damaged'] as $damaged)
											{
												if($damaged->item_category==$allcat->item_category)
												{
													$damagedAmount=$damaged->total_amount;
													$damagedItems=$damaged->damaged_qty;
													$damagedTotalAmount=$damagedTotalAmount+$damagedAmount;
													$damagedTotalItems=$damagedTotalItems+$damagedItems;
												}
											}
											foreach($data['scrapped'] as $scrapped)
											{
												if($scrapped->item_category==$allcat->item_category)
												{
													$scrappedAmount=$scrapped->total_amount;
													$scrappedItems=$scrapped->scrapped_qty;
													$scrappedTotalAmount=$scrappedTotalAmount+$scrappedAmount;
													$scrappedTotalItems=$scrappedTotalItems+$scrappedItems;
												}
											}
											foreach($data['missing'] as $missing)
											{
												if($missing->item_category==$allcat->item_category)
												{
													$missingAmount=$missing->total_amount;
													$missingItems=$missing->missing_item;
													$missingTotalAmount=$missingTotalAmount+$missingAmount;
													$missingTotalItems=$missingTotalItems+$missingItems;
												}
											}
											foreach($data['shifted'] as $shifted)
											{
												if($shifted->item_category==$allcat->item_category)
												{
													$shiftedAmount=$shifted->total_amount;
													$shiftedItems=$shifted->shifted_item;
													$shiftedTotalAmount=$shiftedTotalAmount+$shiftedAmount;
													$shiftedTotalItems=$shiftedTotalItems+$shiftedItems;
												}
											}
											foreach($data['notinuse'] as $notinuse)
											{
												if($notinuse->item_category==$allcat->item_category)
												{
													$notinuseAmount=$notinuse->total_amount;
													$notinuseItems=$notinuse->notinuse_qty;
													$notinuseTotalAmount=$notinuseTotalAmount+$notinuseAmount;
													 $notinuseTotalItems= $notinuseTotalItems + $notinuseItems;
												}
											}
											$remainitem='0';
											foreach($data['remaining'] as $remainingdata)
											{
												if($remainingdata->item_category==$allcat->item_category)
												{
													$remainitem= $remainingdata->items;
												}
												
											}
											$remainitemstotal +=$remainitem;


											$remainingAmount=$allcat->total_amount-($goodAmount+$damagedAmount+$scrappedAmount+$missingAmount+$shiftedAmount+$notinuseAmount);
											$remainingItems=$allcat->total_qty-($goodItems+$damagedItems+$scrappedItems+$missingItems+$shiftedItems+$notinuseItems);
											$remainingTotalAmount=$remainingTotalAmount+$remainingAmount;
											$remainingTotalItems=$remainingTotalItems+$remainingItems;
										?>
										<tr>
											<td><?php echo $allcat->item_category; ?></td>
											<td><?php echo $allcat->total_amount!=0?getmoney_format(round(($allcat->total_amount/100000),2)):$allcat->total_amount; ?></td>
											<td><?php echo $allcat->total_qty; ?></td>
											<td><?php echo $goodAmount!=0?getmoney_format(round(($goodAmount/100000),2)):$goodAmount; ?></td>
											<td><?php echo $goodItems; ?></td>
											<td><?php echo $damagedAmount!=0?getmoney_format(round(($damagedAmount/100000),2)):$damagedAmount;?></td>
											<td><?php echo $damagedItems; ?></td>
											<td><?php echo $scrappedAmount!=0?getmoney_format(round(($scrappedAmount/100000),2)):$scrappedAmount; ?></td>
											<td><?php echo $scrappedItems; ?></td>
											<td><?php echo $missingAmount!=0?getmoney_format(round(($missingAmount/100000),2)):$missingAmount; ?></td>
											<td><?php echo $missingItems; ?></td>
											<td><?php echo $shiftedAmount!=0?getmoney_format(round(($shiftedAmount/100000),2)):$shiftedAmount; ?></td>
											<td><?php echo $shiftedItems; ?></td>
											<td><?php echo $notinuseAmount!=0?getmoney_format(round(($notinuseAmount/100000),2)):$notinuseAmount; ?></td>
											<td><?php echo $notinuseItems; ?></td>
											<td><?php echo $remainingAmount!=0?getmoney_format(round(($remainingAmount/100000),2)):$remainingAmount; ?></td>
											<td><?php echo $remainitem; ?></td>
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
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<th><?php echo "Grand Total"; ?></th>
											<th><?php echo $totalAmount!=0?getmoney_format(round(($totalAmount/100000),2)):$totalAmount; ?></th>
											<th><?php echo $totalItems; ?></th>
											<th><?php echo $goodTotalAmount!=0?getmoney_format(round(($goodTotalAmount/100000),2)):$goodTotalAmount; ?></th>
											<th><?php echo $goodTotalItems; ?></th>
											<th><?php echo $damagedTotalAmount!=0?getmoney_format(round(($damagedTotalAmount/100000),2)):$damagedTotalAmount; ?></th>
											<th><?php echo $damagedTotalItems; ?></th>
											<th><?php echo $scrappedTotalAmount!=0?getmoney_format(round(($scrappedTotalAmount/100000),2)):$scrappedTotalAmount; ?></th>
											<th><?php echo $scrappedTotalItems; ?></th>
											<th><?php echo $missingTotalAmount!=0?getmoney_format(round(($missingTotalAmount/100000),2)):$missingTotalAmount; ?></th>
											<th><?php echo $missingTotalItems; ?></th>
											<th><?php echo $shiftedTotalAmount!=0?getmoney_format(round(($shiftedTotalAmount/100000),2)):$shiftedTotalAmount; ?></th>
											<th><?php echo $shiftedTotalItems; ?></th>
											<th><?php echo $notinuseTotalAmount!=0?getmoney_format(round(($notinuseTotalAmount/100000),2)):$notinuseTotalAmount; ?></th>
											<th><?php echo $notinuseTotalItems; ?></th>
											<th><?php echo $remainingTotalAmount!=0?getmoney_format(round(($remainingTotalAmount/100000),2)):$remainingTotalAmount; ?></th>
											<th><?php echo $remainitemstotal; ?></th>
										</tr>
										<tr>
											<th><?php echo "% to Grand Total"; ?></th>
											<th>100%</th>
											<th>100%</th>
											<th><?php echo round(($goodTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($goodTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($damagedTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($damagedTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($scrappedTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($scrappedTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($missingTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($missingTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($shiftedTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($shiftedTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($notinuseTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($notinuseTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($remainingTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($remainitemstotal/$totalItems)*100,2); ?>%</th>
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
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<th>Click for detailed report</th>
											<th colspan="2"></th>
											<th colspan="2"><?php if($goodTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneGoodReport">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($damagedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneDamagedReport">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($scrappedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneScrappedReport">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($missingTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneMissingReport">Download as Annexure</a> <?php }?></td>
											<th colspan="2"><?php if($shiftedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneShiftedReport">Download as Annexure</a> <?php }?></td>
											<th colspan="2"><?php if($notinuseTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneNotinuseReport">Download as Annexure</a> <?php }?></td>
											<th colspan="2"><?php if($remainingTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneRemainingReport">Download as Annexure</a> <?php }?></td>
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
	<!--seccion table--->
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