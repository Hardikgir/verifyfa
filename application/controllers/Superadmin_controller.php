<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin_controller extends CI_Controller {
	public function __construct() {
		parent::__construct();		
		$this->load->library('session');	
		if (!$this->session->userdata('super_admin_logged_in')) {
            redirect(base_url()."index.php/super-admin-login", 'refresh');
		}
		$this->load->model('Super_admin_model');
	}

    public function super_admin_dashboard(){
        $data['page_title']="Dashboard";
        $this->load->view("super-admin/dashboard",$data);

    }

	public function mange_subscription(){
        $data['page_title']="Manage Subscription";
		$data['plan']=$this->Super_admin_model->list_subscription_plan();
        $this->load->view("super-admin/manage-subscription",$data);

    }

	public function create_subscription(){
        $data['page_title']="Manage Subscription";
        $this->load->view("super-admin/create-subscription",$data);

    }
	public function save_subscription(){
		$data= array(
			"title"=>$this->input->post('title'),
			"subtitle"=>$this->input->post('subtitle'),
			"time_subscription"=>$this->input->post('time_subscription'),
			"description"=>$this->input->post('description'),
			"allowed_entities_no"=>$this->input->post('allowed_entities_no'),
			"location_each_entity"=>$this->input->post('location_each_entity'),
			"user_number_register"=>$this->input->post('user_number_register'),
			"line_item_avaliable"=>$this->input->post('line_item_avaliable'),
			"highlights"=>$this->input->post('highlights'),
			"validity_from"=>date("Y-m-d",strtotime($this->input->post('validity_from'))),
			"validity_to"=>date("Y-m-d",strtotime($this->input->post('validity_to'))),
			"amount"=>$this->input->post('amount'),
			"about_payment"=>$this->input->post('about_payment'),
			"terms_condition"=>$this->input->post('terms_condition'),
			"foot_notes"=>$this->input->post('foot_notes'),
			"created_at"=>date("Y-m-d H:i:s")
			);
			$this->Super_admin_model->save_subscription_plan($data);
			$this->session->set_flashdata('success', 'Plan Addedd Successfully');
			redirect("index.php/manage-subscription");
	}

	public function edit_subscription($id){
        $data['page_title']="Manage Subscription";
		$data['plan']=$this->Super_admin_model->get_subscription_plan_single($id);
        $this->load->view("super-admin/edit-subscription",$data);
    }

	public function edit_save_subscription(){
		$id = $this->input->post('id');
		$data= array(
			"title"=>$this->input->post('title'),
			"time_subscription"=>$this->input->post('time_subscription'),
			"subtitle"=>$this->input->post('subtitle'),
			"description"=>$this->input->post('description'),
			"allowed_entities_no"=>$this->input->post('allowed_entities_no'),
			"location_each_entity"=>$this->input->post('location_each_entity'),
			"user_number_register"=>$this->input->post('user_number_register'),
			"line_item_avaliable"=>$this->input->post('line_item_avaliable'),
			"highlights"=>$this->input->post('highlights'),
			"validity_from"=>date("Y-m-d",strtotime($this->input->post('validity_from'))),
			"validity_to"=>date("Y-m-d",strtotime($this->input->post('validity_to'))),
			"amount"=>$this->input->post('amount'),
			"about_payment"=>$this->input->post('about_payment'),
			"terms_condition"=>$this->input->post('terms_condition'),
			"foot_notes"=>$this->input->post('foot_notes'),
			"created_at"=>date("Y-m-d H:i:s")
			);
			$this->Super_admin_model->update_subscription_plan($data,$id);
			$this->session->set_flashdata('success', 'Plan Updated Successfully');
			redirect("index.php/edit-subscription/".$id);
	}
	
	public function view_subscription($id){
		$data['page_title']="Manage Subscription";
		$data['plan']=$this->Super_admin_model->get_subscription_plan_single($id);
        $this->load->view("super-admin/view-subscription",$data);
	}

	public function inactive_plan($id){
		$date_yesterday=date('Y-m-d',strtotime("-1 days"));
		$data=array("validity_to"=>$date_yesterday);
		$this->Super_admin_model->inactive_plan($id,$data);
		$this->session->set_flashdata('success', 'Plan Inactivated Successfully');
		redirect("index.php/manage-subscription");
	}

	public function active_plan($id){
		$data=array("status"=>"1");
		$this->Super_admin_model->active_plan($id,$data);
		$this->session->set_flashdata('success', 'Plan Activated Successfully');
		redirect("index.php/manage-subscription");
	}

	public function manage_user(){
		$data['page_title']="Manage User";
		$data['user_data']=$this->Super_admin_model->get_registered_users();
        $this->load->view("super-admin/manage-user",$data);
	}

	public function select_plan(){
		$data['page_title']="Manage User";
		$data['plan']=$this->Super_admin_model->get_active_plan();
        $this->load->view("super-admin/select-plan",$data);
	}

	public function create_user($plan_id){
		$data['page_title']="Manage User";
		$data['plan']=$this->Super_admin_model->get_plan_single($plan_id);
        $this->load->view("super-admin/create-user",$data);
	}

	public function calculate_user_plan(){
		$plan_id=$this->input->post('plan_id');
		$validity=$this->input->post('validity');
		$period=$this->input->post('period');
		$start_date=$this->input->post('start_date');
		if($period == 'days'){
			 $addduration= $validity." days";
			}
		if($period == 'month'){  
			$addduration= $validity." months";
			}
		if($period == 'year'){  
			 $addduration= $validity." years";
			}

      // Add days to date and display it
        $end_date = date('Y-m-d', strtotime($start_date. ' + '.$addduration));
		$plan = $this->Super_admin_model->get_subscription_plan_row($plan_id);
		$total_amount = ($plan->amount * $validity);
	
		$dataarr[]=array(
			"end_date"=>$end_date,
			"total_pament_amount"=>$total_amount,
	      );
        echo json_encode($dataarr);
	}
	
	public function test(){
		$data['page_title']="Manage User";
        $this->load->view("super-admin/test",$data);
	}
	public function confirmation_userdetail($id){
		$data['page_title']="Manage User";
		 $data['user']=$this->Super_admin_model->get_registerd_user($id);
        $this->load->view("super-admin/confirmation-user-detail",$data);
	}

	public function save_registred_user(){
	
		if($this->input->post('is_gst') == '1'){
			$gst_no=$this->input->post('gst_no');
			$gst_org_name=$this->input->post('gst_org_name');
			$gst_org_address=$this->input->post('gst_org_address');
		}else{
			$gst_no='';
			$gst_org_name='';
			$gst_org_address='';
		}
		// $password=rand(0,9).rand(9,0).rand(0,9).rand(0,9);
		$password='12345';		//Hardik Fix Password Generate From Here
		
		$urer_registration_no=date("Ym")."VFA".rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$data_user= array(
			"urer_registration_no"=>$urer_registration_no,
			"password"=>md5($password),
			"password_view"=>$password,
			"balance_due"=>$this->input->post('balance_refundable'),
			"plan_id"=>$this->input->post('plan'),
			"first_name"=>$this->input->post('first_name'),
			"last_name"=>$this->input->post('last_name'),
			"email_id"=>$this->input->post('email_id'),
			"phone_no"=>$this->input->post('phone_no'),
			"organisation_name"=>$this->input->post('organisation_name'),
			"entity_code"=>$this->input->post('entity_code'),
			"is_gst"=>$this->input->post('is_gst'),
			"gst_no"=>$gst_no,
			"name_org_gst"=>$gst_org_name,
			"address_org_gst"=>$gst_org_address,
			"created_at"=>date("Y-m-d H:i:s")
		);
	$registered_user_id= $this->Super_admin_model->save_registered_user($data_user);


//step 2 data save//
	$data_plan= array(
		"regiistered_user_id"=>$registered_user_id,
		"plan_id"=>$this->input->post('plan'),
		"subscription_time_value"=>$this->input->post('subscription_time_value'),
		"time_subscription"=>$this->input->post('time_subscription'),
		"plan_start_date"=>date("Y-m-d",strtotime($this->input->post('plan_start_date'))),
		"plan_end_date"=>date("Y-m-d",strtotime($this->input->post('plan_end_date'))),
		"category_subscription"=>$this->input->post('category_subscription'),
		"credit_days"=>$this->input->post('credit_days'),
		"created_at"=>date("Y-m-d H:i:s")
	);
	$this->Super_admin_model->save_registered_user_plan($data_plan);
//step 2 data save//

// ??step 3 data save//
$transection_id=date("YmdHis").rand(0,9).rand(0,9).rand(0,9);
	$data_payment= array(
		"regiistered_user_id"=>$registered_user_id,
		"plan_id"=>$this->input->post('plan'),
		"amount_subs_due"=>$this->input->post('amount_subs_due'),
		"paymentother_charge"=>$this->input->post('paymentother_charge'),
		"discount_credits"=>$this->input->post('discount_credits'),
		"total_amount_payble"=>$this->input->post('total_amount_payble'),
		"payment_amount"=>$this->input->post('payment_amount'),
		"balance_refundable"=>$this->input->post('balance_refundable'),
		"payment_date"=>$this->input->post('payment_date'),
		"mode_of_payment"=>$this->input->post('mode_of_payment'),
		"transection_ref"=>$this->input->post('transection_ref'),
		"payment_remarks"=>$this->input->post('payment_remarks'),
		"transection_id"=>$transection_id,
		"created_at"=>date("Y-m-d H:i:s")
	);
	$this->Super_admin_model->save_registered_user_payment($data_payment);


	// Hardik Comment :- Email Should be Send from here.
	// Email Send While Adding
	/*
	$toemail = $this->input->post('email_id');
	$password_view=$password;
	$firstname=$this->input->post('first_name');
	$lastname=$this->input->post('last_name');

	// $activation_link=$userdata->activation_link;
	$activation_link = base_url().'index.php/send-activation-link/'.$registered_user_id;
	$subject="verifyfa.com Activation Account Email";
	$login_link= base_url()."index.php/registered-user-login";
	$message="Dear ".$firstname." ". $lastname."<br> <br> Your account activation link given below. Click below given link and activate your account.<br> Once you are activate your account login your account with given login credentials.<br> <b>Activate your account:</b> <a href='".$activation_link."' target='_blank'>Click Here</a>.<br><br>
	<b>Login Url:</b> <a href='".$login_link."' target='_blank' >Click Here For login</a>
	<b>Email-Id:</b> ".$toemail."
	<b>Password:</b> ".$password_view."<br><br>Thank You.";
	$this->sent_email($toemail,$subject,$message);
	*/
	
	$this->session->set_flashdata('success', 'User Created Successfully Now you Are In Confirmation Page');
	redirect("index.php/confirmation-user-detail/".$registered_user_id);
	}
 	// ??step 3 data save//




 public function generate_activation_link($id){
	$date = date("Y-m-d");
   // Use date_add() function to add date object
   $activation_link = base_url().'index.php/activation-registered-user/'.$id;

    $expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));
	$data=array(
        "activation_generete_link"=>"1",
		"activation_generete_link_date"=>$date,
		"activation_link"=>$activation_link,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);

	 $this->session->set_flashdata('success', 'Link Generated Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }


 public function send_activation_link($id){

	$date = date("Y-m-d");
	$expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));
	$login_link= base_url()."index.php/registered-user-login";
	$data=array(
        "is_activation_send"=>"1",
		"activation_send_date"=>$date,
		"link_expiry_date"=>$expire_date,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);

	 $userdata=$this->Super_admin_model->get_registerd_user($id);

	 $toemail=$userdata->email_id;
	 $password_view=$userdata->password_view;
	 $firstname=$userdata->first_name;
	 $lastname=$userdata->last_name;
     $activation_link=$userdata->activation_link;
     $subject="verifyfa.com Activation Account Email";

	 $message="Dear ".$firstname." ". $lastname."<br> <br> Your account activation link given below. Click below given link and activate your account.<br> Once you are activate your account login your account with given login credentials.<br> <b>Activate your account:</b> <a href='".$activation_link."' target='_blank'>Click Here</a>.<br><br>
	 <b>Login Url:</b> <a href='".$login_link."' target='_blank' >Click Here For login</a>
	 <b>Email-Id:</b> ".$toemail."
	 <b>Password:</b> ".$password_view."<br><br>Thank You.";
	 $this->sent_email($toemail,$subject,$message);


	 $this->session->set_flashdata('success', 'Activation Link Send Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }

 public function regenerate_activation_link($id){
	$date = date("Y-m-d");
	$data=array(
		"regenerate_activation_date"=>$date,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);
	 $this->session->set_flashdata('success', 'Link Re Generated Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }
 
 public function regenerate_activation_send_link($id){
	$date = date("Y-m-d");
	$expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));

	$data=array(
        "is_activation_send"=>"1",
		"activation_send_date"=>$date,
		"link_expiry_date"=>$expire_date,
		"regenrred_link_send_date"=>$date
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);

	 $userdata=$this->Super_admin_model->get_registerd_user($id);

	 $toemail=$userdata->email_id;
	 $password_view=$userdata->password_view;
	 $firstname=$userdata->first_name;
	 $lastname=$userdata->last_name;
     $activation_link=$userdata->activation_link;
     $subject="verifyfa.com Activation Account Email";

	 $message="Dear ".$firstname." ". $lastname."<br> <br> Your account activation link given below. Click below given link and activate your account.<br> Once you are activate your account login your account with given login credentials.<br> <b>Activate your account:</b> <a href='".$activation_link."' target='_blank'>Click Here</a>.<br><br>
	 <b>Login Url:</b> <a href='".$login_link."' target='_blank' >Click Here For login</a>
	 <b>Email-Id:</b> ".$toemail."
	 <b>Password:</b> ".$password_view."<br><br>Thank You.";
	 $this->sent_email($toemail,$subject,$message);


	 $this->session->set_flashdata('success', 'Activation Link Send Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }

 public function suspend_account($id){
	$date = date("Y-m-d");
	$expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));

	$data=array(
        "is_active"=>"5",
		"suspend_date"=>$date,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);

	 $this->session->set_flashdata('success', 'Activation Suspended Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }


 public function unsubscribe_account($id){
	$date = date("Y-m-d");
	$data=array(
        "is_active"=>"6",
		"unsubscribe_date"=>$date,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);
	 $this->session->set_flashdata('success', 'Activation Suspended Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }

 public function reactive_account($id){
	$date = date("Y-m-d");
	$data=array(
        "is_active"=>"4",
        "is_resubscribe_request"=>"4",
        "is_resubscribe_request_at"=>"",
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);
	 $this->session->set_flashdata('success', 'Activation Reactivate Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }

 public function edit_user($id){
	$data['page_title']="Manage User";
	$data['plan']=$this->Super_admin_model->get_active_plan();
	$data['user_data']=$this->Super_admin_model->get_registerd_user($id);
	$data['payment_data']=$this->Super_admin_model->get_registred_users_payment($id);
	$data['plan_data']=$this->Super_admin_model->get_registered_user_plan($id);
	$this->load->view("super-admin/edit-user",$data);
 }
 

 
 public function view_user($id){
	$data['page_title']="Manage User";
	$data['plan']=$this->Super_admin_model->get_active_plan();
	$data['user_data']=$this->Super_admin_model->get_registerd_user($id);
	$data['payment_data']=$this->Super_admin_model->get_registred_users_payment($id);
	$data['plan_data']=$this->Super_admin_model->get_registered_user_plan($id);


	
	$this->db->select('register_user_plan_log.*, subscription_plan.*');
	$this->db->from(' subscription_plan');
	$this->db->join('register_user_plan_log','register_user_plan_log.plan_id= subscription_plan.id');
	$this->db->where('register_user_plan_log.register_user_id',$id);
	$getnotifications=$this->db->get();
	$result = $getnotifications->result();
	$data['payment_history'] = $result;

	
	$this->load->view("super-admin/view-user",$data);
 }
 


 public function save_edit_registred_user(){
	
	$registered_user_id=$this->input->post('register_user_id');
	if($this->input->post('is_gst') == '1'){
		$gst_no=$this->input->post('gst_no');
		$gst_org_name=$this->input->post('gst_org_name');
		$gst_org_address=$this->input->post('gst_org_address');
	}else{
		$gst_no='';
		$gst_org_name='';
		$gst_org_address='';
	}
	$data_user= array(
		"renew_request"=>'0',
		"request_renew_at"=>'',
		"balance_due"=>$this->input->post('balance_refundable'),
		"plan_id"=>$this->input->post('plan'),
		"first_name"=>$this->input->post('first_name'),
		"last_name"=>$this->input->post('last_name'),
		"email_id"=>$this->input->post('email_id'),
		"phone_no"=>$this->input->post('phone_no'),
		"organisation_name"=>$this->input->post('organisation_name'),
		"entity_code"=>$this->input->post('entity_code'),
		"is_gst"=>$this->input->post('is_gst'),
		"gst_no"=>$gst_no,
		"name_org_gst"=>$gst_org_name,
		"address_org_gst"=>$gst_org_address,
		"updated_at"=>date("Y-m-d H:i:s")
	);
$this->Super_admin_model->edit_save_registered_user($registered_user_id,$data_user);


//step 2 data save//
$data_plan= array(
	"regiistered_user_id"=>$registered_user_id,
	"plan_id"=>$this->input->post('plan'),
	"subscription_time_value"=>$this->input->post('subscription_time_value'),
	"time_subscription"=>$this->input->post('time_subscription'),
	"plan_start_date"=>date("Y-m-d",strtotime($this->input->post('plan_start_date'))),
	"plan_end_date"=>date("Y-m-d",strtotime($this->input->post('plan_end_date'))),
	"category_subscription"=>$this->input->post('category_subscription'),
	"credit_days"=>$this->input->post('credit_days'),
	"created_at"=>date("Y-m-d H:i:s")
);
$this->Super_admin_model->edit_save_registered_user_plan($registered_user_id,$data_plan);
//step 2 data save//

// ??step 3 data save//
if($this->input->post('payment_amount') !=''){
	$transection_id=date("YmdHis").rand(0,9).rand(0,9).rand(0,9);

$data_payment= array(
	"regiistered_user_id"=>$registered_user_id,
	"plan_id"=>$this->input->post('plan'),
	"amount_subs_due"=>$this->input->post('amount_subs_due'),
	"paymentother_charge"=>$this->input->post('paymentother_charge'),
	"discount_credits"=>$this->input->post('discount_credits'),
	"total_amount_payble"=>$this->input->post('total_amount_payble'),
	"payment_amount"=>$this->input->post('payment_amount'),
	"balance_refundable"=>$this->input->post('balance_refundable'),
	"payment_date"=>$this->input->post('payment_date'),
	"mode_of_payment"=>$this->input->post('mode_of_payment'),
	"transection_ref"=>$this->input->post('transection_ref'),
	"payment_remarks"=>$this->input->post('payment_remarks'),
	"transection_id"=>$transection_id,
	"created_at"=>date("Y-m-d H:i:s")
);
$this->Super_admin_model->save_registered_user_payment($data_payment);


$data_array=array(
"plan_id"=>0,
"upgrated_plan_id"=>$this->input->post('plan'),
"register_user_id"=>$registered_user_id,
"created_at"=>date("Y-m-d H:i:s"),
);
$this->Super_admin_model->save_upgradePlan($data_array);



}
$this->session->set_flashdata('success', 'User Details Updated Successfully Now you Are In Confirmation Page');
redirect("index.php/confirmation-user-detail/".$registered_user_id);

}

 //Email Function//
 public function sent_email($toemail,$subject,$message){
	$to = "hardik.meghnathi12@gmail.com"; 
	$from = 'support@verifyfa.com'; 
	$fromName = 'Verifyfa';  
	// Additional headers 
  

	$headers = 'From: '.$fromName.'<'.$from.'>'; 
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	// Send email 
	if(mail($to, $subject, $message, $headers)){ 
	   echo 'Email has sent successfully.'; 
	}else{ 
		
		echo 'Email sending failed.'; 
	}
}
//Email Function//
 

//check subscription exist behalf on title//
public function checksubscription(){
	$title = $this->input->post('title');
	$restitle = $this->Super_admin_model->checksubscription_title($title);
	echo $restitle;

}

//check useremail exist behalf on email//
public function checkuseremail(){
	$email_id = $this->input->post('email_id');
	$restitle = $this->Super_admin_model->checkuser_email($email_id);
	echo $restitle;

}
//check checkentitycode exist behalf on entitycode//
public function checkentitycode(){
	$entity_code = $this->input->post('entity_code');
	$restitle = $this->Super_admin_model->checkuser_entitycode($entity_code);
	echo $restitle;

}


//check checkentitycode exist behalf on entitycode//
public function upgrade_plan($register_user_id){
	$data['page_title']="Upgrade User";
	$datauser=$this->Super_admin_model->get_registerd_user($register_user_id);
	$data['user']=$this->Super_admin_model->get_registerd_user($register_user_id);
	$data['plan']=$this->Super_admin_model->get_active_plan_upgrade($datauser->plan_id);
	$this->load->view("super-admin/upgrade-plan",$data);

}

public function upgrade_plan_save(){
	$plan_id=$this->input->post('plan_id');
	$upgrated_plan_id=$this->input->post('upgrade_plan_id');
	$register_user_id=$this->input->post('register_user_id');

	$data_array=array(
	"plan_id"=>$plan_id,
	"upgrated_plan_id"=>$upgrated_plan_id,
	"register_user_id"=>$register_user_id,
	"created_at"=>date("Y-m-d H:i:s"),
	);

	$data_array1=array(
		"plan_id"=>$upgrated_plan_id,
	);
	$this->Super_admin_model->save_upgradePlan($data_array);
	$this->Super_admin_model->update_plan($data_array1,$register_user_id);
	$this->Super_admin_model->update_plan_plan($data_array1,$register_user_id);
	$this->session->set_flashdata('success', 'User Plan Upgrated Successfully Done');
	redirect("index.php/confirmation-user-detail/".$register_user_id);

}


}

