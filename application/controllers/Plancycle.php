<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plancycle extends CI_Controller {

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

		$this->load->model('Login_model','plancycle');
		$this->load->model('Tasks_model','tasks');		
		// Load form validation library
		$this->load->library('form_validation');
		$this->load->helper('function_helper');
		$this->load->model('Super_admin_model');
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
	 public function get_all_company_user_role($entity_code){
		$this->db->select("*");
		$this->db->from("user_role");
		$this->db->where("entity_code",$entity_code);
		$query = $this->db->get();
		return $query->result();

	}
	
	public function index()
	{	
		
		$session = $this->session->userdata('logged_in');
		$user_id = $session['id'];

		$entity_code = $session['admin_registered_entity_code'];
		// $resultttttcompany = $this->plancycle->GetCompanydataByUserIdOrEntityCode($user_id,$entity_code);
		$resultttttcompany = $this->plancycle->GetCompanydataByUserIdOrEntityCodeuserrole($user_id,$entity_code);

		// $copanyarry = array();
		// foreach($resulttttt as $dataa){

		// 	$userrole = explode(',',$dataa->user_role);
		// 	if (in_array("0", $userrole)){
		// 		$company_id = $dataa->company_id;
		// 		$copanyarry[] = $this->plancycle->GetCompanydataByCompanyId($company_id);
		// 	}
		// }
		

		// echo '<pre>';
		// print_r($_SESSION['logged_in']);
		// echo '</pre>';
		// exit(); 
		

		$this->db->select('register_user_plan_log.*, subscription_plan.*');
		$this->db->from(' subscription_plan');
		$this->db->join('register_user_plan_log','register_user_plan_log.plan_id= subscription_plan.id');
		$this->db->where('register_user_plan_log.register_user_id',$_SESSION['logged_in']['admin_registered_user_id']);
		$getnotifications=$this->db->get();
		$result = $getnotifications->row();
		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();
		$data['payment_history'] = $result;
		

		$condition=array(
			'id' => $this->company_id,
		);
		$condition1=array(
			'company_id' => $this->company_id,
		);
		// gaurav//
		$entity_code=$this->admin_registered_entity_code;
		$role_result_com = $this->get_all_company_user_role($entity_code);
		foreach($role_result_com as $row_role){
			$roledata[]=$row_role->company_id;
			$roledata1[]=$row_role->location_id;
		}

		$company_id_imp = implode(',',$roledata);
		$location_id = implode(',',$roledata1);
		$register_user_id=$this->admin_registered_user_id;
		$entity_code=$this->admin_registered_entity_code;

		$conditionnew='company_id IN ('.$company_id_imp.') AND project_location IN ('.$location_id.')';

		//  $condition1=array(
		// 			'id' => $this->company_id_imp,
		// 		);
		// 		$condition2=array(
		// 			'company_id IN'.$location_id.')',
		// 		);
		//gaurav//

		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$data['projects']=$this->db->query('SELECT * FROM company_projects WHERE '.$conditionnew.' AND status !=2 AND status !=1')->result();
		foreach($data['projects'] as $project)
		{
			$masterTotal=$this->db->query("SELECT count(*) as total from ".$project->original_table_name)->result();
			$project->masterTotal=$masterTotal[0]->total;
			$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
			
            if(!empty($getprojectdetails))
            {
                $project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                
            }
            else
            {   
                $project->TotalQuantity=0;
                
            }
		}
		if(count($data['projects'])>0)
		{
		$data['allocation_status']=$this->db->query('SELECT COUNT(*) as Remaining FROM '.$data['projects'][0]->original_table_name.' WHERE is_alotted=0')->result();
		}
		$data['companydata'] = $resultttttcompany; 
		$data['company']=$this->plancycle->get_data('company',$condition);
		$data['locations']=$this->plancycle->get_data('company_locations',$condition1);
		$data['page_title']="Plan Cycle";
		$this->load->view('plancycle',$data);
		
	}
	public function addcycle()
	{
		// require_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';
		require 'vendor/autoload.php';

		//var_dump($_FILES['test']['name']);die;
		$config['upload_path'] = './projectfiles/';
        $config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name']=true;

        $this->load->library('upload', $config);
		$filename='';
        if (!$this->upload->do_upload('project_file')) {
            $error = array('error' => $this->upload->display_errors());

			print_r($error);
			exit;
        } else {
            $data = $this->upload->data();
			$filename="./projectfiles/".$data['file_name'];
		}
		$this->session->set_userdata('projectfile',$filename);
		$inputfiletype=\PhpOffice\PhpSpreadsheet\IOFactory::identify($filename);
		$objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputfiletype);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load( $filename );
		$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

		$sheet = $objPHPExcel->getActiveSheet();

		$array_data = array();
		$tablename="project_".time();				//Hardik Excel To Table Name Generate from here.
		$inserting_array = array();
		$main=0;
		$insertRow=array();
		$rowCount=0;
		$highestRow = $objPHPExcel->getActiveSheet()->getHighestDataRow();
		foreach($rowIterator as $row){
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
			if(5 == $row->getRowIndex())
			{
				
				//skip first row
				
				$createquery="CREATE TABLE IF NOT EXISTS ".$tablename." (
					id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
				foreach ($cellIterator as $cell) {
					$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
					$new_pattern = array("_", "_", "");

					if(trim($cell->getCalculatedValue())=="Item Category")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NOT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Item Unique Code")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NOT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Item Description")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." TEXT NOT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Location of the Item last verified")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NOT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Quantity as per Invoice")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." INT (11) NOT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Verifiable Status (Y/ N/ NA)")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NOT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Tag Status (Y/ N/ NA)")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(10) NOT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Total Item Amount Capitalized")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DECIMAL(15,2) NOT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Accounting Voucher Date")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(20) DEFAULT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="PO/ WO Date")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(20) DEFAULT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Date of Purchase /Invoice date")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(20) DEFAULT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Date of Item Capitalization")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(20) DEFAULT NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="WDV at the end of reporting period")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DECIMAL(15,2) DEFAULT NULL,";
					}
					else
					{
						if($cell->getCalculatedValue() != '')
						{
							$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) DEFAULT NULL,";
						}
						
					}
					
				}
				
			
				$createquery.="verification_status VARCHAR(50) NOT NULL DEFAULT 'Not-Verified' ,quantity_verified INT(11) NOT NULL DEFAULT '0',new_location_verified VARCHAR(255) NULL,verified_by VARCHAR(255) NULL,verified_by_username VARCHAR(255) NULL,verified_datetime DATETIME NULL,verification_remarks TEXT NULL,item_note TEXT NULL,qty_ok INT(11) NOT NULL DEFAULT '0', qty_damaged INT(11) NOT NULL DEFAULT '0', qty_scrapped INT(11) NOT NULL DEFAULT '0',qty_not_in_use INT(11) NOT NULL DEFAULT '0',qty_missing INT(11) NOT NULL DEFAULT '0',qty_shifted INT(11) NOT NULL DEFAULT '0', is_alotted TINYINT(4) NOT NULL DEFAULT '0',is_edit INT(11) NOT NULL DEFAULT '0',instance_count INT(11) NOT NULL DEFAULT '0',mode_of_verification  VARCHAR(200) NOT NULL DEFAULT 'Not Verified',createdat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updatedat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)";		
				$this->db->query($createquery);
				
			}
			else if($row->getRowIndex()>5)
			{
				$rowIndex = $row->getRowIndex();
				
				$columns=$this->db->list_fields($tablename);
				
				$insertarray=array();
				$i=1;
				foreach ($cellIterator as $cell) {
					
					if($i <=69)
					{
						if($i==21 || $i==25 || $i==27 || $i==49 || $i==28)
						{
							if($cell->getCalculatedValue() != '')
								$insertarray["".$columns[$i].""]="NA";
							else
								$insertarray["".$columns[$i].""]="".$cell->getCalculatedValue()."";
						}
						else if($i==19 || $i ==20)
						{
							if($cell->getCalculatedValue() == '')
								$insertarray["".$columns[$i].""]="NA";
							else
								$insertarray["".$columns[$i].""]="".$cell->getCalculatedValue()."";
						}
						else
						{
							if($cell->getCalculatedValue() != '')
								$insertarray["".$columns[$i].""]="".$cell->getCalculatedValue()."";
							else
								$insertarray["".$columns[$i].""]='';
						}
					}
					$i++;
					
				}
				$inserting_array[] = $insertarray;
				array_push($insertRow,$insertarray);
				if($row->getRowIndex()>5 && ($rowCount==2000 || $row->getRowIndex()==$highestRow))
				{
					$ins=$insertRow;
					// $insert=$this->db->insert_batch($tablename,$ins);
					$rowCount=0;
					$insertRow=array();
					
					
				}
				else
				{
					$rowCount++;
				}
				// echo '<pre>';
				// print_r($insertarray);
				
				
				
			}
			
			
			$main++;
			
		}

		// echo '<pre>';
		// print_r($_SESSION);
		// echo '</pre>';
		// exit(); 
		
		$plan_data = $this->Super_admin_model->get_registered_user_plan($_SESSION['logged_in']['admin_registered_user_id']);
		
		$plan_row=get_plan_row($plan_data->plan_id);
		// echo '<pre>tablename ';
		// print_r($tablename);
		// echo '</pre>';
		// // exit(); 
		// $new_insert_array = array();
		$insert_count = 0;
		foreach($inserting_array as $inserting_array_key=>$inserting_array_value){
			if($insert_count < $plan_row->line_item_avaliable){
				$new_insert_array[] = $inserting_array_value;
				$this->db->insert($tablename,$inserting_array_value);		
			}
			$insert_count++;
		}

		// $insert=$this->db->insert_batch($tablename,$new_insert_array);
		
		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();


		// echo '<pre>new_insert_array ';
		// print_r($new_insert_array);
		// echo '</pre>';
		// exit(); 
		// $insert=$this->db->insert_batch($tablename,$inserting_array);
		// echo '<pre>inserting_array ';
		// print_r($inserting_array);
		// echo '</pre>';
		// exit(); 

		$data['page_title']="Plan Cycle";
		$data['company_name']=$this->input->post('company_name');
		$data['company_location']=$this->input->post('company_location');
		$data['table_name']=$tablename;
		$this->load->view('fileuploadconfirm',$data);
		
	}
	public function plancyclesteptwo()
	{
		$data['page_title']="Plan Cycle";
		$data['company_name']=$this->input->post('company_name');
		$data['company_location']=$this->input->post('company_location');
		$data['table_name']=$this->input->post('table_name');
		$getColumns=$this->plancycle->get_schema($data['table_name']);

		
		$getMandatoryColumns=array();
		foreach($getColumns as $gc)
		{
			array_push($getMandatoryColumns,$gc->COLUMN_NAME);
		}

		// echo '<pre>getMandatoryColumns ';
		// print_r($getMandatoryColumns);
		// echo '</pre>';

		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();
		// exit();

		

		$data['mandatory_cols']=$getMandatoryColumns;
		
		$getOtherColumns=$this->plancycle->getcompleteschema($data['table_name']);
		$is_condition = 0;
		$select='SELECT';
		// $select='SELECT *';
		$i=0;
		foreach($getOtherColumns as $goc)
		{
			if($goc->COLUMN_NAME != 'id' && $goc->COLUMN_NAME != 'quantity_verified' && $goc->COLUMN_NAME != 'verification_status' && $goc->COLUMN_NAME != 'createdat' && $goc->COLUMN_NAME != 'updatedat' && $goc->COLUMN_NAME != 'new_location_verified' && $goc->COLUMN_NAME != 'verified_by' && $goc->COLUMN_NAME != 'verified_by_username' && $goc->COLUMN_NAME != 'verified_datetime' && $goc->COLUMN_NAME != 'verification_remarks' && $goc->COLUMN_NAME != 'item_category' && $goc->COLUMN_NAME != 'item_unique_code' && $goc->COLUMN_NAME != 'item_description' && $goc->COLUMN_NAME != 'location_of_the_item_last_verified' && $goc->COLUMN_NAME != 'quantity_as_per_invoice' && $goc->COLUMN_NAME != 'verifiable_status_y_n_na' && $goc->COLUMN_NAME != 'tag_status_y_n_na' && $goc->COLUMN_NAME != 'total_item_amount_capitalized' && $goc->COLUMN_NAME != 'qty_ok' && $goc->COLUMN_NAME != 'qty_damaged' && $goc->COLUMN_NAME != 'qty_scrapped' && $goc->COLUMN_NAME != 'qty_not_in_use' && $goc->COLUMN_NAME != 'qty_missing' && $goc->COLUMN_NAME != 'qty_shifted'  && $goc->COLUMN_NAME != 'is_alotted')
			{
					if($i==0){
						$select.=" count(".$goc->COLUMN_NAME.") as ".$goc->COLUMN_NAME;
					}
					else{
						$select.=", count(".$goc->COLUMN_NAME.") as ".$goc->COLUMN_NAME;
					}
					$i++;
					$is_condition = 1;
			}
			
			
		}

		if($is_condition == '1'){
			$select.=" FROM ".$data['table_name'];
		}else{
			$select.=" * FROM ".$data['table_name'];
		}

		
		$getColumnsCount=$this->db->query($select)->result_array();
		$getNonMandatory=array_keys(array_filter($getColumnsCount[0]));

		// echo '<pre>getNonMandatory ';
		// print_r($getNonMandatory);
		// echo '</pre>';
		// // exit();
		$data['nonmandatory_cols']=$getNonMandatory;
		$this->load->view('markheaders',$data);
	}
	
	public function markheaders()
	{
		
		$userid=$this->user_id;
		$data['company_name']=$this->input->post('company_name');
		$data['company_location']=$this->input->post('company_location');
		$data['table_name']=$this->input->post('table_name');
		$i=$this->input->post('ival');
		for($iloop=1; $iloop<=$i; $iloop++)
		{
			if($this->input->post('keyname_'.$iloop) !='')
			{
				$headerdata=array(
					'user_id'=>$userid,
					'table_name'=>$data['table_name'],
					'company_id'=>$data['company_name'],
					'keyname'=>$this->input->post('keyname_'.$iloop),
					'keylabel'=>$this->input->post('keylabel_'.$iloop),
					'is_editable'=>$this->input->post($this->input->post('keyname_'.$iloop).'_edit'),

				);
				$insertHeaders=$this->plancycle->insert_data('project_headers',$headerdata);
			}
		}
		$data['page_title']="Plan Cycle";
		$this->load->view('confirmmarkheaders',$data);

	}
	public function plancyclestepthree()
	{
		$data['page_title']="Plan Cycle";
		$data['company_name']=$this->input->post('company_name');
		$data['company_location']=$this->input->post('company_location');
		$data['table_name']=$this->input->post('table_name');

		$projects=$this->plancycle->get_data('company_projects',array('original_table_name'=>$data['table_name']));
		if(count($projects)>0)
		{
			$data['original_file']=$projects[0]->original_file;
		}
		else
		{
			$fileOriginal=$this->session->get_userdata('projectfile');
			$data['original_file']=$fileOriginal['projectfile'];
		}
		// $data['categories']=$this->db->query('SELECT DISTINCT(item_category) FROM '.$data['table_name'])->result_array();

		$data['categories']=$this->db->query('SELECT DISTINCT(item_category) FROM '.$data['table_name'].' WHERE tag_status_y_n_na="Y" and is_alotted=0')->result_array();


		$data['tagged_count']=$this->db->query('SELECT COUNT(*) as `tagged_count` FROM '.$data['table_name'].' WHERE tag_status_y_n_na="Y" and is_alotted=0')->result_array();
		$data['nontagged_count']=$this->db->query('SELECT COUNT(*) as `nontagged_count` FROM '.$data['table_name'].' WHERE tag_status_y_n_na="N" and is_alotted=0')->result_array();
		$data['unspecified_count']=$this->db->query('SELECT COUNT(*) as `unspecified_count` FROM '.$data['table_name'].' WHERE tag_status_y_n_na="NA" and is_alotted=0')->result_array();
		$data['total_count']=$this->db->query('SELECT COUNT(*) as `total_count` FROM '.$data['table_name'].' WHERE is_alotted=0')->result_array();
		
		$this->load->view('setupfaverification',$data);
	}


	public function editverificationcycle($id){
		$data['page_title']="Plan Cycle";

		$project_data=$this->db->query('SELECT * FROM company_projects where id="'.$id.'"')->result_array();
		$company_name=$this->db->query('SELECT * FROM company where id="'.$project_data[0]['company_id'].'"')->result_array();
		$company_location=$this->db->query('SELECT * FROM company_locations where id="'.$project_data[0]['project_location'].'"')->result_array();
		
		$data['company_name']=$company_name[0]['company_name'];
		$data['company_location']=$company_location[0]['location_name'];

		$data['project']=$project_data;
		$data['categories']=$this->db->query('SELECT DISTINCT(item_category) FROM '.$project_data[0]['original_table_name'])->result_array();
		$data['tagged_count']=$this->db->query('SELECT COUNT(*) as `tagged_count` FROM '.$project_data[0]['original_table_name'].' WHERE tag_status_y_n_na="Y" and is_alotted=0')->result_array();
		$data['nontagged_count']=$this->db->query('SELECT COUNT(*) as `nontagged_count` FROM '.$project_data[0]['original_table_name'].' WHERE tag_status_y_n_na="N" and is_alotted=0')->result_array();
		$data['unspecified_count']=$this->db->query('SELECT COUNT(*) as `unspecified_count` FROM '.$project_data[0]['original_table_name'].' WHERE tag_status_y_n_na="NA" and is_alotted=0')->result_array();
		$data['total_count']=$this->db->query('SELECT COUNT(*) as `total_count` FROM '.$project_data[0]['original_table_name'].' WHERE is_alotted=0')->result_array();
		
		$this->load->view('editverification',$data);
	
	}

	public function edit_verification_save(){
		$project_id=$this->input->post('project_id');
		$end_date=$this->input->post('end_date');
		$start_date=$this->input->post('start_date');
		$instructions_to_user=$this->input->post('instructions_to_user');

		$data_array=array(
			"start_date"=>$start_date,
			"due_date"=>$end_date,
			"instruction_to_user"=>$instructions_to_user
		   );
		   $this->db->where('id',$project_id);
		   $this->db->update('company_projects',$data_array);
		   redirect("index.php/plancycle/editresourcemapping/".$project_id);
	}

	public function getlocationdata(){
		
		$company_id = $_POST['company_id'];
		$role_id = 0;
		if(isset($_POST['role_id']) && $_POST['role_id'] !='')
		{
			$role_id = $_POST['role_id'];
		}
		
		
		$this->db->select('*');
        $this->db->from('user_role');
        $this->db->join('company_locations','company_locations.id=user_role.location_id');
		$this->db->where('user_role.company_id',$company_id);
		// $this->db->where('user_role.company_id',$company_id);
		// if(isset($_POST['role_id']) && $_POST['role_id'] !='')
			$this->db->where('FIND_IN_SET('.$role_id.', user_role.user_role)');
		// }
		$this->db->group_by("user_role.location_id");
        $getnotifications=$this->db->get();
        $resulttttt =  $getnotifications->result();

		

		?>
				<option value="">Select Unit Location</option>
		<?php
		foreach($resulttttt as $dataa){
			?>
				<option value="<?php echo $dataa->id; ?>"><?php echo $dataa->location_name; ?></option>	
			<?php
		}
	}


	public function getprojectidndata(){
		$company_id = $_POST['company_id'];
		$location_id = $_POST['location_id'];
		$resulttttt = $this->plancycle->GetLocationdatabyid($company_id);

		$this->db->select('id,project_id');
        $this->db->from('company_projects');
        $this->db->where('company_id',$company_id);
		$this->db->where('project_location',$location_id);
		$getdata=$this->db->get();
		$resulttttt = $getdata->result();


		?>
				<option value="">Select Project ID</option>
		<?php
		foreach($resulttttt as $dataa){
			?>
				<option value="<?php echo $dataa->id; ?>"><?php echo $dataa->project_id; ?></option>	
			<?php
		}
	}


	public function getlocationdatanew1(){
		$company_id = $_POST['company_id'];
		$location_id = $_POST['location_id'];
		
		$resulttttt = $this->plancycle->GetLocationdatabyid($company_id);
		?>
				<option value="">Select Unit Location</option>
		<?php
		foreach($resulttttt as $dataa){
			?>
				<option value="<?php echo $dataa->id; ?>" <?php if($location_id ==$dataa->id){ echo "selected";}?>><?php echo $dataa->location_name; ?></option>	
			<?php
		}
	}

	public function getlocationdatanew(){
		$company_id = $_POST['company_id'];
		$user_id=$this->user_id;
		$resulttttt = $this->plancycle->get_allroles($company_id,$user_id);
		?>
				<option value="">Select Unit Location</option>
		<?php
		foreach($resulttttt as $dataa){
			$locrow=$this->plancycle->get_locrow($dataa->location_id);
			$check_location_assigned=check_location_assigned($company_id,$dataa->location_id,$user_id);
			if($check_location_assigned != '0'){
			?>
				<option value="<?php echo $locrow->id; ?>"><?php echo $locrow->location_name; ?></option>	
			<?php
		}
	  }
	}


	public function getlocationdatareport(){
		$company_id = $_POST['company_id'];
		$user_id=$this->user_id;
		$resulttttt = $this->plancycle->get_allroles($company_id,$user_id);
		?>
				<option value="">Select Unit Location</option>
		<?php
		foreach($resulttttt as $dataa){
			$locrow=$this->plancycle->get_locrow($dataa->location_id);
			$check_location_assigned=check_location_assigned($company_id,$dataa->location_id,$user_id);
			?>
				<option value="<?php echo $locrow->id; ?>"><?php echo $locrow->location_name; ?></option>	
			<?php
		
	  }
	}

	public function getlocationdatadata(){
		$company_id = $_POST['company_id'];
		$location_id = $_POST['location_id'];

		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$conditionnew = 'company_id IN ('.$company_id.') AND project_location IN ('.$location_id.')';
		$projects = $this->db->query('SELECT * FROM company_projects WHERE '.$conditionnew.' AND status !=2 AND status !=1')->result();

		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();

		if(!empty($projects)){
			?>
				<table class="table table-sm small">
				<thead class="text-center thead-dark">
					<tr>
						<th>#</th>
						<th>Project ID</th>
						<th>Project Name</th>
						<th>Project Verifier</th>
						<th>Project Categories</th>
						<th>Project Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody class="text-center">
					<?php
					$i=0;
					foreach($projects as $pro)
					{ 
						$masterTotal=$this->db->query("SELECT count(*) as total from ".$pro->original_table_name)->result();
						// echo '<pre>last_query ';
						// print_r($this->db->last_query());
						// echo '</pre>';
						// exit();
						$pro->masterTotal=$masterTotal[0]->total;
						$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($pro->project_name)));
						$getprojectdetails=$this->tasks->projectdetail($project_name);
						// echo '<pre>last_query ';
						// print_r($this->db->last_query());
						// echo '</pre>';
						// exit();
						
						if(!empty($getprojectdetails))
						{
							$pro->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
							
						}
						else
						{   
							$pro->TotalQuantity=0;
							
						}
					?>
					<tr>
						<td><?php echo ++$i;?></td>
						<td><?php echo $pro->project_id;?></td>
						<td>
							<?php echo $pro->project_name;?><br/>
							<?php echo "(Allocated ".$pro->TotalQuantity." of ".$pro->masterTotal.")"; ?>
						</td>
						<td>
							<?php 
								$k=0;
								$expverifier=explode(',',$pro->project_verifier);
								foreach($expverifier as $ver)
								{
									if($k==0)
										echo get_UserName($ver);
									else
										echo ', '.get_UserName($ver);
								}
							?>
						</td>
						<td><?php echo $pro->item_category;?></td>
						<td><?php echo $pro->status==0 ? "In-Process":($pro->status==3?"Verification Finished":"Cancelled");?></td>
						<td>
                		<?php if($pro->status==3){ ?> 
                       		<a href="<?php echo base_url();?>index.php/dashboard/projectdetail/<?php echo $pro->id;?>" id="contact_detail">
								<i class="fa fa-check"></i> Close & Finish
							</a>
             			<?php } ?>

						<a href="#" id="contact_detail" onclick="save_contact_detail('<?php echo $pro->id;?>')">
						<i class="fa fa-address-book"></i> Contact Detail

						</a>
							
							||

							<a href="<?php echo base_url();?>index.php/plancycle/editverificationcycle/<?php echo $prooooid = $pro->id; ?>">
							<i class="fa fa-pencil"></i>Edit
							</a>

							<!-- || -->

							<!-- <a href="<?php echo base_url();?>index.php/plancycle/deletedallocate?proid=<?php echo $prooooid = $pro->id; ?>&original_table_name=<?php echo $pro->original_table_name; ?>" onclick="return confirm('Are you sure you want to delete this project?');">
							<i class="fa fa-trash"></i>Delete
							</a> -->
						</td>
					</tr>
					<?php 
					}
					?>
				</tbody>
			</table>
			<?php
			if(count($projects)>0)
			{
			$allocation_status =$this->db->query('SELECT COUNT(*) as Remaining FROM '.$projects[0]->original_table_name.' WHERE is_alotted=0')->result();
			}
			?>
			<form method="POST" action="<?php echo base_url();?>index.php/plancycle/plancyclestepthree">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="text-center">
								<input type="hidden" value="<?php echo $projects[0]->company_id;?>" name="company_name">
								<input type="hidden" value="<?php echo $projects[0]->project_location;?>" name="company_location">
								<input type="hidden" value="<?php echo $projects[0]->original_table_name;?>" name="table_name">
								<?php
								if($allocation_status[0]->Remaining > 0)
								{
									echo '<button type="submit" class="btn pull-right-sec my-4">Allocate More Resources</button>';
								}
								else
								{
									echo '<a style="font-weight:bold;color:green;font-siz:18px;text-align:center;border-top:1px dashed green;padding-top:5px;" class="my-4">All items are allocated</a>';
								}
								?>
							</div>
						</div>
					</div>
				</div>

				<div class="clearfix"></div>
			</form>


			<?php
		}else{
			echo "uploaddoc";
		}
	}

	public function deletedallocate()
	{
		
		$projectid = $_REQUEST['proid'];
		$original_table_name = $_REQUEST['original_table_name'];
		$result= $this->plancycle->deleteprojecdatabyid($projectid, $original_table_name);
		
		$this->session->set_flashdata("success","Project Delete Successfully");
		redirect("index.php/plancycle");

	}

	public function getprojectcategories()
	{
		$table_name=$this->input->post('table_name');
		$ptype=$this->input->post('ptype');
		if($ptype=='TG')
		{
			$categories=$this->db->query('SELECT DISTINCT(item_category) FROM '.$table_name.' WHERE tag_status_y_n_na="Y" and is_alotted=0')->result_array();
		}
		else if($ptype=='NT')
		{
			$categories=$this->db->query('SELECT DISTINCT(item_category) FROM '.$table_name.' WHERE tag_status_y_n_na="N" and is_alotted=0')->result_array();
		}
		else if($ptype=='UN')
		{
			$categories=$this->db->query('SELECT DISTINCT(item_category) FROM '.$table_name.' WHERE tag_status_y_n_na="NA" and is_alotted=0')->result_array();
		}
		else
		{
			$categories=$this->db->query('SELECT DISTINCT(item_category) FROM '.$table_name.' WHERE is_alotted=0')->result_array();
		}
		echo json_encode($categories);
	}

	public function createproject()
	{	
		$company_name=$this->input->post('company_name');
		$company_location=$this->input->post('company_location');
		$data['page_title']="Plan Cycle";
		$company_name=$this->input->post('company_name');
		$company_location=$this->input->post('company_location');
		$table_name=$this->input->post('table_name');
		$project_name=$this->input->post('project_name');
		$project_type=$this->input->post('project_type');
		$period_of_verification=$this->input->post('period_of_verification');
		$start_date=$this->input->post('start_date');
		$end_date=$this->input->post('end_date');
		$item_category=$this->input->post('item_category');
		$instructions_to_user=$this->input->post('instructions_to_user');
		$original_file=$this->input->post('original_file');
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_table_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project_name)));
		$cname=$this->db->query("SELECT company_name,short_code from company where id=".$company_name)->result();
		$words = explode(" ", $cname[0]->company_name);
		$company_shortcode = $cname[0]->short_code;
		$acronym = "";

		foreach ($words as $w) {
		$acronym .= $w[0];
		}
		$lastid=$this->db->query("SELECT id from company_projects order by id desc limit 1")->result();
		if(count($lastid) > 0)
		{
			$lastid=$lastid[0]->id+1;
			if($lastid < 10)
			{
				$lastid=sprintf("%03d", $lastid);
			}
			else if($lastid < 100)
			{
				$lastid=sprintf("%02d", $lastid);
			}
			else
			{
				$lastid=$lastid;
			}
		}
		else
		{
			$lastid=0;
			$lastid=$lastid+1;
			$lastid=sprintf("%03d", $lastid);
		}
		
		$project_id=$company_shortcode.'/'.date("y").'/'.$lastid.'/'.$project_type;
		$ctgry='[';
		$ctgrycheck='';
		$ii=0;
		foreach($item_category as $ic)
		{
			if($ii==0)
			{
				$ctgry.='"'.$ic.'"';
				$ctgrycheck.='"'.$ic.'"';
			}
			else
			{
				$ctgry.=',"'.$ic.'"';
				$ctgrycheck.=',"'.$ic.'"';
			}
			$ii++;
		}
		$ctgry.=']';
		
		$insert=array(
			'project_id'=>$project_id,
			'project_name'=>$project_name,
			'company_id'=>$company_name,
			'status'=>0,
			'due_date'=>$end_date,
			'start_date'=>$start_date,
			'period_of_verification'=>$period_of_verification,
			'item_category'=>$ctgry,
			'instruction_to_user'=>$instructions_to_user,
			'project_type'=>$project_type,
			'project_location'=>$company_location,
			'original_table_name'=>$table_name,
			'project_table_name'=>$project_table_name,
			'original_file'=>$original_file,
			'assigned_by'=>$this->user_id,
			'entity_code'=>$this->admin_registered_entity_code
		);

		//Hardik Excel To Table Name Generate from here. in Below

		if($project_type=="TG")
		{
			$createProjectTable=$this->db->query("CREATE TABLE ".$project_table_name." LIKE ".$table_name);
			$insertProjectData=$this->db->query("INSERT INTO ".$project_table_name." (SELECT * FROM ".$table_name." where tag_status_y_n_na='Y' and is_alotted=0 and item_category in (".$ctgrycheck."))");
			$updateMainTable=$this->db->query("UPDATE ".$table_name." SET is_alotted=1 where tag_status_y_n_na='Y' and is_alotted=0 and item_category in (".$ctgrycheck.")");
		}
		else if($project_type=="NT")
		{
			$createProjectTable=$this->db->query("CREATE TABLE ".$project_table_name." LIKE ".$table_name);
			$insertProjectData=$this->db->query("INSERT INTO ".$project_table_name." (SELECT * FROM ".$table_name." where tag_status_y_n_na='N' and is_alotted=0 and item_category in (".$ctgrycheck."))");
			$updateMainTable=$this->db->query("UPDATE ".$table_name." SET is_alotted=1 where tag_status_y_n_na='N'  and is_alotted=0 and item_category in (".$ctgrycheck.")");
		}
		else if($project_type=="UN")
		{
			$createProjectTable=$this->db->query("CREATE TABLE ".$project_table_name." LIKE ".$table_name);
			$insertProjectData=$this->db->query("INSERT INTO ".$project_table_name." (SELECT * FROM ".$table_name." where tag_status_y_n_na='NA'  and is_alotted=0 and item_category in (".$ctgrycheck."))");
			$updateMainTable=$this->db->query("UPDATE ".$table_name." SET is_alotted=1 where tag_status_y_n_na='NA' and is_alotted=0 and item_category in (".$ctgrycheck.")");
		}
		else
		{
			$createProjectTable=$this->db->query("CREATE TABLE ".$project_table_name." LIKE ".$table_name);
			$insertProjectData=$this->db->query("INSERT INTO ".$project_table_name." (SELECT * FROM ".$table_name." where  is_alotted=0 and item_category in (".$ctgrycheck."))");
			$updateMainTable=$this->db->query("UPDATE ".$table_name." SET is_alotted=1 where is_alotted=0 and item_category in (".$ctgrycheck.")");
		}
		
		$createproject=$this->plancycle->insert_data('company_projects',$insert);
		$checkheader=$this->plancycle->get_data('project_headers',array('table_name'=>$table_name,'project_id'=>NULL));
		if(count($checkheader) > 0)
		{
			$updateheaders=$this->plancycle->update_data('project_headers',array('project_id'=>$createproject),array('table_name'=>$table_name,'project_id'=>NULL));
			$updateheaderid=$this->plancycle->update_data('company_projects',array('project_header_id'=>$createproject),array('id'=>$createproject));	
		
		}
		else
		{
			$getmainprojectid=$this->plancycle->get_data('company_projects',array('original_table_name'=>$table_name));
			$updateheaderid=$this->plancycle->update_data('company_projects',array('project_header_id'=>$getmainprojectid[0]->id),array('id'=>$createproject));	
		}

	

		$this->db->select('*'); 
		$this->db->from('user_role');
		$this->db->where('entity_code', $this->admin_registered_entity_code);
		$this->db->where('location_id', $company_location);
		$this->db->where('company_id', $company_name);
		$user_details = $this->db->get()->result();  

		$data['company_name'] = $company_name;
		$data['company_location'] = $company_location;
		$data['user_data'] = $user_details;
		$data['users']=$this->plancycle->get_data('users',array('company_id'=>$company_name));
		$data['project_id']=$createproject;
		$this->load->view('resourcemapping',$data);
	}


	public function editresourcemapping($project_id){
		$data['page_title']="Plan Cycle";

		$project_data=$this->db->query('SELECT * FROM company_projects where id="'.$project_id.'"')->result_array();
		$company_name=$this->db->query('SELECT * FROM company where id="'.$project_data[0]['company_id'].'"')->result_array();
		$company_location=$this->db->query('SELECT * FROM company_locations where id="'.$project_data[0]['project_location'].'"')->result_array();

		$data['company_name']=$company_name[0]['company_name'];
		$data['company_location']=$company_location[0]['location_name'];
		$data['project']=$project_data;

		$this->db->select('*'); 
		$this->db->from('user_role');
		$this->db->where('company_id', $project_data[0]['company_id']);
		$this->db->where('location_id', $project_data[0]['project_location']);
		$user_details = $this->db->get()->result();  

		$data['user_data'] = $user_details;
		$data['users']=$this->plancycle->get_data('users',array('company_id'=>$project_data[0]['company_id']));
		$this->load->view('editresourcemap',$data);
	}

	public function editresourcemappingsave()
	{
		$project_id=$this->input->post('project_id');
		$process_owner=$this->input->post('process_owner');
		$item_owner=$this->input->post('item_owner');
		$project_manager=$this->input->post('project_manager');
		$verifier=$this->input->post('verifier');
		$condition=array(
			'id'=>$project_id
		);
		;
		$ii=0;
		foreach($verifier as $ver)
		{
			if($ii==0)
			{
				$vfr=$ver;
			}
			else
			{
				$vfr.=','.$ver;
			}
			$ii++;
		}
		
		$ij=0;
		foreach($process_owner as $prc)
		{
			if($ij==0)
			{
				$po=$prc;
			}
			else
			{
				$po.=','.$prc;
			}
			$ij++;
		}
		$ik=0;
		foreach($item_owner as $ito)
		{
			if($ik==0)
			{
				$io=$ito;
			}
			else
			{
				$io.=','.$ito;
			}
			$ik++;
		}
		$il=0;
		foreach($project_manager as $pmg)
		{
			if($il==0)
			{
				$pm=$pmg;
			}
			else
			{
				$pm.=','.$pmg;
			}
			$il++;
		}
		

		$updatedata=array(
			'project_verifier'=>$vfr,
			'process_owner'=>$po,
			'item_owner'=>$io,
			'manager'=>$pm
		);
		$update=$this->plancycle->update_data('company_projects',$updatedata,$condition);		
		$this->session->set_flashdata("success","Updated Successfully");
		redirect("index.php/plancycle");

	}

	public function finishplancycle()
	{
		$project_id=$this->input->post('project_id');
		$process_owner=$this->input->post('process_owner');
		$item_owner=$this->input->post('item_owner');
		$project_manager=$this->input->post('project_manager');
		$verifier=$this->input->post('verifier');
		$condition=array(
			'id'=>$project_id
		);
		;
		$ii=0;
		foreach($verifier as $ver)
		{
			if($ii==0)
			{
				$vfr=$ver;
			}
			else
			{
				$vfr.=','.$ver;
			}
			$ii++;
		}
		
		$ij=0;
		$po='';
		if($process_owner !=''){
		foreach($process_owner as $prc)
		{
			if($ij==0)
			{
				$po=$prc;
			}
			else
			{
				$po.=','.$prc;
			}
			$ij++;
		}
	}
		$ik=0;
		$io="";
		if($item_owner !=''){
		foreach($item_owner as $ito)
		{
			if($ik==0)
			{
				$io=$ito;
			}
			else
			{
				$io.=','.$ito;
			}
			$ik++;
		}
	   }
		$il=0;
		foreach($project_manager as $pmg)
		{
			if($il==0)
			{
				$pm=$pmg;
			}
			else
			{
				$pm.=','.$pmg;
			}
			$il++;
		}
		

		$updatedata=array(
			'project_verifier'=>$vfr,
			'process_owner'=>$po,
			'item_owner'=>$io,
			'manager'=>$pm
		);
		$update=$this->plancycle->update_data('company_projects',$updatedata,$condition);
		$projectdetail=$this->plancycle->get_data('company_projects',$condition);
		$data['page_title']="Plan Cycle";
		$data['project_detail']=$projectdetail;
		$data['allocation_status']=$this->db->query('SELECT COUNT(*) as Remaining FROM '.$projectdetail[0]->original_table_name.' WHERE is_alotted=0')->result();
		$data['tagged_count']=$this->db->query('SELECT COUNT(*) as tagged_count FROM '.$projectdetail[0]->original_table_name.' WHERE tag_status_y_n_na="Y" and is_alotted=0')->result();
		$data['nontagged_count']=$this->db->query('SELECT COUNT(*) as nontagged_count FROM '.$projectdetail[0]->original_table_name.' WHERE tag_status_y_n_na="N" and is_alotted=0')->result();
		$data['unspecified_count']=$this->db->query('SELECT COUNT(*) as unspecified_count FROM '.$projectdetail[0]->original_table_name.' WHERE tag_status_y_n_na="NA" and is_alotted=0')->result();
		
		// $company=$this->plancycle->get_data('company',array('id',$projectdetail[0]->company_id));
		// $company_location=$this->plancycle->get_data('company_locations',array('id',$projectdetail[0]->project_location));
		$data['company_name']=$projectdetail[0]->company_id;
		$data['table_name']=$projectdetail[0]->original_table_name;
		$data['company_location']=$projectdetail[0]->project_location;
		$this->load->view('confirmfinish',$data);
	}

	public function get_contact_detail(){
		$project_id=$this->input->post('project_id');
		$this->db->select('*');
		$this->db->from('contact_detail');
		$this->db->where('project_id',$project_id);
		$query=$this->db->get();
		$num=$query->num_rows();
		$row_contact='';
		if($num == '0'){
			$row_contact .='<tr>
				<td colspan="5">No Record Found...</td>
				
			</tr>';
		}else{
		$result=$query->result();
          foreach($result as $row){

			$row_contact .='<tr>
				<td>'.$row->name.'</td>
				<td>'.$row->email.'</td>
				<td>'.$row->phone.'</td>
				<td>'.$row->designation.'</td>
				<td><a href="'.base_url().'index.php/plancycle/remove_project_contact/'.$row->id.'"><i class="fa fa-trash">Remove</a></td>
			</tr>';
		
		}
		echo $row_contact;
    	}
	}

	public function save_contact_detail(){
		$project_id = $this->input->post("project_id");

		$data=array(
			"project_id"=>$this->input->post("project_id"),
			"name"=>$this->input->post("name"),
			"email"=>$this->input->post("email"),
			"phone"=>$this->input->post("phone"),
			"designation"=>$this->input->post("designation"),
			"created_by"=>$this->user_id,
			"created_at"=>date("Y-m-d H:i:s")
		);
		$this->db->insert("contact_detail",$data);
	
	$this->session->set_flashdata("success","Detail Updated Successfully");
    redirect("index.php/plancycle");
	}


	public function remove_project_contact($id){
		$this->db->where("id",$id);
		$this->db->delete("contact_detail");
		$this->session->set_flashdata("success","Deleted Successfully");
		redirect("index.php/plancycle");
	}



	public function getprojectbylocation(){
		$location_id = $_POST['location_id'];
		// $resulttttt = $this->plancycle->GetLocationdatabyid($company_id);


		$resulttttt=$this->db->query('SELECT * from company_projects where project_location='.$_POST['location_id'])->result();
		$this->userRoleArray=array();
		?>
				<option value="">Select Project</option>
		<?php
		foreach($resulttttt as $dataa){
			?>
				<option value="<?php echo $dataa->id; ?>"><?php echo $dataa->project_name; ?></option>	
			<?php
		}
	}



	public function getreportinguserbylocation(){
		$project_id = $_POST['project_id'];
		// $resulttttt = $this->plancycle->GetLocationdatabyid($company_id);

		$user_role = 0;
		$register_user_id = $this->admin_registered_user_id;
        $entity_code = $this->admin_registered_entity_code;

		$resulttttt=$this->db->query('SELECT user_role.*,users.* from user_role INNER JOIN users ON users.id=user_role.user_id where FIND_IN_SET('.$user_role.',user_role) AND user_role.location_id='.$_POST['location_id'].' AND user_role.entity_code="'.$entity_code.'"')->result();

		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();

		$this->userRoleArray=array();
		?>
				<option value="">Select User</option>
		<?php
		foreach($resulttttt as $dataa){
			?>
				<option value="<?php echo $dataa->user_id; ?>"><?php echo $dataa->firstName; ?></option>	
			<?php
		}
	}



}
