<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model','login');	
	}

	public function login()
	{
		$email=$this->input->post('userEmail');
		$password=$this->input->post('userPassword');
		$condition=array(
			"users.userEmail"=>$email,
			"users.password"=>md5($password)
		);
		$login=$this->login->getlogin_data($condition);
		if(!empty($login) && count($login) > 0)
		{
			header('Content-Type: application/json');
			echo json_encode(array("success"=>200,"message"=>"Logged in successfully.","data"=>$login));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"User does not exist"));
			exit;
		}
	}
}
