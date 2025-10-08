<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
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
            $result= $query->row();
            $num = $query->num_rows();
			
  if($num !='0'){
			$is_active= $result->is_active;
            // echo $this->db->last_query();die;
			if($is_active == '5'){
				$this->session->set_flashdata('error_message', 'Your Account Suspended Connect with Admin');
			redirect("index.php/registered-user-login");
			}else{
                $the_session = array("registered_user_logged" => "1", "registered_user_id" => $result->id, "registered_user_email" => $result->email_id,"registered_user_first_name" => $result->first_name,"registered_user_last_name" => $result->last_name,"registered_user_organisation_name" => $result->organisation_name );
                $this -> session -> set_userdata($the_session);
               redirect("index.php/registered-user-dashboard");
			}
		}else{
			$this->session->set_flashdata('error_message', 'Invalid Email or Password');
			redirect("index.php/registered-user-login");
		}



	}
	//for registered user//

	public function activation_registered_user($user_id){
	
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

	 public function generate_active_register_user($id){
		$date = date("Y-m-d");
	   	$activation_link = base_url().'index.php/activation-registered-user/'.$id;
	
		$expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));
		$data=array(
			"activation_generete_link"=>"1",
			"activation_generete_link_date"=>$date,
			"activation_link"=>$activation_link,
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
			$this->session->set_flashdata('error_message', 'Invlid Email or Entity Code');
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
		$num = $query->num_rows();

		if($num !='0'){
			$sess_data = array(
			'email' => $result->email_id,
			'name' => $result->first_name.' '.$result->last_name,
			'id' => $result->id
			);
			$this->session->set_userdata('temp_logged_in', $sess_data);
		}else{
			$this->session->set_flashdata('error_message', 'Invlid Email or Entity Code');
			redirect("index.php/forget-password-register-user");
		}



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
		redirect('index.php/forget-password-register-user');

	}
	
	
}
