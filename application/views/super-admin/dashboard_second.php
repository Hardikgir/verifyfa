
<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    $this->load->view('super-admin/layout/header');
    $this->load->view('super-admin/layout/sidebar');

?>
<style>
    .divtextn{
        text-align: center;
    color: #9d9191;
    font-size: 65px;
    margin-top: 20%;
    font-weight: bold;
    }
    </style>

    <style>
	.card-header{
	    font-size: 20px;
    font-weight: bold;
    min-height: 67px;
    /* margin: auto; */
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
<div class="content">

<?php
    // $usercntrole=Count_user_role();
?>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>


    <div class="container-fluid">

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


    <div class="row mt-5">


        <div class="col-md-12">
            <?php 

                

            ?>
        </div>

        <div class="col-md-12">
            <div id="SubscriptionTrendChart" style="height: 370px; width: 100%; margin: 0px auto;"></div>
        </div>

        <div class="col-md-12 mt-5">
			<div id="TypeSubscriptionActiveChart" style="height: 370px; width: 100%;"></div>
        </div>

		<div class="col-md-12 mt-5">
            <div id="SubscriptionAmountDueChart" style="height: 370px; width: 100%;"></div>
        </div>

        <div class="col-md-12 mt-5">
            <div id="SubscriptionAmountDueAgeingAnalysisChart" style="height: 370px; width: 100%;"></div>
        </div>

        





    </div>



    </div>
</div>


<?php 


?>


  <script>
window.onload = function () {

var chart = new CanvasJS.Chart("SubscriptionTrendChart", {
	theme:"light2",
	animationEnabled: true,
	title:{
		text: "Subscription Trend"
	},
	axisY :{
		title: "",
		suffix: ""
	},
	toolTip: {
		shared: "true"
	},
	legend:{
		cursor:"pointer",
		itemclick : toggleDataSeries
	},
	data: [
	{
		type: "spline",
		showInLegend: true,
		yValueFormatString: "##.00",
		name: "Original",
		dataPoints: <?php echo json_encode($register_users, JSON_NUMERIC_CHECK); ?>
	},
	{
		type: "spline",
		showInLegend: true,
		yValueFormatString: "##.00",
		name: "Renewals",
		dataPoints: [
			{ label: "Day 1", y: 7.94 },
			{ label: "Day 2", y: 7.29 },
			{ label: "Day 3", y: 7.28 },
			{ label: "Day 4", y: 7.82 },
			{ label: "Day 5", y: 7.89 },
			{ label: "Day 6", y: 6.71 },
			{ label: "Day 7", y: 7.80 },
			{ label: "Day 8", y: 7.60 },
			{ label: "Day 9", y: 7.66 },
			{ label: "Day 10", y: 8.89 }
		]
	},
	{
		type: "spline",
		showInLegend: true,
		yValueFormatString: "##.00",
		name: "Resubscriptions",
		dataPoints: [
			{ label: "Day 1", y: 10.11 },
			{ label: "Day 2", y: 9.27 },
			{ label: "Day 3", y: 9.25 },
			{ label: "Day 4", y: 10.17 },
			{ label: "Day 5", y: 10.72 },
			{ label: "Day 6", y: 10.24 },
			{ label: "Day 7", y: 12.07 }
		]
	}]
});
chart.render();

function toggleDataSeries(e) {
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
		e.dataSeries.visible = false;
	} else {
		e.dataSeries.visible = true;
	}
	chart.render();
}

// }








// window.onload = function () {

var chart = new CanvasJS.Chart("TypeSubscriptionActiveChart", {
	animationEnabled: true,
	title:{
		text: "Type of Subscription Plans / Active Subscriptions"
	},
	axisX: {
		interval: 1,
		valueFormatString: "Sub Type"
	},
	/*
	axisX: [
		{
			interval: 1,
			valueFormatString: "Sub Type 12s",
		},
		{
			interval: 2,
			valueFormatString: "Sub Type 12s asdasd",
		},
		{
			interval: 3,
			valueFormatString: "Sub Type 12s asd",
		}
	], */
	axisY: {
		suffix: ""
	},
	toolTip: {
		shared: true
	},
	legend: {
		reversed: true,
		verticalAlign: "center",
		horizontalAlign: "right"
	},
	/*
	data: [{
		type: "stackedColumn100",
		name: "Resubscriptions",
		showInLegend: true,
		xValueFormatString: "Sub Type 1",
		yValueFormatString: "#,##0\"%\"",
		dataPoints: [
			{ x: 1, y: 40 },
			{ x: 2, y: 50 },
			{ x: 3, y: 60 },
			

		]
	},
	{
		type: "stackedColumn100",
		name: "Renewals",
		showInLegend: true,
		xValueFormatString: "Sub Type 2",
		yValueFormatString: "#,##0\"%\"",
		dataPoints: [
			{ x: 1, y: 10 },
			{ x: 2, y: 18 },
			{ x: 3, y: 12 },
			

		]
	},
	{
		type: "stackedColumn100",
		name: "Original",
		showInLegend: true,
		xValueFormatString: "Sub Type 3",
		yValueFormatString: "#,##0\"%\"",
		dataPoints: [
			{ x: 1, y: 15 },
			{ x: 2, y: 12 },
			{ x: 3, y: 10 },
			

		]
	}] */


	data: <?php echo json_encode($TypeSubscriptionActiveChart_array, JSON_NUMERIC_CHECK); ?> 

});
chart.render();



var chart = new CanvasJS.Chart("SubscriptionAmountDueChart", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Subscription Amount Due"
	},
  	axisY: {
      includeZero: true
    },
	axisX: {
		interval: 1,
		valueFormatString: "#"
	},
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		//indexLabel: "{y}", //Shows y value on all Data Points
		indexLabelFontColor: "#000",
      	indexLabelFontSize: 16,
		indexLabelPlacement: "outside",
		dataPoints: [
			{ x: 1, y: 71,label: "10000 (min) - 10000",color: "#4f81bc" },
			{ x: 2, y: 10,label: "10001 - 20000",color: "#4f81bc" },
			{ x: 3, y: 50,label: "20001 - 30000",color: "#4f81bc" },
			{ x: 4, y: 25,label: "30001 - 40000",color: "#4f81bc" },
			{ x: 5, y: 92,label: "40001 - 50000",color: "#4f81bc"},
			{ x: 6, y: 35,label: "50001 - 60000 (max)",color: "#4f81bc" },
		]
	}]
});
chart.render();






