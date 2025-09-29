<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registeredusercontroller extends CI_Controller {
	public function __construct() {
		parent::__construct();		
		$this->load->library('session');	
		if (!$this->session->userdata('registered_user_logged')) {
            redirect(base_url()."index.php/registered-user-login", 'refresh');
		}
		$this->load->model('Registered_user_model');
	}

    public function registered_user_dashboard(){


        $id = $this->session->userdata('registered_user_id');
        $data['page_title']="My Subscription";
        $data['user_data']=$this->Registered_user_model->get_registerd_user($id);
        $data['payment_data']=$this->Registered_user_model->get_registred_users_payment_all($id);
        $data['plan_data']=$this->Registered_user_model->get_registered_user_plan($id);

      
        $this->db->select('register_user_plan_log.*, subscription_plan.*');
		$this->db->from(' subscription_plan');
		$this->db->join('register_user_plan_log','register_user_plan_log.plan_id= subscription_plan.id');
		$this->db->where('register_user_plan_log.register_user_id',$id);
		$getnotifications=$this->db->get();
		$Subscription_plan_result = $getnotifications->row();
        $data['Subscription_plan']= $Subscription_plan_result;


        $data['page_title']="Dashboard";
        $this->load->view("registered-user/dashboard",$data);

    }

    public function change_password(){
        $data['page_title']="Change Password";
        $this->load->view("registered-user/change-password",$data);

    }

    public function check_current_pass(){
        // echo '<pre>';
        // print_r("ASdasdasdsdd");
        // echo '</pre>';
        // exit(); 
        $user_id=$this->session->userdata('registered_user_id');
        $current_pass=$this->input->post('current_pass');
        $rowpass= $this->Registered_user_model->check_current_pass($user_id,$current_pass);

        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();

        echo $rowpass;
    }

    public function registered_user_passwod_save(){
        $user_id=$this->session->userdata('registered_user_id');
        $data=array( 
            "password"=>md5($this->input->post('password')),
            "password_view"=>$this->input->post('password'),
        );
     $this->Registered_user_model->update_password($user_id,$data);
     $this->session->set_flashdata("success","Password Changed Successfully");
     redirect("index.php/registered-user-change-password");
    }

    public function user_detail_plan(){
        $id = $this->session->userdata('registered_user_id');
        $data['page_title']="My Subscription";
        $data['user_data']=$this->Registered_user_model->get_registerd_user($id);
        $data['payment_data']=$this->Registered_user_model->get_registred_users_payment_all($id);
        $data['plan_data']=$this->Registered_user_model->get_registered_user_plan($id);

        $this->db->select('register_user_plan_log.*, subscription_plan.*');
		$this->db->from(' subscription_plan');
		$this->db->join('register_user_plan_log','register_user_plan_log.plan_id= subscription_plan.id');
		$this->db->where('register_user_plan_log.register_user_id',$id);
		$getnotifications=$this->db->get();
		$Subscription_plan_result = $getnotifications->row();
        $data['Subscription_plan']= $Subscription_plan_result;



        $this->load->view("registered-user/view-plan",$data);
     }

     public function registered_user_profile(){
        $id = $this->session->userdata('registered_user_id');
        $data['page_title']="My Profile";
        $data['user_data']=$this->Registered_user_model->get_registerd_user($id);
        $data['payment_data']=$this->Registered_user_model->get_registred_users_payment($id);
        $data['plan_data']=$this->Registered_user_model->get_registered_user_plan($id);
        $this->load->view("registered-user/my-profile",$data);
     }

     public function registered_user_update_profile(){
	
        $registered_user_id=$this->input->post('register_user_id');
        if($this->input->post('is_gst') == '1'){
            $gst_no=$this->input->post('gst_no');
            $gst_org_name=$this->input->post('gst_org_name');
            $gst_org_address=$this->input->post('gst_org_address');
        }else{
            $gst_no='';
            $gst_org_name='';
            $gst_org_address='';
        }
        $data_user= array(
            "first_name"=>$this->input->post('first_name'),
            "last_name"=>$this->input->post('last_name'),
            "phone_no"=>$this->input->post('phone_no'),
            "organisation_name"=>$this->input->post('organisation_name'),
            "entity_code"=>$this->input->post('entity_code'),
            "is_gst"=>$this->input->post('is_gst'),
            "gst_no"=>$gst_no,
            "name_org_gst"=>$gst_org_name,
            "address_org_gst"=>$gst_org_address,
            "updated_at"=>date("Y-m-d H:i:s")
        );
    $this->Registered_user_model->edit_save_registered_user($registered_user_id,$data_user);
    $this->session->set_flashdata("success","Profile Updated Successfully");
    redirect("index.php/registered-user-profile");

    }    


    public function transfer_account(){
        $data['page_title']="Transfer Account";
        $this->load->view("registered-user/transfer-account",$data);
    }

    public function transfer_account_save(){
        $register_user_id=$this->session->userdata("registered_user_id");
        $dataadd=array(
            "regestered_user_id"=>$this->session->userdata("registered_user_id"),
            "trransfer_email_from"=>$this->session->userdata("registered_user_email"),
            "transfer_email_to"=>$this->input->post('transfer_email'),
            "transfer_at"=>date("Y-m-d H:i:s")
        );
        $this->Registered_user_model->save_transfered_user($dataadd);
        $dataupdate=array(
            "email_id"=>$this->input->post('transfer_email'),
            "is_transfer"=>"1",
            "transfer_at"=>date("Y-m-d H:i:s")
        );
        $this->Registered_user_model->edit_save_registered_user($register_user_id,$dataupdate);
		$this->session->sess_destroy();
        redirect('index.php/transfer-logout-confirmation');
    }
    
   
    public function renew_request($registereduserid){
        $data=array(
            "renew_request"=>"1",
            "request_renew_at"=>date("Y-m-d")
        );
        $this->Registered_user_model->request_renew_save($registereduserid,$data);
        $this->session->set_flashdata("success","Renew Request Send Successfully");
        redirect("index.php/registered-user-subscription");
    }

    public function unsubscribe_account($registereduserid){
        $data=array(
            "is_active"=>"6",
        );
        $this->Registered_user_model->request_renew_save($registereduserid,$data);
        $this->session->set_flashdata("success","Account Unsubscribed Successfully");
        redirect("index.php/registered-user-subscription");
    }

    
    public function request_resubscribe($registereduserid){
        $data=array(
            "is_resubscribe_request"=>"1",
            "is_resubscribe_request_at"=>date("Y-m-d"),
        );
        $this->Registered_user_model->request_renew_save($registereduserid,$data);
        $this->session->set_flashdata("success","Request Send Successfully");
        redirect("index.php/registered-user-subscription");
    }
    

    public function as_admin_login($register_user_id){
      $register_userdata = $this->Registered_user_model->as_a_admin($register_user_id);
      $register_usr_first_name= $register_userdata->first_name;
      $register_usr_last_name =$register_userdata->last_name;
      $register_usr_email_id =$register_userdata->email_id;
      $register_usr_phone_no =$register_userdata->phone_no;
      $register_usr_entity_code =$register_userdata->entity_code;
      $register_usr_first_name =$register_userdata->first_name;
      $register_usr_last_name =$register_userdata->last_name;
      $register_usr_phone_no= $register_userdata->phone_no;
    //   $password=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
         $password="12345";
      //   check register user admin exist or not//
       $check_admin_row= $this->Registered_user_model->check_as_a_admin_user($register_user_id,$register_usr_email_id,$register_usr_entity_code);

       if($check_admin_row == '0'){
            $data_user_insert=array(
                "userName"=>$register_usr_first_name,
                "firstName"=>$register_usr_first_name,
                "lastName"=>$register_usr_last_name,
                "userEmail"=>$register_usr_email_id,
                "phone_no"=>$register_usr_phone_no,
                "registered_user_id"=>$register_user_id,
                "entity_code"=>$register_usr_entity_code,
                "company_id"=>0,
                "location_id"=>0,
                "userRole"=>"5",
                "password"=>md5($password)
            );
      // insert record as admin

           $insertid= $this->Registered_user_model->insert_as_a_admin_user($data_user_insert);
      // insert record as admin

            $data_user_role=array(
                "user_id"=>$insertid,
                "company_id"=>0,
                "location_id"=>0,
                "user_role"=>5,
                "registered_user_id"=>$register_user_id,
                "entity_code"=>$register_usr_entity_code,
                "created_by"=>0,
                "created_at"=>date("Y-m-d H:i:s")
               );
               $this->db->insert('user_role',$data_user_role);

            // Login as admin set session//
                $login_cnt=$this->Registered_user_model->login_as_admin($register_usr_email_id,$register_usr_entity_code);
                if($login_cnt >0){
                    redirect(base_url()."index.php/dashboard/admin", 'refresh');
                }else{
                    $this->session->set_flashdata("success","Something Went Wrong Please Try Again");
                    redirect(base_url()."index.php/registered-user-dashboard", 'refresh');

                }
            // Login as admin set session//

       }else{
            // Login as admin set session//
            $login_cnt=$this->Registered_user_model->login_as_admin($register_usr_email_id,$register_usr_entity_code);
            if($login_cnt >0){
               
                redirect(base_url()."index.php/dashboard", 'refresh');
            }else{
                $this->session->set_flashdata("success","Something Went Wrong Please Try Again");
                redirect(base_url()."index.php/registered-user-dashboard", 'refresh');

            }
        // Login as admin set session//
          
       }
    }
   public function index() {
        // 1. Get the current user's ID from the session.
        $user_id = $this->session->userdata('registered_user_id');
        $data_for_view = []; // Initialize an array to pass to the view.

        // 2. Check if a user is logged in.
        if ($user_id) {
            // 3. Query the database to get the plan_end_date.
            $this->db->select('plan_end_date');
            $this->db->from('registered_user_plan');
            $this->db->where('registered_user_id', $user_id);
            $query = $this->db->get();

            // 4. Check if a plan was found for the user.
            if ($query->num_rows() > 0) {
                $result = $query->row();
                // Add the plan_end_date to the data array.
                $data_for_view['plan_end_date'] = $result->plan_end_date;
            } else {
                // If no plan is found, you can set the date to null.
                $data_for_view['plan_end_date'] = null;
            }
        } else {
            // If no user is logged in, also set to null.
            $data_for_view['plan_end_date'] = null;
        }

        // 5. Load your view and pass the $data_for_view array to it.
        // CRITICAL: Replace 'your_page_view' with the actual name of your view file.
        // For example: $this->load->view('registered_user_dashboard', $data_for_view);
        $this->load->view('your_page_view', $data_for_view);
    }
    
 
}
