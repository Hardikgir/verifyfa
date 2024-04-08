<?php 
$listing=getTagUntag($projects[0]->project_name);
$cat=getTagUntagCategories($projects[0]->project_name);
?>
<script>
$(document).ready(function(){
	$('.inDetail').hide();
	$('.showDetail').click(function(){
		if($(this).attr('id')=='showDetail')
		{
			
			$('.inSummary').hide();
			$('.inDetail').show();
			$(this).attr('id','hideDetail');
			$(this).html('Hide Detail');
		}
		else if($(this).attr('id')=='hideDetail')
		{
			$('.inDetail').hide();
			$('.inSummary').show();
			$(this).attr('id','showDetail');
			$(this).html('Show Detail');
		}
	});
});
	// donut 3
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];
var total=<?php echo $projects[0]->VerifiedQuantity != NULL ? round(($projects[0]->VerifiedQuantity/$projects[0]->TotalQuantity)*100,2):0;?>;
var actual=<?php echo $projects[0]->VerifiedQuantity != NULL ? 100-round(($projects[0]->VerifiedQuantity/$projects[0]->TotalQuantity)*100,2):100;?>;
var unverifiedTotal=<?php echo $projects[0]->TotalQuantity-$projects[0]->VerifiedQuantity; ?>;

var chDonut3 = document.getElementById("chDonut3");
if (chDonut3) {
  new Chart(chDonut3, {
      type: 'pie',
      data: {labels: [total+'% \n <?php echo $projects[0]->VerifiedQuantity;?> of <?php echo $projects[0]->TotalQuantity;?> Li verified',actual+'%\n '+unverifiedTotal+' of <?php echo $projects[0]->TotalQuantity;?> Li Unverified'],datasets: [{backgroundColor: colors.slice(0,1),borderWidth: 0,data: [total,actual]}]},
      options: {cutoutPercentage: 80,legend: {position:'top', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}},
	  
  });
}

	//doughnut
	<?php
	if($projects[0]->project_type=='TG')
	{
				?>
		var donutdataset=[{
			data: [<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?>,<?php echo 100-round(($listing['yverified']/$listing['ytotal'])*100,2);?>],
				backgroundColor: ["#46BFBD","#e5e5e5"],
				hoverBackgroundColor: ["#616774","#e5e5e5"]
		}];
		var donutlabel=["Tagged (<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ytotal']; ?> Li","Unverified (<?php echo 100-round(($listing['yverified']/$listing['ytotal'])*100,2);?> %) : <?php echo $listing['ytotal']-$listing['yverified'].' of '.$listing['ytotal'].' Li'; ?>"];
		<?php 
		
	}
	if($projects[0]->project_type=='NT')
	{
	?>
	var donutdataset=[{
		data: [ <?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?>,<?php echo 100-round(($listing['nverified']/$listing['ntotal'])*100,2);?>],
		      backgroundColor: ["#FDB45C","#e5e5e5"],
		      hoverBackgroundColor: ["#007bff","#e5e5e5"]
	}];
	var donutlabel=["Untagged (<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ntotal']; ?> Li","Unverified (<?php echo 100-round(($listing['nverified']/$listing['ntotal'])*100,2);?> %) : <?php echo $listing['ntotal']-$listing['nverified'].' of '.$listing['ntotal'].' Li'; ?>"];
	<?php
	}
	if($projects[0]->project_type=='UN')
	{
	?>
		var donutdataset=[{
			data: [<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?>,<?php echo 100-round(($listing['naverified']/$listing['natotal'])*100,2);?>],
				backgroundColor: ["#F7464A","#e5e5e5"],
				hoverBackgroundColor: ["#FFC870","#e5e5e5"]
		}];
		var donutlabel=["Unspecified (<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?> %): <?php echo $listing['naverified']; ?> of <?php echo $listing['natotal']; ?> Li","Unverified (<?php echo 100-round(($listing['naverified']/$listing['natotal'])*100,2);?> %) : <?php echo $listing['ntotal']-$listing['naverified'].' of '.$listing['natotal'].' Li'; ?>"];
	<?php
	}
	if($projects[0]->project_type=='CD')
	{

	
	if($listing['ytotal']!=0 && $listing['ntotal']!=0 && $listing['natotal']!=0)
	{
	?>
	var donutdataset=[{
		data: [<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?>, <?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?>,<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?>,<?php echo 100-(round((($listing['yverified']+$listing['nverified']+$listing['naverified'])/($listing['ytotal']+$listing['ntotal']+$listing['natotal']))*100,2));?>],
		      backgroundColor: ["#46BFBD", "#FDB45C","#F7464A","#e5e5e5"],
		      hoverBackgroundColor: ["#616774", "#007bff", "#FFC870","#e5e5e5"]
	}];
	var donutlabel=["Tagged (<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ytotal']; ?> Li", "Untagged (<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ntotal']; ?> Li", "Unspecified (<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?> %): <?php echo $listing['naverified']; ?> of <?php echo $listing['natotal']; ?> Li","Unverified (<?php echo 100-(round((($listing['yverified']+$listing['nverified']+$listing['naverified'])/($listing['ytotal']+$listing['ntotal']+$listing['natotal']))*100,2));?> %) : <?php echo ($listing['ytotal']+$listing['ntotal']+$listing['natotal'])-($listing['yverified']+$listing['nverified']+$listing['naverified']).' of '.($listing['ytotal']+$listing['ntotal']+$listing['natotal']).' Li'; ?>"];
	<?php 
	}
	else if($listing['ytotal']!=0 && $listing['ntotal']!=0 && $listing['natotal']==0)
	{
	?>
	var donutdataset=[{
		data: [<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?>, <?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?>,<?php echo 100-(round((($listing['yverified']+$listing['nverified'])/($listing['ytotal']+$listing['ntotal']))*100,2));?>],
		      backgroundColor: ["#46BFBD", "#FDB45C","#e5e5e5"],
		      hoverBackgroundColor: ["#616774", "#007bff","#e5e5e5"]
	}];
	var donutlabel=["Tagged (<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ytotal']; ?> Li", "Untagged (<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?> %): <?php echo $listing['nverified']; ?> of <?php echo $listing['ntotal']; ?> Li","Unverified (<?php echo 100-(round((($listing['yverified']+$listing['nverified'])/($listing['ytotal']+$listing['ntotal']))*100,2));?> %) : <?php echo ($listing['ytotal']+$listing['ntotal'])-($listing['yverified']+$listing['nverified']).' of '.($listing['ytotal']+$listing['ntotal']).' Li';?>"];
	<?php 
	}
	else if($listing['ytotal']!=0 && $listing['ntotal']==0 && $listing['natotal']==0)
	{
	?>
	var donutdataset=[{
		data: [<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?>,<?php echo 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));?>],
		      backgroundColor: ["#46BFBD","#e5e5e5"],
		      hoverBackgroundColor: ["#616774","#e5e5e5"]
	}];
	var donutlabel=["Tagged (<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ytotal']; ?> Li","Unverified (<?php echo 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));?> %) : <?php echo ($listing['ytotal']-$listing['yverified']).' of '.($listing['ytotal']).' Li';?>"];
	<?php 
	}
	else if($listing['ytotal']!=0 && $listing['ntotal']==0 && $listing['natotal']!=0)
	{
	?>
	var donutdataset=[{
		data: [<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?>,<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?>,<?php echo 100-(round((($listing['yverified']+$listing['naverified'])/($listing['ytotal']+$listing['natotal']))*100,2));?>],
		      backgroundColor: ["#46BFBD","#F7464A","#e5e5e5"],
		      hoverBackgroundColor: ["#616774", "#FFC870","#e5e5e5"]
	}];
	var donutlabel=["Tagged (<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ytotal']; ?> Li", "Unspecified (<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?> %): <?php echo $listing['naverified']; ?> of <?php echo $listing['natotal']; ?> Li","Unverified (<?php echo 100-(round((($listing['yverified']+$listing['naverified'])/($listing['ytotal']+$listing['natotal']))*100,2));?> %) : <?php echo ($listing['ytotal']+$listing['natotal'])-($listing['yverified']+$listing['naverified']).' of '.($listing['ytotal']+$listing['natotal']).' Li';?>"];
	<?php 
	}
	else if($listing['ytotal']==0 && $listing['ntotal']==0 && $listing['natotal']!=0)
	{
	?>
	var donutdataset=[{
		data: [<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?>,<?php echo 100-(round((($listing['naverified'])/($listing['natotal']))*100,2));?>],
		      backgroundColor: ["#F7464A","#e5e5e5"],
		      hoverBackgroundColor: ["#FFC870","#e5e5e5"]
	}];
	var donutlabel=["Unspecified (<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?> %): <?php echo $listing['naverified']; ?> of <?php echo $listing['natotal']; ?> Li","Unverified (<?php echo 100-(round((($listing['naverified'])/($listing['natotal']))*100,2));?> %) : <?php echo ($listing['natotal'])-($listing['naverified']).' of '.($listing['natotal']).' Li';?>"];
	<?php 
	}
	else if($listing['ytotal']==0 && $listing['ntotal']!=0 && $listing['natotal']!=0)
	{
	?>
	var donutdataset=[{
		data: [<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?>,<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?>,<?php echo 100-(round((($listing['nverified']+$listing['naverified'])/($listing['ntotal']+$listing['natotal']))*100,2));?>],
		      backgroundColor: ["#46BFBD","#e5e5e5"],
		      hoverBackgroundColor: ["#616774","#e5e5e5"]
	}];
	var donutlabel=["Untagged (<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?> %): <?php echo $listing['nverified']; ?> of <?php echo $listing['ntotal']; ?> Li","Unspecified (<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?> %): <?php echo $listing['naverified']; ?> of <?php echo $listing['natotal']; ?> Li","Unverified (<?php echo 100-(round((($listing['nverified']+$listing['naverified'])/($listing['ntotal']+$listing['natotal']))*100,2));?> %) : <?php echo ($listing['ntotal']+$listing['natotal'])-($listing['nverified']+$listing['naverified']).' of '.($listing['ntotal']+$listing['natotal']).' Li';?>"];
	<?php 
	}
	else if($listing['ytotal']==0 && $listing['ntotal']!=0 && $listing['natotal']==0)
	{
	?>
	var donutdataset=[{
		data: [<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?>,<?php echo 100-(round((($listing['nverified'])/($listing['ntotal']))*100,2));?>],
		      backgroundColor: ["#46BFBD","#F7464A","#e5e5e5"],
		      hoverBackgroundColor: ["#616774",  "#FFC870","#e5e5e5"]
	}];
	var donutlabel=["UnTagged (<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?> %): <?php echo $listing['nverified']; ?> of <?php echo $listing['ntotal']; ?> Li","Unverified (<?php echo 100-(round((($listing['nverified'])/($listing['ntotal']))*100,2));?> %) : <?php echo ($listing['ntotal'])-($listing['nverified']).' of '.($listing['ntotal']).' Li';?>"];
	<?php 
	}
	}
	?>
		var ctxD = document.getElementById("doughnutChart").getContext('2d');
		var myLineChart = new Chart(ctxD, {
		  type: 'doughnut',
		  data: {
		    labels: donutlabel,
		    datasets: donutdataset,
		  },
		  options: {
		    responsive: true
		  },
		 
		});
		
		//pie
 
