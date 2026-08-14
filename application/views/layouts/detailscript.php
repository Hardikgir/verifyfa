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
	
	$grandTotal = $listing['ytotal']+$listing['ntotal']+$listing['natotal'];
	$grandVerified = $listing['yverified']+$listing['nverified']+$listing['naverified'];
	$grandUnverified = $grandTotal - $grandVerified;
	$donutData = array();
	$donutLabels = array();
	$donutColors = array();
	$donutHoverColors = array();
	
	if($listing['ytotal']!=0){
		$tagPercent = $grandTotal>0 ? round(($listing['yverified']/$grandTotal)*100,2) : 0;
		$donutData[] = $tagPercent;
		$donutLabels[] = "Tagged (".$tagPercent." %): ".$listing['yverified']." of ".$grandTotal." Li";
		$donutColors[] = "#46BFBD";
		$donutHoverColors[] = "#616774";
	}
	if($listing['ntotal']!=0){
		$untagPercent = $grandTotal>0 ? round(($listing['nverified']/$grandTotal)*100,2) : 0;
		$donutData[] = $untagPercent;
		$donutLabels[] = "Non-Tagged (".$untagPercent." %): ".$listing['nverified']." of ".$grandTotal." Li";
		$donutColors[] = "#FDB45C";
		$donutHoverColors[] = "#007bff";
	}
	if($listing['natotal']!=0){
		$usPercent = $grandTotal>0 ? round(($listing['naverified']/$grandTotal)*100,2) : 0;
		$donutData[] = $usPercent;
		$donutLabels[] = "Unspecified (".$usPercent." %): ".$listing['naverified']." of ".$grandTotal." Li";
		$donutColors[] = "#F7464A";
		$donutHoverColors[] = "#FFC870";
	}
	if($grandUnverified > 0){
		$unvPercent = $grandTotal>0 ? round(($grandUnverified/$grandTotal)*100,2) : 0;
		$donutData[] = $unvPercent;
		$donutLabels[] = "Unverified (".$unvPercent." %) : ".$grandUnverified." of ".$grandTotal." Li";
		$donutColors[] = "#e5e5e5";
		$donutHoverColors[] = "#e5e5e5";
	}
	?>
	var donutdataset=[{
		data: <?php echo json_encode(array_values($donutData));?>,
		      backgroundColor: <?php echo json_encode(array_values($donutColors));?>,
		      hoverBackgroundColor: <?php echo json_encode(array_values($donutHoverColors));?>
	}];
	var donutlabel=<?php echo json_encode(array_values($donutLabels));?>;
	<?php 
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
	// First calculate totals for correct pie proportions
	$totalTaggedVerified=0;
	$totalTaggedVerifiedAmount=0;
	foreach($cat['tagged'] as $tcat){
		$totalTaggedVerified+=$tcat['verified'];
		$totalTaggedVerifiedAmount+=$tcat['verifiedamount'];
	}
	foreach($cat['tagged'] as $tcat)
	{
		$taggedOverall[$tcat['category']]=$tcat['verified'];
		$totaltaggedOverall[$tcat['category']]=$tcat['total'];
		$amounttaggedOverall[$tcat['category']]=$tcat['verifiedamount'];
		$amounttotaltaggedOverall[$tcat['category']]=$tcat['totalamount'];
	$tcattotalpercentage=$tcattotalpercentage+$tcat['percentage'];
	$atcattotalpercentage=$atcattotalpercentage+$tcat['amountpercentage'];
	$tcatPercent=$totalTaggedVerified>0?round(($tcat['verified']/$totalTaggedVerified)*100,2):0;
	array_push($tcatlabels,$tcat['category'].' ('.$tcat['verified'].' of '.$totalTaggedVerified.') ('.$tcatPercent.' %)');
	array_push($tcatdata,$tcatPercent);
	$atcatPercent=$totalTaggedVerifiedAmount>0?round(($tcat['verifiedamount']/$totalTaggedVerifiedAmount)*100,2):0;
	array_push($atcatlabels,$tcat['category'].' ('.round($tcat['verifiedamount']/100000,2).' of '.round($totalTaggedVerifiedAmount/100000,2).' Lacs) ('.$atcatPercent.' %)');
	array_push($atcatdata,$atcatPercent);
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
	// First calculate totals for correct pie proportions
	$totalUntaggedVerified=0;
	$totalUntaggedVerifiedAmount=0;
	foreach($cat['untagged'] as $utcat){
		$totalUntaggedVerified+=$utcat['verified'];
		$totalUntaggedVerifiedAmount+=$utcat['verifiedamount'];
	}
	foreach($cat['untagged'] as $utcat)
	{
		$untaggedOverall[$utcat['category']]=$utcat['verified'];
		$totaluntaggedOverall[$utcat['category']]=$utcat['total'];
		$amountuntaggedOverall[$utcat['category']]=$utcat['verifiedamount'];
		$amounttotaluntaggedOverall[$utcat['category']]=$utcat['totalamount'];
		$utcattotalpercentage+=$utcat['percentage'];
		$autcattotalpercentage+=$utcat['amountpercentage'];
	$utcatPercent=$totalUntaggedVerified>0?round(($utcat['verified']/$totalUntaggedVerified)*100,2):0;
	array_push($utcatlabels,$utcat['category'].' ('.$utcat['verified'].' of '.$totalUntaggedVerified.') ('.$utcatPercent.' %)');
	array_push($utcatdata,$utcatPercent);
	$autcatPercent=$totalUntaggedVerifiedAmount>0?round(($utcat['verifiedamount']/$totalUntaggedVerifiedAmount)*100,2):0;
	array_push($autcatlabels,$utcat['category'].' ('.round($utcat['verifiedamount']/100000,2).' of '.round($totalUntaggedVerifiedAmount/100000,2).' Lacs) ('.$autcatPercent.' %)');
	array_push($autcatdata,$autcatPercent);
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
	// First calculate totals for correct pie proportions
	$totalUsVerified=0;
	$totalUsVerifiedAmount=0;
	foreach($cat['unspecified'] as $uscat){
		$totalUsVerified+=$uscat['verified'];
		$totalUsVerifiedAmount+=$uscat['verifiedamount'];
	}
	foreach($cat['unspecified'] as $uscat)
	{
		$ustaggedOverall[$uscat['category']]=$uscat['verified'];
		$totalustaggedOverall[$uscat['category']]=$uscat['total'];
		$amountustaggedOverall[$uscat['category']]=$uscat['verifiedamount'];
		$amounttotalustaggedOverall[$uscat['category']]=$uscat['totalamount'];
		$uscattotalpercentage+=$uscat['percentage'];
		$auscattotalpercentage+=$uscat['amountpercentage'];
		$uscatPercent=$totalUsVerified>0?round(($uscat['verified']/$totalUsVerified)*100,2):0;
		array_push($uscatlabels,$uscat['category'].' ('.$uscat['verified'].' of '.$totalUsVerified.') ('.$uscatPercent.' %)');
		array_push($uscatdata,$uscatPercent);
		$auscatPercent=$totalUsVerifiedAmount>0?round(($uscat['verifiedamount']/$totalUsVerifiedAmount)*100,2):0;
		array_push($auscatlabels,$uscat['category'].' ('.round($uscat['verifiedamount']/100000,2).' of '.round($totalUsVerifiedAmount/100000,2).' Lacs) ('.$auscatPercent.' %)');
		array_push($auscatdata,$auscatPercent);
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
