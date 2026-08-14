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
		
		$condition=array(
			"userEmail"=>$email,
			"password"=>md5($password)
		);
		
		$login=$this->login->get_data('users',$condition);
		if(!empty($login) && count($login) > 0)
		{
			if ($remember)
			{

			// Set remember me value in session
				$this->session->set_userdata('remember_me', TRUE);
			}
			$sess_data = array(
			'email' => $login[0]->userEmail,
			'name' => $login[0]->firstName.' '.$login[0]->lastName,
			'id' => $login[0]->id,
			'company_id'=>$login[0]->company_id,
			'main_role'=>$login[0]->userRole
			);
			$this->session->set_userdata('logged_in', $sess_data);
			redirect(base_url()."index.php/dashboard");
		} 
		else {
		
			$this->session->set_flashdata('error_message', 'Invalid Email or Password');
			redirect(base_url(),$condition);
		}
	}
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		redirect(base_url()."index.php/login",'refresh');
		
	}
}
