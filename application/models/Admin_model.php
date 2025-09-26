<?php
class Admin_model extends CI_Model {

	public function _consruct(){

		parent::_construct();

 	}
    public function check_company_shortcode($company_shortcode,$register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('company');
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $this->db->where('short_code',$company_shortcode);
        $query=$this->db->get();
        return $query->num_rows();
    }
    public function save_company($data){
        $this->db->insert('company',$data);
        return 1;
    }
    
    public function get_all_company($register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('company');
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_single_company($company_id){
        $this->db->select('*');
        $this->db->from('company');
        $this->db->where('id',$company_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function save_edit_company($data,$company_id){
        $this->db->where('id',$company_id);
        $this->db->update('company',$data);
        return 1;
    }

    public function get_all_location($register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('company_locations');
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        return $query->result();
    }
    

    public function check_location_shortcode($location_shortcode,$register_user_id,$entity_code,$company_id){
        $this->db->select('*');
        $this->db->from(' company_locations');
        $this->db->where('location_shortcode',$location_shortcode);
        $this->db->where('company_id',$company_id);
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        return $query->num_rows();
    }

    public function save_location($data){
        $this->db->insert('company_locations',$data);
        return 1;
    }

    public function get_single_location($location_id){
        $this->db->select('*');
        $this->db->from('company_locations');
        $this->db->where('id',$location_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function save_edit_location($data,$location_id){
        $this->db->where('id',$location_id);
        $this->db->update('company_locations',$data);
        return 1;
    }
    public function get_registered_user_row($register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('registred_users');
        $this->db->where('id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        return $query->row();
    }

    public function get_plan_row($plan_id){
        $this->db->select('*');
        $this->db->from('subscription_plan');
        $this->db->where('id',$plan_id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function get_total_company_row_entity($register_user_id,$entity_code,$company_id){
        $this->db->select('*');
        $this->db->from(' company_locations');
        $this->db->where('company_id',$company_id);
        $this->db->where('entity_code',$entity_code);
        $this->db->where('registered_user_id',$register_user_id);
        $query=$this->db->get();
        return $query->num_rows();
    }
    

    
    public function get_all_department($register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('department');
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        return $query->result();
    }
    
    public function save_department($data){
        $this->db->insert('department',$data);
        return 1;
    }
    
    public function get_single_department($department_id){
        $this->db->select('*');
        $this->db->from('department');
        $this->db->where('id',$department_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function save_edit_department($data,$department_id){
        $this->db->where('id',$department_id);
        $this->db->update('department',$data);
        return 1;
    }

    public function check_department_name($department_name,$register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('department');
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $this->db->where('department_name',$department_name);
        $query=$this->db->get();
        return $query->num_rows();
    }

    public function get_all_user($register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('registered_user_id',$register_user_id);
        // $this->db->where('id !=',$this->user_id);
        $this->db->where('entity_code',$entity_code);
        // $this->db->where('userRole !=','5');
        $query=$this->db->get();
        return $query->result();
    }

    public function get_subadmin_all_user($register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('registered_user_id',$register_user_id);
        // $this->db->where('id !=',$this->user_id);
        $this->db->where('entity_code',$entity_code);
        // $this->db->where('userRole !=','5');
        $query=$this->db->get();
        return $query->result();
    }

    public function get_company_location_user($company_id,$location_id){
        $this->db->select('*');
        $this->db->from('user_role');
        $this->db->where('company_id',$company_id);
        $this->db->where('location_id',$location_id);
        $query=$this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    
    public function get_userroles($user_id){
        $this->db->select('*');
        $this->db->from('user_role');
        $this->db->where('user_id',$user_id);
        $query=$this->db->get();
        return $query->result();
    }
    
    public function save_admin_user($data){
        $this->db->insert('users',$data);
        return 1;

    }

    
    public function check_userEmail_admin($userEmail,$register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from(' users');
        $this->db->where('userEmail',$userEmail);
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        return $query->num_rows();
    }

    public function get_single_user($user_id){
        $this->db->select('*');
        $this->db->from(' users');
        $this->db->where('id',$user_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function save_edit_admin_user($data,$user_id){
        $this->db->where('id',$user_id);
        $this->db->update('users',$data);
        return 1;

    }

    public function save_user_role($data){
        $this->db->insert('user_role',$data);
        return 1;
    }
    
    public function check_user_role_location_cmpany($company_id,$location_id,$user_id){
        $this->db->select('*');
        $this->db->from(' user_role');
        $this->db->where('company_id',$company_id);
        $this->db->where('location_id',$location_id);
        $this->db->where('user_id',$user_id);
        $query=$this->db->get();
        return $query->num_rows();
    }

  public function get_company_all_location($company_id){
        $this->db->select('*');
        $this->db->from(' company_locations');
        $this->db->where('company_id',$company_id);
        $query=$this->db->get();
        return $query->result();
    }


    public function get_company_all_location_subadmin($company_id,$location_id){
        $this->db->select('*');
        $this->db->from(' company_locations');
        $this->db->where('company_id',$company_id);
        $this->db->where('id',$location_id);
        $query=$this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

  public function get_user_row($user_id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id',$user_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_department_row($department_id){
        $this->db->select('*');
        $this->db->from(' department');
        $this->db->where('id',$department_id);
        $query=$this->db->get();
        return $query->row();
    }
 
 public function get_all_user_role($register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from(' user_role');
        $this->db->where('registered_user_id',$register_user_id);
        // $this->db->where('user_id !=',$this->user_id);
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        // echo $this->db->last_query();
        return $query->result();
    }

    public function get_role_single($role_id){
       $this->db->select('*');
        $this->db->from('user_role');
        $this->db->where('id',$role_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function edit_save_user_role($data,$role_id){
         $this->db->where('id',$role_id);
        $this->db->update('user_role',$data);
        return 1;
    }

    public function my_data($user_id){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id',$user_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function check_current_pass($user_id,$current_pass){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id',$user_id);
        $this->db->where('password',md5($current_pass));
        $query=$this->db->get();
        return $query->num_rows();
    }
    
    public function update_password($user_id,$data){
        $this->db->where('id',$user_id);
        $this->db->update('users',$data);
        return 1;
    }

    public function get_all_notification($entity_code){
        $this->db->select('*');
        $this->db->from('notification');
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        return $query->result();
    }
    
    public function save_notification($data){
        $this->db->insert('notification',$data);
        return 1;
    }

    public function get_single_notification($id){
        $this->db->select('notification.*,users.firstName,users.lastName');
        $this->db->from('notification');
         $this->db->join('users','users.id=notification.created_by');
        $this->db->where('notification.id',$id);
        $query=$this->db->get();
        return $query->row();
    }

    public function save_notification_reply($data){
        $this->db->insert('notification_reply',$data);
        return 1;
    }

    public function get_reply_data($notification_id){
        $this->db->select('*');
        $this->db->from('notification_reply');
        $this->db->where('notification_id',$notification_id);
        $query=$this->db->get();
        return $query->result();
    }
    
    public function update_main_not_read($datamainread,$notification_id,$user_id){
        $this->db->select('*');
        $this->db->from('notification_read');
        $this->db->where('notification_id',$notification_id);
        $this->db->where('user_id',$user_id);
        $query=$this->db->get();
        $cnt =$query->num_rows();
            if($cnt == '0'){
                $this->db->insert('notification_read',$datamainread);

            }
        return 1;
    }


    public function update_reply_not_read($datamainreply,$message_id,$user_id){
        $this->db->select('*');
        $this->db->from('notification_read');
        $this->db->where('reply_message_id',$message_id);
        $this->db->where('user_id',$user_id);
        $query=$this->db->get();
        $cnt =$query->num_rows();
            if($cnt == '0'){
                $this->db->insert('notification_read',$datamainreply);

            }
        return 1;
    }
    
    public function check_department_shortcode($department_shortcode,$register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('department');
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('entity_code',$entity_code);
        $this->db->where('department_shortcode',$department_shortcode);
        $query=$this->db->get();
        return $query->num_rows();
    }
    
    public function get_role_user($user_id,$company_id,$location_id){
        $this->db->select('*');
        $this->db->from('user_role');
        $this->db->where('user_id',$user_id);
        $this->db->where('company_id',$company_id);
        $this->db->where('location_id',$location_id);
        $query=$this->db->get();
        return $query->result_array();
    }
    
    public function get_all_user_role_company_registerid_entity($company_id,$register_user_id,$entity_code){
        $this->db->select('*');
        $this->db->from('user_role');
        $this->db->where('registered_user_id',$register_user_id);
        $this->db->where('company_id',$company_id);
        $this->db->where('entity_code',$entity_code);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_users_by_role($user_role){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('userRole',$user_role);
        $query=$this->db->get();

        // $query=$this->db->query("select * from user_role where  entity_code=''".$entity_code."''  AND FIND_IN_SET(0,user_role) GROUP BY company_id");

        return $query->result();
    }

    public function save_notification_user_assign($data){
        $this->db->insert('notification_user',$data);
        return 1;
    }

    public function get_all_user_of_role_by_entity($user_role,$entity_code){
        $query=$this->db->query("select * from user_role where  entity_code='".$entity_code."'  AND FIND_IN_SET(".$user_role.",user_role)");
        return $query->result();
    }

    public function get_users_by_ids($user_ids){
        $this->db->select('*');
        $this->db->from('users');
        // $this->db->where('id',$user_ids);
        // $this->db->where_in('id',$user_ids);
        $this->db->where("id IN (".$user_ids.")",NULL, false);
        $query=$this->db->get();
        return $query->result();
    }

    public function save_delete_request($data){
        $this->db->insert('request_for_delete_project',$data);
        return 1;
    }


      
    public function save_issue_details($data){
        $this->db->insert('issue_manage',$data);
        return 1;
    }
    public function update_issue_details($data,$row_id){
        $this->db->where('id',$row_id);
        $this->db->update('issue_manage',$data);
        return 1;
    }

    public function save_issue_log_details($data){
        $this->db->insert('issue_log_manage',$data);
        return 1;
    }
  


    // public function get_all_issue_for_me($user_id){
    //     $this->db->select('issue_manage.*,users.firstName,users.lastName,issue_manage.status as status');
    //     $this->db->from('issue_manage');
    //     $this->db->join('users','users.id=issue_manage.resolved_by');
    //     $this->db->where('issue_manage.resolved_by', $user_id);
    //     $getnotifications = $this->db->get();
    //     return $getnotifications->result();
    // }

    // tushar
//     public function get_all_issue_for_me($user_id) {
//     $this->db->select(
//         issue_manage.*,
//         company_projects.project_id AS project_code,
//         company_projects.project_name AS project_real_name,
//         users.firstName,
//         users.lastName,
//         issue_manage.status as status
//     );
//     $this->db->from('issue_manage');
//     $this->db->join('company_projects', 'company_projects.id = issue_manage.project_name', 'left');
//     $this->db->join('users', 'users.id = issue_manage.resolved_by', 'left');
//     $this->db->where('issue_manage.resolved_by', $user_id);
//     $this->db->order_by('issue_manage.id', 'DESC');

//     $query = $this->db->get();
//     return $query->result();
// }

public function get_all_issue_for_me($user_id) {
    $this->db->select('
        issue_manage.*,
        company_projects.project_id AS project_code,
        company_projects.project_name AS project_real_name,
        users.firstName AS resolver_first_name,
        users.lastName AS resolver_last_name,
        issue_manage.status AS status
    ');
    $this->db->from('issue_manage');
    $this->db->join('company_projects', 'company_projects.id = issue_manage.project_name', 'left');
    $this->db->join('users', 'users.id = issue_manage.resolved_by', 'left');
    $this->db->where('issue_manage.resolved_by', $user_id);
    $this->db->order_by('issue_manage.id', 'DESC');

    $query = $this->db->get();
    return $query->result();
}



    //  public function get_all_my_issue($user_id){

    //     // $this->db->select('issue_manage.*,company_projects.project_id,users.firstName,users.lastName,company.company_name,issue_manage.status as status');
    //     $this->db->select('issue_manage.*,users.firstName,users.lastName,issue_manage.status as status');
    //     $this->db->from('issue_manage');
    //     $this->db->join('company_projects','company_projects.id=issue_manage.project_name');
    //     // $this->db->join('users','users.id=issue_manage.manage_name');
    //     $this->db->join('users','users.id=issue_manage.created_by');
    //     // $this->db->join('company','company.id=issue_manage.company_name');
    //     $this->db->where('issue_manage.created_by', $user_id);
    //     $getnotifications=$this->db->get();
    //     return $getnotifications->result();

    //     // $this->db->select('*');
    //     // $this->db->from('issue_manage');
    //     // $this->db->where('created_by',$user_id);
    //     // $query=$this->db->get();
    //     // return $query->result();
    // }

    // tushar
       public function get_all_my_issue2($user_id) {
    $this->db->select('
        issue_manage.*,
        company_projects.project_id AS project_code,
        users.firstName,
        users.lastName,
        issue_manage.status as status
    ');
    $this->db->from('issue_manage');
    $this->db->join('company_projects', 'company_projects.id = issue_manage.project_name', 'left');
    $this->db->join('users', 'users.id = issue_manage.created_by', 'left');
    $this->db->where('issue_manage.created_by', $user_id);
    $this->db->order_by('issue_manage.id', 'DESC');

    $query = $this->db->get();
    return $query->result();
}
   public function get_all_issue_for_groupadmin($user_id) {
    $this->db->select('
        issue_manage.*,
        company_projects.project_id AS project_code,
        company_projects.project_name AS project_real_name,
        users.firstName,
        users.lastName,
        issue_manage.status as status
    ');
    $this->db->from('issue_manage');
    $this->db->join('company_projects', 'company_projects.id = issue_manage.project_name', 'left');
    $this->db->join('users', 'users.id = issue_manage.created_by', 'left'); // Group Admin is creator
    $this->db->where('issue_manage.created_by', $user_id);
    $this->db->order_by('issue_manage.id', 'DESC');

    $query = $this->db->get();
    return $query->result();
}
public function get_all_issue_for_manager($user_id) {
    $this->db->select('
        issue_manage.*,
        company_projects.project_id AS project_code,
        company_projects.project_name AS project_real_name,
        users.firstName,
        users.lastName,
        issue_manage.status as status
    ');
    $this->db->from('issue_manage');
    $this->db->join('company_projects', 'company_projects.id = issue_manage.project_name', 'left');
    $this->db->join('users', 'users.id = issue_manage.created_by', 'left');
    $this->db->where('issue_manage.manager_id', $user_id); // use correct column
    $this->db->order_by('issue_manage.id', 'DESC');

    $query = $this->db->get();
    return $query->result();
}

public function get_issues_by_role($user_id, $role) {
    $this->db->select('
        issue_manage.*,
        company_projects.project_id AS project_code,
        company_projects.project_name AS project_real_name
    ');
    $this->db->from('issue_manage');
    $this->db->join('company_projects', 'company_projects.id = issue_manage.project_name', 'left');

    if ($role === 'manager') {
        $this->db->where('issue_manage.manage_name', $user_id);
         $this->db->where('issue_manage.issue_type', 'Project based');
    } elseif ($role === 'groupadmin') {
        $this->db->where('issue_manage.groupadmin_name', $user_id);
         $this->db->where('issue_manage.issue_type', 'General');
    }

    $this->db->order_by('issue_manage.id', 'DESC');
    return $this->db->get()->result();
}
    
    
    
}
?>