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
                            <h4 class="card-title">Upload document</h4> 
                         </div>
                        <div class="col-md-6">
                            <form id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar" class="text-center">
                                    <li class="active"></li>
                                    <li class=""></li>
                                    <li class=""></li>
                                    <li class=""></li>
                                </ul>
                            </form>
                        </div>                       
                    </div> 
                    </div>
                                   
                    </div>
                <div class="card-body">
                  <form method="post" action="<?php echo base_url();?>index.php/plancycle/plancyclesteptwo">
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
                          The file has been successfully uploaded !! 
                      </div>
                      <div class="col-md-3">                          
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                              <div class="text-center">
                                  
                                    <input type="hidden" value="<?php echo $company_name;?>" name="company_name">
                                    <input type="hidden" value="<?php echo $company_location;?>" name="company_location">
                                    <input type="hidden" value="<?php echo $table_name;?>" name="table_name">

                                    <button type="submit" class="btn pull-right-sec my-4">NEXT</button>
                                  
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