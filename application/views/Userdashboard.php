
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
 $user_id=$this->user_id;
 $entity_code=$this->admin_registered_entity_code;
$user_role_manager_cnt=get_user_role_cnt_managers($user_id,$entity_code);
$user_role_admin_cnt=get_user_role_cnt_admin($user_id,$entity_code);

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
	background: #5ca1e2 !important;
    color: #fff;
	min-height: 100px;
	}
	.card-header h2 {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
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

<?php 

$Date = "2024-04-09 01:00:00"; // Set the date to the current date

// echo '<pre>';
// print_r(date("Y-m-d",strtotime($Date. ' + 1 day')));
// echo '</pre>';
// exit();

?>

<!-- Other Reference :- https://jsfiddle.net/ananyadeka/harmpucn/ -->


<div class="container-fluid">
    <div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h2>No. of Companies mapped</h2>
            </div>
           <div class="card-body card-body-n">
                <h2 style="text-align: center;font-weight: bold;">
                <?php echo $Companies_Mapped;?>
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h2>No. of Locations mapped</h2>
            </div>
            <div class="card-body card-body-n">
                <h2 style="text-align: center;font-weight: bold;">
                    <?php echo $Locations_mapped;?>
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
          <?php 

            $Project_overdue_count = 0;
            foreach($overdue_array as $overdue_array_key=>$overdue_array_value){
                $Project_overdue_count = $Project_overdue_count+(int)$overdue_array_value['y'];
            }

             $Project_withindate_count = 0;
            foreach($withindate_array as $withindate_array_key=>$withindate_array_value){
                $Project_withindate_count = $Project_withindate_count+(int)$withindate_array_value['y'];
            }

            ?>
        <div class="card">
            <div class="card-header ">
                <h2>No. of Open Projects</h2>
            </div>
            <div class="card-body card-body-n">

            <h2 style="text-align: center;font-weight: bold;">
                <?php echo (int)$Project_overdue_count+(int)$Project_withindate_count; ?>    
            </h2>

          

            <table class="table-bordered" style="margin: auto;width: 100%;text-align:center">
                <thead style="text-align:center">
                    <th>Overdue</th>
                    <th>Within Date</th>
                </thead>
                <tbody style="text-align:center">
                    <td style="text-align:center"><?php echo $Project_overdue_count; ?></td>
                    <td style="text-align:center"><?php echo $Project_withindate_count; ?></td>
                </tbody>
            </table>

            
                <!-- <p class="txt-cardp text-left pt-3">* added since April 2023</p> -->
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h2>No. of Closed Projects</h2>
            </div>
            <div class="card-body card-body-n">
                <h2 style="text-align: center;font-weight: bold;">
                <?php echo $Closed_Projects; ?>
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h2>No. of Cancelled Projects</h2>
            </div>
            <div class="card-body card-body-n">
                <h2 style="text-align: center;font-weight: bold;">
                <?php echo $Cancelled_Projects; ?>
                </h2>
            </div>
        </div>
    </div>
    </div>
</div>



    <div class="container-fluid">
 <div class="row">


    <div class="col-md-12 mt-5">
        <form id="userForm" method="post" class="bg-white">
                <br>
                <div class="row">
                <div class="col-md-2 form-row">
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">Select Company</label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        <option value="">All</option>
                        <?php foreach($company_data_list as $row_com_list){ 
                             $company_n=get_company_row($row_com_list['company_id']);
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
    <!-- </div> -->



    <!-- <div class="col-md-12 mt-5"> -->
			<div  style="background: #fff;height: 150px;padding: 15px;">
				<h2 class="text-center">Applicable to Open Project</h2>
				
			</div>
			<div id="TypeSubscriptionActiveChart" style="height: 370px; width: 100%;"></div>
        </div>

        <div class="col-md-12 mt-5" >

          <form id="application_open_project_userForm" method="post" class="bg-white">
                <br>
                <div class="row p-3">
                

                <div class="col-md-3">
                    <label class="form-label">Select Company</label>
                    <select name="application_open_project_company_id" id="application_open_project_company_id" class="form-control" required>
                        <option value="">All</option>
                        <?php foreach($company_data_list as $row_com_list){ 
                             $company_n=get_company_row($row_com_list['company_id']);
                            ?>
                        <option value="<?php echo $company_n->id;?>"><?php echo $company_n->company_name.'('. $company_n->short_code.')';?></option>

                        <?php }?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Select Location</label>
                    <select name="application_open_project_company_location" id="application_open_project_company_location" class="form-control">
                        <option value="">All</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Select Project ID</label>
                    <select name="application_open_project_project_id" id="application_open_project_project_id" class="form-control">
                        <option value="">All</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Select Verifier</label>
                    <select name="application_open_project_verifier" id="application_open_project_verifier" class="form-control">
                        <option value="">All</option>
                         <?php foreach($vrifier_users as $vrifier_users_value){ ?>
                            <option value="<?php echo $vrifier_users_value->user_id;?>"><?php echo $vrifier_users_value->user_firstName;?></option> 
                         <?php } ?>
                    </select>
                </div>



                <div class="col-md-2 form-row">
                <button type="submit" class="btn btn-success">GO</button>
              </div>
                </div>
               
                
                <br>
        </form>

            <div style="background: #fff;padding: 15px;">
            <h2 class="text-center">Applicable to Open Projects only</h2>
            <div id="chartContainer" style="height: 400px; width: 100%;"></div>
            </div>
        </div> 

        </div>  
</div>

<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<?php 

?>
<script>



window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
    theme: "light2",
    animationEnabled: true,
    exportEnabled: true,
    title: {
        text: "",
        fontSize: 24    
    },
    axisY: {
        labelFormatter: function(e){
        jsDate = new Date(e.value * 1000); // Convert seconds to milliseconds
        return jsDate.toISOString().split('T')[0];
        },
        gridThickness: 1
    },
    toolTip:{
        contentFormatter: function ( e ) {
        Start_Date = new Date(e.entries[0].dataPoint.y[0] * 1000); // Convert seconds to milliseconds
        End_Date = new Date(e.entries[0].dataPoint.y[1] * 1000); // Convert seconds to milliseconds
        return "<strong>" + e.entries[0].dataPoint.label + "</strong></br> Start: "+Start_Date.toISOString().split('T')[0]+"</br>End : "+End_Date.toISOString().split('T')[0];
        },
        backgroundColor: "#f7f7f7",
        fontColor: "#333",
        borderThickness: 1,
        borderColor: "#ddd"
    },
  data: [
    {
      type: "rangeBar",
      dataPoints: <?php echo json_encode($stackedBarchartContainer_array, JSON_NUMERIC_CHECK); ?>
    

    }
  ]
});

