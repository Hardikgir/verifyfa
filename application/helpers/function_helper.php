<?php 
ini_set('allow_url_fopen', 'On');
ini_set('allow_url_fopen', 1);
function get_UserName($id)
{
    $CI =& get_instance();
    $CI->load->database();
    $user_query=$CI->db->select('firstName,lastName')->where('id', $id)->get('users')->result();
    if(count($user_query) > 0)
    {
        return $user_query[0]->firstName.' '.$user_query[0]->lastName;
    }
    else
    {
        return "N/A";
    }

}
function get_CompanyName($id)
{
    $CI =& get_instance();
    $CI->load->database();
    $company_query=$CI->db->select('company_name')->where('id', $id)->get('company')->result();
    if(count($company_query) > 0)
    {
        return $company_query[0]->company_name;
    }
    else
    {
        return "N/A";
    }
    

}
function getTagUntag($projectname)
{
    $CI =& get_instance();
    $CI->load->database();
    $projectinfo=$CI->db->select('project_verifier')->where('project_name',$projectname)->get('company_projects')->result();
    $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
    $new_pattern = array("_", "_", "");
    $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));    
    $projectVerifiers=explode(',',$projectinfo[0]->project_verifier);
    $pverified=array();
    foreach($projectVerifiers as $pv)   
    {
        $rnaverified=$CI->db->select('count(*) as rnaverified')->where(array('tag_status_y_n_na'=>'NA',"verified_by"=>$pv))->get($project_name)->result();
        $rnverified=$CI->db->select('count(*) as rnverified')->where(array('tag_status_y_n_na'=>'N',"verified_by"=>$pv))->get($project_name)->result();
        $ryverified=$CI->db->select('count(*) as ryverified')->where(array('tag_status_y_n_na'=>'Y',"verified_by"=>$pv))->get($project_name)->result();
        $pvr=new stdClass;
        $pvr->user_id=$pv;
        $pvr->usertagged=$ryverified[0]->ryverified;
        $pvr->useruntagged=$rnverified[0]->rnverified;
        $pvr->userunspecified=$rnaverified[0]->rnaverified;
        array_push($pverified,$pvr);
    }
    
    $naquery=$CI->db->select('count(*) as natotal')->where('tag_status_y_n_na', 'NA')->get($project_name)->result();
    $naverified=$CI->db->select('count(*) as naverified')->where(array('tag_status_y_n_na'=>'NA',"verified_by !="=>NULL))->get($project_name)->result();
    $yquery=$CI->db->select('count(*) as ytotal')->where('tag_status_y_n_na', 'Y')->get($project_name)->result();
    $yverified=$CI->db->select('count(*) as yverified')->where(array('tag_status_y_n_na'=>'Y',"verified_by !="=>NULL))->get($project_name)->result();
    $nquery=$CI->db->select('count(*) as ntotal')->where('tag_status_y_n_na', 'N')->get($project_name)->result();
    $nverified=$CI->db->select('count(*) as nverified')->where(array('tag_status_y_n_na'=>'N',"verified_by !="=>NULL))->get($project_name)->result();
    return array("natotal"=>$naquery[0]->natotal,"ytotal"=>$yquery[0]->ytotal,"ntotal"=>$nquery[0]->ntotal,"naverified"=>$naverified[0]->naverified,"yverified"=>$yverified[0]->yverified,"nverified"=>$nverified[0]->nverified,"projectverifiers"=>$pverified);
    
}
function getTagUntagCategories($projectname)
{
    $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
    $new_pattern = array("_", "_", "");
    $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
    $CI =& get_instance();
    $CI->load->database();
    $taggedcategories=$CI->db->select('DISTINCT(item_category)')->where('tag_status_y_n_na', 'Y')->get($project_name)->result();
    $taggeddata=array();
    $totalitems=$CI->db->select('count(*) as totalitems')->get($project_name)->result();
    foreach($taggedcategories as $cat)
    {
        $arr['category']=$cat->item_category;
        $yquery=$CI->db->select('count(*) as ytotal')->where('tag_status_y_n_na', 'Y')->where('item_category',$cat->item_category)->get($project_name)->result();
        $yverified=$CI->db->select('count(*) as yverified')->where(array('tag_status_y_n_na'=>'Y','item_category'=>$cat->item_category,"verified_by !="=>NULL))->get($project_name)->result();
        $ytotalamount=$CI->db->select('sum(total_item_amount_capitalized) as ytotalamount')->where('tag_status_y_n_na', 'Y')->where('item_category',$cat->item_category)->get($project_name)->result();
        $yverifiedamount=$CI->db->select('sum(if((quantity_as_per_invoice - quantity_verified) > 0,(total_item_amount_capitalized/quantity_as_per_invoice)*quantity_verified,total_item_amount_capitalized)) as yverifiedamount')->where(array('tag_status_y_n_na'=>'Y','item_category'=>$cat->item_category,"verified_by !="=>NULL))->get($project_name)->result();
        $arr['totalamount']=$ytotalamount[0]->ytotalamount;
        $arr['verifiedamount']=$yverifiedamount[0]->yverifiedamount;
        $arr['total']=$yquery[0]->ytotal;
        $arr['verified']=$yverified[0]->yverified;
        $arr['amountpercentage']=round(($yverifiedamount[0]->yverifiedamount/$ytotalamount[0]->ytotalamount)*100,2);
        $arr['percentage']=round(($yverified[0]->yverified/$yquery[0]->ytotal)*100,2);
        array_push($taggeddata,$arr);
    }
    $untaggedcategories=$CI->db->select('DISTINCT(item_category)')->where('tag_status_y_n_na', 'N')->get($project_name)->result();
    $untaggeddata=array();
    foreach($untaggedcategories as $cat)
    {
        $utarr['category']=$cat->item_category;
        $yquery=$CI->db->select('count(*) as ytotal')->where('tag_status_y_n_na', 'N')->where('item_category',$cat->item_category)->get($project_name)->result();
        $yverified=$CI->db->select('count(*) as yverified')->where(array('tag_status_y_n_na'=>'N','item_category'=>$cat->item_category,"verified_by !="=>NULL))->get($project_name)->result();
        $ytotalamount=$CI->db->select('sum(total_item_amount_capitalized) as ytotalamount')->where('tag_status_y_n_na', 'N')->where('item_category',$cat->item_category)->get($project_name)->result();
        $yverifiedamount=$CI->db->select('sum(if((quantity_as_per_invoice - quantity_verified) > 0,(total_item_amount_capitalized/quantity_as_per_invoice)*quantity_verified,total_item_amount_capitalized)) as yverifiedamount')->where(array('tag_status_y_n_na'=>'N','item_category'=>$cat->item_category,"verified_by !="=>NULL))->get($project_name)->result();
        $utarr['totalamount']=$ytotalamount[0]->ytotalamount;
        $utarr['verifiedamount']=$yverifiedamount[0]->yverifiedamount;
        $utarr['total']=$yquery[0]->ytotal;
        $utarr['verified']=$yverified[0]->yverified;
        $utarr['percentage']=round(($yverified[0]->yverified/$yquery[0]->ytotal)*100,2);
        $utarr['amountpercentage']=round(($yverifiedamount[0]->yverifiedamount/$ytotalamount[0]->ytotalamount)*100,2);
        array_push($untaggeddata,$utarr);
    }
    $nacategories=$CI->db->select('DISTINCT(item_category)')->where('tag_status_y_n_na', 'NA')->get($project_name)->result();
    $nadata=array();
    foreach($nacategories as $cat)
    {
        $narr['category']=$cat->item_category;
        $yquery=$CI->db->select('count(*) as ytotal')->where('tag_status_y_n_na', 'NA')->where('item_category',$cat->item_category)->get($project_name)->result();
        $yverified=$CI->db->select('count(*) as yverified')->where(array('tag_status_y_n_na'=>'NA','item_category'=>$cat->item_category,"verified_by !="=>NULL))->get($project_name)->result();
        $ytotalamount=$CI->db->select('sum(total_item_amount_capitalized) as ytotalamount')->where('tag_status_y_n_na', 'NA')->where('item_category',$cat->item_category)->get($project_name)->result();
        $yverifiedamount=$CI->db->select('sum(if((quantity_as_per_invoice - quantity_verified) > 0,(total_item_amount_capitalized/quantity_as_per_invoice)*quantity_verified,total_item_amount_capitalized)) as yverifiedamount')->where(array('tag_status_y_n_na'=>'NA','item_category'=>$cat->item_category,"verified_by !="=>NULL))->get($project_name)->result();
        $narr['totalamount']=$ytotalamount[0]->ytotalamount;
        $narr['verifiedamount']=$yverifiedamount[0]->yverifiedamount;
        $narr['total']=$yquery[0]->ytotal;
        $narr['verified']=$yverified[0]->yverified;
        $narr['percentage']=round(($yverified[0]->yverified/$yquery[0]->ytotal)*100,2);
        $narr['amountpercentage']=round(($yverifiedamount[0]->yverifiedamount/$ytotalamount[0]->ytotalamount)*100,2);
        array_push($nadata,$narr);
    }
    return array("tagged"=>$taggeddata,"untagged"=>$untaggeddata,"unspecified"=>$nadata,"totalitems"=>$totalitems[0]->totalitems);
}
function getCategories($projectname)
{
    $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
    $new_pattern = array("_", "_", "");
    $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($projectname)));
    $CI =& get_instance();
    $CI->load->database();
    $categories=$CI->db->select('DISTINCT(item_category)')->get($project_name)->result();
    foreach($categories as $cat)
    {
        $yquery=$CI->db->select('SUM(total_item_amount_capitalized) as amount,count(*) as catitems')->where('item_category',$cat->item_category)->get($project_name)->result();
        $cat->amount=$yquery[0]->amount;
        $cat->catitems=$yquery[0]->catitems;
    }
    return array("categories"=>$categories);
}
function getmoney_format($num) {
    $explrestunits = "" ;
    $explode=explode('.',$num);

    if(strlen($explode[0])>3) {
        $lastthree = substr($explode[0], strlen($explode[0])-3, strlen($explode[0]));
        $restunits = substr($explode[0], 0, strlen($explode[0])-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $explode[0];
    }
    if(count($explode)>1)
        return $thecash.'.'.$explode[1]; // writes the final format where $currency is the currency symbol.
    else
        return $thecash;
}



function get_diff_twodate($date_to){

    

    $now1 = date("Y-m-d"); // or your date as well
    $your_date = strtotime($date_to);
    $now=strtotime($now1);
    $datediff = $your_date - $now;
   return $expirydays= round($datediff / (60 * 60 * 24)). ' Days';

//     // $diff = abs(strtotime($date2)-strtotime($date1));

//     // $years = floor($diff / (365*60*60*24));

//     // $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

//     // $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
//     // if($years < 0 && $months<0 && $days<0){
//     //     $timeremainng= "Plan Expired";
//     // }else{

//     // $years1='';
//     // $months1='';
//     // $days1='';
//     // if($years > 0){
//     // $years1= $years.' Years ';
//     // }
//     // if($months > 0){
//     //     $months1= $months.' Month ';
//     // } 
//     // if($days > 0){
//     //     $days1= $days.' Days ';
//     // }
//     // $timeremainng=  $years1.$months1.$days1;

// }
// return $timeremainng=  $years1.$months1.$days1;;
}

function get_plan_row($plan_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('subscription_plan');
    $CI->db->where('id',$plan_id);
    $query=$CI->db->get();
    return $query->row();
}

function get_user_plan_row($user_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('registered_user_plan');
    $CI->db->where('id',$user_id);
    $query=$CI->db->get();
    return $query->row();
}

function get_total_payment_user($user_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select_sum('payment_amount');
    $CI->db->where('regiistered_user_id',$user_id);
    $query = $CI->db->get('registred_users_payment');
    $result=$query->row();
    return $result->payment_amount;
}

function get_total_payment_discount_user($user_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select_sum('discount_credits');
    $CI->db->where('regiistered_user_id',$user_id);
    $query = $CI->db->get('registred_users_payment');
    $result=$query->row();
    return $result->discount_credits;
}
function get_total_payment_charges_user($user_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select_sum('paymentother_charge');
    $CI->db->where('regiistered_user_id',$user_id);
    $query = $CI->db->get('registred_users_payment');
    $result=$query->row();
    return $result->paymentother_charge;
}

function get_create_entity_plan($user_id){
    $CI =& get_instance();
    $CI->load->database();

    $CI->db->select('*');
    $CI->db->from('registred_users');
    $CI->db->where('id',$user_id);
    $query = $CI->db->get();
    $row_registered_user = $query->row();
    $plan_id = $row_registered_user->plan_id;

    $CI->db->select('*');
    $CI->db->from('subscription_plan');
    $CI->db->where('id',$plan_id);
    $query1 = $CI->db->get();
    $plan_row = $query1->row();
    return $plan_row->allowed_entities_no;
}

function get_company_row($company_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('company');
    $CI->db->where('id',$company_id);
    $query = $CI->db->get();
    return $query->row();
}



function create_user_count_check($user_id){
    $CI =& get_instance();
    $CI->load->database();

    $CI->db->select('*');
    $CI->db->from('registred_users');
    $CI->db->where('id',$user_id);
    $query = $CI->db->get();
    $row_registered_user = $query->row();

    $plan_id = $row_registered_user->plan_id;
    $CI->db->select('*');
    $CI->db->from('subscription_plan');
    $CI->db->where('id',$plan_id);
    $query1 = $CI->db->get();
    $plan_row = $query1->row();
    
    return $plan_row->user_number_register;
}



function get_user_row($user_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('users');
    $CI->db->where('id',$user_id);
    $query = $CI->db->get();
    return $query->row();
}


function get_location_row($location_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('company_locations');
    $CI->db->where('id',$location_id);
    $query = $CI->db->get();
    return $query->row();
}


function get_user_role_cnt_managers($user_id,$entity_code){
    $CI =& get_instance();
    $CI->load->database();

       $role = array(0);
       
        $query=$CI->db->query("select * from user_role where  entity_code='".$entity_code."' AND user_id='".$user_id."'  AND FIND_IN_SET(0,user_role)");
        return $query->num_rows();
}


function user_role_subadmin_cnt($user_id,$entity_code){
    $CI =& get_instance();
    $CI->load->database();

       $role = array(0);
       
        $query=$CI->db->query("select * from user_role where  entity_code='".$entity_code."' AND user_id='".$user_id."'  AND FIND_IN_SET(4,user_role)");
        return $query->num_rows();
}

function get_user_role_cnt_admin($user_id,$entity_code){
    $CI =& get_instance();
    $CI->load->database();

       $role = array(5);
       
        $query=$CI->db->query("select * from user_role where  entity_code='".$entity_code."' AND user_id='".$user_id."' AND FIND_IN_SET(5,user_role)");
        return $query->num_rows();
}


function get_all_notification($entity_code){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('notification');
    $CI->db->where('entity_code',$entity_code);
    $CI->db->order_by('id','DESC');
    $query = $CI->db->get();
    return $query->result();
}

function check_main_notificationread($user_id,$notification_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('notification_read');
    $CI->db->where('user_id',$user_id);
    $CI->db->where('notification_id',$notification_id);
    $query = $CI->db->get();
    return $query->num_rows();
}


function get_all_reply($notification_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('notification_reply');
    $CI->db->where('notification_id',$notification_id);
    $CI->db->order_by('id','DESC');

    $query = $CI->db->get();
    return $query->result();
}

function get_cntreply_user($user_id,$message_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('notification_read');
    $CI->db->where('user_id',$user_id);
    $CI->db->where('reply_message_id',$message_id);
    $query = $CI->db->get();
    return $query->num_rows();
}

function get_department_row($dept_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('department');
    $CI->db->where('id',$dept_id);
    $query = $CI->db->get();
    return $query->row();

}

function countuserrole($user_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('user_role');
    $CI->db->where('user_id',$user_id);
    $query = $CI->db->get();
    return $query->num_rows();

}


function registered_user_row($id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('registred_users');
    $CI->db->where('id',$id);
    $query = $CI->db->get();
    return $query->row();

}

function check_company_projects($company_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('company_projects');
    $CI->db->where('company_id',$company_id);
    $query = $CI->db->get();
    return $query->num_rows();

}

function check_location_projects($location_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('company_projects');
    $CI->db->where('project_location',$location_id);
    $query = $CI->db->get();
    return $query->num_rows();

}



function get_textday(){
    date_default_timezone_set('Asia/Kolkata');

    $messg= "";
    $timeOfDay = date('a');
    if($timeOfDay == 'am'){
        return 'Good Morning';
    }else{
        return 'Good Afternoon';
    }
}


function Count_user_role(){
    $CI =& get_instance();
    $user_id=$CI->user_id;
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('user_role');
    $CI->db->where('user_id',$user_id);
    $query = $CI->db->get();
    return $query->num_rows();
}
function get_user_role_company($user_id){
    $CI =& get_instance();


    $role = array(0);
    $query=$CI->db->query("select * from user_role where  user_id =".$user_id." and FIND_IN_SET(4,user_role) GROUP BY company_id");
    //  echo $CI->db->last_query();
    $data= $query->result();
    $com_id="";
    // $seprator="";
    // $cnt=count($data);
    // if($cnt > 1){
    //     $seprator=",";
    // }
    foreach($data as $row){
        $com_id .=$row->company_id.",";
    }
    $com_id= substr_replace($com_id,"",-1);

    return $com_id;
}


function get_user_role_company_all($user_id){
    $CI =& get_instance();
    $query=$CI->db->query("select * from user_role where  user_id =".$user_id."");
    //  echo $CI->db->last_query();
    $data= $query->result();
    return $data;
}
function get_user_role_location($user_id){
    $CI =& get_instance();
    $query=$CI->db->query("select * from user_role where  user_id =".$user_id." and FIND_IN_SET(4,user_role) GROUP BY location_id");

    $data= $query->result();
    $loc_id="";
    // $seprator="";
    // $cnt=count($data);
    // if($cnt>1){
    //     $seprator=",";
    // }
    foreach($data as $row){
        $loc_id .=$row->location_id.",";;
    }
    $loc_id= substr_replace($loc_id,"",-1);
    return $loc_id;
}

function get_user_role_row($comid,$location_id){
    $CI =& get_instance();
    $CI->load->database();

        $role = array(0);
        $query=$CI->db->query("select * from user_role where  registered_user_id =".$CI->admin_registered_user_id." and company_id IN(".$comid.") and location_id IN(".$location_id.")");
        //  echo $CI->db->last_query();
        return $query->result();
}


function get_user_role_rowsubadmin($user_id,$comid,$location_id){
    $CI =& get_instance();
    $CI->load->database();

        $role = array(0);
        $query=$CI->db->query("select * from user_role where user_id=".$user_id." and registered_user_id =".$CI->admin_registered_user_id." and company_id =".$comid." and location_id = ".$location_id." ");
        //  echo $CI->db->last_query();
        return $query->result();
}

function get_location_all_user($company_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('company_locations');
    $CI->db->where('company_id',$company_id);
    $query = $CI->db->get();
    return $query->result();
}


function check_user_projects($userid){
    $CI =& get_instance();
    $CI->load->database();
    $query=$CI->db->query("select * from company_projects where  (project_verifier='".$userid."'  || process_owner='".$userid."' || item_owner='".$userid."' || manager='".$userid."') ");
    //  $CI->db->last_query();
    return $query->num_rows();
}

function check_proile_update($user_id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select('*');
    $CI->db->from('users');
    $CI->db->where('id',$user_id);
    $query = $CI->db->get();
    return $query->row();
}


function check_location_assigned($company_id,$location_id,$user_id){
    $CI =& get_instance();
    $CI->load->database();       
    $query=$CI->db->query("select * from user_role where  company_id='".$company_id."' AND  location_id='".$location_id."' AND user_id='".$user_id."' AND FIND_IN_SET(0,user_role)");
    return $query->num_rows();
}

function check_verifier_count($project_id,$user_id){
    $CI =& get_instance();
    $CI->load->database();       
    $query=$CI->db->query("SELECT * FROM `company_projects` where id='".$project_id."' and FIND_IN_SET($user_id,project_verifier)
    ");
     $CI->db->last_query();
    return $query->num_rows();
}

function check_itemowner_count($project_id,$user_id){
    $CI =& get_instance();
    $CI->load->database();       
    $query=$CI->db->query("SELECT * FROM `company_projects` where id='".$project_id."' and FIND_IN_SET($user_id,item_owner)");
    return $query->num_rows();
}

function check_process_owner_count($project_id,$user_id){
    $CI =& get_instance();
    $CI->load->database();       
    $query=$CI->db->query("SELECT * FROM `company_projects` where id='".$project_id."' and  FIND_IN_SET( $user_id,process_owner)");
    return $query->num_rows();
}

function check_manager_count($project_id,$user_id){
    $CI =& get_instance();
    $CI->load->database();       
    $query=$CI->db->query("SELECT * FROM `company_projects` where id='".$project_id."' and FIND_IN_SET( $user_id,manager)");
    return $query->num_rows();
}


// function get_plan_row($plan_id){
//     $CI =& get_instance();
//     $CI->load->database();       
//     $query=$CI->db->query("SELECT * FROM `subscription_plan` where id='".$plan_id."'");
//     return $query->rows();
// }


function get_project_row($project_id){
    $CI =& get_instance();
    $CI->load->database();       
    $query=$CI->db->query("SELECT * FROM `company_projects` where id='".$project_id."'");
     $CI->db->last_query();
    return $query->row();
}


?>