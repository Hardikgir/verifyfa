<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">Verify fa</h4>
                            <p class="card-category">‘Mark’ headers for verification and editing</p>
                        </div>
                        <div class="col-md-6">
                            <form id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar" class="text-center">
                                    <li class="active"></li>
                                    <li class="active"></li>
                                    <li class=""></li>
                                    <li class=""></li>
                                    <p class="text-center" style="font-weight:bold; margin:0;padding: 10px;"> Entity Name: <?php echo get_CompanyName($company_name);?> Location Name: <?php $comloc= get_location_row($company_location); echo $comloc->location_name?> </p>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" id="markheaderform" action="<?php echo base_url();?>index.php/plancycle/markheaders"> 
                        <div class="table-responsive table-upgrade">
                            <table class="table">
                                <thead class="tableheaders">
                                    <tr>
                                    <th class="firstcolumn">Headers as per file upload</th>
                                    <th class="text-center">Select for Verification Search</th>
                                    <th class="text-center">Select for enabling editing</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="table-wrapper-scroll-y my-custom-scrollbar">
                                            
                            <table class="table headerstable">
                            <tbody>
                                <?php
                                $i=1;
                                if(!empty($mandatory_cols))
                                {
                                    foreach($mandatory_cols as $mc)
                                    {
                                ?>
                                    <tr>
                                    <td class="firstcolumn"><?php echo $i;?>   *<?php echo str_replace("_"," ",$mc);?></td>
                                    <input type="hidden" name="keyname_<?php echo $i;?>" value="<?php echo $mc;?>">
                                    <input type="hidden" name="keylabel_<?php echo $i;?>" value="<?php echo str_replace("_"," ",$mc);?>">
                                    <td class="text-center">
                                        <input type="checkbox" class="mandatory" id="<?php echo $mc.'-show';?>" name="<?php echo $mc.'_show';?>" value="1" checked disabled>
                                        <input type="hidden" name="<?php echo $mc.'_show';?>" value="1">
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="mandatory" id="<?php echo $mc.'-edit';?>" name="<?php echo $mc.'_edit';?>" value="1" <?php if($mc!='item_description'){echo 'disabled';}else{echo 'disabled checked';}?> >
                                        <input type="hidden" name="<?php echo $mc.'_edit';?>" value="<?php if($mc=='item_description'){echo 1;}else {echo 0;}?>" >
                                    </td>
                                    </tr>
                                <?php
                                    $i++;
                                    } 
                                }
                                if(!empty($nonmandatory_cols))
                                {
                                    foreach($nonmandatory_cols as $nmc)
                                    {
                                ?>
                                    <tr>
                                    <td class="firstcolumn"><?php echo $i;?>   <?php echo str_replace("_"," ",$nmc);?>
                                    <input type="hidden" name="keyname_<?php echo $i;?>" id="<?php echo $nmc.'-key';?>" value="<?php echo $nmc;?>" disabled>
                                    <input type="hidden" name="keylabel_<?php echo $i;?>" id="<?php echo $nmc.'-label';?>" value="<?php echo str_replace("_"," ",$nmc);?>" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="headerchecks nonmandatory" id="<?php echo $nmc.'-show';?>" name="<?php echo $nmc.'_show';?>" value="0" title="Select additional (non-mandatory) headers that can help searching & editing Items at the time of verification">
                                        
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="editheaderchecks nonmandatory" id="<?php echo $nmc.'-edit';?>" name="<?php echo $nmc.'_edit';?>" value="0" title="Select additional (non-mandatory) headers that can help searching & editing Items at the time of verification">
                                        <input type="hidden" id="<?php echo $nmc.'_editval';?>" name="<?php echo $nmc.'_edit';?>" value="0" disabled>
                                    </td>
                                    </tr>
                                <?php
                                    $i++;
                                    } 
                                }
                                ?>
                                
                                
                                
                            </tbody>
                            </table>
                            <input type="hidden" value="<?php echo $i;?>" name="ival">
                            <input type="hidden" value="<?php echo $company_name;?>" name="company_name">
                            <input type="hidden" value="<?php echo $company_location;?>" name="company_location">
                            <input type="hidden" value="<?php echo $table_name;?>" name="table_name">
                        </div>         
                        <div class="container">
                            <div class="row">                            
                                <div class="col-md-4">
                                    <td class="text-center">
                                        <a href="#" class="btn btn-round btn-fill  pull-right-sec ">Cancel</a>
                                    </td>
                                </div>
                                <div class="col-md-4">
                                    <td class="text-center">
                                        <a href="javascript:void(0)" class="btn btn-round btn-fill  pull-right-sec resetform">Reset</a>
                                    </td>
                                </div>
                                <div class="col-md-4">
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-round btn-fill pull-right-sec" >Next</button>
                                    </td>
                                </div>
                            </div> 
                        </div>
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