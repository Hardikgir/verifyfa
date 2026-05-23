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
  background: #e74c3c;
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
            <h4>User Confirmation Details</h4>
       </div>     

			<div class="col-lg-12">
				<section class="text-center">
					<!-- Section heading -->
            <div class="row">
                <div class="col-md-12 ">
                  <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link " id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">User Details</a>
                      <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Plan Details</a>
                      <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Payment Details</a>
                      <a class="nav-item nav-link active" id="nav-confirmation-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Confirmation Details</a>
                    </div>
                  </nav>
                  <!-- for user -->
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    
         <div class="tab-pane fade show active" id="nav-confirmation" role="tabpanel" aria-labelledby="nav-contact-tab">
             <div class="row">
                <div class="col-md-6 form-row">
                 <button class="btn btn-success">Genrete Activation Link</button>
                </div>
                <div class="col-md-6 form-row">
                <button class="btn btn-success">Send Activation Link</button>

                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-6 form-row">
                <label class="form-label">Date of Sending Activation Link:</label>
                 2022-12-10    
            </div>
                <div class="col-md-6 form-row">
                <label class="form-label">Registration Number Allotted (Unique):</label>
                1234566
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-6 form-row">
                <label class="form-label">Status of Subscription Account:</label>
                <select class="form-control" id="status_subscription_account" name="status_subscription_account">
                    <option value="0">Waiting for Confirmation</option>
                    <option value="1">Link Expired</option>
                    <option value="2">Requested Regeneration</option>
                    <option value="4">Activated</option>
                    <option value="5">Suspended</option>
                    <option value="6">Unsubscribed</option>
                </select>
            </div>
             <div class="col-md-6 form-row">
                <label class="form-label">Date of Activating Subscription Account</label>
                2022-10-01
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-6 form-row">
                <label class="form-label">Date of Link Expiration: </label>
                2022-10-01
            </div>
             <div class="col-md-6 form-row">
                <label class="form-label">Date of Request for Link Regeneration </label>
                2022-10-01
                </div>
             </div>
             <br>
            <div class="row">
                <div class="col-md-6 form-row">
                 <button class="btn btn-success">Re-Generate Activation Link</button>
                </div>
                <div class="col-md-6 form-row">
                <label class="form-label">Date of Sending Activation Link (Regenerated)</label>
                2022-10-01
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-6 form-row">
                <label class="form-label">Date of Account Suspension</label>
                </div>
                <div class="col-md-6 form-row">
                <button class="btn btn-success">Re-activate Subscription Account</button>
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-6 form-row">
                 <label  class="form-label">Date on which Account Unsubscribed</label>
                 2022-10-01
                </div>
                <div class="col-md-6 form-row">
                <button class="btn btn-success">Suspend Account</button>
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-4 form-row">
                <label class="form-label">Date of Account Suspension by Admin</label>
                2022-10-01
                </div>
                <div class="col-md-4 form-row">
                <button class="btn btn-success">Cancel Account Subscription (by Admin)</button>

                </div>
                <div class="col-md-4 form-row">
                <label class="form-label">Date of Account Cancellation by Admin</label>
                2022-10-01
                </div>
             </div>
             <br>
                       
            
                                <button class="btn btn-warning back">Back</button>
                              
                                <button class="btn btn-primary next">Submit</button>
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
