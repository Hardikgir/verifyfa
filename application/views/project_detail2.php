<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
$listing=getTagUntag($projects[0]->project_name);
$cat=getTagUntagCategories($projects[0]->project_name);
$allcategories=getCategories($projects[0]->project_name);

?>
<style>
.cmpName{
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
    margin-bottom: 10px;
}
.enbtn{    background-color: green !important;}
.disbtn{    background-color: #407b40 !important;}
</style>
      <div class="content">
        <div class="container-fluid">
          <div class="row">
              <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="card">       
                    
              <div class="card-header card-header-primary">
                <div class="container">
                <div class="row mb-4">
                        <div class="col-md-12 text-center">
                           <h3> 
                             <b>Company Name :</b><?php echo get_CompanyName($projects[0]->company_id);?> 
                             <b> (Location :</b><?php echo $projects[0]->project_location;?>)
                           </h3>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-md-4">
                            <td class="text-center"> <a href="<?php echo base_url();?>index.php/dashboard" class="btn btn-round pull-left">Back</a>
                            </td>
                        </div>

                       
                            <?php if($projects[0]->status == '3'){ ?>
                          <div class="col-md-4 text-center">
                            <td class="text-center"> <a href="<?php echo base_url();?>index.php/dashboard/reopen_verification/<?php echo $projects[0]->id;?>" class="btn btn-round pull-left">Reopen Verification</a>
                            </td>
                            </div>
                            <?php } ?>
                        
                        
                            <?php 
                            $user_id=$this->user_id;
                            $entity_code=$this->admin_registered_entity_code;
                            //manager role Menu//
                            $user_role_manager_cnt=get_user_role_cnt_managers($user_id,$entity_code);
                            if($user_role_manager_cnt > 0){ 
                            ?>
                            <div class="col-md-4 text-center">
                            <td class="text-center"> <a href="#" class="btn btn-round btn-fill btn-default finishproject enbtn <?php echo $projects[0]->status!=3?'disabled disbtn':'';?> ">Finish</a>
                            </td>
                            </div>
                            <?php } ?>
                       
                        <div class="col-md-4">
                            <td class="text-center"> <a href="<?php echo base_url();?>index.php/dashboard/projectprint/<?php echo $projects[0]->id;?>" class="btn btn-round btn-fill btn-info pull-right">Export</a>
                            </td>
                        </div>
                        
                    </div>
                </div>
                
            </div>
                <div class="card-body">
                <form>
                    <?php 
                        foreach($projects as $pro)
                        {
                    ?>
                    <div class="row inSummary">
                        <div class="col-md-12">
                            <div class="col-md-4" style="float: left;">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Project ID</label>
                                    <input type="text" value="<?php echo $pro->project_id;?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-4" style="float: left;">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Project Name</label>
                                    <input type="text" value="<?php echo $pro->project_name;?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-4" style="float: left;">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Project Status: </label>
                                    <input style="color:#5ca1e2;font-weight:bold;" type="text" value="<?php echo $pro->status==0 ? "In Progress" : ($pro->status==1 ?"Completed":($pro->status==3 ? "Verification Completed by ".get_UserName($pro->verification_closed_by):"Cancelled"));?>" class="form-control" disabled>
                                   
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row inDetail">
                        <div class="col-md-8">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Company Name</label>
                                    <input type="hidden" value="<?php echo get_CompanyName($pro->company_id);?>" class="form-control" disabled>
                                    <p class="cmpName"><?php echo get_CompanyName($pro->company_id);?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Unit Location</label>
                                    <input type="text" value="<?php echo $pro->project_location;?>" class="form-control" disabled>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Project ID</label>
                                    <input type="text" value="<?php echo $pro->project_id;?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Project Name</label>
                                    <input type="text" value="<?php echo $pro->project_name;?>" class="form-control" disabled>
                                </div>
                            </div>
                        
                    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Date of Project Assigned</label>
                                    <input type="text" value="<?php echo date_format(date_create($pro->start_date),'d/m/Y');?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Due Date</label>
                                    <input type="text" value="<?php echo date_format(date_create($pro->due_date),'d/m/Y');?>" class="form-control" disabled>
                                </div>
                            </div>
                   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php $verifiercnt=  explode(',',$pro->project_verifier);?>
                                    <label class="bmd-label-floating">No.of Resources assigned</label>
                                    <input type="text" value="<?php echo count($verifiercnt); ?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Assigned by</label>
                                    <input type="text" value="<?php echo get_UserName($pro->assigned_by); ?>" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Project Status</label>
                                    <h4 style="color:#5ca1e2;font-weight:bold;"><?php echo $pro->status==0 ? "In Progress" : ($pro->status==1 ?"Completed":($pro->status==3 ? "Verification Completed by ".get_UserName($pro->verification_closed_by):"Cancelled"));?></h4>
                                    
                                </div>
                            </div>
                            <div class="col-md-12">
                                 <?php
                                if($pro->status==1)
                                { 
                                ?>
                                <div class="form-group circle-green-box">
                                    <h4><?php  $activedate1=date_create($pro->start_date)->modify('-1 days');
                                        $activedate2=date_create($pro->due_date);
                                        $activediff=date_diff($activedate1,$activedate2);
                                        // if($activedate1 >= $activedate2)
                                            echo $activediff->format("%a");
                                        // else
                                        //     echo 'NA';
                                       ?>
                                    </h4>
                                    <label class="bmd-label-floating">Active Days</label>
                                    
                                </div>
                                <div class="form-group circle-blue-box">
                                   
                                    <h4><?php  $date1=date_create(date("Y-m-d"));
                                        $date2=date_create($pro->start_date);
                                        if($date1 >= $date2)
                                        {   
                                            $date3=date_create($pro->finish_datetime);
                                            if($date1 > $date3)
                                            {
                                                echo 0;
                                            }
                                            else
                                            {
                                                $diff=date_diff($date1,$date3);
                                                echo $diff->format("%a");
                                            }
                                            
                                         }
                                        else
                                        {
                                            $date3=date_create($pro->due_date);
                                            $diff=date_diff($date2->modify('-1 days'),$date3);
                                            echo $diff->format("%a");
                                             
                                        }
                                        ?>
                                    </h4>
                                    <label class="bmd-label-floating">Remaining Days</label>
                                    

                                </div>
                                <?php
                                }
                                else
                                {
                                ?>
                                <div class="form-group circle-green-box">
                                    <h4><?php  $activedate1=date_create(date("Y-m-d"))->modify('+1 days');
                                        $activedate2=date_create($pro->start_date);
                                        $activediff=date_diff($activedate1,$activedate2);
                                        if($activedate1 >= $activedate2)
                                            echo $activediff->format("%a");
                                        else
                                            echo 'NA';
                                       ?>
                                    </h4>
                                    <label class="bmd-label-floating">Active Days</label>
                                    
                                </div>
                                <div class="form-group circle-blue-box">
                                   
                                    <h4><?php  $date1=date_create(date("Y-m-d"));
                                        $date2=date_create($pro->start_date);
                                        if($date1 >= $date2)
                                        {   
                                            $date3=date_create($pro->due_date);
                                            if($date1 > $date3)
                                            {
                                                $diff=date_diff($date1,$date3);
                                                echo '-'.$diff->format("%a");
                                            }
                                            else
                                            {
                                                $diff=date_diff($date1,$date3);
                                                echo $diff->format("%a");
                                            }
                                            
                                         }
                                        else
                                        {
                                            $date3=date_create($pro->due_date);
                                            $diff=date_diff($date2->modify('-1 days'),$date3);
                                            echo $diff->format("%a");
                                             
                                        }
                                        ?>
                                    </h4>
                                    <label class="bmd-label-floating">Remaining Days</label>
                                    

                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <buttton class="btn btn-warning btn-round showDetail" id="showDetail">Show Detail</buttton>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php
                        }
                        ?>
                </form>
                <div id="carousel-example-1" class="carousel no-flex testimonial-carousel slide carousel-fade" data-ride="carousel" data-interval="false">
                    <!--Slides-->
                    <div class="carousel-inner" role="listbox">
                        <!--First slide-->
                        <div class="carousel-item active">
                            <div class="testimonial">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <h3 class="boder-bottom">Overall Project Status</h3>
                                        <div class="row mt-5">

                                            <div class="col-md-12">
                                                <div  style="background: #fff;height: 150px;padding: 15px;">
                                                    <h2 class="text-center">Overall Project Status</h2>
                                                </div>
                                                <div id="OverallProjectStatusChart" style="height: 370px; width: 100%;"></div>
                                            </div>

                                            <div class="col-md-6">
                                                <canvas id="chDonut3"></canvas>
                                            </div>
                                            <div class="col-md-6">
                                                <canvas id="doughnutChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>
                            </div>
                        </div>
                        <!--Second slide-->
                        <div class="carousel-item">
                            <div class="testimonial">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <h3 class="boder-bottom">Line Item (Li) Breakup</h3>
                                        
                                        <div id="tab"  class="project-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <nav>
                                                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                                            <a class="nav-item nav-link active" id="nav-chart-tab" data-toggle="tab" href="#nav-chart" role="tab" aria-controls="nav-chart" aria-selected="true"><i class="nav-item fas fa-chart-pie pie_icon text-center"></i></a>
                                                            <a class="nav-item nav-link" id="nav-table-tab" data-toggle="tab" href="#nav-table" role="tab" aria-controls="nav-table" aria-selected="false"><i class="nav-item fas fa-table table_icon text-center"></i></a>
                                                        </div>
                                                    </nav>
                                                    <div class="tab-content"  id="nav-tabContent">
                                                        <div class="tab-pane fade show active" id="nav-chart" role="tabpanel" aria-labelledby="nav-chart-tab">
                                                            <div class="row my-5">

                                                                <div class="col-md-12">
                                                                    <div  style="background: #fff;height: 150px;padding: 15px;">
                                                                        <h2 class="text-center">Line Item (Li) Breakup</h2>
                                                                    </div>
                                                                    <div id="LineItemBreakupChart" style="height: 370px; width: 100%;"></div>
                                                                </div>

                                                                <?php 
                                                                
                                                                if(count($cat['tagged']) > 0 && $projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'){
                                                                ?>
                                                                <div class="col-md-6">
                                                                    <canvas id="taggedpieChart"></canvas>
                                                                    <h5 class=" text-center">Tagged</h5>
                                                                </div>
                                                                <?php
                                                                }
                                                                if(count($cat['untagged'])>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD' )){
                                                                ?>
                                                                <div class="col-md-6">
                                                                    <canvas id="untaggedpieChart"></canvas>
                                                                    <h5 class=" text-center">Non-Tagged </h5>
                                                                </div>
                                                                <?php
                                                                }
                                                                if(count($cat['unspecified'])>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD' )){
                                                                ?>	
                                                                <div class="col-md-6">
                                                                    <canvas id="unspecifiedpieChart"></canvas>
                                                                    <h5 class=" text-center">Unspecified</h5>
                                                                </div>	
                                                                <?php 
                                                                }
                                                                ?>	
                                                                <div class="col-md-6">
                                                                    <canvas id="libarchart"></canvas>
                                                                    <h5 class=" text-center">Overall</h5>
                                                                </div>	
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade " id="nav-table" role="tabpanel" aria-labelledby="nav-table-tab">
                                                            <br/>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-sm small" style="border:1px solid rgba(0, 0, 0, 0.06)">
                                                                    <thead class=" text-center thead-dark">
                                                                        <tr class=" text-center">
                                                                            <th>Cat#</th>
                                                                            <th>Amount (in lacs)</th>
                                                                            <?php
                                                                            if($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <th>Tagged</th>
                                                                            <?php
                                                                            }
                                                                            if(count($cat['unspecified'])>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD' ))
                                                                            {
                                                                            ?>
                                                                            <th>Non-Tagged <?php echo count($cat['untagged']);?></th>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <th>Unspecified</th>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <th>Overall</th>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <th>Verification Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php 
                                                                        $ttv=0;
                                                                        $ttt=0;
                                                                        $tntv=0;
                                                                        $tntt=0;
                                                                        $tutv=0;
                                                                        $tutt=0;
                                                                        $tamt=0;
                                                                        foreach($allcategories['categories'] as $alcat)
                                                                        {
                                                                            
                                                                           $overallverified=0;
                                                                            $overalltotal=0;
                                                                        ?>
                                                                        <tr class=" text-center">
                                                                            <td><?php echo $alcat->item_category;?></td>
                                                                            <td><?php $tamt=$tamt+$alcat->amount; echo getmoney_format(round(($alcat->amount/100000),2));?></td>
                                                                            <?php
                                                                            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            $overall=0;
                                                                            
                                                                            $process=0;
                                                                            if(count($cat['tagged'])>0)
                                                                            {
                                                                            $tg=0;
                                                                            foreach($cat['tagged'] as $ct)
                                                                            { 
                                                                                if($ct['category']==$alcat->item_category)
                                                                                {
                                                                                    $overall=$overall+$ct['percentage'];
                                                                                    $overallverified=$overallverified+$ct['verified'];
                                                                                    $overalltotal=$overalltotal+$ct['total'];
                                                                                    $ttv=$ttv+$ct['verified'];
                                                                                    $ttt=$ttt+$ct['total'];
                                                                                    $ct['percentage'] ==100? $process++ : $process;
                                                                            ?>
                                                                            <td><?php echo $ct['percentage'].'% <br/>'.$ct['verified'].' of '.$ct['total'].' Li';?></td>
                                                                            <?php
                                                                            $tg++;
                                                                                }
                                                                            }
                                                                            if($tg==0)
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Li</td>
                                                                            <?php    
                                                                            }
                                                                            }
                                                                            else
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Li</td>
                                                                            <?php
                                                                            } 
                                                                            }
                                                                            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            $overall=0;
                                                                            // $overallverified=0;
                                                                            // $overalltotal=0;
                                                                            $process=0;
                                                                            if(count($cat['untagged'])>0)
                                                                            {
                                                                            $ut=0;
                                                                            foreach($cat['untagged'] as $ct)
                                                                            { 
                                                                                if($ct['category']==$alcat->item_category)
                                                                                {
                                                                                    $overall=$overall+$ct['percentage'];
                                                                                    $overallverified=$overallverified+$ct['verified'];
                                                                                    $overalltotal=$overalltotal+$ct['total'];
                                                                                    $tntv=$tntv+$ct['verified'];
                                                                                    $tntt=$tntt+$ct['total'];
                                                                                    $ct['percentage'] ==100? $process++ : $process;
                                                                            ?>
                                                                            <td><?php echo $ct['percentage'].'% <br/>'.$ct['verified'].' of '.$ct['total'].' Li';?></td>
                                                                            <?php
                                                                                $ut++;
                                                                                }
                                                                                
                                                                            }
                                                                            if($ut==0)
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Li</td>
                                                                            <?php    
                                                                            }
                                                                            }
                                                                            else
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Li</td>
                                                                            <?php    
                                                                            }
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            $overall=0;
                                                                            // $overallverified=0;
                                                                            // $overalltotal=0;
                                                                            $process=0;
                                                                            if(count($cat['unspecified'])>0)
                                                                            {
                                                                                $us=0;
                                                                                foreach($cat['unspecified'] as $ct)
                                                                                { 
                                                                                    if($ct['category']==$alcat->item_category)
                                                                                    {
                                                                                        $overall=$overall+$ct['percentage'];
                                                                                        $overallverified=$overallverified+$ct['verified'];
                                                                                        $overalltotal=$overalltotal+$ct['total'];
                                                                                        $tutv=$tutv+$ct['verified'];
                                                                                    $tutt=$tutt+$ct['total'];
                                                                                        $ct['percentage'] ==100? $process++ : $process;
                                                                                ?>
                                                                                <td><?php echo $ct['percentage'].'% <br/>'.$ct['verified'].' of '.$ct['total'].' Li';?></td>
                                                                                <?php
                                                                                    $us++;
                                                                                    }
                                                                                    
                                                                                }
                                                                                if($us==0)
                                                                                {
                                                                                ?>
                                                                                <td>0% <br/> 0 of 0 Li</td>
                                                                                <?php    
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Li</td>
                                                                            <?php    
                                                                            }
                                                                            }
                                                                            
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <td><?php echo round(($overallverified/$overalltotal)*100,2).' %<br>'.$overallverified.' of '.$overalltotal.' Li';?></td>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <td><?php echo$projects[0]->status<3  ? 'In Process':'Completed';?></td>
                                                                            
                                                                            
                                                                        </tr>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <tr class=" text-center">
                                                                            <td><strong>TOTAL</strong></td>
                                                                            <td><strong><?php echo getmoney_format(round(($tamt/100000),2)); ?></strong></td>
                                                                            <?php
                                                                            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $ttv.' of '.$ttt.' Li';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $tntv.' of '.$tntt.' Li';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD' ))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $tutv.' of '.$tutt.' Li';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo ($ttv+$tntv+$tutv).' of '.($ttt+$tntt+$tutt).' Li';?></strong></td>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr class=" text-center">
                                                                            <td><strong>%</strong></td>
                                                                            <td></td>
                                                                            <?php
                                                                            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $ttt>0?round(($ttv/$ttt)*100,2).' %':'0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $tntt>0?round(($tntv/$tntt)*100,2).' %': '0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $tutt>0?round(($tutv/$tutt)*100,2).' %': '0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo ($ttt+$tntt+$tutt) > 0 ? round((($ttv+$tntv+$tutv)/($ttt+$tntt+$tutt))*100,2).' %': '0 %';?></strong></td>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>
                                
                            </div>
                        </div>
                        <!--Third slide-->
                        <div class="carousel-item">
                            <div class="testimonial">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <h3 class="boder-bottom">Amount wise Breakup</h3>
                                        
                                        <div class="project-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <nav>
                                                        <div class="nav nav-tabs nav-fill" id="nav-tab2" role="tablist">
                                                            <a class="nav-item nav-link active" id="nav-chart-tab2" data-toggle="tab" href="#nav-chart2" role="tab" aria-controls="nav-chart2" aria-selected="true"><i class="nav-item fas fa-chart-pie pie_icon text-center"></i></a>
                                                            <a class="nav-item nav-link" id="nav-table-tab2" data-toggle="tab" href="#nav-table2" role="tab" aria-controls="nav-table2" aria-selected="false"><i class="nav-item fas fa-table table_icon text-center"></i></a>
                                                        </div>
                                                    </nav>
                                                    <div class="tab-content"  id="nav-tabContent2">
                                                        <div class="tab-pane fade show active" id="nav-chart2" role="tabpanel" aria-labelledby="nav-chart-tab2">
                                                            <div class="row my-5">

                                                            <div class="col-md-12">
                                                                    <div  style="background: #fff;height: 150px;padding: 15px;">
                                                                        <h2 class="text-center">Amount wise Breakup</h2>
                                                                    </div>
                                                                    <div id="AmountwiseBreakupChart" style="height: 370px; width: 100%;"></div>
                                                                </div>

                                                               
                                                                        <?php 
                                                                        if(count($cat['tagged']) > 0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
                                                                        ?>
                                                                        <div class="col-md-6">
                                                                            <canvas id="amounttaggedpieChart"></canvas>
                                                                            <h5 class=" text-center">Tagged</h5>
                                                                        </div>
                                                                        <?php
                                                                        }
                                                                        if(count($cat['untagged'])>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD')){
                                                                        ?>
                                                                        <div class="col-md-6">
                                                                            <canvas id="amountuntaggedpieChart"></canvas>
                                                                            <h5 class=" text-center">Non-Tagged</h5>
                                                                        </div>
                                                                        <?php
                                                                        }
                                                                        if(count($cat['unspecified'])>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
                                                                        ?>	
                                                                        <div class="col-md-6">
                                                                            <canvas id="amountunspecifiedpieChart"></canvas>
                                                                            <h5 class=" text-center">Unspecified</h5>
                                                                        </div>	
                                                                        <?php 
                                                                        }
                                                                        ?>
                                                                        <div class="col-md-6">
                                                                            <canvas id="amountbarchart"></canvas>
                                                                            <h5 class=" text-center">Overall</h5>
                                                                        </div>	
                                                                    		
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="nav-table2" role="tabpanel" aria-labelledby="nav-table-tab2">
                                                        <br/>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-sm small" style="border:1px solid rgba(0, 0, 0, 0.06)">
                                                                    <thead class=" text-center thead-dark">
                                                                        <tr class=" text-center">
                                                                            <th>Cat#</th>
                                                                            <th>Line Items (Li)</th>
                                                                            <?php
                                                                            if($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <th>Tagged</th>
                                                                            <?php
                                                                            }
                                                                            if(count($cat['unspecified'])>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD' ))
                                                                            {
                                                                            ?>
                                                                            <th>Non-Tagged</th>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <th>Unspecified</th>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <th>Overall</th>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <th>Verification Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php 
                                                                        $ttv=0;
                                                                        $ttt=0;
                                                                        $tntv=0;
                                                                        $tntt=0;
                                                                        $tutv=0;
                                                                        $tutt=0;
                                                                        $totalCount=0;                                                                        

                                                                        foreach($allcategories['categories'] as $alcat)
                                                                        {
                                                                            $count=0;
                                                                        ?>
                                                                        <tr class=" text-center">
                                                                            <td><?php echo $alcat->item_category;?></td>
                                                                            <?php
                                                                            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                                if(count($cat['tagged'])>0)
                                                                                {
                                                                                    $tg=0;
                                                                                    foreach($cat['tagged'] as $ct)
                                                                                    { 
                                                                                        if($ct['category']==$alcat->item_category)
                                                                                        {
                                                                                            $count=$count+$ct['verified'];
                                                                                            $totalCount=$totalCount+$ct['verified'];
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                                if(count($cat['untagged'])>0)
                                                                                {
                                                                                    $ut=0;
                                                                                    foreach($cat['untagged'] as $ct)
                                                                                    { 
                                                                                        if($ct['category']==$alcat->item_category)
                                                                                        {
                                                                                            $count=$count+$ct['verified'];
                                                                                            $totalCount=$totalCount+$ct['verified'];
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                                if(count($cat['unspecified'])>0)
                                                                                {
                                                                                    $us=0;
                                                                                    foreach($cat['unspecified'] as $ct)
                                                                                    { 
                                                                                        if($ct['category']==$alcat->item_category)
                                                                                        {
                                                                                            $count=$count+$ct['verified'];
                                                                                            $totalCount=$totalCount+$ct['verified'];
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                            ?>
                                                                            <td><?php echo $count.' of '.$alcat->catitems.' Li';?></td>
                                                                            <?php
                                                                            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            $overall=0;
                                                                            $overallverified=0;
                                                                            $overalltotal=0;
                                                                            $process=0;
                                                                            if(count($cat['tagged'])>0)
                                                                            {
                                                                            $tg=0;
                                                                            foreach($cat['tagged'] as $ct)
                                                                            { 
                                                                                if($ct['category']==$alcat->item_category)
                                                                                {
                                                                                    $overall=$overall+$ct['amountpercentage'];
                                                                                    $overallverified=$overallverified+$ct['verifiedamount'];
                                                                                    $overalltotal=$overalltotal+$ct['totalamount'];
                                                                                    $ttv=$ttv+$ct['verifiedamount'];
                                                                                    $ttt=$ttt+$ct['totalamount'];
                                                                                    $ct['amountpercentage'] ==100? $process++ : $process;
                                                                            ?>
                                                                            
                                                                            <td><?php echo $ct['amountpercentage'].'% <br/>'.getmoney_format(round(($ct['verifiedamount']/100000),2)).' of '.getmoney_format(round(($ct['totalamount']/100000),2)).' Lacs';?></td>
                                                                            <?php
                                                                            $tg++;
                                                                                }
                                                                            }
                                                                            if($tg==0)
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Lacs</td>
                                                                            <?php    
                                                                            }
                                                                            }
                                                                            else
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Lacs</td>
                                                                            <?php
                                                                            } 
                                                                            }
                                                                            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            if(count($cat['untagged'])>0)
                                                                            {
                                                                            $ut=0;
                                                                            foreach($cat['untagged'] as $ct)
                                                                            { 
                                                                                if($ct['category']==$alcat->item_category)
                                                                                {
                                                                                    $overall=$overall+$ct['amountpercentage'];
                                                                                    $overallverified=$overallverified+$ct['verifiedamount'];
                                                                                    $overalltotal=$overalltotal+$ct['totalamount'];
                                                                                    $tntv=$tntv+$ct['verifiedamount'];
                                                                                    $tntt=$tntt+$ct['totalamount'];
                                                                                    $ct['amountpercentage'] ==100? $process++ : $process;
                                                                            ?>
                                                                            <td><?php echo $ct['amountpercentage'].'% <br/>'.getmoney_format(round(($ct['verifiedamount']/100000),2)).' of '.getmoney_format(round(($ct['totalamount']/100000),2)).' Lacs';?></td>
                                                                            <?php
                                                                                $ut++;
                                                                                }
                                                                                
                                                                            }
                                                                            if($ut==0)
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Lacs</td>
                                                                            <?php    
                                                                            }
                                                                            }
                                                                            else
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Lacs</td>
                                                                            <?php    
                                                                            }
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            if(count($cat['unspecified'])>0)
                                                                            {
                                                                                $us=0;
                                                                                foreach($cat['unspecified'] as $ct)
                                                                                { 
                                                                                    if($ct['category']==$alcat->item_category)
                                                                                    {
                                                                                        $overall=$overall+$ct['amountpercentage'];
                                                                                        $overallverified=$overallverified+$ct['verifiedamount'];
                                                                                        $overalltotal=$overalltotal+$ct['totalamount'];
                                                                                        $tutv=$tutv+$ct['verifiedamount'];
                                                                                    $tutt=$tutt+$ct['totalamount'];
                                                                                        $ct['amountpercentage'] ==100? $process++ : $process;
                                                                                ?>
                                                                                <td><?php echo $ct['amountpercentage'].'% <br/>'.getmoney_format(round(($ct['verifiedamount']/100000),2)).' of '.getmoney_format(round(($ct['totalamount']/100000),2)).' Lacs';?></td>
                                                                                <?php
                                                                                    $us++;
                                                                                    }
                                                                                    
                                                                                }
                                                                                if($us==0)
                                                                                {
                                                                                ?>
                                                                                <td>0% <br/> 0 of 0 Lacs</td>
                                                                                <?php    
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                            ?>
                                                                            <td>0% <br/> 0 of 0 Lacs </td>
                                                                            <?php    
                                                                            }
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <td><?php echo getmoney_format(round((($overallverified/$overalltotal)*100),2)).'% <br>'.getmoney_format(round(($overallverified/100000),2)).' of '.getmoney_format(round(($overalltotal/100000),2)).' Lacs'?></td>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <td><?php echo$projects[0]->status<3  ? 'In Process':'Completed';?></td>
                                                                            
                                                                            
                                                                        </tr>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <tr class=" text-center">
                                                                            <td><strong>TOTAL</strong></td>
                                                                            <td><strong><?php echo $totalCount.' of '.$cat['totalitems'].' Li';?></strong></td>
                                                                            <?php
                                                                            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo getmoney_format(number_format(($ttv/100000),2,'.', '')).' of '.getmoney_format(number_format(($ttt/100000),2,'.', '')).' Lacs';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo getmoney_format(number_format(($tntv/100000),2,'.', '')
                                                                            ).' of '.getmoney_format(number_format(($tntt/100000),2,'.', '')).' Lacs';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD' ))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo getmoney_format(number_format(($tutv/100000),2,'.', '')).' of '.getmoney_format(number_format(($tutt/100000),2,'.', '')).' Lacs';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo getmoney_format(number_format((($ttv+$tntv+$tutv)/100000),2,'.', '')).' of '.getmoney_format(number_format((($ttt+$tntt+$tutt)/100000),2,'.', '')).' Lacs';?></strong></td>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr class=" text-center">
                                                                            <td><strong>%</strong></td>
                                                                            <td></td>
                                                                            <?php
                                                                            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $ttt>0?round(($ttv/$ttt)*100,2).' %':'0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $tntt>0?round(($tntv/$tntt)*100,2).' %': '0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $tutt>0?round(($tutv/$tutt)*100,2).' %': '0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo ($ttt+$tntt+$tutt) > 0 ? round((($ttv+$tntv+$tutv)/($ttt+$tntt+$tutt))*100,2).' %': '0 %';?></strong></td>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>
                                
                            </div>
                        </div>
                        <!--Fourth slide-->
                        <div class="carousel-item">
                            <div class="testimonial">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <h3 class="boder-bottom">Resource wise Utilization</h3>
                                        
                                        <div class="project-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <nav>
                                                        <div class="nav nav-tabs nav-fill" id="nav-tab3" role="tablist">
                                                            <a class="nav-item nav-link active" id="nav-chart-tab3" data-toggle="tab" href="#nav-chart3" role="tab" aria-controls="nav-chart3" aria-selected="true"><i class="nav-item fas fa-chart-pie pie_icon text-center"></i></a>
                                                            <a class="nav-item nav-link" id="nav-table-tab3" data-toggle="tab" href="#nav-table3" role="tab" aria-controls="nav-table3" aria-selected="false"><i class="nav-item fas fa-table table_icon text-center"></i></a>
                                                        </div>
                                                    </nav>
                                                    <div class="tab-content"  id="nav-tabContent3">
                                                        <div class="tab-pane fade show active" id="nav-chart3" role="tabpanel" aria-labelledby="nav-chart-tab3">

                                                            <div class="col-md-12">
                                                                <div  style="background: #fff;height: 150px;padding: 15px;">
                                                                    <h2 class="text-center">Line Item (Li) Breakup</h2>
                                                                </div>
                                                                <div id="ResourcewiseUtilizationChart" style="height: 370px; width: 100%;"></div>
                                                            </div>


                                                            <div class="row my-5">
                                                                <?php 
                                                                if($listing['ytotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
                                                                ?>
                                                                    <div class="col-md-6">
                                                                        <canvas id="resourcetaggedpieChart"></canvas>
                                                                        <h5 class=" text-center">Tagged</h5>
                                                                    </div>
                                                                <?php
                                                                }
                                                                if($listing['ntotal']>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD')){
                                                                ?>
                                                                    <div class="col-md-6">
                                                                        <canvas id="resourceuntaggedpieChart"></canvas>
                                                                        <h5 class=" text-center">Non-Tagged</h5>
                                                                    </div>
                                                                <?php
                                                                }
                                                                if($listing['natotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
                                                                ?>	
                                                                    <div class="col-md-6">
                                                                        <canvas id="resourceunspecifiedpieChart"></canvas>
                                                                        <h5 class=" text-center">Unspecified</h5>
                                                                    </div>	
                                                                <?php 
                                                                }
                                                                ?>		
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="nav-table3" role="tabpanel" aria-labelledby="nav-table-tab3">
                                                        <br/>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-sm small" style="border:1px solid rgba(0, 0, 0, 0.06)">
                                                                    <thead class=" text-center thead-dark">
                                                                        <tr class=" text-center">
                                                                            <th>#</th>
                                                                            <th>Resource Name</th>
                                                                            <?php
                                                                            if($listing['ytotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD' ))
                                                                            {
                                                                            ?>
                                                                            <th>Tagged</th>
                                                                            <?php
                                                                            }
                                                                            if($listing['ntotal']>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <th>Non-Tagged</th>
                                                                            <?php
                                                                            }
                                                                            if($listing['natotal']>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <th>Unspecified</th>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <th>Overall</th>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <th>Verification Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php 
                                                                        $ttv=0;
                                                                        $ttt=0;
                                                                        $tntv=0;
                                                                        $tntt=0;
                                                                        $tutv=0;
                                                                        $tutt=0;
                                                                        $i=0;
                                                                        foreach($listing['projectverifiers'] as $res)
                                                                        {
                                                                            $ttv=$ttv+$res->usertagged;
                                                                            $tntv=$tntv+$res->useruntagged;
                                                                            $tutv=$tutv+$res->userunspecified;

                                                                        ?>
                                                                        <tr class=" text-center">
                                                                            <td><?php echo ++$i;?></td>
                                                                            <td><?php echo get_UserName($res->user_id);?></td>
                                                                            <?php
                                                                            if($listing['ytotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><?php echo round(($res->usertagged/$listing['ytotal'])*100,2).' %<br>'.$res->usertagged.' of '.$listing['ytotal']." Li";?></td> 
                                                                            <?php
                                                                            }
                                                                            if($listing['ntotal']>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><?php echo round(($res->useruntagged/$listing['ntotal'])*100,2).' %<br>'.$res->useruntagged.' of '.$listing['ntotal']." Li";?></td> 
                                                                            <?php
                                                                            }
                                                                            if($listing['natotal']>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><?php echo round(($res->userunspecified/$listing['natotal'])*100,2).' %<br>'.$res->userunspecified.' of '.$listing['natotal']." Li";?></td> 
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD')
                                                                            {
                                                                            ?>
                                                                            <td><?php $totuser=($res->usertagged+$res->useruntagged+$res->userunspecified);
                                                                            $totalall=$listing['natotal']+$listing['ytotal']+$listing['ntotal'];
                                                                            echo round(($totuser/$totalall)*100,2).' %<br>'.$totuser.' of '.$totalall." Li";?></td> 
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <td><?php echo$projects[0]->status<3  ? 'In Process':'Completed';?></td>
                                                                        </tr>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <tr class=" text-center">
                                                                            <td><strong>TOTAL</strong></td>
                                                                            <td></td>
                                                                            <?php
                                                                            if($listing['ytotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $ttv.' of '.$listing['ytotal'].' Li';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($listing['ntotal'] && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $tntv.' of '.$listing['ntotal'].' Li';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($listing['natotal'] && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD' ))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $tutv.' of '.$listing['natotal'].' Li';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo ($ttv+$tntv+$tutv).' of '.($listing['ytotal']+$listing['natotal']+$listing['ntotal']).' Li';?></strong></td>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <td></td>
                                                                        </tr>
                                                                        <tr class=" text-center">
                                                                            <td><strong>%</strong></td>
                                                                            <td></td>
                                                                            <?php
                                                                            if($listing['ytotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $listing['ytotal']>0?round(($ttv/$listing['ytotal'])*100,2).' %':'0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($listing['ntotal']>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $listing['ntotal']>0?round(($tntv/$listing['ntotal'])*100,2).' %': '0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($listing['natotal']>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo $listing['natotal']>0?round(($tutv/$listing['natotal'])*100,2).' %': '0 %';?></strong></td>
                                                                            <?php
                                                                            }
                                                                            if($projects[0]->project_type=='CD' )
                                                                            {
                                                                            ?>
                                                                            <td><strong><?php echo ($listing['ytotal']+$listing['natotal']+$listing['ntotal']) > 0 ? round((($ttv+$tntv+$tutv)/($listing['ytotal']+$listing['natotal']+$listing['ntotal']))*100,2).' %': '0 %';?></strong></td>
                                                                            <?php 
                                                                            }
                                                                            ?>
                                                                            <td></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <ol class="carousel-indicators" id="change">
                        <li data-target="#carousel-example-1" data-slide-to="0" class="active mx-1"></li>
                        <li data-target="#carousel-example-1" data-slide-to="1"></li>
                        <li data-target="#carousel-example-1" class="mx-1" data-slide-to="2"></li>			
                        <li data-target="#carousel-example-1" data-slide-to="3"></li>
                    </ol>
                </div>
            </div>
            </div>
        </div>                               
        </div>
    </div>
    </div>   

    
</div>
</div>
<!-- MODEL BOX-1 -->
<div class="modal fade " id="modalfinishconfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">FinalConfirmation before closing Verification Cycle </h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body mx-3">
                <form id="confirmationmodalform">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Company Name:</label>
                                    <input disabled class="form-control" name="company_name" value="<?php echo get_CompanyName($projects[0]->company_id);?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Unit Location:</label>
                                    <input disabled class="form-control" name="unit_location" value="<?php echo $projects[0]->project_location;?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Project ID:</label>
                                    <input disabled class="form-control" name="project_id" value="<?php echo $projects[0]->project_id;?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Project Name:</label>
                                    <input disabled class="form-control" name="project_name" value="<?php echo $projects[0]->project_name;?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
            
                
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <span class="text-center" class="pop_up_label">Kindly select the closing stage of your verification cycle !!</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="radio pop_up_label">
                                <label style="color:#000000;">
                                    <input type="radio" id="finishverificationproject" name="actionverificationproject" value="finishproject">Finish Verification Project</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="radio pop_up_label">
                                <label  style="color:#000000;">
                                    <input type="radio" id="abortverificationproject" name="actionverificationproject" value="abortproject">Abort Verification Project</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn pull-right" data-dismiss="modal">Cancel<i class="fas fa-times ml-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-footer d-flex justify-content-center"> <a href="javascript:void(0)" class="btn pull-right" id="confirmVerificationProceed">Proceed<i class="fas fa-check ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div> 
<!-- MODEL BOX-2 -->
<div class="modal fade " id="modalfinalconfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Final Confirmation before finishing Verification Cycle </h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body mx-3">
                <form id="confirmationmodalform">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Overall Stage of Completion:</label>
                                    <input disabled class="form-control" name="overallstage" value="<?php echo round(($projects[0]->VerifiedQuantity/$projects[0]->TotalQuantity)*100,2).' %';?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Total No. of Li:</label>
                                    <input disabled class="form-control" name="totalli" value="<?php echo $projects[0]->TotalQuantity;?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Total No. of Li verified:</label>
                                    <input disabled class="form-control" name="totalliverified" value="<?php echo $projects[0]->VerifiedQuantity;?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Total No. of Li unverified:</label>
                                    <input disabled class="form-control" name="totaliunverified" value="<?php echo $projects[0]->TotalQuantity-$projects[0]->VerifiedQuantity;?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
            
                
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <span class="text-center" class="pop_up_label">Kindly confirm you want to Finish the Verification Project !!</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea rows="4" id="finish_remarks" style="width:100%;" placeholder="<Mention optional text remarks here> with limit of 2500 chrs"></textarea>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div> 
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn pull-right" data-dismiss="modal">Cancel<i class="fas fa-times ml-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-footer d-flex justify-content-center"> <a href="javascript:void(0)" class="btn pull-right" id="finalconfirmationproceed">Proceed<i class="fas fa-check ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div> 
<!-- MODEL BOX-3 -->
<div class="modal fade " id="modalabortconfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Final Confirmation before aborting Verification Cycle </h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body mx-3">
                <form id="confirmationmodalform">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Overall Stage of Completion:</label>
                                    <input disabled class="form-control" name="overallstage" value="<?php echo round(($projects[0]->VerifiedQuantity/$projects[0]->TotalQuantity)*100,2).' %';?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Total No. of Li:</label>
                                    <input disabled class="form-control" name="totalli" value="<?php echo $projects[0]->TotalQuantity;?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Total No. of Li verified:</label>
                                    <input disabled class="form-control" name="totalliverified" value="<?php echo $projects[0]->VerifiedQuantity;?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="pop_up_label">Total No. of Li unverified:</label>
                                    <input disabled class="form-control" name="totaliunverified" value="<?php echo $projects[0]->TotalQuantity-$projects[0]->VerifiedQuantity;?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    
            
                
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <span class="text-center" class="pop_up_label">Kindly confirm you want to Finish the Verification Project !!</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea rows="4" id="abort_remarks" style="width:100%;" placeholder="<Mention optional text remarks here> with limit of 2500 chrs"></textarea>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div> 
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn pull-right" data-dismiss="modal">Cancel<i class="fas fa-times ml-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-footer d-flex justify-content-center"> <a href="javascript:void(0)" class="btn pull-right" id="abortconfirmationproceed">Proceed<i class="fas fa-check ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div> 
<!-- MODEL BOX-4 -->
<div class="modal fade " id="modalfinished" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Congratulations for finishing the Verification Cycle !!! </h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body mx-3">
                <form id="confirmationmodalform">
                    
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group text-center">
                                <span class="text-center" class="pop_up_label">Do you want to process to view FA Reporting?</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div> 
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn pull-right closethismodel" data-dismiss="modal">Cancel<i class="fas fa-times ml-1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-footer d-flex justify-content-center closethismodel"> <a href="javascript:void(0)" class="btn pull-right" id="confirmUpload">Proceed<i class="fas fa-check ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div> 
<!-- MODEL BOX-5 -->
<div class="modal fade " id="modalaborted" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Confirmation message </h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body mx-3">
                <form id="confirmationmodalform">
                    
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Project ID:</label>
                                <input disabled name="project_id_aborted" class="form-control" value="<?php echo $projects[0]->project_id;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Project Name:</label>
                                <input disabled name="project_name_aborted" class="form-control" value="<?php echo $projects[0]->project_name;?>">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span class="text-center" class="pop_up_label">has been successfully aborted!!!</span>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </div> 
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="modal-footer d-flex justify-content-center">
                            <button class="btn pull-right closethismodel" data-dismiss="modal">Close<i class="fas fa-times ml-1"></i>
                            </button>
                        </div>
                    </div>
                    
                </div>
            </div>
            </form>
        </div>
    </div>
</div> 
<?php
  $this->load->view('layouts/scripts'); ?>
<?php 
$listing=getTagUntag($projects[0]->project_name);
$cat=getTagUntagCategories($projects[0]->project_name);
/*
echo '<pre>listing ';
print_r($listing);
echo '</pre>';
// exit();

echo '<pre>last_query ';
print_r($this->db->last_query());
echo '</pre>';
exit();
$cat=getTagUntagCategories($projects[0]->project_name);

if($listing['ytotal']!=0 && $listing['ntotal']==0 && $listing['natotal']==0){

    echo round(($listing['yverified']/$listing['ytotal'])*100,2);
    echo "<br>";
    echo "<br>";
    echo 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo round(($listing['yverified']/$listing['ytotal'])*100,2);
    echo "<br>";
    echo "<br>";
    echo $listing['yverified']." of ". $listing['ytotal'];
    echo "<br>";
    echo "<br>";
    echo 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));
    echo "<br>";
    echo "<br>";
    echo ($listing['ytotal']-$listing['yverified'])." of ".($listing['ytotal']);
}


if($projects[0]->project_type=='CD')
{	
    if($listing['ytotal']!=0 && $listing['ntotal']!=0 && $listing['natotal']!=0){ 
        echo "HHHHH :- 1";
    }else if($listing['ytotal']!=0 && $listing['ntotal']!=0 && $listing['natotal']==0){
        echo "HHHHH :- 2";
    }
    else if($listing['ytotal']!=0 && $listing['ntotal']==0 && $listing['natotal']==0){
        echo "HHHHH :- 3"; ?>
        <script>
        var donutdataset=[{
            data: [
                <?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?>,
                <?php echo 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));?>
                ],
            backgroundColor: ["#46BFBD","#e5e5e5"],
            hoverBackgroundColor: ["#616774","#e5e5e5"]
        }];
        var donutlabel=[
            "Tagged (<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ytotal']; ?> Li",
            "Unverified (<?php echo 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));?> %) : <?php echo ($listing['ytotal']-$listing['yverified']).' of '.($listing['ytotal']).' Li';?>"
        ];
        </script>
        <?php 
    }
    else if($listing['ytotal']!=0 && $listing['ntotal']==0 && $listing['natotal']!=0){
        echo "HHHHH :- 4";
    }else if($listing['ytotal']==0 && $listing['ntotal']==0 && $listing['natotal']!=0){
        echo "HHHHH :- 5";
    }else if($listing['ytotal']==0 && $listing['ntotal']!=0 && $listing['natotal']!=0){
        echo "HHHHH :- 6";
    }else if($listing['ytotal']==0 && $listing['ntotal']!=0 && $listing['natotal']==0){
        echo "HHHHH :- 7";
    }
}

*/

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
        { ?>
            var donutdataset=[{
            data: [<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?>, <?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?>,<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?>,<?php echo 100-(round((($listing['yverified']+$listing['nverified']+$listing['naverified'])/($listing['ytotal']+$listing['ntotal']+$listing['natotal']))*100,2));?>],
                backgroundColor: ["#46BFBD", "#FDB45C","#F7464A","#e5e5e5"],
                hoverBackgroundColor: ["#616774", "#007bff", "#FFC870","#e5e5e5"]
            }];
            var donutlabel=["Tagged (<?php echo round(($listing['yverified']/$listing['ytotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ytotal']; ?> Li", "Untagged (<?php echo round(($listing['nverified']/$listing['ntotal'])*100,2);?> %): <?php echo $listing['yverified']; ?> of <?php echo $listing['ntotal']; ?> Li", "Unspecified (<?php echo round(($listing['naverified']/$listing['natotal'])*100,2);?> %): <?php echo $listing['naverified']; ?> of <?php echo $listing['natotal']; ?> Li","Unverified (<?php echo 100-(round((($listing['yverified']+$listing['nverified']+$listing['naverified'])/($listing['ytotal']+$listing['ntotal']+$listing['natotal']))*100,2));?> %) : <?php echo ($listing['ytotal']+$listing['ntotal']+$listing['natotal'])-($listing['yverified']+$listing['nverified']+$listing['naverified']).' of '.($listing['ytotal']+$listing['ntotal']+$listing['natotal']).' Li'; ?>"];
        <?php 
        }
        else if($listing['ytotal']!=0 && $listing['ntotal']!=0 && $listing['natotal']==0)
        { ?>
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











  <?php
  $this->load->view('layouts/footer');
  ?>
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

  <script>
    window.onload = function () {

         var chart = new CanvasJS.Chart("OverallProjectStatusChart", {
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
            title: "No. of Projects",
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
                dataPoints: [
                    {"label":"HMCMM Auto Ltd","y":2,"id":1},
                    {"label":"HMC (Trial)","y":2,"id":2}
                ]
            },
            {
                type: "stackedColumn",
                name: "Withindate",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: [
                    {"label":"HMCMM Auto Ltd","y":0,"id":1},
                    {"label":"HMC (Trial)","y":2,"id":2}
                ]
            }
        ]
    });
    chart.render();






      var chart = new CanvasJS.Chart("AmountwiseBreakupChart", {
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
            title: "No. of Projects",
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
                dataPoints: [
                    {"label":"HMCMM Auto Ltd","y":2,"id":1},
                    {"label":"HMC (Trial)","y":2,"id":2}
                ]
            },
            {
                type: "stackedColumn",
                name: "Withindate",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: [
                    {"label":"HMCMM Auto Ltd","y":0,"id":1},
                    {"label":"HMC (Trial)","y":2,"id":2}
                ]
            }
        ]
    });
    chart.render();




    var chart = new CanvasJS.Chart("LineItemBreakupChart", {
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
            title: "No. of Projects",
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
                dataPoints: [
                    {"label":"HMCMM Auto Ltd","y":2,"id":1},
                    {"label":"HMC (Trial)","y":2,"id":2}
                ]
            },
            {
                type: "stackedColumn",
                name: "Withindate",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: [
                    {"label":"HMCMM Auto Ltd","y":0,"id":1},
                    {"label":"HMC (Trial)","y":2,"id":2}
                ]
            }
        ]
    });
    chart.render();





    var chart = new CanvasJS.Chart("ResourcewiseUtilizationChart", {
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
            title: "No. of Projects",
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
                dataPoints: [
                    {"label":"HMCMM Auto Ltd","y":2,"id":1},
                    {"label":"HMC (Trial)","y":2,"id":2}
                ]
            },
            {
                type: "stackedColumn",
                name: "Withindate",
                showInLegend: true,
                yValueFormatString: "#,##0",
                dataPoints: [
                    {"label":"HMCMM Auto Ltd","y":0,"id":1},
                    {"label":"HMC (Trial)","y":2,"id":2}
                ]
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