chart.render();

    




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
            title: "No. of Active Projects",
            suffix: "",
            interval: 1,
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries
        },
        data: [
            {
                type: "stackedColumn",
                name: "Overdue",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: <?php echo json_encode($overdue_array, JSON_NUMERIC_CHECK); ?>
            },{
                type: "stackedColumn",
                name: "Withindate",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: <?php echo json_encode($withindate_array, JSON_NUMERIC_CHECK); ?>
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


document.getElementById('company_id').onchange = function() {
    var company_id = this.value;
    var fd = new FormData();
    fd.append('company_id',[company_id]);
    $.ajax({
      url: "<?php echo base_url();?>index.php/plancycle/getlocationdata",
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      success: function(data) {
        $('#company_location').find('option').remove().end().append(data);
      }
    });
}

document.getElementById('application_open_project_company_id').onchange = function() {
    var company_id = this.value;
    var fd = new FormData();
    fd.append('company_id',[company_id]);
    $.ajax({
      url: "<?php echo base_url();?>index.php/plancycle/getlocationdata",
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      success: function(data) {
        $('#application_open_project_company_location').find('option').remove().end().append(data);
      }
    });
}


document.getElementById('application_open_project_company_location').onchange = function() {
    var company_id = $("#application_open_project_company_id").val();
    var location_id = this.value;
    var fd = new FormData();
    fd.append('company_id',[company_id]);
    fd.append('location_id',[location_id]);
    $.ajax({
      url: "<?php echo base_url();?>index.php/plancycle/getprojectidndata",
      type: 'POST',
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      success: function(data) {
        $('#application_open_project_project_id').find('option').remove().end().append(data);
      }
    });
}
</script>


<script>
    function toggleDataSeries(e) {
        if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else {
            e.dataSeries.visible = true;
        }
        e.chart.render();
    }
$(document).ready(function(){
    $('#userForm').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: "<?php echo base_url();?>index.php/Dashboard/ApplicableOpenProjectGraph",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res){


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
                    title: "No. of Subscriptions",
                    suffix: "",
                    interval: 1,
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                data: [
                    {
                        type: "stackedColumn",
                        name: "Overdue",
                        showInLegend: true,
                        yValueFormatString: "#,##0",
                        dataPoints: res.overdue_array
                    },{
                        type: "stackedColumn",
                        name: "Withindate",
                        showInLegend: true,
                        yValueFormatString: "#,##0",
                        dataPoints: res.withindate_array
                    }
                ]
            });
            chart.render();


                // if(res.status == 'success'){
                //     $('#response').html('<p style="color:green;">'+res.message+'</p>');
                //     $('#userForm')[0].reset();
                // } else {
                //     $('#response').html('<p style="color:red;">'+res.message+'</p>');
                // }
            },
            error: function(){
                $('#response').html('<p style="color:red;">Something went wrong.</p>');
            }
        });
    });


     $('#application_open_project_userForm').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: "<?php echo base_url();?>index.php/Dashboard/ApplicableOpenProjectGraphProjectWise",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res){


                var chart = new CanvasJS.Chart("chartContainer", {
                theme: "light2",
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "",
                    fontSize: 24    
                },
                axisY: {
                    labelFormatter: function(e){
                    jsDate = new Date(e.value * 1000); // Convert seconds to milliseconds
                    return jsDate.toISOString().split('T')[0];
                    },
                    gridThickness: 1
                },
                toolTip:{
                    contentFormatter: function ( e ) {
                    Start_Date = new Date(e.entries[0].dataPoint.y[0] * 1000); // Convert seconds to milliseconds
                    End_Date = new Date(e.entries[0].dataPoint.y[1] * 1000); // Convert seconds to milliseconds
                    return "<strong>" + e.entries[0].dataPoint.label + "</strong></br> Start: "+Start_Date.toISOString().split('T')[0]+"</br>End : "+End_Date.toISOString().split('T')[0];
                    },
                    backgroundColor: "#f7f7f7",
                    fontColor: "#333",
                    borderThickness: 1,
                    borderColor: "#ddd"
                },
            data: [
                {
                type: "rangeBar",
                dataPoints: res.stackedBarchartContainer_array
                

                }
            ]
            });

            chart.render();

            }

        });

    });



});
</script>