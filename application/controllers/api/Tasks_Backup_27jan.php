<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";

class Tasks extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->load->helper('function_helper','file','download');
		$this->load->model('tasks_model','tasks');
        // $this->load->helper('file');
        // $this->load->helper('download');
        // $this->load->library('Excel');
        $this->load->library('PHPExcel');

	}

	public function getprojects()
	{
		$userid=$this->input->post('user_id');
		$company_id=$this->input->post('company_id');
		$location_id=$this->input->post('location_id');
		$condition=array(
			"id"=>$userid
		);
        $projects=$this->tasks->getProjects('users',$userid,$company_id,$location_id);
        // echo $this->db->last_query();
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {

    $verifiercount = check_verifier_count($project->id,$userid);
    $check_itemowner_count = check_itemowner_count($project->id,$userid);
    $check_process_owner_count = check_process_owner_count($project->id,$userid);
    $check_manager_count = check_manager_count($project->id,$userid);

    $verifiername = $this->tasks->get_verifire_name($project->project_verifier);

    $project->verifier_name=$verifiername;

    $project->verifier_cnt=$verifiercount;
    $project->iten_owner_cnt=$check_itemowner_count;
    $project->process_owner_count=$check_process_owner_count;
    $project->check_manager_count=$check_manager_count;

    if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){
            $project->project_location=$project->location_name;
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
            $getlastupdatedtime=$this->tasks->lastupdatetime($project_name,$userid);
            if(!empty($getlastupdatedtime))
            {
                $project->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($getlastupdatedtime[0]->updatedat)));
            }
            else
            {
                $project->updatedat='';
            }
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
            $project->verifier_name=$verifiername;
            $project->assigned_by=get_UserName($project->assigned_by);
            $projectheaders=$this->tasks->get_data('project_headers',array('project_id'=>$project->project_header_id));
            $project->visiblecolumns=$projectheaders;
        }else{
            $project->verifier_name=$verifiername;

        }
        }
		if(!empty($projects) && count($projects) > 0)
		{
            
			header('Content-Type: application/json');
			echo json_encode(array("success"=>200,"message"=>"Projects fetched successfully.","data"=>$projects));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No project assigned"));
			exit;
		}
    }


    public function get_all_company_user_role($entity_code,$user_id){           //[NOT IN POSTMAN]
		$this->db->select("*");
		$this->db->from("user_role");
		$this->db->where("entity_code",$entity_code);
		$this->db->where("user_id",$user_id);
		$query = $this->db->get();
		return $query->result();

	}

    public function get_all_company_user_role_by_location_id($entity_code,$user_id,$location_id){           //[NOT IN POSTMAN]
        $this->db->select("*");
		$this->db->from("user_role");
		$this->db->where("entity_code",$entity_code);
        $this->db->where("location_id",$location_id);
		$this->db->where("user_id",$user_id);
		$query = $this->db->get();
		return $query->result();
    }



    public function getDashboard()
    {
        $userid=$this->input->post('user_id');
        $entity_code=$this->input->post('entity_code');
        $location_id=$this->input->post('location_id');

        $company_id_imp='';
        
        $role_result_com = $this->get_all_company_user_role($entity_code,$userid);
        // $role_result_com = $this->get_all_company_user_role_by_location_id($entity_code,$userid,$location_id);
        $location_id='';
        if(!empty($role_result_com)){

        
            foreach($role_result_com as $row_role){
                $roledata[]=$row_role->company_id;
                $roledata1[]=$row_role->location_id;
            }

            $company_id_imp = implode(',',$roledata);
            $location_id = implode(',',$roledata1);
            }

		$condition=array(
			"id"=>$userid
		);

        
        $location_id=$this->input->post('location_id');
        
        $projects=$this->tasks->getProjectsdashboard('users',$userid,$entity_code,$company_id_imp,$location_id);
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
            $project->project_location=$project->location_name;
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $project->listing=getTagUntag($project->project_name);
            $project->cat=getTagUntagCategories($project->project_name);
            $project->allcategories=getCategories($project->project_name);

            $getprojectdetails=$this->tasks->projectdetail($project_name);
            $getlastupdatedtime=$this->tasks->lastupdatetime($project_name,$userid);
            if(!empty($getlastupdatedtime))
            {
                $project->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($getlastupdatedtime[0]->updatedat)));
            }
            else
            {
                $project->updatedat='';
            }
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
            $project->assigned_by=get_UserName($project->assigned_by);
            $projectheaders=$this->tasks->get_data('project_headers',array('project_id'=>$project->project_header_id));
            $project->visiblecolumns=$projectheaders;
        }
		if(!empty($projects) && count($projects) > 0)
		{
            
			header('Content-Type: application/json');
			echo json_encode(array("success"=>200,"message"=>"Projects fetched successfully.","data"=>$projects));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No project assigned"));
			exit;
		}
        
    }
    public function getsearchprojects()
	{
		$userid=$this->input->post('user_id');
        $location_id =$this->input->post('location_id');
		$condition=array(
			"id"=>$userid
		);
        $projects=$this->tasks->getSearchProjects('users',$userid,$location_id);
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {

            $project->project_location=$project->location_name;
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
            $getlastupdatedtime=$this->tasks->lastupdatetime($project_name,$userid);
            
            $verifiercount = check_verifier_count($project->id,$userid);
            $project->verifier_cnt=$verifiercount;

            if(!empty($getlastupdatedtime))
            {
                $project->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($getlastupdatedtime[0]->updatedat)));
            }
            else
            {
                $project->updatedat='';
            }
            if(!empty($getprojectdetails))
            {
                $project->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                if($getprojectdetails[0]->VerifiedQuantity !='')
                $project->VerifiedQuantity=(int)$getprojectdetails[0]->VerifiedQuantity;
                else
                $project->VerifiedQuantity=0;
            }
            else
            {   
                $project->TotalQuantity=0;
                $project->VerifiedQuantity=0;
            }
            $project->assigned_by=get_UserName($project->assigned_by);
        }
		if(!empty($projects) && count($projects) > 0)
		{
			header('Content-Type: application/json');
			echo json_encode(array("success"=>200,"message"=>"Projects fetched successfully.","data"=>$projects));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No project assigned"));
			exit;
		}
    }
    public function scanitem()
    {
        $userid=$this->input->post('user_id');
        $companyid=$this->input->post('company_id');
        $projectid=$this->input->post('project_id');
        $projectname=$this->input->post('project_name');
        $scancode=$this->input->post('scan_code');
		$condition=array(
			"id"=>$userid
        );
        $projectdetail=$this->tasks->get_data('company_projects',array('id'=>$projectid));
        
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $scantask=$this->tasks->scanitem($userid,$companyid,$projectname,$projectid,$scancode);

        foreach($scantask as $st)
        {
            if($st->verified_by !=''){
             $verifiername= $this->tasks->get_verifire_namesingle($st->verified_by);
            }else{
                $verifiername='';
            }
            $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
            $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));
            if($st->verified_datetime)
            {
                $st->verified_by_username= $verifiername;
                $st->verified_by_name= $verifiername;
                $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
            }
            
           // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
        }
		if(!empty($scantask) && count($scantask) > 0)
		{
            $tag='CD';
            
            $projectdetail[0]->project_type=='TG'? $tag='Y':($projectdetail[0]->project_type=='NT'?$tag='N':($projectdetail[0]->project_type=='UN'?$tag='NA':$tag='CD'));
            if($tag!='CD')
            {
                if(!empty($projectdetail) && in_array($scantask[0]->item_category,json_decode($projectdetail[0]->item_category)) && $scantask[0]->tag_status_y_n_na==$tag)
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$scantask));
                    exit;
                }
                else
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>401,"message"=>"Permission to scan this category/tag item is not granted."));
                    exit;
                }

            }
            else
            {
                if(!empty($projectdetail) && in_array($scantask[0]->item_category,json_decode($projectdetail[0]->item_category)))
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$scantask));
                    exit;
                }
                else
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>401,"message"=>"Permission to scan this category item is not granted."));
                    exit;
                }
            }
            
            
			
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Item not available"));
			exit;
		}
    }
    public function manualscanitem()
    {


        $projectid=$this->input->post('project_id');
        $userid=$this->input->post('user_id');
        $verification_status=$this->input->post('verification_status');
        $tag_status_y_n_na =$this->input->post('tag_status_y_n_na');
        $item_category  =$this->input->post('item_category');
        $item_sub_category =$this->input->post('item_sub_category');
        $projectname=$this->input->post('project_name');
        $search_text =$this->input->post('search_text');
        $search_fields = $this->input->post('search_fields');
        $order_by = $this->input->post('order_by');
        $cond=array();
        
        $where=' Where (';
        $i=1;
        foreach($search_fields as $sf)
        {
            if($i==1)
            $where.=str_replace('"','',$sf).' LIKE "%'.$search_text.'%"';
            else
            $where.=' OR '.str_replace('"','',$sf).' LIKE "%'.$search_text.'%"';
            
            if(count($search_fields)==$i)
            {
                $where.=')';
            }

            $i++;
        }

        
        if($verification_status != 'All')
        {
            $where.=' AND verification_status="'.$verification_status.'"';    
        }
        if($tag_status_y_n_na != 'All')
        {
            $where.=' AND tag_status_y_n_na="'.$tag_status_y_n_na.'"';    
        }
        if($item_category != 'All')
        {
            $where.=' AND item_category="'.$item_category.'"';    
        }
        if($item_sub_category !='' && $item_sub_category !='All')
        {
            $where.=' AND item_sub_category="'.$item_sub_category.'"';    
        }

        $where .= ' ORDER BY id '.$order_by;
        
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $projectdetail=$this->tasks->get_data('company_projects',array('id'=>$projectid));
        
        $select="SELECT * FROM ".$projectname;
        $scantask=$this->db->query($select.$where)->result();
        $result_count = count($scantask);
        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();

       
        
		if(!empty($scantask) && count($scantask) > 0)
		{
            foreach($scantask as $st)
            {

                if($st->verified_by !=''){
                    $verifiername= $this->tasks->get_verifire_namesingle($st->verified_by);
                   }else{
                       $verifiername='';
                   }

                $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
                $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));
                if($st->verified_datetime)
                {
                    $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
                    $st->verified_by_username= $verifiername;
                    $st->verified_by_name= $verifiername;
    
                }
                
               // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
            } 
            if(!empty($projectdetail) && in_array($scantask[0]->item_category,json_decode($projectdetail[0]->item_category)))
            {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","count"=>$result_count,"data"=>$scantask));
                exit;
            }
            else
            {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>401,"message"=>"Permission to scan this category item is not granted."));
                exit;
            }
			
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Item not available"));
			exit;
		}
    }




    public function saveverified()
    {
        $itemid=$this->input->post('item_id');
        $projectname=$this->input->post('project_name');
        $scanned=json_decode($this->input->post('scanned_data'));
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $condition=array(
            "id"=>$itemid
        );

        $verification_remarks = '';
        $qty_ok = 0;
        $qty_damaged = 0;
        $qty_scrapped = 0;
        $qty_not_in_use = 0;
        $qty_missing = 0;
        $qty_shifted = 0;

        $getquantity=$this->tasks->get_data($projectname,$condition);
        
        if($scanned->item_scrap_condition =='qty_ok')
        {
            $qty_ok = (int)$getquantity[0]->qty_ok + (int)$scanned->quantity_verified;
            $scanned->qty_ok = $qty_ok;
             
        }
        else if($scanned->item_scrap_condition =='qty_damaged')
        {
            $qty_damaged = (int)$getquantity[0]->qty_damaged + (int)$scanned->quantity_verified;
            $scanned->qty_damaged = $qty_damaged;
        }
        else if($scanned->item_scrap_condition =='qty_scrapped')
        {
            $qty_scrapped = (int)$getquantity[0]->qty_scrapped + (int)$scanned->quantity_verified;
            $scanned->qty_scrapped = $qty_scrapped;
        }
        else if($scanned->item_scrap_condition =='qty_not_in_use')
        {
            $qty_not_in_use = (int)$getquantity[0]->qty_not_in_use + (int)$scanned->quantity_verified;
            $scanned->qty_not_in_use = $qty_not_in_use;
        }
        else if($scanned->item_scrap_condition =='qty_missing')
        {
            $qty_missing = (int)$getquantity[0]->qty_missing + (int)$scanned->quantity_verified;
            $scanned->qty_missing = $qty_missing;
        }
        else if($scanned->item_scrap_condition =='qty_shifted')
        {
            $qty_shifted = (int)$getquantity[0]->qty_shifted + (int)$scanned->quantity_verified;
            $scanned->qty_shifted = $qty_shifted;
        }
        
        if($scanned->verification_remarks!='')
        {
            $quantity_verified = (int)$getquantity[0]->quantity_verified + (int)$scanned->quantity_verified;
            $scanned->quantity_verified = $quantity_verified;

            $verification_status = $scanned->quantity_as_per_invoice <= $scanned->quantity_verified ? "Verified":"Not-Verified";
            $scanned->verification_status = $verification_status;

            $verification_remarks = $getquantity[0]->verification_remarks != '' ? $getquantity[0]->verification_remarks.' || '.$scanned->verification_remarks:$scanned->verification_remarks;
            $scanned->verification_remarks= $verification_remarks;

            $verified_datetime = date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            $scanned->verified_datetime = $verified_datetime;

            $updatedat = date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            $scanned->updatedat = $updatedat;
        }
        else{
            
            $quantity_verified = (int)$getquantity[0]->quantity_verified + (int)$scanned->quantity_verified;
            $scanned->quantity_verified = $quantity_verified;
            
            $verification_status = $scanned->quantity_as_per_invoice <= $scanned->quantity_verified ? "Verified":"Not-Verified";
            $scanned->verification_status = $verification_status;
            
            $verified_datetime = date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            $scanned->verified_datetime = $verified_datetime;
            
            $updatedat = date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            $scanned->updatedat = $updatedat;
        }
        $scanned->instance_count = (int)$getquantity[0]->instance_count + 1;
        $mode_of_verification = $scanned->mode_of_verification;
        $scanned->mode_of_verification= $mode_of_verification;
        $new_array[0] = $this->stdToArray($scanned);
        unset($new_array[0]['item_scrap_condition']);
        $verify=$this->tasks->update_data($projectname,$new_array[0],$condition);
        // $verify = 1;

        $project_id=$this->input->post('project_id');
        $getprojectdetails_condition = array(
            'id' => $project_id
        );
        $getprojectdetails = $this->tasks->get_data('company_projects',$getprojectdetails_condition);
            

        $company_id = $getprojectdetails[0]->company_id;
        // $mode_of_verification = 'Scan';
        $new_location_verified = 0;
        $location_id = $getprojectdetails[0]->project_location;
        $entity_code =  $getprojectdetails[0]->entity_code;
        $project_id = $getprojectdetails[0]->id;
        $project_name = $getprojectdetails[0]->project_name;
        $original_table_name = $getprojectdetails[0]->original_table_name;
        $verified_by = 0;
        $verified_by_username = 'ABCD';

        $verifiedproducts_array = array(
            'company_id' => $company_id,
            'location_id' => $location_id,
            'entity_code' => $entity_code,
            'project_id' => $project_id,
            'project_name' => $project_name,
            'original_table_name' => $original_table_name,
            'item_id' => $getquantity[0]->id,
            'item_category' => $getquantity[0]->item_category,
            'item_unique_code' => $getquantity[0]->item_unique_code,
            'item_sub_code' => $getquantity[0]->item_sub_code,
            'item_description' => $getquantity[0]->item_description,
            'quantity_as_per_invoice' => $getquantity[0]->quantity_as_per_invoice,
            'verification_status' => $verification_status,
            'quantity_verified' => $quantity_verified,
            'new_location_verified' => $new_location_verified,
            'verified_by' => $verified_by,
            'verified_by_username' => $verified_by_username,
            'verified_datetime' => $verified_datetime,
            'verification_remarks' => $verification_remarks,
            'qty_ok' => $qty_ok,
            'qty_damaged' => $qty_damaged,
            'qty_scrapped' => $qty_scrapped,
            'qty_not_in_use' => $qty_not_in_use,
            'qty_missing' => $qty_missing,
            'qty_shifted' => $qty_shifted,
            'mode_of_verification' => $mode_of_verification,
            'created_at' => date('Y-m-d H:s:i'),
        );
        $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts',$verifiedproducts_array);
        
        
		if($verify)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Item verified successfully."));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Item not verified"));
			exit;
		}
    }
    function stdToArray($obj)
    {
        $reaged = (array)$obj;
        foreach($reaged as $key => &$field)
        {
          if(is_object($field))$field = stdToArray($field);
        }
        return $reaged;
    }
    public function projectstart()
    {
        $userid=$this->input->post('user_id');
        $projectid=$this->input->post('project_id');
        $companyid=$this->input->post('company_id');
        $data=array(
            "begin_datetime"=>date('Y-m-d H:s:i'),
        );
        $condition=array(
            "id"=>$projectid,
            "company_id"=>$companyid,
            "project_verifier"=>$userid
        );
        $finish=$this->tasks->update_data('company_projects',$data,$condition);
        
		if($finish)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Project started successfully."));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Project is not started"));
			exit;
		}
    }
    public function projectfinish()
    {
        $userid=$this->input->post('user_id');
        $projectid=$this->input->post('project_id');
        $companyid=$this->input->post('company_id');
        $data=array(
            "verification_closed_by"=>$userid,
            "finish_datetime"=>date('Y-m-d H:s:i'),
            "status"=>3
        );
        $condition=array(
            "id"=>$projectid,
            "company_id"=>$companyid,
        );
        $finish=$this->tasks->update_data('company_projects',$data,$condition);
        
		if($finish)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Verification finished successfully."));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Verification is not finished"));
			exit;
		}
    }
    public function finalizeverifiedproject()
    {
        $userid=$this->input->post('project_finished_by');
        $projectid=$this->input->post('project_id');
        $remarks=$this->input->post('remarks');
        $status=$this->input->post('status');
        if($status==1)
        {
            $data=array(
                "project_finished_by"=>$userid,
                "finish_datetime"=>date('Y-m-d H:s:i'),
                "status"=>$status,
                "end_remark"=>$remarks==''?NULL:$remarks
            );
        }
        else
        {
            $data=array(
                "project_finished_by"=>$userid,
                "finish_datetime"=>date('Y-m-d H:s:i'),
                "cancelled_date"=>date('Y-m-d'),
                "status"=>$status,
                "cancel_reason"=>$remarks==''?NULL:$remarks
            );
        }
        
        $condition=array(
            "id"=>$projectid,

        );
        $finish=$this->tasks->update_data('company_projects',$data,$condition);
        
		if($finish)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Verification finished successfully."));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Verification is not finished"));
			exit;
		}
    }

    public function savenote()
    {
        $userid=$this->input->post('user_id');
        $itemid=$this->input->post('item_id');
        $projectname=$this->input->post('project_name');
        $itemnote=$this->input->post('item_note');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $data=array("item_note"=>$itemnote);
        $condition=array("id"=>$itemid);
        $updatenote=$this->tasks->update_data($projectname,$data,$condition);
        if($updatenote)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Note updated successfully."));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Note not updated"));
			exit;
		}
    }
    public function getcategories()
    {
        $projectname=$this->input->post('project_name');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $getcategory=$this->tasks->getdistinct_data($projectname,"item_category");
        $getsubcategory=$this->tasks->getdistinct_data($projectname,"item_sub_category");
        if($getcategory && $getsubcategory)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"categories"=>$getcategory,"subcategories"=>$getsubcategory));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Something went wrong"));
			exit;
		}

    }
    public function getsubcategories()
    {
        $projectname=$this->input->post('project_name');
        $item_category=$this->input->post('item_category');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $getsubcategory=$this->tasks->getdistinctwithcondition($projectname,"item_sub_category",array("item_category"=>$item_category));
        if($getsubcategory)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"subcategories"=>$getsubcategory));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Something went wrong"));
			exit;
		}
    }

    //gaurav API Work //
   
