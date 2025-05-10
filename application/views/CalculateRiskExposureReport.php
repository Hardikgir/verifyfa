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
											<!-- <th rowspan="1">Allocated Item Category</th> -->
											<th colspan="10" style="background-color:#FFF2CC;">Item Condition</th>
                                            <th colspan="4" >Qty Validation</th>
										</tr>



										<tr>
											<th colspan="2" rowspan="2">Allocated Item Category</th>
											<th colspan="2">Damaged</th>
											<th colspan="2">Scrapped</th>
											<th colspan="2">Missing</th>
											<th colspan="2">Shifted</th>
											<th colspan="2">Not in Use</th>
                                            <th colspan="2">Short Found</th>
                                            <th colspan="2">Excess Found</th>
										</tr>


										<tr>
											<th>Amount(in Lacs)</th><!-- For Damaged -->
											<th>Number of Qty</th><!-- For Damaged -->
											<th>Amount(in Lacs)</th><!-- For Scrapped -->
											<th>Number of Qty</th><!-- For Scrapped -->
											<th>Amount(in Lacs)</th><!-- For Missing -->
											<th>Number of Qty</th><!-- For Missing -->
											<th>Amount(in Lacs)</th><!-- For Shifted -->
											<th>Number of Qty</th><!-- For Shifted -->
											<th>Amount(in Lacs)</th><!-- For Not_in_Use -->
											<th>Number of Qty</th><!-- For Not_in_Use -->
                                            <th>Amount(in Lacs)</th><!-- For Short_Found -->
											<th>Number of Line Items</th><!-- For Short_Found -->
                                            <th>Amount(in Lacs)</th><!-- For Excess_Found -->
                                            <th>Number of Line Items</th><!-- For Excess_Found -->
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

                                            $shortAmount=0;
											$shortItems=0;

                                            foreach($data['verified'] as $verified)
											{
												if($verified->item_category==$allcat->item_category)
												{
													$verifiedAmount=$verified->total_amount;
													$verifiedItems=$verified->total_items;
													$verifiedTotalAmount=$verifiedTotalAmount+$verifiedAmount;
													$verifiedTotalItems=$verifiedTotalItems+$verifiedItems;
													
													if($verified->total_items > $allcat->total_items && $verified->total_items > 0)
													{
														$shortAmount=$allcat->total_amount-$verified->total_amount;
														$shortItems=$allcat->total_items-$verified->total_items;
														$shortTotalAmount=$shortTotalAmount+$shortAmount;
														$shortTotalItems=$shortTotalItems+$shortItems;
													}

													if($verified->total_items > $allcat->total_items)
													{
														// // $excessAmount=$allcat->total_amount - $verified->total_amount;
														// $excessItems=$verified->total_items - $allcat->total_items;

														// $excessTotalAmount=$excessTotalAmount+$excessAmount;
														// $excessTotalItems=$excessTotalItems+$excessItems;
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


                                            $excessitem='0';
											$excessamount='0';
											foreach($data['excess'] as $excess)
											{
												if($excess->item_category == $allcat->item_category)
												{
													$excessitem = $excess->items;
													 $excessAmount =$excess->total_amount;		
													 $excessamounttotalnew=$excessamounttotalnew+$excessAmount;
 
												}
											}
											$excessitemtotal +=$excessitem;


											$remainingAmount=$allcat->total_amount-($goodAmount+$damagedAmount+$scrappedAmount+$missingAmount+$shiftedAmount+$notinuseAmount);
											$remainingItems=$allcat->total_qty-($goodItems+$damagedItems+$scrappedItems+$missingItems+$shiftedItems+$notinuseItems);
											$remainingTotalAmount=$remainingTotalAmount+$remainingAmount;
											$remainingTotalItems=$remainingTotalItems+$remainingItems;
										?>
										<tr>
											<td><?php echo $allcat->item_category; ?></td>
										
											
											<td><?php echo $damagedAmount!=0?getmoney_format(round(($damagedAmount/100000),2)):$damagedAmount;?></td><!-- For Damaged -->
											<td><?php echo $damagedItems; ?></td><!-- For Damaged -->

											<td><?php echo $scrappedAmount!=0?getmoney_format(round(($scrappedAmount/100000),2)):$scrappedAmount; ?></td><!-- For Scrapped -->
											<td><?php echo $scrappedItems; ?></td><!-- For Scrapped -->

											<td><?php echo $missingAmount!=0?getmoney_format(round(($missingAmount/100000),2)):$missingAmount; ?></td><!-- For Missing -->
											<td><?php echo $missingItems; ?></td><!-- For Missing -->

											<td><?php echo $shiftedAmount!=0?getmoney_format(round(($shiftedAmount/100000),2)):$shiftedAmount; ?></td><!-- For Shifted -->
											<td><?php echo $shiftedItems; ?></td><!-- For Shifted -->

											<td><?php echo $notinuseAmount!=0?getmoney_format(round(($notinuseAmount/100000),2)):$notinuseAmount; ?></td><!-- For Not_in_Use -->
											<td><?php echo $notinuseItems; ?></td><!-- For Not_in_Use -->
										
                                            <td><?php echo $shortAmount!=0?getmoney_format(round(($shortAmount/100000),2)):$shortAmount; ?></td><!-- For Short_Found -->
											<td><?php echo $shortItems; ?></td><!-- For Short_Found -->

                                            <td><?php echo $excessAmount!=0?getmoney_format(round(($excessAmount/100000),2)):$excessAmount; ?></td><!-- For Excess_Found -->
											<td><?php echo $excessitem; ?></td><!-- For Excess_Found -->

										</tr>
										<?php
										}
										?>
										<tr>
											<td></td>
											<td></td><!-- For Damaged -->
											<td></td><!-- For Damaged -->
											<td></td><!-- For Scrapped -->
											<td></td><!-- For Scrapped -->
											<td></td><!-- For Missing -->
											<td></td><!-- For Missing -->
											<td></td><!-- For Shifted -->
											<td></td><!-- For Shifted -->
											<td></td><!-- For Not_in_Use -->
											<td></td><!-- For Not_in_Use -->
											<td></td><!-- For Short_Found -->
											<td></td><!-- For Short_Found -->
											<td></td><!-- For Excess_Found -->
											<td></td><!-- For Excess_Found -->
										</tr>
										<tr>
											<td></td>
											<td></td><!-- For Damaged -->
											<td></td><!-- For Damaged -->
											<td></td><!-- For Scrapped -->
											<td></td><!-- For Scrapped -->
											<td></td><!-- For Missing -->
											<td></td><!-- For Missing -->
											<td></td><!-- For Shifted -->
											<td></td><!-- For Shifted -->
											<td></td><!-- For Not_in_Use -->
											<td></td><!-- For Not_in_Use -->
											<td></td><!-- For Short_Found -->
											<td></td><!-- For Short_Found -->
											<td></td><!-- For Excess_Found -->
											<td></td><!-- For Excess_Found -->
										</tr>
										<tr>
											<th><?php echo "Grand Total"; ?></th>
											<th><?php echo $damagedTotalAmount!=0?getmoney_format(round(($damagedTotalAmount/100000),2)):$damagedTotalAmount; ?></th><!-- For Damaged -->
											<th><?php echo $damagedTotalItems; ?></th><!-- For Damaged -->
											<th><?php echo $scrappedTotalAmount!=0?getmoney_format(round(($scrappedTotalAmount/100000),2)):$scrappedTotalAmount; ?></th><!-- For Scrapped -->
											<th><?php echo $scrappedTotalItems; ?></th><!-- For Scrapped -->
											<th><?php echo $missingTotalAmount!=0?getmoney_format(round(($missingTotalAmount/100000),2)):$missingTotalAmount; ?></th><!-- For Missing -->
											<th><?php echo $missingTotalItems; ?></th><!-- For Missing -->
											<th><?php echo $shiftedTotalAmount!=0?getmoney_format(round(($shiftedTotalAmount/100000),2)):$shiftedTotalAmount; ?></th><!-- For Shifted -->
											<th><?php echo $shiftedTotalItems; ?></th><!-- For Shifted -->
											<th><?php echo $notinuseTotalAmount!=0?getmoney_format(round(($notinuseTotalAmount/100000),2)):$notinuseTotalAmount; ?></th><!-- For Not_in_Use -->
											<th><?php echo $notinuseTotalItems; ?></th><!-- For Not_in_Use -->
											<th><?php echo $shortTotalAmount!=0?getmoney_format(round(($shortTotalAmount/100000),2)):$shortTotalAmount; ?></th><!-- For Short_Found -->
											<th><?php echo $shortTotalItems; ?></th><!-- For Short_Found -->
                                            <th><?php echo $excessamounttotalnew!=0?getmoney_format(round(($excessamounttotalnew/100000),2)):$excessamounttotalnew; ?></th><!-- For Excess_Found -->
											<th><?php echo $excessitemtotal; ?></th><!-- For Excess_Found -->
										</tr>
										<tr>
											<th><?php echo "% to Grand Total"; ?></th>
											<th><?php echo round(($damagedTotalAmount/$totalAmount)*100,2); ?>%</th><!-- For Damaged -->
											<th><?php echo round(($damagedTotalItems/$totalItems)*100,2); ?>%</th><!-- For Damaged -->
											<th><?php echo round(($scrappedTotalAmount/$totalAmount)*100,2); ?>%</th><!-- For Scrapped -->
											<th><?php echo round(($scrappedTotalItems/$totalItems)*100,2); ?>%</th><!-- For Scrapped -->
											<th><?php echo round(($missingTotalAmount/$totalAmount)*100,2); ?>%</th><!-- For Missing -->
											<th><?php echo round(($missingTotalItems/$totalItems)*100,2); ?>%</th><!-- For Missing -->
											<th><?php echo round(($shiftedTotalAmount/$totalAmount)*100,2); ?>%</th><!-- For Shifted -->
											<th><?php echo round(($shiftedTotalItems/$totalItems)*100,2); ?>%</th><!-- For Shifted -->
											<th><?php echo round(($notinuseTotalAmount/$totalAmount)*100,2); ?>%</th><!-- For Not_in_Use -->
											<th><?php echo round(($notinuseTotalItems/$totalItems)*100,2); ?>%</th><!-- For Not_in_Use -->
										    <th><?php echo round(($shortTotalItems/$totalItems)*100,2); ?>%</th><!-- For Short_Found -->
											<th><?php echo round(($excessamounttotalnew/$totalAmount)*100,2); ?>%</th><!-- For Short_Found -->
                                            <th><?php echo round(($excessamounttotalnew/$totalAmount)*100,2); ?>%</th><!-- For Excess_Found -->
											<th><?php echo round(($excessitemtotal/$totalItems)*100,2); ?>%</th><!-- For Excess_Found -->
										</tr>
										<tr>
											<td></td>
											<td></td><!-- For Damaged -->
											<td></td><!-- For Damaged -->
											<td></td><!-- For Scrapped -->
											<td></td><!-- For Scrapped -->
											<td></td><!-- For Missing -->
											<td></td><!-- For Missing -->
											<td></td><!-- For Shifted -->
											<td></td><!-- For Shifted -->
											<td></td><!-- For Not_in_Use -->
											<td></td><!-- For Not_in_Use -->
											<td></td><!-- For Short_Found -->
                                            <td></td><!-- For Short_Found -->
											<td></td><!-- For Excess_Found -->
											<td></td><!-- For Excess_Found -->
										</tr>
										<tr>
											<th>Click for detailed report</th>
											<th colspan="2"><?php if($damagedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneDamagedReport">Download as Annexure</a><?php }?></th><!-- For Damaged -->
											<th colspan="2"><?php if($scrappedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneScrappedReport">Download as Annexure</a><?php }?></th><!-- For Scrapped -->
											<th colspan="2"><?php if($missingTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneMissingReport">Download as Annexure</a> <?php }?></th><!-- For Missing -->
											<th colspan="2"><?php if($shiftedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneShiftedReport">Download as Annexure</a> <?php }?></th><!-- For Shifted -->
											<th colspan="2"><?php if($notinuseTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneNotinuseReport">Download as Annexure</a> <?php }?></th><!-- For Not_in_Use -->
										    <th colspan="2"><?php if($shortTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionThreeShortReport">Download as Annexure</a><?php }?></th><!-- For Short_Found -->
                                            <th th colspan="2"><?php if($excessamounttotalnew!=0){ ?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionThreeExcessReport">Download as Annexure</a><?php } ?></th><!-- For Excess_Found -->
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