<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('super-admin/layout/header');
$this->load->view('super-admin/layout/sidebar');
?>
<style>

nav > .nav.nav-tabs{

border: none;
  color:#fff;
  background:#272e38;
  border-radius:0;

}
nav > div a.nav-item.nav-link,
nav > div a.nav-item.nav-link
{
border: none;
  padding: 18px 25px;
  color:#fff;
  background:#272e38;
  border-radius:0;
}

nav > div a.nav-item.nav-link.active:after
{
content: "";
position: relative;
bottom: -60px;
left: -10%;
border: 15px solid transparent;
border-top-color: #e74c3c ;
}
.tab-content{
background: #fdfdfd;
  line-height: 25px;
  border: 1px solid #ddd;
  border-top:5px solid #e74c3c;
  border-bottom:5px solid #e74c3c;
  padding:30px 25px;
}

nav > div a.nav-item.nav-link:hover,
nav > div a.nav-item.nav-link:focus
{
border: none;
  /* background: #e74c3c; */
  color:#fff;
  border-radius:0;
  transition:background 0.20s linear;
}
.nav-link>.active{
    background: #e74c3c;
}
.form-row {
    display: flex;
    flex-wrap: wrap;
    margin-right: 0;
    margin-left: 0;
    padding: 0 35px;
}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">User Confirmation Details</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
            <div class="row">
                <div class="col-md-12 ">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" href="<?php echo base_url();?>index.php/edit-user/<?php echo $user->id;?>">
                      <a class="nav-item nav-link " id="nav-home-tab" href="<?php echo base_url();?>index.php/edit-user/<?php echo $user->id;?>">User Details</a>
                      <a class="nav-item nav-link" id="nav-profile-tab" href="<?php echo base_url();?>index.php/edit-user/<?php echo $user->id;?>">Plan Details</a>
                      <a class="nav-item nav-link" id="nav-contact-tab" href="<?php echo base_url();?>index.php/edit-user/<?php echo $user->id;?>">Payment Details</a>
                      <a class="nav-item nav-link active" id="nav-confirmation-tab" >Confirmation Details</a>
                    </div>
                  </nav>
                  <!-- for user -->
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    
         <div class="tab-pane fade show active" id="nav-confirmation" role="tabpanel" aria-labelledby="nav-contact-tab">
            
            <div class="row">
                <div class="col-md-6 form-row">
                <label class="form-label">Registration Number Allotted (Unique):</label>
                 <?php echo $user->urer_registration_no;?>
                </div>
                <div class="col-md-6 form-row">
                <label class="form-label">Status of Subscription Account::</label>
                <?php if($user->is_active== '1'){echo "Waiting for Confirmation";}?>
                <?php if($user->is_active== '2'){echo "Link Expired";}?>
                <?php if($user->is_active== '3'){echo "Requested Regeneration";}?>
                <?php if($user->is_active== '4'){echo "Activated";}?>
                <?php if($user->is_active== '5'){echo "Suspended";}?>
                <?php if($user->is_active== '6'){echo "Unsubscribed";}?>
                </div>
              </div>
            <br>
         <div class="row">
                <div class="col-md-6 form-row">
                <?php if($user->activation_generete_link == 0){ ?>
                  <a href="<?php echo base_url();?>index.php/generate-activation-link/<?php echo $user->id;?>">
                    <button class="btn btn-success">Generate Activation Link</button>
                    <label class="form-label">Date of Generated Activation Link:</label>
                    N/A

                  </a>
                  <?php }else{ ?>
                    <button class="btn btn-warning">Link Generate Already</button>
                    <label class="form-label">Date of Generated Activation Link:</label>
                    <?php echo date('d-M-Y',strtotime($user->activation_generete_link_date));?>
                    <?php } ?>
                </div>

                <?php if($user->activation_generete_link == 1){ ?>
                <div class="col-md-6 form-row">
                <?php if($user->is_activation_send == 0){ ?>
               <a href="<?php echo base_url();?>index.php/send-activation-link/<?php echo $user->id;?>">
                <button class="btn btn-success">Send Activation Link</button>
                </a>
                <br>
                <label class="form-label">Date of Sending Activation Link:</label>
                 N/A  
                 <?php }else{?>
                      <button class="btn btn-warning">Activation Link Already Sended</button><br>
                     <label class="form-label">Date of Sending Activation Link:</label>
                     <?php echo date('d-M-Y',strtotime($user->activation_send_date));?>
                 <?php } ?>
 
              </div>
              <?php } ?>

             </div>
             <br>
             <div class="row">
              <div class="col-md-4 form-row">
                <label class="form-label">Date of Link Expiration:</label>
                     <?php 
                     if($user->link_expiry_date !='' ){
                     echo date('d-M-Y',strtotime($user->link_expiry_date));
                     }else{ echo "N/A";}
                     ?>
                </div>
              <div class="col-md-4 form-row">
              <label class="form-label">Date of Request for Link Regeneration:</label>
              <?php 
                     if($user->regenerate_activation_date !='' ){
                     echo date('d-M-Y',strtotime($user->regenerate_activation_date));
                     }else{ echo "N/A";}
                     ?>
              </div>

              <div class="col-md-4 form-row">
              <label class="form-label">Date of Sending Activation Link (Regenerated):</label>
              <?php 
                     if($user->regenrred_link_send_date !='' ){
                     echo date('d-M-Y',strtotime($user->regenrred_link_send_date));
                     }else{ echo "N/A";}
                     ?>
              </div>
             </div>
            <br>

   <?php if($user->link_expiry_date < date("Y-m-d") && $user->link_expiry_date !=''){ ?>

             <div class="row">
                <div class="col-md-6 form-row">
                  <a href="<?php echo base_url();?>index.php/regenerate-activation-link/<?php echo $user->id;?>">
                    <button class="btn btn-success">Re Generate Activation Link</button>
                  </a>
                </div>

              <div class="col-md-6 form-row">
               <a href="<?php echo base_url();?>index.php/send-activation-link-regenreted/<?php echo $user->id;?>">
                <button class="btn btn-success">Send Regenrated Activation Link</button>
                </a>                
              </div>
             </div>
             <br>
           <?php } ?>

