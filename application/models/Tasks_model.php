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

    function get_datavisiblecolumn($condition) { 
     $this->db->select("*");
     $this->db->from('project_headers');
        $this->db->where($condition);
        $this->db->group_by('project_id');
        $query = $this->db->get();
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
    function getProjects($table,$userid) { 

       
        $this->db->select('Company_projects.*,company_locations.location_name,CONCAT(users.firstName,users.lastName) as verifier_name,company.company_name');
        $this->db->from('Company_projects');
        $this->db->join('users','find_in_set(users.id,Company_projects.project_verifier) AND Company_projects.company_id=users.company_id');
        $this->db->join('company','company.id=users.company_id');
        $this->db->join('company_locations','company_locations.id=Company_projects.project_location');
        $this->db->where(array('users.id'=>$userid,'Company_projects.status'=>0));
        $gettasks=$this->db->get();
        
        return $gettasks->result();

    }
    function getSearchProjects($table,$userid) { 

       
        $this->db->select('Company_projects.*,company_locations.location_name,CONCAT(users.firstName,users.lastName) as verifier_name,company.company_name');
        $this->db->from('Company_projects');
        $this->db->join('users','find_in_set(users.id,Company_projects.project_verifier) AND Company_projects.company_id=users.company_id');
        $this->db->join('company','company.id=users.company_id');
        $this->db->join('company_locations','company_locations.id=Company_projects.project_location');
        $this->db->where(array('users.id'=>$userid));
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
            // $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE quantity_as_per_invoice > quantity_verified group by item_category")->result();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data['good']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_ok > 0 group by item_category")->result();
            $data['damaged']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_damaged > 0 group by item_category")->result();
            $data['scrapped']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_scrapped > 0 group by item_category")->result();
            $data['missing']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_missing > 0 group by item_category")->result();
            $data['shifted']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_shifted > 0 group by item_category")->result();
            $data['notinuse']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(qty_ok) as good_qty,SUM(qty_damaged) as damaged_qty,SUM(qty_scrapped)as scrapped_qty,SUM(qty_missing) as missing_qty,SUM(qty_not_in_use) as notinuse_qty,SUM(qty_shifted) as shifted_qty  FROM ".$tablename." WHERE verification_status='Verified' and qty_not_in_use > 0 group by item_category")->result();
            // $data['remaining']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as items  FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice > quantity_verified group by item_category")->result();
            
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
        }
        
        $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,SUM(quantity_as_per_invoice) as total_qty FROM ".$tablename." group by item_category")->result();
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
        if($verificationstatus==1)
        {
            $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as total_items FROM ".$tablename." group by item_category")->result();
            $data['verified']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' group by item_category")->result();
            $data['verifiedequal']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice=quantity_verified group by item_category")->result();
            
        }
        else if($verificationstatus=='Verified')
        {
            $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' group by item_category")->result();
            $data['verified']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' group by item_category")->result();
            $data['verifiedequal']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Verified' and quantity_as_per_invoice=quantity_verified group by item_category")->result();
            
        }
        else
        {
            $data['all']=$this->db->query("SELECT item_category,SUM(total_item_amount_capitalized) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Not-Verified' group by item_category")->result();
            $data['verified']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified > 0 group by item_category")->result();
            $data['verifiedequal']=$this->db->query("SELECT item_category,SUM((total_item_amount_capitalized/quantity_as_per_invoice) * quantity_verified) as total_amount,count(*) as total_items FROM ".$tablename." WHERE verification_status='Not-Verified' and quantity_verified>0 group by item_category")->result();
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
}