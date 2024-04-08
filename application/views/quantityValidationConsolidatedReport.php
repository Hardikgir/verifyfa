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
                                        <tr><th colspan="13" style="text-align:left;color:green;">Allocated Projects: </th></tr>
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
                                        foreach($data as $data)
                                        {
                                            $subtotalAmount=0;
											$subtotalItems=0;
											$subverifiedTotalAmount=0;
											$subverifiedTotalItems=0;
											$subshortTotalAmount=0;
											$subshortTotalItems=0;
											$subequalTotalAmount=0;
											$subequalTotalItems=0;
											$subexcessTotalAmount=0;
											$subexcessTotalItems=0;
											$subremainingTotalAmount=0;
											$subremainingTotalItems=0;
                                        ?>
                                            <tr><th colspan="13"  style="text-align:left;"><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionThreeReportAllocated/<?php echo  $data['project']->id; ?>" style="color:#5CA1E2;"><?php echo $data['project']->project_name;?>:</a></th></tr>
                                        <?php
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
                                                $subtotalAmount=$subtotalAmount+$allcat->total_amount;
                                                $subtotalItems=$subtotalItems+$allcat->total_items;
                                                $setCon=false;
                                                foreach($data['verified'] as $verified)
												{
													if($verified->item_category==$allcat->item_category)
													{
														if($verified->total_amount<=0)
														{
															$verified->total_items=0;
															$verified->total_amount=0;	
														}
														$verifiedAmount=$verified->total_amount;
														$verifiedItems=$verified->total_items;
														$verifiedTotalAmount=$verifiedTotalAmount+$verifiedAmount;
														$verifiedTotalItems=$verifiedTotalItems+$verifiedItems;
														$subverifiedTotalAmount=$subverifiedTotalAmount+$verifiedAmount;
														$subverifiedTotalItems=$subverifiedTotalItems+$verifiedItems;
														if($verified->total_items < $allcat->total_items && $verified->total_items > 0)
														{
															$shortAmount=$allcat->total_amount-$verified->total_amount;
															$shortItems=$allcat->total_items-$verified->total_items;
															$shortTotalAmount=$shortTotalAmount+$shortAmount;
															$shortTotalItems=$shortTotalItems+$shortItems;
															$subshortTotalAmount=$subshortTotalAmount+$shortAmount;
															$subshortTotalItems=$subshortTotalItems+$shortItems;
														}
														if($verified->total_items > $allcat->total_items)
														{
															$excessAmount=$allcat->total_amount-$verified->total_amount;
															$excessItems=$verified->total_items-$allcat->total_items;
															$excessTotalAmount=$excessTotalAmount+$excessAmount;
															$excessTotalItems=$excessTotalItems+$excessItems;
															$subexcessTotalAmount=$subexcessTotalAmount+$excessAmount;
															$subexcessTotalItems=$subexcessTotalItems+$excessItems;
														}
														if($verified->total_items < 1)
														{
															$remainingAmount=$allcat->total_amount;
															$remainingItems=$allcat->total_items;
															$subremainingTotalAmount=$subremainingTotalAmount+$remainingAmount;
															$subremainingTotalItems=$subremainingTotalItems+$remainingItems;	
														}
														$setCon=True;
														
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
														$subequalTotalAmount=$subequalTotalAmount+$equalAmount;
														$subequalTotalItems=$subequalTotalItems+$equalItems;
													}
												}
												if($setCon==false)
												{
													$remainingAmount=$allcat->total_amount;
													$remainingItems=$allcat->total_items;
													$subremainingTotalAmount=$subremainingTotalAmount+$remainingAmount;
													$subremainingTotalItems=$subremainingTotalItems+$remainingItems;
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
											<td><?php if($shortAmount>0){echo getmoney_format(round(($shortAmount/100000),2));}else if($shortAmount<0){ echo '-'.getmoney_format(round(($shortAmount/100000),2));}else {echo 0;} ?></td>
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
                                            <th><?php echo "Sub Total"; ?></th>
                                            <th><?php echo $subtotalAmount!=0?getmoney_format(round(($subtotalAmount/100000),2)):$subtotalAmount; ?></th>
											<th><?php echo $subtotalItems; ?></th>
											<th><?php echo $subverifiedTotalAmount!=0?getmoney_format(round(($subverifiedTotalAmount/100000),2)):$subverifiedTotalAmount; ?></th>
											<th><?php echo $subverifiedTotalItems; ?></th>
											<th><?php echo $subequalTotalAmount!=0?getmoney_format(round(($subequalTotalAmount/100000),2)):$subequalTotalAmount; ?></th>
											<th><?php echo $subequalTotalItems; ?></th>
											<th><?php if($subshortTotalAmount>0){echo getmoney_format(round(($subshortTotalAmount/100000),2));}else if($subshortTotalAmount<0){ echo '-'.getmoney_format(round(($subshortTotalAmount/100000),2));}else {echo 0;} ?></th>
											<th><?php echo $subshortTotalItems; ?></th>
											<th><?php if($subexcessTotalAmount>0){echo getmoney_format(round(($subexcessTotalAmount/100000),2));}else if($subexcessTotalAmount<0){ echo '-'.getmoney_format(round(($subexcessTotalAmount/100000),2));}else {echo 0;} ?></th>
											<th><?php echo $subexcessTotalItems; ?></th>
											<th><?php echo $subremainingTotalAmount!=0?getmoney_format(round(($subremainingTotalAmount/100000),2)):$subremainingTotalAmount; ?></th>
											<th><?php echo $subremainingTotalItems; ?></th>
                                        </tr>
                                        <?php
                                        
                                        $remainingTotalAmount=$remainingTotalAmount+$subremainingTotalAmount;
                                        $remainingTotalItems=$remainingTotalItems+$subremainingTotalItems;
                                        }
                                        ?>
										<tr>
											<th><?php echo "Grand Total"; ?></th>
											<th><?php echo $totalAmount!=0?getmoney_format(round(($totalAmount/100000),2)):$totalAmount; ?></th>
											<th><?php echo $totalItems; ?></th>
											<th><?php echo $verifiedTotalAmount!=0?getmoney_format(round(($verifiedTotalAmount/100000),2)):$verifiedTotalAmount; ?></th>
											<th><?php echo $verifiedTotalItems; ?></th>
											<th><?php echo $equalTotalAmount!=0?getmoney_format(round(($equalTotalAmount/100000),2)):$equalTotalAmount; ?></th>
											<th><?php echo $equalTotalItems; ?></th>
											<th><?php if($shortTotalAmount>0){echo getmoney_format(round(($shortTotalAmount/100000),2));}else if($shortTotalAmount<0){ echo '-'.getmoney_format(round(($shortTotalAmount/100000),2));}else {echo 0;} ?></th>
											<th><?php echo $shortTotalItems; ?></th>
											<th><?php if($excessTotalAmount>0){echo getmoney_format(round(($excessTotalAmount/100000),2));}else if($excessTotalAmount<0){ echo '-'.getmoney_format(round(($excessTotalAmount/100000),2));}else {echo 0;} ?></th>
											<th><?php echo $excessTotalItems; ?></th>
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