/*  
public function get_company(){
    $userid=$this->input->post('user_id');
    // $project=$this->tasks->get_project_companies($userid);
    $user_company=$this->tasks->get_all_company_user_role($userid);
    // print_r($user_company);
    $data_company=array();
    $i=0;
    foreach($user_company as $row_project){
 if($row_project['company_id']!='0'){

        $company_row=$this->tasks->get_company_row($row_project['company_id']);
        $location_cnt=$this->tasks->get_location_count_user($userid,$row_project['company_id']);
        $project_cnt=$this->tasks->get_project_count_user($userid,$row_project['company_id']);

        $data_company[$i]['company_name']= $company_row->company_name;
        $data_company[$i]['company_id']= $company_row->id;
        $data_company[$i]['company_short_code']= $company_row->short_code;
        $data_company[$i]['number_of_location']= $location_cnt;
        $data_company[$i]['number_of_project']= $project_cnt;
        $data_company[$i]['project_close_overdue']= $this->tasks->get_project_close_by_company($company_row->id);

        }
$i++;
    }
    $number_of_company = count($user_company);

    if(!empty($user_company))
		{
			header('Content-Type: application/json');
            $data=$data_company;
			echo json_encode(array("success"=>200,"message"=>"Company fetched successfully.","data"=>$data));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No Company assigned"));
			exit;
		}
} */