var chart = new CanvasJS.Chart("SubscriptionAmountDueAgeingAnalysisChart", {
	animationEnabled: true,
	theme: "light2",
	title: {
		text: "Subscription Amount Due – Ageing Analysis"
	},
	axisX: {
		valueFormatString: "MMM"
	},
	axisY: {
		prefix: "",
		labelFormatter: addSymbols
	},
	toolTip: {
		shared: true
	},
	legend: {
		cursor: "pointer",
		itemclick: toggleDataSeries
	},
	data: [
	{
		type: "column",
		name: "Actual Sales",
		showInLegend: true,
		xValueFormatString: "MMMM YYYY",
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: 0, y: 20,label: "0 (Min) - 30" },
			{ x: 1, y: 30,label: "30-60" },
			{ x: 2, y: 25,label: "61-90" },
			{ x: 3, y: 70,label: "91-120" },
			{ x: 4, y: 50,label: "121-150" },
			{ x: 5, y: 35,label: "151-180" },

		]
	},
	{
		type: "line",
		name: "Expected Sales",
		showInLegend: true,
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: 0, y: 40,label: "0 (Min) - 30"},
			{ x: 1, y: 2,label: "30-60"},
			{ x: 2, y: 45,label: "61-90"},
			{ x: 3, y: 45,label: "91-120"},
			{ x: 4, y: 47,label: "121-150"},
			{ x: 5, y: 43,label: "151-180"},
		]
	}]
});
chart.render();

function addSymbols(e) {
	var suffixes = ["", "K", "M", "B"];
	var order = Math.max(Math.floor(Math.log(Math.abs(e.value)) / Math.log(1000)), 0);

	if(order > suffixes.length - 1)
		order = suffixes.length - 1;

	var suffix = suffixes[order];
	return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
}

function toggleDataSeries(e) {
	if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	} else {
		e.dataSeries.visible = true;
	}
	e.chart.render();
}





}
</script>


<?php
    $this->load->view('layouts/scripts');
    // $this->load->view('layouts/dashboard_script');
    $this->load->view('layouts/footer');
?>
