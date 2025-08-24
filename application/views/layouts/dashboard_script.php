<?php
$open_projects=0;
$closed_projects=0;
$cancelled_projects=0;
$open_projectdetails="";
$close_projectdetails="";
$cancel_projectdetails="";
foreach($projects as $project)
{
    $verifiercount = check_verifier_count($project->id,$this->user_id);
    $check_itemowner_count = check_itemowner_count($project->id,$this->user_id);
    $check_process_owner_count = check_process_owner_count($project->id,$this->user_id);
    $check_manager_count = check_manager_count($project->id,$this->user_id);

    if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){

        if($project->status==0)
        {
            $open_projects++;
            if($project->VerifiedQuantity !=0){
            $open_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
        }else{
            $open_projectdetails.='0%';
        }
        }
        if($project->status==1)
        {
            $closed_projects++;
            $close_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
        }
        if($project->status==2)
        {
            $cancelled_projects++;
            $cancel_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
        }
    }
}
?>
<script>
  // pie chart
var open_p='<?php echo $open_projects; ?>';
var close_p='<?php echo $closed_projects; ?>';
var cancelled_p='<?php echo $cancelled_projects; ?>';
var o_pd='<?php echo $open_projectdetails; ?>';
var cl_pd='<?php echo $close_projectdetails; ?>';
var ca_pd='<?php echo $cancel_projectdetails; ?>';
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ["Open Project ("+open_p+")"+o_pd, "Closed Project ("+close_p+")"+cl_pd, "Cancelled Project ("+cancelled_p+")"+ca_pd],
        datasets: [{
        backgroundColor: [
            "#2ecc71",
            "#e74c3c",
            "#34495e"
        ],
        data: [open_p,close_p, cancelled_p]
        }]
    }
});
$(document).ready(function(){
    var extensions = {
        "sFilter": "dataTables_filter custom_filter_class",
        "sLength": "dataTables_length custom_length_class"
    }
    // Used when bJQueryUI is false
    $.extend($.fn.dataTableExt.oStdClasses, extensions);
    // Used when bJQueryUI is true
    $.extend($.fn.dataTableExt.oJUIClasses, extensions);
    $('table.table').DataTable( {
        "paging":false,
        "fnRowCallback":function(nRow, aData, iDisplayIndex){
            $("td:first", nRow).html(iDisplayIndex +1);
           return nRow;
        }
    });
});


</script>
<style>
    .custom_length_class select
    {
        width: auto;
    -webkit-appearance: menulist;
    }
    .custom_filter_class input{
        width: auto;
    display: inline-block;
    }
    table th,table td{
    padding:5px;
    font-size: 0.875rem;
}
    </style>