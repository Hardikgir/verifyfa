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
			$sd=$this->db->query('SELECT * from company_projects where company_id='.$session['company_id'].' and ('.$session['id'].' IN (project_verifier) OR  '.$session['id'].' IN (manager) OR '.$session['id'].' IN (item_owner) OR '.$session['id'].' IN (process_owner))')->result();
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
				$this->user_type=0;
			}
		}

 	}
	public function index()
	{
		$condition=array(
			'id' => $this->company_id,
		);
		$condition2=array(
			'company_id' => $this->company_id,
		);
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$data['projects']=$this->db->query('SELECT * FROM company_projects WHERE company_id='.$this->company_id.' AND status !=2 AND status !=1')->result();
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
		$data['company']=$this->plancycle->get_data('company',$condition);
		$data['locations']=$this->plancycle->get_data('company_locations',$condition2);
		$data['page_title']="Plan Cycle";
		$this->load->view('plancycle',$data);
		
	}
	public function addcycle()
	{
		require_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';
		
		$config['upload_path'] = './projectfiles/';
        $config['allowed_types'] = 'xls|xlsx';
		$config['encrypt_name']=true;

        $this->load->library('upload', $config);
		$filename='';
		//var_dump($_FILES['project_file']);die;
        if (!$this->upload->do_upload('project_file')) {
            $error = array('error' => $this->upload->display_errors());

			print_r($error);
			exit;
        } else {
            $data = $this->upload->data();
			$filename="./projectfiles/".$data['file_name'];
		}
		$objReader = new PHPExcel_Reader_Excel5();
		//$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load( $filename );

		$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

		$sheet = $objPHPExcel->getActiveSheet();

		$array_data = array();
		$tablename="project_".time();
		$main=0;
		foreach($rowIterator as $row){
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
			if(1 == $row->getRowIndex())
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
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="PO/ WO Date")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Date of Purchase /Invoice date")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Date of Item Capitalization")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
					}
					else if(trim($cell->getCalculatedValue())=="Date of Sale")
					{
						$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
					}
					else
					{
						if($cell->getCalculatedValue() != '')
						{
							$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NULL,";
						}
						
					}
					
				}
				
			
				$createquery.="verification_status VARCHAR(50) NOT NULL DEFAULT 'Not-Verified' ,quantity_verified INT(11) NOT NULL DEFAULT '0',new_location_verified VARCHAR(255) NULL,verified_by VARCHAR(255) NULL,verified_by_username VARCHAR(255) NULL,verified_datetime DATETIME NULL,verification_remarks TEXT NULL,item_note TEXT NULL,qty_ok INT(11) NOT NULL DEFAULT '0', qty_damaged INT(11) NOT NULL DEFAULT '0', qty_scrapped INT(11) NOT NULL DEFAULT '0',qty_not_in_use INT(11) NOT NULL DEFAULT '0',qty_missing INT(11) NOT NULL DEFAULT '0',qty_shifted INT(11) NOT NULL DEFAULT '0', is_alotted TINYINT(4) NOT NULL DEFAULT '1' ,createdat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updatedat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)";		
				$this->db->query($createquery);
				
			}
			else
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
								$insertarray["".$columns[$i].""]="".PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD')."";
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
						}
					}
					$i++;
					
				}
				// echo '<pre>';
				// print_r($insertarray);
				
				$insert=$this->db->insert($tablename,$insertarray);
				
			}
			$main++;
			
		}
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
		$data['mandatory_cols']=$getMandatoryColumns;
		
		$getOtherColumns=$this->plancycle->getcompleteschema($data['table_name']);
		
		$select='SELECT';
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
			}
			
			
		}
		$select.=" FROM ".$data['table_name'];
		$getColumnsCount=$this->db->query($select)->result_array();
		$getNonMandatory=array_keys(array_filter($getColumnsCount[0]));
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
		$data['categories']=$this->db->query('SELECT DISTINCT(item_category) FROM '.$data['table_name'])->result_array();
		$data['tagged_count']=$this->db->query('SELECT COUNT(*) as `tagged_count` FROM '.$data['table_name'].' WHERE tag_status_y_n_na="Y" and is_alotted=0')->result_array();
		$data['nontagged_count']=$this->db->query('SELECT COUNT(*) as `nontagged_count` FROM '.$data['table_name'].' WHERE tag_status_y_n_na="N" and is_alotted=0')->result_array();
		$data['unspecified_count']=$this->db->query('SELECT COUNT(*) as `unspecified_count` FROM '.$data['table_name'].' WHERE tag_status_y_n_na="NA" and is_alotted=0')->result_array();
		$data['total_count']=$this->db->query('SELECT COUNT(*) as `total_count` FROM '.$data['table_name'].' WHERE is_alotted=0')->result_array();
		$this->load->view('setupfaverification',$data);
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
		$instruction_to_user=$this->input->post('instruction_to_user');
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		$project_table_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project_name)));
		$cname=$this->db->query("SELECT company_name from company where id=".$company_name)->result();
		$words = explode(" ", $cname[0]->company_name);
		$acronym = "";

		foreach ($words as $w) {
		$acronym .= $w[0];
		}
		$lastid=$this->db->query("SELECT id from company_projects order by id desc limit 1")->result();
		if($lastid[0]->id < 10)
		{
			$lastid=sprintf("%03d", $lastid[0]->id);
		}
		else if($lastid[0]->id < 100)
		{
			$lastid=sprintf("%03d", $lastid[0]->id);
		}
		else
		{
			$lastid=$lastid[0]->id;
		}
		$project_id=$acronym.'/'.date("y").'/'.$lastid.'/'.$project_type;
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
			'instruction_to_user'=>$instruction_to_user,
			'project_type'=>$project_type,
			'project_location'=>$company_location,
			'original_table_name'=>$table_name,
			'assigned_by'=>$this->user_id
		);
		if($project_type=="TG")
		{
			$createProjectTable=$this->db->query("CREATE TABLE ".$project_table_name." AS SELECT * FROM ".$table_name." where tag_status_y_n_na='Y' and is_alotted=0 and item_category in (".$ctgrycheck.")");
			$updateMainTable=$this->db->query("UPDATE ".$table_name." SET is_alotted=1 where tag_status_y_n_na='Y' and is_alotted=0 and item_category in (".$ctgrycheck.")");
		}
		else if($project_type=="NT")
		{
			$createProjectTable=$this->db->query("CREATE TABLE ".$project_table_name." AS SELECT * FROM ".$table_name." where tag_status_y_n_na='N' and is_alotted=0 and item_category in (".$ctgrycheck.")");
			$updateMainTable=$this->db->query("UPDATE ".$table_name." SET is_alotted=1 where tag_status_y_n_na='N'  and is_alotted=0 and item_category in (".$ctgrycheck.")");
		}
		else if($project_type=="UN")
		{
			$createProjectTable=$this->db->query("CREATE TABLE ".$project_table_name." AS SELECT * FROM ".$table_name." where tag_status_y_n_na='NA'  and is_alotted=0 and item_category in (".$ctgrycheck.")");
			$updateMainTable=$this->db->query("UPDATE ".$table_name." SET is_alotted=1 where tag_status_y_n_na='NA' and is_alotted=0 and item_category in (".$ctgrycheck.")");
		}
		else
		{
			$createProjectTable=$this->db->query("CREATE TABLE ".$project_table_name." AS SELECT * FROM ".$table_name." where  is_alotted=0 and item_category in (".$ctgrycheck.")");
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

		
		$data['users']=$this->plancycle->get_data('users',array('company_id'=>$company_name));
		$data['project_id']=$createproject;
		$this->load->view('resourcemapping',$data);
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
		$projectdetail=$this->plancycle->get_data('company_projects',$condition);
		$data['page_title']="Plan Cycle";
		$data['project_detail']=$projectdetail;
		$data['allocation_status']=$this->db->query('SELECT COUNT(*) as Remaining FROM '.$projectdetail[0]->original_table_name.' WHERE is_alotted=0')->result();
		// $company=$this->plancycle->get_data('company',array('id',$projectdetail[0]->company_id));
		// $company_location=$this->plancycle->get_data('company_locations',array('id',$projectdetail[0]->project_location));
		$data['company_name']=$projectdetail[0]->company_id;
		$data['table_name']=$projectdetail[0]->original_table_name;
		$data['company_location']=$projectdetail[0]->project_location;
		$this->load->view('confirmfinish',$data);
	}
}
