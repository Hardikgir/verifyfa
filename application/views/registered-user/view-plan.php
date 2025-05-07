<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('registered-user/layout/header');
$this->load->view('registered-user/layout/sidebar');
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
    /* background: #e74c3c; */
}
.form-row {
    display: flex;
    flex-wrap: wrap;
    margin-right: 0;
    margin-left: 0;
    padding: 0 35px;
}
#example_wrapper{
    margin: 27px;
}
</style>
<!-- Section: Testimonials v.2 -->
	<div class="content">
		<div class="container-fluid content-new">
	
		<div class="row">
        <div class="col-lg-12 mt-4 mb-4" style="border-bottom:1px solid #6e50505e;">
            <h4 class="page-title">My Subscription</h4>
       </div>     

			<div class="col-lg-12">
            
            <section class="text-center">
					<!-- Section heading -->
                    <div class="row">
                <div class="col-md-12 ">
                  <nav>
                    <div class="nav nav-tabs nav-fill p-0" id="nav-tab" role="tablist">
                      <a disabled class="nav-item nav-link active text-left" id="nav-home-tab" >My Plan Details</a>
                   
                    </div>
                  </nav>
                  <!-- for user -->
<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">                     
 <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
 <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">User Registration No:</label>
                <?php echo $user_data->urer_registration_no;?>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Entity Code:</label>
                    <?php echo $user_data->entity_code;?>
                </div>
              
             </div>
             <br>             
 <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Account Status</label>
                <?php if($user_data->is_active== '1'){echo "Waiting for Confirmation";}?>
                <?php if($user_data->is_active== '2'){echo "Link Expired";}?>
                <?php if($user_data->is_active== '3'){echo "Requested Regeneration";}?>
                <?php if($user_data->is_active== '4'){echo "Activated";}?>
                <?php if($user_data->is_active== '5'){echo "Suspended";}?>
                <?php if($user_data->is_active== '6'){echo "Unsubscribed";}?>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Category of Subscription </label>
                    <?php if($plan_data->category_subscription == 'Original'){echo "Original";}?>
                    <?php if($plan_data->category_subscription == 'Upgrade'){echo "Upgrade";}?>
                     <?php if($plan_data->category_subscription == 'Renewal'){echo "Renewal";}?>
                     <?php if($plan_data->category_subscription == 'Reactivation'){echo "Reactivation";}?>
                </div>
              
             </div>
             <br>           
             <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Subscription Plan</label>

                    <?php
                    
                    // echo '<pre>Subscription_plan ';
                    // print_r($Subscription_plan);
                    // echo '</pre>';
                    // exit(); 
                    
                    /*
                    $plan_row=get_plan_row($plan_data->plan_id);?>
                    <b>Subscription Plan Breakup</b> <br><br>
                    <b>No. of Entities</b>: <u><?php echo ($plan_row->amount * $plan_data->subscription_time_value);?></u><br>
                    <b> No. of Locations under each Entity</b>:  <u><?php echo get_total_payment_charges_user($plan_data->regiistered_user_id);?></u><br>
                    <b>Total No. of Users</b>:  <u><?php echo get_total_payment_discount_user($plan_data->regiistered_user_id);?></u><br>
                    <b>No. of Rows for upload</b>:  <u><?php echo get_total_payment_discount_user($plan_data->regiistered_user_id);?></u>
                    */ ?>
                    
                    <?php $plan_row=get_plan_row($plan_data->plan_id);?>
                    <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="
                    <b>Subscription Plan Breakup</b> <br><br>
                    <b>No. of Entities</b>: <u><?php echo $Subscription_plan->allowed_entities_no;?></u><br>
                    <b> No. of Locations under each Entity</b>:  <u><?php echo $Subscription_plan->location_each_entity;?></u><br>
                    <b>Total No. of Users</b>:  <u><?php echo $Subscription_plan->user_number_register;?></u><br>
                    <b>No. of Rows for upload</b>:  <u><?php echo $Subscription_plan->line_item_avaliable;?></u>">
                    <?php echo $plan_row->title;?>
                    </a>

                </div>
                <div class="col-md-6 form-row">
                <label class="form-label">Period of Subscription </label>
                <?php echo $plan_data->time_subscription;?>
                </div>
               
             </div>
             <br>
             <div class="row">
             <div class="col-md-6 form-row">
                <label class="form-label">Plan Price/ Time Duration  </label>
                RS. <?php echo $plan_row->amount;?>  / <?php echo $plan_row->time_subscription;?>
                </div>

             
          <div class="col-md-6 form-row">
            <label class="form-label">Subscription Amount</label>
                RS. <a href="#" data-toggle="tooltip" data-placement="right" data-html="true" title="<b>Amount Breakup</b> <br>
            <b>Subscription Amount</b>: Rs.<?php echo ($plan_row->amount * $plan_data->subscription_time_value);?>
            <br><b> Other Charges</b>:  Rs.<?php echo get_total_payment_charges_user($plan_data->regiistered_user_id);?><br>
            <b>Discount Credit</b>:  Rs.<?php echo get_total_payment_discount_user($plan_data->regiistered_user_id);?>">
            <?php echo ($plan_row->amount * $plan_data->subscription_time_value);?>
            </a>
          </div>
          </div>
             <br>

             <div class="row">
               
                <div class="col-md-6 form-row">
                    <label class="form-label">Start Date of Subscription </label>
                    <?php echo date("d-M-Y",strtotime($plan_data->plan_start_date));?>
                </div>
                <div class="col-md-6 form-row">
                <label class="form-label">End Date of Subscription </label>
               <?php echo date("d-M-Y",strtotime($plan_data->plan_end_date));?>
                </div>
             </div>
             <br>

             <div class="row">
             <div class="col-md-6 form-row">
                    
                    <label class="form-label">Subscription Plan Validity </label>
                    <?php
                    if($plan_data->plan_end_date < date("Y-m-d")){
    
                    }else{
                    $time_remain=get_diff_twodate($plan_data->plan_end_date);
                    ?>
                    <?php echo $time_remain;?> Left
                    <?php } ?>
                    </div>
             <div class="col-md-6 form-row">
                <label class="form-label">Amount of Subscription Due </label>
                RS. <?php echo $user_data->balance_due;?>
                </div>



                <div class="col-md-6 form-row">
 <?php if($user_data->is_active != '6'){ ?>
  <a href="<?php echo base_url();?>index.php/unsubscribe-account/<?php echo $user_data->id;?>">
   <button class="btn btn-success">Unsubscribe Account</button>
 </a>
   <?php }else{ ?>
       <button class="btn btn-warning"> Account Unsubscribed</button>
       <label class="form-label">Date of Unsubscribed:</label>
                     <?php 
                     if($user_data->unsubscribe_date !='' ){
                     echo date('d-M-Y',strtotime($user_data->unsubscribe_date));
                     }else{ echo "N/A";}
                     ?>
      <?php } ?>
 </div>
                
             </div>
             <br>

            <div class="row">
            <?php if($user_data->is_active == '4' && $plan_data->plan_end_date >= date("Y-m-d")){ ?>
               <div class="col-md-6 form-row">
                <label class="form-label">Unsubscribe Subscription Plan</label>
                <button class="btn btn-danger" onclick="unsubscribe_account()">Unsubscribe Now</button>
              </div>
                <?php } ?>
                <?php if($user_data->is_active == '6'){ ?>
        <?php if($user_data->is_resubscribe_request == '1'){ 
           $reqdate=$user_data->is_resubscribe_request_at;
          $daysreq30 =date('Y-m-d', strtotime($reqdate. ' + 30 days'))
          ?>
            <div class="col-md-12 form-row">

                <label class="form-label">Request to Resubscription Plan At</label>
                <?php   echo date("d-M-Y",strtotime($user_data->is_resubscribe_request_at));?>
                <?php if($daysreq30 < date("Y-m-d")){?>
                <p style="color:red;font-weight:bold; width: 100%;">Your Resubscription  Request expired at <?php echo $daysreq30;?>.Your Account is suspended now kindly connect with admin.
                <?php
                }else{?>
                <p style="color:red;font-weight:bold; width: 100%;">Your Resubscription Request expiry at <?php echo $daysreq30;?></p>
                <?php } ?>
              </div>
             <?php }else{?>
               <div class="col-md-6 form-row">
                <label class="form-label">Request to Subscription Plan</label>
               <a href="<?php echo base_url();?>index.php/request-resubscribe/<?php echo $user_data->id;?>">
                <button class="btn btn-success">Request Now</button>
                </a>
              </div>
              <?php } ?>
                <?php } ?>
                <?php 
                if($user_data->renew_request =='1'){?>
                   <div class="col-md-6 form-row">
                        <label class="form-label">Renewal Request At:</label>
                        <?php echo date("d-M-Y",strtotime($user_data->request_renew_at));?>
                    </div>

                <?php }else{
                $now1 = date("Y-m-d"); // or your date as well
                $your_date = strtotime($plan_data->plan_end_date);
                $now=strtotime($now1);
                $datediff = $your_date - $now;
                $expirydays= round($datediff / (60 * 60 * 24));
                
                if($user_data->is_active== '4' && $plan_data->plan_end_date >= date("Y-m-d") && $expirydays <= '45'){ ?>
                    <div class="col-md-6 form-row">
                        <label class="form-label"><?php echo $expirydays;?> Days left Renew Subscription Plan</label>
                        <a href="<?php echo base_url();?>index.php/request-to-renew/<?php echo $user_data->id;?>">
                        <button class="btn btn-danger">Request to Renew</button>
                       </a>
                    </div>
                <?php } 
                }
                ?>
             </div>
             <br>
 <div class="row">
