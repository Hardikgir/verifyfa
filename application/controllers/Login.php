<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model','login');	
	}
	public function index()
	{

		// echo '<pre>';
		// print_r("Asdasd");
		// echo '</pre>';
		// exit(); 

		/*
		$id = '123456';
		// $to = ;
		$to = 'hardik.meghnathi12@gmail.com';
		$subject = 'Active your Account';
		$email_updated_content = '<a href="'.base_url().'index.php/generate-activation-link/'.$id.'">Active Your Account</a>';
		
		$CI = setEmailProtocol();
		$from_email = 'solutions@ethicalminds.in';
		$CI->email->set_newline("\r\n");
		$CI->email->set_mailtype("html");
		$CI->email->set_header('Content-Type', 'text/html');
		$CI->email->from($from_email);
		$CI->email->to($to);
		$CI->email->subject($subject);
		$CI->email->message($email_updated_content);

		$mailsend = 0;		
		if($CI->email->send()){
			$mailsend = 1;
		}else{
			$errors = $CI->email->print_debugger();
    		print_r($errors);
		}

		echo '<pre>mailsend :: ';
		print_r($mailsend);
		echo '</pre>';
		// exit(); 

		exit("test");
		*/

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

			redirect(base_url()."index.php/dashboard");
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
}
