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
		$entity=$this->input->post('entity');
		$condition=array(
			"users.userEmail"=>$email,
			"users.password"=>md5($password),
			"users.entity_code"=>$entity
		);
		$userid='';
		$login=$this->login->getlogin_data($condition);


		$this->db->select('id,department_name');
        $this->db->from('department');
        $this->db->where('id',$login[0]['department_id']);
		$getdata=$this->db->get();		
		$department_value = $getdata->row();
		$login[0]['department_value'] = $department_value->department_name;
		$userid=$login[0]["id"];
		$login[0]["role_cnt"]=$this->login->getuserrolecnt($userid);
		
		
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
