
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


<div class="container-fluid">
    <div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h2>No. of Companies mapped</h2>
            </div>
            <div class="card-body card-body-n" style="padding: 2px 1px;">
                <?php $plan_row=get_plan_row(2);?>
                <p class="txt-cardp" style="text-align: center;"><?php echo $subscription_plan_details->title;?></p>
                <ul>
                <li>Activation Date: <b><?php echo date("d-M-Y",strtotime($registered_user_plan_details->plan_start_date));?></b></li>
                </ul>
                <p class="txt-cardp" style="text-align: center;">Plan Brief:</p>
                <ul>
                <li>No. of Entities – <b><?php echo $subscription_plan_details->allowed_entities_no;?></b></li>
                <li>No. of Locations under each Entity – <b><?php echo $subscription_plan_details->location_each_entity;?></b></li>
                <li>Total No. of Users – <b><?php echo $subscription_plan_details->user_number_register;?></b></li>
                <li>Line Item Available – <b><?php echo $subscription_plan_details->line_item_avaliable;?></b></li>
                </ul>
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
                <?php echo date("d-M-Y",strtotime($registered_user_plan_details->plan_end_date));?>
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header ">
                <h2>No. of Open Projects</h2>
            </div>
            <div class="card-body card-body-n">

            <h2 style="text-align: center;font-weight: bold;">
                3
                </h2>

            <table class="table-bordered" style="margin: auto;width: 100%;text-align:center">
                <thead style="text-align:center">
                    <th>Within Time</th>
                    <th>Overdue</th>
                </thead>
                <tbody style="text-align:center">
                    <td style="text-align:center">2</td>
                    <td style="text-align:center">0</td>
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
                <?php echo $total_company_count; ?>
                </h2>
                <?php 
                $Companies_count = (int)$subscription_plan_details->allowed_entities_no-(int)$total_company_count;
                ?>
                <p class="txt-cardp" style="text-align: center;"><?php echo " (",$Companies_count." remaining)"; ?></p>
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
                <?php echo $total_company_locations_count; ?>
                </h2>
                <?php 
                $Location_count = (int)$subscription_plan_details->location_each_entity-(int)$total_company_locations_count;
                ?>
                <p class="txt-cardp" style="text-align: center;"><?php echo " (".$Location_count." remaining)"; ?></p>
            </div>
        </div>
    </div>
    </div>
</div>



    <div class="container-fluid">
        



    <div class="row">
        <div class="col-md-12">
            <div class="row" id="#myCarousel">
                <?php if(!empty($projects)){ ?>
                    <canvas id="myChart" height="140"></canvas>
                <?php }else{ ?>
                <canvas id="myChart" height="140"></canvas>
                    <div class="col-md-12"><h3 class="text-center" style="width:100%;">No projects Available</h3></div>
                <?php } ?> 
            </div>
        </div>
    </div>


 <div class="row">


    <div class="col-md-12 mt-5">
        <form action="<?php echo base_url();?>index.php/dashboard/index" method="post" class="bg-white">
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
    <!-- </div> -->



    <!-- <div class="col-md-12 mt-5"> -->
			<div  style="background: #fff;height: 150px;padding: 15px;">
				<h2 class="text-center">Type of Subscription Plans / Active Subscriptions</h2>
				
			</div>
			<div id="TypeSubscriptionActiveChart" style="height: 370px; width: 100%;"></div>
        </div>
        </div>  
</div>

<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>



window.onload = function () {
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
</script>
