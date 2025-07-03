<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
		 $this->load->model('Tasks_model','tasks');	
		 $this->load->model('Login_model','plancycle');

		// Load form validation library
		$this->load->library('form_validation');
		$this->load->helper('function_helper','helper');
		// Load session library
		$this->load->library('session');	
		if (!$this->session->userdata('logged_in')) {
            redirect(base_url()."index.php/login", 'refresh');
		}
		else
		{ 
			$session=$this->session->userdata('logged_in');
			$this->user_id=$session['id'];
			$this->company_id=$session['company_id'];
			$this->main_role=$session['main_role'];
			$this->name=$session['name'];
			$this->admin_registered_user_id=$session['admin_registered_user_id'];
			$this->admin_registered_entity_code=$session['admin_registered_entity_code'];
		if($session['main_role'] !='5'){
		if(isset($_REQUEST['company_id'])){
			$sd=$this->db->query('SELECT * from company_projects where company_id='.$_REQUEST['company_id'].' and ('.$session['id'].' IN (project_verifier) OR  '.$session['id'].' IN (manager) OR '.$session['id'].' IN (item_owner) OR '.$session['id'].' IN (process_owner))')->result();
			$this->userRoleArray=array();
			if(!empty($sd))
			{
				
				if(in_array($session['id'],explode(',',$sd[0]->manager)))
				{
					array_push($this->userRoleArray,0);		
				}
				if(in_array($session['id'],explode(',',$sd[0]->project_verifier)))
				{
					array_push($this->userRoleArray,1);
				}
				if(in_array($session['id'],explode(',',$sd[0]->process_owner)))
				{
					array_push($this->userRoleArray,2);	
				}
				if(in_array($session['id'],explode(',',$sd[0]->item_owner)))
				{
					array_push($this->userRoleArray,3);	
				}
				if(in_array(0,$this->userRoleArray))
				{
					$this->user_type=0;
				}
				else if(in_array(1,$this->userRoleArray))
				{
					$this->user_type=1;
				}
				else if(in_array(2,$this->userRoleArray))
				{
					$this->user_type=2;
				}
				else if(in_array(3,$this->userRoleArray))
				{
					$this->user_type=3;
				}
				else
				{
					$this->user_type=0;
				}
			}
			else
			{
				$this->user_type=$session['main_role'];
			}
		}
			
		}
	 }

 	}
 
	public function get_all_company_user_role($entity_code){
		$this->db->select("*");
		$this->db->from("user_role");
		$this->db->where("entity_code",$entity_code);
		$query = $this->db->get();
		return $query->result();

	}

	public function index()
	{   

		$admin_registered_user_id = $_SESSION['logged_in']['admin_registered_user_id'];
		$user_id=$this->user_id;
		$entity_code=$this->admin_registered_entity_code;
		
		$company_id_imp='';
		$location_id='';
		$role_result_com = $this->get_all_company_user_role($entity_code);
		if(!empty($role_result_com)){
			foreach($role_result_com as $row_role){
				$roledata[]=$row_role->company_id;
				$roledata1[]=$row_role->location_id;
			}
			$company_id_imp = implode(',',$roledata);
			$location_id = implode(',',$roledata1);
		}
		$register_user_id=$this->admin_registered_user_id;
            
		// $condition=array('company_id'=>$this->company_id);
       $condition=array('company_id IN ('.$company_id_imp.') AND project_location IN ('.$location_id.')',"entity_code"=>$this->admin_registered_entity_code);

        if($this->input->post('company_id') && $this->input->post('company_id') !=''){
			$condition=array('company_id'=>$this->input->post('company_id'));
        }

      	if($this->input->post('location_id') && $this->input->post('location_id') !=''){
			$condition=array('company_id'=>$this->input->post('company_id'), 'project_location'=>$this->input->post('location_id'),);
        }

		// $condition = array();
		
		$projects=$this->tasks->get_data('company_projects',$condition);	
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
			 
            if(!empty($getprojectdetails))
            {
                $project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                if($getprojectdetails[0]->VerifiedQuantity !='')
                $project->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
                else
                $project->VerifiedQuantity=0;
            }
            else
            {   
                $project->TotalQuantity=0;
                $project->VerifiedQuantity=0;
            }
            
		}

		$data['projects']=$projects;
		$data['page_title']="Dashboard";
		$data['company_data_list']=$this->company_data_list();

		$this->db->select("*");
		$this->db->from('registred_users');
		$this->db->where('id',$admin_registered_user_id);
		$query=$this->db->get();
		$registred_users_details = $query->row();

		$this->db->select("*");
		$this->db->from('subscription_plan');
		$this->db->where('id',$registred_users_details->plan_id);
		$query=$this->db->get();
		$subscription_plan_details = $query->row();

		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$this->db->where('regiistered_user_id',$admin_registered_user_id);
		$this->db->where('plan_id',$registred_users_details->plan_id);
		$query=$this->db->get();
		$registered_user_plan_details = $query->row();
		
		$data['subscription_plan_details'] = $subscription_plan_details;
		$data['registred_users_details'] = $registred_users_details;
		$data['registered_user_plan_details'] = $registered_user_plan_details;
		
		$total_company_query = $this->db->query('SELECT * FROM company where registered_user_id = '.$admin_registered_user_id);
		$total_company_count = $total_company_query->num_rows();

		$total_company_locations_query = $this->db->query('SELECT * FROM company_locations where registered_user_id = '.$admin_registered_user_id);
		$total_company_locations_count = $total_company_locations_query->num_rows();

		$total_users_query = $this->db->query('SELECT * FROM users where registered_user_id = '.$admin_registered_user_id);
		$total_users_count = $total_users_query->num_rows();

		$data['total_company_count'] = $total_company_count;
		$data['total_company_locations_count'] = $total_company_locations_count;
		$data['total_users_count'] = $total_users_count;
		$this->load->view('dashboard2',$data);		







		/*
		$open_projects=0;
		$closed_projects=0;
		$cancelled_projects=0;
		$open_projectdetails="";
		$close_projectdetails="";
		$cancel_projectdetails="";
		foreach($projects as $project)
		{
			$verifiercount = check_verifier_count($project->id,$this->user_id);
			$check_itemowner_count = check_itemowner_count($project->id,$this->user_id);
			$check_process_owner_count = check_process_owner_count($project->id,$this->user_id);
			$check_manager_count = check_manager_count($project->id,$this->user_id);

			if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){

				if($project->status==0)
				{
					$open_projects++;
					if($project->VerifiedQuantity !=0){
					$open_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
				}else{
					$open_projectdetails.='0%';
				}
				}
				if($project->status==1)
				{
					$closed_projects++;
					$close_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
				}
				if($project->status==2)
				{
					$cancelled_projects++;
					$cancel_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
				}
			}
		}


		$company_array = array();
		foreach($data['company_data_list'] as $company_details){
			$company_array[] = $company_details->company_id;
		}

		$company_datas = implode(",", $company_array);
		$company_projects = $this->db->query('SELECT company.company_name,company_projects.* FROM company_projects LEFT JOIN company ON company_projects.company_id = company.id WHERE company_projects.company_id IN ('.$company_datas.')')->result();
		
		$project_base_count = array();
		$withing_time = array();
		$due_date = array();
		foreach($company_projects as $company_projects_key=>$company_projects_value){

			$project_due_date = $company_projects_value->due_date;
			$project_name = $company_projects_value->project_name;
			$due_date = $project_due_date; // Format: Y-m-d
			$today = date('Y-m-d');

			if ($due_date <= $today) {
				$project_base_count[$company_projects_value->company_name]['overdue'][] = 1;
			} else {
				$project_base_count[$company_projects_value->company_name]['withindate'][] = 1;
			}
		}



		$graph_data = array();
		$count = 1;

		$overdue_array = array();
		$withindate_array = array();
		$count_zero = 1;
		$count = 0;
		foreach($project_base_count as $project_base_count_key=>$project_base_count_value){
			

			$overdue_array[$count]['label'] = $project_base_count_key;
			$withindate_array[$count]['label'] = $project_base_count_key;
			$overdue_count_data = 0;
			$withindate_count_data = 0;
			if(isset($project_base_count[$project_base_count_key]['overdue'])){
				$overdue_count_data = count($project_base_count[$project_base_count_key]['overdue']);
				$project_base_count[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue']);
				$graph_data[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue'] );
			}else{
				$overdue_count_data = 0;
				$project_base_count[$project_base_count_key]['overdue_count'] = 0;
				$graph_data[$project_base_count_key]['overdue_count'] = 0;
			}

			$overdue_array[$count]['y'] = $overdue_count_data;
			$overdue_array[$count]['id'] = $count_zero;

			if(isset($project_base_count[$project_base_count_key]['withindate'])){
				$withindate_count_data = count($project_base_count[$project_base_count_key]['withindate']);
				$project_base_count[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
				$graph_data[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
			}else{
				$withindate_count_data = 0;
				$project_base_count[$project_base_count_key]['withindate_count'] = 0;
				$graph_data[$project_base_count_key]['withindate_count'] = 0;
			}

			$withindate_array[$count]['y'] = $withindate_count_data;
			$withindate_array[$count]['id'] = $count_zero;
			$count++;
			$count_zero++;
		}


		$this->db->select("*");
		$this->db->from('company_projects');
		$query=$this->db->get();
		$registered_user_plan = $query->result();




		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$query=$this->db->get();
		$registered_user_plan = $query->result();


		$this->db->select("id,title");
		$this->db->from('subscription_plan');
		$query=$this->db->get();
		$subscription_plan = $query->result();

		$subscription_plan_array = array();

		$TypeSubscriptionActiveChart_type = array('Original','Renewals','Resubscriptions');


		
		
		foreach($TypeSubscriptionActiveChart_type as $TypeSubscriptionActiveChart_type_key=>$TypeSubscriptionActiveChart_type_value){
			foreach($subscription_plan as $subscription_plan_key=>$subscription_plan_value){
				$subscription_plan_array[$TypeSubscriptionActiveChart_type_value][] = array("label"=> $subscription_plan_value->title, "id"=> $subscription_plan_value->id);
			}
		}

		$OriginalPoints = array();
		foreach($subscription_plan_array['Original'] as $subscription_plan_array_key=>$subscription_plan_array_value){
			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Original" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$OriginalPoints[] = $subscription_plan_array_value;
		}

		

		$RenewalsPoints = array();
		foreach($subscription_plan_array['Renewals'] as $subscription_plan_array_key=>$subscription_plan_array_value){

			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Renewals" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$RenewalsPoints[] = $subscription_plan_array_value;
		}

		$ResubscriptionsPoints = array();
		foreach($subscription_plan_array['Resubscriptions'] as $subscription_plan_array_key=>$subscription_plan_array_value){
			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Resubscriptions" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$ResubscriptionsPoints[] = $subscription_plan_array_value;
		}

		$data['OriginalPoints'] = $OriginalPoints;
		$data['RenewalsPoints'] = $RenewalsPoints;
		$data['ResubscriptionsPoints'] = $ResubscriptionsPoints;

		$company_mapped_query = $this->db->query('SELECT count(company_id) as company_mapped FROM user_role where user_role.user_id = '.$user_id);
		$company_mapped_query_result = $company_mapped_query->row();
		$data['Companies_Mapped'] = $company_mapped_query_result->company_mapped;

		$location_mapped_query = $this->db->query('SELECT count(location_id) as location_mapped FROM user_role where user_role.user_id = '.$user_id);
		$location_mapped_query_result = $location_mapped_query->row();
		$data['Locations_mapped'] = $location_mapped_query_result->location_mapped;

		$Closed_Projects_query = $this->db->query('SELECT * FROM company_projects where status in (2,3)');
		$Closed_Projects_count = $Closed_Projects_query->num_rows();
		$data['Closed_Projects'] = $Closed_Projects_count;

		$Cancelled_Projects_query = $this->db->query('SELECT * FROM company_projects where status = "0" AND cancelled_date IS NOT NULL');
		$Cancelled_Projects_count = $Cancelled_Projects_query->num_rows();
		$data['Cancelled_Projects'] = $Cancelled_Projects_count;
		



		// echo '<pre>overdue_array ';
		// 		print_r($overdue_array);
		// 		echo '</pre>';

		// 		echo '<pre>withindate_array ';
		// 		print_r($withindate_array);
		// 		echo '</pre>';

		$data['overdue_array'] = $overdue_array;
		$data['withindate_array'] = $withindate_array;

		// echo '<pre>data ';
		// print_r($data);
		// echo '</pre>';
		// exit(); 



		*/




	}


	public function User()
	{   

		$admin_registered_user_id = $_SESSION['logged_in']['admin_registered_user_id'];
		$user_id=$this->user_id;
		$entity_code=$this->admin_registered_entity_code;
		
		// echo '<pre>user_id ::';
		// print_r($user_id);
		// echo '</pre>';
		// exit();
		
		$company_id_imp='';
		$location_id='';
		$role_result_com = $this->get_all_company_user_role($entity_code);

		if(!empty($role_result_com)){
			foreach($role_result_com as $row_role){
				$roledata[]=$row_role->company_id;
				$roledata1[]=$row_role->location_id;
			}
			$company_id_imp = implode(',',$roledata);
			$location_id = implode(',',$roledata1);
		}
		$register_user_id=$this->admin_registered_user_id;
           
		
		// $condition=array('company_id'=>$this->company_id);
       $condition=array('company_id IN ('.$company_id_imp.') AND project_location IN ('.$location_id.')',"entity_code"=>$this->admin_registered_entity_code);

        if($this->input->post('company_id') && $this->input->post('company_id') !=''){
			$condition=array('company_id'=>$this->input->post('company_id'));
        }

      	if($this->input->post('location_id') && $this->input->post('location_id') !=''){
			$condition=array('company_id'=>$this->input->post('company_id'), 'project_location'=>$this->input->post('location_id'),);
        }

		// $condition = array();
		
	
		$projects=$this->tasks->get_data('company_projects',$condition);	
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
			 
            if(!empty($getprojectdetails))
            {
                $project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                if($getprojectdetails[0]->VerifiedQuantity !='')
                $project->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
                else
                $project->VerifiedQuantity=0;
            }
            else
            {   
                $project->TotalQuantity=0;
                $project->VerifiedQuantity=0;
            }
            
		}

		$data['projects']=$projects;
		$data['page_title']="User Dashboard";

		// $company_data_query=$this->db->query("select * from user_role where user_id = '".$user_id."' Group by company_id");
		$company_data_query=$this->db->query("select * from user_role where user_id = '".$user_id."'");
		$company_data_list = $company_data_query->result();

		
		
		$company_dropdown_array = array();
		$company_array = array();
		$location_array = array();
		foreach($company_data_list as $company_data_list){
			if(!in_array($company_data_list->company_id, $company_array)){
				$company_dropdown_array[]['company_id'] = $company_data_list->company_id;
			}
			$company_array[] = $company_data_list->company_id;
			$location_array[] = $company_data_list->location_id;
		}
	
		$data['company_data_list']=$company_dropdown_array;


		
	

		$company_datas = implode(",", $company_array);
		$location_datas = implode(",", $location_array);

		$company_projects = $this->db->query('SELECT company.company_name,company_projects.* FROM company_projects LEFT JOIN company ON company_projects.company_id = company.id WHERE company_projects.company_id IN ('.$company_datas.') AND company_projects.project_location IN ('.$location_datas.') AND company_projects.status = 0 AND item_owner = "'.$user_id.'"')->result();
		
		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();

		

		$project_base_count = array();
		$withing_time = array();
		$due_date = array();
		foreach($company_projects as $company_projects_key=>$company_projects_value){

			$project_due_date = $company_projects_value->due_date;
			$project_name = $company_projects_value->project_name;
			$due_date = $project_due_date; // Format: Y-m-d
			$today = date('Y-m-d');

			if ($due_date <= $today) {
				$project_base_count[$company_projects_value->company_name]['overdue'][] = 1;
			} else {
				$project_base_count[$company_projects_value->company_name]['withindate'][] = 1;
			}
		}



		$graph_data = array();
		$count = 1;

		$overdue_array = array();
		$withindate_array = array();
		$count_zero = 1;
		$count = 0;
		foreach($project_base_count as $project_base_count_key=>$project_base_count_value){
			

			$overdue_array[$count]['label'] = $project_base_count_key;
			$withindate_array[$count]['label'] = $project_base_count_key;
			$overdue_count_data = 0;
			$withindate_count_data = 0;
			if(isset($project_base_count[$project_base_count_key]['overdue'])){
				$overdue_count_data = count($project_base_count[$project_base_count_key]['overdue']);
				$project_base_count[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue']);
				$graph_data[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue'] );
			}else{
				$overdue_count_data = 0;
				$project_base_count[$project_base_count_key]['overdue_count'] = 0;
				$graph_data[$project_base_count_key]['overdue_count'] = 0;
			}

			$overdue_array[$count]['y'] = $overdue_count_data;
			$overdue_array[$count]['id'] = $count_zero;

			if(isset($project_base_count[$project_base_count_key]['withindate'])){
				$withindate_count_data = count($project_base_count[$project_base_count_key]['withindate']);
				$project_base_count[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
				$graph_data[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
			}else{
				$withindate_count_data = 0;
				$project_base_count[$project_base_count_key]['withindate_count'] = 0;
				$graph_data[$project_base_count_key]['withindate_count'] = 0;
			}

			$withindate_array[$count]['y'] = $withindate_count_data;
			$withindate_array[$count]['id'] = $count_zero;
			$count++;
			$count_zero++;
		}


		$company_mapped_query = $this->db->query('SELECT count(company_id) as company_mapped FROM user_role where user_role.user_id = '.$user_id.' Group by company_id');
		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();
		$company_mapped_query_result = $company_mapped_query->row();
		$data['Companies_Mapped'] = $company_mapped_query_result->company_mapped;

		$location_mapped_query = $this->db->query('SELECT count(location_id) as location_mapped FROM user_role where user_role.user_id = '.$user_id);
		$location_mapped_query_result = $location_mapped_query->row();
		$data['Locations_mapped'] = $location_mapped_query_result->location_mapped;

		$Closed_Projects_query = $this->db->query('SELECT * FROM company_projects where status in (2,3) AND company_projects.company_id IN ('.$company_datas.') AND company_projects.project_location IN ('.$location_datas.') AND company_projects.status = 0 AND item_owner = "'.$user_id.'"');
		$Closed_Projects_count = $Closed_Projects_query->num_rows();
		$data['Closed_Projects'] = $Closed_Projects_count;

		$Cancelled_Projects_query = $this->db->query('SELECT * FROM company_projects where status = "0" AND cancelled_date IS NOT NULL AND company_projects.company_id IN ('.$company_datas.') AND company_projects.project_location IN ('.$location_datas.') AND company_projects.status = 0 AND item_owner = "'.$user_id.'"');
		$Cancelled_Projects_count = $Cancelled_Projects_query->num_rows();
		$data['Cancelled_Projects'] = $Cancelled_Projects_count;
		
		$data['overdue_array'] = $overdue_array;
		$data['withindate_array'] = $withindate_array;

		$projects=$this->db->query('SELECT * from company_projects where status = 0')->result();
		$stackedBarchartContainer_array = array();
		
		$count = 0;
		foreach($projects as $projects_key=>$projects_value){
			$project_start_date = $projects_value->start_date;
			$project_end_date = $projects_value->due_date;
			$project_name_value = $projects_value->project_name;
			$stackedBarchartContainer_array[$count]['y'] = [(strtotime($project_start_date. ' + 1 day')), (strtotime($project_end_date. ' + 11 day'))];
			$stackedBarchartContainer_array[$count]['label'] = mb_strimwidth($project_name_value, 0, 10, "...");;
			$count++;
		}
		$data['stackedBarchartContainer_array'] = $stackedBarchartContainer_array;
		$this->load->view('Userdashboard',$data);	
	}


	public function User_Backup_PREVIOUS_Work_02July()
	{   

		$admin_registered_user_id = $_SESSION['logged_in']['admin_registered_user_id'];
		$user_id=$this->user_id;
		$entity_code=$this->admin_registered_entity_code;
		
		$company_id_imp='';
		$location_id='';
		$role_result_com = $this->get_all_company_user_role($entity_code);
		if(!empty($role_result_com)){
			foreach($role_result_com as $row_role){
				$roledata[]=$row_role->company_id;
				$roledata1[]=$row_role->location_id;
			}
			$company_id_imp = implode(',',$roledata);
			$location_id = implode(',',$roledata1);
		}
		$register_user_id=$this->admin_registered_user_id;
            
		// $condition=array('company_id'=>$this->company_id);
       $condition=array('company_id IN ('.$company_id_imp.') AND project_location IN ('.$location_id.')',"entity_code"=>$this->admin_registered_entity_code);

        if($this->input->post('company_id') && $this->input->post('company_id') !=''){
			$condition=array('company_id'=>$this->input->post('company_id'));
        }

      	if($this->input->post('location_id') && $this->input->post('location_id') !=''){
			$condition=array('company_id'=>$this->input->post('company_id'), 'project_location'=>$this->input->post('location_id'),);
        }

		// $condition = array();
		
		$projects=$this->tasks->get_data('company_projects',$condition);	
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
			 
            if(!empty($getprojectdetails))
            {
                $project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                if($getprojectdetails[0]->VerifiedQuantity !='')
                $project->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
                else
                $project->VerifiedQuantity=0;
            }
            else
            {   
                $project->TotalQuantity=0;
                $project->VerifiedQuantity=0;
            }
            
		}

		$data['projects']=$projects;
		$data['page_title']="User Dashboard";

		$company_data_query=$this->db->query("select * from user_role where user_id = '".$user_id."'");
		$company_data_list = $company_data_query->result();

		$data['company_data_list']=$company_data_list;

		$this->db->select("*");
		$this->db->from('registred_users');
		$this->db->where('id',$admin_registered_user_id);
		$query=$this->db->get();
		$registred_users_details = $query->row();

		$this->db->select("*");
		$this->db->from('subscription_plan');
		$this->db->where('id',$registred_users_details->plan_id);
		$query=$this->db->get();
		$subscription_plan_details = $query->row();

		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$this->db->where('regiistered_user_id',$admin_registered_user_id);
		$this->db->where('plan_id',$registred_users_details->plan_id);
		$query=$this->db->get();
		$registered_user_plan_details = $query->row();
		
		$data['subscription_plan_details'] = $subscription_plan_details;
		$data['registred_users_details'] = $registred_users_details;
		$data['registered_user_plan_details'] = $registered_user_plan_details;
		
		$total_company_query = $this->db->query('SELECT * FROM company where registered_user_id = '.$admin_registered_user_id);
		$total_company_count = $total_company_query->num_rows();

		$total_company_locations_query = $this->db->query('SELECT * FROM company_locations where registered_user_id = '.$admin_registered_user_id);
		$total_company_locations_count = $total_company_locations_query->num_rows();

		$total_users_query = $this->db->query('SELECT * FROM users where registered_user_id = '.$admin_registered_user_id);
		$total_users_count = $total_users_query->num_rows();

		$data['total_company_count'] = $total_company_count;
		$data['total_company_locations_count'] = $total_company_locations_count;
		$data['total_users_count'] = $total_users_count;
		// $this->load->view('dashboard2',$data);		








		$open_projects=0;
		$closed_projects=0;
		$cancelled_projects=0;
		$open_projectdetails="";
		$close_projectdetails="";
		$cancel_projectdetails="";
		foreach($projects as $project)
		{
			$verifiercount = check_verifier_count($project->id,$this->user_id);
			$check_itemowner_count = check_itemowner_count($project->id,$this->user_id);
			$check_process_owner_count = check_process_owner_count($project->id,$this->user_id);
			$check_manager_count = check_manager_count($project->id,$this->user_id);

			if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){

				if($project->status==0)
				{
					$open_projects++;
					if($project->VerifiedQuantity !=0){
					$open_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
				}else{
					$open_projectdetails.='0%';
				}
				}
				if($project->status==1)
				{
					$closed_projects++;
					$close_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
				}
				if($project->status==2)
				{
					$cancelled_projects++;
					$cancel_projectdetails.=", ".$project->project_name.'('.round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2).' %)';
				}
			}
		}


		$company_array = array();
		foreach($data['company_data_list'] as $company_details){
			$company_array[] = $company_details->company_id;
		}

		$company_datas = implode(",", $company_array);
		$company_projects = $this->db->query('SELECT company.company_name,company_projects.* FROM company_projects LEFT JOIN company ON company_projects.company_id = company.id WHERE company_projects.company_id IN ('.$company_datas.')')->result();
		
		$project_base_count = array();
		$withing_time = array();
		$due_date = array();
		foreach($company_projects as $company_projects_key=>$company_projects_value){

			$project_due_date = $company_projects_value->due_date;
			$project_name = $company_projects_value->project_name;
			$due_date = $project_due_date; // Format: Y-m-d
			$today = date('Y-m-d');

			if ($due_date <= $today) {
				$project_base_count[$company_projects_value->company_name]['overdue'][] = 1;
			} else {
				$project_base_count[$company_projects_value->company_name]['withindate'][] = 1;
			}
		}



		$graph_data = array();
		$count = 1;

		$overdue_array = array();
		$withindate_array = array();
		$count_zero = 1;
		$count = 0;
		foreach($project_base_count as $project_base_count_key=>$project_base_count_value){
			

			$overdue_array[$count]['label'] = $project_base_count_key;
			$withindate_array[$count]['label'] = $project_base_count_key;
			$overdue_count_data = 0;
			$withindate_count_data = 0;
			if(isset($project_base_count[$project_base_count_key]['overdue'])){
				$overdue_count_data = count($project_base_count[$project_base_count_key]['overdue']);
				$project_base_count[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue']);
				$graph_data[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue'] );
			}else{
				$overdue_count_data = 0;
				$project_base_count[$project_base_count_key]['overdue_count'] = 0;
				$graph_data[$project_base_count_key]['overdue_count'] = 0;
			}

			$overdue_array[$count]['y'] = $overdue_count_data;
			$overdue_array[$count]['id'] = $count_zero;

			if(isset($project_base_count[$project_base_count_key]['withindate'])){
				$withindate_count_data = count($project_base_count[$project_base_count_key]['withindate']);
				$project_base_count[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
				$graph_data[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
			}else{
				$withindate_count_data = 0;
				$project_base_count[$project_base_count_key]['withindate_count'] = 0;
				$graph_data[$project_base_count_key]['withindate_count'] = 0;
			}

			$withindate_array[$count]['y'] = $withindate_count_data;
			$withindate_array[$count]['id'] = $count_zero;
			$count++;
			$count_zero++;
		}


		$this->db->select("*");
		$this->db->from('company_projects');
		$query=$this->db->get();
		$registered_user_plan = $query->result();




		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$query=$this->db->get();
		$registered_user_plan = $query->result();


		$this->db->select("id,title");
		$this->db->from('subscription_plan');
		$query=$this->db->get();
		$subscription_plan = $query->result();

		$subscription_plan_array = array();

		$TypeSubscriptionActiveChart_type = array('Original','Renewals','Resubscriptions');


		
		
		foreach($TypeSubscriptionActiveChart_type as $TypeSubscriptionActiveChart_type_key=>$TypeSubscriptionActiveChart_type_value){
			foreach($subscription_plan as $subscription_plan_key=>$subscription_plan_value){
				$subscription_plan_array[$TypeSubscriptionActiveChart_type_value][] = array("label"=> $subscription_plan_value->title, "id"=> $subscription_plan_value->id);
			}
		}

		$OriginalPoints = array();
		foreach($subscription_plan_array['Original'] as $subscription_plan_array_key=>$subscription_plan_array_value){
			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Original" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$OriginalPoints[] = $subscription_plan_array_value;
		}

		

		$RenewalsPoints = array();
		foreach($subscription_plan_array['Renewals'] as $subscription_plan_array_key=>$subscription_plan_array_value){

			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Renewals" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$RenewalsPoints[] = $subscription_plan_array_value;
		}

		$ResubscriptionsPoints = array();
		foreach($subscription_plan_array['Resubscriptions'] as $subscription_plan_array_key=>$subscription_plan_array_value){
			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Resubscriptions" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$ResubscriptionsPoints[] = $subscription_plan_array_value;
		}

		$data['OriginalPoints'] = $OriginalPoints;
		$data['RenewalsPoints'] = $RenewalsPoints;
		$data['ResubscriptionsPoints'] = $ResubscriptionsPoints;

		$company_mapped_query = $this->db->query('SELECT count(company_id) as company_mapped FROM user_role where user_role.user_id = '.$user_id);
		$company_mapped_query_result = $company_mapped_query->row();
		$data['Companies_Mapped'] = $company_mapped_query_result->company_mapped;

		$location_mapped_query = $this->db->query('SELECT count(location_id) as location_mapped FROM user_role where user_role.user_id = '.$user_id);
		$location_mapped_query_result = $location_mapped_query->row();
		// $data['Locations_mapped'] = $location_mapped_query_result->location_mapped;
		$data['Locations_mapped'] = $location_mapped_query_result->location_mapped;

		$Closed_Projects_query = $this->db->query('SELECT * FROM company_projects where status in (2,3)');
		$Closed_Projects_count = $Closed_Projects_query->num_rows();
		$data['Closed_Projects'] = $Closed_Projects_count;

		$Cancelled_Projects_query = $this->db->query('SELECT * FROM company_projects where status = "0" AND cancelled_date IS NOT NULL');
		$Cancelled_Projects_count = $Cancelled_Projects_query->num_rows();
		$data['Cancelled_Projects'] = $Cancelled_Projects_count;
		
		$data['overdue_array'] = $overdue_array;
		$data['withindate_array'] = $withindate_array;

		$this->load->view('Userdashboard',$data);	
	}





	public function ApplicableOpenProjectGraph(){

		
		$company_datas = $_POST['company_id'];
		$location_datas = $_POST['location_id'];
		$user_id=$this->user_id;
		// $company_projects = $this->db->query('SELECT company.company_name,company_projects.* FROM company_projects LEFT JOIN company ON company_projects.company_id = company.id WHERE company_projects.company_id IN ('.$company_datas.')')->result();

		$company_projects = $this->db->query('SELECT company_locations.location_name,company_projects.* FROM company_projects LEFT JOIN company_locations ON company_projects.project_location = company_locations.id WHERE company_projects.company_id IN ('.$company_datas.') AND company_projects.status = 0')->result();

		$company_projects = $this->db->query('SELECT company_locations.location_name,company_projects.* FROM company_projects LEFT JOIN company_locations ON company_projects.project_location = company_locations.id WHERE company_projects.company_id IN ('.$company_datas.') AND item_owner = '.$user_id.' AND company_projects.status = 0')->result();


		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();


		if(!empty($location_datas)){
			// $company_projects = $this->db->query('SELECT company.company_name,company_projects.* FROM company_projects LEFT JOIN company ON company_projects.company_id = company.id WHERE company_projects.company_id IN ('.$company_datas.') AND company_projects.project_location = '.$location_datas)->result();

			$company_projects = $this->db->query('SELECT company_locations.location_name,company_projects.* FROM company_projects LEFT JOIN company_locations ON company_projects.project_location = company_locations.id WHERE company_projects.company_id IN ('.$company_datas.') AND item_owner = '.$user_id.' AND company_projects.project_location = '.$location_datas.' AND company_projects.status = 0')->result();

			// echo '<pre>last_query ';
			// print_r($this->db->last_query());
			// echo '</pre>';
			// exit();
		}

		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// // exit();

		// echo '<pre>company_projects ';
		// print_r($company_projects);
		// echo '</pre>';
		// // exit();
		
		$project_base_count = array();
		$withing_time = array();
		$due_date = array();
		foreach($company_projects as $company_projects_key=>$company_projects_value){

			$project_due_date = $company_projects_value->due_date;
			$project_name = $company_projects_value->project_name;
			$due_date = $project_due_date; // Format: Y-m-d
			$today = date('Y-m-d');

			// if ($due_date <= $today) {
			// 	$project_base_count[$company_projects_value->company_name]['overdue'][] = 1;
			// } else {
			// 	$project_base_count[$company_projects_value->company_name]['withindate'][] = 1;
			// }

			if ($due_date <= $today) {
				$project_base_count[$company_projects_value->location_name]['overdue'][] = 1;
			} else {
				$project_base_count[$company_projects_value->location_name]['withindate'][] = 1;
			}
		}

		// echo '<pre>';
		// print_r($project_base_count);
		// echo '</pre>';
		// exit();


		$graph_data = array();
		$count = 1;

		$overdue_array = array();
		$withindate_array = array();
		$count_zero = 1;
		$count = 0;
		foreach($project_base_count as $project_base_count_key=>$project_base_count_value){
			

			$overdue_array[$count]['label'] = $project_base_count_key;
			$withindate_array[$count]['label'] = $project_base_count_key;
			$overdue_count_data = 0;
			$withindate_count_data = 0;
			if(isset($project_base_count[$project_base_count_key]['overdue'])){
				$overdue_count_data = count($project_base_count[$project_base_count_key]['overdue']);
				$project_base_count[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue']);
				$graph_data[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue'] );
			}else{
				$overdue_count_data = 0;
				$project_base_count[$project_base_count_key]['overdue_count'] = 0;
				$graph_data[$project_base_count_key]['overdue_count'] = 0;
			}

			$overdue_array[$count]['y'] = $overdue_count_data;
			$overdue_array[$count]['id'] = $count_zero;

			if(isset($project_base_count[$project_base_count_key]['withindate'])){
				$withindate_count_data = count($project_base_count[$project_base_count_key]['withindate']);
				$project_base_count[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
				$graph_data[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
			}else{
				$withindate_count_data = 0;
				$project_base_count[$project_base_count_key]['withindate_count'] = 0;
				$graph_data[$project_base_count_key]['withindate_count'] = 0;
			}

			$withindate_array[$count]['y'] = $withindate_count_data;
			$withindate_array[$count]['id'] = $count_zero;
			$count++;
			$count_zero++;
		}

		$data['overdue_array'] = $overdue_array;
		$data['withindate_array'] = $withindate_array;
		echo json_encode($data);
	}


	public function Two(){
		// $data['projects']=$projects;
		$data['page_title']="Dashboard";
		$data['company_data_list']=$this->company_data_list();
		$this->load->view('dashboardgraph',$data);	
	}

	public function company_data_list(){
        $entity_code=$this->admin_registered_entity_code;
        $userid=$this->user_id;
        $query=$this->db->query("select * from user_role where  entity_code='".$entity_code."'  AND FIND_IN_SET(0,user_role) GROUP BY company_id");
		return $query->result();
	}

	public function company_data_list_new(){
        $entity_code=$this->admin_registered_entity_code;
        $userid=$this->user_id;
        $query=$this->db->query("select * from user_role where  entity_code='".$entity_code."' AND  user_id='".$userid."'  AND  company_id!='0' GROUP BY company_id");
		return $query->result();
	}

	public function users()
	{
		$data['page_title']="Users";
		$users=$this->db->select('*')->order_by('updated_on','desc')->get('users')->result();
		$this->load->view('users',array("meta"=>$data,"users"=>$users));

	}
	public function projectdetail($id)
	{
		$condition=array('id'=>$id);
		$projects=$this->tasks->get_data('company_projects',$condition);	
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
			// echo $this->db->last_query();
           
            if(!empty($getprojectdetails))
            {
                $project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                if($getprojectdetails[0]->VerifiedQuantity !='')
                $project->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
                else
                $project->VerifiedQuantity=0;
            }
            else
            {   
                $project->TotalQuantity=0;
                $project->VerifiedQuantity=0;
			}
			$condition2=array('id'=>$project->company_id);
			$company=$this->tasks->get_data('company',$condition2);
			$companylocation=$this->tasks->get_data('company_locations',array('id'=>$project->project_location));
			$project->company_name=$company[0]->company_name;
            $project->project_location=$companylocation[0]->location_name;
		}



		$listing=getTagUntag($projects[0]->project_name);
		$cat=getTagUntagCategories($projects[0]->project_name);
		$allcategories=getCategories($projects[0]->project_name);
		

		// print_r($projects);
		$data['projects']=$projects;
		$data['page_title']="Dashboard";

		$this->load->view('project_detail',$data);
		
	}
	public function projectprint($id)
	{
		$condition=array('id'=>$id);
		$projects=$this->tasks->get_data('company_projects',$condition);	
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
           
            if(!empty($getprojectdetails))
            {
                $project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                if($getprojectdetails[0]->VerifiedQuantity !='')
                $project->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
                else
                $project->VerifiedQuantity=0;
            }
            else
            {   
                $project->TotalQuantity=0;
                $project->VerifiedQuantity=0;
			}
			$condition2=array('id'=>$project->company_id);
			$company=$this->tasks->get_data('company',$condition2);
			$companylocation=$this->tasks->get_data('company_locations',array('id'=>$project->project_location));
			$project->company_name=$company[0]->company_name;
            $project->project_location=$companylocation[0]->location_name;
		}
		$data['projects']=$projects;
		$data['projectId']=$id;
		$data['page_title']="Dashboard";
		$pdfFilePath = 'pdf/prop_'.time().'.pdf';
		$pdf=$this->load->view('projectprint',$data , true);
		print_r($pdf);
		// $mpdf = new \Mpdf\Mpdf();
		
		
		// $pdf = mb_convert_encoding($pdf, 'UTF-8', 'UTF-8');
		// $mpdf->WriteHTML($pdf);
		// $mpdf->Output();
		// $mpdf->Output($_SERVER['DOCUMENT_ROOT'].'/'.$pdfFilePath, "F");  
		// echo json_encode(array("success"=>true,"pdfurl"=>base_url().$pdfFilePath));
		// if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$pdfFilePath)) {
		// 	header('Content-type: application/force-download');
		// 	header('Content-Disposition: attachment; filename='.$_SERVER['DOCUMENT_ROOT'].'/'.$pdfFilePath);
		// 	readfile($_SERVER['DOCUMENT_ROOT'].'/'.$pdfFilePath);
		//  }
		exit;
		
	}
	public function reports()
	{
		
		if($this->input->post('company_id') && $this->input->post('location_id') ){
		$lastProj=$this->db->query('Select * from company_projects where company_id='.$this->input->post('company_id').' and  project_location='.$this->input->post('location_id').' and entity_code="'.$this->admin_registered_entity_code.'"   order by id desc limit 1')->result();
		if(count($lastProj)>0)
		{
			$condition=array(
				'company_id'=>$this->input->post('company_id') ,
				'project_location'=>$this->input->post('location_id') ,
				'original_table_name'=>$lastProj[0]->original_table_name,
				'entity_code'=>$this->admin_registered_entity_code
			);
			$projects=$this->tasks->get_data('company_projects',$condition);
			if(count($projects)>0)
			{
				$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
				$new_pattern = array("_", "_", "");
				$headerCondition=array('table_name'=>$lastProj[0]->original_table_name);
				//$data['project_headers']=$this->tasks->get_schema(strtolower(preg_replace($old_pattern, $new_pattern , trim($projects[0]->project_name))));
				$data['project_headers']=$this->tasks->get_data('project_headers',$headerCondition);
				foreach($projects as $project)
				{
					$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
					$getprojectdetails=$this->tasks->projectdetail($project_name);
				
					if(!empty($getprojectdetails))
					{
						$project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
						if($getprojectdetails[0]->VerifiedQuantity !='')
						$project->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
						else
						$project->VerifiedQuantity=0;
					}
					else
					{   
						$project->TotalQuantity=0;
						$project->VerifiedQuantity=0;
					}
					
				}
			}
		}
		else
		{
			$projects=array();
		}	
	}else{
		$projects=array();
	 }
		$data['company_data_list']=$this->company_data_list_new();
		$data['projects']=$projects;
		$data['page_title']="Reports";
		$this->load->view('reports',$data);

	}
	public function generateReport(){
		$type=$this->input->post('optradio');
		$projectSelect=$this->input->post('projectSelect');
		$reporttype=$this->input->post('reporttype');
		$projectstatus=$this->input->post('projectstatus');
		$verificationstatus=$this->input->post('verificationstatus');
		$reportOutput=$this->input->post('reportOutput');
		$reportHeaders=$this->input->post('reportHeaders');
		$company_id=$this->input->post('company_id');
		$location_id=$this->input->post('location_id');
		$original_table_name=$this->input->post('original_table_name');
		if($reporttype == '2'){
			redirect("dashboard/additional_report/".$projectSelect);
			exit;
		}
		if($type=='project')
		{
			$condition=array(
				"id"=>$projectSelect,
				"status"=>$projectstatus,
				'company_id'=>$company_id,
				'project_location'=>$location_id,
				// 'entity_code'=>$this->admin_registered_entity_code
			);
			$reportSearch=array(
				"type"=>$type,
				"id"=>$projectSelect,
				"project_status"=>$projectstatus,
				"verification_status"=>$verificationstatus,
				"table_name"=>$original_table_name,
				"report_headers"=>$reportHeaders
			);
			
			$getProject=$this->tasks->get_data('company_projects',$condition);
			
			if(count($getProject) > 0)
			{
				$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
				$new_pattern = array("_", "_", "");
				$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
				$categories=$this->tasks->getdistinct_data($project_name,'item_category');
				
				if($reporttype==1)
				{
					$getreport=$this->tasks->getBasicReport($project_name,$verificationstatus,$reportHeaders);
				}
				else if($reporttype==2)
				{
					$getreport=$this->tasks->getDetailedReport($project_name,$verificationstatus,$reportHeaders);
				}
				else
				{
					$getreport=$this->tasks->getOriginalReport($project_name,$verificationstatus,$reportHeaders);
				}
				$getreport['project']=$getProject;
				$getreport['type']=$type;
				$getreport['reportHeaders']=$reportHeaders;
				$this->session->set_userdata('reportData', $reportSearch);
				$this->reportGenerate($getreport);
			}
			else
			{
				$this->session->set_flashdata('error_message', array("message"=>"Select other verification method OR Check the Status of Verification"));	
				redirect('index.php/dashboard/reports','refresh');
			}
		}
		else
		{

			$lastProj=$this->db->query('Select * from company_projects where status="'.$projectstatus.'" and company_id='.$company_id.'  and entity_code="'.$this->admin_registered_entity_code.'" order by id desc limit 1')->result();
			$condition=array(
				"status"=>$projectstatus,
				'company_id'=>$company_id,
				'original_table_name'=>$lastProj[0]->original_table_name,
				'entity_code'=>$this->admin_registered_entity_code
			);
			$reportSearch=array(
				"type"=>$type,
				"project_status"=>$projectstatus,
				"verification_status"=>$verificationstatus,
				"table_name"=>$original_table_name,
				"report_headers"=>$reportHeaders
			);
			$getProject=$this->tasks->get_data('company_projects',$condition);
			
			if(count($getProject) > 0)
			{
				$i=0;
				foreach($getProject as $getProject)
				{
					$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
					$new_pattern = array("_", "_", "");
					$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject->project_name)));
					$categories=$this->tasks->getdistinct_data($project_name,'item_category');
					
					if($reporttype==1)
					{
						$getreport[$i]=$this->tasks->getBasicReport($project_name,$verificationstatus,$reportHeaders);
					}
					else if($reporttype==2)
					{
						$getreport=$this->tasks->getDetailedReport($project_name,$verificationstatus,$reportHeaders);
					}
					else
					{
						$getreport=$this->tasks->getOriginalReport($project_name,$verificationstatus,$reportHeaders);
					}
					
					$getreport[$i]['project']=$getProject;
					$getreport[$i]['type']=$type;
					$getreport[$i]['reportHeaders']=$reportHeaders;
					$i++;
				}
				$unallocated=$this->tasks->getUnallocated($lastProj[0]->original_table_name,$verificationstatus,$reportHeaders);
				$getreport[0]['unallocated']=$unallocated;
				$this->session->set_userdata('reportData', $reportSearch);
				$this->consolidateReport($getreport);
			}
			else
			{
				$this->session->set_flashdata('error_message', array("message"=>"Select other verification method OR Check the Status of Verification"));	
				redirect('index.php/dashboard/reports','refresh');
			}
		}
		
	}
	public function reportGenerate($getreport)
	{
		$data['page_title']="Report";
		$data['data']=$getreport;
		$this->load->view('reportGenerate',$data);
	}
	public function consolidateReport($getreport)
	{
		$data['page_title']="Report";
		$data['data']=$getreport;
		$this->load->view('consolidateReport',$data);
	}
	public function exceptions()
	{

		if($this->input->post('company_id') && $this->input->post('location_id') ){
			$lastProj=$this->db->query('Select * from company_projects where company_id='.$this->input->post('company_id').' and  project_location='.$this->input->post('location_id').'  and  entity_code="'.$this->admin_registered_entity_code.'"   order by id desc limit 1')->result();
			
		if(count($lastProj)>0)
		{
			$condition=array(
				// 'company_id'=>$this->input->post('company_id') ,
				// 'project_location'=>$this->input->post('location_id') ,
				'original_table_name'=>$lastProj[0]->original_table_name,
				// 'entity_code'=>$this->admin_registered_entity_code

			);
			$projects=$this->tasks->get_data('company_projects',$condition);
			if(count($projects)>0)
			{
				$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
				$new_pattern = array("_", "_", "");
				$headerCondition=array('table_name'=>$lastProj[0]->original_table_name);
				//$data['project_headers']=$this->tasks->get_schema(strtolower(preg_replace($old_pattern, $new_pattern , trim($projects[0]->project_name))));
				$data['project_headers']=$this->tasks->get_data('project_headers',$headerCondition);
				foreach($projects as $project)
				{
					$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
					$getprojectdetails=$this->tasks->projectdetail($project_name);
				
					if(!empty($getprojectdetails))
					{
						$project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
						if($getprojectdetails[0]->VerifiedQuantity !='')
						$project->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
						else
						$project->VerifiedQuantity=0;
					}
					else
					{   
						$project->TotalQuantity=0;
						$project->VerifiedQuantity=0;
					}
					
				}
			}
		}
		else
		{
			$projects=array();
		}
	   }else
		{
			$projects=array();
		}	
		$data['company_data_list']=$this->company_data_list_new();
		$data['projects']=$projects;
		$data['page_title']="Excpetions";
		$this->load->view('exception_report',$data);

	}
	public function generateExceptionReport(){
		$type=$this->input->post('optradio');
		$projectSelect=$this->input->post('projectSelect');
		$exceptioncategory=$this->input->post('exceptioncategory');
		$projectstatus=$this->input->post('projectstatus');
		$verificationstatus=$this->input->post('verificationstatus');
		$reportOutput=$this->input->post('reportOutput');
		$reportHeaders=$this->input->post('reportHeaders');
		$original_table_name=$this->input->post('original_table_name');
		$company_id=$this->input->post('company_id');
		$location_id=$this->input->post('location_id');

		$reportView='';
		if($type=='project')
		{
			$condition=array(
				"id"=>$projectSelect,
				"status"=>$projectstatus,
				'company_id'=>$company_id,
				'project_location'=>$location_id,
				// 'entity_code'=>$this->admin_registered_entity_code

			);
			$reportSearch=array(
				"type"=>$type,
				"id"=>$projectSelect,
				"project_status"=>$projectstatus,
				"verification_status"=>$verificationstatus,
				"table_name"=>$original_table_name,
				"report_headers"=>$reportHeaders
			);
			
			$getProject=$this->tasks->get_data('company_projects',$condition);
			// echo $this->db->last_query();
			// echo "gaurav";die;



			if(count($getProject) > 0)
			{
				$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
				$new_pattern = array("_", "_", "");
				$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
				// echo '<pre>project_name :: ';
				// print_r($project_name);
				// echo '</pre>';
				// exit();

				$categories=$this->tasks->getdistinct_data($project_name,'item_category');
				// echo '<pre>last_query ';
				// print_r($this->db->last_query());
				// echo '</pre>';
				// echo '<pre>exceptioncategory ';
				// print_r($exceptioncategory);
				// echo '</pre>';
				// exit();
				// exit();

				// echo '<pre>exceptioncategory ::';
				// print_r($exceptioncategory);
				// echo '</pre>';
				// exit();
				
				
				if($exceptioncategory==1)	//Condition of Item
				{
					$getreport=$this->tasks->getExceptionOneReport($project_name,$verificationstatus,$reportHeaders);
					$reportView="conditionReport";
				}
				else if($exceptioncategory==2)	//Changes/ Updations of Items
				{
					$getreport=$this->tasks->getExceptionTwoReport($project_name,$verificationstatus,$reportHeaders);
					// $reportView="updationReport";
					$reportView="ChangesUpdationsItemsReport";
				}
				else if($exceptioncategory==3)	//Qty Validation Status
				{
					error_reporting(0);
					$getreport=$this->tasks->getExceptionThreeReport($project_name,$verificationstatus,$reportHeaders);
					$reportView="quantityValidationReport";
				}
				else if($exceptioncategory==4)	//Updated with Verification Remarks
				{
					$getreport=$this->tasks->getExceptionFourReport($project_name,$verificationstatus,$reportHeaders);
					$reportView="verificationRemarksReport";
				}
				else if($exceptioncategory==5)	//Updated with Item Notes
				{
					$getreport=$this->tasks->getExceptionFiveReport($project_name,$verificationstatus,$reportHeaders);
					$reportView="itemNotesReport";
				}
				else if($exceptioncategory==6)	//Calculate Risk Exposure
				{
					$getreport=$this->tasks->getExceptionSixReport($project_name,$verificationstatus,$reportHeaders);
					$reportView="CalculateRiskExposureReport";
				}
				else if($exceptioncategory==7)
				{
					$getreport=$this->tasks->getExceptionSevenReport($project_name,$verificationstatus,$reportHeaders);
					$reportView="riskExposerReport";
				}
				else if($exceptioncategory==8)	//Mode of Verification
				{
					$getreport=$this->tasks->getExceptionEightReport($project_name,$verificationstatus,$reportHeaders);
					$reportView="modeReport";
				}

				else if($exceptioncategory==10)	//Duplicate Item Codes verified || Identified
				{
					$getreport=$this->tasks->getExceptionNineReport($project_name,$verificationstatus,$reportHeaders);
					$reportView="DuplicateItemCodesReport";
				}

				$getreport['project']=$getProject;
				$getreport['type']=$type;
				$getreport['reportHeaders']=$reportHeaders;

			

				$this->session->set_userdata('reportData', $reportSearch);
				$this->exceptionReportGenerate($getreport,$reportView);
				
			}
			else
			{
				$this->session->set_flashdata('error_message', array("message"=>"Select other verification method OR Check the Status of Verification"));	
				redirect('index.php/dashboard/exceptions','refresh');
			}
		}
		if($type=='consolidated')
		{		

			$lastProj=$this->db->query('Select * from company_projects where status="'.$projectstatus.'" and company_id='.$company_id.'  and entity_code="'.$this->admin_registered_entity_code.'" order by id desc limit 1')->result();
			$condition=array(
				"status"=>$projectstatus,
				'company_id'=>$company_id,
				'original_table_name'=>$lastProj[0]->original_table_name,
				'entity_code'=>$this->admin_registered_entity_code

			);
			$reportSearch=array(
				"type"=>$type,
				"project_status"=>$projectstatus,
				"verification_status"=>$verificationstatus,
				"table_name"=>$original_table_name,
				"report_headers"=>$reportHeaders
			);
			$getProject=$this->tasks->get_data('company_projects',$condition);
			if(count($getProject) > 0)
			{
				$i=0;
				foreach($getProject as $getProject)
				{
					$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
					$new_pattern = array("_", "_", "");
					$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject->project_name)));
					$categories=$this->tasks->getdistinct_data($project_name,'item_category');
					
					if($exceptioncategory==1)
					{
						$getreport[$i]=$this->tasks->getExceptionOneReport($project_name,$verificationstatus,$reportHeaders);
						$reportView="conditionConsolidatedReport";
					}
					else if($exceptioncategory==2)
					{
						$getreport[$i]=$this->tasks->getExceptionTwoReport($project_name,$verificationstatus,$reportHeaders);
						$reportView="updationConsolidatedReport";
					}
					else if($exceptioncategory==3)
					{
						$getreport[$i]=$this->tasks->getExceptionThreeReport($project_name,$verificationstatus,$reportHeaders);
						$reportView="quantityValidationConsolidatedReport";
					}
					else if($exceptioncategory==4)
					{
						$getreport[$i]=$this->tasks->getExceptionFourReport($project_name,$verificationstatus,$reportHeaders);
						$reportView="verificationRemarksConsolidatedReport";
					}
					else if($exceptioncategory==5)
					{
						$getreport[$i]=$this->tasks->getExceptionFiveReport($project_name,$verificationstatus,$reportHeaders);
						$reportView="itemNotesConsolidatedReport";
					}
					else if($exceptioncategory==6)	//Calculate Risk Exposure
					{
						$getreport[$i]=$this->tasks->getExceptionSixReport($project_name,$verificationstatus,$reportHeaders);
						$reportView="markedForReviewReport";
					}
					else if($exceptioncategory==7)
					{
						$getreport[$i]=$this->tasks->getExceptionSevenReport($project_name,$verificationstatus,$reportHeaders);
						$reportView="riskExposerConsolidatedReport";
					}
					else if($exceptioncategory==8)
					{
						$getreport[$i]=$this->tasks->getExceptionEightReport($project_name,$verificationstatus,$reportHeaders);
						$reportView="modeConsolidatedReport";
					}
					$getreport[$i]['project']=$getProject;
					$getreport[$i]['type']=$type;
					$getreport[$i]['reportHeaders']=$reportHeaders;
					$i++;
				}
				if($exceptioncategory==1)
				{
					$unallocated=$this->tasks->getExceptionOneUnallocated($lastProj[0]->original_table_name,$verificationstatus,$reportHeaders);
				}
				else
				{
					$unallocated=[];
				}
				$getreport[0]['unallocated']=$unallocated;
				$getreport[0]['type']=$type;
				$this->session->set_userdata('reportData', $reportSearch);
				$this->exceptionConsolidatedReport($getreport,$reportView);
			}
			else
			{
				$this->session->set_flashdata('error_message', array("message"=>"Select other verification method OR Check the Status of Verification"));	
				redirect('index.php/dashboard/exceptions','refresh');
			}
		}
		if($type=='additional')
		{
			 $data['page_title']="Additional Assets Report";
			 
			 $data['comapny_row']=$this->tasks->com_row($company_id);
			 $data['location_row']=$this->tasks->loc_row($location_id);
			 $data['data']=$this->tasks->genrateadditionalassets($projectSelect);
			 $this->load->view('additional-assets-report',$data);

		}
		
	}
	public function exceptionReportGenerate($getreport,$reportView)
	{
		$data['page_title']="Report";
		$data['data']=$getreport;
		$this->load->view($reportView,$data);
	}
	public function exceptionConsolidatedReport($getreport,$reportView)
	{
		$data['page_title']="Report";
		$data['data']=$getreport;
		$this->load->view($reportView,$data);
	}
	public function changeAccess()
	{
		$user_id=$this->input->post('user_id');
		$status=$this->input->post('status');
		$condition=array(
			'user_id'=>$user_id
		);
		$data=array(
			'user_access'=>$status
		);
		$update=$this->company->update_data('users',$data,$condition);
		echo 1;
	}
	public function changeStatus()
	{
		$user_id=$this->input->post('user_id');
		$status=$this->input->post('status');
		$condition=array(
			'user_id'=>$user_id
		);
		$data=array(
			'user_status'=>$status
		);
		$update=$this->company->update_data('users',$data,$condition);
		echo 1;
	}
	public function deleteUser()
	{
		$user_id=$this->input->post('user_id');
		$condition=array(
			'user_id'=>$user_id
		);
		$delete=$this->company->delete_data('users',$condition);
		echo 1;
	}
	public function addUser()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$user_name=$this->input->post('user_name');
			$user_email=$this->input->post('user_email');
			$user_mobile=$this->input->post('user_mobile');
			$user_address=$this->input->post('user_address');
			$company_name=$this->input->post('company_name');
			$user_access=$this->input->post('user_access');
			$user_status=$this->input->post('user_status');
			$user_token=md5(uniqid($user_email, true));
			$userdata=array(
				'user_name'=>$user_name,
				'user_email'=>$user_email,
				'user_mobile'=>$user_mobile,
				'user_address'=>$user_address,
				'company_name'=>$company_name,
				'user_access'=>$user_access,
				'user_status'=>$user_status,
				'user_token'=>$user_token
			);
			$insert=$this->company->insert_data('users',$userdata);
			if($insert > 1)
			{
				$this->session->set_flashdata('success_message', "User added successfully");	
				redirect('dashboard/addUser','refresh');
			}
			else
			{
				$this->session->set_flashdata('error_message', array("message"=>"There is some issue with the values","data"=>$userdata));	
				redirect('dashboard/addUser','refresh');
			}
		}
		$data['page_title']="Add User";
		$this->load->view('addUser',array("meta"=>$data));
	}
	public function editUser()
	{
		$uid=$this->uri->segment(3);
		if ($this->input->server('REQUEST_METHOD') == 'POST')
		{
			$user_id=$this->input->post('user_id');
			$user_name=$this->input->post('user_name');
			$user_email=$this->input->post('user_email');
			$user_mobile=$this->input->post('user_mobile');
			$user_address=$this->input->post('user_address');
			$company_name=$this->input->post('company_name');
			$user_access=$this->input->post('user_access');
			$user_status=$this->input->post('user_status');
			$user_token=md5(uniqid($user_email, true));
			$updatedata=array(
				'user_name'=>$user_name,
				'user_email'=>$user_email,
				'user_mobile'=>$user_mobile,
				'user_address'=>$user_address,
				'company_name'=>$company_name,
				'user_access'=>$user_access,
				'user_status'=>$user_status,
				'user_token'=>$user_token,

			);
			$condition=array(
				'user_id'=>$user_id
			);
			$update=$this->company->update_data('users',$updatedata,$condition);
			if($update)
			{
				$this->session->set_flashdata('success_message', "User updated successfully");	
				redirect('dashboard/editUser/'.$user_id,'refresh');
			}
			else
			{
				$this->session->set_flashdata('error_message', array("message"=>"There is some issue with the values","data"=>$updatedata));	
				redirect('dashboard/editUser/'.$user_id,'refresh');
			}
		}
		$condition2=array(
			'user_id'=>$uid
		);
		$userdata=$this->company->get_data('users',$condition2);
		$data['page_title']="Edit User";
		$this->load->view('editUser',array("meta"=>$data,"userdata"=>$userdata));
	}

	
	public function downloadProjectReportFAR($location_id)
	{
		$location_row=get_location_row($location_id);
		require 'vendor/autoload.php';
		
		$reportData=$this->session->get_userdata('reportData');
		$reportData['logged_in']['company_id']=$location_row->company_id;
		$reportData['logged_in']['project_location']=$location_id;
		
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($cnt==count($project_headers)-2)
					{
						$columns.=" ".$ph->keyname.",";
					}
					else
					{
						$columns.=" ".$ph->keyname.",";
					}
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					array_push($colsArray,$project_headers[$i]->keyname);
					$columns.=" ".$project_headers[$i]->keyname.",";
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($i==count($reportHeaders)-1)
					{
						$columns.=" ".$reportHeaders[$i];
					}
					else
					{
						$columns.=" ".$reportHeaders[$i].",";
					}
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$columns =rtrim($columns,",");

		$getreport=$this->tasks->getDetailedReportFAR($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode (",", $getProject[0]->project_verifier);
			$verifier_name='';
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);

			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Project report details FAR';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadProjectReportTagged($location_id)
	{
		$location_row=get_location_row($location_id);

		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');

		$reportData['logged_in']['company_id']=$location_row->company_id;
		$reportData['logged_in']['project_location']=$location_id;
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($cnt==count($project_headers)-2)
					{
						$columns.=" ".$ph->keyname.",";
					}
					else
					{
						$columns.=" ".$ph->keyname.",";
					}
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					array_push($colsArray,$project_headers[$i]->keyname);
					$columns.=" ".$project_headers[$i]->keyname.",";
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($i==count($reportHeaders)-1)
					{
						$columns.=" ".$reportHeaders[$i];
					}
					else
					{
						$columns.=" ".$reportHeaders[$i].",";
					}
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$columns=rtrim($columns,",");
		$getreport=$this->tasks->getDetailedReportTagged($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode (",", $getProject[0]->project_verifier);
			$verifier_name='';
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);

			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Project report details Tagged';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadProjectReportNonTagged()
	{
		$location_row=get_location_row($location_id);

		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');

		$reportData['logged_in']['company_id']=$location_row->company_id;
		$reportData['logged_in']['project_location']=$location_id;

		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($cnt==count($project_headers)-2)
					{
						$columns.=" ".$ph->keyname.",";
					}
					else
					{
						$columns.=" ".$ph->keyname.",";
					}
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					array_push($colsArray,$project_headers[$i]);
					$columns.=" ".$project_headers[$i].",";
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($i==count($reportHeaders)-1)
					{
						$columns.=" ".$reportHeaders[$i];
					}
					else
					{
						$columns.=" ".$reportHeaders[$i].",";
					}
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$columns =rtrim($columns,",");

		$getreport=$this->tasks->getDetailedReportNonTagged($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode (",", $getProject[0]->project_verifier);
			$verifier_name='';
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);

			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Project report details Non-Tagged';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadProjectReportUnspecified()
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($cnt==count($project_headers)-2)
					{
						$columns.=" ".$ph->keyname;
					}
					else
					{
						$columns.=" ".$ph->keyname.",";
					}
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					array_push($colsArray,$project_headers[$i]->keyname);
					$columns.=" ".$project_headers[$i]->keyname.",";
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($i==count($reportHeaders)-1)
					{
						$columns.=" ".$reportHeaders[$i];
					}
					else
					{
						$columns.=" ".$reportHeaders[$i].",";
					}
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$getreport=$this->tasks->getDetailedReportUnspecified($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode (",", $getProject[0]->project_verifier);
			$verifier_name='';
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}

			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);

			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Project report details Unspecified';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadProjectReportAllocated($projectid)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($cnt==count($project_headers)-2)
					{
						$columns.=" ".$ph->keyname;
					}
					else
					{
						$columns.=" ".$ph->keyname.",";
					}
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					array_push($colsArray,$project_headers[$i]->keyname);
					$columns.=" ".$project_headers[$i]->keyname.",";
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($i==count($reportHeaders)-1)
					{
						$columns.=" ".$reportHeaders[$i];
					}
					else
					{
						$columns.=" ".$reportHeaders[$i].",";
					}
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$getreport=$this->tasks->getDetailedReportFAR($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode (",", $getProject[0]->project_verifier);
			$verifier_name='';
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);

			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Project report details FAR';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	
	public function downloadProjectReportUnallocated()
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($cnt==count($project_headers)-2)
					{
						$columns.=" ".$ph->keyname;
					}
					else
					{
						$columns.=" ".$ph->keyname.",";
					}
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					array_push($colsArray,$project_headers[$i]->keyname);
					$columns.=" ".$project_headers[$i]->keyname.",";
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					if($i==count($reportHeaders)-1)
					{
						$columns.=" ".$reportHeaders[$i];
					}
					else
					{
						$columns.=" ".$reportHeaders[$i].",";
					}
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		
		// $projCondition=array('id'=>$projectid);
		// $getProject=$this->tasks->get_data('company_projects',$projCondition);
		// $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		// $new_pattern = array("_", "_", "");
		// $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$getreport=$this->tasks->getDetailedReportUnallocated($table_name,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			

			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Unallocated");
			if($rowCount == count($getreport)+1)
			{
				$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
				$writer->setPreCalculateFormulas(false);
				$filename = 'Project report details Unallocated';
		
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
				header('Cache-Control: max-age=0');
				
				$writer->save('php://output');
			}
			$rowCount++;

		}
		
	}
	public function downloadExceptionOneGoodReport()
	{
		$reportOneType='qty_ok';
		$this->downloadExceptionOneReport($reportOneType,'ExceptionGoodReport');
	}
	public function downloadExceptionOneDamagedReport()
	{
		$reportOneType='qty_damaged';
		$this->downloadExceptionOneReport($reportOneType,'ExceptionDamagedReport');
	}
	public function downloadExceptionOneScrappedReport()
	{
		$reportOneType='qty_scrapped';
		$this->downloadExceptionOneReport($reportOneType,'ExceptionScrappedReport');
	}
	public function downloadExceptionOneMissingReport()
	{
		$reportOneType='qty_missing';
		$this->downloadExceptionOneReport($reportOneType,'ExceptionMissingReport');
	}
	public function downloadExceptionOneShiftedReport()
	{
		$reportOneType='qty_shifted';
		$this->downloadExceptionOneReport($reportOneType,'ExceptionShiftedReport');
	}
	public function downloadExceptionOneNotinuseReport()
	{
		$reportOneType='qty_not_in_use';
		$this->downloadExceptionOneReport($reportOneType,'ExceptionNotinuseReport');
	}
	public function downloadExceptionOneRemainingReport()
	{
		$reportOneType='qty_remaining';
		$this->downloadExceptionOneReport($reportOneType,'ExceptionRemainingReport');
	}
	public function downloadExceptionOneReportAllocated($projectid)
	{
		$reportOneType='consolidated';
		$this->downloadExceptionOneAllocatedReport($projectid,$reportOneType);
	}
	public function downloadExceptionOneReport($reportOneType,$ReportTitle)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		
		$headerCondition=array('table_name'=>$table_name);
		
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);

		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();



		 // $condition1=array('company_projects.project_verifier IN ('.$userid.') || company_projects.manager IN ('.$userid.') || company_projects.process_owner IN ('.$userid.') || company_projects.item_owner IN ('.$userid.')');

		

		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();
		

		
		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Condition of Items";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');
		
		$cnt=0;
		$rowCount=6;
		
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,new_location_verified,updatedat,verification_remarks,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,mode_of_verification,quantity_verified,verified_by";
		// $columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,new_location_verified,updatedat,verification_remarks,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,mode_of_verification,quantity_verified";

		array_push($colsArray,'quantity_as_per_invoice');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "To be Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'verified_by');
		// array_push($colsArray,'verified_by');
		// $sheet->setCellValue($rowHeads[$cnt].$rowCount, "Verify By");
		// $sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		// $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'total_item_amount_capitalized');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		array_push($colsArray,'new_location_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "New Location Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'verification_remarks');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_ok');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Good Condition");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_damaged');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Damaged");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_scrapped');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Scrapped");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_not_in_use');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Not in Use");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_missing');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Missing");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_shifted');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Shifted");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'quantity_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verify By");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Remaining To be verified: Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Remaining To be verified: Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=7;
		$getreport=$this->tasks->getDetailedExceptionOneReport($project_name,$verification_status,$columns,$reportOneType);
		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}

			// echo '<pre>';
			// print_r($gr);
			// echo '</pre>';
			// exit(); 

			$verifier_by_name = get_UserName($gr['verified_by']);


			$remainingAmount=$gr['total_item_amount_capitalized']/$gr['quantity_as_per_invoice'];
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $remainingAmount*$gr['quantity_verified']);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_by_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = $ReportTitle;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionOneAllocatedReport($projectid,$reportOneType)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,new_location_verified,updatedat,verification_remarks,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,mode_of_verification,quantity_verified";
		array_push($colsArray,'quantity_as_per_invoice');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "To be Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'total_item_amount_capitalized');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		array_push($colsArray,'new_location_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "New Location Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_remarks');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'qty_ok');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Good Condition");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'qty_damaged');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Damaged");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'qty_scrapped');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Scrapped");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'qty_not_in_use');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Not in Use");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'qty_missing');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Missing");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'qty_shifted');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Shifted");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'quantity_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Remaining To be verified: Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Remaining To be verified: Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$getreport=$this->tasks->getDetailedExceptionOneReport($project_name,$verification_status,$columns,$reportOneType);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$remainingAmount=$gr['quantity_as_per_invoice']==0?0:$gr['total_item_amount_capitalized']/$gr['quantity_as_per_invoice'];
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $remainingAmount*$gr['quantity_verified']);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, ($gr['quantity_as_per_invoice']-$gr['quantity_verified'])<=0?0:$gr['quantity_as_per_invoice']-$gr['quantity_verified']);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, ($gr['quantity_as_per_invoice']-$gr['quantity_verified'])<=0?0:$remainingAmount*($gr['quantity_as_per_invoice']-$gr['quantity_verified']));
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Exception Report';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}

	// Hardik
	public function downloadExceptionThreeVerifiedReport()
	{
		$reportOneType='verified';
		$this->downloadExceptionThreeReport($reportOneType,'VerifiedReport');
	}
	public function downloadExceptionThreeEqualReport()
	{
		$reportOneType='equal';
		$this->downloadExceptionThreeReport($reportOneType,'EqualReport');
	}
	public function downloadExceptionThreeShortReport()
	{
		$reportOneType='short';
		$this->downloadExceptionThreeReport($reportOneType,'ShortReport');
	}
	public function downloadExceptionThreeExcessReport()
	{
		$reportOneType='excess';
		$this->downloadExceptionThreeReport($reportOneType,'ExcessReport');
	}
	public function downloadExceptionThreeRemainingReport()
	{
		$reportOneType='remaining';
		$this->downloadExceptionThreeReport($reportOneType,'RemainingReport');
	}
	public function downloadExceptionThreeReportAllocated($projectid)
	{
		$reportOneType='consolidated';
		$this->downloadExceptionThreeAllocatedReport($projectid);
	}


	// Hardik downloadExceptionThreeReport
	public function downloadExceptionThreeReport($reportOneType,$ReportTitle)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);

		$original_verification_status = $verification_status; 
		if($original_verification_status == 'Not-Verified'){
			// $reportOneType = 'remaining';
			$verification_status = 'Verified';
		}

	
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();
		
		
		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Qty Validation Status";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');
		
		$cnt=0;
		$rowCount=6;



		// $cnt=0;
		$columns="";
		// $rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}

		$columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,new_location_verified,updatedat,verification_remarks,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,mode_of_verification,quantity_verified";

		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		if($verification_status!='Not-Verified')
		{
			array_push($colsArray,'verification_remarks');
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

			array_push($colsArray,'new_location_verified');
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "New Location Verified");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		}

		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'quantity_as_per_invoice');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'total_item_amount_capitalized');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);



		if($verification_status!='Not-Verified')
		{
			array_push($colsArray,'quantity_verified');
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified: Qty");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified: Amount");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Result");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

			if($reportOneType == 'remaining'){
				$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Quantity Remaining 'as per selection'");
				$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
				$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

				$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Amount Remaining 'as per selection'");
				$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
				$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

			}else{
				$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Quantity Verified 'as per selection'");
				$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
				$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

				$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Amount Verified 'as per selection'");
				$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
				$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			}
			
			
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		}



		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=7;

		
		// echo '<pre>reportOneType ::';
		// print_r($reportOneType);
		// echo '</pre>';
		// exit();
		

		if($getProject[0]->status==0)
		{
			$getreport=$this->tasks->getDetailedExceptionThreeReport($project_name,$verification_status,$columns,$reportOneType);	
		}
		else
		{
			$getreport=$this->tasks->getDetailedExceptionThreeProjectCloseRemaining($project_name,$verification_status,$columns,$reportOneType);	
		}

		$getreport=$this->tasks->getExceptionThreeReportdownload($project_name,$verification_status,$reportOneType);	

		// echo '<pre>verification_status ';
		// print_r($verification_status);
		// echo '</pre>';
		// exit();
		if($original_verification_status == 'Not-Verified'){
			$getreport=$this->tasks->getExceptionThreeReportdownload($project_name,'Not-Verified',$reportOneType);	
		}
		
	
		
		foreach($getreport as $gr)
		{
			
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				// echo '<pre>rowHeads :- '.$cnt.' ::- ';
				// print_r($rowHeads[$cnt].$rowCount);
				// echo '</pre>';
				// echo '<pre>colsArray :-'.$rh.' ::- ';
				// print_r($gr[$colsArray[$rh]]);
				// echo '</pre>';
				// // exit();
				// // exit();
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";


			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}

			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			if($verification_status!='Not-Verified')
			{
				$vAmount=($gr['total_item_amount_capitalized']/$gr['quantity_as_per_invoice'])*$gr['quantity_verified'];
				$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $vAmount);
				
				
				
				$vResult = 'Verified as Short';
				if($gr['quantity_verified']==0)
				{
					// $vResult="Unverified";
					$vResult="Not-Verified";
				}
				else if($gr['quantity_as_per_invoice']==$gr['quantity_verified'])
				{
					$vResult="Verified as Equal";	
				}
				/* else if($gr['quantity_as_per_invoice']<$gr['quantity_verified'])
				{
					$vResult="Verified as Short";	
				} */
				else if($gr['quantity_as_per_invoice']<$gr['quantity_verified'])
				{
					$vResult="Verified as Excess";	
				}


				$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $vResult);
				if($gr['quantity_as_per_invoice']==$gr['quantity_verified'])		//Hardik From this section their is showing difference.
				{
					// echo '<pre>rowHeads in if:- '.$cnt.' ::- ';
					// print_r($rowHeads[$cnt++].$rowCount);
					// echo '</pre>';
					if($reportOneType == 'remaining'){
						$sheet->setCellValue($rowHeads[$cnt++].$rowCount,$gr['quantity_verified']);
					}else{
						$sheet->setCellValue($rowHeads[$cnt++].$rowCount,0);
					}
					$sheet->setCellValue($rowHeads[$cnt++].$rowCount,$vAmount);	
				}
				else
				{
					// echo '<pre>rowHeads in Else:- '.$cnt.' ::- ';
					// print_r($rowHeads[$cnt++].$rowCount);
					// echo '</pre>';
					$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $gr['quantity_as_per_invoice']-$gr['quantity_verified']);
					$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $gr['total_item_amount_capitalized']-$vAmount);
				}
				$condition_of_item='';
				if($gr['qty_ok']>0)
				{
					$condition_of_item.="Good: ".$gr['qty_ok'].",";
				}
				if($gr['qty_damaged']>0)
				{
					$condition_of_item.="Damaged: ".$gr['qty_damaged'].",";
				}
				if($gr['qty_scrapped']>0)
				{
					$condition_of_item.="Scrapped: ".$gr['qty_scrapped'].",";
				}
				if($gr['qty_not_in_use']>0)
				{
					$condition_of_item.="Not in Use: ".$gr['qty_not_in_use'].",";
				}
				if($gr['qty_missing']>0)
				{
					$condition_of_item.="Missing: ".$gr['qty_missing'].",";
				}
				if($gr['qty_shifted']>0)
				{
					$condition_of_item.="Shifted: ".$gr['qty_shifted'];
				}
				$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $condition_of_item);
			}

			// echo '<pre>rowHeads :- '.$cnt.' ::- ';
			// print_r($rowHeads[$cnt].$rowCount);
			// echo '</pre>';
			// echo '<pre>colsArray :-'.$rh.' ::- ';
			// print_r($gr[$colsArray[$rh]]);
			// echo '</pre>';
			// exit();
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Qty Validation Report';

		$report_type = 'QtyValidationStatus_';
		$dateddmmyy = date('dmy'); 

		if($original_verification_status == '1'){
			$filename = $report_type.'AllReport_'.$dateddmmyy;
		}else if($original_verification_status == 'Verified'){
			$filename = $report_type.'VerifiedReport_'.$dateddmmyy;
		}else{
			$filename = $report_type.'NotVerifiedReport_'.$dateddmmyy;
		}
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}




	public function downloadExceptionThreeAllocatedReport($projectid)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,new_location_verified,updatedat,verification_remarks,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,mode_of_verification,quantity_verified";
		
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		if($verification_status!='Not-Verified')
		{
			array_push($colsArray,'verification_remarks');
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			array_push($colsArray,'new_location_verified');
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "New Location Verified");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		}
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'quantity_as_per_invoice');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'total_item_amount_capitalized');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		if($verification_status!='Not-Verified')
		{
			array_push($colsArray,'quantity_verified');
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified: Qty");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified: Amount");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Result");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Quantity Verified 'as per selection'");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Amount Verified 'as per selection'");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified");
			$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		}
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;

		$getreport=$this->tasks->getDetailedExceptionThreeConsolidatedReport($project_name,$verification_status,$columns);	

		// $project_name = 'kml_mahag_stock_rm_fg';
		// $verification_status = '1';
		// $reporttype = 'remaining';
		// $getreport=$this->tasks->getExceptionThreeReportdownload($project_name,$verification_status,$reporttype);	
		
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			if($verification_status!='Not-Verified')
			{
				$vAmount=($gr['total_item_amount_capitalized']/$gr['quantity_as_per_invoice'])*$gr['quantity_verified'];
				$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $vAmount);
				
				if($gr['quantity_verified']==0)
				{
					$vResult="Unverified";	
				}
				else if($gr['quantity_as_per_invoice']==$gr['quantity_verified'])
				{
					$vResult="Verified as Equal";	
				}
				else if($gr['quantity_as_per_invoice']<$gr['quantity_verified'])
				{
					$vResult="Verified as Short";	
				}
				else if($gr['quantity_as_per_invoice']<$gr['quantity_verified'])
				{
					$vResult="Verified as Excess";	
				}
				$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $vResult);
				if($gr['quantity_as_per_invoice']==$gr['quantity_verified'])
				{
					$sheet->setCellValue($rowHeads[$cnt++].$rowCount,$gr['quantity_verified']);
					$sheet->setCellValue($rowHeads[$cnt++].$rowCount,$vAmount);	
				}
				else
				{
					$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $gr['quantity_as_per_invoice']-$gr['quantity_verified']);
					$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $gr['total_item_amount_capitalized']-$vAmount);
				}
				$condition_of_item='';
				if($gr['qty_ok']>0)
				{
					$condition_of_item.="Good: ".$gr['qty_ok'].",";
				}
				if($gr['qty_damaged']>0)
				{
					$condition_of_item.="Damaged: ".$gr['qty_damaged'].",";
				}
				if($gr['qty_scrapped']>0)
				{
					$condition_of_item.="Scrapped: ".$gr['qty_scrapped'].",";
				}
				if($gr['qty_not_in_use']>0)
				{
					$condition_of_item.="Not in Use: ".$gr['qty_not_in_use'].",";
				}
				if($gr['qty_missing']>0)
				{
					$condition_of_item.="Missing: ".$gr['qty_missing'].",";
				}
				if($gr['qty_shifted']>0)
				{
					$condition_of_item.="Shifted: ".$gr['qty_shifted'];
				}
				$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $condition_of_item);
			}
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Exception Report';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionFourReport($item_category)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();


		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Updated with Verification Remarks";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');

		$cnt=0;
		// $columns="";
		$rowCount=6;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,updatedat,verification_remarks,mode_of_verification,quantity_verified";
		array_push($colsArray,'quantity_as_per_invoice');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "To be Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'total_item_amount_capitalized');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_remarks');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'quantity_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=7;
		$getreport=$this->tasks->getDetailedExceptionFourReport($project_name,$verification_status,$columns,$reportOneType,$item_category);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$remainingAmount=$gr['total_item_amount_capitalized']/$gr['quantity_as_per_invoice'];
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $remainingAmount*$gr['quantity_verified']);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Exception Report';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionFourAllReport()
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();


		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();


		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Updated with Verification Remarks";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');

		$cnt=0;
		// $columns="";
		$rowCount=6;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,updatedat,verification_remarks,mode_of_verification,quantity_verified";
		array_push($colsArray,'quantity_as_per_invoice');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "To be Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'total_item_amount_capitalized');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_remarks');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'quantity_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=7;
		$getreport=$this->tasks->getDetailedExceptionFourAllReport($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$remainingAmount=$gr['total_item_amount_capitalized']/$gr['quantity_as_per_invoice'];
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $remainingAmount*$gr['quantity_verified']);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'verificationRemarksReport';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionFourConsolidatedReport($projectid)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,updatedat,verification_remarks,mode_of_verification,quantity_verified";
		array_push($colsArray,'quantity_as_per_invoice');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "To be Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'total_item_amount_capitalized');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_remarks');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'quantity_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$getreport=$this->tasks->getDetailedExceptionFourAllReport($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$remainingAmount=$gr['quantity_as_per_invoice']==0?0:$gr['total_item_amount_capitalized']/$gr['quantity_as_per_invoice'];
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $remainingAmount*$gr['quantity_verified']);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Exception Report';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionFiveReport($item_category)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();

		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Updated with Item Notes";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');

		$cnt=0;
		// $columns="";
		$rowCount=6;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="verification_status,updatedat,verified_datetime,item_note,mode_of_verification";
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verified_datetime');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Item Note Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'item_note');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Item Note Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=7;
		$getreport=$this->tasks->getDetailedExceptionFiveReport($project_name,$verification_status,$columns,$reportOneType,$item_category);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'itemNotesReport';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionFiveAllReport()
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();

		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Updated with Item Notes";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');

		$cnt=0;
		// $columns="";
		$rowCount=6;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="verification_status,updatedat,verified_datetime,item_note,mode_of_verification";
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verified_datetime');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Item Note Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'item_note');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Item Note Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=7;
		$getreport=$this->tasks->getDetailedExceptionFiveAllReport($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Exception Report';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionFiveConsolidatedReport($projectid)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="verification_status,updatedat,verified_datetime,item_note,mode_of_verification";
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verified_datetime');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Item Note Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'item_note');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Item Note Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$getreport=$this->tasks->getDetailedExceptionFiveAllReport($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Exception Report';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionEightReport($mode)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();

		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Mode of Verification";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');

		$cnt=0;
		// $columns="";
		$rowCount=6;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="verification_status,updatedat,mode_of_verification,verification_remarks,new_location_verified,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,quantity_verified";
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'quantity_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Quantity Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_remarks');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'new_location_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "New Location Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=7;
		$getreport=$this->tasks->getDetailedExceptionEightReport($project_name,$verification_status,$columns,$mode);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$condition_of_item='';
			if($gr['qty_ok']>0)
			{
				$condition_of_item.="Good: ".$gr['qty_ok'].",";
			}
			if($gr['qty_damaged']>0)
			{
				$condition_of_item.="Damaged: ".$gr['qty_damaged'].",";
			}
			if($gr['qty_scrapped']>0)
			{
				$condition_of_item.="Scrapped: ".$gr['qty_scrapped'].",";
			}
			if($gr['qty_not_in_use']>0)
			{
				$condition_of_item.="Not in Use: ".$gr['qty_not_in_use'].",";
			}
			if($gr['qty_missing']>0)
			{
				$condition_of_item.="Missing: ".$gr['qty_missing'].",";
			}
			if($gr['qty_shifted']>0)
			{
				$condition_of_item.="Shifted: ".$gr['qty_shifted'];
			}
			
			
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $condition_of_item);
			
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'modeReport_'.$mode;
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	public function downloadExceptionEightConsolidatedReport($projectid)
	{
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}

		}
		$columns.="verification_status,updatedat,mode_of_verification,verification_remarks,new_location_verified,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,quantity_verified";
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'quantity_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Quantity Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_remarks');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'new_location_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "New Location Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=2;
		$getreport=$this->tasks->getDetailedExceptionEightConsolidatedReport($project_name,$verification_status,$columns);
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}
			$condition_of_item='';
			if($gr['qty_ok']>0)
			{
				$condition_of_item.="Good: ".$gr['qty_ok'].",";
			}
			if($gr['qty_damaged']>0)
			{
				$condition_of_item.="Damaged: ".$gr['qty_damaged'].",";
			}
			if($gr['qty_scrapped']>0)
			{
				$condition_of_item.="Scrapped: ".$gr['qty_scrapped'].",";
			}
			if($gr['qty_not_in_use']>0)
			{
				$condition_of_item.="Not in Use: ".$gr['qty_not_in_use'].",";
			}
			if($gr['qty_missing']>0)
			{
				$condition_of_item.="Missing: ".$gr['qty_missing'].",";
			}
			if($gr['qty_shifted']>0)
			{
				$condition_of_item.="Shifted: ".$gr['qty_shifted'];
			}
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $condition_of_item);
			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'Exception Report';
 
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}


	public function deleteproject()
	{
		
		$projectidarray = $_REQUEST['project_id'];
		$project_id_cont= count($projectidarray);

		for($i=0; $i< $project_id_cont;$i++){
		$original_table_name = $projectidarray[$i];
		echo $projectid=$projectidarray[$i];

		  $this->db->select("*");
		  $this->db->from("company_projects");
		  $this->db->where("id",$projectid);
		  $query=$this->db->get();
		  $result= $query->row();
		  $this->db->last_query();
		  $original_table_name = $result->original_table_name;
		  $this->plancycle->clearprojecdatabyid($projectid, $original_table_name);
		}
		$this->session->set_flashdata("success","Project Cleared Successfully");
		redirect("index.php/dashboard");

	}

	public function reopen_project($project_id){
		$data=array(
			"status"=>"0"
		);
		$this->db->where("id",$project_id);
		$this->db->update("company_projects",$data);
		$this->session->set_flashdata("success","Project Reopen Successfully");
		redirect("index.php/dashboard");
	}
	public function reopen_verification($project_id){
		$data=array(
			"status"=>"0"
		);
		$this->db->where("id",$project_id);
		$this->db->update("company_projects",$data);
		$this->session->set_flashdata("success","Project Verification Reopen Successfully");
		redirect("index.php/dashboard");
	}
  
	public function additional_report($project_id){
        
        $this->db->select("*");
        $this->db->from("additional_data");
        $this->db->where("id",$project_id);
		$queresultry= $this->db->get()->result();

		$data['additionaldata']=$result;
		$data['page_title']="Reports";
		$this->load->view('reports-additional',$data);
	}


	public function requestdeleteproject($project_id)
	{		

		$condition=array('id'=>$project_id);
		$projects=$this->tasks->get_data('company_projects',$condition);	
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
			// echo $this->db->last_query();
           
            if(!empty($getprojectdetails))
            {
                $project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                if($getprojectdetails[0]->VerifiedQuantity !='')
                $project->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
                else
                $project->VerifiedQuantity=0;
            }
            else
            {   
                $project->TotalQuantity=0;
                $project->VerifiedQuantity=0;
			}
			$condition2=array('id'=>$project->company_id);
			$company=$this->tasks->get_data('company',$condition2);
			$companylocation=$this->tasks->get_data('company_locations',array('id'=>$project->project_location));
			$project->company_name=$company[0]->company_name;
            $project->project_location=$companylocation[0]->location_name;
		}

		// print_r($projects);
		

		$this->db->select("company_projects.id as company_project_id,company_projects.project_name as company_project_name,");
		$this->db->join('company_projects', 'request_for_delete_project.project_id = company_projects.id', 'inner'); 
		$query = $this->db->get('request_for_delete_project');
		$result = $query->row();

		$data['projects']=$projects;
		$data['requestdeteleprojectdetails']=$result;
		$data['page_title']="Reports";
		$this->load->view('request_delete_project_details',$data);		

	}



	public function acceptrequestdeleteproject($project_id)
	{	
		$data=array(
			"status"=>"5"
		);
		$this->db->where("id",$project_id);
		$this->db->update("company_projects",$data);
		$this->session->set_flashdata("success","Project Accept Request Delete Successfully");
		redirect("index.php/dashboard");		

	}
	
	public function declinerequestdeleteproject($project_id)
	{		
		$data=array(
			"status"=>"6"
		);
		$this->db->where("id",$project_id);
		$this->db->update("company_projects",$data);
		$this->session->set_flashdata("success","Project Accept Request Delete Successfully");
		redirect("index.php/dashboard");	

	}

	

	/*  
public function downloadExceptionChangesUpdationsofItems()
	{

		$projectid = 26;
		$reportOneType = 'qty_ok';
		$ReportTitle = 'ChangesUpdationsofItems';
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		
		$headerCondition=array('table_name'=>$table_name);

		$company_projects = $this->db->query("SELECT *  FROM company_projects WHERE id='".$projectid."'")->row();
        $project_id = $company_projects->id;
        $company_id = $company_projects->company_id;
        $original_table_name = $company_projects->original_table_name;
        $project_table_name = $company_projects->project_table_name;


        $project_headers = $this->db->query("SELECT *  FROM project_headers WHERE project_id='".$projectid."' AND is_editable = 1")->result();
        // $project_header_column = array('id','item_sub_category','location_of_the_item_last_verified');
			$project_header_column = array('id','item_sub_category');
        foreach($project_headers as $project_headers_value){
            $project_header_column[] = $project_headers_value->keyname;
        }
        $project_header_column_value = implode(',', $project_header_column);
     


        $project_table_result = $this->db->query("SELECT ".$project_header_column_value." FROM ".$project_table_name)->result();
        $existing_id_array = array();
        foreach($project_table_result as $project_table_value){
            $existing_id_array[] = $project_table_value->id;
        }
        $existing_id_value = implode(',', $existing_id_array);
      

        // $project_header_column_base = array('id','item_sub_category','new_location_verified');
		$project_header_column_base = array('id','item_sub_category');
        foreach($project_headers as $project_headers_value){
            $project_header_column_base[] = $project_headers_value->keyname;
        }
        $project_header_column_base_value = implode(',', $project_header_column_base);
        $original_table_result = $this->db->query("SELECT ".$project_header_column_base_value." FROM ".$original_table_name." WHERE id in (".$existing_id_value.") ")->result();

        $different_array = array();
		$Updated_value_array = array();
        foreach($project_table_result as $project_table_key=>$project_table_value){

            foreach($project_header_column as $project_header_column_new_value)
            {
				if($original_table_result[$project_table_key]->$project_header_column_new_value != $project_table_result[$project_table_key]->$project_header_column_new_value){
					// $Updated_value_array[$project_header_column_new_value]['New'][] = $original_table_result[$project_table_key]->$project_header_column_new_value;
					// $Updated_value_array[$project_header_column_new_value]['Old'][] = $project_table_result[$project_table_key]->$project_header_column_new_value;
					$Updated_value_array[$project_header_column_new_value]['MixValue'][] = $original_table_result[$project_table_key]->$project_header_column_new_value." | ".$project_table_result[$project_table_key]->$project_header_column_new_value;
				}
            }
        }













		
		
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);

		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();



		 // $condition1=array('company_projects.project_verifier IN ('.$userid.') || company_projects.manager IN ('.$userid.') || company_projects.process_owner IN ('.$userid.') || company_projects.item_owner IN ('.$userid.')');

		

		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();
		

		
		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Condition of Items";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');
		
		$cnt=0;
		$rowCount=6;
		
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}
		}
		
		$columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,new_location_verified,updatedat,verification_remarks,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,mode_of_verification,quantity_verified,verified_by";
		
		// $columns.="quantity_as_per_invoice,total_item_amount_capitalized, verification_status,new_location_verified,updatedat,verification_remarks,qty_ok,qty_damaged,qty_scrapped,qty_not_in_use,qty_missing,qty_shifted,mode_of_verification,quantity_verified";

		array_push($colsArray,'quantity_as_per_invoice');
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, "To be Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'total_item_amount_capitalized');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "To be Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		array_push($colsArray,'verification_status');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		array_push($colsArray,'new_location_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "New Location Verified");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'updatedat');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Last Updated on");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'verification_remarks');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verification Remarks");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_ok');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Good Condition");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_damaged');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Damaged");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_scrapped');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Scrapped");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_not_in_use');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Not in Use");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_missing');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Missing");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'qty_shifted');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Condition of Item Verified: Shifted");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'mode_of_verification');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Mode of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		array_push($colsArray,'quantity_verified');
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verified Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Verify By");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocation Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project ID");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Name");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Start Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

    	$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Due Date");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Period of Verification");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Allocated Resources");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Project Status");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Remaining To be verified: Qty");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Remaining To be verified: Amount");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$sheet->setCellValue($rowHeads[++$cnt].$rowCount, "Hardik Development Test");
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
		
		
		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));

		// echo '<pre>columns ';
		// print_r($columns);
		// echo '</pre>';
		// exit();

		$rowCount=7;
		$getreport=$this->tasks->getDetailedExceptionOneReport($project_name,$verification_status,$columns,$reportOneType);

		// echo '<pre>getreport ';
		// print_r($getreport);
		// echo '</pre>';
		// exit();

		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();
		
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			$verifier=explode(',',$getProject[0]->project_verifier);
			$verifier_name="";
			for($ii=0;$ii<count($verifier);$ii++)
			{
				if($ii==count($verifier)-1)
				{
					$verifier_name.=get_UserName($verifier[$ii]);
				}
				else
				{
					$verifier_name.=get_UserName($verifier[$ii]).", ";
				}
			}
			$startdate=date_create($getProject[0]->start_date);
			$duedate=date_create($getProject[0]->due_date);
			$projectStatus='';
			if($getProject[0]->status==0)
			{
				$projectStatus='In Process';
			}
			else if($getProject[0]->status==1)
			{
				$projectStatus='Completed';
			}
			else if($getProject[0]->status==2)
			{
				$projectStatus='Cancelled';
			}
			else if($getProject[0]->status==3)
			{
				$projectStatus='Finished Verification';
			}

	
			$verifier_by_name = get_UserName($gr['verified_by']);
			$remainingAmount=$gr['total_item_amount_capitalized']/$gr['quantity_as_per_invoice'];
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $remainingAmount*$gr['quantity_verified']);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_by_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Allocated");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_id);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->project_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($startdate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, date_format($duedate,"d-m-Y"));
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $getProject[0]->period_of_verification);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $verifier_name);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, $projectStatus);
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "test");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "50");
			$sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Hardik Development Test");

			$rowCount++;
		}
		
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = $ReportTitle;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}
	*/





	public function downloadExceptionChangesUpdationsofItems11()
	{

		$projectid = 26;
		$reportOneType = 'qty_ok';
		$ReportTitle = 'ChangesUpdationsofItems';
		require 'vendor/autoload.php';
		$reportData=$this->session->get_userdata('reportData');
		$type=$reportData['reportData']['type'];
		$projectid=$reportData['reportData']['id'];
		$project_status=$reportData['reportData']['project_status'];
		$verification_status=$reportData['reportData']['verification_status'];
		$table_name=$reportData['reportData']['table_name'];
		$reportHeaders=$reportData['reportData']['report_headers'];
		
		$headerCondition=array('table_name'=>$table_name);

		$company_projects = $this->db->query("SELECT *  FROM company_projects WHERE id='".$projectid."'")->row();
        $project_id = $company_projects->id;
        $company_id = $company_projects->company_id;
        $original_table_name = $company_projects->original_table_name;
        $project_table_name = $company_projects->project_table_name;


        $project_headers = $this->db->query("SELECT *  FROM project_headers WHERE project_id='".$projectid."' AND is_editable = 1")->result();
        	$project_header_column = array('id','item_sub_category');
        foreach($project_headers as $project_headers_value){
            $project_header_column[] = $project_headers_value->keyname;
        }
        $project_header_column_value = implode(',', $project_header_column);
     


        $project_table_result = $this->db->query("SELECT ".$project_header_column_value." FROM ".$project_table_name)->result();
        $existing_id_array = array();
        foreach($project_table_result as $project_table_value){
            $existing_id_array[] = $project_table_value->id;
        }
        $existing_id_value = implode(',', $existing_id_array);
      

        // $project_header_column_base = array('id','item_sub_category','new_location_verified');
		$project_header_column_base = array('id','item_sub_category');
        foreach($project_headers as $project_headers_value){
            $project_header_column_base[] = $project_headers_value->keyname;
        }
        $project_header_column_base_value = implode(',', $project_header_column_base);
        $original_table_result = $this->db->query("SELECT ".$project_header_column_base_value." FROM ".$original_table_name." WHERE id in (".$existing_id_value.") ")->result();

        $different_array = array();
		$Updated_value_array = array();
        foreach($project_table_result as $project_table_key=>$project_table_value){

            foreach($project_header_column as $project_header_column_new_value)
            {
				if($original_table_result[$project_table_key]->$project_header_column_new_value != $project_table_result[$project_table_key]->$project_header_column_new_value){
					// $Updated_value_array[$project_header_column_new_value]['New'][] = $original_table_result[$project_table_key]->$project_header_column_new_value;
					// $Updated_value_array[$project_header_column_new_value]['Old'][] = $project_table_result[$project_table_key]->$project_header_column_new_value;
					$Updated_value_array[$project_header_column_new_value][$original_table_result[$project_table_key]->item_sub_category][$original_table_result[$project_table_key]->id] = $original_table_result[$project_table_key]->$project_header_column_new_value." | ".$project_table_result[$project_table_key]->$project_header_column_new_value;
				}
            }
        }




		// echo '<pre>Updated_value_array ';
		// print_r($Updated_value_array);
		// echo '</pre>';
		// exit(); 








		
		
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);

		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();



		 // $condition1=array('company_projects.project_verifier IN ('.$userid.') || company_projects.manager IN ('.$userid.') || company_projects.process_owner IN ('.$userid.') || company_projects.item_owner IN ('.$userid.')');

		

		$this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
		$this->db->from('company_projects');
		$this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
		$this->db->join('company','company.id=user_role.company_id');
		$this->db->join('company_locations','company_locations.id=company_projects.project_location');
		$this->db->where(array('company_projects.id'=>$projectid));
		$gettasks=$this->db->get();
		$company_project_details =  $gettasks->result();
		

		
		$cnt=0;
		$rowCount=1;
		$columns="";
		$colsArray=array();
		
		$details_content = "Name Of Company : ".$company_project_details[0]->company_name;
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=2;
		$details_content = "Name Of Location : ".$company_project_details[0]->location_name;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		$rowCount=3;
		$details_content = "Period of Verification : ".$company_project_details[0]->period_of_verification;
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


		$rowCount=4;
		$details_content = "Name of the Report : Condition of Items";
		// $sheet->mergeCells("A1:F1");
		$sheet->setCellValue($rowHeads[$cnt].$rowCount, $details_content);
		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

		// array_push($colsArray,'My title');
		
		$cnt=0;
		$rowCount=6;
		


		
		if($reportHeaders[0]=='all')
		{
			foreach($project_headers as $ph)
			{
				if($ph->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($ph->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$ph->keyname.",";
					array_push($colsArray,$ph->keyname);
					$cnt++;
				}
			}
		}
		else
		{
			for($i=0;$i<9;$i++)
			{
				if($project_headers[$i]->keyname!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords($project_headers[$i]->keylabel));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					
					$columns.=" ".$project_headers[$i]->keyname.",";
					array_push($colsArray,$project_headers[$i]->keyname);
					$cnt++;
				}
			}
			for($i=0;$i<count($reportHeaders);$i++)
			{
				if($reportHeaders[$i]!='is_alotted')
				{
					$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
					$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
					$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
					$columns.=" ".$reportHeaders[$i].",";
					array_push($colsArray,$reportHeaders[$i]);
					$cnt++;
				}
			}
		}
		$columns.="quantity_as_per_invoice";
		
		// echo '<pre>colsArray ';
		// print_r($colsArray);
		// echo '</pre>';
		// exit(); 

		// echo '<pre>Updated_value_array 111';
		// print_r($Updated_value_array);
		// echo '</pre>';
		// exit(); 

		// $Updated_value_array[$project_header_column_new_value]['MixValue'][] = $original_table_result[$project_table_key]->$project_header_column_new_value." | ".$project_table_result[$project_table_key]->$project_header_column_new_value;
		

		$projCondition=array('id'=>$projectid);
		$getProject=$this->tasks->get_data('company_projects',$projCondition);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject[0]->project_name)));
		$rowCount=7;

		$getreport=$this->tasks->getDetailedExceptionOneReport($project_name,$verification_status,$columns,$reportOneType);
	
		// echo '<pre>project_header_column_base ';
		// print_r($project_header_column_base);
		// echo '</pre>';


		// echo '<pre>Updated_value_array ';
		// print_r($Updated_value_array);
		// echo '</pre>';
		// exit(); 



	
		
		
		foreach($getreport as $gr)
		{
			$cnt=0;
			for($rh=0;$rh<count($colsArray);$rh++)
			{
				// echo '<pre>rowCount ';
				// print_r($rowHeads[$cnt].$rowCount);
				// echo '</pre>';
				// echo '<pre>colsArray ';
				// print_r($gr[$colsArray[$rh]]);
				// echo '</pre>';
				// exit(); 
				// exit(); 
				$sheet->setCellValue($rowHeads[$cnt].$rowCount,$gr[$colsArray[$rh]] );
				$cnt++;
			}
			// echo '<pre>rowCount ';
			// print_r($rowHeads[$cnt++].$rowCount);
			// echo '</pre>';
			// exit(); 
			// $sheet->setCellValue($rowHeads[$cnt++].$rowCount, "Hardik Development Test");

			$rowCount++;
		}


		$tablename = 'tenmaytwentyone';


		$company_projects = $this->db->query("SELECT *  FROM company_projects WHERE project_table_name='".$tablename."'")->row();
        // echo '<pre>last_query 1';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();
        $project_id = $company_projects->id;
        $company_id = $company_projects->company_id;
        $original_table_name = $company_projects->original_table_name;
        $project_table_name = $company_projects->project_table_name;


        $project_headers = $this->db->query("SELECT *  FROM project_headers WHERE project_id='".$project_id."' AND is_editable = 1")->result();
        $project_header_column = array('id','item_sub_category','location_of_the_item_last_verified');
        foreach($project_headers as $project_headers_value){
            $project_header_column[] = $project_headers_value->keyname;
        }
        $project_header_column_value = implode(',', $project_header_column);
     


        $project_table_result = $this->db->query("SELECT ".$project_header_column_value." FROM ".$project_table_name)->result();
       
        $existing_id_array = array();
        foreach($project_table_result as $project_table_value){
            $existing_id_array[] = $project_table_value->id;
        }
        $existing_id_value = implode(',', $existing_id_array);
      

        $project_header_column_base = array('id','item_sub_category','new_location_verified');
        foreach($project_headers as $project_headers_value){
            $project_header_column_base[] = $project_headers_value->keyname;
        }
        $project_header_column_base_value = implode(',', $project_header_column_base);
        $original_table_result = $this->db->query("SELECT ".$project_header_column_base_value." FROM ".$original_table_name." WHERE id in (".$existing_id_value.") ")->result();
       
        $different_array = array();
        foreach($project_table_result as $project_table_key=>$project_table_value){

            foreach($project_header_column as $project_header_column_new_value)
            {

                if($project_header_column_new_value == 'location_of_the_item_last_verified'){

                    if($original_table_result[$project_table_key]->new_location_verified != $project_table_result[$project_table_key]->$project_header_column_new_value){
                        $different_array['different'][$project_table_result[$project_table_key]->item_sub_category][$project_header_column_new_value][] = 1;
                    }

                }else{
                    if($original_table_result[$project_table_key]->$project_header_column_new_value != $project_table_result[$project_table_key]->$project_header_column_new_value){
                    $different_array['different'][$project_table_result[$project_table_key]->item_sub_category][$project_header_column_new_value][] = 1;
                }
                }

               
            }
        }
        $different_array['project_header_column_value'] = $project_header_column_value; 
		$project_header_column_value = explode(",",$project_header_column_value);
		unset($project_header_column_value[0]);
		unset($project_header_column_value[1]);

		// echo '<pre>project_header_column_value ';
		// print_r($project_header_column_value);
		// echo '</pre>';
		// exit(); 

 		$spreadsheet1= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet1 = $spreadsheet1->getActiveSheet();
		// $sheet1->setCellValue("A1", "Hardik Development Test");
		$rowCount = 1;
		$cnt = 0;
		// foreach($data['different'] as $key=>$value){ 
			foreach($project_header_column_value as $project_header_column_value_value){

				// echo '<pre> ';
				// print_r($project_header_column_value_value);
				// echo '</pre>';
				// // exit(); 

				// echo '<pre>cnt ';
				// print_r($cnt);
				// echo '</pre>';

				// echo '<pre>rowHeads ';
				// print_r($rowHeads);
				// echo '</pre>';

				// echo '<pre>rowCount ';
				// print_r($rowCount);
				// echo '</pre>';

				// echo '<pre>rowCount ';
				// print_r($rowHeads[$cnt].$rowCount);
				// echo '</pre>';
				// exit(); 

				// exit(); 
				// exit(); 
				// exit(); 

				$sheet1->setCellValue($rowHeads[$cnt].$rowCount,ucfirst(str_replace('_',' ',$project_header_column_value_value)) );
				
				$cnt++;

			}


			// exit();
		// }


			/*
		$project_header_column_value = explode(",",$data['project_header_column_value']);
		unset($project_header_column_value[0]);
		unset($project_header_column_value[1]);
		echo '<tr>';
		echo '<th>Allocated Item Category</th>';
		foreach($project_header_column_value as $project_header_column_value_value){
			echo '<th>';
			echo ucfirst(str_replace('_',' ',$project_header_column_value_value));
			echo '</th>';
		}
		echo '</tr>';


		foreach($data['different'] as $key=>$value){ 
			foreach($project_header_column_value as $project_header_column_value_value){
				echo '<td>';
				if(isset($data['different'][$key][$project_header_column_value_value])){
					echo count($data['different'][$key][$project_header_column_value_value]);
				}else{
					echo "0";
				}
				echo '</td>';
			}
		}

		

		echo '<pre>different_array ';
		print_r($different_array);
		echo '</pre>';
		exit(); 
		*/

		// exit();

		// $spreadsheet1= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		// $sheet1 = $spreadsheet1->getActiveSheet();
		// $sheet1->setCellValue("A1", "Hardik Development Test");
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet1, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = $ReportTitle;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
	}















		public function downloadExceptionChangesUpdationsofItems()
	{
		require 'vendor/autoload.php';
		$spreadsheet1= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet1 = $spreadsheet1->getActiveSheet();
		$ReportTitle = 'ChangesUpdationsofItems';
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();


		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');

		$tablename = 'tenmaytwentyone';
		$company_projects = $this->db->query("SELECT *  FROM company_projects WHERE project_table_name='".$tablename."'")->row();
		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();
        $project_id = $company_projects->id;
        $company_id = $company_projects->company_id;
        $original_table_name = $company_projects->original_table_name;
        $project_table_name = $company_projects->project_table_name;


        $project_headers = $this->db->query("SELECT *  FROM project_headers WHERE project_id='".$project_id."' AND is_editable = 1")->result();
		

        $project_header_column = array('id','item_sub_category','location_of_the_item_last_verified');
        foreach($project_headers as $project_headers_value){
            $project_header_column[] = $project_headers_value->keyname;
        }
        $project_header_column_value = implode(',', $project_header_column);
        $project_table_result = $this->db->query("SELECT ".$project_header_column_value." FROM ".$project_table_name)->result();
	   
        $existing_id_array = array();
        foreach($project_table_result as $project_table_value){
            $existing_id_array[] = $project_table_value->id;
        }
        $existing_id_value = implode(',', $existing_id_array);
      

        $project_header_column_base = array('id','item_sub_category','new_location_verified');
        foreach($project_headers as $project_headers_value){
            $project_header_column_base[] = $project_headers_value->keyname;
        }
        $project_header_column_base_value = implode(',', $project_header_column_base);
        $original_table_result = $this->db->query("SELECT ".$project_header_column_base_value." FROM ".$original_table_name." WHERE id in (".$existing_id_value.") ")->result();
	
        $different_array = array();
        foreach($project_table_result as $project_table_key=>$project_table_value){
            foreach($project_header_column as $project_header_column_new_value)
            {
                if($project_header_column_new_value == 'location_of_the_item_last_verified'){
                    if($original_table_result[$project_table_key]->new_location_verified != $project_table_result[$project_table_key]->$project_header_column_new_value){
                        $different_array['different'][$project_table_result[$project_table_key]->item_sub_category][$project_header_column_new_value]['old_value'][] = $original_table_result[$project_table_key]->new_location_verified;
						$different_array['different'][$project_table_result[$project_table_key]->item_sub_category][$project_header_column_new_value]['new_value'][] = $project_table_result[$project_table_key]->$project_header_column_new_value;
                    }
                }else{
                    if($original_table_result[$project_table_key]->$project_header_column_new_value != $project_table_result[$project_table_key]->$project_header_column_new_value){
                    	$different_array['different'][$project_table_result[$project_table_key]->item_sub_category][$project_header_column_new_value]['old_value'][] = $original_table_result[$project_table_key]->$project_header_column_new_value;
						$different_array['different'][$project_table_result[$project_table_key]->item_sub_category][$project_header_column_new_value]['new_value'][] = $project_table_result[$project_table_key]->$project_header_column_new_value;
                	}
                }
            }
        }

		
        $different_array['project_header_column_value'] = $project_header_column_value; 

		$project_header_column_value = explode(",",$project_header_column_value);
		unset($project_header_column_value[0]);
		unset($project_header_column_value[1]);

 		
		$rowCount = 1;
		$cnt = 0;

		

		foreach($project_header_column_value as $project_header_column_value_value){
			$sheet1->setCellValue($rowHeads[$cnt].$rowCount,ucfirst(str_replace('_',' ',$project_header_column_value_value)));
			$sheet1->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE ] );
			$sheet1->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			$cnt++;
		}

		$rowCount = 2;
		$cnt = 0;
		$all_value = array();
		foreach($different_array['different'] as $differentkey=>$differentvalue){
			$old_value = '';
			$new_value = '';
			foreach($differentvalue['location_of_the_item_last_verified']['old_value'] as $location_of_the_item_last_verified_key=>$location_of_the_item_last_verified_value_old){
					$all_value[] = "Old :- ".$location_of_the_item_last_verified_value_old." || New :- ".$differentvalue['location_of_the_item_last_verified']['new_value'][$location_of_the_item_last_verified_key];
			}
		}
		$rowCount = 2;
		$cnt1 = 0;
		foreach($all_value as $all_value_value){
			$sheet1->setCellValue($rowHeads[$cnt1].$rowCount,$all_value_value);
			$rowCount++;
		}

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet1,"Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = $ReportTitle ;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
	}

	
}
 