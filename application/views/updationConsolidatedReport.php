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
								if($data[0]['type']=='consolidated')
								{
								?>
								<form>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="project" class="optradio" disabled> Project based </label></span>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<div class="radio"> <span class="text-center"><label><input type="radio" name="optradio" value="consolidated" class="text-center optradio" checked > Consolidated </label></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="clearfix"></div>
								</form>

								<?php foreach($data as $projectData) { 
									$project_header_column_value = explode(",", $projectData['project_header_column_value']);
									unset($project_header_column_value[0]); // remove 'id'
									unset($project_header_column_value[1]); // remove 'item_sub_category'
								?>
								<div class="col-md-12" style="overflow-x:scroll; margin-bottom:30px;">
									<h5 style="text-align:left; padding:10px 0; font-weight:bold; color:#5CA1E2;">
										<a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionChangesUpdationsofItems/<?php echo $projectData['project']->id;?>" style="color:#5CA1E2;">
											<?php echo $projectData['project']->project_name; ?>
										</a>
									</h5>
								    
                                    <table border="1" width="100%">
                                        <?php 
                                        echo '<tr>';
                                        echo '<th>Allocated Item Category</th>';
									    $count2 = 1;
                                        foreach($project_header_column_value as $col_val){
                                            echo '<th>';
                                            echo ucfirst(str_replace('_',' ', $col_val));
                                            echo '</th>';
										    $count2++;
                                        }
                                        echo '</tr>';

							    		if(!empty($projectData['different'])){
								    		foreach($projectData['different'] as $key=>$value){ ?>
                                        <tr>
                                            <th>
                                                <?php echo $key; ?>
                                            </th>

                                            <?php 
                                            foreach($project_header_column_value as $col_val){
                                                echo '<td>';
                                                if(isset($projectData['different'][$key][$col_val])){
                                                    echo count($projectData['different'][$key][$col_val]);
                                                }else{
                                                    echo "0";
                                                }
                                                echo '</td>';
                                            } ?>
                                        </tr>

								    	<?php
								    		}
								    	} else { ?>
                                        <tr>
                                            <td colspan="<?php echo $count2; ?>" style="text-align:center;">No changes / updations found for this project.</td>
                                        </tr>
                                        <?php } ?>
										<tr>
											<td colspan="<?php echo $count2; ?>">
												<b>
													<a href="<?php echo base_url(); ?>index.php/dashboard/downloadExceptionChangesUpdationsofItems/<?php echo $projectData['project']->id;?>">Download as Annexure</a>
												</b>
											</td>
										</tr>
                                    </table>
								</div>
								<?php } // end foreach project ?>

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
	<!--seccion table-->
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
