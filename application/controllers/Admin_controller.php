<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {

		parent::__construct();
        $this->load->library('session');	
        $this->load->model('Admin_model');
        $this->load->model('Registered_user_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url()."index.php/login", 'refresh');
		}
		else
		{ 
			$session=$this->session->userdata('logged_in');
			$this->user_id=$session['id'];
			$this->company_id=$session['company_id'];
            $this->email=$session['email'];
            $this->name=$session['name'];
			$this->id=$session['id'];
			$this->admin_registered_user_id=$session['admin_registered_user_id'];
			$this->admin_registered_entity_code=$session['admin_registered_entity_code'];
			$this->main_role=$session['main_role'];
        }
    }
  
    public function create_company(){
		$data['page_title']="Manage Entity";
		$this->load->view('create-company',$data);
    }

    public function check_company_shortcode(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $company_shortcode=$this->input->post('company_shortcode');
        $data = $this->Admin_model->check_company_shortcode($company_shortcode,$register_user_id,$entity_code);
        echo $data;
    }

    
    public function save_company_data(){
        $data=array(
            "company_name"=>$this->input->post('company_name'),
            "short_code"=>$this->input->post('short_code'),
            "created_by"=>$this->id,
            "registered_user_id"=>$this->admin_registered_user_id,
            "entity_code"=>$this->admin_registered_entity_code,
            "created_at"=>date("Y-m-d H:i:s")
        );
        $data = $this->Admin_model->save_company($data);
        $this->session->set_flashdata('success', "Company Created Successfully");
        redirect("index.php/manage-entity");
    }

    public function manage_entity(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['page_title']="Manage Entity";
        $data['companydata'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);
		$this->load->view('manage-entity',$data);
    }

    public function edit_entity($company_id){
        $data['page_title']="Manage Entity";
        $data['companydata'] = $this->Admin_model->get_single_company($company_id);
		$this->load->view('edit-entity',$data);
    }

    public function edit_save_entity(){
       $company_id =  $this->input->post('company_id');
        $data= array(
            "company_name"=>$this->input->post('company_name'),
            "short_code"=>$this->input->post('short_code'),
            "last_edited_by"=>$this->id,
            "updated_at"=>date("Y-m-d H:i:s")
        );
        $this->Admin_model->save_edit_company($data,$company_id);
        $this->session->set_flashdata('success', "Company Updated Successfully");
        redirect("index.php/edit-entity/".$company_id);
    }

    public function manage_location(){
        $data['page_title']="Manage Location";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['locationdata'] = $this->Admin_model->get_all_location($register_user_id,$entity_code);
		$this->load->view('manage-location',$data);
    }

    public function admin_create_location(){
        $data['page_title']="Manage Location";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['company_data'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);
        $this->load->view('create-location',$data);
    }

    public function check_location_shortcode(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $location_shortcode=$this->input->post('location_shortcode');
        $company_id=$this->input->post('company_id');
        $data = $this->Admin_model->check_location_shortcode($location_shortcode,$register_user_id,$entity_code,$company_id);
        echo $data;
    }

    public function save_location_data(){
        $data=array(
            "company_id"=>$this->input->post('company_id'),
            "location_name"=>$this->input->post('location_name'),
            "location_shortcode"=>$this->input->post('location_shortcode'),
            "created_by"=>$this->id,
            "registered_user_id"=>$this->admin_registered_user_id,
            "entity_code"=>$this->admin_registered_entity_code,
            "created_at"=>date("Y-m-d H:i:s")
        );
        $data = $this->Admin_model->save_location($data);
        $this->session->set_flashdata('success', "Location Created Successfully");
        redirect("index.php/manage-location");
    }

    public function edit_location($location_id){
        $data['page_title']="Manage Location";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['company_data'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);
        $data['location_data'] = $this->Admin_model->get_single_location($location_id);
		$this->load->view('edit-location',$data);
    }


    public function edit_save_location(){
        $location_id =  $this->input->post('location_id');
         $data= array(
            "company_id"=>$this->input->post('company_id'),
            "location_name"=>$this->input->post('location_name'),
            "location_shortcode"=>$this->input->post('location_shortcode'),
             "edited_by"=>$this->id,
             "updated_at"=>date("Y-m-d H:i:s")
         );
         $this->Admin_model->save_edit_location($data,$location_id);
         $this->session->set_flashdata('success', "Location Updated Successfully");
         redirect("index.php/edit-location/".$location_id);
     }


     public function check_location_company_cnt(){
        $company_id=$this->input->post('company_id');

        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        //get registerd user data//
            $data_user = $this->Admin_model->get_registered_user_row($register_user_id,$entity_code);
            $plan_id=$data_user->plan_id;
        //get registerd user data//

      //  get_total_company_row_entity//
        $data_company_cnt = $this->Admin_model->get_total_company_row_entity($register_user_id,$entity_code,$company_id);
     //  get_total_company_row_entity//
 

        //get plan user data//
            $data_plan = $this->Admin_model->get_plan_row($plan_id);
            $location_each_entity=$data_plan->location_each_entity;
        //get plan user data//
        if($data_company_cnt >= $location_each_entity){
            echo "1";
        }else{
            echo "0";
        }
     }
    
     public function manage_department(){
        $data['page_title']="Manage Department";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['department'] = $this->Admin_model->get_all_department($register_user_id,$entity_code);
		$this->load->view('manage-department',$data);
    }


    public function create_department(){
        
        $data['page_title']="Manage Department";
        $this->load->view('create-department',$data);
    }
    

    
    public function save_department(){
        $data=array(
            "department_name"=>$this->input->post('department_name'),
            "department_shortcode"=>$this->input->post('department_shortcode'),
            "create_by"=>$this->id,
            "registered_user_id"=>$this->admin_registered_user_id,
            "entity_code"=>$this->admin_registered_entity_code,
            "created_at"=>date("Y-m-d H:i:s")
        );
        $data = $this->Admin_model->save_department($data);
        $this->session->set_flashdata('success', "Department Created Successfully");
        redirect("index.php/manage-department");
    }

    public function edit_department($department_id){
        $data['page_title']="Manage Department";
        $data['department_data'] = $this->Admin_model->get_single_department($department_id);
		$this->load->view('edit-department',$data);
    }
    
    
    public function edit_department_save(){
        $department_id =  $this->input->post('department_id');
         $data= array(
             "department_name"=>$this->input->post('department_name'),
            "department_shortcode"=>$this->input->post('department_shortcode'),
             "edited_by"=>$this->id,
             "updated_at"=>date("Y-m-d H:i:s")
         );
         $this->Admin_model->save_edit_department($data,$department_id);
         $this->session->set_flashdata('success', "Department Updated Successfully");
         redirect("index.php/edit-department/".$department_id);
     }

     public function check_department_name(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $department_name=$this->input->post('department_name');
        $data = $this->Admin_model->check_department_name($department_name,$register_user_id,$entity_code);
        echo $data;
    }

    public function manage_user_admin(){
        $data['page_title']="Manage User";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['user'] = $this->Admin_model->get_all_user($register_user_id,$entity_code);
		$this->load->view('manage-user',$data);
    }

    public function admin_create_user(){

        
        $data['page_title']="Manage User";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['company_data_list'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);
        $data['department'] = $this->Admin_model->get_all_department($register_user_id,$entity_code);
        $this->load->view('create-user',$data);
    }

    public function save_admin_user(){
       $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $user_id = $this->user_id;

        $digits = 5;
        $temp_password = rand(pow(10, $digits-1), pow(10, $digits)-1);
        // exit();

        $data=array(
            "created_by"=>$user_id,
            "registered_user_id"=>$register_user_id,
            "entity_code"=>$entity_code,
            "firstName" => $this->input->post('firstName'),
            "lastName"=> $this->input->post('lastName'),
            "userEmail"=>$this->input->post('userEmail'),
            // "password"=>md5('12345'),
            "password"=>md5($temp_password),
            "password_view"=>$temp_password,
            "phone_no"=>$this->input->post('phone_no'),
            "department_id"=>$this->input->post('department_id'),
            "designation"=>$this->input->post('designation'),
            "company_id"=>$this->input->post('company_id'),
            "location_id"=>$this->input->post('location_id'),
            "created_on"=>date("Y-m-d H:i:s")
        );
        $this->Admin_model->save_admin_user($data);

        $this->session->set_flashdata('success', "User Created Successfully");
        redirect("index.php/manage-user-admin/");
    }

    public function check_admin_userEmail(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $userEmail=$this->input->post('userEmail');
        $data = $this->Admin_model->check_userEmail_admin($userEmail,$register_user_id,$entity_code);
        echo $data;
    }

    public function edit_admin_user($user_id){
        $data['page_title']="Manage User";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['department'] = $this->Admin_model->get_all_department($register_user_id,$entity_code);
        $data['company_data_list'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);
        $data['user_role'] = $this->Admin_model->get_userroles($user_id);
        $data['user'] = $this->Admin_model->get_single_user($user_id);
        $this->load->view('edit-user',$data);
    }

    public function edit_admin_user_save(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $user_id =  $this->input->post('user_id');
 
         $data=array(
             "edited_by"=>$user_id,
             "firstName" => $this->input->post('firstName'),
             "lastName"=> $this->input->post('lastName'),
             "userEmail"=>$this->input->post('userEmail'),
             "phone_no"=>$this->input->post('phone_no'),
             "department_id"=>$this->input->post('department_id'),
             "designation"=>$this->input->post('designation'),
             "company_id"=>$this->input->post('company_id'),
             "location_id"=>$this->input->post('location_id'),
             "profile_update"=>'1',
             "updated_on"=>date("Y-m-d H:i:s")
          );
         $this->Admin_model->save_edit_admin_user($data,$user_id);
         $this->session->set_flashdata('success', "User Updated Successfully");
         redirect("index.php/edit-admin-user/".$user_id);
     }

     public function manage_user_role(){
        $data['page_title']="User Maping";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['user_role'] = $this->Admin_model->get_all_user_role($register_user_id,$entity_code);
        $this->load->view('manage-user_role',$data);
     }

     public function add_user_role(){
        $data['page_title']="User Maping";
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['user'] = $this->Admin_model->get_all_user($register_user_id,$entity_code);
        $data['comapny'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);
        $this->load->view('add-user-role',$data);

     }

     public function assign_user_role_save(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $user_created_by=$this->user_id;

        $company_id=$this->input->post('company_id');
        $location_id=$this->input->post('location_id');
        $user_id=$this->input->post('user_id');

        $check_role =$this->Admin_model->check_user_role_location_cmpany($company_id,$location_id,$user_id);

      if($check_role == 1){
          $this->session->set_flashdata('success', "User Already Assign Role This Company Location.");
         redirect("index.php/add-user-role/");
      }else{
        $data=array(
         "user_id"=>$this->input->post('user_id'),
         "company_id"=>$this->input->post('company_id'),
         "location_id"=>$this->input->post('location_id'),
         "user_role"=>implode(',',$this->input->post('user_role')),
         "registered_user_id"=>$register_user_id,
         "entity_code"=>$entity_code,
         "created_by"=>$user_created_by,
         "created_at"=>date("Y-m-d H:i:s")
        );
         $this->Admin_model->save_user_role($data);
         $this->session->set_flashdata('success', "Role Assign Successfully");
         redirect("index.php/add-user-role/");
          }

     }

     public function get_company_location(){
        $company_id=$this->input->post('company_id');
        $location_data= $this->Admin_model->get_company_all_location($company_id);
          $option ="<option value=''>Select Location</option>";
          foreach ($location_data as  $row) {
             $option .="<option value='".$row->id."'>".$row->location_name."(".$row->location_shortcode.")</option>";

       }
       echo  $option;
   }

   public function get_company_location_sub_admin(){
    $company_id=$this->input->post('company_id');
    $location_id=$this->input->post('location_id');
    $location_data= $this->Admin_model->get_company_all_location_subadmin($company_id,$location_id);


      $option ="<option value=''>Select Location</option>";
      foreach ($location_data as  $row) {
         $option .="<option value='".$row->id."'>".$row->location_name."(".$row->location_shortcode.")</option>";

   }
   echo  $option;
}


   public function get_company_location_user(){
    $company_id=$this->input->post('company_id');
    $location_id=$this->input->post('location_id');
    $userdata= $this->Admin_model->get_company_location_user($company_id,$location_id);
    $entity_code=$this->admin_registered_entity_code;

    $option ="<option value=''>Select User</option>";
    foreach ($userdata as  $row) {

        $user_id=$row->user_id;
        $user_role_addmin_cnt=get_user_role_cnt_admin($user_id,$entity_code);
            $user_row= get_user_row($row->user_id);
            $option .="<option value='".$user_row->id."'>".$user_row->firstName." ".$user_row->lastName."</option>";
            
            }
            echo  $option;
   }

