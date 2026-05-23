<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<style>
  .custom-select[multiple], .custom-select[size]:not([size="1"]) {
    height: auto;
    padding-right: 0.75rem;
    background-image: none;
    min-height: 400px;
}
option {
    text-wrap: wrap;
    border-bottom: 1px solid;
    padding-bottom: 5px;
    padding-top: 5px;
}
</style>
 <div class="content">
        <div class="container-fluid">
          <div class="row">
              <div class="col-md-1"></div>
            <div class="col-md-10">
              <div class="card">                
                <div class="card-header card-header-primary">         
                    <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title">Edit Project Resource Mapping </h4>                  
                        </div>
                        <div class="col-md-6">
                        <form id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar" class="text-center">
                                    <li class="active"></li>
                                    <li class="active"></li>
                                    <li class="active"></li>
                                    <li class="active"></li>
                                    <p class="text-center" style="font-weight:bold; margin:0;padding: 10px;"> Entity Name: <?php echo $company_name;?> Location Name: <?php echo $company_location;?> </p>
                                </ul>
                            </form>
                         
                        </div>     
                       
                    </div>      
                    </div>
                <div class="card-body">  
                    <form method="POST" action="<?php echo base_url();?>index.php/plancycle/editresourcemappingsave">                                     
                      <div class="row my-3">
                      <div class="col-md-12">
                        <h3>Map Project to Monitoring Team:</h3>
                      </div>

                          <div class="col-md-3">
                              <div class="form-group">
                                  <label class="bmd-label-floating">To Select multiple Item Categories, hold Ctrl button and then multi select</label>
                                  <select class="browser-default custom-select multiple" name="verifier[]" multiple required>
                                      <option value="">Allocated to Verifier(s)<span id="mandatory_star">*</span></option>
                                      <?php
                                      $verifier=$project[0]['project_verifier'];
                                      $verifyer_array = explode(',',$verifier);
                                      foreach($user_data as $dsad){
                                        $user_role = explode(',',$dsad->user_role);
                                        if (in_array("1", $user_role)){
                                          $user_id = $dsad->user_id;
                                          $getdat = get_user_row($user_id);
                                          $getdept = get_department_row($getdat->department_id);
                                          $getcom = get_company_row($getdat->company_id);
                                          ?>
                                              <option value="<?php echo $getdat->id;?>" <?php if (in_array($getdat->id, $verifyer_array)){ echo "selected"; }?>><?php echo $getdat->firstName.' '.$getdat->lastName;?>/ <?php echo $getdat->designation;?> /<?php echo $getdept->department_shortcode;?>/ <?php echo $getcom->short_code;?></option>
                                            <?php
                                        }
                                      }
                                      ?>
                                  </select>
                              </div> 
                          </div>                                                   
                        
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="bmd-label-floating">To Select multiple Item Categories, hold Ctrl button and then multi select</label>
                                    <select class="browser-default custom-select multiple" multiple name="process_owner[]" required>
                                        <option value="">Process Owner(s)</span></option>
                                      <?php
                                       $process_owner=$project[0]['process_owner'];
                                       $process_owner_array = explode(',',$process_owner);
                                      foreach($user_data as $dsad){
                                        $user_role = explode(',',$dsad->user_role);
                                        if (in_array("2", $user_role)){
                                          $user_id = $dsad->user_id;
                                          $getdat = get_user_row($user_id);
                                          $getdept = get_department_row($getdat->department_id);
                                          $getcom = get_company_row($getdat->company_id);
                                          ?>
                                              <option value="<?php echo $getdat->id;?>" <?php if (in_array($getdat->id, $process_owner_array)){ echo "selected"; }?>><?php echo $getdat->firstName.' '.$getdat->lastName;?>/ <?php echo $getdat->designation;?> /<?php echo $getdept->department_shortcode;?> /<?php echo $getcom->short_code;?></option>
                                            <?php
                                        }
                                      }
                                      ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="bmd-label-floating">To Select multiple Item Categories, hold Ctrl button and then multi select</label>
                                    <select class="browser-default custom-select multiple" multiple name="item_owner[]" required>
                                        <option value="">Entity Owner(s)</option>
                                      <?php
                                        $item_owner=$project[0]['item_owner'];
                                        $item_owner_array = explode(',',$item_owner);

                                        foreach($user_data as $dsad){
                                          $user_role = explode(',',$dsad->user_role);
                                          if (in_array("3", $user_role)){
                                            $user_id = $dsad->user_id;
                                            $getdat = get_user_row($user_id);
                                            $getdept = get_department_row($getdat->department_id);
                                            $getcom = get_company_row($getdat->company_id);
                                            ?>
                                                <option value="<?php echo $getdat->id;?>" <?php if (in_array($getdat->id, $item_owner_array)){ echo "selected"; }?>><?php echo $getdat->firstName.' '.$getdat->lastName;?>/ <?php echo $getdat->designation;?> /<?php echo $getdept->department_shortcode;?>/ <?php echo $getcom->short_code;?></option>
                                              <?php
                                          }
                                        }
                                      ?>
                                    </select>
                                </div>
                            </div>                          
                          
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="bmd-label-floating">To Select multiple Item Categories, hold Ctrl button and then multi select</label>
                                      <select class="browser-default custom-select multiple" multiple name="project_manager[]" required>
                                          <option value="">Manager(s)</span></option>
                                      <?php
                                            $manager=$project[0]['manager'];
                                            $manager_array = explode(',',$manager);
                                          foreach($user_data as $dsad){
                                            $user_role = explode(',',$dsad->user_role);
                                            if (in_array("0", $user_role)){
                                              $user_id = $dsad->user_id;
                                              $getdat = get_user_row($user_id);
                                              $getdept = get_department_row($getdat->department_id);
                                              $getcom = get_company_row($getdat->company_id);
                                              ?>
                                                  <option value="<?php echo $getdat->id;?>" <?php if (in_array($getdat->id, $manager_array)){ echo "selected"; }?>><?php echo $getdat->firstName.' '.$getdat->lastName;?>/ <?php echo $getdat->designation;?> /<?php echo $getdept->department_shortcode;?>/ <?php echo $getcom->short_code;?></option>
                                                <?php
                                            }
                                          }
                                      
                                      ?>
                                      </select>
                                  </div>
                              </div>                                                       
                            </div> 
                            <input type="hidden" name="project_id" value="<?php echo $project[0]['id'];?>">
                            <div class="row ml-5">
                                <!-- <div class="col-md-4">
                                    <td class="text-center">
                                      <a href="#" class="btn  btn-fill  pull-right-sec ">Cancel</a>
                                    </td>
                                  </div>
                                  -->
                                <div class="col-md-12 text-center">
                                  <td class="text-center">
                                    <button type="submit" class="btn btn-fill pull-right-sec">Update Now</button>
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