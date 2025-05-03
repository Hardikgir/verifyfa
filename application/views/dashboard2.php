
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
        <?php 
        // $user_role_admin_cnt = 1;   //TEMPORARTY
        // if($user_role_admin_cnt > 0){
             ?>
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
    <?php // }
     ?>



    <div class="row">
                                    <div class="col-md-12">
                                        

                                          
                                       
                                            
                                                        
                                                    
                                                    <div class="row">
                                    <div class="col-md-12">
                                        <nav>
                                            <div class="nav nav-tabs nav-fill nav-justified" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" href="#div1" id="tab1" data-toggle="tab" aria-controls="open-project" aria-selected="true"><b>Open Projects</b></a>
                                                <a class="nav-item nav-link" href="#div2" id="tab2" data-toggle="tab" aria-controls="closed-project" aria-selected="true"><b>Closed Projects</b></a>
                                                <a class="nav-item nav-link" href="#div3" data-toggle="tab" aria-controls="cancelled-project" aria-selected="true"><b>Cancelled Projects</b></a>
                                                <a class="nav-item nav-link" href="#div4" data-toggle="tab" aria-controls="request-clear-project" aria-selected="true"><b>Request For Clear Projects</b></a>
                                            </div>
                                        </nav>
                                        
                                        <div class="tab-content pt-5">


                                            <!--- Open Project Start --->
                                            <div id="div1" class="tab-pane in active" aria-labelledby="open-project">
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
                                                                    <thead class=" text-center">
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <?php if($this->main_role == '5'){
                                                                                echo '<th>#</th>';
                                                                            }else{
                                                                                if($user_role_admin_cnt > 0){ 
                                                                                    echo '<th>#</th>';
                                                                                }
                                                                            } ?>
                                                                            <th><span>Project ID</span></th>
                                                                            <th><span>Project Name</span></th>
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
                                                                                        <td>
                                                                                           
                                                                                        </td>
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
                                                                                    </tr>
                                                                                <?php 
                                                                                }
                                                                            }
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
                                             <div id="div4" class="tab-pane in" aria-labelledby="request-clear-project">
                                                <div class="card">
                                                    <div class="card-header card-header-primary">
                                                        <h4 class="card-title">Request Clear Projects</h4>
                                                        <p class="card-category">Showing all Request Clear projects</p>
                                                    </div>
                                                    <div class="card-body">
                                                                        
                                                        <?php /* if($this->main_role == '0'){ ?>
                                                            <form id="cleardataform" action="<?php echo base_url();?>index.php/dashboard/requestdeleteproject" method="post">    
                                                        <?php }else{ ?>
                                                            <form id="cleardataform" action="<?php echo base_url();?>index.php/dashboard/deleteproject" method="post">    
                                                        <?php } */ ?>

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
                                </div>

                                                  

                                                    <div class="row" id="#myCarousel">
                                    <?php if(!empty($projects))
                                    {
                                    ?>
                                    <canvas id="myChart" height="140"></canvas>
                                    <?php
                                    }else
                                    {
                                    ?>
                                    <canvas id="myChart" height="140"></canvas>
                                    <div class="col-md-12"><h3 class="text-center" style="width:100%;">No projects Available</h3></div>
                                    <?php
                                    }?>
                                    
                                </div>
                                                   
                                    





                    


    

        <?php /*
        <div class="row">
            <div class="col-md-12">
                <div id="carousel-example-1" class="carousel no-flex testimonial-carousel slide carousel-fade" data-ride="carousel" data-interval="false">
                    <!--Slides-->
                    <div class="carousel-inner" role="listbox">

                        <!--First slide-->
                        <div class="carousel-item active">
                            <div class="testimonial">
                                
                            </div>
                        </div>
                        
                        <!--Second slide-->
                        <div class="carousel-item ">
                            <div class="testimonial">
                                
                            </div>
                        </div>


                    </div>
                    <ol class="carousel-indicators" id="change">
                        <li data-target="#carousel-example-1" data-slide-to="0" class="active mx-1"></li>
                        <li data-target="#carousel-example-1" data-slide-to="1"></li>
                    </ol>
                </div>
            </div>
        </div> */  ?>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <form action="<?php echo base_url(); ?>index.php/request-for-delete" method="post">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>           -->
          <h4 class="modal-title">Give Reason of Delete Project</h4>
        </div>
        <div class="modal-body">
          <p>Reason for Clear Project</p>
          <input type="hidden" name="hdn_project_id" id="hdn_project_id" value="0">
          <input type="hidden" name="hdn_user_id" id="hdn_user_id" value="<?php echo $this->user_id; ?>">
          
          <textarea class="form-control" name="reason_for_detele"></textarea>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>  
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>


<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
<script>

function requestfordelete(event,project_id){
    // var project_id = $("#event")
    $("#hdn_project_id").val(project_id);
    $('#myModal').modal('show');
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
        // console.log(data);
        $('#company_location').find('option').remove().end().append(data);
      }
    });
}
</script>
<script>
    //form submit handler
    $('#cleardataform').submit(function (e) {
        //check atleat 1 checkbox is checked
 var checkedNum = $('input[name="project_id[]"]:checked').length;
if (checkedNum == 0) {
    alert("Atlease select One Project!");
            //prevent the default form submit if it is not checked
            e.preventDefault();
}
// alert(checkedNum);
       
//         if (!$('.clear_project_id').is(':checked')) {
//             alert("Atlease select One Project!");
//             //prevent the default form submit if it is not checked
//             e.preventDefault();
//         }
        
    })

</script>