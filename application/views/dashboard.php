<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
?>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid">
	
    	
    
		<div class="row">
			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
					<div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
						<div id="carousel-example-1" class="carousel no-flex testimonial-carousel slide carousel-fade" data-ride="carousel" data-interval="false">
							<!--Slides-->
							<div class="carousel-inner" role="listbox">
								<!--First slide-->
								<div class="carousel-item active">
									<div class="testimonial">
										<div class="row" id="#myCarousel">
											<canvas id="myChart" height="140"></canvas>
										</div>
									</div>
								</div>
								<!--First slide-->
								<!--Second slide-->
								<div class="carousel-item">
									<div class="testimonial">
										<!--Avatar-->
										<!-- <div class="content">
											<div class="container-fluid"> -->
												<div class="row">
													<div class="col-md-12">
														<nav>
															<div class="nav nav-tabs nav-fill nav-justified" id="nav-tab" role="tablist">
																<a class="nav-item nav-link active" href="#div1" id="tab1" data-toggle="tab" aria-controls="open-project" aria-selected="true">Open Projects</a>
																<a class="nav-item nav-link" href="#div2" id="tab2" data-toggle="tab" aria-controls="closed-project" aria-selected="true"><span id="tab-text">Closed Projects</span></a>
																<a class="nav-item nav-link" href="#div3" data-toggle="tab" aria-controls="cancelled-project" aria-selected="true"><span id="tab-text">Cancelled Projects</span></a>
															</div>
														</nav>
														<!-- <ul class="nav nav-tabs nav-justified ">
															<li class="nav-item"><a class="nav-link active table-active " href="#div1" id="tab1" data-toggle="tab" aria-controls="open-project" aria-selected="true">Open Projects</a>
															</li>
															<li class="nav-item"><a class="nav-link tab-click" href="#div2" id="tab2" data-toggle="tab" aria-controls="closed-project" aria-selected="true"><span id="tab-text">Closed Projects</span></a>
															</li>
															<li class="nav-item"><a class="nav-link tab-click" href="#div3" data-toggle="tab" aria-controls="cancelled-project" aria-selected="true"><span id="tab-text">Cancelled Projects</span></a>
															</li>
														</ul> -->
														<div class="tab-content pt-5">
															<div id="div1" class="tab-pane in active" aria-labelledby="open-project">
																<div class="card">
																	<div class="card-header card-header-primary">
																		<h4 class="card-title">Open Projects</h4>
																		<p class="card-category">Showing all open projects</p>
																	</div>
																	<div class="card-body">
																		<div class="table-responsive">
																			<table class="table">
																				<thead class=" text-center">
																					<th>#</th>
																					<th> <span>Project ID</span> 
																					</th>
																					<th> <span>Project Name</span> 
																					</th>
																					<th> <span>Date of Project assigned</span> 
																					</th>
																					<th> <span>Due Date</span> 
																					</th>
																					<th> <span>Remaining/(Overdue) Day </span> 
																					</th>
																					<th> <span>Stage of Completion </span>
																					</th>
																				</thead>
																				<tbody>
																					<?php 
																					$i=0;
																					foreach($projects as $pro)
																					{
																						if($pro->status==0 || $pro->status==3)
																						{

																						
																					?>
																							<tr class="text-center">
																								<td><?php echo ++$i; ?></td>
																								<td><?php echo $pro->project_id;?></td>
																								<td><a href="<?php echo base_url();?>index.php/dashboard/projectdetail/<?php echo $pro->id; ?>"><?php echo $pro->project_name;?></a></td>
																								<td><?php echo date_format(date_create($pro->start_date),'d/m/Y');?></td>
																								<td><?php echo date_format(date_create($pro->due_date),'d/m/Y');?></td>
																								<td><?php $interval=date_diff(date_create($pro->start_date), date_create($pro->due_date)); 
  																								// printing result in days format 
																								echo $interval->format('%R%a days');  ?></td>
																								<td><?php echo round(($pro->VerifiedQuantity/$pro->TotalQuantity)*100,2).' %'; ?></td>
																							</tr>
																					<?php 
																							
																							}
																							
																						}
																						if($i==0)
																						{
																							"<tr><td colspan='6'><strong>Projects are not available.</strong></td></tr>";
																						}
																					?>
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
															</div>
															<!--seccion table-2-->
															<div id="div2" class="tab-pane" aria-labelledby="closed-project">
																<div class="card">
																	<div class="card-header card-header-primary">
																		<h4 class="card-title ">Closed Projects</h4>
																		<p class="card-category">Showing all closed projects</p>
																	</div>
																	<div class="card-body">
																		<div class="table-responsive">
																			<table class="table">
																				<thead class=" text-center">
																					<th>#</th>
																					<th> <span> Project ID  <i class="fas fa-sort"></i></span> 
																					</th>
																					<th> <span>Project Name  <i class="fas fa-sort"></i> </span> 
																					</th>
																					<th> <span>Date of Project assigned <i class="fas fa-sort"></i> </span> 
																					</th>
																					<th> <span>Due Date  <i class="fas fa-sort"></i> </span> 
																					</th>
																					<th> <span>Completion Date  <i class="fas fa-sort"></i></span> 
																					</th>
																					<th> <span>No. of Days Taken  <i class="fas fa-sort"></i></span>
																					</th>
																				</thead>
																				<tbody>
																				<?php 
																					$i=0;
																					foreach($projects as $pro)
																					{
																						if($pro->status==1)
																						{

																						
																					?>
																							<tr class="text-center">
																								<td><?php echo $i; ?></td>
																								<td><?php echo $pro->project_id;?></td>
																								<td><a href="<?php echo base_url();?>/index.php/dashboard/projectdetail"><?php echo $pro->project_name;?></a></td>
																								<td><?php echo date_format(date_create($pro->start_date),'d/m/Y');?></td>
																								<td><?php echo date_format(date_create($pro->due_date),'d/m/Y');?></td>
																								<td>
																									<?php echo date_format(date_create($pro->finish_datetime), 'd/m/Y H:s:i'); 
																									?>  
																								</td>
																								<td><?php $interval=date_diff(date_create($pro->start_date), date_create($pro->finish_datetime)); 
  																								// printing result in days format 
																								echo $interval->format('%R%a days');  ?></td>
																							</tr>
																					<?php 
																							$i++;
																							}
																							
																						}
																						if($i==0)
																						{
																							echo "<tr><td colspan='6'><strong>Projects are not closed yet.</strong></td></tr>";
																						}
																					?>
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
															</div>
															<!--seccion table-3-->
															<div id="div3" class="tab-pane" aria-labelledby="cancelled-project">
																<div class="card">
																	<div class="card-header card-header-primary">
																		<h4 class="card-title ">Cancelled Projects</h4>
																		<p class="card-category">Showing all cancelled projects</p>
																	</div>
																	<div class="card-body">
																		<div class="table-responsive">
																			<table class="table">
																				<thead class="text-center">
																					<th>#</th>
																					<th> <span> Project ID  <i class="fas fa-sort"></i></span> 
																					</th>
																					<th> <span>Project Name  <i class="fas fa-sort"></i> </span> 
																					</th>
																					<th> <span>Date of Project assigned <i class="fas fa-sort"></i> </span> 
																					</th>
																					<th> <span>Due Date  <i class="fas fa-sort"></i> </span> 
																					</th>
																					<th> <span>Cancellation Date  <i class="fas fa-sort"></i></span> 
																					</th>
																					<th> <span>Reason for Cancellation  <i class="fas fa-sort"></i></span>
																					</th>
																				</thead>
																				<tbody>
																				<?php 
																					$i=0;
																					foreach($projects as $pro)
																					{
																						if($pro->status==2)
																						{

																						
																					?>
																							<tr class="text-center">
																								<td><?php echo $i; ?></td>
																								<td><?php echo $pro->project_id;?></td>
																								<td><a href="<?php echo base_url();?>index.php/dashboard/projectdetail"><?php echo $pro->project_name;?></a></td>
																								<td><?php echo date_format(date_create($pro->start_date),'d/m/Y');?></td>
																								<td><?php echo date_format(date_create($pro->due_date),'d/m/Y');?></td>
																								<td><?php  echo date_format(date_create($pro->cancelled_date),'d/m/Y');  ?></td>
																								<td><?php echo $pro->cancel_reason;?></td>
																							</tr>
																					<?php 
																							$i++;
																							}
																							
																						}
																						if($i==0)
																						{
																							echo "<tr><td colspan='6'><strong>Projects are not cancelled yet.</strong></td></tr>";
																						}
																					?>
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											<!-- </div>
										</div> -->
									</div>
								</div>
								<!--Second slide-->
							</div>
						</div>
						<!--Controls-->
						<ol class="carousel-indicators p-1" id="change">
							<li data-target="#carousel-example-1" data-slide-to="0" class="active mr-1"></li>
							<li data-target="#carousel-example-1" data-slide-to="1" class=""></li>
							
						</ol>	
					</div>			
				</section>
				</div>
			</div>
			<!-- Section: Testimonials v.2 -->
		
		</div>	
		
			</div>
		</div>
	</div>
	
</div>	
<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/dashboard_script');
?>
	<script src="<?php echo base_url();?>assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
<script>
	$(document).ready(function() {
		      // Javascript method's body can be found in assets/js/demos.js
		     
		      md.initDashboardPageCharts();
		      
		    });
	</script>
<?php
$this->load->view('layouts/footer');
?>