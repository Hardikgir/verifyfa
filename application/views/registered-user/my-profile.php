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
            <h4 class="page-title">My Profile</h4>
       </div>     

			<div class="col-lg-12">
            <form action="<?php echo base_url();?>index.php/update-profile-registred-user" method="post" id="step1">
            <input type="hidden" value="<?php echo $user_data->id;?>" name="register_user_id">
            <section class="text-center">
					<!-- Section heading -->
                    <div class="row">
                <div class="col-md-12 ">
                  <nav>
                    <div class="nav nav-tabs nav-fill p-0" id="nav-tab" role="tablist">
                    <a disabled class="nav-item nav-link active text-left"  >Update Profile</a>

                    </div>
                  </nav>
                  <!-- for user -->
                  <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                     

              <div class="row">
              <div class="col-md-4 form-row">
                    <label class="form-label">Registration No <span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="urer_registration_no" id="urer_registration_no" class="form-control" placeholder="Enter First Name" required value="<?php echo $user_data->urer_registration_no;?>" readonly>
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">First Name <span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter First Name" required value="<?php echo $user_data->first_name;?>">
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">Last Name <span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter Last Name" required value="<?php echo $user_data->last_name;?>">
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-6 form-row">
                    <label class="form-label">Email ID <span style="color:red;font-size: 20px;">*</span></label>
                    <input type="email" name="email_id"  id="email_id"class="form-control" placeholder="Enter Email Id" required  value="<?php echo $user_data->email_id;?>" readonly>
                </div>
                <div class="col-md-6 form-row">
                    <label class="form-label">Phone Number <span style="color:red;font-size: 20px;">*</span></label>
                    <input type="number" name="phone_no" id="phone_no" class="form-control" placeholder="Enter Phone number" required value="<?php echo $user_data->phone_no;?>">
                </div>
             </div>
             <br>

             <div class="row">
                <div class="col-md-4 form-row">
                    <label class="form-label">Organization Name <span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="organisation_name" id="organisation_name" class="form-control" placeholder="Enter Organization Name" required  value="<?php echo $user_data->organisation_name;?>">
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label"> Enter Unique Entity Code (Min 4 Chrs - Max 8 Chrs) <span style="color:red;font-size: 20px;">*</span></label>
                    <input type="text" name="entity_code" id="entity_code" class="form-control" placeholder="Enter Unique Entity Code (Min 4 Chrs - Max 8 Chrs)" required minlength="4" maxlength="8" value="<?php echo $user_data->entity_code;?>" readonly>
                    <p style="color:red;" id="entityalert"></p>
                </div>
                <div class="col-md-4 form-row">
                    <label class="form-label">Is your Organization GST Registered?<span style="color:red;font-size: 20px;">*</span></label>
                    <select class="form-control" id="is_gst" name="is_gst" required>
                    <option value="">Choose Here</option>
                    <option value="1" <?php if($user_data->is_gst=='1'){echo "selected";}?>>Yes</option>
                    <option value="0" <?php if($user_data->is_gst=='0'){echo "selected";}?>>No</option>
                </select>
                </select>
                </div>
             </div>
             <br>
                        <div id="gst_information">
                        <?php if($user_data->is_gst=='1'){?>
                                <div class="row">
                                <div class="col-md-4 form-row">
                                <label class="form-label">Enter GST No<span style="color:red;font-size: 20px;">*</span></label>
                                <input type="text" name="gst_no" id="gst_no" class="form-control" placeholder="Enter GST Number" required value="<?php echo $user_data->gst_no;?>">
                                </div>
                                    <div class="col-md-4 form-row">
                                        <label class="form-label">Name Organization as per GST Records<span style="color:red;font-size: 20px;">*</span></label>
                                        <input type="text"  id="gst_org_name" name="gst_org_name" class="form-control" placeholder="Organization as per GST Records" required value="<?php echo $user_data->name_org_gst;?>">
                                        </div>
                                    <div class="col-md-4 form-row">
                                        <label class="form-label"> Address of Organization as per GST Records<span style="color:red;font-size: 20px;">*</span></label>
                                        <input type="text" id="gst_org_address" name="gst_org_address" class="form-control" placeholder="Address of Organization as per GST Records" required value="<?php echo $user_data->address_org_gst;?>" >
                                    </div>
                                </div>
                                <br>
                        <?php } ?>
                        </div>
                     <button class="btn btn-primary" type="submit">Update Profile</button>
                    </div>
                  </div>
                
                </div>
              </div>
        </div>
        </div>
     </div>
 </section>
 </form>
</div>
</div>
</div>

<?php
$this->load->view('registered-user/layout/scripts');
// $this->load->view('registered-user/layouts/dashboard_script');
$this->load->view('registered-user/layout/footer');
?>
<!-- for second step validation and go to next step -->

<script>
    $("#is_gst").change(function(){
        if($(this).val() == '1'){
        var gstrow='';
        gstrow +='<div class="row">';
        gstrow +=' <div class="col-md-4 form-row">';
        gstrow +='   <label class="form-label">Enter GST No<span style="color:red;font-size: 20px;">*</span></label>';
        gstrow +='    <input type="text" name="gst_no" id="gst_no" class="form-control" placeholder="Enter GST Number" required>';
        gstrow +=' </div>';
                gstrow +='<div class="col-md-4 form-row">';
                gstrow +=' <label class="form-label">Name Organization as per GST Records<span style="color:red;font-size: 20px;">*</span></label>';
                gstrow +='   <input type="text"  id="gst_org_name" name="gst_org_name" class="form-control" placeholder="Organization as per GST Records" required>';
                gstrow +=' </div>';
                gstrow +='<div class="col-md-4 form-row">';
                gstrow +='  <label class="form-label"> Address of Organization as per GST Records<span style="color:red;font-size: 20px;">*</span></label>';
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