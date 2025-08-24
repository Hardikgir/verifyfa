<?php
/*
$OriginalPoints = array(
	array("label"=> "PREMIUM - 01", "y"=> 1),
	array("label"=> "Basic - 01", "y"=> 2),
	array("label"=> "DLJM Customized", "y"=> 3),
	array("label"=> "Premium -02", "y"=> 4),
	array("label"=> "Upgraded Premium -03", "y"=> 5),
	array("label"=> "Premium - 03", "y"=> 6),
);

$RenewalsPoints = array(
	array("label"=> "PREMIUM - 01", "y"=> 6),
	array("label"=> "Basic - 01", "y"=> 5),
	array("label"=> "DLJM Customized", "y"=> 4),
	array("label"=> "Premium -02", "y"=> 3),
	array("label"=> "Upgraded Premium -03", "y"=> 2),
	array("label"=> "Premium - 03", "y"=> 1),
);

$ResubscriptionsPoints = array(
	array("label"=> "PREMIUM - 01", "y"=> 8),
	array("label"=> "Basic - 01", "y"=> 6),
	array("label"=> "DLJM Customized", "y"=> 5),
	array("label"=> "Premium -02", "y"=> 7),
	array("label"=> "Upgraded Premium -03", "y"=> 3),
	array("label"=> "Premium - 03", "y"=> 4),
); */

?>
<!DOCTYPE HTML>
<html>
<head>  
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Type of Subscription Plans / Active Subscriptions"
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

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>                              