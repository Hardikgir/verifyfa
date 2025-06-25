
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
    <h5 class="card-title card-txt"><?php echo $Total_Active_Subscription_Plans; ?></h5>

  </div>
</div>

</div>


<div class="col-md-3">

<div class="card">
<div class="card-header">
	<h2>Total No. of Registrations</h2>
</div>
  <div class="card-body card-body-n">
    <h5 class="card-title card-txt"><?php echo $Total_Registrations; ?></h5>

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
  <h5 class="card-title card-txt"><?php echo $Total_Registered_Active_Subscriptions; ?></h5>
<!-- <p class="txt-cardp">+654*</p>
<p class="txt-cardp text-left pt-3">* added since April 2023</p> -->
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
			<td><?php echo $Total_Subscriptions_expiring_in['in_30_Day']; ?></td>
			<td><?php echo $Total_Subscriptions_expiring_in['in_31_to_60_Day']; ?></td>
			<td><?php echo $Total_Subscriptions_expiring_in['in_61_to_90_Day']; ?></td>
			<td><?php echo $Total_Subscriptions_expiring_in['in_91_to_120_Day']; ?></td>
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
  <h5 class="card-title card-txt"><?php echo $Total_Registrations_Unsubscribed; ?></h5>
<!-- <p class="txt-cardp">-65*</p> -->
<!-- <p class="txt-cardp text-left pt-3">* unsubscribed since April 2023</p> -->
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
    <h5 class="card-title card-txt"><?php echo $Total_Registered_Users_where_Subscription_Link_Expired; ?></h5>
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
			<div  style="background: #fff;height: 150px;padding: 15px;">
				<h2 class="text-center">Subscription Trend</h2>
				<form action>
					<div class="form-group row">
						<div class="col-sm-5">
							<label>Start Date</label>
							<input type="date" name="SubscriptionTrendStartDate" id="SubscriptionTrendStartDate"  class="form-control">
						</div>
						<div class="col-sm-5">
							<label>To Date</label>
							<input type="date" name="SubscriptionTrendEndDate" id="SubscriptionTrendEndDate"  class="form-control">
						</div>
						<div class="col-sm-2">
							<a href="javascript:void(0)" class="btn btn-primary" onclick="SubscriptionTrendForm(this)" data-formtype="SubscriptionTrend">Submit</a>
						</div>
					</div>

				</form>
			</div>
            <div id="SubscriptionTrendChart" style="height: 370px; width: 100%; margin: 0px auto;"></div>
        </div>

        <div class="col-md-12 mt-5">
			<div  style="background: #fff;height: 150px;padding: 15px;">
				<h2 class="text-center">Type of Subscription Plans / Active Subscriptions</h2>
				<form action>
					<div class="form-group row">
						<div class="col-sm-5">
							<input type="date" name="TypeSubscriptionActiveStartDate" id="TypeSubscriptionActiveStartDate" class="form-control">
						</div>
						<div class="col-sm-5">
							<input type="date" name="TypeSubscriptionActiveEndDate" id="TypeSubscriptionActiveEndDate" class="form-control">
						</div>
						<div class="col-sm-2">
							<a href="javascript:void(0)" class="btn btn-primary" onclick="TypeSubscriptionActiveForm(this)" data-formtype="TypeSubscriptionActive">Submit</a>
						</div>
					</div>
				</form>
			</div>
			<div id="TypeSubscriptionActiveChart" style="height: 370px; width: 100%;"></div>
        </div>

		<div class="col-md-12 mt-5">
			<div  style="background: #fff;height: 150px;padding: 15px;">
				<h2 class="text-center">Subscription Amount Due</h2>
				<form action>
					<div class="form-group row">
						<div class="col-sm-5">
							<input type="date" name="SubscriptionAmountStartDate" id="SubscriptionAmountStartDate" class="form-control">
						</div>
						<div class="col-sm-5">
							<input type="date" name="SubscriptionAmountEndDate" id="SubscriptionAmountEndDate" class="form-control">
						</div>
						<div class="col-sm-2">
							<a href="javascript:void(0)" class="btn btn-primary" onclick="SubscriptionAmountForm(this)" data-formtype="SubscriptionAmount">Submit</a>
						</div>
					</div>
				</form>
			</div>
            <div id="SubscriptionAmountDueChart" style="height: 370px; width: 100%;"></div>
        </div>

		
        <div class="col-md-12 mt-5">
			<div class="text-center" style="background: #fff;height: 70px;padding: 15px;">
				<h2>Subscription Amount Due – Ageing Analysis</h2>
			</div>
            <div id="SubscriptionAmountDueAgeingAnalysisChart" style="height: 370px; width: 100%;"></div>
        </div>

        





    </div>



    </div>
