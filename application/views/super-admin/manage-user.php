<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('super-admin/layout/header');
$this->load->view('super-admin/layout/sidebar');
$open_projects=3;
$closed_projects=5;
$cancelled_projects=2;
?>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">Manage Registration</h4>
            <a href="<?php echo base_url();?>index.php/select-plan">
             <button class="btn btn-primary" style="float:right">Create New Registration</button>
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
                <th>Name</th>
                <th>Email</th>
                <th>Phone No</th>
                <th>Entity Code</th>
                <th>Subscription Plan</th>
                <th>Remaining Time</th>
                <th>Status </th>
                <th>Action </th>
            </tr>
        </thead>
        <tbody>
            <?php
             foreach($user_data as $row){ 
                $plan_row=get_plan_row($row->plan_id);    
                $user_plan_row=get_user_plan_row($row->id);   
                // $time_remain=get_diff_twodate($user_plan_row->plan_end_date);
                $time_remain = 5;
                ?>
            <tr>
                <td style="padding: 15px 0px !important;"><?php echo $row->first_name .' '. $row->last_name;?></td>
                <td><?php echo $row->email_id;?></td>
                <td><?php echo $row->phone_no;?></td>
                <td><?php echo $row->entity_code;?></td>
                <td><?php echo $plan_row->title;?></td>
                <td><?php echo $time_remain;?></td>
              
                <td>
                <?php if($row->is_active== '1'){echo "Waiting for Confirmation";}?>
                <?php if($row->is_active== '2'){echo "Link Expired";}?>
                <?php if($row->is_active== '3'){echo "Requested Regeneration";}?>
                <?php if($row->is_active== '4'){echo "Activated";}?>
                <?php if($row->is_active== '5'){echo "Suspended";}?>
                <?php if($row->is_active== '6'){echo "Unsubscribed";}?>
               </td>
                <td>
                <?php if($row->renew_request == '1'){ ?>

                <a href="<?php echo base_url();?>index.php/confirmation-user-detail/<?php echo $row->id;?>">
                     <i class="fa fa-eye"></i> Requested to Renew</a>
                     ||
                     <?php } ?>

 <?php if($row->is_resubscribe_request == '1'){ ?>

<a href="<?php echo base_url();?>index.php/confirmation-user-detail/<?php echo $row->id;?>">
     <i class="fa fa-eye"></i> Requested to Resubscription</a>
     ||
     <?php } ?>

                     <a href="<?php echo base_url();?>index.php/confirmation-user-detail/<?php echo $row->id;?>">
                     <i class="fa fa-edit"></i> Change Status</a>
                     ||
                    <a href="<?php echo base_url();?>index.php/edit-user/<?php echo $row->id;?>">
                    <i class="fa fa-edit"></i> Edit
                    </a>
                    ||
                    <a href="<?php echo base_url();?>index.php/view-user/<?php echo $row->id;?>">
                    <i class="fa fa-eye"></i> View
                    </a>

                    ||
                    <a href="<?php echo base_url();?>index.php/upgrade-user-plan/<?php echo $row->id;?>">
                    <i class="fa fa-eye"></i> Upgrade User Plan
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
<?php
$this->load->view('super-admin/layout/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('super-admin/layout/footer');
?>