<div class="col-md-12" style="    margin: 0px 10px;">
<nav style="margin: 25px 0;">
    <div class="nav nav-tabs nav-fill p-0" id="nav-tab" role="tablist">
        <a disabled="" class="nav-item nav-link active text-left" id="nav-home-tab">Transaction History</a>
    
    </div>
</nav>
 <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Transaction ID allotted</th>
                <th>Payment Date</th>
                <th>Mode of Payment</th>
                <th>Transaction Ref </th>
                <th>Amount Paid</th>
                <th>Discount Cr</th>
                <th>Balance Due </th>

            </tr>
        </thead>
        <tbody>
            <?php 
            $paidamt=0;
            $paidamtfinal=0;
            foreach($payment_data as $payment_row){ 
              
             $paidamt += $payment_row->payment_amount + $payment_row->discount_credits;
            //  $paidamtfinal += ($paidamt + $payment_row->discount_credits) -$payment_row->payment_amount;

             $plan_row= get_plan_row($payment_row->plan_id);

            $planamt =  $plan_row->amount * $plan_data->subscription_time_value;
           
              ?>
            <tr>
            <td><?php echo $payment_row->transection_id;?></td>
            <td><?php echo date("d-M-Y",strtotime($payment_row->payment_date));?></td>
            <td><?php echo $payment_row->mode_of_payment;?></td>
            <td><?php echo $payment_row->transection_ref;?></td>
          
            <td>
            <?php echo $payment_row->payment_amount;?>
          </td>
          <td>
            <?php echo $payment_row->discount_credits;?>
          </td>
            <td>
            <?php echo   $planamt - $paidamt;?>
          </td>
           </tr>  
           <?php } ?>
      </tbody>
       
        </table>
        </div> 
        </div>

        </div> 
        </div>
     </div> <br> <br>

    
 </section>
</div>
</div>
</div>

<?php
$this->load->view('registered-user/layout/scripts');
// $this->load->view('registered-user/layouts/dashboard_script');
$this->load->view('registered-user/layout/footer');
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function unsubscribe_account(target_url,msg){
          swal({
            title: "Are you sure?",
            text: "Want to Unsubscribe your Account",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              window.location.href="<?php echo base_url();?>index.php/unsubscribe-now/<?php echo $user_data->id;?>";
            } else {
              swal("Your data is safe!");
            }
          });
          }
          $("[data-toggle=tooltip]").tooltip();

    </script>

