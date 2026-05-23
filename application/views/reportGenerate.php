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
                                    		<a href="<?php echo base_url(); ?>index.php/dashboard/reports"><button class="btn btn-round pull-right">Back</button></a>
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
                                                    <label>Report Type:</label>
                                                    <input type="text" class="form-control" value="Scope Summary">
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
                                                    <input type="text" class="form-control" value="<?php echo date('d/m/Y',strtotime($data['project'][0]->start_date));?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Due Date:</label>
                                                    <input type="text" class="form-control" value="<?php echo date('d/m/Y',strtotime($data['project'][0]->due_date));?>">
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
									</div>
									</div>
									
									<div class="clearfix"></div>
								</form>
								<div class="col-md-12">
									<table border="1">
										<tr>
											<th rowspan="2">Allocated Item Category</th>
											<th colspan="2">Total as per FAR</th>
											
											<th colspan="2">Tagged</th>
											<th colspan="2">Non-Tagged</th>
											<th colspan="2">Unspecified</th>
											
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
										</tr>
										<?php
										$totalAmount=0;
										$totalItems=0;
										$taggedTotalAmount=0;
										$taggedTotalItems=0;
										$nontaggedTotalAmount=0;
										$nontaggedTotalItems=0;
										$unspecifiedTotalAmount=0;
										$unspecifiedTotalItems=0;
										foreach($data['all'] as $allcat)
										{
											$taggedAmount=0;
											$taggedItems=0;
											$nontaggedAmount=0;
											$nontaggedItems=0;
											$unspecifiedAmount=0;
											$unspecifiedItems=0;
											$totalAmount=$totalAmount+$allcat->total_amount;
											$totalItems=$totalItems+$allcat->items;
											foreach($data['tagged'] as $tagged)
											{
												if($tagged->item_category==$allcat->item_category)
												{
													$taggedAmount=$tagged->total_amount;
													$taggedItems=$tagged->items;
													$taggedTotalAmount=$taggedTotalAmount+$taggedAmount;
													$taggedTotalItems=$taggedTotalItems+$taggedItems;
												}
											}
											foreach($data['nontagged'] as $nontagged)
											{
												if($nontagged->item_category==$allcat->item_category)
												{
													$nontaggedAmount=$nontagged->total_amount;
													$nontaggedItems=$nontagged->items;
													$nontaggedTotalAmount=$nontaggedTotalAmount+$nontaggedAmount;
													$nontaggedTotalItems=$nontaggedTotalItems+$nontaggedItems;
												}
											}
											foreach($data['unspecified'] as $unspecified)
											{
												if($unspecified->item_category==$allcat->item_category)
												{
													$unspecifiedAmount=$unspecified->total_amount;
													$unspecifiedItems=$unspecified->items;
													$unspecifiedTotalAmount=$unspecifiedTotalAmount+$unspecifiedAmount;
													$unspecifiedTotalItems=$unspecifiedTotalItems+$unspecifiedItems;
												}
											}
										
										?>
										<tr>
											<td><?php echo $allcat->item_category; ?></td>
											<td><?php echo $allcat->total_amount!=0?getmoney_format(round(($allcat->total_amount/100000),2)):$allcat->total_amount; ?></td>
											<td><?php echo $allcat->items; ?></td>
											<td><?php echo $taggedAmount!=0?getmoney_format(round(($taggedAmount/100000),2)):$taggedAmount; ?></td>
											<td><?php echo $taggedItems; ?></td>
											<td><?php echo $nontaggedAmount!=0?getmoney_format(round(($nontaggedAmount/100000),2)):$nontaggedAmount;?></td>
											<td><?php echo $nontaggedItems; ?></td>
											<td><?php echo $unspecifiedAmount!=0?getmoney_format(round(($unspecifiedAmount/100000),2)):$unspecifiedAmount; ?></td>
											<td><?php echo $unspecifiedItems; ?></td>
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
										</tr>
										<tr>
											<th><?php echo "Grand Total"; ?></th>
											<th><?php echo $totalAmount!=0?getmoney_format(round(($totalAmount/100000),2)):$totalAmount; ?></th>
											<th><?php echo $totalItems; ?></th>
											<th><?php echo $taggedTotalAmount!=0?getmoney_format(round(($taggedTotalAmount/100000),2)):$taggedTotalAmount; ?></th>
											<th><?php echo $taggedTotalItems; ?></th>
											<th><?php echo $nontaggedTotalAmount!=0?getmoney_format(round(($nontaggedTotalAmount/100000),2)):$nontaggedTotalAmount; ?></th>
											<th><?php echo $nontaggedTotalItems; ?></th>
											<th><?php echo $unspecifiedTotalAmount!=0?getmoney_format(round(($unspecifiedTotalAmount/100000),2)):$unspecifiedTotalAmount; ?></th>
											<th><?php echo $unspecifiedTotalItems; ?></th>
										</tr>
										<tr>
											<th><?php echo "% to total FAR"; ?></th>
											<th>100%</th>
											<th>100%</th>
											<th><?php echo round(($taggedTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($taggedTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($nontaggedTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($nontaggedTotalItems/$totalItems)*100,2); ?>%</th>
											<th><?php echo round(($unspecifiedTotalAmount/$totalAmount)*100,2); ?>%</th>
											<th><?php echo round(($unspecifiedTotalItems/$totalItems)*100,2); ?>%</th>
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
										</tr>
										<tr>
											<th>Click for detailed report</th>
											<th colspan="2"><?php if($totalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadProjectReportFAR/<?php echo $_POST['location_id'];?>">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($taggedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadProjectReportTagged/<?php echo $_POST['location_id'];?>">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($nontaggedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadProjectReportNonTagged/<?php echo $_POST['location_id'];?>">Download as Annexure</a><?php }?></th>
											<th colspan="2"><?php if($unspecifiedTotalAmount!=0){?><a href="<?php echo base_url(); ?>index.php/dashboard/downloadProjectReportUnspecified/<?php echo $_POST['location_id'];?>">Download as Annexure</a> <?php }?></td>
										</tr>
										
									</table>
								</div>
								<?php
								}
								?>
								<div class="col-md-12 text-center mt-3"><a href="<?php echo base_url(); ?>index.php/dashboard/reports"><button class="btn btn-round btn-blue">Back</button></a></div>
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