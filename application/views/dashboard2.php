<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div id="carousel-example-1" class="carousel no-flex testimonial-carousel slide carousel-fade" data-ride="carousel" data-interval="false">
                    <!--Slides-->
                    <div class="carousel-inner" role="listbox">
                        <!--First slide-->
                        <div class="carousel-item active">
                            <div class="testimonial">
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
                            </div>
                        </div>
                        <!--Second slide-->
                        <div class="carousel-item">
                            <div class="testimonial">
                                <div class="row">
                                    <div class="col-md-12">
                                        <nav>
                                            <div class="nav nav-tabs nav-fill nav-justified" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" href="#div1" id="tab1" data-toggle="tab" aria-controls="open-project" aria-selected="true"><b>Open Projects</b></a>
                                                <a class="nav-item nav-link" href="#div2" id="tab2" data-toggle="tab" aria-controls="closed-project" aria-selected="true"><b>Closed Projects</b></a>
                                                <a class="nav-item nav-link" href="#div3" data-toggle="tab" aria-controls="cancelled-project" aria-selected="true"><b>Cancelled Projects</b></a>
                                            </div>
                                        </nav>
                                        <!-- <ul class="nav nav-tabs nav-justified ">
                                            <li class="nav-item"><a class="nav-link active table-active " href="#div1" id="tab1" data-toggle="tab" aria-controls="open-project" aria-selected="true">Open Projects</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link tab-click" href="#div2" id="tab2" data-toggle="tab" aria-controls="closed-project" aria-selected="true"><span id="tab-text">Closed Projects</span></a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link tab-click" href="#div3" data-toggle="tab" aria-controls="cancelled-project" aria-selected="true"><span id="tab-text">Cancelled Projects</span></a>
                                            </li>
                                        </ul> -->
                                        <div class="tab-content pt-5">
                                            <div id="div1" class="tab-pane in active" aria-labelledby="open-project">
                                                <div class="card">
                                                    <div class="card-header card-header-primary">
                                                        <h4 class="card-title">Open Projects</h4>
                                                        <p class="card-category">Showing all open projects</p>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead class=" text-center">
                                                                    <tr>
                                                                    <th>#</th>
                                                                    <th> <span>Project ID</span> 
                                                                    </th>
                                                                    <th> <span>Project Name</span> 
                                                                    </th>
                                                                    <th> <span>Date of Project assigned</span> 
                                                                    </th>
                                                                    <th> <span>Due Date</span> 
                                                                    </th>
                                                                    <th> <span>Remaining/(Overdue) Day </span> 
                                                                    </th>
                                                                    <th> <span>Stage of Completion </span>
                                                                    </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php 
                                                                    $i=0;
                                                                    if(!empty($projects))
                                                                    {
                                                                    foreach($projects as $pro)
                                                                    {
                                                                        if($pro->status==0 || $pro->status==3)
                                                                        {

                                                                        
                                                                    ?>
                                                                            <tr class="text-center">
                                                                                <td></td>
                                                                                <td><?php echo $pro->project_id;?></td>
                                                                                <td>
                                                                                    <a href="<?php echo base_url();?>index.php/dashboard/projectdetail/<?php echo $pro->id; ?>">
                                                                                        <?php echo $pro->project_name;?>
                                                                                    </a>
                                                                                </td>
                                                                                <td><span style="display:none;"><?php echo date_format(date_create($pro->start_date),'Ymd');?></span><?php echo date_format(date_create($pro->start_date),'d/m/Y');?></td>
                                                                                <td><span style="display:none;"><?php echo date_format(date_create($pro->due_date),'Ymd');?></span><?php echo date_format(date_create($pro->due_date),'d/m/Y');?></td>
                                                                                <td><?php 
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
                                                                                // printing result in days format 
                                                                                // $interval->format('%R%a days');
                                                                                echo $interval->format('%R%a') >= 0 ? $interval->format('%a days').' Remaining':'<span style="color:red;"> '.$interval->format('%a days').'(Overdue)</span>';   ?></td>
                                                                                <td><?php echo round(($pro->VerifiedQuantity/$pro->TotalQuantity)*100,2).' %'; ?></td>
                                                                            </tr>
                                                                    <?php 
                                                                            
                                                                            }
                                                                            
                                                                        }
                                                                        if($i==0)
                                                                        {
                                                                            "<tr><td colspan='6'><strong>Projects are not available.</strong></td></tr>";
                                                                        }
                                                                    }
                                                                    // else{
                                                                    //     "<tr><td colspan='6'><strong>Projects are not available.</strong></td></tr>";
                                                                    // }
                                                                    ?>
                                                                </tbody>
                                                            </table>

                                                            
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <!--seccion table-2-->
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
                                                                </thead>
                                                                <tbody>
                                                                <?php 
                                                                    $i1=0;
                                                                    if(!empty($projects))
                                                                    {
                                                                    foreach($projects as $pro)
                                                                    {
                                                                        if($pro->status==1)
                                                                        {

                                                                        
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
                                                                            </tr>
                                                                    <?php 
                                                                           
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
                                            <!--seccion table-3-->
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
														</div>
													</div>
												</div>
                            </div>
                        </div>
                    </div>
                    <ol class="carousel-indicators" id="change">
                        <li data-target="#carousel-example-1" data-slide-to="0" class="active mx-1"></li>
                        <li data-target="#carousel-example-1" data-slide-to="1"></li>
                        
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/dashboard_script');
$this->load->view('layouts/footer');
?>