
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');


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
<div class="content">

<?php 
$usercntrole=Count_user_role();
if($this->main_role != '5'){
if($usercntrole == 0){ 
	?>	
    <div class="divtextn">
	No Role Assigned.
    </div>
    <?php
$this->load->view('layouts/scripts');
// $this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
<?php  die;}?>
<!-- <div class="divtextn">
	No Role Assigned.
    </div> -->
<?php }?>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>


    <div class="container-fluid">
        <form action="javascript:void(0)" method="post" class="bg-white">
                <br>
                <div class="row">
                <div class="col-md-2 form-row">
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">Select Company</label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        <option value="">All</option>
                        <?php foreach($company_data_list as $row_com_list){ 
                             $company_n=get_company_row($row_com_list->company_id);
                            ?>
                        <option value="<?php echo $company_n->id;?>"><?php echo $company_n->company_name.'('. $company_n->short_code.')';?></option>

                        <?php }?>
                    </select>
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">Select Location</label>
                    <select name="location_id" id="company_location" class="form-control">
                        <option value="">All</option>
                    </select>
                </div>
                <div class="col-md-2 form-row">
                <button type="submit" class="btn btn-success">GO</button>
              </div>
                </div><br>
               
                
                <br>
        </form>
    


    <div class="row">
        <div class="col-md-12">
            <div id="Spline_chartContainer" style="height: 370px; width: 100%; margin: 0px auto;"></div>
        </div>  

        <div class="col-md-12 mt-5">
            <div id="ChartsGraphs_chartContainer" style="height: 370px; width: 100%;"></div>
        </div> 
        
        <div class="col-md-12 mt-5">
            <div id="IndexDataLabelchartContainer" style="height: 370px; width: 100%;"></div>
        </div> 

        <div class="col-md-12 mt-5">
            <div id="LineAreaColumnchartContainer" style="height: 370px; width: 100%;"></div>
        </div> 

        <div class="col-md-12 mt-5">
            <div id="RangeBarchartContainer" style="height: 370px; width: 100%;"></div>
        </div> 
        

        <div class="col-md-12 mt-5">
            <div id="stackedBarchartContainer" style="height: 370px; width: 100%;"></div>
        </div> 

        

        


    </div>


        
    </div>
</div>



  <script>
