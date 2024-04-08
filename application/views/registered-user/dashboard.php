<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('registered-user/layout/header');
$this->load->view('registered-user/layout/sidebar');
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
$this->load->view('registered-user/layout/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('registered-user/layout/footer');
?>