public function get_company(){
    $userid=$this->input->post('user_id');
    // $project=$this->tasks->get_project_companies($userid);
    $user_company=$this->tasks->get_all_company_user_role($userid);
    // print_r($user_company);
    $data_company=array();
    $i=0;
    foreach($user_company as $row_project){
        if($row_project['company_id']!='0'){
            $company_row=$this->tasks->get_company_row($row_project['company_id']);
            $location_cnt=$this->tasks->get_location_count_user($userid,$row_project['company_id']);
            $project_cnt=$this->tasks->get_project_count_user($userid,$row_project['company_id']);
            
            $data_company[$i]['company_name']= $company_row->company_name;
            $data_company[$i]['company_id']= $company_row->id;
            $data_company[$i]['company_short_code']= $company_row->short_code;
            $data_company[$i]['number_of_location']= $location_cnt;
            $data_company[$i]['number_of_project']= $project_cnt;
            $data_company[$i]['project_close_overdue']= $this->tasks->get_project_close_by_company($company_row->id);
        }
    $i++;
    }
    $number_of_company = count($user_company);

    if(!empty($user_company))
		{
			header('Content-Type: application/json');
            $data=$data_company;
			echo json_encode(array("success"=>200,"message"=>"Company fetched successfully.","data"=>$data));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No Company assigned"));
			exit;
		}
}


public function get_company_location(){
// error_reporting(0);
    $userid=$this->input->post('user_id');
    $company_id=$this->input->post('company_id');
    $locations=$this->tasks->get_all_location_user_role($userid,$company_id);
    $data_location=array();
    $i=0;

    foreach($locations as $row_project){
        if($row_project['location_id'] !='0'){
        $location_row=$this->tasks->get_location_row($row_project['location_id']);
        $project_cnt=$this->tasks->get_project_count_location($userid,$row_project['company_id'],$row_project['location_id']);

        $data_location[$i]['location_name']= $location_row->location_name;
        $data_location[$i]['location_id']= $location_row->id;
        $data_location[$i]['location_shortcode']= $location_row->location_shortcode;
        $data_location[$i]['number_of_project']= $project_cnt;


        $this->db->select("*");   
        $this->db->from("company_projects");   
        // $this->db->where("entity_code",$entity_code);
        $this->db->where("project_location",$location_row->id);
        $this->db->where("status !=",'2');
        $query= $this->db->get();   
        $projects = $query->row();
       $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
       $new_pattern = array("_", "_", "");
       if(empty($projects)){
        $data_location[$i]['project_percent']='0';
       }
       if(!empty($projects)){
            $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($projects->project_name)));
            $getprojectdetails=$this->tasks->projectdetail($project_name);
          
            if(!empty($getprojectdetails))
            {
                 $projects->TotalQuantity= ((int)$getprojectdetails[0]->TotalQuantity);
                if($getprojectdetails[0]->VerifiedQuantity !='')
                 $projects->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
                else
                $projects->VerifiedQuantity=0;
            }
            else
            {   
                $projects->TotalQuantity=0;
                $projects->VerifiedQuantity=0;
            }
    
    
            
           
            if($projects->VerifiedQuantity!=0){
                 $project_percent= round(($projects->VerifiedQuantity/$projects->TotalQuantity)*100,2);
                }else{ 
                    $project_percent= "0";
                }
             $data_location[$i]['project_percent']= $project_percent;
             $data_location[$i]['project_close_overdue']= $this->tasks->get_project_close($projects->id);
        }

    }
    $i++; }
    $number_of_location = count($locations);
    if(!empty($locations) && count($locations) > 0)
		{
			header('Content-Type: application/json');
            $data=$data_location;
            
			echo json_encode(array("success"=>200,"message"=>"Location fetched successfully.","data"=>$data));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No Location assigned"));
			exit;
		}


}



public function get_company_location_project(){

    $userid=$this->input->post('user_id');
    $company_id=$this->input->post('company_id');
    $location_id=$this->input->post('location_id');
    $project=$this->tasks->get_project_company_location($userid,$company_id,$location_id);
    // print_r($project);
   
    if(!empty($project) && count($project) > 0)
		{
			header('Content-Type: application/json');
            
			echo json_encode(array("success"=>200,"message"=>"Project fetched successfully.","data"=>$project));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No Project assigned for this location"));
			exit;
		}


}