public function get_user_row(){
         $user_id=$this->input->post('user_id');
         $userdata= $this->Admin_model->get_user_row($user_id);
          $rowdata='';
           foreach ($userdata as  $row) {
            if($row->department_id =='0'){
                $department_name='';
            }else{
            $department_row=$this->Admin_model->get_department_row($row->department_id);
            $department_name=$department_row->department_name;

            }
            if($row->company_id !=''){
                $company_row=get_company_row($row->company_id);
                $company_name = $company_row->company_name;
            }else{
                $company_name ="";
            }

            if($row->location_id !='0'){
                $location_row=get_location_row($row->location_id);               
                 $location_name = $location_row->location_name;
            }else{
                $location_name ="";
            }
           
             $rowdata .='
            <div class="row">
             <div class="col-md-4 form-row">
              <label class="form-label">First Name</label>'.
              $row->firstName.'
             </div>
             <div class="col-md-4 form-row">
              <label class="form-label">Last Name</label>'.
              $row->lastName.'
             </div>

             <div class="col-md-4 form-row">
              <label class="form-label">Email ID</label>'.
              $row->userEmail.'
             </div>

             </div><br>
             <div class="row">
             <div class="col-md-4 form-row">
              <label class="form-label">Phone Number</label>'.
              $row->phone_no.'
             </div>
            
             <div class="col-md-4 form-row">
              <label class="form-label">Department</label>'.
              $department_name.'
             </div>
             <div class="col-md-4 form-row">
              <label class="form-label">Designation</label>'.
              $row->designation.'
             </div>
             </div><br>
             <div class="row">
             <div class="col-md-4 form-row">
              <label class="form-label">Company</label>'.
              $company_name.'
             </div>
            
             <div class="col-md-4 form-row">
              <label class="form-label">Location</label>'.
              $location_name.'
             </div>
             </div><br>';

       }
       echo $rowdata;
}
    
