<?php

$dataPoints1 = array(
	array("label"=> "Single", "y"=> 13),
	array("label"=> "Married", "y"=> 21),
	array("label"=> "Married and have Kids", "y"=> 24),
	array("label"=> "Single Parent", "y"=> 15)
);

$dataPoints2 = array(
	array("label"=> "Single", "y"=> 6),
	array("label"=> "Married", "y"=> 12),
	array("label"=> "Married and have Kids", "y"=> 13),
	array("label"=> "Single Parent", "y"=> 7)
);

$dataPoints3 = array(
	array("label"=> "Single", "y"=> 5),
	array("label"=> "Married", "y"=> 9),
	array("label"=> "Married and have Kids", "y"=> 10),
	array("label"=> "Single Parent", "y"=> 6)
);

$dataPoints4 = array(
	array("label"=> "Single", "y"=> 3),
	array("label"=> "Married", "y"=> 8),
	array("label"=> "Married and have Kids", "y"=> 9),
	array("label"=> "Single Parent", "y"=> 3)
);

$dataPoints5 = array(
	array("label"=> "Single", "y"=> 3),
	array("label"=> "Married", "y"=> 5),
	array("label"=> "Married and have Kids", "y"=> 4),
	array("label"=> "Single Parent", "y"=> 2)
);

$dataPoints6 = array(
	array("label"=> "Single", "y"=> 2),
	array("label"=> "Married", "y"=> 3),
	array("label"=> "Married and have Kids", "y"=> 4),
	array("label"=> "Single Parent", "y"=> 2)
);

$dataPoints7 = array(
	array("label"=> "Single", "y"=> 5),
	array("label"=> "Married", "y"=> 9),
	array("label"=> "Married and have Kids", "y"=> 9),
	array("label"=> "Single Parent", "y"=> 5)
);

?>
<!DOCTYPE HTML>
<html>
<head>  
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Spending of Money Based on Household Composition"
	},
	theme: "light2",
	animationEnabled: true,
	toolTip:{
		shared: true,
		reversed: true
	},
	axisY: {
		suffix: "%"
	},
	data: [
		{
			type: "stackedColumn100",
			name: "Housing",
			showInLegend: true,
			yValueFormatString: "$#,##0 K",
			dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn100",
			name: "Transportation",
			showInLegend: true,
			yValueFormatString: "$#,##0 K",
			dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn100",
			name: "Food",
			showInLegend: true,
			yValueFormatString: "$#,##0 K",
			dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn100",
			name: "Insurance and Pastion",
			showInLegend: true,
			yValueFormatString: "$#,##0 K",
			dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn100",
			name: "Healthcare",
			showInLegend: true,
			yValueFormatString: "$#,##0 K",
			dataPoints: <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn100",
			name: "Entertainment",
			showInLegend: true,
			yValueFormatString: "$#,##0 K",
			dataPoints: <?php echo json_encode($dataPoints6, JSON_NUMERIC_CHECK); ?>
		},{
			type: "stackedColumn100",
			name: "Other",
			showInLegend: true,
			yValueFormatString: "$#,##0 K",
			dataPoints: <?php echo json_encode($dataPoints7, JSON_NUMERIC_CHECK); ?>
		}
	]
});

chart.render();

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>                              