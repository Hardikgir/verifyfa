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
											<th>Allocated Item Category</th>
											<th>Verified by Scan</th>
											<th>Verified by Manual Search</th>
											
											
										</tr>
										
                                        <tr><th colspan="3" style="text-align:left;color:green;">Allocated Projects: </th></tr>
                                        <?php
                                        $totalManualItems=0;
										$totalScanItems=0;
										foreach($data as $data)
                                        {
                                            $subtotalManualItems=0;
											$subtotalScanItems=0;
                                            
                                        ?>
                                            <tr><th colspan="3"  style="text-align:left;"><a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionEightConsolidatedReport/<?php echo  $data['project']->id; ?>" style="color:#5CA1E2;"><?php echo $data['project']->project_name;?>:</a></th></tr>
                                        <?php
                                            
											foreach($data['all'] as $allcat)
											{
												$manualItems=0;
												$scanItems=0;
												foreach($data['manual'] as $manual)
												{
													$manualItems=$manual->items;
												}
												foreach($data['scan'] as $scan)
												{
													$scanItems=$scan->items;
												}
												$subtotalManualItems=$subtotalManualItems+$manualItems;
												$subtotalScanItems=$subtotalScanItems+$scanItems;
												$totalManualItems=$totalManualItems+$manualItems;
												$totalScanItems=$totalScanItems+$scanItems;
										?>
										<tr>
											<td><?php echo $allcat->item_category; ?></td>
											<td><?php echo $scanItems; ?></td>
											<td><?php echo $manualItems;  ?></td>
										</tr>
										<?php
                                            }
                                            ?>
                                        <tr>
                                            <th><?php echo "Sub Total"; ?></th>
                                            <td><?php echo $subtotalScanItems; ?></td>
                                            <td><?php echo $subtotalManualItems; ?></td>
											
                                        </tr>
                                        <?php
                                        }
                                        
                                        ?>
                                        <tr>
											<th></th>
											<th></th>
											<th></th>
											
										</tr>
										
										<tr>
											<th><?php echo "Grand Total"; ?></th>
											<td><?php echo $totalScanItems; ?></td>
                                            <td><?php echo $totalManualItems; ?></td>
											
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