public function get_graph_data(){
    $projects_id = $this->input->post('project_id');
    $project_row = $this->tasks->get_company_project($projects_id);

     $projectname = $project_row->project_name;
    $project_type =  $project_row->project_type;

    $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
    $new_pattern = array("_", "_", "");
  
     $projectname1=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
     $listing= getTagUntag($projectname);


    $project1 = $this->tasks->projectdetailnew($projectname1);
    $tagged=0;
    $untagged=0;
    $unverified=0;
 
    foreach($listing['projectverifiers'] as $row){
        $usertaged=$row->usertagged;
        $untagged=$row->useruntagged;
    }
    $useruntagedlisting=$listing['ntotal'];
    $usertagedlisting=$listing['ytotal'];
    $total_unverified = ($project1[0]['TotalQuantity'] - $project1[0]['VerifiedQuantity']);
    $project['ProjectID']=$project_row->project_id;
    $project['ProjectName']=$project_row->project_name;
    $project['ProjectStatus']=$project_row->status;
    $project['TotalQuantity']=$project1[0]['TotalQuantity'];
    $project['VerifiedQuantity']=$project1[0]['VerifiedQuantity'];
    $project['unverifiedQuantity']=$total_unverified;
    $project['untagged']=$untagged;
    $project['untagged_li_total']=$useruntagedlisting;

    $project['tagged']=$usertaged;
    $project['tagged_li_total']=$usertagedlisting;

    // print_r($project);
   
    if(!empty($project) && count($project) > 0)
		{
			header('Content-Type: application/json');
            
			echo json_encode(array("success"=>200,"message"=>"Project fetched successfully.","data"=>$project));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No Project assigned for this location"));
			exit;
		}
}

public function get_project_contact_info(){
$project_id=$this->input->post('project_id');
$data_contact= $this->tasks->get_contact_detail($project_id);

if(!empty($data_contact) && count($data_contact) > 0)
		{
			header('Content-Type: application/json');
            
			echo json_encode(array("success"=>200,"message"=>"Contact fetched successfully.","data"=>$data_contact));
			exit;
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"No Contact this project"));
			exit;
		}
}


public function save_project_contact_info(){
    $project_id=$this->input->post('project_id');
    $data_contact= array(
        "project_id"=>$this->input->post('project_id'),
        "name"=>$this->input->post('name'),
        "email"=>$this->input->post('email'),
        "phone"=>$this->input->post('phone'),
        "designation"=>$this->input->post('designation'),
        "created_by"=>$this->input->post('user_id'),
        "created_at"=>date("Y-m-d H:i:s")
     );
    $save= $this->tasks->save_contact_detail($data_contact);
    
    if($save == '1')
            {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>200,"message"=>"Contact Saved successfully."));
                exit;
            } 
            else {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>401,"message"=>"Something Went Wrong"));
                exit;
            }
    }
    
public function get_graph_datastatus(){
 $entity_code=$this->input->post('entity_code');
 $this->db->select("*");   
 $this->db->from("company_projects");   
 $this->db->where("entity_code",$entity_code);
 $query= $this->db->get();   
 $project = $query->result();
//  echo $this->db->last_query();


 $open_project=0;
 $closed_project=0;
 $cancel_project=0;
 foreach($project as $row){
    if($row->status==0 || $row->status==3){
        $open_project++;
    } 
    if($row->status==1){
        $closed_project++;
     }

     if($row->status==2){
        $cancel_project++;
     }
 }
 $data=array();
 $data['open_project']=$open_project;
 $data['closed_project']=$closed_project;
 $data['cancel_project']=$cancel_project;

header('Content-Type: application/json');
echo json_encode(array("success"=>200,"message"=>"Data fetched successfully.","data"=>$data));
exit;

}



public function project_completion_by_location(){
    $entity_code=$this->input->post('entity_code');
    $project_location=$this->input->post('project_location');
    $this->db->select("*");   
    $this->db->from("company_projects");   
    $this->db->where("entity_code",$entity_code);
    $this->db->where("project_location",$project_location);
    $query= $this->db->get();   
    $projects = $query->result();
   //  echo $this->db->last_query();
   

    foreach($projects as $project){
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
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


        
       
        if($project->VerifiedQuantity!=0){ 
            $project_percent= round(($project->VerifiedQuantity/$project->TotalQuantity)*100,2);
        }else{ 
            $project_percent= "0";
        }

    }
    $data=array();
    $data['project_completion_percent']=$project_percent;

   header('Content-Type: application/json');
   echo json_encode(array("success"=>200,"message"=>"Data fetched successfully.","data"=>$data));
   exit;
   
   }

   public function additional_data(){
    $asset_category=$this->input->post('asset_category');
    $asset_classification=$this->input->post('asset_classification');
    $description_of_asset=$this->input->post('description_of_asset');
    $qty_verified=$this->input->post('qty_verified');
    $current_location=$this->input->post('current_location');
    $condition_of_assets=$this->input->post('condition_of_assets');
    $make=$this->input->post('make');
    $model=$this->input->post('model');
    $serial_no=$this->input->post('serial_no');
    $temp_verifiction_id_ref=$this->input->post('temp_verifiction_id_ref');
    $expected_unit_cost=$this->input->post('expected_unit_cost');
    $any_other_details_unit_cost=$this->input->post('any_other_details_unit_cost');
    $verified_name=$this->input->post('verified_name');
    $project_id=$this->input->post('project_id');
    $data=array(
        "asset_category"=>$asset_category,
        "asset_classification"=>$asset_classification,
        "description_of_asset"=>$description_of_asset,
        "qty_verified"=>$qty_verified,
        "current_location"=>$current_location,
        "condition_of_assets"=>$condition_of_assets,
        "make"=>$make,
        "model"=>$model,
        "serial_no"=>$serial_no,
        "temp_verifiction_id_ref"=>$temp_verifiction_id_ref,
        "expected_unit_cost"=>$expected_unit_cost,
        "any_other_details_unit_cost"=>$any_other_details_unit_cost,
        "verified_name"=>$verified_name,
        "project_id"=>$project_id,
        "created_at"=>date("Y-m-d H:i:s")
    );
    $insert=$this->db->insert('additional_data',$data);
    $datanew=array();
    if($insert){
        header('Content-Type: application/json');
        echo json_encode(array("success"=>200,"message"=>"Data insert successfully.","status"=>'0'));
    }else{
        header('Content-Type: application/json');
        echo json_encode(array("success"=>200,"message"=>"something went wrong.","status"=>'0'));
    }
   exit;
   }



   
