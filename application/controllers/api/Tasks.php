<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        $this->load->helper('function_helper');
		$this->load->model('tasks_model','tasks');	
	}

	public function getprojects()
	{
		$userid=$this->input->post('user_id');
		$condition=array(
			"id"=>$userid
		);
        $projects=$this->tasks->getProjects('users',$userid);
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
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
    public function getDashboard()
    {
        $userid=$this->input->post('user_id');
		$condition=array(
			"id"=>$userid
		);
        $projects=$this->tasks->getProjects('users',$userid);
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
		$condition=array(
			"id"=>$userid
		);
        $projects=$this->tasks->getSearchProjects('users',$userid);
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
        foreach($projects as $project)
        {
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
        $projectdetail=$this->tasks->get_data('Company_projects',array('id'=>$projectid));
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
        $scantask=$this->tasks->scanitem($userid,$companyid,$projectname,$projectid,$scancode);
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
        $search_fields =$this->input->post('search_fields');
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
        $projectdetail=$this->tasks->get_data('Company_projects',array('id'=>$projectid));
        
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
        $getquantity=$this->tasks->get_data($projectname,$condition);
        if($scanned->item_scrap_condition =='qty_ok')
        {
            $scanned->qty_ok=(int)$getquantity[0]->qty_ok + (int)$scanned->quantity_verified;
        }
        else if($scanned->item_scrap_condition =='qty_damaged')
        {
            $scanned->qty_damaged=(int)$getquantity[0]->qty_damaged + (int)$scanned->quantity_verified;
        }
        else if($scanned->item_scrap_condition =='qty_scrapped')
        {
            $scanned->qty_scrapped=(int)$getquantity[0]->qty_scrapped + (int)$scanned->quantity_verified;
        }
        else if($scanned->item_scrap_condition =='qty_not_in_use')
        {
            $scanned->qty_not_in_use=(int)$getquantity[0]->qty_not_in_use + (int)$scanned->quantity_verified;
        }
        else if($scanned->item_scrap_condition =='qty_missing')
        {
            $scanned->qty_missing=(int)$getquantity[0]->qty_missing + (int)$scanned->quantity_verified;
        }
        else if($scanned->item_scrap_condition =='qty_shifted')
        {
            $scanned->qty_shifted=(int)$getquantity[0]->qty_shifted + (int)$scanned->quantity_verified;
        }
        
            if($scanned->verification_remarks!='')
            {
                $scanned->quantity_verified=(int)$getquantity[0]->quantity_verified + (int)$scanned->quantity_verified;
                $scanned->verification_status=$scanned->quantity_as_per_invoice <= $scanned->quantity_verified ? "Verified":"Not-Verified";
                $scanned->verification_remarks=$getquantity[0]->verification_remarks != '' ? $getquantity[0]->verification_remarks.' || '.$scanned->verification_remarks:$scanned->verification_remarks;
                $scanned->verified_datetime=date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
                $scanned->updatedat=date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            }
            else{
                $scanned->quantity_verified=(int)$getquantity[0]->quantity_verified + (int)$scanned->quantity_verified;
                $scanned->verification_status=$scanned->quantity_as_per_invoice <= $scanned->quantity_verified ? "Verified":"Not-Verified";
                $scanned->verified_datetime=date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
                $scanned->updatedat=date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))));
            }
            
        $new_array[0] = $this->stdToArray($scanned);
        unset($new_array[0]['item_scrap_condition']);
        $verify=$this->tasks->update_data($projectname,$new_array[0],$condition);
        
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
        $finish=$this->tasks->update_data('Company_projects',$data,$condition);
        
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
        $finish=$this->tasks->update_data('Company_projects',$data,$condition);
        
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
        $finish=$this->tasks->update_data('Company_projects',$data,$condition);
        
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

}
