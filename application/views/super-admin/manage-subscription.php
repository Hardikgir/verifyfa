<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('super-admin/layout/header');
$this->load->view('super-admin/layout/sidebar');
?>
<style>
    .tooltip-arrow,
.red-tooltip + .tooltip > .tooltip-inner {background-color: #f00;}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Manage Subscription</h4>
            <a href="<?php echo base_url();?>index.php/create-subscription">
             <button class="btn btn-primary" style="float:right">Create New Subscription</button>
            </a>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
 <div class="wrapper-carousel-fix">
						<!-- Carousel Wrapper -->
 <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Title Subscription</th>
                <th>Validity From (Date)</th>
                <th>Validity To (Date)</th>
                <th>Amount </th>
                <th>Subscription Type</th>
                <th>Remaining Time </th>
                <th> Status </th>
                <th>Change Status </th>
                <th>Action </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($plan as $row){ ?>
            <tr>
                <td >
            <a href="#" class="destitle" data="<?php echo $row->description;?>"><?php echo $row->title;?></a>
                </td>
                <td><?php echo date("d M Y", strtotime($row->validity_from));?></td>
                <td><?php echo date("d M Y", strtotime($row->validity_to));?></td>
                <td><?php echo $row->amount; ?></td>
                <td>
                <?php echo ucfirst($row->time_subscription);?>
              </td>
                <td>
                <?php if(($row->validity_to < date("Y-m-d")) || ($row->validity_from > date("Y-m-d"))){
                  echo "NA";
                }else{?>  
                <?php
                 echo get_diff_twodate($row->validity_to);
                 }
                ?>
            </td>

            <td>
                    <?php if(($row->validity_to < date("Y-m-d")) || ($row->validity_from > date("Y-m-d"))){?>
                          Inactive
                        <?php }else{
                            ?>
                        Active
                      
                        <?php } ?>
               </td>
                <td>
                    <?php if(($row->validity_to < date("Y-m-d")) || ($row->validity_from > date("Y-m-d"))){?>
                        <a href="<?php echo base_url();?>index.php/edit-subscription/<?php echo $row->id;?>">
                          <button class="btn btn-success">Activate</button> 
                        </a>
                        <?php }else{
                            ?>
                      <a href="<?php echo base_url();?>index.php/inactive-plan/<?php echo $row->id;?>">
                        <button class="btn btn-danger">Inactivate</button> 
                       </a>
                      
                        <?php } ?>
               </td>
                <td>
                    <a href="<?php echo base_url();?>index.php/edit-subscription/<?php echo $row->id;?>">
                     <i class="fa fa-edit"></i>Edit
                    </a>
                    ||
                    <a href="<?php echo base_url();?>index.php/view-subscription/<?php echo $row->id;?>">
                     <i class="fa fa-eye"></i>View
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        </table>
					</div>			
				</section>
				</div>
			</div>
			<!-- Section: Testimonials v.2 -->
		
		</div>	
		
			</div>
		</div>
	</div>
	
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" id="subsdes" data-target="#exampleModalCenter" style="display:none;">
  Launch Description model</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><span id="headingtitle"></span> Description</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="content">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
$this->load->view('super-admin/layout/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('super-admin/layout/footer');
?>
<script>
    $(".destitle").click(function(){
        var heading =$(this).html();
        var content =$(this).attr('data');
        $("#content").html(content);
        $("#headingtitle").html(heading);
        $("#subsdes").trigger("click");
    })
</script>