</div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

<?php 


?>


  <script>
window.onload = function () {

var chart = new CanvasJS.Chart("SubscriptionTrendChart", {
	theme:"light2",
	animationEnabled: true,
	title:{
		text: ""
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
		dataPoints: <?php echo json_encode($Original_user_result, JSON_NUMERIC_CHECK); ?>
	},
	{
		type: "spline",
		showInLegend: true,
		yValueFormatString: "##.00",
		name: "Renewals",
		dataPoints: <?php echo json_encode($Renewals_user_result, JSON_NUMERIC_CHECK); ?>
	},
	{
		type: "spline",
		showInLegend: true,
		yValueFormatString: "##.00",
		name: "Resubscriptions",
		dataPoints: <?php echo json_encode($Resubscriptions_user_result, JSON_NUMERIC_CHECK); ?>
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






var chart = new CanvasJS.Chart("TypeSubscriptionActiveChart", {
	title: {
		text: ""
	},
	theme: "light2",
	animationEnabled: true,
	toolTip:{
		shared: true,
		reversed: true
	},
	axisY: {
		title: "Cumulative Capacity",
        suffix: ""
	},
	legend: {
		cursor: "pointer",
		itemclick: toggleDataSeries
	},
	data: [
		{
			type: "stackedColumn",
			name: "Original",
			showInLegend: true,
			yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($OriginalPoints, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn",
			name: "Renewals",
			showInLegend: true,
			yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($RenewalsPoints, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn",
			name: "Resubscriptions",
			showInLegend: true,
			yValueFormatString: "#,##0",
			dataPoints: <?php echo json_encode($ResubscriptionsPoints, JSON_NUMERIC_CHECK); ?>
		}
	]
});

chart.render();

function toggleDataSeries(e) {
	if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	} else {
		e.dataSeries.visible = true;
	}
	e.chart.render();
}






console.log(<?php echo json_encode($my_array, JSON_NUMERIC_CHECK); ?>)


var chart = new CanvasJS.Chart("SubscriptionAmountDueChart", {
	animationEnabled: true,
	exportEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: ""
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
		dataPoints: <?php echo json_encode($my_array, JSON_NUMERIC_CHECK); ?>
		/*
		dataPoints: [
			{ x: 1, y: 71,label: "10000 (min) - 10000",color: "#4f81bc" },
			{ x: 2, y: 10,label: "10001 - 20000",color: "#4f81bc" },
			{ x: 3, y: 50,label: "20001 - 30000",color: "#4f81bc" },
			{ x: 4, y: 25,label: "30001 - 40000",color: "#4f81bc" },
			{ x: 5, y: 92,label: "40001 - 50000",color: "#4f81bc"},
			{ x: 6, y: 35,label: "50001 - 60000 (max)",color: "#4f81bc" },
		] */ 
	}]
});
chart.render();






var chart = new CanvasJS.Chart("SubscriptionAmountDueAgeingAnalysisChart", {
	animationEnabled: true,
	theme: "light2",
	title: {
		text: ""
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








function toggleDataSeries(e) {
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible ){
		e.dataSeries.visible = false;
	} else {
		e.dataSeries.visible = true;
	}
	chart.render();
}



function SubscriptionTrendForm(event){
	var FormType = $(event).attr("data-formtype");
	console.log("FormType")
	console.log(FormType)

	var location = 1;
	var SubscriptionTrendStartDate = $("#SubscriptionTrendStartDate").val();
	var SubscriptionTrendEndDate = $("#SubscriptionTrendEndDate").val();


	 $.ajax({
        url: "<?php echo base_url('index.php/Superadmin_controller/super_admin_dashboard_SubscriptionTrend_result'); ?>", //backend url
        type: 'post',
        dataType: 'json',
        data: {
			"SubscriptionTrendStartDate" : SubscriptionTrendStartDate,
          	"SubscriptionTrendEndDate" : SubscriptionTrendEndDate,
        },
        error: function (request, error) {
            console.log(arguments);
            alert(" Can't do because: " + error);
        },
        success: function(response) {
			console.log("response")
			console.log(response)
		var chart = new CanvasJS.Chart("SubscriptionTrendChart", {
			theme:"light2",
			animationEnabled: true,
			title:{
				text: ""
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
				dataPoints: response.Original_user_result
			},
			{
				type: "spline",
				showInLegend: true,
				yValueFormatString: "##.00",
				name: "Renewals",
				dataPoints: response.Renewals_user_result
			},
			{
				type: "spline",
				showInLegend: true,
				yValueFormatString: "##.00",
				name: "Resubscriptions",
				dataPoints: response.Resubscriptions_user_result
			}]
		});
		chart.render();
        }
    });




}

function TypeSubscriptionActiveForm(event){
	var FormType = $(event).attr("data-formtype");
	console.log("FormType")
	console.log(FormType)

	
	var TypeSubscriptionActiveStartDate = $("#TypeSubscriptionActiveStartDate").val();
	var TypeSubscriptionActiveEndDate = $("#TypeSubscriptionActiveEndDate").val();
	
	$.ajax({
        url: "<?php echo base_url('index.php/Superadmin_controller/super_admin_dashboard_TypeSubscriptionActive_result'); ?>", //backend url
        type: 'post',
        dataType: 'json',
        data: {
			"TypeSubscriptionActiveStartDate" : TypeSubscriptionActiveStartDate,
          	"TypeSubscriptionActiveEndDate" : TypeSubscriptionActiveEndDate,
        },
        error: function (request, error) {
            console.log(arguments);
            alert(" Can't do because: " + error);
        },
        success: function(response) {

			console.log("response")
			console.log(response)

			var chart = new CanvasJS.Chart("TypeSubscriptionActiveChart", {
				title: {
					text: ""
				},
				theme: "light2",
				animationEnabled: true,
				toolTip:{
					shared: true,
					reversed: true
				},
				axisY: {
					title: "Cumulative Capacity",
					suffix: ""
				},
				legend: {
					cursor: "pointer",
					itemclick: toggleDataSeries
				},
				data: [
					{
						type: "stackedColumn",
						name: "Original",
						showInLegend: true,
						yValueFormatString: "#,##0",
						dataPoints: response.OriginalPoints
					},{
						type: "stackedColumn",
						name: "Renewals",
						showInLegend: true,
						yValueFormatString: "#,##0",
						dataPoints: response.RenewalsPoints
					},{
						type: "stackedColumn",
						name: "Resubscriptions",
						showInLegend: true,
						yValueFormatString: "#,##0",
						dataPoints: response.ResubscriptionsPoints	
					}
				]
			});

			chart.render();

		}

	});

}

function SubscriptionAmountForm(event){
	var FormType = $(event).attr("data-formtype");
	console.log("FormType")
	console.log(FormType)




	var SubscriptionAmountStartDate = $("#SubscriptionAmountStartDate").val();
	var SubscriptionAmountEndDate = $("#SubscriptionAmountEndDate").val();
	
	$.ajax({
        url: "<?php echo base_url('index.php/Superadmin_controller/super_admin_dashboard_SubscriptionAmount_result'); ?>", //backend url
        type: 'post',
        dataType: 'json',
        data: {
			"SubscriptionAmountStartDate" : SubscriptionAmountStartDate,
          	"SubscriptionAmountEndDate" : SubscriptionAmountEndDate,
        },
        error: function (request, error) {
            console.log(arguments);
            alert(" Can't do because: " + error);
        },
        success: function(response) {

			console.log("response.my_array")
			console.log(response.my_array)

			var chart = new CanvasJS.Chart("SubscriptionAmountDueChart", {
			animationEnabled: true,
			exportEnabled: true,
			theme: "light1", // "light1", "light2", "dark1", "dark2"
			title:{
				text: ""
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
				indexLabelFontColor: "#000",
				indexLabelFontSize: 16,
				indexLabelPlacement: "outside",
				dataPoints: response.my_array
			}]
		});
		chart.render();
			

		}

	});







}


</script>


<?php
    $this->load->view('layouts/scripts');
    // $this->load->view('layouts/dashboard_script');
    $this->load->view('layouts/footer');
?>