public function edit_user_role($role_id){
    $data['page_title']="User Maping";
    $register_user_id = $this->admin_registered_user_id;
    $entity_code = $this->admin_registered_entity_code;
    $data['user'] = $this->Admin_model->get_all_user($register_user_id,$entity_code);
    $data['comapny'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);
    $data['role_data'] = $this->Admin_model->get_role_single($role_id);
    $this->load->view('edit-user-role',$data);
}


public function edit_user_role_save(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $user_updated_by=$this->user_id;

        $company_id=$this->input->post('company_id');
        $location_id=$this->input->post('location_id');
        $user_id=$this->input->post('user_id');
        $role_id=$this->input->post('role_id');
if(!empty($this->input->post('user_role'))){
$role=implode(',',$this->input->post('user_role'));
}else{
    $role='';
}
        $data=array(
         "user_id"=>$this->input->post('user_id'),
         "company_id"=>$this->input->post('company_id'),
         "location_id"=>$this->input->post('location_id'),
         "user_role"=>$role,
         "registered_user_id"=>$register_user_id,
         "entity_code"=>$entity_code,
         "edited_by"=>$user_updated_by,
         "updated_at"=>date("Y-m-d H:i:s")
        );
         $this->Admin_model->edit_save_user_role($data,$role_id);
         $this->session->set_flashdata('success', "Role Updated Successfully");
         redirect("index.php/manage-user-role");
          
     }

     public function my_profile(){
        $data['page_title']="My Profile";
        $user_id=$this->user_id;
        $data["profile_data"]=$this->Admin_model->my_data($user_id);
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['department'] = $this->Admin_model->get_all_department($register_user_id,$entity_code);
        $data['company_data_list'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);

        $this->load->view('my-profile',$data);
     }

     public function save_my_profile(){
        $user_id=$this->input->post('user_id');
        // $data=array(
        //     "firstName"=>$this->input->post('firstName'),
        //     "lastName"=>$this->input->post('lastName'),
        //     "phone_no"=>$this->input->post('phone_no'),
        // );

        $data=array(
            "firstName" => $this->input->post('firstName'),
            "lastName"=> $this->input->post('lastName'),
            "phone_no"=>$this->input->post('phone_no'),
            "department_id"=>$this->input->post('department_id'),
            "designation"=>$this->input->post('designation'),
            "company_id"=>$this->input->post('company_id'),
            "location_id"=>$this->input->post('location_id'),
            "profile_update"=>'1',
            "updated_on"=>date("Y-m-d H:i:s")
         );

        $this->db->where("id",$user_id);
        $this->db->update("users",$data);
        $this->session->set_flashdata('success', "Profile Updated Successfully");
        redirect("index.php/my-profile");   
      }

      public function change_my_password(){
        $data['page_title']="My Profile";
        $this->load->view('change-my-password',$data);
      }

      public function admin_user_check_pass(){
      $user_id=$this->user_id;
      $current_pass=$this->input->post('current_pass');
      $rowpass= $this->Admin_model->check_current_pass($user_id,$current_pass);
      echo $rowpass;
      }


      public function admin_user_passwod_save(){

        $user_id=$this->user_id;
        $data=array( 
            "password"=>md5($this->input->post('password')),
            "password_view"=>$this->input->post('password'),
        );
        $this->Admin_model->update_password($user_id,$data);


        $user_id=$this->session->userdata('admin_registered_user_id');
        $data=array( 
            "password"=>md5($this->input->post('password')),
            "password_view"=>$this->input->post('password'),
        );
        $this->Registered_user_model->update_password($user_id,$data);


     $this->session->set_flashdata("success","Password Changed Successfully");
     redirect("index.php/change-my-password");
    }

    public function manage_notification(){
        $data['page_title']="Manage Notification";
        $entity_code=$this->admin_registered_entity_code;

        
        $user_id=$this->user_id;

        $this->db->select('notification_user.*,notification.*');
        $this->db->from('notification');
        $this->db->join('notification_user','notification_user.notification_id=notification.id');
        // $this->db->where('notification_user.user_id',$user_id);
        $this->db->where('notification.created_by',$user_id);
        $this->db->group_by('notification_user.notification_id'); 
        $getnotifications=$this->db->get();
       
        $data["notification"] =  $getnotifications->result();
        $this->load->view('manage-notification',$data);
    }

    public function manage_notification2(){
        $data['page_title']="Manage Notification";
        $entity_code=$this->admin_registered_entity_code;

        
        $user_id=$this->user_id;

        $this->db->select('notification_user.*,notification.*');
        $this->db->from('notification');
        $this->db->join('notification_user','notification_user.notification_id=notification.id');
        $this->db->where('notification_user.user_id',$user_id);
        // $this->db->where('notification.created_by',$user_id);
        $this->db->group_by('notification_user.notification_id'); 
        $getnotifications=$this->db->get();
       
        $data["notification"] =  $getnotifications->result();
        $this->load->view('manage-notification',$data);
    }
    /*
    public function brodcast_notification(){
        $data['page_title']="Manage Notification";
        $entity_code=$this->admin_registered_entity_code;
        $this->load->view('brodcast-notification',$data);
    } */

    public function brodcast_notification(){
        $data['page_title']="Manage Notification";

        $company_id = $_SESSION['logged_in']['company_id'];
        $admin_registered_user_id = $_SESSION['logged_in']['admin_registered_user_id'];
        $admin_registered_entity_code = $_SESSION['logged_in']['admin_registered_entity_code'];
        

        $all_users_roles = $this->Admin_model->get_all_user_role_company_registerid_entity($company_id,$admin_registered_user_id,$admin_registered_entity_code);
        $user_roles = array();
        $user_id = array();
        foreach($all_users_roles as $all_users_role_key=>$all_users_role_value){
            $user_roles_value = explode(",",$all_users_role_value->user_role);
            foreach($user_roles_value as $user_roles_key=>$user_roles_value){
                $user_roles[] = $user_roles_value;
            }
            $user_id[$all_users_role_value->user_id] = $all_users_role_value->user_role;
        }
       $all_GroupAdmin = array();
       $all_SubAdmin = array();
       $all_EntityOwner = array();
       $all_ProcessOwner = array();
       $all_Manager = array();
       $all_Verify = array();

       $entity_code = $_SESSION['logged_in']['admin_registered_entity_code'];
    
        if(!empty($user_roles)){
            foreach(array_unique($user_roles) as $user_role_key=>$user_role_value){
                if($user_role_value == '5'){
                    // $all_GroupAdmin = $this->Admin_model->get_users_by_role($user_role_value);
                    $all_GroupAdmin_roles = $this->Admin_model->get_all_user_of_role_by_entity($user_role_value,$entity_code);
                    $all_GroupAdmin_array = array();
                    foreach($all_GroupAdmin_roles as $all_GroupAdmin_roleskey=>$all_GroupAdmin_rolesvalue){
                        $all_GroupAdmin_roles_array[] = $all_GroupAdmin_rolesvalue->user_id;
                    }
                    $all_GroupAdmin = $this->Admin_model->get_users_by_ids(implode(',', $all_GroupAdmin_roles_array));
                }

                if($user_role_value == '4'){
                    $all_SubAdmin_roles = $this->Admin_model->get_all_user_of_role_by_entity($user_role_value,$entity_code);
                    $all_SubAdmin_roles_array = array();
                    foreach($all_SubAdmin_roles as $all_SubAdmin_roleskey=>$all_SubAdmin_rolesvalue){
                        $all_SubAdmin_roles_array[] = $all_SubAdmin_rolesvalue->user_id;
                    }
                    $all_SubAdmin = $this->Admin_model->get_users_by_ids(implode(',', $all_SubAdmin_roles_array));
                }
                if($user_role_value == '3'){
                    // $all_EntityOwner = $this->Admin_model->get_users_by_role($user_role_value);
                    $all_EntityOwner_roles = $this->Admin_model->get_all_user_of_role_by_entity($user_role_value,$entity_code);
                    $all_EntityOwner_roles_array = array();
                    foreach($all_EntityOwner_roles as $all_EntityOwner_roleskey=>$all_EntityOwner_rolesvalue){
                        $all_EntityOwner_roles_array[] = $all_EntityOwner_rolesvalue->user_id;
                    }
                    $all_EntityOwner = $this->Admin_model->get_users_by_ids(implode(',', $all_EntityOwner_roles_array));
                }
                if($user_role_value == '2'){
                    // $all_ProcessOwner = $this->Admin_model->get_users_by_role($user_role_value);
                    $all_ProcessOwner_roles = $this->Admin_model->get_all_user_of_role_by_entity($user_role_value,$entity_code);
                    $all_ProcessOwner_roles_array = array();
                    foreach($all_ProcessOwner_roles as $all_ProcessOwner_roleskey=>$all_ProcessOwner_rolesvalue){
                        $all_ProcessOwner_roles_array[] = $all_ProcessOwner_rolesvalue->user_id;
                    }
                    $all_ProcessOwner = $this->Admin_model->get_users_by_ids(implode(',', $all_ProcessOwner_roles_array));
                }
                if($user_role_value == '0'){
                    // $all_Manager = $this->Admin_model->get_users_by_role($user_role_value);
                    $all_Manager_roles = $this->Admin_model->get_all_user_of_role_by_entity($user_role_value,$entity_code);
                    $all_Manager_roles_array = array();
                    foreach($all_Manager_roles as $all_Manager_roleskey=>$all_Manager_rolesvalue){
                        $all_Manager_roles_array[] = $all_Manager_rolesvalue->user_id;
                    }
                    $all_Manager = $this->Admin_model->get_users_by_ids(implode(',', $all_Manager_roles_array));
                }
                if($user_role_value == '1'){
                    // $all_Verify = $this->Admin_model->get_users_by_role($user_role_value);
                    $all_Verify_roles = $this->Admin_model->get_all_user_of_role_by_entity($user_role_value,$entity_code);
                    $all_Verify_roles_array = array();
                    foreach($all_Verify_roles as $all_Verify_roleskey=>$all_Verify_rolesvalue){
                        $all_Verify_roles_array[] = $all_Verify_rolesvalue->user_id;
                    }
                    $all_Verify = $this->Admin_model->get_users_by_ids(implode(',', $all_Verify_roles_array));
                    // las
                }
            }
        }
        $data['all_GroupAdmin'] = $all_GroupAdmin;
        $data['all_SubAdmin'] = $all_SubAdmin;
        $data['all_EntityOwner'] = $all_EntityOwner;
        $data['all_ProcessOwner'] = $all_ProcessOwner;
        $data['all_Manager'] = $all_Manager;
        $data['all_Verify'] = $all_Verify;
        $entity_code=$this->admin_registered_entity_code;
        $this->load->view('brodcast-notification',$data);
    }

    /*
    public function save_brodcast_notification(){
        $data['page_title']="Manage Notification";
        $entity_code=$this->admin_registered_entity_code;
        $registered_user_id=$this->admin_registered_user_id;
        $created_by=$this->user_id;

        $data=array(
            "type"=>$this->input->post("type"),
            "title"=>$this->input->post("title"),
            "description"=>$this->input->post("description"),
            "entity_code"=>$entity_code,
            "registered_user_id"=>$registered_user_id,
            "created_by"=>$created_by,
            "created_at"=>date("Y-m-d H:i:s")
         );
        $data["notification"]=$this->Admin_model->save_notification($data);

        $this->session->set_flashdata("success","Notification Brodcast Successfully");
        redirect("index.php/manage-notification");
    }
    */
    public function save_brodcast_notification(){
        $data['page_title']="Manage Notification";
        $entity_code=$this->admin_registered_entity_code;
        $registered_user_id=$this->admin_registered_user_id;
        $created_by=$this->user_id;

        $data=array(
            "type"=>$this->input->post("type"),
            "title"=>$this->input->post("title"),
            "description"=>$this->input->post("description"),
            "entity_code"=>$entity_code,
            "registered_user_id"=>$registered_user_id,
            "created_by"=>$created_by,
            "created_at"=>date("Y-m-d H:i:s")
        );
        $data["notification"]=$this->Admin_model->save_notification($data);
        $insert_id = $this->db->insert_id();
        
        $notification_type = $this->input->post("type");
        if($notification_type == 'Notification'){

            if(isset($_POST['selectGropAdmin'])){
                $selectGropAdmin = $this->input->post("selectGropAdmin");
                if(!empty($selectGropAdmin)){
                    foreach($selectGropAdmin as $selectGropAdmin_key=>$selectGropAdmin_value){
                        $save_notification_user_assign_data=array(
                            "notification_id"=>$insert_id,
                            "user_id"=>$selectGropAdmin_value,
                            "user_role"=>5,
                        );
                        $this->Admin_model->save_notification_user_assign($save_notification_user_assign_data);
                    }
                }
            }

            if(isset($_POST['selectSubAdmin'])){
                $selectSubAdmin = $this->input->post("selectSubAdmin");
                if(!empty($selectSubAdmin)){
                    foreach($selectSubAdmin as $selectSubAdmin_key=>$selectSubAdmin_value){
                        $save_notification_user_assign_data=array(
                            "notification_id"=>$insert_id,
                            "user_id"=>$selectSubAdmin_value,
                            "user_role"=>4,
                        );
                        $this->Admin_model->save_notification_user_assign($save_notification_user_assign_data);
                    }
                }
            }

            if(isset($_POST['selectEntityOwner'])){
                $selectEntityOwner = $this->input->post("selectEntityOwner");
                if(!empty($selectEntityOwner)){
                    foreach($selectEntityOwner as $selectEntityOwner_key=>$selectEntityOwner_value){
                        $save_notification_user_assign_data=array(
                            "notification_id"=>$insert_id,
                            "user_id"=>$selectEntityOwner_value,
                            "user_role"=>3,
                        );
                        $this->Admin_model->save_notification_user_assign($save_notification_user_assign_data);
                    }
                }
            }

            if(isset($_POST['selectProcessOwner'])){
                $selectProcessOwner = $this->input->post("selectProcessOwner");
                if(!empty($selectProcessOwner)){
                    foreach($selectProcessOwner as $selectProcessOwner_key=>$selectProcessOwner_value){
                        $save_notification_user_assign_data=array(
                            "notification_id"=>$insert_id,
                            "user_id"=>$selectProcessOwner_value,
                            "user_role"=>2,
                        );
                        $this->Admin_model->save_notification_user_assign($save_notification_user_assign_data);
                    }
                }
            }
            
            if(isset($_POST['selectManager'])){
                $selectManager = $this->input->post("selectManager");
                if(!empty($selectManager)){
                    foreach($selectManager as $selectManager_key=>$selectManager_value){
                        $save_notification_user_assign_data=array(
                            "notification_id"=>$insert_id,
                            "user_id"=>$selectManager_value,
                            "user_role"=>0,
                        );
                        $this->Admin_model->save_notification_user_assign($save_notification_user_assign_data);
                    }
                }
            }
            
            if(isset($_POST['selectVerify'])){
                $selectVerify = $this->input->post("selectVerify");
                if(!empty($selectVerify)){
                    foreach($selectVerify as $selectVerify_key=>$selectVerify_value){
                        $save_notification_user_assign_data=array(
                            "notification_id"=>$insert_id,
                            "user_id"=>$selectVerify_value,
                            "user_role"=>1,
                        );
                        $this->Admin_model->save_notification_user_assign($save_notification_user_assign_data);
                    }
                }
            }
            
        }
        // exit();
        $this->session->set_flashdata("success","Notification Brodcast Successfully");
        redirect("index.php/manage-notification");
    }

    public function view_notification($id){
        $data['page_title']="Manage Notification";
        $created_by=$this->user_id;

        if($this->input->get('main_not') && $this->input->get('main_not') == '1'){
            $datamainread=array(
                "notification_id"=>$id,
                "user_id"=>$created_by,
                "is_read"=>'1'
               );
           $this->Admin_model->update_main_not_read($datamainread,$id,$created_by);
        }

        if($this->input->get('reply_msg') && $this->input->get('reply_msg') == '1'){
            $datamainreply=array(
                "reply_message_id"=>$this->input->get('reply_msg_id'),
                "user_id"=>$created_by,
                "is_read"=>'1'
               );
           $this->Admin_model->update_reply_not_read($datamainreply,$this->input->get('reply_msg_id'),$created_by);
        }

        $data['notification_data']=$this->Admin_model->get_single_notification($id);
        $data['reply_data']=$this->Admin_model->get_reply_data($id);

        $data['sender_to_user'] = array();

        if($_SESSION['logged_in']['id'] == $data['notification_data']->created_by){
            $this->db->select('notification_user.*,users.*');
            $this->db->from('users');
            $this->db->join('notification_user','notification_user.user_id=users.id');
            $this->db->where('notification_user.notification_id',$data['notification_data']->id);
            $this->db->order_by('notification_user.user_role','DESC');
            $getnotifications=$this->db->get();
            $result =  $getnotifications->result();
            $data['sender_to_user'] = $result;
        }
        $this->load->view('view-reply-notification',$data);
    }


    public function save_notification_reply(){
        $data['page_title']="Manage Notification";
        $entity_code=$this->admin_registered_entity_code;
        $registered_user_id=$this->admin_registered_user_id;
        $created_by=$this->user_id;

        $data=array(
            "notification_id"=>$this->input->post("notification_id"),
            "reply_message"=>$this->input->post("reply_message"),
            "reply_from"=>$created_by,
            "reply_at"=>date("Y-m-d H:i:s")
         );
        $data["notification"]=$this->Admin_model->save_notification_reply($data);

        $this->session->set_flashdata("success","Message Submitted Successfully");
        redirect("index.php/view-reply-notofication/".$this->input->post("notification_id"));
    }    
    

    public function view_all_notification(){
        $data['page_title']="Manage Notification";
        
        $this->load->view('view-all-notification.php',$data);
    }
    

    public function delete_entity($id){
        $this->db->where('id',$id);
        $this->db->delete('company');
        $this->delete_location($id);
        $this->session->set_flashdata("success","Deleted Successfully");
      redirect('index.php/manage-entity');
    }
    public function delete_location($id){
        $this->db->where('company_id',$id);
        $this->db->delete('company_locations');
    }


    public function delete_location_location($id){
        $this->db->where('id',$id);
        $this->db->delete('company_locations');
        $this->session->set_flashdata("success","Deleted Successfully");
        redirect('index.php/manage-location');
    }


    public function check_department_shortcode(){
        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $department_shortcode=$this->input->post('department_shortcode');
        $data = $this->Admin_model->check_department_shortcode($department_shortcode,$register_user_id,$entity_code);
        echo $data;
    }
    

    public function delete_department($id){
        $this->db->where('id',$id);
        $this->db->delete('department');
        $this->session->set_flashdata("success","Deleted Successfully");
        redirect('index.php/manage-department');
    }

    public function get_role(){
        $user_id=$this->input->post('user_id');
        $company_id=$this->input->post('company_id');
        $location_id=$this->input->post('location_id');
        $data = $this->Admin_model->get_role_user($user_id,$company_id,$location_id);
        echo json_encode($data);
    }
    
    public function delete_user($id){
        $this->db->where('id',$id);
        $this->db->delete('users');
        $this->session->set_flashdata("success","Deleted Successfully");
        redirect('index.php/manage-user-admin');
    }



    public function request_for_delete(){
        
        $project_id = $this->input->post("hdn_project_id");
        $data=array(
            "status" => 4,
         );

        $this->db->where("id",$project_id);
        $this->db->update("company_projects",$data);
        
        $data=array(
            "project_id"=>$this->input->post("hdn_project_id"),
            "reason_for_delete"=>$this->input->post("reason_for_detele"),
            "requester_id"=>$this->input->post("hdn_user_id"),
            "status"=>1            
        );
        $data["notification"]=$this->Admin_model->save_delete_request($data);
        $insert_id = $this->db->insert_id();

        $this->session->set_flashdata('success', "Delete Requesting Successfull..");
        redirect("index.php/dashboard");
        
    }




    public function reset_user_login($id){
        $data=array(
            "is_login" => 0,
        );
        $this->db->where("id",$id);
        $this->db->update("users",$data);

        $this->session->set_flashdata("success","Login Reset Successfully");
        redirect('index.php/manage-user-admin');
    }


    public function manage_issue_for_me($role = 'groupadmin') {
	    $user_id = $_SESSION['logged_in']['id']; // Get logged-in user ID
	    $data['page_title'] = "Manage Issue";

	    // Fetch issues based on role
	    $data['issue'] = $this->Admin_model->get_issues_by_role($user_id, $role);

	    $data['selected_role'] = $role; // Pass role to view for highlighting button

	    $this->load->view('issue-list', $data);
	}
    public function manage_my_issue(){

       
        $data['page_title']="Manage Issue 2";
        $entity_code=$this->admin_registered_entity_code;
        $data["issue"]=$this->Admin_model->get_all_my_issue2($_SESSION['logged_in']['id']);
        $this->load->view('issue-list',$data);
    }

    public function add_issue(){

        $company_id = $_SESSION['logged_in']['company_id'];
        $user_id = $_SESSION['logged_in']['id'];
        $registered_user_id = $_SESSION['logged_in']['admin_registered_user_id'];

        $this->db->select('company_projects.*');
        $this->db->from('company_projects');
        $this->db->where('company_projects.company_id',$company_id);
        $gettasks=$this->db->get();
        $company_project_result = $gettasks->result();


        $register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;
        $data['locationdata'] = $this->Admin_model->get_all_location($register_user_id,$entity_code);

        $data['page_title']="Manage Issue";

        $company_id = $_SESSION['logged_in']['company_id'];
        $admin_registered_user_id = $_SESSION['logged_in']['admin_registered_user_id'];
        $admin_registered_entity_code = $_SESSION['logged_in']['admin_registered_entity_code'];

        $all_users_roles = $this->Admin_model->get_all_user_role_company_registerid_entity($company_id,$admin_registered_user_id,$admin_registered_entity_code);
        $user_roles = array();
        $user_id = array();
        foreach($all_users_roles as $all_users_role_key=>$all_users_role_value){
            $user_roles_value = explode(",",$all_users_role_value->user_role);
            foreach($user_roles_value as $user_roles_key=>$user_roles_value){
                $user_roles[] = $user_roles_value;
            }
            $user_id[$all_users_role_value->user_id] = $all_users_role_value->user_role;
        }
       $all_GroupAdmin = array();
       $all_SubAdmin = array();
       $all_EntityOwner = array();
       $all_ProcessOwner = array();
       $all_Manager = array();
       $all_Verify = array();

       $entity_code = $_SESSION['logged_in']['admin_registered_entity_code'];
       
        if(!empty($user_roles)){
            foreach(array_unique($user_roles) as $user_role_key=>$user_role_value){
                if($user_role_value == '5'){
                    // $all_GroupAdmin = $this->Admin_model->get_users_by_role($user_role_value);
                    $all_GroupAdmin_roles = $this->Admin_model->get_all_user_of_role_by_entity($user_role_value,$entity_code);
                    $all_GroupAdmin_array = array();
                    foreach($all_GroupAdmin_roles as $all_GroupAdmin_roleskey=>$all_GroupAdmin_rolesvalue){
                        $all_GroupAdmin_roles_array[] = $all_GroupAdmin_rolesvalue->user_id;
                    }
                    $all_GroupAdmin = $this->Admin_model->get_users_by_ids(implode(',', $all_GroupAdmin_roles_array));
                }
                if($user_role_value == '0'){
                    // $all_Manager = $this->Admin_model->get_users_by_role($user_role_value);
                    $all_Manager_roles = $this->Admin_model->get_all_user_of_role_by_entity($user_role_value,$entity_code);
                    $all_Manager_roles_array = array();
                    foreach($all_Manager_roles as $all_Manager_roleskey=>$all_Manager_rolesvalue){
                        $all_Manager_roles_array[] = $all_Manager_rolesvalue->user_id;
                    }
                    $all_Manager = $this->Admin_model->get_users_by_ids(implode(',', $all_Manager_roles_array));
                }
                
            }
        }
        $data['all_GroupAdmin'] = $all_GroupAdmin;
        $data['all_Manager'] = $all_Manager;
        $data['company_project'] = $company_project_result;
        $entity_code=$this->admin_registered_entity_code;

        $data['comapny'] = $this->Admin_model->get_all_company($register_user_id,$entity_code);


        $user_role = 5; //Group Admin
		$register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;

		$group_admin = $this->db->query('SELECT user_role.*,users.* from user_role INNER JOIN users ON users.id=user_role.user_id where FIND_IN_SET('.$user_role.',user_role) AND user_role.entity_code="'.$entity_code.'"')->result();

        $data['group_admin'] = $group_admin;
      
        $this->load->view('issue-form',$data);
    }

    public function save_issue(){
        $created_by=$this->user_id;
        $random_number = rand(10000,99999);
        $tracking_id_value = date('ymd').$random_number;
        

        $config['upload_path'] = './issueattachment/';
        $config['allowed_types'] = '*';
		$config['encrypt_name']=true;

        $this->load->library('upload', $config);
		$issue_attachment='';
        if (!$this->upload->do_upload('issue_attachment')) {
            $error = array('error' => $this->upload->display_errors());
			print_r($error);
			exit;
        } else {
            $data = $this->upload->data();
			$issue_attachment=$data['file_name'];
		}


        $resolved_by = $this->input->post("groupadmin_name");
        if($this->input->post("issue_type") == 'projectbase'){
            $resolved_by = $this->input->post("manage_name");
        }
       

        $data=array(
            "tracking_id"=>$tracking_id_value,
            "issue_type"=>$this->input->post("issue_type"),
            "company_name"=>$this->input->post("company_name"),
            "location_name"=>$this->input->post("location_name"),
            "project_name"=>$this->input->post("project_name"),

            "manage_name"=>$this->input->post("manage_name"),
            "groupadmin_name"=>$this->input->post("groupadmin_name"),
            "issue_title"=>$this->input->post("issue_title"),
            "issue_description"=>$this->input->post("issue_description"),
            "issue_attachment"=>$issue_attachment,
            
            "created_by"=>$created_by,
            "resolved_by" => $resolved_by,
            "created_at"=>date("Y-m-d H:i:s")
        );
       
        $data["notification"]=$this->Admin_model->save_issue_details($data);
        $insert_id = $this->db->insert_id();
        $this->session->set_flashdata("success","Issue Created Successfully");
        redirect("index.php/manage-my-issue");
    }

    public function view_issue($issue_id){
        $data['page_title']="View Issue"; 
        $company_id = $_SESSION['logged_in']['company_id'];
        $user_id = $_SESSION['logged_in']['id'];
        $registered_user_id = $_SESSION['logged_in']['admin_registered_user_id'];

        /*       
        $this->db->select('issue_manage.*,company_projects.project_id,users.firstName,users.lastName,company.company_name,company_locations.location_name');
        $this->db->from('issue_manage');
        $this->db->join('company','company.id=issue_manage.company_name');
        $this->db->join('company_locations','company_locations.id=issue_manage.location_name');
        $this->db->join('company_projects','company_projects.id=issue_manage.project_name');
        $this->db->join('users','users.id=issue_manage.resolved_by');
        $this->db->where('issue_manage.id',$issue_id);
        $issue_row=$this->db->get();
        $issue_result = $issue_row->row();
            */ 

        $this->db->select('issue_manage.*,users.firstName,users.lastName');
        $this->db->from('issue_manage');
        $this->db->join('users','users.id=issue_manage.resolved_by');
        $this->db->where('issue_manage.id',$issue_id);
        $issue_row=$this->db->get();
        $issue_result = $issue_row->row();

        $data['issue_result'] = $issue_result;
        // $this->load->view('issue-view',$data);
        $this->load->view('general-issue-view',$data);
    }


      public function update_issue(){

        $hdn_issue_id = $this->input->post("hdn_issue_id");

        $created_by=$this->user_id;
        $random_number = rand(10000,99999);
        $tracking_id_value = date('ymd').$random_number;
        

        $config['upload_path'] = './issueattachment/';
        $config['allowed_types'] = '*';
		$config['encrypt_name']=true;

        $this->load->library('upload', $config);
		$issue_attachment='';
        if (!$this->upload->do_upload('issue_attachment')) {
            $error = array('error' => $this->upload->display_errors());
			print_r($error);
			exit;
        } else {
            $data = $this->upload->data();
			$issue_attachment="response_".$data['file_name'];
		}


        $status_value = $this->input->post("status");
        $status_remark_value = $this->input->post("Remstatus_remarkark");
        $hdn_status_type_value = $this->input->post("hdn_status_type");
        $status_type_remark_value = $this->input->post("status_type_remark");
       

        $data=array(
            "status"=>$status_value,
            "status_type"=>$hdn_status_type_value,
            "remark_content"=>$status_remark_value,
            "remark_content" => $this->input->post("Remark"),
            "updated_at"=>date("Y-m-d H:i:s")
        );
       
        $data["notification"]=$this->Admin_model->update_issue_details($data,$hdn_issue_id);


        $data=array(
            "issue_id"=>$hdn_issue_id,
            "user_id"=>$created_by,
            "status"=>$status_value,
            "status_remark"=>$status_remark_value,
            "status_type"=>$hdn_status_type_value,
            "status_type_remark"=>$status_type_remark_value,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at"=>date("Y-m-d H:i:s")
        );
       
        $this->Admin_model->save_issue_log_details($data);
        $insert_id = $this->db->insert_id();
        
        $this->session->set_flashdata("success","Issue Updated Successfully");
        redirect("index.php/manage-my-issue");
    }
    
}