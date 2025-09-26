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
                            <h4 class="card-title">Set up FA verification cycle</h4> 
                        </div>
                        <div class="col-md-6">
                            <form id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar" class="text-center">
                                    <li class="active"></li>
                                    <li class="active"></li>
                                    <li class="active"></li>
                                    <li class=""></li>
                                    <?php
                                     $com=get_company_row($company_name);
                                     $loc=get_location_row($company_location);
                                    ?>
                                    <p class="text-center" style="font-weight:bold; margin:0;padding: 10px;"> Entity Name: <?php echo $com->company_name;?> Location Name: <?php echo $loc->location_name;?> </p>

                                </ul>
                            </form>
                        </div>
                        
                    </div>               
                                   
                    </div>
                <div class="card-body">  
                  <form method="POST" action="<?php echo base_url();?>index.php/plancycle/createproject">              
                    <div class="row">
                    <?php if($tagged_count[0]['tagged_count']>0){?>
                      
                      <div class="col-md-6">
                        <div class="form-group">                                            
                                <div class="radio">
                                  <span class="text-center"><label><input type="radio" name="project_type" class="project_type" value="TG" >Tagged (<?php echo $tagged_count[0]['tagged_count']." of ".$total_count[0]['total_count'];?>) </label></span>
                                </div>
                          </div>
                      </div> 
                    <?php } if($nontagged_count[0]['nontagged_count']>0){?>
                       <div class="col-md-6">
                          <div class="form-group">                                            
                                  <div class="radio">
                                      <span class="text-center"><label><input type="radio" name="project_type" class="project_type" value="NT"  class="text-center"> Non-Tagged (<?php echo $nontagged_count[0]['nontagged_count']." of ".$total_count[0]['total_count'];?>)</label></span>
                                  </div>
                            </div>
                       </div>                        
                   
                    <?php } if($unspecified_count[0]['unspecified_count']>0){?>
                        <div class="col-md-6">
                          <div class="form-group">                                            
                                  <div class="radio">
                                    <label><input type="radio" name="project_type" class="project_type" value="UN" >Unspecified (<?php echo $unspecified_count[0]['unspecified_count']." of ".$total_count[0]['total_count'];?>) </label>
                                  </div>
                            </div>
                        </div> 
                      <?php } ?>
                         <div class="col-md-6">
                            <div class="form-group">                                            
                                    <div class="radio">
                                      <label><input type="radio" name="project_type" value="CD" class="project_type" checked> Consolidated (<?php echo $total_count[0]['total_count'];?>) </label>
                                    </div>
                              </div>
                         </div>                        
                      </div> 
                        
                        <div class="row my-3">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="bmd-label-floating">Project Name<span id="mandatory_star">*</span></label>
                              <input type="text" name="project_name" class="form-control" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label class="bmd-label-floating">Period of Verification<span id="mandatory_star">*</span></label>
                              <input type="text" name="period_of_verification" class="form-control" required>
                            </div>
                          </div>                          
                        </div>  
                        <div class="row my-3">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label" style="top:-1rem;">Tentative Start Date</label>
                                    <input type="date" name="start_date" id="start_date" onblur="startDateChange(event);" class="form-control" required>
                                </div> 
                            </div>   
                            <div class="col-md-6">
                                <div class="form-group">
                                <label class="bmd-label" style="top:-1rem;">Tentative End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                                </div> 
                            </div>                          
                          </div> 
                          <div class="row my-3">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label class="bmd-label-floating">Special Instructions for User</label>
                                  <textarea onkeyup="counter(this,'counter',500)" name="instructions_to_user" maxlength="500" rows="4" class="form-control" required></textarea>
                                  <span id="counter" style="color:red;font-size:10px;">500 chars left</span>
                                </div>
                              </div>                                                     
                                                                                   
                      
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label class="bmd-label-floating">To Select multiple Item Categories, hold Ctrl button and then multi select</label>
                                    <select class="browser-default custom-select multiple_selection" name="item_category[]" id="item_category" multiple required>
                                        <option selected>Item Category<span id="mandatory_star">*</span></option>
                                        <?php
                                        foreach($categories as $cat)
                                        {
                                        ?>
                                        <option value="<?php echo $cat['item_category'];?>"><?php echo $cat['item_category'];?></option>
                                        <?php
                                        } 
                                        ?>
                                        
                                    </select>
                                </div> 
                            </div>
                    </div>       
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <div class="text-center">
                                <a href="#"><button type="button" class="btn pull-right-sec my-4">Cancel</button></a>
                              </div>
                        </div>
                      </div>
                        <div class="col-md-6">
                           <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn pull-right-sec my-4">Next</button>
                              </div>
                          </div>
                      </div>                      
                    </div>
                    <div class="clearfix"></div>
                    <input type="hidden" value="<?php echo $company_name;?>" name="company_name">
                    <input type="hidden" value="<?php echo $company_location;?>" name="company_location">
                    <input type="hidden" value="<?php echo $table_name;?>" id="table_name" name="table_name">
                    <input type="hidden" value="<?php echo $original_file;?>" id="original_file" name="original_file">
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
  ?>
  <script>
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    today = yyyy+'-'+mm+'-'+dd;
    
    document.getElementById("start_date").setAttribute('min',today);
    function startDateChange(e){

      var end_date = new Date($('#start_date').val());
      end_date.setDate(end_date.getDate() + 1);
      var end_dd = end_date.getDate();
      var end_mm = end_date.getMonth()+1; //January is 0!
      var end_yyyy = end_date.getFullYear();
      if(end_dd<10){
          end_dd='0'+end_dd;
      } 
      if(end_mm<10){
          end_mm='0'+end_mm;
      } 
      end_date = end_yyyy+'-'+end_mm+'-'+end_dd;
      document.getElementById("end_date").setAttribute("min", end_date);
      $('#end_date').val('')
      
    }

    
  </script>
  <?php
  $this->load->view('layouts/footer');
  ?>