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
                    <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title">Project Resource Mapping </h4>                  
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
                <div class="card-body">  
                    <form method="POST" action="<?php echo base_url();?>index.php/plancycle/finishplancycle">                                     
                      <div class="row my-3">
                          <div class="col-md-12">
                              <div class="form-group">
                                  <select class="browser-default custom-select multiple" name="verifier[]" multiple required>
                                      <option value="">Allocated to Verifier(s)<span id="mandatory_star">*</span></option>
                                      <?php
                                      foreach($users as $usr)
                                      {
                                          if($usr->userRole==1)
                                          {
                                     ?>
                                      <option value="<?php echo $usr->id;?>"><?php echo $usr->firstName.' '.$usr->lastName;?></option>
                                      <?php
                                          }
                                      }
                                      ?>
                                  </select>
                              </div> 
                          </div>                                                   
                        </div>  
                        <h3>Map Project to Monitoring Team:</h3>
                        <div class="row my-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="browser-default custom-select multiple" multiple name="process_owner[]" required>
                                        <option value="">Process Owner(s)</span></option>
                                        <?php
                                      foreach($users as $usr)
                                      {
                                          if($usr->userRole==2)
                                          {
                                     ?>
                                      <option value="<?php echo $usr->id;?>"><?php echo $usr->firstName.' '.$usr->lastName;?></option>
                                      <?php
                                          }
                                      }
                                      ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="browser-default custom-select multiple" multiple name="item_owner[]" required>
                                        <option value="">Item Owner(s)</option>
                                        <?php
                                      foreach($users as $usr)
                                      {
                                          if($usr->userRole==3)
                                          {
                                     ?>
                                      <option value="<?php echo $usr->id;?>"><?php echo $usr->firstName.' '.$usr->lastName;?></option>
                                      <?php
                                          }
                                      }
                                      ?>
                                    </select>
                                </div>
                            </div>                          
                          </div> 
                          <div class="row my-3">
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <select class="browser-default custom-select multiple" multiple name="project_manager[]" required>
                                          <option value="">Manager(s)</span></option>
                                         <?php
                                      foreach($users as $usr)
                                      {
                                          if($usr->userRole==0)
                                          {
                                     ?>
                                      <option value="<?php echo $usr->id;?>"><?php echo $usr->firstName.' '.$usr->lastName;?></option>
                                      <?php
                                          }
                                      }
                                      ?>
                                      </select>
                                  </div>
                              </div>                                                       
                            </div> 
                            <input type="hidden" name="project_id" value="<?php echo $project_id;?>">
                            <div class="row ml-5">
                                <div class="col-md-4">
                                    <td class="text-center">
                                      <a href="#" class="btn  btn-fill  pull-right-sec ">Cancel</a>
                                    </td>
                                  </div>
                                 
                                <div class="col-md-4">
                                  <td class="text-center">
                                    <button type="submit" class="btn btn-fill pull-right-sec">Finish</button>
                                  </td>
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
  </div> 
  <?php
  $this->load->view('layouts/scripts');
  $this->load->view('layouts/planningscript');
  $this->load->view('layouts/footer');
  ?>