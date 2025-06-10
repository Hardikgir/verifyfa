<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
error_reporting(0);
?>
<style>
table th,table td{
	padding:5px;
	font-size: 0.875rem;
}
.btn-blue{
	background: #5B96CE !important;
    color: white !important;
}
</style>
	<div class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<?php if(isset($_SESSION['error_message']))
							{
							?>
							<div class="alert alert-danger">
								<?php echo $_SESSION['error_message']['message']; ?>
							</div>
							<?php 
							}
							?>
							<div class="card">
								
								<div class="card-header card-header-primary">
									<div class="row">
                                    	<div class="col-md-6">
                                    		<h4 class="card-title">Reports </h4> 
                                    	</div>
                                    	<div class="col-md-6">
                                    		<a href="<?php echo base_url(); ?>index.php/dashboard/exceptions"><button class="btn btn-round pull-right">Back</button></a>
                                    	</div>
                                    </div>
                                    	
								</div>
								<?php 
								if($data['type']=='project')
								{
								?>
								<form>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="project" class="optradio" checked> Project based </label></span>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="consolidated" class="text-center optradio" disabled> Consolidated </label></span>
													</div>
												</div>
											</div>
										</div>
										<div class="row my-3">
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Exception Category:</label>
                                                    <input type="text" class="form-control" value="Changes/ Updations of Items">
												</div>
                                            </div>
                                            <div class="col-md-6">
												<div class="form-group">
                                                    <label>Project ID:</label>
                                                    <input type="text" class="form-control" value="<?php echo $data['project'][0]->project_id;?>">
												</div>
											</div>
										</div>
										<div class="row my-3">
                                            
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Project Name:</label>
                                                    <input type="text" class="form-control" value="<?php echo $data['project'][0]->project_name;?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Period of Verification(Days):</label>
                                                    <input type="text" class="form-control" value="<?php echo $data['project'][0]->period_of_verification;?>">
												</div>
											</div>
										</div>
										<div class="row my-3">
                                            <div class="col-md-6">
												<div class="form-group">
                                                    <label>Start Date:</label>
                                                    <input type="text" class="form-control" value="<?php echo date('d/m/Y',strtotime($data['project'][0]->start_date));?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Due Date:</label>
                                                    <input type="text" class="form-control" value="<?php echo date('d/m/Y',strtotime($data['project'][0]->due_date));?>">
												</div>
											</div>
											
										</div>
										<div class="row my-3">
											<?php
											$verifier=explode(',',$data['project'][0]->project_verifier);
											$verifier_name="";
											for($ii=0;$ii<count($verifier);$ii++)
											{
												if($ii==count($verifier)-1)
												{
													$verifier_name.=get_UserName($verifier[$ii]);
												}
												else
												{
													$verifier_name.=get_UserName($verifier[$ii]).", ";
												}
											}
											?>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Allocated Users:</label>
                                                    <input type="text" class="form-control" value="<?php echo $verifier_name;?>">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
                                                    <label>Project Status:</label>
                                                    <?php 
                                                    	if($data['project'][0]->status==0){
	                                                    	$statusProject='In Process';
	                                                    }
	                                                    else if($data['project'][0]->status==1)
	                                                    {
		                                                    $statusProject='Completed';
		                                                }
		                                                else if($data['project'][0]->status==2)
		                                                {
			                                                $statusProject='Cancelled';
			                                            }
			                                            else
			                                            {
				                                            $statusProject='Finished Verification';
				                                        }
				                                    ?>
                                                    <input type="text" class="form-control" value="<?php echo $statusProject;?>">
												</div>
											</div>
									</div>
									</div>
									
									<div class="clearfix"></div>
								</form>
								<div class="col-md-12" style="overflow-x:scroll;">

                                    
                                    <table class="table table-bordered">
                                        <?php 
                                        $project_header_column_value = explode(",",$data['project_header_column_value']);
                                        unset($project_header_column_value[0]);
                                        unset($project_header_column_value[1]);
                                        echo '<tr>';
                                        echo '<th>Allocated Item Category</th>';
                                        foreach($project_header_column_value as $project_header_column_value_value){
                                            echo '<th>';
                                            echo ucfirst(str_replace('_',' ',$project_header_column_value_value));
                                            echo '</th>';
                                        }
                                        echo '</tr>';

                                        foreach($data['different'] as $key=>$value){ ?>
                                        <tr>
                                            <td>
                                                <?php echo $key; ?>
                                            </td>

                                            <?php 
                                            foreach($project_header_column_value as $project_header_column_value_value){
                                                echo '<td>';
                                                if(isset($data['different'][$key][$project_header_column_value_value])){
                                                    echo count($data['different'][$key][$project_header_column_value_value]);
                                                }else{
                                                    echo "0";
                                                }
                                                echo '</td>';
                                            } ?>
                                        </tr>
                                        <?php } ?>
                                    </table>											
									<a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionChangesUpdationsofItems">Download as Annexure</a>
								</div>
								<?php
								}
								?>
								<div class="col-md-12 text-center mt-3"><a href="<?php echo base_url(); ?>index.php/dashboard/exceptions"><button class="btn btn-round btn-blue">Back</button></a></div>
							</div>
						</div>
                    </div>
                    
				</div>
			</div>
		</div>
	</div>
	<!--seccion table--->
	</div>
	</div>
<style>
	table th,table td
	{
		text-align: center;
		height:40px;
	}
	</style>
<?php
$this->load->view('layouts/scripts');
$this->load->view('layouts/reportscript');
$this->load->view('layouts/footer');
?>