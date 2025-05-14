<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    $this->load->view('layouts/header');
    $this->load->view('layouts/sidebar');
?>


<link href="<?php echo base_url(); ?>assets/multipledropdown/multiselect.css" rel="stylesheet"/>
<script src="<?php echo base_url(); ?>assets/multipledropdown/multiselect.min.js"></script>
<style>
    #selectUserType_multiSelect {
        width: 100%;
    }
    .multiselect-wrapper ul li.active label{
        color: white;
    }
    .multiselect-count {
        background-color: #5ca1e2;
        color:white;
    }
</style>
<style>
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    width: 100% !important;
}
.usertypeselection {
    margin: 15px 0px;
}

.view_attachment_cls{
    font-weight: bold;
    color: blue;
}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid">

		<div class="container-fluid content-new">

		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">View Issue</h4>
       </div>

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
           <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->

                        <div class="row">
                            <div class="col-12">

                            <table class="table table-bordered">
                                <tr>
                                    <th>Subject</th>
                                    <td><?php echo $issue_result->issue_title ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $issue_result->issue_description ?></td>
                                </tr>

                                <tr>
                                    <th>Tracking ID</th>
                                    <td><?php echo $issue_result->tracking_id ?></td>
                                </tr>
                                <tr>
                                    <th>Issue Type</th>
                                    <td><?php echo $issue_result->issue_type ?></td>
                                </tr>
                                <tr>
                                    <th>Company Name</th>
                                    <td><?php echo $issue_result->company_name ?></td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td><?php echo $issue_result->location_name ?></td>
                                </tr>
                                <tr>
                                    <th>Project</th>
                                    <td><?php echo $issue_result->project_id ?></td>
                                </tr>

                                <tr>
                                    <th>Resolve Name</th>
                                    <td><?php echo $issue_result->firstName." - ".$issue_result->lastName; ?></td>
                                </tr>
                                <?php 
                                if(!empty($issue_result->issue_attachment)){ ?>
                                <tr>
                                    <th>Attachment</th>
                                    <td><a href="<?php echo base_url().'issueattachment/'.$issue_result->issue_attachment; ?>" target="_blank" class="view_attachment_cls">View Attachment</a></td>
                                </tr>
                                <?php } ?>
                                
                                <tr>
                                    <th>Status</th>
                                    <td><?php 
                                    if($issue_result->status =='1'){echo "Open";}
                                    if($issue_result->status =='0'){echo "Closed";}
                                    ?></td>
                                </tr>
                                 <tr>
                                    <th>Status Type</th>
                                   <td><?php 
                                    if($issue_result->status_type =='1'){echo "New";}
                                    if($issue_result->status_type =='2'){echo "Escalted";}                                    
                                    ?></td>
                                </tr>

                            </table>
                             

                                    <?php
                                    $user_id = $_SESSION['logged_in']['id'];
                                    if(($user_id == $issue_result->resolved_by) && ($issue_result->status==1)){
                                    ?>
                                     <hr>
                                    <form action="<?php echo base_url(); ?>index.php/update-issue" method="post" enctype='multipart/form-data'>
                                        <div class="row my-4">

                                            <div class="col-md-12 my-2 form-row" >
                                                <label class="form-label">Status</label>
                                                <select name="status" id="status" onchange="changeStatus(this)" class="form-control">
                                                    <option value="1" <?php if($issue_result->status =='1'){ echo 'selected'; } ?>>Open</option>
                                                    <option value="0" <?php if($issue_result->status =='0'){ echo 'selected'; } ?>>Closed</option>
                                                </select>
                                            </div>

                                            <div class="col-md-12 my-2 form-row" id="status_remark_section">
                                                <label class="form-label">Remark</label>
                                                <textarea  name="status_remark" id="status_remark" class="form-control" placeholder="Enter Remark"></textarea>
                                            </div>


                                            <div class="col-md-12 my-2 form-row" id="status_type_remark_section" style="display:none">
                                                <label class="form-label">Status Type Remark</label>
                                                <textarea  name="status_type_remark" id="status_type_remark" class="form-control" placeholder="Enter Remark"></textarea>
                                            </div>

                                            <div class="col-md-12 my-2 form-row">
                                                <label class="form-label">Attachment</label>
                                                <input type="file" name="issue_attachment" id="issue_attachment" class="form-control" required="">
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 my-4 form-row">
                                                <!-- <a href="javascript:void" class="btn btn-primary" onclick="EscalteFun(this)">Escalte Issue</a> -->
                                                <input type="hidden" name="hdn_status_type" id="hdn_status_type" value="<?php echo $issue_result->status_type; ?>">
                                                <input type="hidden" name="hdn_issue_id" id="hdn_issue_id" value="<?php echo $issue_result->id; ?>">
                                                <button type="reset" class="btn btn-danger">Cancel</button>
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php } ?>
                            </div>
                        </div>
              
				</div></section>
				</div>
			</div>
			<!-- Section: Testimonials v.2 -->

		</div>
			<!-- Section: Testimonials v.2 -->

		</div>

			</div>
		</div>
	</div>

</div>
<?php
    $this->load->view('layouts/scripts');
    // $this->load->view('super-admin/layouts/dashboard_script');
    $this->load->view('layouts/footer');
?>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
