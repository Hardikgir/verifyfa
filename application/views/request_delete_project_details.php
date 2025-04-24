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
                        
                </div>
                
            </div>
                <div class="card-body">
                
                    <?php 
                        foreach($projects as $pro)
                        {
                    ?>
                    <div class="row inSummary23">
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
                    <div class="row inDetails">
                            
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

                        <?php 
                        if($_SESSION['logged_in']['main_role'] == '5'){
                            $project_id = $requestdeteleprojectdetails->company_project_id;
                            $accept_url = base_url().'index.php/dashboard/acceptrequestdeleteproject/'.$project_id;
                            $declined_url = base_url().'index.php/dashboard/declinerequestdeleteproject/'.$project_id;
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-primary" href='<?php echo $accept_url; ?>'>Accept</a>
                                    <a class="btn btn-danger" href='<?php echo $declined_url; ?>'>Declined</a>
                                </div>
                            </div>
                        <?php } ?>
                        
                        
                    </div>
                    <div class="clearfix"></div>
                    <?php
                        }
                        ?>
                
                
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