<?php
$tcatlabels=array();
$tcatdata=array();
$atcatlabels=array();
$atcatdata=array();
$cnt1=0;
$tcattotalpercentage=0;
$atcattotalpercentage=0;
$taggedOverall=[];
$totaltaggedOverall=[];
$amounttaggedOverall=[];
$amounttotaltaggedOverall=[];
if(count($cat['tagged'])>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
	foreach($cat['tagged'] as $tcat)
	{
		$taggedOverall[$tcat['category']]=$tcat['verified'];
		$totaltaggedOverall[$tcat['category']]=$tcat['total'];
		$amounttaggedOverall[$tcat['category']]=$tcat['verifiedamount'];
		$amounttotaltaggedOverall[$tcat['category']]=$tcat['totalamount'];
	$tcattotalpercentage=$tcattotalpercentage+$tcat['percentage'];
	$atcattotalpercentage=$atcattotalpercentage+$tcat['amountpercentage'];
	array_push($tcatlabels,$tcat['category'].' ('.round(($tcat['percentage']/count($cat['tagged'])),2).' %)');
	array_push($tcatdata,round(($tcat['percentage']/count($cat['tagged'])),2));
	array_push($atcatlabels,$tcat['category'].' ('.$tcat['amountpercentage'].' %)');
	array_push($atcatdata,$tcat['amountpercentage']);
	// if($cnt1 == count($cat['tagged'])-1)
	// {
	// 	array_push($tcatlabels,'Unverified'.' ('.(100-($tcattotalpercentage/count($cat['tagged']))).' %)');
	// 	array_push($tcatdata,(100-($tcattotalpercentage/count($cat['tagged']))));
	// 	array_push($atcatlabels,'Unverified'.' ('.(100-($atcattotalpercentage/count($cat['tagged']))).' %)');
	// 	array_push($atcatdata,(100-($atcattotalpercentage/count($cat['tagged']))));
	// }
	$cnt1++;
	}
?>
var taggedctxP = document.getElementById("taggedpieChart").getContext('2d');
var taggedpieChart = new Chart(taggedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($tcatlabels);?>,
datasets: [{
data: <?php echo json_encode($tcatdata);?>,
backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360"],
hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774"]
}]
},
options: {
responsive: true
}
});

