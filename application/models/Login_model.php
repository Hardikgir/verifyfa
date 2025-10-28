<?php 
class Login_model extends CI_Model {

	public function __contruct(){

		parent::_construct();

 	}
	 
	function get_data($table,$condition) { 

	  $this->db->where($condition);

	  $query = $this->db->get($table);

	  return $query->result();

	}
	function getlogin_data($condition)
	{	
		$this->db->select('id,userName,firstName,lastName,userEmail,entity_code,created_on,updated_on');
        $this->db->from('users');
        $this->db->where($condition);
		$getdata=$this->db->get();
		// echo $this->db->last_query();
		return $getdata->result_array();
	}
	function getuserrolecnt($userid)
	{	
		$this->db->select('*');
        $this->db->from('user_role');
        $this->db->where("user_id",$userid);
		$getdata=$this->db->get();
		// echo $this->db->last_query();
		return $getdata->num_rows();
	}

	
	function GetCompanydataByUserIdOrEntityCode($user_id,$entity_code)
	{	
		$this->db->select('id,company_id,user_role');
        $this->db->from('user_role');
        $this->db->where('user_id',$user_id);
		$this->db->where('entity_code',$entity_code);
		$this->db->group_by('company_id');
		$getdata=$this->db->get();
		// echo $this->db->last_query();
		return $getdata->result();
	}

	function GetCompanydataByUserIdOrEntityCodeuserrole($user_id,$entity_code)
	{	
		$this->db->select('id,company_id,user_role');
        $this->db->from('user_role');
        $this->db->where('user_id',$user_id);
		$this->db->where('entity_code',$entity_code);
		$this->db->group_by('company_id');
		$this->db->where("FIND_IN_SET(0,user_role)",true);

		$getdata=$this->db->get();
		return $getdata->result();
	}

	

	function GetCompanydataByCompanyId($company_id)
	{	
		$this->db->select('id,company_name');
        $this->db->from('company');
        $this->db->where('id',$company_id);
		$getdata=$this->db->get();
		// echo $this->db->last_query();
		return $getdata->result();
	}

	function GetLocationdatabyid($company_id)
	{	
		$this->db->select('id,location_name');
        $this->db->from('company_locations');
        $this->db->where('company_id',$company_id);
		$getdata=$this->db->get();
		// echo $this->db->last_query();
		return $getdata->result();
	}


	function GetLocationdatabyidnew($company_id)
	{	
		$this->db->select('id,location_name');
        $this->db->from('company_locations');
        $this->db->where('company_id',$company_id);
		$getdata=$this->db->get();
		// echo $this->db->last_query();
		return $getdata->result();
	}

	function get_locrow($location_id)
	{	
		$this->db->select('id,location_name');
        $this->db->from('company_locations');
        $this->db->where('id',$location_id);
		$getdata=$this->db->get();
		// echo $this->db->last_query();
		return $getdata->row();
	}


	function get_allroles($company_id,$user_id)
	{	
		$this->db->select('*');
        $this->db->from('user_role');
        $this->db->where('company_id',$company_id);
        $this->db->where('user_id',$user_id);
        $this->db->where('entity_code',$this->admin_registered_entity_code);
		$getdata=$this->db->get();
		// echo $this->db->last_query();
		return $getdata->result();
	}


	function update_data($table,$data,$condition) { 

		$this->db->where($condition);
  
		$query = $this->db->update($table,$data);
  
		return true;
  	}
	function get_schema($table)
	{
		return $this->db->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='verifyfa_test_db'  AND `TABLE_NAME`='".$table."' AND `IS_NULLABLE`='No' AND `COLUMN_NAME`!='verification_status' AND `COLUMN_NAME`!='item_scrap' AND `COLUMN_NAME`!='quantity_verified' AND `COLUMN_NAME`!='createdat'  AND `COLUMN_NAME`!='updatedat' AND `COLUMN_NAME`!='id' AND `COLUMN_NAME` != 'qty_ok' AND `COLUMN_NAME` != 'qty_damaged' AND `COLUMN_NAME` != 'qty_scrapped' AND `COLUMN_NAME` != 'qty_not_in_use' AND `COLUMN_NAME` != 'qty_missing' AND `COLUMN_NAME` != 'qty_shifted'")->result();
	}
	function getcompleteschema($table)
	{
		return $this->db->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='verifyfa_test_db'  AND `TABLE_NAME`='".$table."'")->result();
	}
	function insert_data($table,$data)
	{
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}

