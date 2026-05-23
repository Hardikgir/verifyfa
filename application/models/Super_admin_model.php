<?php 
class Super_admin_model extends CI_Model {

	public function _consruct(){

		parent::_construct();

 	}

public function save_subscription_plan($data){
    $this->db->insert('subscription_plan',$data);
    return 1;
}

public function list_subscription_plan(){
    $this->db->select('*');
    $this->db->from('subscription_plan');
    $query= $this->db->get();
    return $query->result();
}

public function get_subscription_plan_single($id){
    $this->db->select('*');
    $this->db->from('subscription_plan');
    $this->db->where('id',$id);
    $query= $this->db->get();
    return $query->result();
}


public function update_subscription_plan($data,$id){
    $this->db->where('id',$id);
    $this->db->update('subscription_plan',$data);
    return 1;
}

public function inactive_plan($id,$data){
    $this->db->where('id',$id);
    $this->db->update('subscription_plan',$data);
    return 1;
}

public function active_plan($id,$data){
    $this->db->where('id',$id);
    $this->db->update('subscription_plan',$data);
    return 1;
}

public function get_registered_users(){
    $this->db->select("*");
    $this->db->from('registred_users');
    $query=$this->db->get();
    return $query->result();
}


public function get_active_plan(){
    $this->db->select('*');
    $this->db->from('subscription_plan');
    $this->db->where('status','1');
    $this->db->or_where('validity_from >=',date("Y-m-d"));
    $this->db->or_where('validity_from <=',date("Y-m-d"));
    $this->db->or_where('validity_to <=',date("Y-m-d"));
    $this->db->or_where('validity_to >=',date("Y-m-d"));
    $query= $this->db->get();
    return $query->result();
}


public function get_subscription_plan_row($plan_id){
    $this->db->select('*');
    $this->db->from('subscription_plan');
    $this->db->where('id',$plan_id);
    $query= $this->db->get();
    return $query->row();
}

public function save_registered_user($data_user){
    $this->db->insert('registred_users',$data_user);
    return $this->db->insert_id();
}

public function save_registered_user_plan($data_plan){
    $this->db->insert('registered_user_plan',$data_plan);
    return 1;
}

public function save_registered_user_payment($data_payment){
    $this->db->insert('registred_users_payment',$data_payment);
    return 1;
}

public function get_registerd_user($id){
    $this->db->select('*');
    $this->db->from('registred_users');
    $this->db->where('id',$id);
    $query= $this->db->get();
    return $query->row();
}

public function update_confirmation_data_user($id,$data){
    $this->db->where('id',$id);
    $this->db->update('registred_users',$data);
    return 1;
}

public function get_registred_users_payment($id){
    $this->db->select('*');
    $this->db->from('registred_users_payment');
    $this->db->where('regiistered_user_id',$id);
    $query= $this->db->get();
    return $query->row();
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

public function edit_save_registered_user_plan($register_user_id,$data_user){
    $this->db->where('regiistered_user_id',$register_user_id);
    $this->db->update('registered_user_plan',$data_user);
    return 1;
}

public function get_plan_single($id){
    $this->db->select('*');
    $this->db->from('subscription_plan');
    $this->db->where('id',$id);
    $query= $this->db->get();
    return $query->row();
}

public function checksubscription_title($title){
    $this->db->select('*');
    $this->db->from('subscription_plan');
    $this->db->where('title',$title);
    $query= $this->db->get();
    return $query->num_rows();
}

public function checkuser_email($email_id){
    $this->db->select('*');
    $this->db->from('registred_users');
    $this->db->where('email_id',$email_id);
    $query= $this->db->get();
    return $query->num_rows();
}

public function checkuser_entitycode($entity_code){
    $this->db->select('*');
    $this->db->from('registred_users');
    $this->db->where('entity_code',$entity_code);
    $query= $this->db->get();
    // echo $this->db->last_query();
    return $query->num_rows();
}



public function get_active_plan_upgrade($plan_id){
    $this->db->select('*');
    $this->db->from('subscription_plan');
    $this->db->where('status','1');
    $this->db->where('id','!=',$plan_id);
    $this->db->or_where('validity_from >=',date("Y-m-d"));
    $this->db->or_where('validity_from <=',date("Y-m-d"));
    $this->db->or_where('validity_to <=',date("Y-m-d"));
    $this->db->or_where('validity_to >=',date("Y-m-d"));
    $query= $this->db->get();
    return $query->result();
}


public function save_upgradePlan($data){
    $this->db->insert('register_user_plan_log',$data);
    return 1;
}

public function update_plan($data,$register_user_id){
    $this->db->where('id',$register_user_id);
    $this->db->update('registred_users',$data);
    return 1;
}

public function update_plan_plan($data,$register_user_id){
    $this->db->where('regiistered_user_id',$register_user_id);
    $this->db->update('registered_user_plan',$data);
    return 1;
}

}