public function get_project_additionaldata(){
    $project_id=$this->input->post('project_id');
    $data_additional= $this->tasks->get_addintional_detail($project_id);
    
    if(!empty($data_additional) && count($data_additional) > 0)
            {
                header('Content-Type: application/json');
                
                echo json_encode(array("success"=>200,"message"=>"Contact fetched successfully.","data"=>$data_additional));
                exit;
            } 
            else {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>401,"message"=>"No additional data in this project"));
                exit;
            }
    }

    public function update_additional_data(){
        $asset_category=$this->input->post('asset_category');
        $asset_classification=$this->input->post('asset_classification');
        $description_of_asset=$this->input->post('description_of_asset');
        $qty_verified=$this->input->post('qty_verified');
        $current_location=$this->input->post('current_location');
        $condition_of_assets=$this->input->post('condition_of_assets');
        $make=$this->input->post('make');
        $model=$this->input->post('model');
        $serial_no=$this->input->post('serial_no');
        $temp_verifiction_id_ref=$this->input->post('temp_verifiction_id_ref');
        $expected_unit_cost=$this->input->post('expected_unit_cost');
        $any_other_details_unit_cost=$this->input->post('any_other_details_unit_cost');
        $verified_name=$this->input->post('verified_name');
        $project_id=$this->input->post('project_id');
        $id=$this->input->post('id');
        $data=array(
            "asset_category"=>$asset_category,
            "asset_classification"=>$asset_classification,
            "description_of_asset"=>$description_of_asset,
            "qty_verified"=>$qty_verified,
            "current_location"=>$current_location,
            "condition_of_assets"=>$condition_of_assets,
            "make"=>$make,
            "model"=>$model,
            "serial_no"=>$serial_no,
            "temp_verifiction_id_ref"=>$temp_verifiction_id_ref,
            "expected_unit_cost"=>$expected_unit_cost,
            "any_other_details_unit_cost"=>$any_other_details_unit_cost,
            "verified_name"=>$verified_name,
            "project_id"=>$project_id,
        );
        $insert=$this->db->where('id',$id);
        $insert=$this->db->update('additional_data',$data);
        $datanew=array();
        if($insert){
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Data Updated successfully.","status"=>'0'));
        }else{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"something went wrong.","status"=>'0'));
        }
       exit;
       }
    
       public function update_project_contact_info(){
        $id=$this->input->post('id');
        $data_contact= array(
            "project_id"=>$this->input->post('project_id'),
            "name"=>$this->input->post('name'),
            "email"=>$this->input->post('email'),
            "phone"=>$this->input->post('phone'),
            "designation"=>$this->input->post('designation'),
            "created_by"=>$this->input->post('user_id'),
            "created_at"=>date("Y-m-d H:i:s")
         );
        $this->db->where('id',$id);
        $save=$this->db->update('contact_detail',$data_contact);
        if($save == '1')
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>200,"message"=>"Contact Updated successfully."));
                    exit;
                } 
                else {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>401,"message"=>"Something Went Wrong"));
                    exit;
                }
        }



 public function itemnewall()
    {
        $projectid=$this->input->post('project_id');
        $userid=$this->input->post('user_id');
        $verification_status=$this->input->post('verification_status');
        $tag_status_y_n_na =$this->input->post('tag_status_y_n_na');
        $item_category  =$this->input->post('item_category');
        $item_sub_category =$this->input->post('item_sub_category');
        $projectname=$this->input->post('project_name');
        // $search_text =$this->input->post('search_text');
        // $search_fields =$this->input->post('search_fields');
        $cond=array();
        $where=' Where 1 ';
        // $where=' Where (';
        $i=1;
        // foreach($search_fields as $sf)
        // {
        //     if($i==1)
        //     $where.=str_replace('"','',$sf).' LIKE "%'.$search_text.'%"';
        //     else
        //     $where.=' OR '.str_replace('"','',$sf).' LIKE "%'.$search_text.'%"';
        //     if(count($search_fields)==$i)
        //     {
        //         $where.=')';
        //     }
        //     $i++;
        // }
        if($verification_status !='All')
        {
            $where.=' AND verification_status="'.$verification_status.'"';    
        }
        if($tag_status_y_n_na !='All')
        {
            $where.=' AND tag_status_y_n_na="'.$tag_status_y_n_na.'"';    
        }
        if($item_category !='All')
        {
            $where.=' AND item_category="'.$item_category.'"';    
        }
        if($item_sub_category !='' && $item_sub_category !='All')
        {
            $where.=' AND item_sub_category="'.$item_sub_category.'"';    
        }
        
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $projectdetail=$this->tasks->get_data('company_projects',array('id'=>$projectid));
        
        $select="SELECT * FROM ".$projectname;
        $scantask=$this->db->query($select.$where)->result();
		if(!empty($scantask) && count($scantask) > 0)
		{
            foreach($scantask as $st)
            {
                $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
                $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));
                if($st->verified_datetime)
                {
                    $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
                }
                
               // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
            } 
            if(!empty($projectdetail) && in_array($scantask[0]->item_category,json_decode($projectdetail[0]->item_category)))
            {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$scantask));
                exit;
            }
            else
            {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>401,"message"=>"Permission to scan this category item is not granted."));
                exit;
            }
			
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Item not available"));
			exit;
		}
    }


    
	public function getprojectsreports()
	{
		$userid=$this->input->post('user_id');
        $projects =$this->tasks->getProjectsreport($userid);


        if(!empty($projects))
        {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$projects));
            exit;
        }
        else
        {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"No Project Found."));
            exit;
        }
        
    }





    public function generateReport(){
        error_reporting(0);
		$type=$this->input->post('optradio');
		$projectSelect=$this->input->post('projectSelect');
		$reporttype=$this->input->post('reporttype');
		$projectstatus=$this->input->post('projectstatus');
		$verificationstatus=$this->input->post('verificationstatus');
		$reportOutput=$this->input->post('reportOutput');
		$reportHeaders=$this->input->post('reportHeaders');
		$original_table_name=$this->input->post('original_table_name');
		$company_id=$this->input->post('company_id');
		$location_id=$this->input->post('location_id');
		


		if($type=='project')
		{
			$condition=array(
				"id"=>$projectSelect,
				"status"=>$projectstatus,
				'company_id'=>$company_id,
				'project_location'=>$location_id,
				// 'entity_code'=>$this->admin_registered_entity_code
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
				
                header('Content-Type: application/json');
                echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$getreport));
                exit;
    
			}
			else
			{
                header('Content-Type: application/json');
                echo json_encode(array("success"=>200,"message"=>"No data found."));
                exit;	
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
                header('Content-Type: application/json');
                echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$getreport));
                exit;
    
			}
			else
			{
                header('Content-Type: application/json');
                echo json_encode(array("success"=>200,"message"=>"No data found."));
                exit;	
           }
		}
		
	}
      
    public function get_project_header(){
        $entity_code=$this->input->post('entity_code');
        $lastProj=$this->db->query('Select * from company_projects where  entity_code="'.$entity_code.'"   order by id desc limit 1')->result();
        $headerCondition=array('table_name'=>$lastProj[0]->original_table_name);
        $project_headers=$this->tasks->get_data('project_headers',$headerCondition);
        if(count($project_headers)>0)
        {

        header('Content-Type: application/json');
        echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$project_headers));
        exit;
        }else
        {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"No data found."));
            exit;	
       }
    }

    public function get_overdue_projects(){
        $userid=$this->input->post('user_id');
        $user_company=$this->tasks->get_all_company_user_role($userid);
        $data_company=array();
        $i=0;
        foreach($user_company as $row_project){
            if($row_project['company_id']!='0'){
                $company_row=$this->tasks->get_company_row($row_project['company_id']);
                $overdue_status = $this->tasks->get_project_close_by_company($company_row->id);
                if($overdue_status['is_project_close'] == '1' && $overdue_status['is_project_overdue'] == '1'){
                    $data_company[$i]['company_name']= $company_row->company_name;
                    $data_company[$i]['company_id']= $company_row->id;
                    $data_company[$i]['company_short_code']= $company_row->short_code;
                    // $data_company[$i]['project_name']= $company_row->project_name;
                    // $data_company[$i]['project_id']= $company_row->project_id;
                    // $data_company[$i]['due_date']= $company_row->due_date;
                    // $data_company[$i]['finish_datetime']= date("Y-m-d", strtotime($company_row->finish_datetime));
                }
            }
        $i++;
        }
        
        if(!empty($data_company))
            {
                header('Content-Type: application/json');
                $data=$data_company;
                echo json_encode(array("success"=>200,"message"=>"Company fetched successfully.","data"=>$data));
                exit;
            } 
            else {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>401,"message"=>"No Record Found"));
                exit;
            }
    }


    public function get_notifications(){
        $userid=$this->input->post('user_id');
        $user_notications=$this->tasks->get_notification_by_user_role($userid);
        if(!empty($user_notications))
        {
            header('Content-Type: application/json');
            $data=$user_notications;
            echo json_encode(array("success"=>200,"message"=>"Company fetched successfully.","data"=>$data));
            exit;
        } 
        else {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>401,"message"=>"No Company assigned"));
            exit;
        }
    }

    public function notification_read(){
        $user_id=$this->input->post('user_id');
        $notification_id=$this->input->post('notification_id');
       
        $data=array(
            "is_read"=>1
        );
        $condition=array(
            "user_id"=>$user_id,
            "notification_id"=>$notification_id,
        );
        $update_notification=$this->tasks->update_data('notification_user',$data,$condition);
        
		if($update_notification)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Notification List"));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Record Empty"));
			exit;
		}
       
    }


    public function search(){
        $sort_by_column = $this->input->post('sort_by');
        
        if($sort_by_column == 'ByDept'){
            $column_name = 'user_department';
        }
        if($sort_by_column == 'ByAssetCategory'){
            $column_name = 'item_sub_category';
        }
        if($sort_by_column == 'ByAssetSubCategory'){
            $column_name = 'item_category';
        }
        if($sort_by_column == 'ByAssetClassification'){
            $column_name = 'item_classification';
        }

        $order_by = $this->input->post('order_by');
        $project_id = $this->input->post('project_id');
        $project_details = $this->tasks->get_company_project($project_id);

        if(!empty($project_details))
        {
            $table_name = $project_details->original_table_name;
            $SearchResult = $this->tasks->get_product_search($column_name,$order_by,$table_name);
            if(!empty($SearchResult))
            {
                header('Content-Type: application/json');
                $data=$SearchResult;
                echo json_encode(array("success"=>200,"message"=>"Details fetched successfully.","data"=>$data));
                exit;
            } 
            else {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>401,"message"=>"No Details Found"));
                exit;
            }
        } 
        else {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>401,"message"=>"No Project Exist"));
            exit;
        }
        
        
    }

    public function get_verifiedprojects_instance(){
        $item_id=$this->input->post('item_id');
        $project_id = $this->input->post('project_id');
        $user_notications=$this->tasks->get_verifiedprojects_instance_by_item($item_id,$project_id);
        if(!empty($user_notications))
        {
            header('Content-Type: application/json');
            $data=$user_notications;
            echo json_encode(array("success"=>200,"message"=>"Get Verified Project Phases.","data"=>$data));
            exit;
        } 
        else {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>401,"message"=>"No Found Verified Project Phases"));
            exit;
        }
    }

    public function EditVerifyoption(){
        $item_id=$this->input->post('item_id');
        $operation_type = $this->input->post('operation_type');
        $tablename = 'test_demo_01';
        $Item_Result = $this->tasks->get_item_details($tablename,$item_id);
        if(!empty($Item_Result))
        {
            header('Content-Type: application/json');
            $data=$Item_Result;
            echo json_encode(array("success"=>200,"message"=>"Details fetched successfully.","data"=>$data));
            exit;
        } 
        else {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>401,"message"=>"No Details Found"));
            exit;
        }
    }


    public function editverified()
    {
        $itemid=$this->input->post('item_id');
        $projectname=$this->input->post('project_name');
        $update_details=json_decode($this->input->post('scanned_data'));
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $condition=array(
            "id"=>$itemid
        );

        $verification_remarks = '';
        $qty_ok = 0;
        $qty_damaged = 0;
        $qty_scrapped = 0;
        $qty_not_in_use = 0;
        $qty_missing = 0;
        $qty_shifted = 0;



        // {"item_scrap_condition":"qty_ok","qty_ok":1,"quantity_as_per_invoice":1,"quantity_verified":1,"verification_status":"","verification_remarks":"","verified_datetime":"","updatedat":"a","mode_of_verification":"Scan","new_location_verified":"ASDASDASDSD"}

   


        $getquantity=$this->tasks->get_data($projectname,$condition);

        if($update_details->item_scrap_condition =='qty_ok')
        {
            $qty_ok = (int)$getquantity[0]->qty_ok + (int)$update_details->quantity_verified;
            $update_details->qty_ok = $qty_ok;
             
        }
        else if($update_details->item_scrap_condition =='qty_damaged')
        {
            $qty_damaged = (int)$getquantity[0]->qty_damaged + (int)$update_details->quantity_verified;
            $update_details->qty_damaged = $qty_damaged;
        }
        else if($update_details->item_scrap_condition =='qty_scrapped')
        {
            $qty_scrapped = (int)$getquantity[0]->qty_scrapped + (int)$update_details->quantity_verified;
            $update_details->qty_scrapped = $qty_scrapped;
        }
        else if($update_details->item_scrap_condition =='qty_not_in_use')
        {
            $qty_not_in_use = (int)$getquantity[0]->qty_not_in_use + (int)$update_details->quantity_verified;
            $update_details->qty_not_in_use = $qty_not_in_use;
        }
        else if($update_details->item_scrap_condition =='qty_missing')
        {
            $qty_missing = (int)$getquantity[0]->qty_missing + (int)$update_details->quantity_verified;
            $update_details->qty_missing = $qty_missing;
        }
        else if($update_details->item_scrap_condition =='qty_shifted')
        {
            $qty_shifted = (int)$getquantity[0]->qty_shifted + (int)$update_details->quantity_verified;
            $update_details->qty_shifted = $qty_shifted;
        }
        
        if($update_details->verification_remarks!='')
        {
            $quantity_verified = (int)$getquantity[0]->quantity_verified + (int)$update_details->quantity_verified;
            $update_details->quantity_verified = $quantity_verified;

            $verification_status = $update_details->quantity_as_per_invoice <= $update_details->quantity_verified ? "Verified":"Not-Verified";
            $update_details->verification_status = $verification_status;

            $verification_remarks = $getquantity[0]->verification_remarks != '' ? $getquantity[0]->verification_remarks.' || '.$update_details->verification_remarks:$update_details->verification_remarks;
            $update_details->verification_remarks= $verification_remarks;

            $verified_datetime = date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            $update_details->verified_datetime = $verified_datetime;

            $updatedat = date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            $update_details->updatedat = $updatedat;
        }
        else{
            
            $quantity_verified = (int)$getquantity[0]->quantity_verified + (int)$update_details->quantity_verified;
            $update_details->quantity_verified = $quantity_verified;
            
            $verification_status = $update_details->quantity_as_per_invoice <= $update_details->quantity_verified ? "Verified":"Not-Verified";
            $update_details->verification_status = $verification_status;
            
            $verified_datetime = date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            $update_details->verified_datetime = $verified_datetime;
            
            $updatedat = date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            $update_details->updatedat = $updatedat;
        }
        // $update_details->instance_count = (int)$getquantity[0]->instance_count + 1;
        $mode_of_verification = $update_details->mode_of_verification;
        $update_details->mode_of_verification= $mode_of_verification;
        $new_array[0] = $this->stdToArray($update_details);
        unset($new_array[0]['item_scrap_condition']);
        $verify=$this->tasks->update_data($projectname,$new_array[0],$condition);
        

        $project_id=$this->input->post('project_id');
        $getprojectdetails_condition = array(
            'id' => $project_id
        );
        $getprojectdetails = $this->tasks->get_data('company_projects',$getprojectdetails_condition);
            

        $company_id = $getprojectdetails[0]->company_id;
        $new_location_verified = 0;
        $location_id = $getprojectdetails[0]->project_location;
        $entity_code =  $getprojectdetails[0]->entity_code;
        $project_id = $getprojectdetails[0]->id;
        $project_name = $getprojectdetails[0]->project_name;
        $original_table_name = $getprojectdetails[0]->original_table_name;
        $verified_by = 0;
        $verified_by_username = 'ABCD';

        $verifiedproducts_array = array(
            'roW_id' => $company_id,
            'edit_opration' => "asdasd",
            'previous_company_id' => $company_id,
            'company_id' => $company_id,
            'previous_location_id' => $location_id,
            'location_id' => $location_id,
            'previous_entity_code' => $entity_code,
            'entity_code' => $entity_code,
            'previous_project_id' => $project_id,
            'project_id' => $project_id,
            'previous_project_name' => $project_name,
            'project_name' => $project_name,
            'previous_original_table_name' => $original_table_name,
            'original_table_name' => $original_table_name,
            'previous_item_id' => $getquantity[0]->id,
            'item_id' => $getquantity[0]->id,
            'previous_item_category' => $getquantity[0]->item_category,
            'item_category' => $getquantity[0]->item_category,
            'previous_item_unique_code' => $getquantity[0]->item_unique_code,
            'item_unique_code' => $getquantity[0]->item_unique_code,
            'previous_item_sub_code' => $getquantity[0]->item_sub_code,
            'item_sub_code' => $getquantity[0]->item_sub_code,
            'previous_item_description' => $getquantity[0]->item_description,
            'item_description' => $getquantity[0]->item_description,
            'previous_quantity_as_per_invoice' => $getquantity[0]->quantity_as_per_invoice,
            'quantity_as_per_invoice' => $getquantity[0]->quantity_as_per_invoice,
            'previous_verification_status' => $verification_status,
            'verification_status' => $verification_status,
            'previous_quantity_verified' => $quantity_verified,
            'quantity_verified' => $quantity_verified,
            'previous_new_location_verified' => $new_location_verified,
            'new_location_verified' => $new_location_verified,
            'previous_verified_by' => $verified_by,
            'verified_by' => $verified_by,
            'previous_verified_by_username' => $verified_by_username,
            'verified_by_username' => $verified_by_username,
            'previous_verified_datetime' => $verified_datetime,
            'verified_datetime' => $verified_datetime,
            'previous_verification_remarks' => $verification_remarks,
            'verification_remarks' => $verification_remarks,
            'previous_qty_ok' => $qty_ok,
            'qty_ok' => $qty_ok,
            'previous_qty_damaged' => $qty_damaged,
            'qty_damaged' => $qty_damaged,
            'previous_qty_scrapped' => $qty_scrapped,
            'qty_scrapped' => $qty_scrapped,
            'previous_qty_not_in_use' => $qty_not_in_use,
            'qty_not_in_use' => $qty_not_in_use,
            'previous_qty_missing' => $qty_missing,
            'qty_missing' => $qty_missing,
            'previous_qty_shifted' => $qty_shifted,
            'qty_shifted' => $qty_shifted,
            'previous_mode_of_verification' => $mode_of_verification,
            'mode_of_verification' => $mode_of_verification,
            'previous_created_at' => date('Y-m-d H:s:i'),
            'created_at' => date('Y-m-d H:s:i'),
        );
        // $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts',$verifiedproducts_array);
        $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts_log',$verifiedproducts_array);
        
        
        
		if($verify)
		{
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Item verified update successfully."));
            exit;

		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Item not verified"));
			exit;
		}
    }

   

    public function getdepartments()
    {
        $verification_status=$this->input->post('verification_status');
        $tag_status_y_n_na=$this->input->post('tag_status_y_n_na');
        $item_category=$this->input->post('item_category');
        $item_sub_category=$this->input->post('item_sub_category');
        $projectname=$this->input->post('project_name');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $select="SELECT user_department FROM ".$projectname;

        $where= '';
        $is_where = 0;
        if($verification_status !='All')
        {
            $where.=' WHERE verification_status="'.$verification_status.'"';    
            $is_where = 1;
        }
        if($tag_status_y_n_na !='All')
        {
            if($is_where == 1){
                $where.=' AND tag_status_y_n_na="'.$tag_status_y_n_na.'"';    
            }else{
                $where.=' WHERE tag_status_y_n_na="'.$tag_status_y_n_na.'"';    
            }
            $is_where = 1;
            
        }
        if($item_category !='All')
        {
            if($is_where == 1){
                $where.=' AND item_category="'.$item_category.'"';    
            }else{
                $where.=' WHERE item_category="'.$item_category.'"';    
            }
            $is_where = 1;
        }
      
        if($item_sub_category !='' && $item_sub_category !='All')
        {
            if($is_where == 1){
                $where.=' AND item_sub_category="'.$item_sub_category.'"';
            }else{
                $where.=' WHERE item_sub_category="'.$item_sub_category.'"';
            }       
        } 
        $where .= ' GROUP BY user_department';

        $scantask=$this->db->query($select.$where)->result();
        if(empty($scantask)){
            $scantask[0]['user_department'] = 'All';
        }
        

        // echo '<pre>scantask ';
        // print_r($scantask);
        // echo '</pre>';
        // exit(); 
        
        $result_count = count($scantask);

        if($scantask)
        {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","count"=>$result_count,"data"=>$scantask));
            exit;
        }
        else
        {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>401,"message"=>"Permission to scan this category item is not granted."));
            exit;
        }
    }


    public function getassets()
    {
        $verification_status=$this->input->post('verification_status');
        $tag_status_y_n_na=$this->input->post('tag_status_y_n_na');
        $item_category=$this->input->post('item_category');
        $item_sub_category=$this->input->post('item_sub_category');
        $user_department=$this->input->post('user_department');
        $projectname=$this->input->post('project_name');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $select="SELECT item_classification FROM ".$projectname;

        $where= '';
        $is_where = 0;
        if($verification_status !='All')
        {
            $where.=' WHERE verification_status="'.$verification_status.'"';    
            $is_where = 1;
        }
        if($tag_status_y_n_na !='All')
        {
            if($is_where == 1){
                $where.=' AND tag_status_y_n_na="'.$tag_status_y_n_na.'"';    
            }else{
                $where.=' WHERE tag_status_y_n_na="'.$tag_status_y_n_na.'"';    
            }
            $is_where = 1;
            
        }
        if($item_category !='All')
        {
            if($is_where == 1){
                $where.=' AND item_category="'.$item_category.'"';    
            }else{
                $where.=' WHERE item_category="'.$item_category.'"';    
            }
            $is_where = 1;
        }
        if($user_department !='All')
        {
            if($is_where == 1){
                $where.=' AND user_department="'.$user_department.'"';    
            }else{
                $where.=' WHERE user_department="'.$user_department.'"';    
            }
            $is_where = 1;
        }
        if($item_sub_category !='' && $item_sub_category !='All')
        {
            if($is_where == 1){
                $where.=' AND item_sub_category="'.$item_sub_category.'"';
            }else{
                $where.=' WHERE item_sub_category="'.$item_sub_category.'"';
            }       
        } 
        $where .= ' GROUP BY item_classification';

        $scantask=$this->db->query($select.$where)->result();
        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();
        $result_count = count($scantask);

        if($scantask)
        {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","count"=>$result_count,"data"=>$scantask));
            exit;
        }
        else
        {
            header('Content-Type: application/json');
            echo json_encode(array("success"=>401,"message"=>"Permission to scan this category item is not granted."));
            exit;
        }

    }


    public function verifybylist()
    {
        $projectid=$this->input->post('project_id');
        $userid=$this->input->post('user_id');
        $verification_status=$this->input->post('verification_status');
        // $tag_status_y_n_na =$this->input->post('tag_status_y_n_na');
        // $item_category  =$this->input->post('item_category');
        // $item_sub_category =$this->input->post('item_sub_category');
        $projectname=$this->input->post('project_name');
        $search_text =$this->input->post('search_text');
        // $search_fields = $this->input->post('search_fields');

        // echo '<pre>search_fields ';
        // print_r($search_fields);
        // echo '</pre>';
        // exit(); 

        $search_fields = array();
        if(!empty($this->input->post('search_fields'))){
            $search_fields = explode(",",$this->input->post('search_fields'));
        }
        
        // echo '<pre>search_fields ';
        // print_r($search_fields);
        // echo '</pre>';
        // exit(); 


        $item_classification=$this->input->post('item_classification');
        $user_department=$this->input->post('user_department');

        $order_by = $this->input->post('order_by');
        $cond=array();
        
        $where= '';
        $is_where = 0;
        if(!empty($search_text)){
            $where=' Where (';
            $i=1;
            foreach($search_fields as $sf)
            {
                if($i==1)
                $where.=str_replace('"','',$sf).' LIKE "%'.$search_text.'%"';
                else
                $where.=' OR '.str_replace('"','',$sf).' LIKE "%'.$search_text.'%"';
                
                if(count($search_fields)==$i)
                {
                    $where.=')';
                }
    
                $i++;
            }
            $is_where = 1;
        }
        

        /*
        if($verification_status !='All')
        {
            $where.=' AND verification_status="'.$verification_status.'"';    
        }
        if($tag_status_y_n_na !='All')
        {
            $where.=' AND tag_status_y_n_na="'.$tag_status_y_n_na.'"';    
        }
        if($item_category !='All')
        {
            $where.=' AND item_category="'.$item_category.'"';    
        }
        if($item_sub_category !='' && $item_sub_category !='All')
        {
            $where.=' AND item_sub_category="'.$item_sub_category.'"';    
        } 
        */

        
        if($verification_status !='All')
        {
            if($is_where == 1){
                $where.=' AND verification_status="'.$verification_status.'"';    
            }else{
                $where.=' WHERE verification_status="'.$verification_status.'"';    
            }
            
            $is_where = 1;
        }

        if($item_classification !='All')
        {
            if((isset($item_classification)) && (!empty($item_classification)))
            {
                if($is_where == 1){
                    $where.=' AND item_classification="'.$item_classification.'"';    
                }else{
                    $where.=' WHERE item_classification="'.$item_classification.'"';    
                }
                $is_where = 1;
            }
        }
      

        
        if($user_department !='All')
        {
           
            if((isset($user_department)) && (!empty($user_department)))
            {
                if($is_where == 1){
                    $where.=' AND user_department="'.$user_department.'"';
                }else{
                    $where.=' WHERE user_department="'.$user_department.'"';
                }
                    
            }
        }



        $where .= ' ORDER BY id '.$order_by;
        
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $projectdetail=$this->tasks->get_data('company_projects',array('id'=>$projectid));
        
        $select="SELECT * FROM ".$projectname;
        $scantask=$this->db->query($select.$where)->result();
        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();
        $result_count = count($scantask);
        
		if(!empty($scantask) && count($scantask) > 0)
		{
            foreach($scantask as $st)
            {

                if($st->verified_by != ''){
                    $verifiername= $this->tasks->get_verifire_namesingle($st->verified_by);
                    // echo '<pre>last_query ';
                    // print_r($this->db->last_query());
                    // echo '</pre>';
                    // exit();
                   }else{
                       $verifiername='';
                   }

                $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
                $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));
                if($st->verified_datetime)
                {
                    $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
                    $st->verified_by_username= $verifiername;
                    $st->verified_by_name= $verifiername;
    
                }
                
               // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
            } 
            if(!empty($projectdetail) && in_array($scantask[0]->item_category,json_decode($projectdetail[0]->item_category)))
            {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","count"=>$result_count,"data"=>$scantask));
                exit;
            }
            else
            {
                header('Content-Type: application/json');
                echo json_encode(array("success"=>401,"message"=>"Permission to scan this category item is not granted."));
                exit;
            }
			
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Item not available"));
			exit;
		}
    }











    public function downloadExceptionFiveConsolidatedReport()       //[NOT IN POSTMAN]
	{
		require 'vendor/autoload.php';
        $projectid = 1;
        
        echo '<pre>';
        print_r("asdasdasd");
        echo '</pre>';
        // exit();
		// $reportData=$this->session->get_userdata('reportData');
		// $type=$reportData['reportData']['type'];
		// $project_status=$reportData['reportData']['project_status'];
		// $verification_status=$reportData['reportData']['verification_status'];
		// $table_name=$reportData['reportData']['table_name'];
		// $reportHeaders=$reportData['reportData']['report_headers'];
        $reportHeaders = 'all';
        $table_name = 'test';
		$headerCondition=array('table_name'=>$table_name);
		$project_headers=$this->tasks->get_data('project_headers',$headerCondition);
		$rowHeads=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
		$spreadsheet= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$cnt=0;
		$columns="";
		$rowCount=1;
		$colsArray=array();
		if($reportHeaders=='all')
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
			// for($i=0;$i<count($reportHeaders);$i++)
			// {
			// 	if($reportHeaders[$i]!='is_alotted')
			// 	{
			// 		$sheet->setCellValue($rowHeads[$cnt].$rowCount, ucwords(str_replace("_"," ",$reportHeaders[$i])));
			// 		$sheet->getStyle($rowHeads[$cnt].$rowCount)->getFont()->applyFromArray( [ 'bold' => TRUE] );
			// 		$sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
			// 		$columns.=" ".$reportHeaders[$i].",";
			// 		array_push($colsArray,$reportHeaders[$i]);
			// 		$cnt++;
			// 	}
			// }

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
        /*
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
		*/
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
		$writer->setPreCalculateFormulas(false);
		$filename = 'HHH_Exception_Report.xlsx';


        // //var_dump($_FILES['test']['name']);die;
		// $config['upload_path'] = './projectfiles/';
        // $config['allowed_types'] = 'xls|xlsx';
		// $config['encrypt_name']=true;

        // $this->load->library('upload', $config);
		// $filename='';
        // if (!$this->upload->do_upload('project_file')) {
        //     $error = array('error' => $this->upload->display_errors());

		// 	print_r($error);
		// 	exit;
        // } else {
        //     $data = $this->upload->data();
		// 	$filename="./projectfiles/".$data['file_name'];
		// }

        $writer->save('projectfiles/' . $filename);
 
        echo '<pre>filename111 ';
        print_r($filename);
        echo '</pre>';
        exit();
        // header('Content-Type: application/vnd.ms-excel');
        // header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        // header('Cache-Control: max-age=0');
        
        // $writer->save('php://output');
	}



    public function generateExcel() {

       

        // Load data or create your Excel content here
        $data = array(
            array('Name', 'Email', 'Phone'),
            array('John Doe', 'john@example.com', '123456789'),
            array('Jane Doe', 'jane@example.com', '987654321')
        );

        // Create a new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set properties etc.

        // Add some data
        $objPHPExcel->getActiveSheet()->fromArray($data, NULL, 'A1');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // Save Excel 2007 file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        // Set a file name for the Excel file
        $filename = 'example.xlsx';

        // Save the Excel file to a directory
        $objWriter->save('path/to/your/directory/' . $filename);

        // Optionally, you can force download the file:
        // force_download('path/to/your/directory/' . $filename, NULL);

        // Display success message or redirect
        echo "Excel file generated and saved to directory successfully!";
    }


    

    public function emailsending(){     //[NOT IN POSTMAN]

        // Ref :- https://stackoverflow.com/questions/50604268/excel-generate-and-send-email-using-phpexcel-library-in-codeigniter
        // Ref :- https://stackoverflow.com/questions/13108508/how-can-i-attach-the-file-in-codeigniter-which-i-have-created-through-coding
        // Ref :- https://forum.codeigniter.com/showthread.php?tid=64101

        $html=$this->load->view('sendmail_template',$data,TRUE);

include("simple_html_dom.php");
$rowRecords = str_get_html($html);

//echo $html;
$filename="Report-".$dtimeFile.".xls";
$path='./reports/';
$csv_handler = fopen ($path.$filename,'w');
fwrite ($csv_handler,$rowRecords);
fclose ($csv_handler);

$msg='Report';

$file = $path.$filename;
$file_size = filesize($file);
$handle = fopen($file, "r");
$content = fread($handle, $file_size);
fclose($handle);
$content = chunk_split(base64_encode($content));
$uid = md5(uniqid(time()));

$file_path=base_url('reports/'.$filename);


$config = Array(
'protocol' => 'smtp',
'smtp_host' => 'ssl://smtp.googlemail.com',
'smtp_port' => 465,
'smtp_user' => 'xxxxxxxxxx@gmail.com',
'smtp_pass' => 'xxxxxxxxxx',
'mailtype'  => 'html', 
'charset'   => 'iso-8859-1'
);
$this->load->library('email', $config);
$this->email->set_newline("\r\n");

// Set to, from, message, etc.

$email = $result->to_addr;

$info = "info@xxxx.xx";
$infoname = "info";
$message = "PFA Report";
$this->email->set_mailtype("html");
$this->email->from($info, $infoname);
$this->email->to($email);

$subject = 'Report';
$this->email->subject($subject);
$this->email->message($message);
$this->email->attach($file_path);

 $r = $this->email->send();
    if (!$r) {
    echo "Failed to send email:" . $this->email->print_debugger(array("header"));
} else {
     echo "Mail Sent";
}
    }







    public function setemail()      //[NOT IN POSTMAN]
    {
        $email="hardik.meghnathi12@gmail.com";
        $subject="some text";
        $message="some text";
        $this->sendEmail($email,$subject,$message);
    }
    public function sendEmail($email,$subject,$message)     //[NOT IN POSTMAN]
    {

        $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'ssl://smtp.googlemail.com',
        'smtp_port' => 465,
        'smtp_user' => 'abc@gmail.com', 
        'smtp_pass' => 'passwrd', 
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE
        );


          $this->load->library('email', $config);
          $this->email->set_newline("\r\n");
          $this->email->from('hardikgirim@gmail.com');
          $this->email->to($email);
          $this->email->subject($subject);
          $this->email->message($message);
            $this->email->attach('C:\Users\xyz\Desktop\images\abc.png');
          if($this->email->send())
         {
          echo 'Email send.';
         }
         else
        {
         show_error($this->email->print_debugger());
        }

    }


   
    public function EditVerificationNew()
    {
        $userid=$this->input->post('user_id');
        $companyid=$this->input->post('company_id');
        $projectid=$this->input->post('project_id');
        $projectname=$this->input->post('project_name');
        $instance_id=$this->input->post('instance_id');
        // $scancode=$this->input->post('scan_code');
		$condition=array(
			"id"=>$userid
        );
        $projectdetail=$this->tasks->get_data('company_projects',array('id'=>$projectid));
        
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        // $scantask=$this->tasks->scanitem($userid,$companyid,$projectname,$projectid,$scancode);
        $scantask=$this->tasks->scanitem2($userid,$companyid,$projectname,$projectid,$instance_id);
        
        foreach($scantask as $st)
        {
            if($st->verified_by !=''){
             $verifiername= $this->tasks->get_verifire_namesingle($st->verified_by);
            }else{
                $verifiername='';
            }
            $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
            $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));
            if($st->verified_datetime)
            {
                $st->verified_by_username= $verifiername;
                $st->verified_by_name= $verifiername;
                $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
            }
            
           // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
        }
		if(!empty($scantask) && count($scantask) > 0)
		{
            $tag='CD';
            
            $projectdetail[0]->project_type=='TG'? $tag='Y':($projectdetail[0]->project_type=='NT'?$tag='N':($projectdetail[0]->project_type=='UN'?$tag='NA':$tag='CD'));
            if($tag!='CD')
            {
                if(!empty($projectdetail) && in_array($scantask[0]->item_category,json_decode($projectdetail[0]->item_category)) && $scantask[0]->tag_status_y_n_na==$tag)
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$scantask));
                    exit;
                }
                else
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>401,"message"=>"Permission to scan this category/tag item is not granted."));
                    exit;
                }

            }
            else
            {
                if(!empty($projectdetail) && in_array($scantask[0]->item_category,json_decode($projectdetail[0]->item_category)))
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>200,"message"=>"Tasks fetched successfully.","data"=>$scantask));
                    exit;
                }
                else
                {
                    header('Content-Type: application/json');
                    echo json_encode(array("success"=>401,"message"=>"Permission to scan this category item is not granted."));
                    exit;
                }
            }
            
            
			
		} 
		else {
			header('Content-Type: application/json');
			echo json_encode(array("success"=>401,"message"=>"Item not available"));
			exit;
		}
    }




}