var ataggedctxP = document.getElementById("amounttaggedpieChart").getContext('2d');
var ataggedpieChart = new Chart(ataggedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($atcatlabels);?>,
datasets: [{
data: <?php echo json_encode($atcatdata);?>,
backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"],
hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774","#e5e5e5"]
}]
},
options: {
responsive: true
}
});

<?php
}
$utcatlabels=[];
$utcatdata=[];
$autcatlabels=[];
$autcatdata=[];
$cnt2=0;
$utcattotalpercentage=0;
$autcattotalpercentage=0;
$untaggedOverall=[];
$totaluntaggedOverall=[];
$amountuntaggedOverall=[];
$amounttotaluntaggedOverall=[];
if(count($cat['untagged'])>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD')){
	foreach($cat['untagged'] as $utcat)
	{
		$untaggedOverall[$utcat['category']]=$utcat['verified'];
		$totaluntaggedOverall[$utcat['category']]=$utcat['total'];
		$amountuntaggedOverall[$utcat['category']]=$utcat['verifiedamount'];
		$amounttotaluntaggedOverall[$utcat['category']]=$utcat['totalamount'];
		$utcattotalpercentage+=$utcat['percentage'];
		$autcattotalpercentage+=$utcat['amountpercentage'];
	array_push($utcatlabels,$utcat['category'].' ('.round(($utcat['percentage']/count($cat['untagged'])),2).' %)');
	array_push($utcatdata,round(($utcat['percentage']/count($cat['untagged'])),2));
	array_push($autcatlabels,$utcat['category'].' ('.$utcat['amountpercentage'].' %)');
	array_push($autcatdata,$utcat['amountpercentage']);
	// if($cnt2 == count($cat['untagged'])-1)
	// {
	// 	array_push($utcatlabels,'Unverified'.' ('.(100-($utcattotalpercentage/count($cat['untagged']))).' %)');
	// 	array_push($utcatdata,(100-($utcattotalpercentage/count($cat['untagged']))));
	// 	array_push($autcatlabels,'Unverified'.' ('.(100-($autcattotalpercentage/count($cat['untagged']))).' %)');
	// 	array_push($autcatdata,(100-($autcattotalpercentage/count($cat['untagged']))));
	// }
	$cnt2++;
	}
?>
var untaggedctxP = document.getElementById("untaggedpieChart").getContext('2d');
var untaggedpieChart = new Chart(untaggedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($utcatlabels);?>,
datasets: [{
data: <?php echo json_encode($utcatdata);?>,
backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"],
hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774","#e5e5e5"]
}]
},
options: {
responsive: true
}
});
var auntaggedctxP = document.getElementById("amountuntaggedpieChart").getContext('2d');
var auntaggedpieChart = new Chart(auntaggedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($autcatlabels);?>,
datasets: [{
data: <?php echo json_encode($autcatdata);?>,
backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"],
hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774","#e5e5e5"]
}]
},
options: {
responsive: true
}
});
<?php
}
$uscatlabels=array();
$uscatdata=array();
$auscatlabels=array();
$auscatdata=array();
$cnt3=0;
$uscattotalpercentage=0;
$auscattotalpercentage=0;
$ustaggedOverall=[];
$totalustaggedOverall=[];
$amountustaggedOverall=[];
$amounttotalustaggedOverall=[];
if(count($cat['unspecified'])>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD')){
	foreach($cat['unspecified'] as $uscat)
	{
		$untaggedOverall[$uscat['category']]=$uscat['verified'];
		$totalustaggedOverall[$uscat['category']]=$uscat['total'];
		$amountustaggedOverall[$uscat['category']]=$uscat['verifiedamount'];
		$amounttotalustaggedOverall[$uscat['category']]=$uscat['totalamount'];
		$uscattotalpercentage+=$uscat['percentage'];
		$auscattotalpercentage+=$uscat['amountpercentage'];
		array_push($uscatlabels,$uscat['category'].' ('.round(($uscat['percentage']/count($cat['unspecified'])),2).' %)');
		array_push($uscatdata,round(($uscat['percentage']/count($cat['unspecified'])),2));
		array_push($auscatlabels,$uscat['category'].' ('.$uscat['amountpercentage'].' %)');
		array_push($auscatdata,$uscat['amountpercentage']);
		// if($cnt3 == count($cat['unspecified'])-1)
		// {
		// 	array_push($uscatlabels,'Unverified'.' ('.(100-($uscattotalpercentage/count($cat['unspecified']))).' %)');
		// 	array_push($uscatdata,(100-($uscattotalpercentage/count($cat['unspecified']))));
		// 	array_push($auscatlabels,'Unverified'.' ('.(100-($auscattotalpercentage/count($cat['unspecified']))).' %)');
		// 	array_push($auscatdata,(100-($auscattotalpercentage/count($cat['unspecified']))));
		// }
		$cnt3++;
	
	}
?>
var unspecifiedctxP = document.getElementById("unspecifiedpieChart").getContext('2d');
var unspecifiedpieChart = new Chart(unspecifiedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($uscatlabels);?>,
datasets: [{
data: <?php echo json_encode($uscatdata);?>,
backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"],
hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774","#e5e5e5"]
}]
},
options: {
responsive: true
}
});
var aunspecifiedctxP = document.getElementById("amountunspecifiedpieChart").getContext('2d');
var aunspecifiedpieChart = new Chart(aunspecifiedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($auscatlabels);?>,
datasets: [{
data: <?php echo json_encode($auscatdata);?>,
backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"],
hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774","#e5e5e5"]
}]
},
options: {
responsive: true
}
});
<?php 
}
$yverifiernames=array();
$yverifierperc=array();
if($listing['ytotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
{
	foreach($listing['projectverifiers'] as $list)
	{
	
		array_push($yverifiernames,get_UserName($list->user_id).' ('.round(($list->usertagged/$listing['ytotal'])*100,2).' %)');
		array_push($yverifierperc,round(($list->usertagged/$listing['ytotal'])*100,2));
		
	}	
?>

var restaggedctxP = document.getElementById("resourcetaggedpieChart").getContext('2d');
var restaggedpieChart = new Chart(restaggedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($yverifiernames);?>,
datasets: [{
data: <?php echo json_encode($yverifierperc);?>,
backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"],
hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774","#e5e5e5"]
}]
},
options: {
responsive: true
}
});		
<?php 
}
$yverifiernames=array();
$yverifierperc=array();
if($listing['ntotal']>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
{
	foreach($listing['projectverifiers'] as $list)
	{
		array_push($yverifiernames,get_UserName($list->user_id).' ('.round(($list->useruntagged/$listing['ntotal'])*100,2).' %)');
		array_push($yverifierperc,round(($list->useruntagged/$listing['ntotal'])*100,2));
	
	}
?>
var resuntaggedctxP = document.getElementById("resourceuntaggedpieChart").getContext('2d');
var resuntaggedpieChart = new Chart(resuntaggedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($yverifiernames);?>,
datasets: [{
data: <?php echo json_encode($yverifierperc);?>,
backgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870", "#A8B3C5", "#616774","#e5e5e5"],
hoverBackgroundColor: ["#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"]
}]
},
options: {
responsive: true
}
});
<?php 
}
$yverifiernames=array();
$yverifierperc=array();
if($listing['natotal']>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
{
	foreach($listing['projectverifiers'] as $list)
	{
		array_push($yverifiernames,get_UserName($list->user_id).' ('.round(($list->userunspecified/$listing['natotal'])*100,2).' %)');
		array_push($yverifierperc,round(($list->userunspecified/$listing['natotal'])*100,2));
	
	}
?>
var resunspecifiedctxP = document.getElementById("resourceunspecifiedpieChart").getContext('2d');
var resunspecifiedpieChart = new Chart(resunspecifiedctxP, {
type: 'pie',
data: {
labels: <?php echo json_encode($yverifiernames);?>,
datasets: [{
data: <?php echo json_encode($yverifierperc);?>,
backgroundColor: ["#FFC870","#FF5A5E", "#5AD3D1",  "#A8B3C5", "#616774","#e5e5e5"],
hoverBackgroundColor: ["#F7464A", "#FDB45C", "#46BFBD", "#949FB1", "#4D5360","#e5e5e5"]
}]
},
options: {
responsive: true
}
});
<?php 
}
$merger=[];
$totalmerger=[];
$amountmerger=[];
$amounttotalmerger=[];
if(count($cat['tagged'])>0 && count($cat['untagged'])>0 && count($cat['unspecified'])>0)
{
	foreach (array_keys($taggedOverall + $untaggedOverall+$ustaggedOverall) as $item) {
       $merger[$item] = (isset($taggedOverall[$item]) ? $taggedOverall[$item] : 0) + (isset($untaggedOverall[$item]) ? $untaggedOverall[$item] : 0) + (isset($ustaggedOverall[$item]) ? $ustaggedOverall[$item] : 0);
	}
	foreach (array_keys($totaltaggedOverall + $totaluntaggedOverall+$ustaggedOverall) as $item) {
        $totalmerger[$item] = (isset($totaltaggedOverall[$item]) ? $totaltaggedOverall[$item] : 0) + (isset($totaluntaggedOverall[$item]) ? $totaluntaggedOverall[$item] : 0) + (isset($totalustaggedOverall[$item]) ? $totalustaggedOverall[$item] : 0);
	}
	foreach (array_keys($amounttaggedOverall + $amountuntaggedOverall+$amountustaggedOverall) as $item) {
		$amountmerger[$item] = (isset($amounttaggedOverall[$item]) ? $amounttaggedOverall[$item] : 0) + (isset($amountuntaggedOverall[$item]) ? $amountuntaggedOverall[$item] : 0) + (isset($amountustaggedOverall[$item]) ? $amountustaggedOverall[$item] : 0);
	 }
	 foreach (array_keys($amounttotaltaggedOverall + $amounttotaluntaggedOverall+$amountustaggedOverall) as $item) {
		 $amounttotalmerger[$item] = (isset($amounttotaltaggedOverall[$item]) ? $amounttotaltaggedOverall[$item] : 0) + (isset($amounttotaluntaggedOverall[$item]) ? $amounttotaluntaggedOverall[$item] : 0) + (isset($amounttotalustaggedOverall[$item]) ? $amounttotalustaggedOverall[$item] : 0);
	 }
	
}
else if(count($cat['tagged'])>0 && count($cat['untagged'])>0 && count($cat['unspecified'])==0)
{
	foreach (array_keys($taggedOverall + $untaggedOverall) as $item) {
        $merger[$item] = (isset($taggedOverall[$item]) ? $taggedOverall[$item] : 0) + (isset($untaggedOverall[$item]) ? $untaggedOverall[$item] : 0);
	}
	foreach (array_keys($totaltaggedOverall + $totaluntaggedOverall) as $item) {
        $totalmerger[$item] = (isset($totaltaggedOverall[$item]) ? $totaltaggedOverall[$item] : 0) + (isset($totaluntaggedOverall[$item]) ? $totaluntaggedOverall[$item] : 0);
	}
	foreach (array_keys($amounttaggedOverall + $amountuntaggedOverall) as $item) {
		$amountmerger[$item] = (isset($amounttaggedOverall[$item]) ? $amounttaggedOverall[$item] : 0) + (isset($amountuntaggedOverall[$item]) ? $amountuntaggedOverall[$item] : 0);
	 }
	 foreach (array_keys($amounttotaltaggedOverall + $amounttotaluntaggedOverall) as $item) {
		 $amounttotalmerger[$item] = (isset($amounttotaltaggedOverall[$item]) ? $amounttotaltaggedOverall[$item] : 0) + (isset($amounttotaluntaggedOverall[$item]) ? $amounttotaluntaggedOverall[$item] : 0);
	 }
}
else if(count($cat['tagged'])>0 && count($cat['untagged'])==0 && count($cat['unspecified'])>0)
{
	foreach (array_keys($taggedOverall + $ustaggedOverall) as $item) {
        $merger[$item] = (isset($taggedOverall[$item]) ? $taggedOverall[$item] : 0) + (isset($ustaggedOverall[$item]) ? $ustaggedOverall[$item] : 0);
	}
	foreach (array_keys($totaltaggedOverall + $ustaggedOverall) as $item) {
        $totalmerger[$item] = (isset($totaltaggedOverall[$item]) ? $totaltaggedOverall[$item] : 0) + (isset($totalustaggedOverall[$item]) ? $totalustaggedOverall[$item] : 0);
	}
	foreach (array_keys($amounttaggedOverall+$amountustaggedOverall) as $item) {
		$amountmerger[$item] = (isset($amounttaggedOverall[$item]) ? $amounttaggedOverall[$item] : 0) + (isset($amountustaggedOverall[$item]) ? $amountustaggedOverall[$item] : 0);
	 }
	 foreach (array_keys($amounttotaltaggedOverall +$amountustaggedOverall) as $item) {
		 $amounttotalmerger[$item] = (isset($amounttotaltaggedOverall[$item]) ? $amounttotaltaggedOverall[$item] : 0) + (isset($amounttotalustaggedOverall[$item]) ? $amounttotalustaggedOverall[$item] : 0);
	 }
}
else if(count($cat['tagged'])==0 && count($cat['untagged'])>0 && count($cat['unspecified'])>0)
{
	foreach (array_keys($untaggedOverall+$ustaggedOverall) as $item) {
        $merger[$item] = (isset($untaggedOverall[$item]) ? $untaggedOverall[$item] : 0) + (isset($ustaggedOverall[$item]) ? $ustaggedOverall[$item] : 0);
	}
	foreach (array_keys($totaluntaggedOverall+$ustaggedOverall) as $item) {
        $totalmerger[$item] = (isset($totaluntaggedOverall[$item]) ? $totaluntaggedOverall[$item] : 0) + (isset($totalustaggedOverall[$item]) ? $totalustaggedOverall[$item] : 0);
	}
	foreach (array_keys($amountuntaggedOverall+$amountustaggedOverall) as $item) {
		$amountmerger[$item] = (isset($amountuntaggedOverall[$item]) ? $amountuntaggedOverall[$item] : 0) + (isset($amountustaggedOverall[$item]) ? $amountustaggedOverall[$item] : 0);
	 }
	 foreach (array_keys($amounttotaluntaggedOverall+$amountustaggedOverall) as $item) {
		 $amounttotalmerger[$item] = (isset($amounttotaluntaggedOverall[$item]) ? $amounttotaluntaggedOverall[$item] : 0) + (isset($amounttotalustaggedOverall[$item]) ? $amounttotalustaggedOverall[$item] : 0);
	 }
}
else if(count($cat['tagged'])==0 && count($cat['untagged'])==0 && count($cat['unspecified'])>0)
{
	$merger=$ustaggedOverall;
	$totalmerger=$totalustaggedOverall;
	$amountmerger=$amountustaggedOverall;
	$amounttotalmerger=$amounttotalustaggedOverall;
}
else if(count($cat['tagged'])==0 && count($cat['untagged'])>0 && count($cat['unspecified'])==0)
{
	$merger=$untaggedOverall;
	$totalmerger=$totaluntaggedOverall;
	$amountmerger=$amountuntaggedOverall;
	$amounttotalmerger=$amounttotaluntaggedOverall;
}
else if(count($cat['tagged'])>0 && count($cat['untagged'])==0 && count($cat['unspecified'])==0)
{
	$merger=$taggedOverall;
	$totalmerger=$totaltaggedOverall;
	$amountmerger=$amounttaggedOverall;
	$amounttotalmerger=$amounttotaltaggedOverall;
}
$libarlabels=[];
$libarvalues=[];
$amountbarlabels=[];
$amountbarvalues=[];
foreach($merger as $key=> $item )
{
	array_push($libarlabels,$key);
	array_push($libarvalues,round(($item/$totalmerger[$key])*100,2));
	
}
foreach($amountmerger as $key=> $item )
{
	array_push($amountbarlabels,$key);
	array_push($amountbarvalues,round(($item/$amounttotalmerger[$key])*100,2));
}
?>
var libarcharts = document.getElementById("libarchart").getContext('2d');
libarcharts.height = 200;
var libarchart = new Chart(libarcharts, {
    type: 'horizontalBar',
    data: {
    labels: <?php echo json_encode($libarlabels);?>,
    datasets: [{
      label: '(%)',
	  barThickness:20,
      data: <?php echo json_encode($libarvalues);?>,
      backgroundColor: [
        "#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5","#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"
      ],
      borderColor: [
        "#F7464A", "#FDB45C", "#46BFBD", "#949FB1", "#4D5360","#e5e5e5","#F7464A", "#FDB45C", "#46BFBD", "#949FB1", "#4D5360","#e5e5e5"
      ],
      borderWidth: 1
    }]
  },
    options: {
		
    scales: {
      xAxes: [{
        gridLines: {
                offsetGridLines: true
            }
      }],
      
    }
	}
});
var amountbarcharts = document.getElementById("amountbarchart").getContext('2d');
var amountbarchart = new Chart(amountbarcharts, {
    type: 'horizontalBar',
    data: {
    labels: <?php echo json_encode($amountbarlabels);?>,
    datasets: [{
      label: '(%)',
	  barThickness:20,
      data: <?php echo json_encode($amountbarvalues);?>,
      backgroundColor: [
        "#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5","#F7464A", "#46BFBD", "#FDB45C", "#949FB1", "#4D5360","#e5e5e5"
      ],
      borderColor: [
        "#F7464A", "#FDB45C", "#46BFBD", "#949FB1", "#4D5360","#e5e5e5","#F7464A", "#FDB45C", "#46BFBD", "#949FB1", "#4D5360","#e5e5e5"
      ],
      borderWidth: 1
    }]
  },
    options: {
		
    scales: {
      xAxes: [{
        gridLines: {
                offsetGridLines: true
            }
      }],
      
    }
	}
});
$('#nav-table-tab').on('click',function(){
	$('#nav-table').addClass('show active');
	$('#nav-chart').removeClass('show active')
});
$('#nav-chart-tab').on('click',function(){
	$('#nav-chart').addClass('show active');
	$('#nav-table').removeClass('show active')
});
$('#nav-table-tab2').on('click',function(){
	$('#nav-table2').addClass('show active');
	$('#nav-chart2').removeClass('show active')
});
$('#nav-chart-tab2').on('click',function(){
	$('#nav-chart2').addClass('show active');
	$('#nav-table2').removeClass('show active')
});
$('#nav-table-tab3').on('click',function(){
	$('#nav-table3').addClass('show active');
	$('#nav-chart3').removeClass('show active')
});
$('#nav-chart-tab3').on('click',function(){
	$('#nav-chart3').addClass('show active');
	$('#nav-table3').removeClass('show active')
});
$('.finishproject').click(function(){
    $('#modalfinishconfirmation').modal('show');
	
    
});

$('#confirmVerificationProceed').click(function(){
	var radioValue = $("input[name='actionverificationproject']:checked").val();
	if(radioValue=='finishproject')
	{
		$('#modalfinishconfirmation').modal('hide');
    	$('#modalfinalconfirmation').modal('show');
	}
	else if(radioValue=='abortproject')
	{
		$('#modalfinishconfirmation').modal('hide');
    	$('#modalabortconfirmation').modal('show');
	}
	else
	{
		alert("Kindly select the closing stage of your verification cycle");
		return false;
	}
    
});
$('#finalconfirmationproceed').click(function(){
	var remarks=$('#finish_remarks').val();
	var project_id="<?php echo $projects[0]->id; ?>"	;
	if(remarks !='')
	{
		console.log("<?php echo $_SESSION['logged_in']['id'];?>");
		$.ajax({
			url:"<?php echo base_url();?>index.php/api/tasks/finalizeverifiedproject",
			method:"POST",
			data:"remarks="+remarks+"&project_id="+project_id+"&status=1&project_finished_by=<?php echo $_SESSION['logged_in']['id'];
			?>",
			success:function(res)
			{
				$('#modalfinalconfirmation').modal('hide');
    			$('#modalfinished').modal('show');
			}
		});

	}
	else
	{
		$.ajax({
			url:"<?php echo base_url();?>index.php/api/tasks/finalizeverifiedproject",
			method:"POST",
			data:"project_id="+project_id+"&status=1&project_finished_by=<?php echo $_SESSION['logged_in']['id'];
			?>",
			success:function(res)
			{
				$('#modalfinalconfirmation').modal('hide');
    			$('#modalfinished').modal('show');
			}
		});
	}
    
});
$('#abortconfirmationproceed').click(function(){
	var remarks=$('#abort_remarks').val();
	var project_id="<?php echo $projects[0]->id; ?>";
	if(remarks !='')
	{
		$.ajax({
			url:"<?php echo base_url();?>index.php/api/Tasks/finalizeverifiedproject",
			method:"POST",
			data:"remarks="+remarks+"&project_id="+project_id+"&status=2&project_finished_by=<?php echo $this->user_id;?>",
			success:function(res)
			{
				$('#modalabortconfirmation').modal('hide');
    			$('#modalaborted').modal('show');
			}
		});

	}
	else
	{
		$.ajax({
			url:"<?php echo base_url();?>index.php/api/Tasks/finalizeverifiedproject",
			method:"POST",
			data:"project_id="+project_id+"&status=2&project_finished_by=<?php echo $this->user_id;?>",
			success:function(res)
			{
				$('#modalabortconfirmation').modal('hide');
    			$('#modalaborted').modal('show');
			}
		});
	}
    
});
$('.closethismodel').click(function(){
	window.location.reload();
});
</script>
