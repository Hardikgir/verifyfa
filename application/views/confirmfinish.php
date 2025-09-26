 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
 <div class="content">
        <div class="container-fluid">
          <div class="row">
              <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="card">                
                <div class="card-header card-header-primary">
                    <div class="container">
                        <div class="row">                            
                          <div class="col-md-6">
                            <h4 class="card-title">Validation check on parameters </h4>   
                         </div>
                        <div class="col-md-6">
                            <form id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar" class="text-center">
                                    <li class="active"></li>
                                    <li class="active"></li>
                                    <li class="active"></li>
                                    <li class="active"></li>
                                </ul>
                            </form>
                        </div>                       
                    </div> 
                    </div>
                                 
                    </div>
                <div class="card-body">
                  <form method="post" action="<?php echo base_url();?>index.php/plancycle/plancyclestepthree">
                    <div class="row">
                      <div class="col-md-12">
                        <h3 class="text-center">Confirmation Message</h3>                     
                      </div>                                            
                    </div>
                    <div class="row my-4">
                        <div class="col-md-5">                          
                          </div>

                      <div class="col-md-2">
                          <i class="fas fa-check check-confirm"></i>
                      </div>  

                      <div class="col-md-5">
                        
                      </div>
                    </div>
                    <div class="row">
                       <div class="col-md-3">                          
                        </div>
                      <div class="col-md-6 text-center">
                      You have successfully created Verification Cycle for specified period and respective User(s) have been informed on their email. 
                      <br/>
                      Your verification project has been allotted project ID: <?php echo $project_detail[0]->project_id; ?>
                      </div>
                      <div class="col-md-3">                          
                        </div>
                    </div>
                    <?php 
                    if($allocation_status[0]->Remaining>0){
                    ?>
                    <div class="row">
                      <div class="col-md-3"></div>
                      <div class="col-md-6 text-center">
                        <br/>
                        <br/>
                        <u><b>Remaining unallocated items</b></u>
                        <br/>
                        <?php if($tagged_count[0]->tagged_count > 0){?>
                        Tagged (<?php echo $tagged_count[0]->tagged_count;?>/<?php echo $allocation_status[0]->Remaining; ?>)<br/>
                        <?php } ?>
                        <?php if($nontagged_count[0]->nontagged_count > 0){?>
                        Non-tagged (<?php echo $nontagged_count[0]->nontagged_count;?>/<?php echo $allocation_status[0]->Remaining; ?>)<br/>
                        <?php } ?>
                        <?php if($unspecified_count[0]->unspecified_count > 0){?>
                        Unspecified (<?php echo $unspecified_count[0]->unspecified_count;?>/<?php echo $allocation_status[0]->Remaining; ?>)<br/>
                        <?php } ?>
                        <br/>

                        <b>Do you want to continue with allocations ?</b>
                      </div>
                      <div class="col-md-3"></div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                              <div class="text-center">
                                    <input type="hidden" value="<?php echo $company_name;?>" name="company_name">
                                    <input type="hidden" value="<?php echo $company_location;?>" name="company_location">
                                    <input type="hidden" value="<?php echo $table_name;?>" name="table_name">
                                    <?php 
                                    if($allocation_status[0]->Remaining>0)
                                      echo '<a href="'.base_url().'index.php/plancycle" class="btn pull-left-sec my-4">CLOSE</a> <button type="submit" class="btn pull-right-sec my-4">PROCEED</button>';
                                    else
                                      echo 'All Items are Allocated. <br/><br/><a href="'.base_url().'index.php/plancycle" class="btn pull-right-sec my-4">Go to Plancycle</a>';
                                    ?>
                                </div>
                          </div>
                        </div>
                      </div>
                    <div class="clearfix"></div>
                  </form>
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
  $this->load->view('layouts/planningscript');
  $this->load->view('layouts/footer');
  ?>