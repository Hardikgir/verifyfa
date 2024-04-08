<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('super-admin/layout/header');
$this->load->view('super-admin/layout/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
?>
<style>
	.card-header{
	    font-size: 20px;
    font-weight: bold;
    min-height: 67px;
    margin: auto;
	background: #5ca1e2 !important;
    color: #fff;
	min-height: 100px;
	}
	.card-header h2{
	font-size: 20px;
    font-weight: bold;
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
<div  id="#myCarousel">
<div class="row">
<div class="col-md-3">

<div class="card">
<div class="card-header">
	<h2>Total No. of ‘Active’
Subscription Plans</h2>
</div>
  <div class="card-body card-body-n">
    <h5 class="card-title card-txt">10</h5>
    
  </div>
</div>

</div>


<div class="col-md-3">

<div class="card">
<div class="card-header">
	<h2>Total No. of Registrations</h2>
</div>
  <div class="card-body card-body-n">
    <h5 class="card-title card-txt">99999</h5>
    
  </div>
</div>

</div>


<div class="col-md-3">

<div class="card">
<div class="card-header ">
	<h2>Total No. of Registered
‘Active’ Subscriptions
</h2>
</div>
  <div class="card-body card-body-n">
  <h5 class="card-title card-txt">99999</h5>
<p class="txt-cardp">+654*</p>
<p class="txt-cardp text-left pt-3">* added since April 2023</p>
  </div>
</div>

</div>


<div class="col-md-3">

<div class="card">
<div class="card-header">
	<h2>Total No. of Subscriptions
expiring in
</h2>
</div>
  <div class="card-body p-0 card-body-n" >
    <table class="table-bordered">
		<thead>
			<th>< 30d</th>
			<th>31d – 60d</th>
			<th>61d – 90d</th>
			<th>91d – 120d</th>
		</thead>

		<tbody>
			<td>< 30d</td>
			<td>31d – 60d</td>
			<td>61d – 90d</td>
			<td>91d – 120d</td>
		</tbody>

	</table>
  </div>
</div>
</div>
</div>

<div class="row">
<div class="col-md-3"></div>


<div class="col-md-3">

<div class="card">
<div class="card-header ">
	<h2>Total No. of Registrations Unsubscribed</h2>
</div>
  <div class="card-body card-body-n">
  <h5 class="card-title card-txt">99999</h5>
<p class="txt-cardp">-65*</p>
<p class="txt-cardp text-left pt-3">* unsubscribed since April 2023</p>
  </div>
</div>

</div>


<div class="col-md-3">
<div class="card">
<div class="card-header">
	<h2>Total No. of Registered
Users where Subscription
Link ‘Expired’</h2>
</div>
  <div class="card-body card-body-n">
    <h5 class="card-title card-txt">99999</h5>
  </div>
</div>

</div>





<div class="col-md-3">


</div>
</div>
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
													<div class="col-md-6" style="background:#fffbef;">
													<form>
													<div class="row">
													  <div class="col-md-3">
														<label style="color: #000;font-weight: bold;"><br>Trend for last</label>
													  </div>
														<div class="col-md-4">
														<select class="form-control">
															<option value="">Select Days</option>
															<option value="">Last 7 Days</option>
															<option value="">Last 15 Days</option>
															<option value="">Last 30 Days</option>
															<option value="">Last 60 Days</option>
                                                        </select>
													   </div>
													   <div class="col-md-1">
													  	<button class="btn btn-primary" style="background: #5ca1e2;">Go</button>
													   </div> 
													</div>
                                                    </form>

													<form>
													<div class="row p-2">
													  
														<div class="col-md-5 text-left">
														  <lable style="color: #000;font-weight: bold;">From Date</lable>
														  <input type="date" class="form-control" name="from_date">
													   </div>
													   <div class="col-md-5  text-left">
														  <lable style="color: #000;font-weight: bold;">To Date</lable>
														  <input type="date" class="form-control" name="to_date">
													   </div>
													   <div class="col-md-2">
													  	<button class="btn btn-primary" style="background: #5ca1e2;">Go</button>
													   </div> 
													</div>
                                                    </form>
													<hr>
													<canvas id="myChart" style="width:100%;max-width:100%;background:#fffbef;"></canvas>
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
$this->load->view('super-admin/layout/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('super-admin/layout/footer');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
const xValues = ['Day 1','Day 2','Day 3','Day 4','Day 5','Day 6','Day 7','Day 8','Day 9','Day 10'];

new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{ 
      data: [860,1140,1060,1060,1070,1110,1330,2210,7830,2478],
      borderColor: "red",
      fill: false,
	  label: "Orignal"

    }, { 
      data: [1600,1700,1700,1900,2000,2700,4000,5000,6000,7000],
      borderColor: "green",
      fill: false,
	  label: "Renewal"

    }, { 
      data: [300,700,2000,5000,6000,4000,2000,1000,200,100],
      borderColor: "blue",
      fill: false,
	  label: "Resubscription"

    }]
  },
  options: {
    legend: {display: true}
  }
});
</script>