<div class="row">
   <div class="col-md-6 form-row">
   <?php if($user->is_active != '5'){ ?>
     <a href="<?php echo base_url();?>index.php/suspend-account/<?php echo $user->id;?>">
       <button class="btn btn-success">Suspend Account</button>
     </a>
     <?php }else{ ?>
       <button class="btn btn-warning"> Account Suspended</button>
       <label class="form-label">Date of Account Suspension:</label>
                     <?php 
                     if($user->suspend_date !='' ){
                     echo date('d-M-Y',strtotime($user->suspend_date));
                     }else{ echo "N/A";}
                     ?>
      <?php } ?>
   </div>

 <div class="col-md-6 form-row">
 <?php if($user->is_active != '6'){ ?>
  <a href="<?php echo base_url();?>index.php/unsubscribe-account/<?php echo $user->id;?>">
   <button class="btn btn-success">Unsubscripe Subscribe Account</button>
 </a>
   <?php }else{ ?>
       <button class="btn btn-warning"> Account Unsubscribed</button>
       <label class="form-label">Date of Unsubscribed:</label>
                     <?php 
                     if($user->unsubscribe_date !='' ){
                     echo date('d-M-Y',strtotime($user->unsubscribe_date));
                     }else{ echo "N/A";}
                     ?>
      <?php } ?>
 </div>
</div>
<br>

<div class="row">
<?php if(($user->is_active == '6') || $user->is_active == '5'){ ?>
 <div class="col-md-6 form-row">
  <a href="<?php echo base_url();?>index.php/reactive-account/<?php echo $user->id;?>">
   <button class="btn btn-success">Reactive Subscirption Account</button>
 </a>
 </div>
 <?php } ?>

 <?php if(($user->is_active == '6') || $user->is_resubscribe_request == '1'){ ?>
 <div class="col-md-6 form-row">
 <label class="form-label">Date of Request Unsubscribed:</label>
<?php echo date("d-M-Y",strtotime($user->is_resubscribe_request_at));?>
</a>
 </div>
 <?php } ?>

</div>
<br>
<?php if($user->renew_request == '1'){ ?>
  <div class="row">

 <div class="col-md-6 form-row">
 <label class="form-label">Renew Subscirption:</label>
  <a href="<?php echo base_url();?>index.php/edit-user/<?php echo $user->id;?>">
   <button class="btn btn-success">Renew Subscirption Now</button>
 </a>
 </div>
 <div class="col-md-6 form-row">
 <label class="form-label">Request Renew Subscirption At:</label>
 <?php echo date("d-M-Y",strtotime($user->request_renew_at));?>
 </div>
<?php } ?>
<!-- <button class="btn btn-warning back">Back</button>
<button class="btn btn-primary next">Submit</button> -->
                          </div>
                  </div>
                
                </div>
              </div>
        </div>
      </div>
                    </section>
</div>
</div>
</div>

<?php
$this->load->view('super-admin/layout/scripts');
// $this->load->view('super-admin/layouts/dashboard_script');
$this->load->view('super-admin/layout/footer');
?>

<script>
    function check_required_field(new1){
        var result = '1';

     var a= new1.parent('a').parent('div');
       a.find("input").each(function(){
       var ab= $(this).val();
        if ($(this).attr('required') && $(this).val() ==''){
            $(this).focus();
            $(this).css('border','1px solid red');
            result= '0';
        }else{
            result= '1';
        }

    })
    return result;

}
    $(".next").click(function(){
      //  var tag = check_required_field($(this));
        // alert(tag);
        // if(tag == '1'){
        var target= $(this).attr('data-href');
        $(".tab-pane").removeClass('fade');
        $(".tab-pane").removeClass('active');
        $(".tab-pane").removeClass('show');
        $("#"+target).addClass('fade');
        $("#"+target).addClass('active');
        $("#"+target).addClass('show');

        $(".nav-link").removeClass('active');
        $(".nav-link").removeClass('show');
        $("#"+target+"-tab").addClass('active');
        $("#"+target+"-tab").addClass('show');
        // }
    })
</script>
