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
    /* background: #e74c3c; */
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
            <h4  class="page-title">View User Details</h4>
       </div>     

			<div class="col-lg-12">
            
            <section class="text-center">
					<!-- Section heading -->
                    <div class="row">
                <div class="col-md-12 ">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                      <a disabled class="nav-item nav-link active" id="nav-home-tab" >User Details</a>
                      <a disabled class="nav-item nav-link" id="nav-profile-tab" >Plan Details</a>
                      <a disabled class="nav-item nav-link" id="nav-contact-tab" >Payment Details</a>
                      <a disabled class="nav-item nav-link" id="nav-confirmation-tab">Confirmation Details</a>
                    </div>
                  </nav>
                  <!-- for user -->
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                     

              <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">First Name </label>
                    <?php echo $user_data->first_name;?>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Last Name </label>
                  <?php echo $user_data->last_name;?>
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Email ID </label>
                    <?php echo $user_data->email_id;?>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Phone Number </label>
                   <?php echo $user_data->phone_no;?>
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-4 form-row">
                    <label class="form-label">Organization Name </label>
                   <?php echo $user_data->organisation_name;?>
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label"> Enter Unique Entity Code (Min 4 Chrs - Max 8 Chrs) </label>
                    <?php echo $user_data->entity_code;?>
                    <p style="color:red;" id="entityalert"></p>
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">Is your Organization GST Registered?</label>
                     <?php if($user_data->is_gst=='1'){echo "Yes";}?>
                     <?php if($user_data->is_gst=='0'){echo "No";}?>
                </div>
             </div>
             <br>
<div id="gst_information">
 <?php if($user_data->is_gst=='1'){?>
         <div class="row">
        <div class="col-md-4 form-row">
          <label class="form-label">Enter GST No</label>
         <?php echo $user_data->gst_no;?>
        </div>
               <div class="col-md-4 form-row">
                <label class="form-label">Name Organization as per GST Records</label>
                 <?php echo $user_data->name_org_gst;?>
                </div>
               <div class="col-md-4 form-row">
                 <label class="form-label"> Address of Organization as per GST Records</label>
                  <?php echo $user_data->address_org_gst;?>
               </div>
           </div>
          <br>
 <?php } ?>
