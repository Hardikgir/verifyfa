<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Calcutta"); 
		$this->load->model('login_model','login');	
		$this->load->model('Super_admin_model');
		$this->load->model('Admin_model');
		$this->load->model('Registered_user_model');
		
	}
	public function index()
	{
		$this->data['title']="VerifyFa Login";
		if ($this->session->userdata('logged_in')!='') {
			$session=$this->session->userdata('logged_in');
			redirect(base_url()."index.php/dashboard", 'refresh');
		}
		else
		{
			$this->load->view('login',$this->data);
		}
		
	}
	public function login()
	{
		$email=$this->input->post('userEmail');
		$password=$this->input->post('userPassword');
		$remember = $this->input->post('remember_me');
		$entity_code = $this->input->post('entity_code');
		
		$condition=array(
			"userEmail"=>$email,
			"password"=>md5($password),
			"entity_code"=>$entity_code
		);
		
		$login=$this->login->get_data('users',$condition);

	
		if(!empty($login) && count($login) > 0)
		{
			$this->db->select('*');
            $this->db->from('registered_user_plan');
            $this->db->where('id',$login[0]->registered_user_id);
            $query = $this->db->get();
            $registered_user_plan_result= $query->row();
		
			$today = date("Y-m-d"); // current date
			if ($today > $registered_user_plan_result->plan_end_date) {
				$this->session->set_flashdata('error_message', 'This user subscription plan has been expired.');
				redirect(base_url());
				exit();
			}
			

			if($login[0]->registered_user_id == '1'){
				$this->session->set_flashdata('error_message', 'This User is already logged in. Contact your Group Admin to Reset the session, if required.');
				redirect(base_url(),$condition);
			}


			if($login[0]->is_login == '1'){
				$this->session->set_flashdata('error_message', 'This User is already logged in. Contact your Group Admin to Reset the session, if required.');
				redirect(base_url(),$condition);
			}

			if ($remember)
			{
				$this->session->set_userdata('remember_me', TRUE);
			}
			$sess_data = array(
			'email' => $login[0]->userEmail,
			'name' => $login[0]->firstName.' '.$login[0]->lastName,
			'id' => $login[0]->id,
			'company_id'=>$login[0]->company_id,
			'admin_registered_user_id'=>$login[0]->registered_user_id,
			'admin_registered_entity_code'=>$login[0]->entity_code,
			'main_role'=>$login[0]->userRole
			);
			$this->session->set_userdata('logged_in', $sess_data);

			$updatedata=array(
				'is_login'=>1,
			);
			$condition=array(
				'id'=>$login[0]->id
			);
			$update=$this->login->update_data('users ',$updatedata,$condition);	

			if($_SESSION['logged_in']['main_role'] == '0'){
				redirect(base_url()."index.php/dashboard/user");
			}

			redirect(base_url()."index.php/dashboard/admin");
		} 
		else {
		
			$this->session->set_flashdata('error_message', 'Invalid Email or Password');
			redirect(base_url(),$condition);
		}
	}
	public function logout()
	{
		$updatedata=array(
			'is_login'=>0,
		);
		$condition=array(
			'id'=>$_SESSION['logged_in']['id']
		);
		$update=$this->login->update_data('users ',$updatedata,$condition);	

		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		redirect(base_url()."index.php/login",'refresh');
		
	}


	//for super admin//

	public function super_admin_login()
	{
		$this->data['title']="VerifyFa Super Admin Login";
		if ($this->session->userdata('super_admin_logged_in')!='') {
			$session=$this->session->userdata('super_admin_logged_in');
			redirect(base_url()."index.php/super-admin-dashboard", 'refresh');
		}
		else
		{
			$this->load->view('super-admin/login',$this->data);
		}
	}

	public function super_admin_login_check(){
		$data= $this->login->checksuperadmin_login();
		if($data == 1){
			redirect("index.php/super-admin-dashboard");
		}else{

			$this->session->set_flashdata('error_message', 'Invalid Email or Password');
			redirect("index.php/super-admin-login");
		}

	}
	//for super admin//




	//for registered user//

	public function registered_user_login()
	{
		$this->data['title']="VerifyFa Registered User Login";
		
		$this->load->view('registered-user/login',$this->data);
		
	}

	public function registered_user_login_check(){
		$email=$this->input->post('email');
		$password=md5($this->input->post('password'));

		$this->db->select('*');
		$this->db->from('registred_users');
		$this->db->where('email_id',$email);
		$this->db->where('password',$password);
		$query = $this->db->get();
		$user_result= $query->row();
		$num = $query->num_rows();



		$this->db->select('
			register_user_plan_log.*,
			subscription_plan.title,
			subscription_plan.subtitle,
			subscription_plan.amount,
			subscription_plan.user_number_register,
			subscription_plan.allowed_entities_no,
			subscription_plan.location_each_entity,
			subscription_plan.user_number_register,
			subscription_plan.line_item_avaliable,
			subscription_plan.time_subscription
		');




		$this->db->from('register_user_plan_log');

		$this->db->join(
			'subscription_plan',
			'subscription_plan.id = register_user_plan_log.plan_id',
			'left'
		);

		$this->db->where('register_user_plan_log.register_user_id', $user_result->id);

		$subscription_plan_query = $this->db->get();

		$subscription_plan_result = $subscription_plan_query->row_array();	




		


			
			
  		if($num !='0'){
			$is_active= $user_result->is_active;
            // echo $this->db->last_query();die;
			if($is_active == '5'){
				$this->session->set_flashdata('error_message', 'Your Account Suspended Connect with Admin');
			redirect("index.php/registered-user-login");
			}else{
                $the_session = array("registered_user_logged" => "1", "registered_user_id" => $user_result->id, "registered_user_email" => $user_result->email_id,"registered_user_first_name" => $user_result->first_name,"registered_user_last_name" => $user_result->last_name,"registered_user_organisation_name" => $user_result->organisation_name );
                $this -> session -> set_userdata($the_session);
               redirect("index.php/registered-user-dashboard");
			}
		}else{
			$this->session->set_flashdata('error_message', 'Invalid Email or Password');
			redirect("index.php/registered-user-login");
		}



	}

public function activation_registered_user($user_id){
	
		$userrow = $this->login->activate_register_user($user_id);
		$date=date("Y-m-d");
		$expiry_date= $userrow->link_expiry_date;
		if( $expiry_date < $date){
		
			$digits = 5;
			$TEMPORARYPASSWORD = rand(pow(10, $digits-1), pow(10, $digits)-1);

			$data_pass = array(
				"password"=>md5($TEMPORARYPASSWORD),
				"password_view"=>$TEMPORARYPASSWORD,
			);
			$this->Super_admin_model->update_confirmation_data_user($user_id, $data_pass);

			require_once(APPPATH.'controllers/EmailController.php');
			$emailObj = new EmailController();
			ob_start();
			$emailObj->activationLinkExpiration2($user_id);
			ob_end_clean();

			$this->session->set_flashdata('error_message', 'Your activation link has expired. A new activation link and temporary password have been sent to your email.');
			redirect("index.php/registered-user-login");
		}else{
			$data=array("is_active"=>"4");
			$this->login->activate_register_user_save($user_id,$data);

			require_once(APPPATH.'controllers/EmailController.php');
			$emailObj = new EmailController();
			ob_start();
			$emailObj->successfulRegistration3($user_id);
			ob_end_clean();

			$this->session->set_flashdata('error_message', 'Your account is active please login here');
			redirect("index.php/registered-user-login");
		 }
	}



	//already working perfectly
	//for registered user//

	public function activation_registered_user1($user_id){
	
		$userrow = $this->login->activate_register_user($user_id);
		$date=date("Y-m-d");
		$expiry_date= $userrow->link_expiry_date;
		if( $expiry_date < $date){
		
			$this->session->set_flashdata('error_message', 'Your activation link expire kindly connect with verifyfa team.');
			redirect("index.php/registered-user-login");
		}else{
			$data=array("is_active"=>"4");
			$this->login->activate_register_user_save($user_id,$data);
			$this->session->set_flashdata('error_message', 'Your account is active please login here');
			redirect("index.php/registered-user-login");
		 }
	}

	public function logout_superadmin()
	{
		$this->session->sess_destroy();
		redirect(base_url()."index.php/super-admin-login",'refresh');
		
	}
	public function logout_registereduser()
	{
		$this->session->sess_destroy();
		redirect(base_url()."index.php/registered-user-login",'refresh');
		
	}
	public function transfer_logout_confirmation(){
        $data['page_title']="Connfirmation Session Use";
        $this->load->view("registered-user/confirmation-window-transfer",$data);
     }




public function generate_active_register_user($id)
{
    $date = date("Y-m-d");

    $activation_link = base_url().'index.php/activation-registered-user/'.$id;

    $data = array(
        "is_activation_send" => "1",
        "activation_generete_link" => "1",
        "activation_generete_link_date" => $date,
        "activation_link" => $activation_link,
        "activation_send_date" => date('Y-m-d'),
    );

    $this->Super_admin_model->update_confirmation_data_user($id,$data);

    // Activate account
    $data = array("is_active" => "4");
    $this->login->activate_register_user_save($id,$data);

    // 🔥 SEND MAIL 3 HERE
    $this->load->library('../controllers/EmailController');
    $this->emailcontroller->successfulRegistration3($id);

    // Redirect after everything
    $this->session->set_flashdata('error_message', 'Your account is active please login here');
    redirect("index.php/registered-user-login");
}



//already working perfectly
	 public function generate_active_register_user1($id){
		$date = date("Y-m-d");
	   	$activation_link = base_url().'index.php/activation-registered-user/'.$id;
	
		$expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));
		$data=array(
			"is_activation_send" => "1",
			"activation_generete_link"=>"1",
			"activation_generete_link_date"=>$date,
			"activation_link"=>$activation_link,
			"activation_send_date"=>date('Y-m-d'),			
		 );
		 $this->Super_admin_model->update_confirmation_data_user($id,$data);

		/*
		$userrow = $this->login->activate_register_user($id);
		$date=date("Y-m-d");
		$expiry_date= $userrow->link_expiry_date;
		if( $expiry_date < $date){
		
			$this->session->set_flashdata('error_message', 'Your activation link expire kindly connect with verifyfa team.');
			redirect("index.php/registered-user-login");
		}else{
			$data=array("is_active"=>"4");
			$this->login->activate_register_user_save($user_id,$data);
			$this->session->set_flashdata('error_message', 'Your account is active please login here');
			redirect("index.php/registered-user-login");
		 }
		 */

		$data=array("is_active"=>"4");
		$this->login->activate_register_user_save($id,$data);
		$this->session->set_flashdata('error_message', 'Your account is active please login here');
		redirect("index.php/registered-user-login");
		
	 }




	public function clear_all(){
		$updatedata=array(
			'is_login'=>0,
		);

		$query = $this->db->update('users',$updatedata);
  
		redirect("index.php/login");
	}


	public function registered_user_forget_password()
	{
		$this->data['title']="VerifyFa Registered User Login";		
		$this->load->view('registered-user/forget-password',$this->data);
	}
	public function verifyfa_user_forget_password()
	{
		$this->data['title']="VerifyFa Registered User Login";		
		$this->load->view('forget-password',$this->data);
	}
	public function VerifyForForgetPassword(){
		// $this->data['title']="VerifyFa Registered User Login";		
		// $this->load->view('password-change',$this->data);

		$email=$this->input->post('userEmail');
		$password=$this->input->post('userPassword');
		$remember = $this->input->post('remember_me');
		$entity_code = $this->input->post('entity_code');
		
		$condition=array(
			"userEmail"=>$email,
			"entity_code"=>$entity_code
		);
		
		$login=$this->login->get_data('users',$condition);
		if(!empty($login) && count($login) > 0)
		{
			$sess_data = array(
			'email' => $login[0]->userEmail,
			'name' => $login[0]->firstName.' '.$login[0]->lastName,
			'id' => $login[0]->id
			);
			$this->session->set_userdata('temp_logged_in', $sess_data);
		}else{
			$this->session->set_flashdata('error_message', 'Invalid Email or Entity Code');
			redirect("index.php/forget-password-verifyfa-user");
		}

		redirect("index.php/login/VerifyForChangePassword");
	}
	public function VerifyForChangePassword(){
		$this->data['title']="VerifyFa Registered User Login";		
		$this->load->view('password-change',$this->data);
	}
	public function updatePasswordFromForget(){
		$user_id=$_SESSION['temp_logged_in']['id'];
        $data=array( 
            "password"=>md5($this->input->post('password')),
            "password_view"=>$this->input->post('password'),
        );
        $this->Admin_model->update_password($user_id,$data);

		$updatedata=array(
			'is_login'=>0,
		);
		$condition=array(
			'id'=>$_SESSION['temp_logged_in']['id']
		);
		$update=$this->login->update_data('users ',$updatedata,$condition);	

		$this->session->unset_userdata('temp_logged_in');
		$this->session->sess_destroy();
		$this->session->set_flashdata('success', "Password Update Successful.");
		// redirect(base_url()."index.php/login",'refresh');
		redirect('index.php/login');

	}
	
	public function VerifyForForgetPasswordRegistered(){
		// $this->data['title']="VerifyFa Registered User Login";		
		// $this->load->view('password-change',$this->data);

		$email=$this->input->post('email');
		$entity=$this->input->post('entity');

		$this->db->select('*');
		$this->db->from('registred_users');
		$this->db->where('email_id',$email);
		$this->db->where('entity_code',$entity);
		$query = $this->db->get();
		$result= $query->row();

		// echo "<pre>result :";
		// print_r($result);
		// echo "</pre>";
		// exit;

		$num = $query->num_rows();

		if($num !='0'){
			$sess_data = array(
			'email' => $result->email_id,
			'name' => $result->first_name.' '.$result->last_name,
			'id' => $result->id
			);
			$this->session->set_userdata('temp_logged_in', $sess_data);
		}else{
			$this->session->set_flashdata('error_message', 'Invalid Email or Entity Code');
			redirect("index.php/forget-password-register-user");
		}



		/*hhhh
		// Get User Data
        $user = $this->Registered_user_model->get_registerd_user($result->id);

        if (!$user) {
            echo "User not found.";
            return;
        }

        /*
        -----------------------------------------
        Actual DB Fields
        -----------------------------------------
        first_name
        last_name
        email_id
        entity_code
        -----------------------------------------
        */
		/*hhhh
        $receiverName = trim($user->first_name . ' ' . $user->last_name);
        $to = $user->email_id;

        if (empty($to)) {
            echo "Email ID not found.";
            return;
        }

        // Temporary Password
        $tempPassword = "AUTH_" . rand(1000, 9999);

        // Reset Password Link
        $resetLink = base_url('index.php/login/resetPassword/' . md5($to));

        // Subject
        $subject = "VerifyFA - Reset Password";

        // Date Time
        $dateTime = date('d-m-Y h:i A');

        // Logo Path
        $logo = base_url('assets/img/logo.png');

        // Email Template
        $email_content = '
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
            <tr>
                <td align="center">

                    <table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                        <!-- Logo -->
                        <tr>
                            <td align="center" style="padding-bottom:20px;">
                                <img src="' . $logo . '" height="70">
                            </td>
                        </tr>

                        <!-- Auto Generated Message -->
                        <tr>
                            <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                                ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                            </td>
                        </tr>

                        <!-- Date -->
                        <tr>
                            <td style="font-size:13px;color:#666;padding-bottom:15px;">
                                ' . $dateTime . '
                            </td>
                        </tr>

                        <!-- Greeting -->
                        <tr>
                            <td style="font-size:15px;color:#333;padding-bottom:15px;">
                                Dear <b>' . $receiverName . '</b>,
                            </td>
                        </tr>

                        <!-- Main Body -->
                        <tr>
                            <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                                Upon your request to reset Password / unlocking the User Account,
                                a Temporary Password has been generated:
                                <b style="color:#d9534f;">' . $tempPassword . '</b>
                            </td>
                        </tr>

                        <!-- Sub Body -->
                        <tr>
                            <td style="font-size:14px;color:#333;line-height:24px;padding-bottom:15px;">
                                You requested to enter New Password on <b>VerifyFA</b>.
                                Please click on the link below to setup your New Password:
                            </td>
                        </tr>

                        <!-- Button -->
                        <tr>
                            <td align="center" style="padding:20px 0;">
                                <a href="' . $resetLink . '" 
                                style="background:#007bff;color:#ffffff;text-decoration:none;padding:14px 24px;border-radius:5px;font-size:14px;display:inline-block;">
                                Setup New Password
                                </a>
                            </td>
                        </tr>

                        <!-- Thanks -->
                        <tr>
                            <td style="font-size:14px;color:#333;padding-bottom:15px;">
                                Thanks for your support and understanding.
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="font-size:14px;color:#333;padding-bottom:20px;">
                                Regards,<br>
                                <b>VerifyFA</b>
                            </td>
                        </tr>

                        <!-- Bottom Note -->
                        <tr>
                            <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                                ***** This is a system generated communication and does not require signature. *****
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
        </table>';

        // Send Email using your existing working dynamic function
        $result = sendEmailDynamic($to, $subject, $email_content);

        if ($result) {
            echo "Password reset email sent successfully to " . $to;
        } else {
            echo "Email sending failed.";
        }


		$this->session->set_flashdata('success', 'Reset Password Email');
		redirect("index.php/forget-password-register-user");
		*/

		redirect("index.php/login/VerifyForChangePasswordRegistered");
	}
	public function VerifyForChangePasswordRegistered(){
		$this->data['title']="VerifyFa Registered User Login";		
		$this->load->view('registered-user/password-change',$this->data);
	}


	public function updateRegisterUserPasswordFromForget(){
		

		$user_id=$_SESSION['temp_logged_in']['id'];
        $data=array( 
            "password"=>md5($this->input->post('password')),
            "password_view"=>$this->input->post('password'),
        );
     	$this->Registered_user_model->update_password($user_id,$data);

		$this->session->unset_userdata('temp_logged_in');
		$this->session->sess_destroy();
		$this->session->set_flashdata('success', "Password Update Successful.");
		redirect('index.php/registered-user-login');

	}
	


	public function activate_account_submit()
{
    $id = $this->input->post('user_id');

    $password = $this->input->post('password');
    $confirm  = $this->input->post('confirm_password');

    if ($password != $confirm) {
        echo "Passwords do not match.";
        return;
    }

    $data = array(
        'password'      => md5($password),
        'password_view' => $password,
        'is_active'     => 4,
        'is_activated'  => 1
    );

    $this->db->where('id', $id);
    $this->db->update('registred_users', $data);

    // Mail 3
    redirect(base_url().'index.php/EmailController/successfulRegistration3/'.$id);
}
	
}