window.onload = function () {

var chart = new CanvasJS.Chart("Spline_chartContainer", {
	theme:"light2",
	animationEnabled: true,
	title:{
		text: "Spline Charts"
	},
	axisY :{
		title: "Number of Viewers",
		suffix: "mn"
	},
	toolTip: {
		shared: "true"
	},
	legend:{
		cursor:"pointer",
		itemclick : toggleDataSeries
	},
	data: [{
		type: "spline",
		visible: false,
		showInLegend: true,
		yValueFormatString: "##.00mn",
		name: "Season 1",
		dataPoints: [
			{ label: "Ep. 1", y: 2.22 },
			{ label: "Ep. 2", y: 2.20 },
			{ label: "Ep. 3", y: 2.44 },
			{ label: "Ep. 4", y: 2.45 },
			{ label: "Ep. 5", y: 2.58 },
			{ label: "Ep. 6", y: 2.44 },
			{ label: "Ep. 7", y: 2.40 },
			{ label: "Ep. 8", y: 2.72 },
			{ label: "Ep. 9", y: 2.66 },
			{ label: "Ep. 10", y: 3.04 }
		]
	},
	{
		type: "spline", 
		showInLegend: true,
		visible: false,
		yValueFormatString: "##.00mn",
		name: "Season 2",
		dataPoints: [
			{ label: "Ep. 1", y: 3.86 },
			{ label: "Ep. 2", y: 3.76 },
			{ label: "Ep. 3", y: 3.77 },
			{ label: "Ep. 4", y: 3.65 },
			{ label: "Ep. 5", y: 3.90 },
			{ label: "Ep. 6", y: 3.88 },
			{ label: "Ep. 7", y: 3.69 },
			{ label: "Ep. 8", y: 3.86 },
			{ label: "Ep. 9", y: 3.38 },
			{ label: "Ep. 10", y: 4.20 }
		]
	},
	{
		type: "spline",
		visible: false,
		showInLegend: true,
		yValueFormatString: "##.00mn",
		name: "Season 3",
		dataPoints: [
			{ label: "Ep. 1", y: 4.37 },
			{ label: "Ep. 2", y: 4.27 },
			{ label: "Ep. 3", y: 4.72 },
			{ label: "Ep. 4", y: 4.87 },
			{ label: "Ep. 5", y: 5.35 },
			{ label: "Ep. 6", y: 5.50 },
			{ label: "Ep. 7", y: 4.84 },
			{ label: "Ep. 8", y: 4.13 },
			{ label: "Ep. 9", y: 5.22 },
			{ label: "Ep. 10", y: 5.39 }
		]
	},
	{
		type: "spline",
      	visible: false,
		showInLegend: true,
		yValueFormatString: "##.00mn",
		name: "Season 4",
		dataPoints: [
			{ label: "Ep. 1", y: 6.64 },
			{ label: "Ep. 2", y: 6.31 },
			{ label: "Ep. 3", y: 6.59 },
			{ label: "Ep. 4", y: 6.95 },
			{ label: "Ep. 5", y: 7.16 },
			{ label: "Ep. 6", y: 6.40 },
			{ label: "Ep. 7", y: 7.20 },
			{ label: "Ep. 8", y: 7.17 },
			{ label: "Ep. 9", y: 6.95 },
			{ label: "Ep. 10", y: 7.09 }
		]
	},
	{
		type: "spline", 
		showInLegend: true,
		yValueFormatString: "##.00mn",
		name: "Season 5",
		dataPoints: [
			{ label: "Ep. 1", y: 8 },
			{ label: "Ep. 2", y: 6.81 },
			{ label: "Ep. 3", y: 6.71 },
			{ label: "Ep. 4", y: 6.82 },
			{ label: "Ep. 5", y: 6.56 },
			{ label: "Ep. 6", y: 6.24 },
			{ label: "Ep. 7", y: 5.40 },
			{ label: "Ep. 8", y: 7.01 },
			{ label: "Ep. 9", y: 7.14 },
			{ label: "Ep. 10", y: 8.11 }
		]
	},
	{
		type: "spline", 
		showInLegend: true,
		yValueFormatString: "##.00mn",
		name: "Season 6",
		dataPoints: [
			{ label: "Ep. 1", y: 7.94 },
			{ label: "Ep. 2", y: 7.29 },
			{ label: "Ep. 3", y: 7.28 },
			{ label: "Ep. 4", y: 7.82 },
			{ label: "Ep. 5", y: 7.89 },
			{ label: "Ep. 6", y: 6.71 },
			{ label: "Ep. 7", y: 7.80 },
			{ label: "Ep. 8", y: 7.60 },
			{ label: "Ep. 9", y: 7.66 },
			{ label: "Ep. 10", y: 8.89 }
		]
	},
	{
		type: "spline", 
		showInLegend: true,
		yValueFormatString: "##.00mn",
		name: "Season 7",
		dataPoints: [
			{ label: "Ep. 1", y: 10.11 },
			{ label: "Ep. 2", y: 9.27 },
			{ label: "Ep. 3", y: 9.25 },
			{ label: "Ep. 4", y: 10.17 },
			{ label: "Ep. 5", y: 10.72 },
			{ label: "Ep. 6", y: 10.24 },
			{ label: "Ep. 7", y: 12.07 }
		]
	},
          {
		type: "spline", 
		showInLegend: true,
		yValueFormatString: "##.00mn",
		name: "Season 8",
		dataPoints: [
			{ label: "Ep. 1", y: 11.76 },
			{ label: "Ep. 2", y: 10.29 },
			{ label: "Ep. 3", y: 12.02 },
			{ label: "Ep. 4", y: 11.80 },
			{ label: "Ep. 5", y: 12.48 },
			{ label: "Ep. 6", y: 13.61 }
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

var chart = new CanvasJS.Chart("ChartsGraphs_chartContainer", {
	animationEnabled: true,
	title:{
		text: "Charts & Graphs"
	},
	axisX: {
		interval: 1,
		intervalType: "year",
		valueFormatString: "YYYY"
	},
	axisY: {
		suffix: "%"
	},
	toolTip: {
		shared: true
	},
	legend: {
		reversed: true,
		verticalAlign: "center",
		horizontalAlign: "right"
	},
	data: [{
		type: "stackedColumn100",
		name: "Real-Time",
		showInLegend: true,
		xValueFormatString: "YYYY",
		yValueFormatString: "#,##0\"%\"",
		dataPoints: [
			{ x: new Date(2010,0), y: 40 },
			{ x: new Date(2011,0), y: 50 },
			{ x: new Date(2012,0), y: 60 },
			{ x: new Date(2013,0), y: 61 },
			{ x: new Date(2014,0), y: 63 },
			{ x: new Date(2015,0), y: 65 },
			{ x: new Date(2016,0), y: 67 }
		]
	}, 
	{
		type: "stackedColumn100",
		name: "Web Browsing",
		showInLegend: true,
		xValueFormatString: "YYYY",
		yValueFormatString: "#,##0\"%\"",
		dataPoints: [
			{ x: new Date(2010,0), y: 28 },
			{ x: new Date(2011,0), y: 18 },
			{ x: new Date(2012,0), y: 12 },
			{ x: new Date(2013,0), y: 10 },
			{ x: new Date(2014,0), y: 10 },
			{ x: new Date(2015,0), y: 7 },
			{ x: new Date(2016,0), y: 5 }
		]
	}, 
	{
		type: "stackedColumn100",
		name: "File Sharing",
		showInLegend: true,
		xValueFormatString: "YYYY",
		yValueFormatString: "#,##0\"%\"",
		dataPoints: [
			{ x: new Date(2010,0), y: 15 },
			{ x: new Date(2011,0), y: 12 },
			{ x: new Date(2012,0), y: 10 },
			{ x: new Date(2013,0), y: 9 },
			{ x: new Date(2014,0), y: 7 },
			{ x: new Date(2015,0), y: 5 },
			{ x: new Date(2016,0), y: 1 }
		]
	},
	{
		type: "stackedColumn100",
		name: "Others",
		showInLegend: true,
		xValueFormatString: "YYYY",
		yValueFormatString: "#,##0\"%\"",
		dataPoints: [
			{ x: new Date(2010,0), y: 17 },
			{ x: new Date(2011,0), y: 20 },
			{ x: new Date(2012,0), y: 18 },
			{ x: new Date(2013,0), y: 20 },
			{ x: new Date(2014,0), y: 20 },
			{ x: new Date(2015,0), y: 23 },
			{ x: new Date(2016,0), y: 27 }
		]
	}]
});
chart.render();










var chart = new CanvasJS.Chart("IndexDataLabelchartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Index / Data Label"
	},
  	axisY: {
      includeZero: true
    },
	data: [{
		type: "column", //change type to bar, line, area, pie, etc
		//indexLabel: "{y}", //Shows y value on all Data Points
		indexLabelFontColor: "#5A5757",
      	indexLabelFontSize: 16,
		indexLabelPlacement: "outside",
		dataPoints: [
			{ x: 10, y: 71 },
			{ x: 20, y: 55 },
			{ x: 30, y: 50 },
			{ x: 40, y: 65 },
			{ x: 50, y: 92, indexLabel: "\u2605 Highest" },
			{ x: 60, y: 68 },
			{ x: 70, y: 38 },
			{ x: 80, y: 71 },
			{ x: 90, y: 54 },
			{ x: 100, y: 60 },
			{ x: 110, y: 36 },
			{ x: 120, y: 49 },
			{ x: 130, y: 21, indexLabel: "\u2691 Lowest" }
		]
	}]
});
chart.render();










var chart = new CanvasJS.Chart("LineAreaColumnchartContainer", {
	animationEnabled: true,
	theme: "light2",
	title: {
		text: "Line, Area and Column"
	},
	axisX: {
		valueFormatString: "MMM"
	},
	axisY: {
		prefix: "$",
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
			{ x: new Date(2016, 0), y: 20000 },
			{ x: new Date(2016, 1), y: 30000 },
			{ x: new Date(2016, 2), y: 25000 },
			{ x: new Date(2016, 3), y: 70000, indexLabel: "High Renewals" },
			{ x: new Date(2016, 4), y: 50000 },
			{ x: new Date(2016, 5), y: 35000 },
			{ x: new Date(2016, 6), y: 30000 },
			{ x: new Date(2016, 7), y: 43000 },
			{ x: new Date(2016, 8), y: 35000 },
			{ x: new Date(2016, 9), y:  30000},
			{ x: new Date(2016, 10), y: 40000 },
			{ x: new Date(2016, 11), y: 50000 }
		]
	}, 
	{
		type: "line",
		name: "Expected Sales",
		showInLegend: true,
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: new Date(2016, 0), y: 40000 },
			{ x: new Date(2016, 1), y: 42000 },
			{ x: new Date(2016, 2), y: 45000 },
			{ x: new Date(2016, 3), y: 45000 },
			{ x: new Date(2016, 4), y: 47000 },
			{ x: new Date(2016, 5), y: 43000 },
			{ x: new Date(2016, 6), y: 42000 },
			{ x: new Date(2016, 7), y: 43000 },
			{ x: new Date(2016, 8), y: 41000 },
			{ x: new Date(2016, 9), y: 45000 },
			{ x: new Date(2016, 10), y: 42000 },
			{ x: new Date(2016, 11), y: 50000 }
		]
	},
	{
		type: "area",
		name: "Profit",
		markerBorderColor: "white",
		markerBorderThickness: 2,
		showInLegend: true,
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: new Date(2016, 0), y: 5000 },
			{ x: new Date(2016, 1), y: 7000 },
			{ x: new Date(2016, 2), y: 6000},
			{ x: new Date(2016, 3), y: 30000 },
			{ x: new Date(2016, 4), y: 20000 },
			{ x: new Date(2016, 5), y: 15000 },
			{ x: new Date(2016, 6), y: 13000 },
			{ x: new Date(2016, 7), y: 20000 },
			{ x: new Date(2016, 8), y: 15000 },
			{ x: new Date(2016, 9), y:  10000},
			{ x: new Date(2016, 10), y: 19000 },
			{ x: new Date(2016, 11), y: 22000 }
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




var chart = new CanvasJS.Chart("RangeBarchartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	title: {
		text: "Range Bar"
	},
	axisX: {
		title: "Departments"
	},
	axisY: {
		title: "Salary in USD",
		interval: 10,
		suffix: "k",
		prefix: "$"
	}, 
	data: [{
		type: "rangeBar",
		showInLegend: true,
		yValueFormatString: "$#0.#K",
		indexLabel: "{y[#index]}",
		legendText: "Department wise Min and Max Salary",
		toolTipContent: "<b>{label}</b>: {y[0]} to {y[1]}",
		dataPoints: [
			{ x: 10, y:[80, 115], label: "Data Scientist" },
			{ x: 20, y:[95, 141], label: "Product Manager" },
			{ x: 30, y:[98, 115], label: "Web Developer" },
			{ x: 40, y:[90, 160], label: "Software Engineer" },
			{ x: 50, y:[100,152], label: "Quality Assurance" }
		]
	}]
});
chart.render();



var chart = new CanvasJS.Chart("stackedBarchartContainer", {
	animationEnabled: true,
	title:{
		text: "Stacked Bar Similar as Range Bar"
	},
	axisX: {
		valueFormatString: "DDD"
	},
	axisY: {
		prefix: "$"
	},
	toolTip: {
		shared: true
	},
	legend:{
		cursor: "pointer",
		itemclick: toggleDataSeries
	},
	data: [{
		type: "stackedBar",
		name: "Meals",
		showInLegend: "true",
		xValueFormatString: "DD, MMM",
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: new Date(2017, 0, 30), y: 56 },
			{ x: new Date(2017, 0, 31), y: 45 },
			{ x: new Date(2017, 1, 1), y: 71 },
			{ x: new Date(2017, 1, 2), y: 41 },
			{ x: new Date(2017, 1, 3), y: 60 },
			{ x: new Date(2017, 1, 4), y: 75 },
			{ x: new Date(2017, 1, 5), y: 98 }
		]
	},
	{
		type: "stackedBar",
		name: "Snacks",
		showInLegend: "true",
		xValueFormatString: "DD, MMM",
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: new Date(2017, 0, 30), y: 86 },
			{ x: new Date(2017, 0, 31), y: 95 },
			{ x: new Date(2017, 1, 1), y: 71 },
			{ x: new Date(2017, 1, 2), y: 58 },
			{ x: new Date(2017, 1, 3), y: 60 },
			{ x: new Date(2017, 1, 4), y: 65 },
			{ x: new Date(2017, 1, 5), y: 89 }
		]
	},
	{
		type: "stackedBar",
		name: "Drinks",
		showInLegend: "true",
		xValueFormatString: "DD, MMM",
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: new Date(2017, 0, 30), y: 48 },
			{ x: new Date(2017, 0, 31), y: 45 },
			{ x: new Date(2017, 1, 1), y: 41 },
			{ x: new Date(2017, 1, 2), y: 55 },
			{ x: new Date(2017, 1, 3), y: 80 },
			{ x: new Date(2017, 1, 4), y: 85 },
			{ x: new Date(2017, 1, 5), y: 83 }
		]
	},
	{
		type: "stackedBar",
		name: "Dessert",
		showInLegend: "true",
		xValueFormatString: "DD, MMM",
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: new Date(2017, 0, 30), y: 61 },
			{ x: new Date(2017, 0, 31), y: 55 },
			{ x: new Date(2017, 1, 1), y: 61 },
			{ x: new Date(2017, 1, 2), y: 75 },
			{ x: new Date(2017, 1, 3), y: 80 },
			{ x: new Date(2017, 1, 4), y: 85 },
			{ x: new Date(2017, 1, 5), y: 105 }
		]
	},
	{
		type: "stackedBar",
		name: "Takeaway",
		showInLegend: "true",
		xValueFormatString: "DD, MMM",
		yValueFormatString: "$#,##0",
		dataPoints: [
			{ x: new Date(2017, 0, 30), y: 52 },
			{ x: new Date(2017, 0, 31), y: 55 },
			{ x: new Date(2017, 1, 1), y: 20 },
			{ x: new Date(2017, 1, 2), y: 35 },
			{ x: new Date(2017, 1, 3), y: 30 },
			{ x: new Date(2017, 1, 4), y: 45 },
			{ x: new Date(2017, 1, 5), y: 25 }
		]
	}]
});
chart.render();

function toggleDataSeries(e) {
	if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else {
		e.dataSeries.visible = true;
	}
	chart.render();
}




}
</script>


<?php
$this->load->view('layouts/scripts');
// $this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
