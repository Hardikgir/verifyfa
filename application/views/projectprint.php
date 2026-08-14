<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$listing=getTagUntag($projects[0]->project_name);
$cat=getTagUntagCategories($projects[0]->project_name);
$allcategories=getCategories($projects[0]->project_name);

?>
<style>
    .wrapper{
        height:auto;
    }
    .btn-blue{
    background: #5B96CE !important;
    color: white !important;
}

.table .thead-dark th {
    background: #5ca1e2 !important;
    
}
    </style>

      <div class="content">
        <div class="container-fluid">
          <div class="row">
              <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="card">                
              <div class="card-header card-header-primary">
                <div class="container">
                    <div class="row">
                        
                            <div class="col-md-3"><h4>Project Detail<h4></div>
                            <div class="col-md-3 col-xs-3 text-right">
                            <a href="<?php echo base_url();?>index.php/dashboard/projectdetail/<?php echo $projectId;?>" class="btn btn-round btn-blue">Back</a>
                            </div>
                            <div class="col-md-3 col-xs-3 text-right">
                            <a href="#" class="btn btn-round btn-blue" onclick="window.print()">Print</a>
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
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Company Name</label>
                                    <input type="text" value="<?php echo get_CompanyName($pro->company_id);?>" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Unit Location</label>
                                    <input type="text" value="<?php echo $pro->project_location;?>" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Project ID</label>
                                    <input type="text" value="<?php echo $pro->project_id;?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Project Name</label>
                                    <input type="text" value="<?php echo $pro->project_name;?>" class="form-control">
                                </div>
                            </div>
                        
                    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Date of Project Assigned</label>
                                    <input type="text" value="<?php echo date_format(date_create($pro->start_date),'d/m/Y');?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Due Date</label>
                                    <input type="text" value="<?php echo date_format(date_create($pro->due_date),'d/m/Y');?>" class="form-control">
                                </div>
                            </div>
                   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">No.of Resources assigned</label>
                                    <input type="text" value="<?php echo count($pro->project_verifier); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Assigned by</label>
                                    <input type="text" value="<?php echo get_UserName($pro->assigned_by); ?>" class="form-control">
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
                            </div>
                        </div>
                    
                    </div>
                    <div class="clearfix"></div>
                    <?php
                        }
                        ?>
                </form>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <h3 class="boder-bottom">Overall Project Status</h3>
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <canvas id="chDonut3" height="150"></canvas>
                            </div>
                            <div class="col-md-6">
                                <canvas id="doughnutChart" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <h3 class="boder-bottom">Line Item (Li) Breakup</h3>
                        
                        <div id="tab"  class="project-tab">
                            <div class="row">
                                
                                <?php 
                                                
                                if(count($cat['tagged']) > 0 && $projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'){
                                ?>
                                <div class="col-md-6">
                                    <canvas id="taggedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Tagged</h5>
                                </div>
                                <?php
                                }
                                if(count($cat['untagged'])>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD' )){
                                ?>
                                <div class="col-md-6">
                                    <canvas id="untaggedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Non-Tagged</h5>
                                </div>
                                <?php
                                }
                                if(count($cat['unspecified'])>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD' )){
                                ?>	
                                <div class="col-md-6">
                                    <canvas id="unspecifiedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Unspecified</h5>
                                </div>	
                                <?php 
                                }
                                ?>
                                <div class="col-md-12">
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
                                                            if($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD' )
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
                                                        $tamt=0;
                                                        foreach($allcategories['categories'] as $alcat)
                                                        {
                                                            
                                                            
                                                        ?>
                                                        <tr class=" text-center">
                                                            <td><?php echo $alcat->item_category;?></td>
                                                            <td><?php $tamt=$tamt+$alcat->amount; echo getmoney_format(round(($alcat->amount/100000),2));?></td>
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
                                                            <td>0%</td>
                                                            <?php    
                                                            }
                                                            }
                                                            else
                                                            {
                                                            ?>
                                                            <td>0%</td>
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
                                                            <td>0%</td>
                                                            <?php    
                                                            }
                                                            }
                                                            else
                                                            {
                                                            ?>
                                                            <td>0%</td>
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
                                                                if($tg==0)
                                                                {
                                                                ?>
                                                                <td>0%</td>
                                                                <?php    
                                                                }
                                                            }
                                                            else
                                                            {
                                                            ?>
                                                            <td>0%</td>
                                                            <?php    
                                                            }
                                                            }
                                                            if($projects[0]->project_type=='CD' )
                                                            {
                                                            ?>
                                                            <td><?php echo round(($overall/3),2).' %<br>'.$overallverified.' of '.$overalltotal;?></td>
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
                    <div class="col-md-1"></div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <h3 class="boder-bottom">Amount wise Breakup</h3>
                        
                        <div class="project-tab">
                            <div class="row">
                            <?php 
                                if(count($cat['tagged']) > 0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
                                ?>
                                <div class="col-md-6">
                                    <canvas id="amounttaggedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Tagged</h5>
                                </div>
                                <?php
                                }
                                if(count($cat['untagged'])>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD')){
                                ?>
                                <div class="col-md-6">
                                    <canvas id="amountuntaggedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Non-Tagged</h5>
                                </div>
                                <?php
                                }
                                if(count($cat['unspecified'])>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
                                ?>	
                                <div class="col-md-6">
                                    <canvas id="amountunspecifiedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Unspecified</h5>
                                </div>	
                                <?php 
                                }
                                ?>	
                                
                                <div class="col-md-12">
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
                                                    if($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD' )
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
                                                    <td>0%</td>
                                                    <?php    
                                                    }
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                    <td>0%</td>
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
                                                    <td>0%</td>
                                                    <?php    
                                                    }
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                    <td>0%</td>
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
                                                        if($tg==0)
                                                        {
                                                        ?>
                                                        <td>0%</td>
                                                        <?php    
                                                        }
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                    <td>0%</td>
                                                    <?php    
                                                    }
                                                    }
                                                    if($projects[0]->project_type=='CD' )
                                                    {
                                                    ?>
                                                    <td><?php echo round(($overall/3),2).'%<br>'.getmoney_format(round(($overallverified/100000),2)).' of '.getmoney_format(round(($overalltotal/100000),2)).' Lacs'?></td>
                                                    <?php 
                                                    }
                                                    ?>
                                                    
                                                    
                                                    
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
                                                    <td><strong><?php echo getmoney_format(round($ttv/100000),2).' of '.getmoney_format(round($ttt/100000),2).' Lacs';?></strong></td>
                                                    <?php
                                                    }
                                                    if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                    {
                                                    ?>
                                                    <td><strong><?php echo getmoney_format(round($tntv/100000),2).' of '.getmoney_format(round($tntt/100000),2).' Lacs';?></strong></td>
                                                    <?php
                                                    }
                                                    if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD' ))
                                                    {
                                                    ?>
                                                    <td><strong><?php echo getmoney_format(round($tutv/100000),2).' of '.getmoney_format(round($tutt/100000),2).' Lacs';?></strong></td>
                                                    <?php
                                                    }
                                                    if($projects[0]->project_type=='CD' )
                                                    {
                                                    ?>
                                                    <td><strong><?php echo getmoney_format(round(($ttv+$tntv+$tutv)/100000),2).' of '.getmoney_format(round(($ttt+$tntt+$tutt)/100000),2).' Lacs';?></strong></td>
                                                    <?php 
                                                    }
                                                    ?>
                                                    
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
                                                    
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <h3 class="boder-bottom">Resource wise Utilization</h3>
                        
                        <div class="project-tab">
                            <div class="row">
                            <?php 
                                if($listing['ytotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
                                ?>
                                <div class="col-md-6">
                                    <canvas id="resourcetaggedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Tagged</h5>
                                </div>
                                <?php
                                }
                                if($listing['ntotal']>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD')){
                                ?>
                                <div class="col-md-6">
                                    <canvas id="resourceuntaggedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Non-Tagged</h5>
                                </div>
                                <?php
                                }
                                if($listing['natotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){
                                ?>	
                                <div class="col-md-6">
                                    <canvas id="resourceunspecifiedpieChart" height="150"></canvas>
                                    <h5 class=" text-center">Unspecified</h5>
                                </div>	
                                <?php 
                                }
                                ?>
                                <div class="col-md-12">
                                    
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
                                                            <td><?php echo round(($res->usertagged/$listing['ytotal'])*100,2).' %<br>'.$res->usertagged.' of '.$listing['ytotal'];?></td> 
                                                            <?php
                                                            }
                                                            if($listing['ntotal']>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
                                                            {
                                                            ?>
                                                            <td><?php echo round(($res->useruntagged/$listing['ntotal'])*100,2).' %<br>'.$res->useruntagged.' of '.$listing['ntotal'];?></td> 
                                                            <?php
                                                            }
                                                            if($listing['natotal']>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
                                                            {
                                                            ?>
                                                            <td><?php echo round(($res->userunspecified/$listing['natotal'])*100,2).' %<br>'.$res->userunspecified.' of '.$listing['natotal'];?></td> 
                                                            <?php
                                                            }
                                                            if($projects[0]->project_type=='CD')
                                                            {
                                                            ?>
                                                            <td><?php $totuser=($res->usertagged+$res->useruntagged+$res->userunspecified);
                                                            $totalall=$listing['natotal']+$listing['ytotal']+$listing['ntotal'];
                                                            echo round(($totuser/$totalall)*100,2).' %<br>'.$totuser.' of '.$totalall;?></td> 
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
                    <div class="col-md-1"></div>
                </div>
               
            </div>
            </div>
        </div>                               
        </div>
    </div>
    </div>   

    
</div>
</div> 
  <?php
  $this->load->view('layouts/scripts');
  $this->load->view('layouts/detailscript');
  $this->load->view('layouts/footer');
  ?>