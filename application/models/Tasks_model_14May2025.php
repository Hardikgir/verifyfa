<?php 
class Tasks_model extends CI_Model {

	public function _consruct(){

		parent::_construct();

 	}

	function get_data($table,$condition) { 

	  $this->db->where($condition);

	  $query = $this->db->get($table);

	  return $query->result();

    }
    function getdistinct_data($table,$column) { 

        $this->db->select("DISTINCT(".$column.")");
  
        $query = $this->db->get($table);
  
        return $query->result();
  
      }
    function getdistinctwithcondition($table,$column,$condition)
    {
        $this->db->select("DISTINCT(".$column.")");
        $this->db->where($condition);
        $query = $this->db->get($table);
  
        return $query->result();
    }
	function update_data($table,$data,$condition) { 

		$this->db->where($condition);
  
		$query = $this->db->update($table,$data);
  
		return true;
  	}

    function getProjects($table,$userid,$company_id,$location_id) { 

        // $condition1=array('company_projects.project_verifier IN ('.$userid.') || company_projects.manager IN ('.$userid.') || company_projects.process_owner IN ('.$userid.') || company_projects.item_owner IN ('.$userid.')');

        $condition1='(FIND_IN_SET('.$userid.',company_projects.project_verifier) || FIND_IN_SET('.$userid.',company_projects.manager) || FIND_IN_SET('.$userid.',company_projects.process_owner) || FIND_IN_SET('.$userid.',company_projects.item_owner))';

        $this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
        $this->db->from('company_projects');
        $this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
        $this->db->join('company','company.id=user_role.company_id');
        $this->db->join('company_locations','company_locations.id=company_projects.project_location');
        // $this->db->where(array('user_role.user_id'=>$userid,'company_projects.status'=>0));
        // $this->db->where(array('user_role.user_id'=>$userid,'company_projects.status'=>0));
        $this->db->where( 'company_projects.status !=','2');
        $this->db->where(array('user_role.user_id'=>$userid));
        $this->db->where($condition1 );
        $this->db->where(array('company_projects.company_id'=>$company_id,'company_projects.project_location'=>$location_id));
        $this->db->group_by('company_projects.project_id');
        $gettasks=$this->db->get();
        // echo $this->db->last_query();
        return $gettasks->result();

    }



    function getProjectsdashboard($table,$userid,$entity_code,$company_id_imp,$location_id) { 

          $condition=array('company_projects.company_id IN ('.$company_id_imp.') AND company_projects.project_location IN ('.$location_id.')',"company_projects.entity_code"=>$entity_code);

          $condition1=array('company_projects.project_verifier IN ('.$userid.') || company_projects.manager IN ('.$userid.') || company_projects.process_owner IN ('.$userid.') || company_projects.item_owner IN ('.$userid.')');


        $this->db->select('company_projects.*,company_locations.location_name,user_role.id as role_id,company.company_name');
        $this->db->from('company_projects');
        $this->db->join('user_role','find_in_set(user_role.user_id,company_projects.project_verifier) AND company_projects.company_id=user_role.company_id');
        $this->db->join('company','company.id=user_role.company_id');
        $this->db->join('company_locations','company_locations.id=company_projects.project_location');
        // $this->db->where(array('user_role.user_id'=>$userid,'company_projects.status'=>0));
        // $this->db->where(array('user_role.user_id'=>$userid,'company_projects.status'=>0));
        // $this->db->where($condition);
        $this->db->where($condition[0]);
        $this->db->where('company_projects.status !=','2');

        // $this->db->where(array('company_projects.company_id'=>$company_id,'company_projects.project_location'=>$location_id));
        $this->db->group_by('company_projects.project_id');
        $gettasks=$this->db->get();
        // echo $this->db->last_query();
        return $gettasks->result();

    }


    function getSearchProjects($table,$userid,$location_id=0) { 

        $condition1='FIND_IN_SET('.$userid.',company_projects.project_verifier) || FIND_IN_SET('.$userid.',company_projects.manager) || FIND_IN_SET('.$userid.',company_projects.process_owner) || FIND_IN_SET('.$userid.',company_projects.item_owner)';

        $this->db->select('company_projects.*,company_locations.location_name,CONCAT(users.firstName,users.lastName) as verifier_name,company.company_name');
        $this->db->from('company_projects');
        $this->db->join('users','find_in_set(users.id,company_projects.project_verifier) AND company_projects.company_id=users.company_id');
        $this->db->join('company','company.id=users.company_id');
        $this->db->join('company_locations','company_locations.id=company_projects.project_location');
        $this->db->where(array('users.id'=>$userid));
        $this->db->where('company_projects.status !=','2');
        if(!empty($location_id)){
            $this->db->where('company_locations.id',$location_id);
        }

        // $this->db->where($condition1);
        $gettasks=$this->db->get();
        return $gettasks->result();

    }
    function projectdetail($project_name)
    {
       return  $this->db->query("SELECT count(*) as TotalQuantity, COUNT(verified_by) as VerifiedQuantity FROM ".$project_name)->result();
    }

    
    function lastupdatetime($project_name,$userid)
    {
        return $this->db->query("SELECT updatedat FROM ".$project_name." where verified_by=".$userid." order by updatedat desc limit 1")->result();
    }
    function scanitem($userid,$companyid,$projectname,$projectid,$scancode)
    {
        $this->db->where(array('item_unique_code'=>$scancode));
        $result=$this->db->get($projectname)->result();
        return $result;
    }


