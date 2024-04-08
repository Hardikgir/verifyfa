<?php 
class Registered_user_model extends CI_Model {

	public function _consruct(){

		parent::_construct();

 	}

public function check_current_pass($user_id,$current_pass){
    $this->db->select('*');
    $this->db->from('registred_users');
    $this->db->where('id',$user_id);
    $this->db->where('password',md5($current_pass));
    $query=$this->db->get();
    return $query->num_rows();
}

public function update_password($user_id,$data){
    $this->db->where('id',$user_id);
    $this->db->update('registred_users',$data);
    return 1;
}

public function get_registerd_user($id){
    $this->db->select('*');
    $this->db->from('registred_users');
    $this->db->where('id',$id);
    $query= $this->db->get();
    return $query->row();
}


public function get_registred_users_payment($id){
    $this->db->select('*');
    $this->db->from('registred_users_payment');
    $this->db->where('regiistered_user_id',$id);
    $query= $this->db->get();
    return $query->row();
}

public function get_registred_users_payment_all($id){
    $this->db->select('*');
    $this->db->from('registred_users_payment');
    $this->db->where('regiistered_user_id',$id);
    $query= $this->db->get();
    return $query->result();
}


public function get_registered_user_plan($id){
    $this->db->select('*');
    $this->db->from('registered_user_plan');
    $this->db->where('regiistered_user_id',$id);
    $query= $this->db->get();
    return $query->row();
}




public function edit_save_registered_user($register_user_id,$data_user){
    $this->db->where('id',$register_user_id);
    $this->db->update('registred_users',$data_user);
    return 1;
}
public function save_transfered_user($data_user){
    $this->db->insert('transfered_account',$data_user);
    return 1;
}



public function request_renew_save($registereduserid,$data){
    $this->db->where('id',$registereduserid);
    $this->db->update('registred_users',$data);
    return 1;
}


public function as_a_admin($registereduserid){
    $this->db->select('*');
    $this->db->from('registred_users');
    $this->db->where('id',$registereduserid);
    $query=$this->db->get();
    return $query->row();
}

public function check_as_a_admin_user($registereduserid,$register_usr_email_id,$register_usr_entity_code){
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('registered_user_id',$registereduserid);
    $this->db->where('userEmail',$register_usr_email_id);
    $this->db->where('entity_code',$register_usr_entity_code);
    $query=$this->db->get();
    return $query->num_rows();
}

public function insert_as_a_admin_user($data){
   $this->db->insert("users",$data);
   return $this->db->insert_id();
}

public function login_as_admin($register_usr_email_id,$register_usr_entity_code){
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('userEmail',$register_usr_email_id);
    $this->db->where('entity_code',$register_usr_entity_code);
    $query=$this->db->get();
    $result=$query->row();
    $cnt = $query->num_rows();
    if($cnt !== '0'){
           $sess_data = array(
			'email' => $result->userEmail,
			'name' => $result->firstName.' '.$result->lastName,
			'id' => $result->id,
			'company_id'=>$result->company_id,
			'admin_registered_user_id'=>$result->registered_user_id,
			'admin_registered_entity_code'=>$result->entity_code,
			'main_role'=>$result->userRole,
			'is_register_as_login'=>'1',
			);
			$this->session->set_userdata('logged_in', $sess_data);
            return 1;
    }else{
        return 0;
    }
}



}