</div>
                    <a data-href="nav-profile" class="next1">
                     <button class="btn btn-primary" type="button">Continue</button>
                     </a>
                    </div>
       <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Select Subscription Plan</label>
                    <?php $plan_row=get_plan_row($plan_data->plan_id);?>
                    <?php echo $plan_row->title;?>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Validity of Subscription</label>
                    <?php echo $plan_data->subscription_time_value;?> <?php echo $plan_data->time_subscription;?>
                </div>
                
             </div>
             <br>

             <div class="row">
             <div class="col-md-6 form-row">
                <label class="form-label">Subscription Amount </label>
                <?php echo $plan_row->amount;?>
                </div>

                <div class="col-md-6 form-row">
                <label class="form-label">Period of Subscription </label>
                <?php echo $plan_data->time_subscription;?>
                </div>
               
                
             </div>
             <br>

             <div class="row">
             <div class="col-md-6 form-row">
                    <label class="form-label">Start Date of Subscription </label>
                   <?php echo date('d-M-Y',strtotime($plan_data->plan_start_date));?>
                </div>
                <div class="col-md-6 form-row">
                <label class="form-label">End Date of Subscription </label>
               <?php echo date('d-M-Y',strtotime($plan_data->plan_end_date));?>
                </div>
                
             </div>
             <br>
             <div class="row">
             <div class="col-md-6 form-row">
                    <label class="form-label">Category of Subscription </label>
                     <?php if($plan_data->category_subscription == 'Original'){echo "Original";}?>
                     <?php if($plan_data->category_subscription == 'Upgrade'){echo "Upgrade";}?>
                     <?php if($plan_data->category_subscription == 'Renewal'){echo "Renewal";}?>
                     <?php if($plan_data->category_subscription == 'Reactivation'){echo "Reactivation";}?>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Credit Period (days) of Subcription allowed after expiry (before account suspension)</label>
                    <?php echo $plan_data->credit_days;?>
                </div>
             </div>
             <br>
                    <a data-href="nav-home" class="back">
                    <button class="btn btn-warning back" type="button">Back</button>
                    </a>
                <a data-href="nav-contact" class="next2">
                    <button class="btn btn-primary" type="button"> Next</button>
                  </a>
                    </div>



        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <div class="row">
                <div class="col-md-6 form-row">
                <label class="form-label">Amount of Subscription </label>
                <?php echo ($plan_row->amount * $plan_data->subscription_time_value);?>
               </div>
               <div class="col-md-6 form-row">
                <label class="form-label">Total Discount </label>
                RS. <?php echo get_total_payment_discount_user($user_data->id);?>
                </div>
              
             </div>
             <br>

             <div class="row">
              

                <div class="col-md-6 form-row">
                <label class="form-label">Total Other Charges </label>
                <?php echo get_total_payment_charges_user($user_data->id);?>
              </div>

                <div class="col-md-6 form-row">
                    <label class="form-label">Total Paid Amount</label>
                   RS. <?php echo get_total_payment_user($user_data->id);?>
                </div>
            </div>
                <br>

             <div class="row">
                <div class="col-md-4 form-row">
                    <label class="form-label">Balance Due</label>
                    <?php echo $user_data->balance_due;?>
                </div>
             </div>
             <br>

           
                    <a data-href="nav-profile" class="back">
                    <button class="btn btn-warning"  type="button">Back</button>
                    </a>
                    <a href="<?php echo base_url();?>index.php/confirmation-user-detail/<?php echo $user_data->id;?>">
                      <button class="btn btn-primary" type="button">Continue to Confirmation Details</button>
                    </a>
                    </div>
                   
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
  $(".back").click(function(){
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
<!-- for first step validation and go to next step -->
<script> 
    $(".next1").click(function(){
        if($("#first_name").val()==''){
            $("#first_name").css('border','1 px solid red');
            $("#first_name").focus();
            return false;
        }
        if($("#last_name").val()==''){
            $("#last_name").css('border','1 px solid red');
            $("#last_name").focus();
            return false;
        }
        if($("#email_id").val()==''){
            $("#email_id").css('border','1 px solid red');
            $("#email_id").focus();
            return false;
        }
        if($("#phone_no").val()==''){
            $("#phone_no").css('border','1 px solid red');
            $("#phone_no").focus();
            return false;
        }
        if($("#organisation_name").val()==''){
            $("#organisation_name").css('border','1 px solid red');
            $("#organisation_name").focus();
            return false;
        }
        if($("#entity_code").val()==''){
            $("#entity_code").css('border','1 px solid red');
            $("#entity_code").focus();
            return false;
        }
        
        if($("#is_gst").val()==''){
            $("#is_gst").css('border','1 px solid red');
            $("#is_gst").focus();
            return false;
        }
        if($("#is_gst").val() == 1){
            if($("#gst_no").val()==''){
                $("#gst_no").css('border','1 px solid red');
                $("#gst_no").focus();
                return false;
            }
            if($("#gst_org_name").val()==''){
                $("#gst_org_name").css('border','1 px solid red');
                $("#gst_org_name").focus();
                return false;
            }
            if($("#gst_org_address").val()==''){
                $("#gst_org_address").css('border','1 px solid red');
                $("#gst_org_address").focus();
                return false;
            }
        }
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
<!-- for first step validation and go to next step -->


<!-- for second step validation and go to next step -->
<script> 
    $(".next2").click(function(){
        if($("#plan").val()==''){
            $("#plan").css('border','1 px solid red');
            $("#plan").focus();
            return false;
        }
        if($("#validity").val()==''){
            $("#validity").css('border','1 px solid red');
            $("#validity").focus();
            return false;
        }
        if($("#period").val()==''){
            $("#period").css('border','1 px solid red');
            $("#period").focus();
            return false;
        }
        if($("#start_date").val()==''){
            $("#start_date").css('border','1 px solid red');
            $("#start_date").focus();
            return false;
        }
        if($("#plan_end_date").val()==''){
            $("#plan_end_date").css('border','1 px solid red');
            $("#plan_end_date").focus();
            return false;
        }
        if($("#category_subscription").val()==''){
            $("#category_subscription").css('border','1 px solid red');
            $("#category_subscription").focus();
            return false;
        }
        if($("#credit_days").val()==''){
            $("#credit_days").css('border','1 px solid red');
            $("#credit_days").focus();
            return false;
        }
      
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
<!-- for second step validation and go to next step -->

<script>
    $("#is_gst").change(function(){

        if($(this).val() == '1'){
        var gstrow='';
        gstrow +='<div class="row">';
        gstrow +=' <div class="col-md-4 form-row">';
        gstrow +='   <label class="form-label">Enter GST No</label>';
        gstrow +='    <input type="text" name="gst_no" id="gst_no" class="form-control" placeholder="Enter GST Number" required>';
        gstrow +=' </div>';
                gstrow +='<div class="col-md-4 form-row">';
                gstrow +=' <label class="form-label">Name Organization as per GST Records</label>';
                gstrow +='   <input type="text"  id="gst_org_name" name="gst_org_name" class="form-control" placeholder="Organization as per GST Records" required>';
                gstrow +=' </div>';
                gstrow +='<div class="col-md-4 form-row">';
                gstrow +='  <label class="form-label"> Address of Organization as per GST Records</label>';
                gstrow +='   <input type="text" id="gst_org_address" name="gst_org_address" class="form-control" placeholder="Address of Organization as per GST Records" required>';
                gstrow +='</div>';
                gstrow +=' </div>';
                gstrow +='<br>';
             $("#gst_information").html(gstrow);
        }else{
            $("#gst_information").html('');

        }
    })
</script>
<script>
     function get_plandata(){
        if($("#plan").val() !='' && $("#validity").val() !='' && $("#period").val() !='' && $("#start_date").val() !='' ){
          var plan_id=$("#plan").val();
          var validity=$("#validity").val();
          var period=$("#period").val();
          var start_date=$("#start_date").val();
            $.post("<?php echo base_url();?>index.php/calculate-user-plan", {'plan_id': plan_id,'validity':validity,'period':period,'start_date':start_date}, function(result){
               var res= $.parseJSON(result);
                $.each(res , function(index, item){ 
                    $("#plan_end_date").val(item.end_date);
                    $("#total_amount_payble").val(item.total_pament_amount);
                    $("#amount_subs_due").val(item.total_pament_amount);
                    $("#totalamounthidden").val(item.total_pament_amount);
                });
            
        })
        
    }
}
    $("#plan").change(function(){
        get_plandata();
    })
    $("#validity").change(function(){
        get_plandata();
    })
    
    $("#period").change(function(){
        get_plandata();
    })
    
    $("#start_date").change(function(){
        get_plandata();
    })
    
   
</script>


<script>
    function calculate_payment(){
     var other_charge = $("#paymentother_charge").val();
     var other_charge1 = parseFloat(other_charge);
    var discount_credits=   $("#discount_credits").val();
    var discount_credits1 = parseInt(discount_credits);
    var total_amount_payble=   $("#totalamounthidden").val();
    var total_amount_payble1 = parseInt(total_amount_payble);
    console.log("1"+discount_credits1);
    console.log("2"+other_charge1);
    console.log("3"+total_amount_payble1);
    var total_paybleamt = ((total_amount_payble1  + other_charge1) + discount_credits1);
    $("#total_amount_payble").val(total_paybleamt);
    }

$("#paymentother_charge").change(function(){
    calculate_payment();
})

$("#discount_credits").change(function(){
    calculate_payment();
})


$("#payment_amount").change(function(){
   var totalamount_pay=  $("#total_amount_payble").val();
   var totalamount_pay1 = parseInt(totalamount_pay);
   var payment_amount=  $("#payment_amount").val();
   var payment_amount1= parseInt(payment_amount);

   var finalrefund = (totalamount_pay1 - payment_amount1);
   $("#balance_refundable").val(finalrefund);

})

</script>

<script>
$("#entity_code").keyup(function(){
    $('#entityalert').html("minimum 4 and maximum 8 char allowed");

    $('#entity_code').unbind('keyup change input paste').bind('keyup change input paste',function(e){
    var $this = $(this);
    var val = $this.val();
    var valLength = val.length;
    var maxCount = $this.attr('maxlength');
    if(valLength>maxCount){
        $this.val($this.val().substring(0,maxCount));
    }
}); 
})
</script>

<script>
 $(function(){
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var minDate= year + '-' + month + '-' + day;

    $('#start_date').attr('min', minDate);
    // $('#validity_to').attr('min', minDate);

});
</script>

<script>
function forceLimit(val) {
   var balance= $("#amount_subs_due").val();
   var limit = '-'+balance;
   if(val > limit && val < 0){
   $("#alertdiscountcredit").html("You are not allow less than "+ balance + " discounts");
    $("#discount_credits").val(limit);
   }else{
    $("#alertdiscountcredit").html("");
   }
}
</script>