	//for super admin//

	public function checksuperadmin_login(){
		$email=$this->input->post('email');
		$password=$this->input->post('password');

            $this->db->select('*');
            $this->db->from('super_admin');
            $this->db->where('email',$email);
            $this->db->where('password',$password);
            $query = $this->db->get();
            $result= $query->row();
            $num = $query->num_rows();
            // echo $this->db->last_query();die;
        
            if($num=='1'){
                $the_session = array("super_admin_logged_in" => "1", "super_admin_id" => $result->id, "super_admin_email" => $result->email,"super_admin_name" => $result->name );
                $this -> session -> set_userdata($the_session);
                return 1;
            }else{
                return 0;
            }
	}


	public function checkregistereduser_login(){
		$email=$this->input->post('email');
		$password=md5($this->input->post('password'));

            $this->db->select('*');
            $this->db->from('registred_users');
            $this->db->where('email_id',$email);
            $this->db->where('password',$password);
            $query = $this->db->get();
            $result= $query->row();
            $num = $query->num_rows();
			$is_active= $result->is_active;
            // echo $this->db->last_query();die;
			if($is_active != '4'){
				return 2;
			}else{
            if($num=='1'){
                $the_session = array("registered_user_logged" => "1", "registered_user_id" => $result->id, "registered_user_email" => $result->email_id,"registered_user_first_name" => $result->first_name,"registered_user_last_name" => $result->last_name,"registered_user_organisation_name" => $result->organisation_name );
                $this -> session -> set_userdata($the_session);
                return 1;
				
            }else{
                return 0;
            }
		}
	}



	public function activate_register_user($user_id){
		$this->db->select('*');
		$this->db->from('registred_users');
		$this->db->where('id',$user_id);
		$query = $this->db->get();
		$result= $query->row();
		return $result;
	}

	public function activate_register_user_save($user_id,$data){
		$this->db->update('registred_users',$data);
		$this->db->where('id',$user_id);
		return 1;
	}

	public function deleteprojecdatabyid($projectid, $original_table_name){
		$deletepro = $this->db->where('id',$projectid)->delete('company_projects');
		$this->db->select('original_table_name	');
		$this->db->from('company_projects');
		$this->db->where('original_table_name',$original_table_name);
		$query = $this->db->get();
		$result= $query->row();
		$num = $query->num_rows();
		if($num == 0){
			$this->load->dbforge();
			$this->dbforge->drop_table($original_table_name);
		}
		echo $this->db->last_query();

	}


	public function clearprojecdatabyid($projectid, $original_table_name){
		
		$this->db->select('*');
		$this->db->from('company_projects');
		$this->db->where('id',$projectid);
		$query = $this->db->get();
		$company_projects_result= $query->row();
		
		$item_category_array = json_decode($company_projects_result->item_category);

		foreach($item_category_array as $item_category_array_value){
			$condition=array(
				'item_category'=>$item_category_array_value
			);
			$data=array(
				'is_alotted'=>0
			);
			$this->db->where($condition);
			$query = $this->db->update($original_table_name,$data);
		}

		// Code Added on 8 May Start
		$this->db->select('id');
		$this->db->from($original_table_name);
		$this->db->where('is_alotted',1);
		$query = $this->db->get();
		$result= $query->row();
		$num = $query->num_rows();
		if(empty($num)){
			$this->load->dbforge();
			$this->dbforge->drop_table($original_table_name);
		}		
		// Code Added on 8 May End
		
		

		$deletepro = $this->db->where('id',$projectid)->delete('company_projects');
		$this->dbforge->drop_table($company_projects_result->project_table_name);	
		// $this->db->select('original_table_name');
		// $this->db->from('company_projects');
		// $this->db->where('original_table_name',$original_table_name);
		// $query = $this->db->get();
		// $result= $query->row();
		// $num = $query->num_rows();
		// $this->load->dbforge();
		// $this->dbforge->drop_table($original_table_name);
		
		// echo $this->db->last_query();

	}


}