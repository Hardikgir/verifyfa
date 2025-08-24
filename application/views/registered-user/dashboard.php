<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('registered-user/layout/header');
$this->load->view('registered-user/layout/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
?>

<style>
	.card-header{
	    font-size: 20px;
    font-weight: bold;
    min-height: 67px;
	background: #5ca1e2 !important;
    color: #fff;
	min-height: 100px;
	}
	.card-header h2 {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}
	.card-txt{
		font-size: 50px;
	}
	.table-bordered th, .table-bordered td {
    border: 1px solid rgb(0 0 0);
	color: #000;
    padding: 10px;
}
 .txt-cardp{
	font-size: 20px;
    color: #000;
    font-weight: bold;
 }
.card-body-n{
	min-height: 200px !important;
}

	</style>

<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid">
	
		<div class="row">


		<div class="col-md-3">

<div class="card">
<div class="card-header">
	<h2>Subscription Plan Details</h2>
</div>
  <div class="card-body card-body-n" style="padding: 2px 1px;">
	<?php $plan_row=get_plan_row($plan_data->plan_id);?>
	<p class="txt-cardp" style="text-align: center;"><?php echo $plan_row->title;?></p>
	<ul>
		
		
		<li>Activation Date: <b><?php echo date("d-M-Y",strtotime($plan_data->plan_start_date));?></b></li>
		
		
	</ul>

	<p class="txt-cardp" style="text-align: center;">Plan Brief:</p>
	<ul>
		<li>No. of Entities – <b><?php echo $Subscription_plan->allowed_entities_no;?></b></li>
		<li>No. of Locations under each Entity – <b><?php echo $Subscription_plan->location_each_entity;?></b></li>
		<li>Total No. of Users – <b><?php echo $Subscription_plan->user_number_register;?></b></li>
		<li>No. of Rows for upload – <b><?php echo $Subscription_plan->line_item_avaliable;?></b></li>
	</ul>

  </div>
</div>

</div>


<div class="col-md-3">

<div class="card">
<div class="card-header">
	<h2>Current Subscription Valid till</h2>
</div>
  <div class="card-body card-body-n">
    <p class="txt-cardp" style="text-align: center;"><?php echo date("d-M-Y",strtotime($plan_data->plan_end_date));?></p>
    
  </div>
</div>

</div>


<div class="col-md-3">

<div class="card">
<div class="card-header ">
	<h2>Current Subscription Expiring in next</h2>
</div>
  <div class="card-body card-body-n">
  
	<p class="txt-cardp" style="text-align: center;">
		<?php
		if($plan_data->plan_end_date < date("Y-m-d")){

		}else{
		$time_remain=get_diff_twodate($plan_data->plan_end_date);
		?>
		<?php echo $time_remain;?> Left
		<?php } ?>
	</p>
	<!-- <p class="txt-cardp text-left pt-3">* added since April 2023</p> -->
  </div>
</div>

</div>


<div class="col-md-3">

<div class="card">
<div class="card-header">
	<h2>Subscription Amount Due(Rs.)</h2>
</div>
  <div class="card-body card-body-n">
   <p class="txt-cardp" style="text-align: center;"><?php echo $user_data->balance_due;?>/-</p>
  </div>
</div>
</div>
		

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