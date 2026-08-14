<?php 
class Login_model extends CI_Model {

	public function _consruct(){

		parent::_construct();

 	}

	function get_data($table,$condition) { 

	  $this->db->where($condition);

	  $query = $this->db->get($table);

	  return $query->result();

	}
	function getlogin_data($condition)
	{
		$this->db->select('users.id,users.userName,users.firstName,users.lastName,users.userEmail,users.company_id,users.userRole,users.created_on,users.updated_on,company.company_name');
        $this->db->from('users');
        $this->db->join('company','company.id=users.company_id');
        $this->db->where($condition);
		$getdata=$this->db->get();
		return $getdata->result();
	}
	function update_data($table,$data,$condition) { 

		$this->db->where($condition);
  
		$query = $this->db->update($table,$data);
  
		return true;
  	}
	function get_schema($table)
	{
		return $this->db->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='verifyfa_db'  AND `TABLE_NAME`='".$table."' AND `IS_NULLABLE`='No' AND `COLUMN_NAME`!='verification_status' AND `COLUMN_NAME`!='item_scrap' AND `COLUMN_NAME`!='quantity_verified' AND `COLUMN_NAME`!='createdat'  AND `COLUMN_NAME`!='updatedat' AND `COLUMN_NAME`!='id' AND `COLUMN_NAME` != 'qty_ok' AND `COLUMN_NAME` != 'qty_damaged' AND `COLUMN_NAME` != 'qty_scrapped' AND `COLUMN_NAME` != 'qty_not_in_use' AND `COLUMN_NAME` != 'qty_missing' AND `COLUMN_NAME` != 'qty_shifted'")->result();
	}
	function getcompleteschema($table)
	{
		return $this->db->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='verifyfa_db'  AND `TABLE_NAME`='".$table."'")->result();
	}
	function insert_data($table,$data)
	{
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}

}