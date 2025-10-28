
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
            <h2>Subscription Plan Details</h2>
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
            <h2>Current Subscription Valid till</h2>
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
            <h2>Current Subscription Expiring in next</h2>
         </div>
         <div class="card-body card-body-n">
            <h2 style="text-align: center;font-weight: bold;">
               <?php
                  if($registered_user_plan_details->plan_end_date < date("Y-m-d")){
                  }else{
                  $time_remain=get_diff_twodate($registered_user_plan_details->plan_end_date);
                  ?>
               <?php echo $time_remain;?> Left
               <?php } ?>
            </h2>           
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h2>No. of Companies added</h2>
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
            <h2>No. of Locations added</h2>
         </div>
         <div class="card-body card-body-n">
            <h2 style="text-align: center;font-weight: bold;">
               <?php echo $total_company_locations_count; ?>
            </h2>
            <?php 
               $total_entity = (int)$subscription_plan_details->allowed_entities_no*(int)$subscription_plan_details->location_each_entity;
               $Location_count = (int)$total_entity-(int)$total_company_locations_count;
               ?>
            <p class="txt-cardp" style="text-align: center;"><?php echo " (".$Location_count." remaining)"; ?></p>
         </div>
      </div>
   </div>
   <div class="col-md-4">
      <div class="card">
         <div class="card-header">
            <h2>No. of Users added</h2>
         </div>
         <div class="card-body card-body-n">
            <h2 style="text-align: center;font-weight: bold;">
               <?php echo $total_users_count; ?>
            </h2>
            <?php 
               $user_count = (int)$subscription_plan_details->user_number_register-(int)$total_users_count;
               ?>
            <p class="txt-cardp" style="text-align: center;"><?php echo " (",$user_count." remaining)"; ?></p>
         </div>
      </div>
   </div>





    <!-- <div class="row"> -->
                                    <div class="col-md-12">
                                        <nav>
                                            <div class="nav nav-tabs nav-fill nav-justified" id="nav-tab" role="tablist">
                                             <?php /*
                                                <a class="nav-item nav-link " href="#div1" id="tab1" data-toggle="tab" aria-controls="open-project" aria-selected="true"><b>Open Projects</b></a>
                                                <a class="nav-item nav-link" href="#div2" id="tab2" data-toggle="tab" aria-controls="closed-project" aria-selected="true"><b>Closed Projects</b></a>
                                                <a class="nav-item nav-link" href="#div3" data-toggle="tab" aria-controls="cancelled-project" aria-selected="true"><b>Cancelled Projects</b></a> */ ?>
                                                <a class="nav-item nav-link active" href="#div4" data-toggle="tab" aria-controls="request-clear-project" aria-selected="true"><b>Request For Clear Projects</b></a> 
                                            </div>
                                        </nav>
                                        
                                        <div class="tab-content pt-5">


                                            <!--- Open Project Start --->
                                            <div id="div1" class="tab-pane in" aria-labelledby="open-project">
                                                <div class="card">
                                                    <div class="card-header card-header-primary">
                                                        <h4 class="card-title">Open Projects</h4>
                                                        <p class="card-category">Showing all open projects</p>
                                                    </div>
                                                    <div class="card-body">                                                        
                                                        <form id="cleardataform" action="<?php echo base_url();?>index.php/dashboard/deleteproject" method="post">
                                                            <input type="hidden" value="<?php echo $this->main_role;?>" name="user_role" class="clear_project_id">
                                                            <div class="table-responsive">
                                                            <button type="submit" class="btn btn-primary">Clear Project/Data</button>
                                                                <table class="table">
                                                                    <thead class="text-center_">
                                                                        <tr>
                                                                            <!-- <th>#</th> -->
                                                                            <?php if($this->main_role == '5'){
                                                                                echo '<th>#</th>';
                                                                            }else{
                                                                                if($user_role_admin_cnt > 0){ 
                                                                                    echo '<th>#</th>';
                                                                                }
                                                                            } ?>
                                                                            <th><span>Project ID</span></th>
                                                                            <th><span>Project Name</span></th>
                                                                            <th><span>Company</span></th>
                                                                            <th><span>Location</span></th>                                                                            
                                                                            <th><span>Date of Project assigned</span></th>
                                                                            <th><span>Due Date</span></th>
                                                                            <th><span>Remaining/(Overdue) Day</span></th>
                                                                            <th> <span>Stage of Completion</span>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php 
                                                                        $i=0;
                                                                        if(!empty($projects))
                                                                        {
                                                                        foreach($projects as $pro)
                                                                        {
                                                                            $verifiercount = check_verifier_count($pro->id,$this->user_id);
                                                                            $check_itemowner_count = check_itemowner_count($pro->id,$this->user_id);
                                                                            $check_process_owner_count = check_process_owner_count($pro->id,$this->user_id);
                                                                            $check_manager_count = check_manager_count($pro->id,$this->user_id);
                                                                            
                                                                            $verifiercount = 1;
                                                                            $check_itemowner_count = 1;
                                                                            $check_process_owner_count = 1;
                                                                            $check_manager_count = 1;

                                                                            if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1'))
                                                                            {
                                                                                if($pro->status==0 || $pro->status==3)
                                                                                { ?>
                                                                                    <tr class="text-center">
                                                                                        
                                                                                        <?php
                                                                                        if($this->main_role == '5'){ ?>
                                                                                        <td>
                                                                                            <span>
                                                                                                <?php 
                                                                                                if(($pro->status==1) || ($pro->VerifiedQuantity=='0')){ ?> 
                                                                                                    <input type="checkbox" value="<?php echo $pro->id;?>" name="project_id[]" class="clear_project_id">             
                                                                                                <?php } ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <?php 
                                                                                        }else{
                                                                                            ?>
                                                                                            <?php if($user_role_admin_cnt > 0){ ?>
                                                                                                <td>
                                                                                                    <span>22
                                                                                                        <?php
                                                                                                        $pro->VerifiedQuantity;                              
                                                                                                        if(($pro->status=='1') || ($pro->VerifiedQuantity=='0')){ 
                                                                                                        ?> 
                                                                                                        <input type="checkbox" value="<?php echo $pro->id;?>" name="project_id[]">
                                                                                                        <?php } ?>
                                                                                                    </span>
                                                                                                </td>
                                                                                            <?php 
                                                                                            } 
                                                                                        } ?>
                                                                                        <td>
                                                                                        <?php 
                                                                                            if($this->main_role == '0'){
                                                                                                echo '<a href="javascript:void(0)" onclick="requestfordelete(this,'.$pro->id.')" class="btn btn-danger">Request For Delete</a>';
                                                                                            }
                                                                                        ?>    
                                                                                        <?php echo $pro->project_id;?></td>
                                                                                        <td>
                                                                                            <a href="<?php echo base_url();?>index.php/ProjectDetails/one/<?php echo $pro->id; ?>">
                                                                                                <?php echo $pro->project_name;?>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td><?php echo $pro->CompanyValue;?></td>
                                                                                        <td><?php echo $pro->LocationValue;?></td>
                                                                                        <td><span style="display:none;"><?php echo date_format(date_create($pro->start_date),'Ymd');?></span><?php echo date_format(date_create($pro->start_date),'d/m/Y');?></td>
                                                                                        <td><span style="display:none;"><?php echo date_format(date_create($pro->due_date),'Ymd');?></span><?php echo date_format(date_create($pro->due_date),'d/m/Y');?></td>
                                                                                        <td>
                                                                                            <?php
                                                                                            $date1=date_create(date("Y-m-d"));
                                                                                            $date2=date_create($pro->start_date);
                                                                                            if($date1 >= $date2)
                                                                                            {   
                                                                                                $interval=date_diff(date_create(date("Y-m-d")), date_create($pro->due_date)); 
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $interval=date_diff(date_create($pro->start_date)->modify('-1 days'), date_create($pro->due_date)); 
                                                                                            }
                                                                                            echo $interval->format('%R%a') >= 0 ? $interval->format('%a days').' Remaining':'<span style="color:red;"> '.$interval->format('%a days').'(Overdue)</span>';   
                                                                                            ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if($pro->VerifiedQuantity!=0){ echo round(($pro->VerifiedQuantity/$pro->TotalQuantity)*100,2).' %';}else{ echo "0%";} ?>
                                                                                        </td>       
                                                                                    </tr>
                                                                                <?php 
                                                                                }
                                                                            }
                                                                            }
                                                                            if($i==0)
                                                                            {
                                                                                "<tr><td colspan='6'><strong>Projects are not available.</strong></td></tr>";
                                                                            }                                                                        
                                                                        }else{
                                                                            echo "<tr><td colspan='8'><strong>Projects are not available.</strong></td></tr>";
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--- Open Project End --->


                                            <!--- Closed Project Start --->
                                            <div id="div2" class="tab-pane" aria-labelledby="closed-project">
                                                <div class="card">
                                                    <div class="card-header card-header-primary">
                                                        <h4 class="card-title ">Closed Projects</h4>
                                                        <p class="card-category">Showing all closed projects</p>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead class=" text-center">
                                                                    <th>#</th>
                                                                    <th>Project ID</th>
                                                                    <th>Project Name</th>
                                                                    <th>Date of Project assigned</th>
                                                                    <th>Due Date</th>
                                                                    <th>Completion Date</th>
                                                                    <th>No. of Days Taken</th>
                                                                    <th> <span>Action</span>

                                                                </thead>
                                                                <tbody>
                                                                <?php 
                                                                    $i1=0;
                                                                    if(!empty($projects))
                                                                    {
                                                                    foreach($projects as $pro)
                                                                    {
                                                                         $verifiercount = check_verifier_count($pro->id,$this->user_id);
                                                                        $check_itemowner_count = check_itemowner_count($pro->id,$this->user_id);
                                                                        $check_process_owner_count = check_process_owner_count($pro->id,$this->user_id);
   
                                                                        $check_manager_count = check_manager_count($pro->id,$this->user_id);

                                                                        if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){
                                                                        if($pro->status==1){
                                                                        

                                                                        
                                                                    ?>
                                                                            <tr class="text-center">
                                                                                <td><?php echo ++$i1; ?></td>
                                                                                <td><?php echo $pro->project_id;?></td>
                                                                                <td><a href="<?php echo base_url();?>index.php/dashboard/projectdetail/<?php echo $pro->id; ?>"><?php echo $pro->project_name;?></a></td>
                                                                                <td><span style="display:none;"><?php echo date_format(date_create($pro->start_date),'Ymd');?></span><?php echo date_format(date_create($pro->start_date),'d/m/Y');?></td>
                                                                                <td><span style="display:none;"><?php echo date_format(date_create($pro->due_date),'Ymd');?></span><?php echo date_format(date_create($pro->due_date),'d/m/Y');?></td>
                                                                                <td><span style="display:none;"><?php echo date_format(date_create($pro->finish_datetime),'Ymd');?></span>
                                                                                    <?php echo date_format(date_create($pro->finish_datetime), 'd/m/Y'); 
                                                                                    ?>  
                                                                                </td>
                                                                                <td><?php $originainterval=date_diff(date_create($pro->start_date)->modify("-1 days"), date_create($pro->due_date));
                                                                                $interval=date_diff(date_create($pro->start_date)->modify("-1 days"), date_create($pro->finish_datetime)); 
                                                                                $spent=(int)$originainterval->format('%a')-(int)$interval->format('%a');
                                                                                // printing result in days format 
                                                                                echo $originainterval->format('%a d'); if($spent < 0){echo '<span style="color:red;">['.($spent*-1).'d]</span>';}else{echo '['.$spent.'d]';}  ?></td>
                                                                                <td><a href="<?php echo base_url();?>index.php/dashboard/reopen_project/<?php echo $pro->id;?>" onclick="return confirm('Are you sure to reopen this project!')">Reopen Project</a></td>
                                                                            </tr>
                                                                    <?php 
                                                                           
                                                                            }
                                                                        }
                                                                        }
                                                                        if($i1==0)
                                                                        {
                                                                            echo "<tr><td colspan='6'><strong>Projects are not closed yet.</strong></td></tr>";
                                                                        }
                                                                    }
                                                                    // else
                                                                    // {
                                                                    //     echo "<tr><td colspan='6'><strong>Projects are not closed yet.</strong></td></tr>";
                                                                    // }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--- Closed Project End --->


                                            <!--- Cancelled Project Start --->
                                            <div id="div3" class="tab-pane" aria-labelledby="cancelled-project">
                                                <div class="card">
                                                    <div class="card-header card-header-primary">
                                                        <h4 class="card-title ">Cancelled Projects</h4>
                                                        <p class="card-category">Showing all cancelled projects</p>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead class="text-center">
                                                                    <th>#</th>
                                                                    <th>Project ID</th>
                                                                    <th>Project Name</th>
                                                                    <th>Date of Project assigned</th>
                                                                    <th>Due Date</th>
                                                                    <th>Cancellation Date</th>
                                                                    <th>Reason for Cancellation</th>
                                                                </thead>
                                                                <tbody>
                                                                <?php 
                                                                    $i2=0;
                                                                    if(!empty($projects))
                                                                    {
                                                                    foreach($projects as $pro)
                                                                    {
                                                                        $verifiercount = check_verifier_count($pro->id,$this->user_id);
                                                                        $check_itemowner_count = check_itemowner_count($pro->id,$this->user_id);
                                                                        $check_process_owner_count = check_process_owner_count($pro->id,$this->user_id);

                                                                        $check_manager_count = check_manager_count($pro->id,$this->user_id);

                                                                        if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){
                                                                        if($pro->status==2)
                                                                        {

                                                                        
                                                                    ?>
                                                                    <tr class="text-center">
                                                                        <td><?php echo ++$i2; ?></td>
                                                                        <td><?php echo $pro->project_id;?></td>
                                                                        <td><a href="<?php echo base_url();?>index.php/dashboard/projectdetail/<?php echo $pro->id; ?>"><?php echo $pro->project_name;?></a></td>
                                                                        <td><span style="display:none;"><?php echo date_format(date_create($pro->start_date),'Ymd');?></span><?php echo date_format(date_create($pro->start_date),'d/m/Y');?></td>
                                                                        <td><span style="display:none;"><?php echo date_format(date_create($pro->due_date),'Ymd');?></span><?php echo date_format(date_create($pro->due_date),'d/m/Y');?></td>
                                                                        <td><span style="display:none;"><?php echo date_format(date_create($pro->cancelled_date),'Ymd');?></span><?php  echo date_format(date_create($pro->cancelled_date),'d/m/Y');  ?></td>
                                                                        <td><?php echo $pro->cancel_reason;?></td>
                                                                    </tr>
                                                                    <?php 
                                                                            
                                                                            }
                                                                        }
                                                                            
                                                                        }
                                                                        if($i2==0)
                                                                        {
                                                                            echo "<tr><td colspan='6'><strong>Projects are not cancelled yet.</strong></td></tr>";
                                                                        }
                                                                    }
                                                                    // else{
                                                                    //     echo "<tr><td colspan='6'><strong>Projects are not cancelled yet.</strong></td></tr>";
                                                                    // }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--- Cancelled Project End --->

                                                                    
                                            <!--- Request Cleat Start --->
                                             <div id="div4" class="tab-pane in active" aria-labelledby="request-clear-project">
                                                <div class="card">
                                                    <div class="card-header card-header-primary">
                                                        <h4 class="card-title">Request Clear Projects</h4>
                                                        <p class="card-category">Showing all Request Clear projects</p>
                                                    </div>
                                                    <div class="card-body">
                                                                        
                                                      

                                                        <form id="cleardataform" action="<?php echo base_url();?>index.php/dashboard/deleteproject" method="post">    
                                                        

                                                            <input type="hidden" value="<?php echo $this->main_role;?>" name="user_role" class="clear_project_id">
                                                            
                                                            <div class="table-responsive">

                                                            <?php if($this->main_role == '5'){ ?>
                                                                <button type="submit" class="btn btn-danger">Remove Project</button>
                                                            <?php } ?>
                                                                <table class="table">
                                                                    <thead class=" text-center">
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <?php /* if($this->main_role == '5'){
                                                                                echo '<th>#</th>';
                                                                            }else{
                                                                                if($user_role_admin_cnt > 0){ 
                                                                                    echo '<th>#</th>';
                                                                                }
                                                                            } */ ?>
                                                                            <th><span>Project ID</span></th>
                                                                            <th><span>Project Name</span></th>
                                                                            <th><span>Date of Project assigned</span></th>
                                                                            <th><span>Due Date</span></th>
                                                                            <th><span>Remaining/(Overdue) Day</span></th>
                                                                            <th> <span>Stage of Completion</span></th>
                                                                            <th><span>Action</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php 
                                                                        $i=0;
                                                                        if(!empty($projects))
                                                                        {
                                                                        foreach($projects as $pro)
                                                                        {
                                                                            if($pro->status==4)
                                                                                { ?>
                                                                                    <tr class="text-center">
                                                                                       
                                                                                        <td>
                                                                                            <span>
                                                                                                <?php 
                                                                                                if(($pro->status==4)){
                                                                                                    // if($this->main_role != '0'){ ?> 
                                                                                                    <input type="checkbox" value="<?php echo $pro->id;?>" name="project_id[]" class="clear_project_id">             
                                                                                                <?php } // } ?>
                                                                                            </span>
                                                                                        </td>
                                                                                        <td>
                                                                                           
                                                                                        <?php echo $pro->project_id;?></td>
                                                                                        <td>
                                                                                            <a href="<?php echo base_url();?>index.php/dashboard/projectdetail/<?php echo $pro->id; ?>">
                                                                                                <?php echo $pro->project_name;?>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td><span style="display:none;"><?php echo date_format(date_create($pro->start_date),'Ymd');?></span><?php echo date_format(date_create($pro->start_date),'d/m/Y');?></td>
                                                                                        <td><span style="display:none;"><?php echo date_format(date_create($pro->due_date),'Ymd');?></span><?php echo date_format(date_create($pro->due_date),'d/m/Y');?></td>
                                                                                        <td>
                                                                                            <?php
                                                                                            $date1=date_create(date("Y-m-d"));
                                                                                            $date2=date_create($pro->start_date);
                                                                                            if($date1 >= $date2)
                                                                                            {   
                                                                                                $interval=date_diff(date_create(date("Y-m-d")), date_create($pro->due_date)); 
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $interval=date_diff(date_create($pro->start_date)->modify('-1 days'), date_create($pro->due_date)); 
                                                                                            }
                                                                                            echo $interval->format('%R%a') >= 0 ? $interval->format('%a days').' Remaining':'<span style="color:red;"> '.$interval->format('%a days').'(Overdue)</span>';   
                                                                                            ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php if($pro->VerifiedQuantity!=0){ echo round(($pro->VerifiedQuantity/$pro->TotalQuantity)*100,2).' %';}else{ echo "0%";} ?>
                                                                                        </td>    
                                                                                        <td>
                                                                                            <a href="<?php echo base_url();?>index.php/dashboard/requestdeleteproject/<?php echo $pro->id; ?>" class="btn btn-primary">View</a>
                                                                                        </td>   
                                                                                    </tr>
                                                                                <?php 
                                                                                }
                                                                            // }
                                                                            }
                                                                            if($i==0)
                                                                            {
                                                                                "<tr><td colspan='6'><strong>Projects are not available.</strong></td></tr>";
                                                                            }                                                                        
                                                                        }                                                                    
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--- Open Project End --->



                                        </div>
                                    </div>
                                <!-- </div> -->







</div>
</div>

<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
