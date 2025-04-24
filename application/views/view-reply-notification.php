<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layouts/header');
$this->load->view('layouts/sidebar');
?>
<?php 
$user_id=$this->user_id;
$entity_code=$this->admin_registered_entity_code;

?>
<style>
    .tooltip-arrow,
.red-tooltip + .tooltip > .tooltip-inner {background-color: #f00;}
chat-offline {
    color: #e4606d
}

.chat-messages {
    display: flex;
    flex-direction: column;
    max-height: 800px;
    overflow-y: scroll
}

.chat-message-left,
.chat-message-right {
    display: flex;
    flex-shrink: 0
}

.chat-message-left {
    margin-right: auto
}

.chat-message-right {
    flex-direction: row-reverse;
    margin-left: auto
}
.py-3 {
    padding-top: 1rem!important;
    padding-bottom: 1rem!important;
}
.px-4 {
    padding-right: 1.5rem!important;
    padding-left: 1.5rem!important;
}
.flex-grow-0 {
    flex-grow: 0!important;
}
.border-top {
    border-top: 1px solid #dee2e6!important;
}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
<div class="row">    
<div class="col-lg-12">
<section>
<!-- Section heading -->
<div class="wrapper-carousel-fix">
<main class="content">
    <div class="container p-0">

		<h1 class="h3 mb-3">Notification Type: <?php echo $notification_data->type;?> </h1>

		<div class="card">
			<div class="row g-0">
				<div class="col-12 col-md-12">
					<div class="py-2 px-4 border-bottom d-none d-lg-block">
						<div class="d-flex align-items-center py-1">

							<div class="flex-grow-1 pl-3">
								<strong>Title:</strong> <?php echo $notification_data->title;?> 
								<div class="text-muted small">
                                <strong>Description:</strong> <?php echo $notification_data->description;?>

                                </div>
							</div>
							<div>
								
							</div>
						</div>
					</div>
					<?php 
					if($notification_data->type != 'Notification'){ ?>
					<div class="position-relative">
						<div class="chat-messages p-4">

							<!-- <div class="chat-message-right pb-4">
								 <div>
									<img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle mr-1" alt="Chris Wood" width="40" height="40">
									<div class="text-muted small text-nowrap mt-2">2:33 am</div>
								</div>
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 mr-3">
									<div class="font-weight-bold mb-1">You</div>
									Lorem ipsum dolor sit amet, vis erat denique in, dicunt prodesset te vix.
								</div>
							</div> -->
                        <?php foreach($reply_data as $row){ 
                             $user_row=get_user_row($row->reply_from);
                            ?>
							<div class="<?php if($row->reply_from == $this->user_id){echo "chat-message-right"; }else { echo "chat-message-left";}?> pb-4">
								
								<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">
									<div class="font-weight-bold mb-1">
                                    <?php 
                                      if($row->reply_from == $this->user_id){
                                        echo "You ";
                                        }else{ 
                                        echo $user_row->firstName.' '.$user_row->lastName;
                                         }
                                        ?></div>
									<?php echo $row->reply_message;?>
								</div>
							</div>
                            <?php } ?>


						</div>
					</div>
				<?php 
					
						$user_role_addmin_cnt=get_user_role_cnt_admin($user_id,$entity_code);
						$user_role_manager_cnt=get_user_role_cnt_managers($user_id,$entity_code);					
						if(($user_role_addmin_cnt > 0 || $user_role_manager_cnt > 0)){ 
							?>
							<div class="flex-grow-0 py-3 px-4 border-top">
								<form action="<?php echo base_url();?>index.php/save-notification-reply" method="post">
									<input type="hidden" value="<?php echo $notification_data->id;?>" name="notification_id">
									<div class="input-group">
										<input type="text" name="reply_message" class="form-control" placeholder="Type your Feedback or Message">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								</form>
							</div>
							<?php 
						}
					}
				?>

				<?php /*  //I Think this is not require now becuase as we have already check above if their is role is 5 or not
				
				if($this->main_role == '5'){ ?>
					<div class="flex-grow-0 py-3 px-4 border-top">
									<form action="<?php echo base_url();?>index.php/save-notification-reply" method="post">
										<input type="hidden" value="<?php echo $notification_data->id;?>" name="notification_id">
										<div class="input-group">
											<input type="text" name="reply_message" class="form-control" placeholder="Type your Feedback or Message">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
								</form>
								</div>
					<?php } */ ?>

					<!-- <hr> -->

					<div class="notification_users">
						<h3 class="text-center mb-3 mt-5">SEND NOTIFICATION TO USERS</h3>
						<table class="table table-border">
							<tr>
								<th>FirstName</th>
								<th>LastName</th>
								<th>Designation</th>
								<th>Role</th>
							</tr>
							<?php foreach($sender_to_user as $sender_to_user_key=>$sender_to_user_value){ ?>
								<tr>
									<td><?php echo $sender_to_user_value->firstName; ?></td>
									<td><?php echo $sender_to_user_value->lastName; ?></td>
									<td><?php echo $sender_to_user_value->designation; ?></td>
									<td>
										<?php 
											echo get_role_name($sender_to_user_value->user_role);
										?>
									</td>
								</tr>
							<?php } ?>
							
						</table>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>


 

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
$this->load->view('layouts/scripts');
// $this->load->view('layouts/layouts/dashboard_script');
$this->load->view('layouts/footer');
?>
