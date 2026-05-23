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
                            
                            <?php
                            $unspecifieddata=$data[0]['unallocated']; 
                            if(isset($_SESSION['error_message']))
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
								if($data[0]['type']=='consolidated')
								{
								?>
								<form>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="project" class="optradio" disabled> Project based </label></span>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="consolidated" class="text-center optradio" checked > Consolidated </label></span>
													</div>
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
                                        <tr><th colspan="17" style="text-align:left;color:green;">Allocated Projects: </th></tr>
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
                                        foreach($data as $data)
                                        {
                                            $subtotalAmount=0;
                                            $subtotalItems=0;
                                            $subgoodTotalAmount=0;
											$subgoodTotalItems=0;
											$subdamagedTotalAmount=0;
											$subdamagedTotalItems=0;
											$subscrappedTotalAmount=0;
											$subscrappedTotalItems=0;
											$submissingTotalAmount=0;
											$submissingTotalItems=0;
											$subshiftedTotalAmount=0;
											$subshiftedTotalItems=0;
											$subnotinuseTotalAmount=0;
											$subnotinuseTotalItems=0;
											$subremainingTotalAmount=0;
											$subremainingTotalItems=0;
                                        ?>
                                            <tr><th colspan="17"  style="text-align:left;"><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionOneReportAllocated/<?php echo  $data['project']->id; ?>" style="color:#5CA1E2;"><?php echo $data['project']->project_name;?>:</a></th></tr>
                                        <?php
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
                                                $subtotalAmount=$subtotalAmount+$allcat->total_amount;
                                                $subtotalItems=$subtotalItems+$allcat->total_qty;
                                                foreach($data['good'] as $good)
												{
													if($good->item_category==$allcat->item_category)
													{
                                                        $goodAmount=$good->total_amount;
														$goodItems=$good->good_qty;
														$goodTotalAmount=$goodTotalAmount+$goodAmount;
														$goodTotalItems=$goodTotalItems+$goodItems;
                                                        $subgoodTotalAmount=$subgoodTotalAmount+$goodAmount;
                                                        $subgoodTotalItems=$subgoodTotalItems+$goodItems;
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
                                                        $subdamagedTotalAmount=$subdamagedTotalAmount+$damagedAmount;
                                                        $subdamagedTotalItems=$subdamagedTotalItems+$damagedItems;
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
                                                        $subscrappedTotalAmount=$subscrappedTotalAmount+$scrappedAmount;
                                                        $subscrappedTotalItems=$subscrappedTotalItems+$scrappedItems;
                                                    }
                                                   
                                                }
                                                foreach($data['missing'] as $missing)
												{
													if($missing->item_category==$allcat->item_category)
													{
														$missingAmount=$missing->total_amount;
														$missingItems=$missing->missing_qty;
														$missingTotalAmount=$missingTotalAmount+$missingAmount;
														$missingTotalItems=$missingTotalItems+$missingItems;
														$submissingTotalAmount=$submissingTotalAmount+$missingAmount;
                                                        $submissingTotalItems=$submissingTotalItems+$missingItems;
													}
												}
												foreach($data['shifted'] as $shifted)
												{
													if($shifted->item_category==$allcat->item_category)
													{
														$shiftedAmount=$shifted->total_amount;
														$shiftedItems=$shifted->shifted_qty;
														$shiftedTotalAmount=$shiftedTotalAmount+$shiftedAmount;
														$shiftedTotalItems=$shiftedTotalItems+$shiftedItems;
														$subshiftedTotalAmount=$subshiftedTotalAmount+$shiftedAmount;
                                                        $subshiftedTotalItems=$subshiftedTotalItems+$shiftedItems;
													}
												}
												foreach($data['notinuse'] as $notinuse)
												{
													if($notinuse->item_category==$allcat->item_category)
													{
														$notinuseAmount=$notinuse->total_amount;
														$notinuseItems=$notinuse->notinuse_qty;
														$notinuseTotalAmount=$notinuseTotalAmount+$notinuseAmount;
														$notinuseTotalItems=$notinuseTotalItems+$notinuseItems;
														$subnotinuseTotalAmount=$subnotinuseTotalAmount+$notinuseAmount;
                                                        $subnotinuseTotalItems=$subnotinuseTotalItems+$notinuseItems;
													}
												}
												$remainingAmount=$allcat->total_amount-($goodAmount+$damagedAmount+$scrappedAmount+$missingAmount+$shiftedAmount+$notinuseAmount);
												$remainingItems=$allcat->total_qty-($goodItems+$damagedItems+$scrappedItems+$missingItems+$shiftedItems+$notinuseItems);
												$subremainingTotalAmount=$subremainingTotalAmount+$remainingAmount;
                                                $subremainingTotalItems=$subremainingTotalItems+$remainingItems;
                                                
												
										
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
											<td><?php echo $remainingItems; ?></td>
										</tr>
										<?php
                                            }
                                            ?>
                                            <tr>
                                            <th><?php echo "Sub Total"; ?></th>
                                            <td><?php echo $subtotalAmount!=0?getmoney_format(round(($subtotalAmount/100000),2)):$subtotalAmount; ?></td>
											<td><?php echo $subtotalItems; ?></td>
											<td><?php echo $subgoodTotalAmount!=0?getmoney_format(round(($subgoodTotalAmount/100000),2)):$subgoodTotalAmount; ?></td>
											<td><?php echo $subgoodTotalItems; ?></td>
											<td><?php echo $subdamagedTotalAmount!=0?getmoney_format(round(($subdamagedTotalAmount/100000),2)):$subdamagedTotalAmount; ?></td>
											<td><?php echo $subdamagedTotalItems; ?></td>
											<td><?php echo $subscrappedTotalAmount!=0?getmoney_format(round(($subscrappedTotalAmount/100000),2)):$subscrappedTotalAmount; ?></td>
											<td><?php echo $subscrappedTotalItems; ?></td>
											<td><?php echo $submissingTotalAmount!=0?getmoney_format(round(($submissingTotalAmount/100000),2)):$submissingTotalAmount; ?></td>
											<td><?php echo $submissingTotalItems; ?></td>
											<td><?php echo $subshiftedTotalAmount!=0?getmoney_format(round(($subshiftedTotalAmount/100000),2)):$subshiftedTotalAmount; ?></td>
											<td><?php echo $subshiftedTotalItems; ?></td>
											<td><?php echo $subnotinuseTotalAmount!=0?getmoney_format(round(($subnotinuseTotalAmount/100000),2)):$subnotinuseTotalAmount; ?></td>
											<td><?php echo $subnotinuseTotalItems; ?></td>
											<td><?php echo $subremainingTotalAmount!=0?getmoney_format(round(($subremainingTotalAmount/100000),2)):$subremainingTotalAmount; ?></td>
											<td><?php echo $subremainingTotalItems; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        $remainingTotalAmount=$remainingTotalAmount+$subremainingTotalAmount;
                                        $remainingTotalItems=$remainingTotalItems+$subremainingTotalItems;
                                        if(count($unspecifieddata['all'])>0)
                                        {
                                            $subtotalAmount=0;
                                            $subtotalItems=0;
                                            $subgoodTotalAmount=0;
											$subgoodTotalItems=0;
											$subdamagedTotalAmount=0;
											$subdamagedTotalItems=0;
											$subscrappedTotalAmount=0;
											$subscrappedTotalItems=0;
											$submissingTotalAmount=0;
											$submissingTotalItems=0;
											$subshiftedTotalAmount=0;
											$subshiftedTotalItems=0;
											$subnotinuseTotalAmount=0;
											$subnotinuseTotalItems=0;
											$subremainingTotalAmount=0;
											$subremainingTotalItems=0;
                                        ?>
                                            <tr><th colspan="17"  style="text-align:left;"><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionReportUnallocated/<?php echo  $data['project']->id; ?>" style="color:#5CA1E2;"><?php echo 'Unallocated';?>:</a></th></tr>
                                        <?php
                                            
                                        foreach($unspecifieddata['all'] as $allcat)
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
                                            $subtotalAmount=$subtotalAmount+$allcat->total_amount;
                                            $subtotalItems=$subtotalItems+$allcat->total_qty;
                                            
                                            
                                            
                                            foreach($data['good'] as $good)
											{
												if($good->item_category==$allcat->item_category)
												{
                                                    $goodAmount=$good->total_amount;
													$goodItems=$good->good_qty;
													$goodTotalAmount=$goodTotalAmount+$goodAmount;
													$goodTotalItems=$goodTotalItems+$goodItems;
                                                    $subgoodTotalAmount=$subgoodTotalAmount+$goodAmount;
                                                    $subgoodTotalItems=$subgoodTotalItems+$goodItems;
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
                                                    $subdamagedTotalAmount=$subdamagedTotalAmount+$damagedAmount;
                                                    $subdamagedTotalItems=$subdamagedTotalItems+$damagedItems;
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
                                                    $subscrappedTotalAmount=$subscrappedTotalAmount+$scrappedAmount;
                                                    $subscrappedTotalItems=$subscrappedTotalItems+$scrappedItems;
                                                }
                                               
                                            }
                                            foreach($data['missing'] as $missing)
											{
												if($missing->item_category==$allcat->item_category)
												{
													$missingAmount=$missing->total_amount;
													$missingItems=$missing->missing_qty;
													$missingTotalAmount=$missingTotalAmount+$missingAmount;
													$missingTotalItems=$missingTotalItems+$missingItems;
													$submissingTotalAmount=$submissingTotalAmount+$missingAmount;
                                                    $submissingTotalItems=$submissingTotalItems+$missingItems;
												}
											}
											foreach($data['shifted'] as $shifted)
											{
												if($shifted->item_category==$allcat->item_category)
												{
													$shiftedAmount=$shifted->total_amount;
													$shiftedItems=$shifted->shifted_qty;
													$shiftedTotalAmount=$shiftedTotalAmount+$shiftedAmount;
													$shiftedTotalItems=$shiftedTotalItems+$shiftedItems;
													$subshiftedTotalAmount=$subshiftedTotalAmount+$shiftedAmount;
                                                    $subshiftedTotalItems=$subshiftedTotalItems+$shiftedItems;
												}
											}
											foreach($data['notinuse'] as $notinuse)
											{
												if($notinuse->item_category==$allcat->item_category)
												{
													$notinuseAmount=$notinuse->total_amount;
													$notinuseItems=$notinuse->notinuse;
													$notinuseTotalAmount=$notinuseTotalAmount+$notinuseAmount;
													$notinuseTotalItems=$notinuseTotalItems+$notinuseItems;
													$subnotinuseTotalAmount=$subnotinuseTotalAmount+$notinuseAmount;
                                                    $subnotinuseTotalItems=$subnotinuseTotalItems+$notinuseItems;
												}
											}
											$remainingAmount=$allcat->total_amount-($goodAmount+$damagedAmount+$scrappedAmount+$missingAmount+$shiftedAmount+$notinuseAmount);
											$remainingItems=$allcat->total_qty-($goodItems+$damagedItems+$scrappedItems+$missingItems+$shiftedItems+$notinuseItems);
											$subremainingTotalAmount=$subremainingTotalAmount+$remainingAmount;
                                            $subremainingTotalItems=$subremainingTotalItems+$remainingItems;
                                    
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
											<td><?php echo $remainingItems; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
											<th><?php echo "Sub Total"; ?></th>
											<td><?php echo $subtotalAmount!=0?getmoney_format(round(($subtotalAmount/100000),2)):$subtotalAmount; ?></td>
											<td><?php echo $subtotalItems; ?></td>
											<td><?php echo $subgoodTotalAmount!=0?getmoney_format(round(($subgoodTotalAmount/100000),2)):$subgoodTotalAmount; ?></td>
											<td><?php echo $subgoodTotalItems; ?></td>
											<td><?php echo $subdamagedTotalAmount!=0?getmoney_format(round(($subdamagedTotalAmount/100000),2)):$subdamagedTotalAmount; ?></td>
											<td><?php echo $subdamagedTotalItems; ?></td>
											<td><?php echo $subscrappedTotalAmount!=0?getmoney_format(round(($subscrappedTotalAmount/100000),2)):$subscrappedTotalAmount; ?></td>
											<td><?php echo $subscrappedTotalItems; ?></td>
											<td><?php echo $submissingTotalAmount!=0?getmoney_format(round(($submissingTotalAmount/100000),2)):$submissingTotalAmount; ?></td>
											<td><?php echo $submissingTotalItems; ?></td>
											<td><?php echo $subshiftedTotalAmount!=0?getmoney_format(round(($subshiftedTotalAmount/100000),2)):$subshiftedTotalAmount; ?></td>
											<td><?php echo $subshiftedTotalItems; ?></td>
											<td><?php echo $subnotinuseTotalAmount!=0?getmoney_format(round(($subnotinuseTotalAmount/100000),2)):$subnotinuseTotalAmount; ?></td>
											<td><?php echo $subnotinuseTotalItems; ?></td>
											<td><?php echo $subremainingTotalAmount!=0?getmoney_format(round(($subremainingTotalAmount/100000),2)):$subremainingTotalAmount; ?></td>
											<td><?php echo $subremainingTotalItems; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        $remainingTotalAmount=$remainingTotalAmount+$subremainingTotalAmount;
                                        $remainingTotalItems=$remainingTotalItems+$subremainingTotalItems;
                                        ?>
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
											<th><?php echo $remainingTotalItems; ?></th>
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