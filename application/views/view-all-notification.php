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

		<h1 class="h3 mb-3">See All Notification </h1>

        <?php $main_notification= get_all_notification($entity_code);?>
<?php 
$i=0;
$j=0;
foreach($main_notification as $main_notification_row){
	$userrownt= get_user_row($main_notification_row->created_by);
	$cntmain_notify= check_main_notificationread($this->user_id,$main_notification_row->id);

	?>

<div class="card">
			<div class="row g-0">
				<div class="col-12 col-md-12">
					<div class="py-2 px-4 border-bottom d-none d-lg-block">
						<div class="d-flex align-items-center py-1">

							<div class="flex-grow-1 pl-3">
								<strong>Title:</strong> <?php echo $main_notification_row->title;?> 
                                <?php echo ucfirst($userrownt->firstName).' '.ucfirst($userrownt->lastName);?> Broadcast a New <?php echo $main_notification_row->type;?><br>
                                    <p style="font-size: 15px;font-weight: normal;" class="pb-0 mb-0"> <?php echo $main_notification_row->title;?></p>
                                    <p style="margin: 0;padding: 0;font-size: 12px;"><b>At:</b> <?php echo date("d-M-Y g:i:a", strtotime($main_notification_row->created_at));?></p>
							</div>
                                <a class="dropdown-item d-block" href="<?php echo base_url();?>index.php/view-reply-notofication/<?php echo $main_notification_row->id;?>?main_not=1" style="                                        font-weight: bold;padding: 0;margin: 0;">
                                        <span style="color:blue;">Check now<span>
                                    </a>
                               <div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>






<?php

$allreply = get_all_reply($main_notification_row->id);
foreach($allreply as $reply_row){ 
	$userrownt1= get_user_row($reply_row->reply_from);
    $cnt_reply_user=get_cntreply_user($this->user_id,$reply_row->id);
	?>


<div class="card">
			<div class="row g-0">
				<div class="col-12 col-md-12">
					<div class="py-2 px-4 border-bottom d-none d-lg-block">
						<div class="d-flex align-items-center py-1">

							<div class="flex-grow-1 pl-3">
                            <?php echo ucfirst($userrownt1->firstName).' '.ucfirst($userrownt1->lastName);?> a New Reply <?php echo $main_notification_row->title;?><br>
                                    <p style="font-size: 15px;font-weight: normal;" class="pb-0 mb-0"></p>   
                                    <p style="margin: 0;padding: 0;font-size: 12px;"><b>At:</b> <?php echo date("d-M-Y g:i:a", strtotime($reply_row->reply_at));?></p>  
                                    <a class="dropdown-item d-block" href="<?php echo base_url();?>index.php/view-reply-notofication/<?php echo $main_notification_row->id;?>?reply_msg=1&&reply_msg_id=<?php echo $reply_row->id;?>" style="font-weight: bold;padding: 0;margin: 0;"> <span style="color:blue;">Check now<span></a>
							</div>
							<div>
								
							</div>
						</div>
					</div>
      

				</div>
			</div>
		</div>

 <?php }?>

<?php } ?>
		
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
