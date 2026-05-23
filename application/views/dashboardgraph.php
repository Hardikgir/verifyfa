
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
            <div id="IndexDataLabelchartContainer2" style="height: 370px; width: 100%;"></div>
        </div> 

        <div class="col-md-12 mt-5">
            <div id="LineAreaColumnchartContainer" style="height: 370px; width: 100%;"></div>
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
		dataPoints: [
			{ label: "Day 1", y: 8 },
			{ label: "Day 2", y: 6.81 },
			{ label: "Day 3", y: 6.71 },
			{ label: "Day 4", y: 6.82 },
			{ label: "Day 5", y: 6.56 },
			{ label: "Day 6", y: 6.24 },
			{ label: "Day 7", y: 5.40 },
			{ label: "Day 8", y: 7.01 },
			{ label: "Day 9", y: 7.14 },
			{ label: "Day 10", y: 8.11 }
		]
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

var chart = new CanvasJS.Chart("ChartsGraphs_chartContainer", {
	animationEnabled: true,
	title:{
		text: "Charts & Graphs"
	},
	axisX: {
		interval: 1,
		valueFormatString: "Sub Type"
	},
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
			{ x: 4, y: 61 },
			
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
			{ x: 4, y: 10 },
			
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
			{ x: 4, y: 9 },
			
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
		indexLabelFontColor: "#000",
      	indexLabelFontSize: 16,
		indexLabelPlacement: "outside",
		dataPoints: [
			{ x: 1, y: 71,label: "1",color: "#4f81bc"},
			{ x: 2, y: 0,label: "2",color: "#4f81bc"},
			{ x: 3, y: 50,label: "3",color: "#4f81bc"},
			{ x: 4, y: 0,label: "4",color: "#4f81bc"},
			{ x: 5, y: 92,label: "5",color: "#4f81bc"},
			{ x: 6, y: 0,label: "6",color: "#4f81bc"},
			{ x: 7, y: 38,label: "7",color: "#4f81bc"},
			{ x: 8, y: 0,label: "8",color: "#4f81bc"},
			{ x: 9, y: 54,label: "9",color: "#4f81bc"},
			{ x: 10, y: 0,label: "10",color: "#4f81bc"},
			{ x: 11, y: 36,label: "11",color: "#4f81bc"},
			{ x: 12, y: 0,label: "12",color: "#4f81bc"},
			{ x: 13, y: 21,label: "13",color: "#4f81bc"},
			{ x: 14, y: 0,label: "14",color: "#4f81bc"},
			{ x: 15, y: 49,label: "15",color: "#4f81bc"},
		]
	}]
});
chart.render();




var chart = new CanvasJS.Chart("IndexDataLabelchartContainer2", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Index / Data Label"
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

var chart = new CanvasJS.Chart("stackedBarchartContainer", {
	animationEnabled: true,
	title:{
		text: "Stacked Bar Similar as Range Bar"
	},
	axisX: {
		valueFormatString: "DDD"
	},
	axisY: {
		prefix: ""
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
		yValueFormatString: "#,##0",
		dataPoints: [
			{ x: new Date(2017, 0, 30), y: 5,color: "transparent",label: "Project-7"},
			{ x: new Date(2017, 0, 31), y: 45,color: "transparent",label: "Project-6"},
			{ x: new Date(2017, 1, 1), y: 71,color: "transparent",label: "Project-5"},
			{ x: new Date(2017, 1, 2), y: 12,color: "transparent",label: "Project-4"},
			{ x: new Date(2017, 1, 3), y: 60,color: "transparent",label: "Project-3"},
			{ x: new Date(2017, 1, 4), y: 23,color: "transparent",label: "Project-2"},
			{ x: new Date(2017, 1, 5), y: 98,color: "transparent",label: "Project-1"}
		]
	},
	{
		type: "stackedBar",
		name: "Snacks",
		showInLegend: "true",
		xValueFormatString: "DD, MMM",
		yValueFormatString: "#,##0",
		dataPoints: [
			{ x: new Date(2017, 0, 30), y: 20,color: "#4f81bc",label: "Project-7"},
			{ x: new Date(2017, 0, 31), y: 95,color: "#4f81bc",label: "Project-6"},
			{ x: new Date(2017, 1, 1), y: 71,color: "#4f81bc",label: "Project-5"},
			{ x: new Date(2017, 1, 2), y: 58,color: "#4f81bc",label: "Project-4"},
			{ x: new Date(2017, 1, 3), y: 60,color: "#4f81bc",label: "Project-3"},
			{ x: new Date(2017, 1, 4), y: 65,color: "#4f81bc",label: "Project-2"},
			{ x: new Date(2017, 1, 5), y: 89,color: "#4f81bc",label: "Project-1"}
		]
	},
	{
		type: "stackedBar",
		name: "Snacks",
		showInLegend: "true",
		xValueFormatString: "DD, MMM",
		yValueFormatString: "#,##0",
		dataPoints: [
			{ x: new Date(2017, 0, 30), y: 20,color: "#f29e65",label: "Project-7"},
			{ x: new Date(2017, 0, 31), y: 95,color: "#f29e65",label: "Project-6"},
			{ x: new Date(2017, 1, 1), y: 71,color: "#f29e65",label: "Project-5"},
			{ x: new Date(2017, 1, 2), y: 58,color: "#f29e65",label: "Project-4"},
			{ x: new Date(2017, 1, 3), y: 60,color: "#f29e65",label: "Project-3"},
			{ x: new Date(2017, 1, 4), y: 65,color: "#f29e65",label: "Project-2"},
			{ x: new Date(2017, 1, 5), y: 89,color: "#f29e65",label: "Project-1"}
		]
	}
	]
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
