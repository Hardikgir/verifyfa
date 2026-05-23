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
                    <?php /*
                    <div class="row inSummary23">
                        <!-- <div class="col-md-12"> -->
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
                        <!-- </div> -->
                        
                    </div> */ ?>
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

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="bmd-label-floating">Reason of Deletion</label>
                                    <input type="text" value="<?php echo $requestdeteleprojectdetails->reason_for_delete;?>" class="form-control" disabled>
                                </div>
                            </div>


                        </div>

                        <?php 

                     
                        if($_SESSION['logged_in']['main_role'] == '5'){
                            
                            // $project_id = $requestdeteleprojectdetails->company_project_id;
                            $project_id = $projects[0]->id;
                            $accept_url = base_url().'index.php/dashboard/acceptrequestdeleteproject/'.$project_id;
                            $declined_url = base_url().'index.php/dashboard/declinerequestdeleteproject/'.$project_id;
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-primary" href='<?php echo $accept_url; ?>'>Accept</a>
                                    <!-- <a class="btn btn-danger" href='<?php echo $declined_url; ?>'>Declined</a> -->
                                    <a href="javascript:void(0)" onclick="requestfordelete(this,<?php echo $project_id; ?>)" class="btn btn-danger">Decline</a>
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


    <!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <form action="<?php echo base_url().'index.php/dashboard/declinerequestdeleteproject/'; ?>" method="post">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button>           -->
          <h4 class="modal-title">Give Reason For Decline Delete Project</h4>
        </div>
        <div class="modal-body">
          <p>Reason for Decline</p>
          <input type="hidden" name="hdn_project_id" id="hdn_project_id" value="<?php echo $project_id; ?>">
          <input type="hidden" name="hdn_row_id" id="hdn_row_id" value="<?php echo $requestdeteleprojectdetails->request_delete_row_id; ?>">
           <input type="hidden" name="hdn_requester_id" id="hdn_requester_id" value="<?php echo $requestdeteleprojectdetails->request_delete_requester_id; ?>">
          <input type="hidden" name="hdn_user_id" id="hdn_user_id" value="<?php echo $this->user_id; ?>">
          
          <textarea class="form-control" name="reason_for_detele"></textarea>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>  
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </form>
      </div>
      
    </div>
  </div>

  <script>

function requestfordelete(event,project_id){
    // var project_id = $("#event")
    $("#hdn_project_id").val(project_id);
    $('#myModal').modal('show');
}

  </script>
    
</div>
</div>
  <?php
  $this->load->view('layouts/scripts');
  $this->load->view('layouts/detailscript');
  $this->load->view('layouts/footer');
  ?>