    function scanitem2($userid,$companyid,$projectname,$projectid,$instance_value)
    {
        $this->db->where(array('instance_count'=>$instance_value));
        $result=$this->db->get($projectname)->result();
        return $result;
    }


    function get_schema($table)
	{
		return $this->db->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS`  WHERE `TABLE_SCHEMA`='verifyfa_db'  AND `TABLE_NAME`='".$table."' AND `IS_NULLABLE`='No'")->result();
    }
    function getBasicReport($tablename,$verificationstatus,$reportHeaders)
    {
        $cols="";
        $grp="";
        $i=1;
        foreach($reportHeaders as $rh)
        {
            if($i==1)
            {
                if($rh=='total_item_amount_capitalized')
                {
                    $grp.=$rh;
                    $cols.="SUM(".$rh.")";
                }   
                else
                {
                    $grp.=$rh;
                    $cols.=$rh;
                }
                    
            }
            else
            {
                if($rh=='total_item_amount_capitalized')
                {
                    $grp.=",".$rh;
                    $cols.=",SUM(".$rh.")";
                }
                else
                {
                    $grp.=",".$rh;
                    $cols.= ",".$rh;
                }
                
            }
            $i++;
        }
        
        if($cols=='All')
        {
            $columns='*';
        }
        else
        {
            $columns=$cols;
        }
        if($verificationstatus==1)
        {
            $data['tagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE tag_status_y_n_na='Y' group by item_category")->result();
            $data['nontagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE tag_status_y_n_na='N' group by item_category")->result();
            $data['unspecified']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE tag_status_y_n_na='NA' group by item_category")->result();
        }
        else if($verificationstatus=='Verified')
        {
            $data['tagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='Y' group by item_category")->result();
            $data['nontagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='N' group by item_category")->result();
            $data['unspecified']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='NA' group by item_category")->result();
            
        }
        else
        {
            $data['tagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='Y' group by item_category")->result();
            $data['nontagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='N' group by item_category")->result();
            $data['unspecified']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='NA' group by item_category")->result();
        }
        
        $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." group by item_category")->result();
        return $data;
    }
    public function getUnallocated($tablename,$verificationstatus,$reportHeaders)
    {
        $cols="";
        $grp="";
        $i=1;
        foreach($reportHeaders as $rh)
        {
            if($i==1)
            {
                if($rh=='total_item_amount_capitalized')
                {
                    $grp.=$rh;
                    $cols.="SUM(".$rh.")";
                }   
                else
                {
                    $grp.=$rh;
                    $cols.=$rh;
                }
                    
            }
            else
            {
                if($rh=='total_item_amount_capitalized')
                {
                    $grp.=",".$rh;
                    $cols.=",SUM(".$rh.")";
                }
                else
                {
                    $grp.=",".$rh;
                    $cols.= ",".$rh;
                }
                
            }
            $i++;
        }
        
        if($cols=='All')
        {
            $columns='*';
        }
        else
        {
            $columns=$cols;
        }
        if($verificationstatus==1)
        {
            $data['tagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE tag_status_y_n_na='Y' and is_alotted=0 group by item_category")->result();
            $data['nontagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE tag_status_y_n_na='N'  and is_alotted=0 group by item_category")->result();
            $data['unspecified']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE tag_status_y_n_na='NA'  and is_alotted=0 group by item_category")->result();
        }
        else if($verificationstatus=='Verified')
        {
            $data['tagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='Y'  and is_alotted=0 group by item_category")->result();
            $data['nontagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='N'  and is_alotted=0 group by item_category")->result();
            $data['unspecified']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='NA'  and is_alotted=0 group by item_category")->result();
            
        }
        else
        {
            $data['tagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='Y'  and is_alotted=0 group by item_category")->result();
            $data['nontagged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='N' and is_alotted=0 group by item_category")->result();
            $data['unspecified']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='NA' and is_alotted=0 group by item_category")->result();
        }
        
        $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename."  where is_alotted=0 group by item_category")->result();
        return $data;
    }
    function getExceptionOneReport($tablename,$verificationstatus,$reportHeaders)
    {
        $cols="";
        $grp="";
        $i=1;
        foreach($reportHeaders as $rh)
        {
            if($i==1)
            {
                if($rh=='total_item_amount_capitalized')
                {
                    $grp.=$rh;
                    $cols.="SUM(".$rh.")";
                }   
                else
                {
                    $grp.=$rh;
                    $cols.=$rh;
                }
                    
            }
            else
            {
                if($rh=='total_item_amount_capitalized')
                {
                    $grp.=",".$rh;
                    $cols.=",SUM(".$rh.")";
                }
                else
                {
                    $grp.=",".$rh;
                    $cols.= ",".$rh;
                }
                
            }
            $i++;
        }
        
        if($cols=='All')
        {
            $columns='*';
        }
        else
        {
            $columns=$cols;
        }
        if($verificationstatus==1)
        {
            $data['good']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE qty_ok > 0 group by item_category")->result();
            $data['damaged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE qty_damaged > 0 group by item_category")->result();
            $data['scrapped']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE qty_scrapped > 0 group by item_category")->result();
            $data['missing']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE qty_missing > 0 group by item_category")->result();
            $data['shifted']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE qty_shifted > 0 group by item_category")->result();
            $data['notinuse']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE qty_not_in_use > 0 group by item_category")->result();
            $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE quantity_as_per_invoice > quantity_verified group by item_category")->result();
                    //   echo $this->db->last_query();

            
        }
        else if($verificationstatus=='Verified')
        {
            $data['good']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_ok > 0 group by item_category")->result();
            $data['damaged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_damaged > 0 group by item_category")->result();
            $data['scrapped']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_scrapped > 0 group by item_category")->result();
            $data['missing']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_missing > 0 group by item_category")->result();
            $data['shifted']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_shifted > 0 group by item_category")->result();
            $data['notinuse']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_not_in_use > 0 group by item_category")->result();
            //$data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice > quantity_verified group by item_category")->result();
            $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified > 0 group by item_category")->result();
            
        }
        else
        {
            $data['good']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_ok > 0 group by item_category")->result();
            $data['damaged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_damaged > 0 group by item_category")->result();
            $data['scrapped']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_scrapped > 0 group by item_category")->result();
            $data['missing']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_missing > 0 group by item_category")->result();
            $data['shifted']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_shifted > 0 group by item_category")->result();
            $data['notinuse']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_not_in_use > 0 group by item_category")->result();
            // $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_as_per_invoice > quantity_verified group by item_category")->result();
            $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified = 0 group by item_category")->result();
        }
        
        $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(quantity_as_per_invoice) as total_qty FROM ".$tablename." group by item_category")->result();
        // echo $this->db->last_query();
        return $data;
    }
    public function getExceptionOneUnallocated($tablename,$verificationstatus,$reportHeaders)
    {
        $cols="";
        $grp="";
        $i=1;
        foreach($reportHeaders as $rh)
        {
            if($i==1)
            {
                if($rh=='total_item_amount_capitalized')
                {
                    $grp.=$rh;
                    $cols.="SUM(".$rh.")";
                }   
                else
                {
                    $grp.=$rh;
                    $cols.=$rh;
                }
                    
            }
            else
            {
                if($rh=='total_item_amount_capitalized')
                {
                    $grp.=",".$rh;
                    $cols.=",SUM(".$rh.")";
                }
                else
                {
                    $grp.=",".$rh;
                    $cols.= ",".$rh;
                }
                
            }
            $i++;
        }
        
        if($cols=='All')
        {
            $columns='*';
        }
        else
        {
            $columns=$cols;
        }
        if($verificationstatus==1)
        {
            $data['good']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE qty_ok > 0 and is_alotted=0 group by item_category")->result();
            $data['damaged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE qty_damaged > 0 and is_alotted=0 group by item_category")->result();
            $data['scrapped']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE qty_scrapped > 0 and is_alotted=0 group by item_category")->result();
            $data['missing']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE qty_missing > 0 and is_alotted=0 group by item_category")->result();
            $data['shifted']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE qty_shifted > 0 and is_alotted=0 group by item_category")->result();
            $data['notinuse']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE qty_not_in_use > 0 and is_alotted=0 group by item_category")->result();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data['good']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and qty_ok > 0 and is_alotted=0 group by item_category")->result();
            $data['damaged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and qty_damaged > 0 and is_alotted=0 group by item_category")->result();
            $data['scrapped']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and qty_scrapped > 0 and is_alotted=0 group by item_category")->result();
            $data['missing']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and qty_missing > 0 and is_alotted=0 group by item_category")->result();
            $data['shifted']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and qty_shifted > 0 and is_alotted=0 group by item_category")->result();
            $data['notinuse']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and qty_not_in_use > 0 and is_alotted=0 group by item_category")->result();

            
        }
        else
        {
            $data['good']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_ok > 0 and is_alotted=0 group by item_category")->result();
            $data['damaged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_damaged > 0 and is_alotted=0 group by item_category")->result();
            $data['scrapped']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_scrapped > 0 and is_alotted=0 group by item_category")->result();
            $data['missing']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_missing > 0 and is_alotted=0 group by item_category")->result();
            $data['shifted']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_shifted > 0 and is_alotted=0 group by item_category")->result();
            $data['notinuse']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_not_in_use > 0 and is_alotted=0 group by item_category")->result();
            
        }
        
        $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items FROM ".$tablename." where is_alotted=0 group by item_category")->result();
        return $data;
    }
    public function getExceptionThreeReport($tablename,$verificationstatus,$reportHeaders)
    {
        // 1 = All
        // Verified = Verified
        // Else = Not-Verified

        if($verificationstatus==1)
        {
            $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as total_items FROM ".$tablename." group by item_category")->result();
            // echo '<pre>all Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';

            $data['verified']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' group by item_category")->result();
            // echo '<pre>verified Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';

            $data['not_verified']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status!='Verified' group by item_category")->result();
            // echo '<pre>not_verified Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';

            $data['verifiedequal']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice=quantity_verified group by item_category")->result();
            // echo '<pre>verifiedequal Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';

            $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE quantity_as_per_invoice > quantity_verified group by item_category")->result();
            // echo '<pre>remaining Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            
            $data['excess']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as items FROM ".$tablename." WHERE quantity_as_per_invoice < quantity_verified  group by item_category")->result(); 
            // echo '<pre>excess Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            // exit();
            //echo $this->db->last_query();
        }
        else if($verificationstatus=='Verified')
        {
            $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' group by item_category")->result();
            // echo '<pre>all Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';

            $data['verified']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' group by item_category")->result();
            // echo '<pre>verified Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            
            $data['verifiedequal']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice=quantity_verified group by item_category")->result();
            // echo '<pre>verifiedequal Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            
            //$data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice > quantity_verified group by item_category")->result();
            $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified > 0 group by item_category")->result();
            
            // echo '<pre>remaining Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            
            $data['excess']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as items FROM ".$tablename." WHERE quantity_as_per_invoice < quantity_verified  group by item_category")->result();
            // echo '<pre>excess Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            
        }
        else
        {
            $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Not-Verified' group by item_category")->result();
            // echo '<pre>all Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';

            $data['verified']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified > 0 group by item_category")->result();
            // echo '<pre>verified Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            
            $data['verifiedequal']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified>0 group by item_category")->result();
            // echo '<pre>verifiedequal Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            
            // $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_as_per_invoice > quantity_verified group by item_category")->result();
            $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified = 0 group by item_category")->result();
            // echo '<pre>remaining Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';
            // exit();
            
            $data['excess']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as items FROM ".$tablename." WHERE quantity_as_per_invoice < quantity_verified  group by item_category")->result();
            // echo '<pre>excess Query :- ';
            // print_r($this->db->last_query());
            // echo '</pre>';    

        }
        return $data;
    }



    public function getExceptionFourReport($tablename,$verificationstatus,$reportHeaders)
    {
        $cols="";
        $grp="";
        $i=1;
        if($verificationstatus==1)
        {
            $data['all']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE (verification_remarks!='' OR verification_remarks!=NULL) group by item_category")->result();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data['all']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE verification_status='Verified' and (verification_remarks!='' OR verification_remarks!=NULL) group by item_category")->result();
            
        }
        else
        {
            $data['all']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE verification_status='Not-Verified' and (verification_remarks!='' OR verification_remarks!=NULL) group by item_category")->result();
        }
        
        
        return $data;
    }
    public function getExceptionFiveReport($tablename,$verificationstatus,$reportHeaders)
    {
        $cols="";
        $grp="";
        $i=1;
        if($verificationstatus==1)
        {
            $data['all']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE (item_note!='' OR item_note!=NULL) group by item_category")->result();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data['all']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE verification_status='Verified' and (item_note!='' OR item_note!=NULL) group by item_category")->result();
            
        }
        else
        {
            $data['all']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE verification_status='Not-Verified' and (item_note!='' OR item_note!=NULL) group by item_category")->result();
        }
        
        
        return $data;
    }
    public function getExceptionEightReport($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
            $data['manual']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE mode_of_verification='Manual' group by item_category")->result();
            $data['scan']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE mode_of_verification='Scan' group by item_category")->result();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data['manual']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE mode_of_verification='Manual' and verification_status='Verified' group by item_category")->result();
            $data['scan']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE mode_of_verification='Scan' and verification_status='Verified' group by item_category")->result();
            
        }
        else
        {
            $data['manual']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE mode_of_verification='Manual' and verification_status='Not-Verified' group by item_category")->result();
            $data['scan']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE mode_of_verification='Scan' and verification_status='Not-Verified' group by item_category")->result();
        }
        
        $data['all']=$this->db->query("SELECT item_category,count(*) as items FROM ".$tablename." WHERE mode_of_verification!='Not Verified' group by item_category")->result();
        return $data;
    }
    public function getDetailedReportFAR($tablename,$verificationstatus,$reportHeaders)
    {
        // print_r($reportHeaders);
        if($verificationstatus==1)
        {
           $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename)->result_array();
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified'")->result_array();
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified'")->result_array();
        }
        // echo $this->db->last_query();
        return $data;
    }
    public function getDetailedReportTagged($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE tag_status_y_n_na='Y'")->result_array();
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='Y'")->result_array();
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='Y'")->result_array();
        }
                // echo $this->db->last_query();

        return $data;
    }
    public function getDetailedReportNonTagged($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE  tag_status_y_n_na='N'")->result_array();
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='N'")->result_array();
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='N'")->result_array();
        }
        return $data;
    }
    public function getDetailedReportUnspecified($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE tag_status_y_n_na='NA'")->result_array();
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and tag_status_y_n_na='NA'")->result_array();
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and tag_status_y_n_na='NA'")->result_array();
        }
        return $data;
    }
    public function getDetailedReportUnallocated($tablename,$reportHeaders)
    {
        $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE is_alotted=0")->result_array();
        return $data;
    }
    public function getDetailedExceptionOneReport($tablename,$verificationstatus,$reportHeaders,$reportOneType)
    {
        if($verificationstatus==1)
        {
            if($reportOneType=='qty_ok')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE qty_ok>0")->result_array();    
            }
            else if($reportOneType=='qty_damaged')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE qty_damaged>0")->result_array();    
            }
            else if($reportOneType=='qty_scrapped')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE qty_scrapped>0")->result_array();    
            }
            else if($reportOneType=='qty_missing')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE qty_missing>0")->result_array();    
            }
            else if($reportOneType=='qty_shifted')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE qty_shifted>0")->result_array();    
            }
            else if($reportOneType=='qty_not_in_use')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE qty_not_in_use>0")->result_array();    
            }
            else if($reportOneType=='qty_remaining')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE quantity_as_per_invoice>quantity_verified")->result_array();    
            }
            else if($reportOneType=='consolidated')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename)->result_array();    
            }
            
        }
        else if($verificationstatus=='Verified')
        {
            if($reportOneType=='qty_ok')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and qty_ok>0")->result_array();    
            }
            else if($reportOneType=='qty_damaged')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and qty_damaged>0")->result_array();    
            }
            else if($reportOneType=='qty_scrapped')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and qty_scrapped>0")->result_array();    
            }
            else if($reportOneType=='qty_missing')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and qty_missing>0")->result_array();    
            }
            else if($reportOneType=='qty_shifted')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and qty_shifted>0")->result_array();    
            }
            else if($reportOneType=='qty_not_in_use')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and qty_not_in_use>0")->result_array();    
            }
            else if($reportOneType=='qty_remaining')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice>quantity_verified")->result_array();    
            }
            else if($reportOneType=='consolidated')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified'")->result_array();    
            }
            
        }
        else
        {
            if($reportOneType=='qty_ok')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_ok>0")->result_array();    
            }
            else if($reportOneType=='qty_damaged')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_damaged>0")->result_array();    
            }
            else if($reportOneType=='qty_scrapped')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_scrapped>0")->result_array();    
            }
            else if($reportOneType=='qty_missing')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_missing>0")->result_array();    
            }
            else if($reportOneType=='qty_shifted')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_shifted>0")->result_array();    
            }
            else if($reportOneType=='qty_not_in_use')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_not_in_use>0")->result_array();    
            }
            else if($reportOneType=='qty_remaining')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_as_per_invoice>quantity_verified")->result_array();    
            }
            else if($reportOneType=='consolidated')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified'")->result_array();    
            }

        }
        return $data;
    }
    public function getDetailedExceptionTwoReport($tablename,$verificationstatus,$reportHeaders,$reportOneType)
    {
        if($verificationstatus==1)
        {
            if($reportOneType=='qty_ok')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE qty_ok>0")->result_array();    
            }
            
            
        }
        else if($verificationstatus=='Verified')
        {
            if($reportOneType=='qty_ok')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and qty_ok>0")->result_array();    
            }
            
            
        }
        else
        {
            if($reportOneType=='qty_ok')
            {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and qty_ok>0")->result_array();    
            }
            

        }
        return $data;
    }
    public function getDetailedExceptionThreeReport($tablename,$verificationstatus,$reportHeaders,$reportOneType)
    {
        if($reportOneType=='verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified'")->result_array();
          
        }
        else if($reportOneType=='equal')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice=quantity_verified")->result_array();
        }
        else if($reportOneType=='short')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE quantity_as_per_invoice > quantity_verified and quantity_verified>0")->result_array();    
        }
        else if($reportOneType=='excess')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE quantity_as_per_invoice < quantity_verified")->result_array();   

        }
        else if($reportOneType=='remaining')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE quantity_as_per_invoice > quantity_verified and quantity_verified=0")->result_array();    
        }
        
        return $data;
    }
    public function getDetailedExceptionThreeProjectCloseRemaining($tablename,$verificationstatus,$reportHeaders,$reportOneType)
    {
        if($reportOneType=='verified')
        {
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified'")->result_array();
          
        }
        else if($reportOneType=='equal')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice=quantity_verified")->result_array();
        }
        else if($reportOneType=='short')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE quantity_as_per_invoice > quantity_verified and quantity_verified<0")->result_array();    
        }
        else if($reportOneType=='excess')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE quantity_as_per_invoice < quantity_verified")->result_array();    
        }
        
        if($reportOneType=='remaining')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE quantity_as_per_invoice > quantity_verified")->result_array();    
        }
    }
    public function getDetailedExceptionThreeConsolidatedReport($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename)->result_array();
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified'")->result_array();
        }
        else if($verificationstatus=='Not-Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified'")->result_array();
        }
        return $data;
    }
    
    public function getDetailedExceptionFourReport($tablename,$verificationstatus,$reportHeaders,$reportOneType,$item_category)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE (verification_remarks!='' OR verification_remarks!=NULL) and item_category=".$item_category)->result_array();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and (verification_remarks!='' OR verification_remarks!=NULL) and item_category=".$item_category)->result_array();
            
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and (verification_remarks!='' OR verification_remarks!=NULL) and item_category=".$item_category)->result_array();
        }
        
        return $data;
    }
    public function getDetailedExceptionFourAllReport($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE (verification_remarks!='' OR verification_remarks!=NULL)")->result_array();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and (verification_remarks!='' OR verification_remarks!=NULL)")->result_array();
            
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and (verification_remarks!='' OR verification_remarks!=NULL)")->result_array();
        }
        
        return $data;
    }
    public function getDetailedExceptionFiveReport($tablename,$verificationstatus,$reportHeaders,$reportOneType,$item_category)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE (item_note!='' OR item_note!=NULL) and item_category=".$item_category)->result_array();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and (item_note!='' OR item_note!=NULL) and item_category=".$item_category)->result_array();
            
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and (item_note!='' OR item_note!=NULL) and item_category=".$item_category)->result_array();
        }
        
        return $data;
    }
    public function getDetailedExceptionFiveAllReport($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE (item_note!='' OR item_note!=NULL)")->result_array();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified' and (item_note!='' OR item_note!=NULL)")->result_array();
            
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified' and (item_note!='' OR item_note!=NULL)")->result_array();
        }
        
        return $data;
    }
    public function getDetailedExceptionEightReport($tablename,$verificationstatus,$reportHeaders,$mode)
    {
        if($verificationstatus==1)
        {
            if($mode=='manual')
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification='Manual'")->result_array();
            else
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification='Scan'")->result_array();
            
        }
        else if($verificationstatus=='Verified')
        {
            if($mode=='manual')
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification='Manual' and verification_status='Verified'")->result_array();
            else
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification='Scan' and verification_status='Verified'")->result_array();
            
        }
        else
        {
            if($mode=='manual')
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification='Manual' and verification_status='Not-Verified' ")->result_array();
            else
                $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification='Scan' and verification_status='Not-Verified'")->result_array();
        }
        return $data;
    }
    public function getDetailedExceptionEightConsolidatedReport($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
           $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification!='Not Verified'")->result_array();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification!='Not Verified' and verification_status='Verified'")->result_array();
            
        }
        else
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE mode_of_verification!='Not Verified' and verification_status='Not-Verified'")->result_array();
            
        }
        return $data;
    }



    //gaurav api work start//
    public function get_project_companies($userid){
           $this->db->select('*');
           $this->db->from('company_projects');
           $this->db->where('project_verifier',$userid);
           $this->db->group_by('company_id'); 
           $query=$this->db->get();
           $result=$query->result_array();
    //    echo $this->db->last_query();
           return $result;
           
    }
   
    public function get_all_company_user_role($user_id){
		$this->db->select("*");
		$this->db->from("user_role");
		$this->db->where("user_id",$user_id);
		$this->db->where("company_id !=",'0');
        $this->db->group_by('company_id'); 
		$query = $this->db->get();
		return $query->result_array();

	}
    
    public function get_company_row($company_id){
        $this->db->select('*');
        $this->db->from('company');
        $this->db->where('id',$company_id);
        $query=$this->db->get();
        $row=$query->row();
        return $row;
 }


  //gaurav api work start//
  public function get_project_location($userid,$company_id){
    $this->db->select('*');
    $this->db->from('company_projects');
    $this->db->where('project_verifier',$userid);
    $this->db->where('company_id',$company_id);
    $this->db->group_by('project_location');

    $query=$this->db->get();
    $result=$query->result_array();
    return $result;
  }

  public function get_all_location_user_role($user_id,$company_id){
    $this->db->select("*");
    $this->db->from("user_role");
    $this->db->where("user_id",$user_id);
    $this->db->where("company_id",$company_id);
    $this->db->group_by('location_id'); 
    $query = $this->db->get();
    // echo $this->db->last_query();
    $result = $query->result_array();
    // foreach($result as $row){
    //     $verifiercount = check_verifier_count($row['projectids'],$user_id);
    //     $check_itemowner_count = check_itemowner_count($row['projectids'],$user_id);
    //     $check_process_owner_count = check_process_owner_count($row['projectids'],$user_id);
    
    //     $check_manager_count = check_manager_count($row['projectids'],$user_id);
    
    //     if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){
    //         $row['final']='1';
    //     }else{
    //         $row['final']='0';
    //     }
    // }
    return $result;

}

  public function get_location_count($userid,$company_id){
    $this->db->select('*');
    $this->db->from('company_projects');
    $this->db->where('project_verifier',$userid);
    $this->db->where('company_id',$company_id);
    $this->db->group_by('project_location');
    $query=$this->db->get();
    $result=$query->num_rows();
    return $result;
  }
  public function get_location_count_user($userid,$company_id){
    $this->db->select("*");
    $this->db->from("user_role");
    $this->db->where("user_id",$userid);
    $this->db->where("company_id",$company_id);
    $this->db->group_by('location_id'); 
    $query = $this->db->get();
    // $this->db->group_by('project_location');
    $result=$query->num_rows();
    return $result;
  }
  

  public function get_project_count($userid,$company_id){
    $this->db->select('*');
    $this->db->from('company_projects');
    $this->db->where('project_verifier',$userid);
    $this->db->where('company_id',$company_id);
    // $this->db->group_by('project_location');
    $query=$this->db->get();
    $result=$query->num_rows();
    return $result;
  }


  
  public function get_project_count_user($userid,$company_id){
    $this->db->select('*');
    $this->db->from('company_projects');
    $this->db->where('company_id',$company_id);
    $this->db->where('status !=','2');

    $query=$this->db->get();
    $result=$query->result();
 $i=0;
    foreach($result as $row){
    $verifiercount = check_verifier_count($row->id,$userid);
    $check_itemowner_count = check_itemowner_count($row->id,$userid);
    $check_process_owner_count = check_process_owner_count($row->id,$userid);

    $check_manager_count = check_manager_count($row->id,$userid);

    if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){
        $i++;
     }
    }
    return $i;
  }
  public function get_project_count_location($userid,$company_id,$location_id){
    $this->db->select('*');
    $this->db->from('company_projects');
    // $this->db->where('project_verifier',$userid);
    $this->db->where('project_location',$location_id);
    $this->db->where('company_id',$company_id);
    $this->db->where('status !=','2');
    // $this->db->group_by('project_location');
    $query=$this->db->get();
    $result=$query->result_array();
    $i=0;
    foreach($result as $row){
    $verifiercount = check_verifier_count($row['id'],$userid);
    $check_itemowner_count = check_itemowner_count($row['id'],$userid);
    $check_process_owner_count = check_process_owner_count($row['id'],$userid);
    $check_manager_count = check_manager_count($row['id'],$userid);

    if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){
        $i++;
     }
    }
    return $i;  }

  

  
  public function get_location_row($project_location){
    $this->db->select('*');
    $this->db->from('company_locations');
    $this->db->where('id',$project_location);
    $query=$this->db->get();
    $row=$query->row();
    return $row;
}


public function get_project_company_location($userid,$company_id,$location_id){
    $this->db->select('*');
    $this->db->from('company_projects');
    // $this->db->where('project_verifier',$userid);
    $this->db->where('company_id',$company_id);
    $this->db->where('project_location',$location_id);
    $query=$this->db->get();
    $result=$query->result_array();
//  $this->db->last_query();

 foreach($result as $row){
        $verifiercount = check_verifier_count($row['id'],$userid);
        $check_itemowner_count = check_itemowner_count($row['id'],$userid);
        $check_process_owner_count = check_process_owner_count($row['id'],$userid);
        $check_manager_count = check_manager_count($row['id'],$userid);
    
        if(($verifiercount == '1') || ($check_itemowner_count =='1') || ($check_process_owner_count == '1') ||  ($check_manager_count == '1')){
            $row['final']='1';
        }else{
            $row['final']='0';
        }
    }
    return $result;
  }

  public function get_company_project($id){
    $this->db->select('*');
    $this->db->from('company_projects');
    $this->db->where('id',$id);
    $query=$this->db->get();
    $result=$query->row();
//  $this->db->last_query();
    return $result;
  }
  function projectdetailnew($project_name)
  {
     return  $this->db->query("SELECT count(*) as TotalQuantity, COUNT(verified_by) as VerifiedQuantity FROM ".$project_name)->result_array();
  }

  public function get_contact_detail($id){
    $this->db->select('*');
    $this->db->from('contact_detail');
    $this->db->where('project_id',$id);
    $query=$this->db->get();
    $result=$query->result_array();
//  $this->db->last_query();
    return $result;
  }

  public function get_addintional_detail($id){
    $this->db->select('*');
    $this->db->from('additional_data');
    $this->db->where('project_id',$id);
    $query=$this->db->get();
    $result=$query->result_array();
//  $this->db->last_query();
    return $result;
  }

  public function save_contact_detail($data){
    $this->db->insert('contact_detail',$data);
    return 1;
  }
  
  public function save_additional_data($data){
    $this->db->insert('additional_data',$data);
    return 1;
  }



  public function get_verifire_name($verifierids){
    $verifierids =explode(',',$verifierids);
    $verifiername='';
    foreach( $verifierids as $row){
    $this->db->select('firstName, lastName');
    $this->db->from('users');
    $this->db->where('id',$row);
    $query=$this->db->get();
    $result=$query->row();
     $verifiername .= $result->firstName.' '.$result->lastName.', ';

}
    return $verifiername;
 }

 public function get_verifire_namesingle($verifierids){

    $this->db->select('firstName, lastName');
    $this->db->from('users');
    $this->db->where('id',$verifierids);
    $query=$this->db->get();
    $result=$query->row();
    $verifiername = '';
    if(!empty($result)){
        $verifiername= $result->firstName.' '.$result->lastName;
    }
    
    return $verifiername;
 }
    


// for reports//
 function getProjectsreport($userid) { 

    $condition1='(FIND_IN_SET('.$userid.',company_projects.project_verifier) || FIND_IN_SET('.$userid.',company_projects.manager) || FIND_IN_SET('.$userid.',company_projects.process_owner) || FIND_IN_SET('.$userid.',company_projects.item_owner))';
    $this->db->select('*');
    $this->db->from('company_projects');
    $this->db->where($condition1 );
    $this->db->where('status !=','2');
    $gettasks=$this->db->get();
    // echo $this->db->last_query();
    return $gettasks->result_array();

}

public function genrateadditionalassets($projectSelect){
    $this->db->select('*');
    $this->db->from('additional_data');
    $this->db->where('project_id',$projectSelect);
    $gettasks=$this->db->get();
    return $gettasks->result();
}

public function com_row($comid){
    $this->db->select('*');
    $this->db->from('company');
    $this->db->where('id',$comid);
    $gettasks=$this->db->get();
    return $gettasks->row();
}

public function loc_row($locid){
    $this->db->select('*');
    $this->db->from('company_locations');
    $this->db->where('id',$locid);
    $gettasks=$this->db->get();
    return $gettasks->row();
}


public function get_project_close_by_company($company_id){
    $this->db->select('*');
    $this->db->from('company_projects');
    $this->db->where('company_id',$company_id);
    $this->db->where('status','1');
    $query=$this->db->get();
    $result=$query->row();
    $data['is_project_close'] = 0;
    if(!empty($result)){
        if($result->status == 1){
            // echo "asdasd";
            $data['is_project_close'] = 1;
            $due_date_value = $result->due_date;
            $finish_date_value = date("Y-m-d", strtotime($result->finish_datetime));
            if ($finish_date_value < $due_date_value ) {
                $data['is_project_overdue'] = 0;
            }else{
                $data['is_project_overdue'] = 1;
            }
        }
    }
    return $data;
   
}


public function get_project_close($project_id){
    $this->db->select('*');
    $this->db->from('company_projects');
    $this->db->where('id',$project_id);
    $this->db->where('status !=','2');
    $query=$this->db->get();
    $result=$query->row();
    $data['is_project_close'] = 0;
    if($result->status == '1'){
        $data['is_project_close'] = 1;
        $due_date_value = $result->due_date;
        $finish_date_value = date("Y-m-d", strtotime($result->finish_datetime));
        if ($finish_date_value < $due_date_value ) {
            $data['is_project_overdue'] = 0;
        }else{
            $data['is_project_overdue'] = 1;
        }
    }
    return $data;
}


public function get_notification_by_user_role($user_id){
    $this->db->select('notification.title,notification.description,notification.type,notification_user.notification_id,notification_user.user_id,notification_user.is_read');
    $this->db->from('notification');
    $this->db->join('notification_user','notification_user.notification_id=notification.id');
    $this->db->where('notification_user.user_id',$user_id);
    $this->db->where('notification.type','Notification');
    $getnotifications=$this->db->get();
    return $getnotifications->result();
}

public function get_over_due_project(){
    $this->db->select('*');
    $this->db->from('company_projects');
    // $this->db->where('company_id',$company_id);
    $this->db->where('status','1');
    $query=$this->db->get();
    $result=$query->row();
    $data['is_project_close'] = 0;
    if(!empty($result)){
        if($result->status == 1){
            // echo "asdasd";
            $data['is_project_close'] = 1;
            $due_date_value = $result->due_date;
            $finish_date_value = date("Y-m-d", strtotime($result->finish_datetime));
            if ($finish_date_value < $due_date_value ) {
                $data['is_project_overdue'] = 0;
            }else{
                $data['is_project_overdue'] = 1;
            }
        }
    }
    return $data;
   
}




function get_product_search($sort_by,$order_by,$table_name)
{
    return $this->db->query("SELECT * FROM ".$table_name." order by ".$sort_by." ".$order_by."")->result();
}



    public function getDetailedExceptionThreeConsolidatedReport_2($tablename,$verificationstatus,$reportHeaders)
    {
        if($verificationstatus==1)
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename)->result_array();
        }
        else if($verificationstatus=='Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Verified'")->result_array();
        }
        else if($verificationstatus=='Not-Verified')
        {
            $data=$this->db->query("SELECT ".$reportHeaders." FROM ".$tablename." WHERE verification_status='Not-Verified'")->result_array();
        }
        return $data;
    }


    public function getExceptionThreeReportdownload($tablename,$verificationstatus,$reporttype)
    {
        if($verificationstatus==1)
        {
            if($reporttype == 'all'){
                $data=$this->db->query("SELECT * FROM ".$tablename."")->result_array();
            }
            if($reporttype == 'verified'){
                $data=$this->db->query("SELECT * FROM ".$tablename." WHERE verification_status='Verified'")->result_array();
            }
            
            if($reporttype == 'not_verified'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE verification_status!='Verified'")->result_array();;
            }

            if($reporttype == 'verifiedequal' || $reporttype =='equal'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice=quantity_verified")->result_array();
            }

            if($reporttype == 'remaining'){
                $data = $this->db->query("SELECT *  FROM ".$tablename." WHERE quantity_as_per_invoice > quantity_verified")->result_array();
            }

            if($reporttype == 'excess'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE quantity_as_per_invoice < quantity_verified ")->result_array();
            }
        }
        else if($verificationstatus=='Verified')
        {
            if($reporttype == 'all'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE verification_status='Verified' ")->result_array();
            }
            

            if($reporttype == 'verified'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE verification_status='Verified' ")->result_array();
            }

            if($reporttype == 'verifiedequal' || $reporttype =='equal'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice=quantity_verified ")->result_array();
            }

            if($reporttype == 'remaining'){
                $data = $this->db->query("SELECT *  FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice > quantity_verified ")->result_array();

                $data = $this->db->query("SELECT *  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified > 0")->result_array();

                //  $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified > 0 group by item_category")->result();
            }

            if($reporttype == 'excess'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE quantity_as_per_invoice < quantity_verified  ")->result_array();
            }

        }
        else
        {
           
            if($reporttype == 'all'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE verification_status='Not-Verified'")->result_array();
            }

            
            if($reporttype == 'verified'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified > 0 ")->result_array();
            }

            if($reporttype == 'verifiedequal' || $reporttype =='equal'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified>0 ")->result_array();
            }

            if($reporttype == 'remaining'){
                // $data = $this->db->query("SELECT *  FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_as_per_invoice > quantity_verified ")->result_array();
                $data = $this->db->query("SELECT *  FROM ".$tablename." WHERE verification_status='Not-Verified' AND quantity_verified = 0")->result_array();
            }

            if($reporttype == 'excess'){
                $data = $this->db->query("SELECT * FROM ".$tablename." WHERE quantity_as_per_invoice < quantity_verified  ")->result_array(); 
            }
        }
        return $data;
    }


    function insert_data($table,$data)
	{
		$this->db->insert($table,$data);
		return $this->db->insert_id();
	}


    public function get_verifiedprojects_instance_by_item($item_id,$project_id){
        $this->db->select('*');
        $this->db->from('verifiedproducts');
        $this->db->where('item_id',$item_id);
        $this->db->where('project_id',$project_id);
        $gettasks=$this->db->get();
        return $gettasks->result();
    }

    public function get_item_details($tablename,$item_id){
        $details = $this->db->query("SELECT instance_count FROM ".$tablename." WHERE id =".$item_id." ")->row();
        return $details;
    }

}