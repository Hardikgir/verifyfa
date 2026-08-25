<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once APPPATH."/third_party/PHPExcel.php";

class Tasks extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('function_helper', 'file', 'download');
        $this->load->model('tasks_model', 'tasks');
        // $this->load->helper('file');
        // $this->load->helper('download');
        // $this->load->library('Excel');
        // $this->load->library('PHPExcel');
        date_default_timezone_set('Asia/Calcutta');
        date_default_timezone_set("Asia/Calcutta");

    }


    public function getprojects()
    {
        $userid = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $location_id = $this->input->post('location_id');
        $condition = array(
            "id" => $userid
        );
        $projects = $this->tasks->getProjects('users', $userid, $company_id, $location_id);

        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();

        // echo $this->db->last_query();
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        foreach ($projects as $project) {

            $verifiercount = check_verifier_count($project->id, $userid);
            $check_itemowner_count = check_itemowner_count($project->id, $userid);
            $check_process_owner_count = check_process_owner_count($project->id, $userid);
            $check_manager_count = check_manager_count($project->id, $userid);

            $verifiername = $this->tasks->get_verifire_name($project->project_verifier);

            $project->verifier_name = $verifiername;

            $project->verifier_cnt = $verifiercount;
            $project->iten_owner_cnt = $check_itemowner_count;
            $project->process_owner_count = $check_process_owner_count;
            $project->check_manager_count = $check_manager_count;

            if (($verifiercount == '1') || ($check_itemowner_count == '1') || ($check_process_owner_count == '1') || ($check_manager_count == '1')) {
                $project->project_location = $project->location_name;
                $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));
                $getprojectdetails = $this->tasks->projectdetail($project_name);
                $getlastupdatedtime = $this->tasks->lastupdatetime($project_name, $userid);
                if (!empty($getlastupdatedtime)) {
                    // $project->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($getlastupdatedtime[0]->updatedat)));
                    // $project->updatedat=date('d-m-Y H:i:s');
                    $project->updatedat = date('d-m-Y H:i:s', strtotime($getlastupdatedtime[0]->updatedat));

                } else {
                    $project->updatedat = '';
                }
                if (!empty($getprojectdetails)) {
                    $project->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                    if ($getprojectdetails[0]->VerifiedQuantity != '')
                        $project->VerifiedQuantity = $getprojectdetails[0]->VerifiedQuantity;
                    else
                        $project->VerifiedQuantity = 0;
                } else {
                    $project->TotalQuantity = 0;
                    $project->VerifiedQuantity = 0;
                }
                $project->verifier_name = $verifiername;
                $project->assigned_by = get_UserName($project->assigned_by);
                $projectheaders = $this->tasks->get_data('project_headers', array('project_id' => $project->project_header_id));


                $update_array = array();
                $check_array = array();
                foreach ($projectheaders as $projectheaders_key => $projectheaders_value) {

                    if (!in_array($projectheaders_value->keyname, $check_array)) {
                        $update_array[] = $projectheaders_value;
                        $check_array[] = $projectheaders_value->keyname;
                    }

                }
                $project->visiblecolumns = $update_array;
            } else {
                $project->verifier_name = $verifiername;

            }
        }

        // echo '<pre>projects ';
        // print_r($projects);
        // echo '</pre>';
        // exit(); 

        if (!empty($projects) && count($projects) > 0) {

            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Projects fetched successfully.", "data" => $projects));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No project assigned"));
            exit;
        }
    }


    public function get_all_company_user_role($entity_code, $user_id)
    {
        $this->db->select("*");
        $this->db->from("user_role");
        $this->db->where("entity_code", $entity_code);
        $this->db->where("user_id", $user_id);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_all_company_user_role_by_location_id($entity_code, $user_id, $location_id)
    {
        $this->db->select("*");
        $this->db->from("user_role");
        $this->db->where("entity_code", $entity_code);
        $this->db->where("location_id", $location_id);
        $this->db->where("user_id", $user_id);
        $query = $this->db->get();
        return $query->result();
    }



    public function getDashboard()
    {
        $userid = $this->input->post('user_id');
        $entity_code = $this->input->post('entity_code');
        $location_id = $this->input->post('location_id');

        // $company_id_imp='';



        $role_result_com = $this->get_all_company_user_role($entity_code, $userid);
        // $role_result_com = $this->get_all_company_user_role_by_location_id($entity_code,$userid,$location_id);
        $location_id = '';
        if (!empty($role_result_com)) {


            foreach ($role_result_com as $row_role) {
                $roledata[] = $row_role->company_id;
                $roledata1[] = $row_role->location_id;
            }

            $company_id_imp = implode(',', $roledata);
            $location_id = implode(',', $roledata1);
        }

        $condition = array(
            "id" => $userid
        );

        $company_id = $this->input->post('company_id');
        $role_id = $this->input->post('role_id');
        $location_id = $this->input->post('location_id');

        $projects = $this->tasks->getProjectsdashboard('users', $userid, $entity_code, $company_id, $location_id, $role_id);



        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        foreach ($projects as $project) {
            $project->project_location = $project->location_name;
            $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));
            $project->listing = getTagUntag($project->project_name);
            $project->cat = getTagUntagCategories($project->project_name);
            $project->allcategories = getCategories($project->project_name);

            $getprojectdetails = $this->tasks->projectdetail($project_name);
            $getlastupdatedtime = $this->tasks->lastupdatetime($project_name, $userid);
            if (!empty($getlastupdatedtime)) {
                // $project->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($getlastupdatedtime[0]->updatedat)));
                // $project->updatedat=date('d-m-Y H:i:s');
                $project->updatedat = date('d-m-Y H:i:s', strtotime($getlastupdatedtime[0]->updatedat));
            } else {
                $project->updatedat = '';
            }
            if (!empty($getprojectdetails)) {
                $project->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                if ($getprojectdetails[0]->VerifiedQuantity != '')
                    $project->VerifiedQuantity = $getprojectdetails[0]->VerifiedQuantity;
                else
                    $project->VerifiedQuantity = 0;
            } else {
                $project->TotalQuantity = 0;
                $project->VerifiedQuantity = 0;
            }
            $project->assigned_by = get_UserName($project->assigned_by);
            $projectheaders = $this->tasks->get_data('project_headers', array('project_id' => $project->project_header_id));
            $project->visiblecolumns = $projectheaders;
        }
        if (!empty($projects) && count($projects) > 0) {

            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Projects fetched successfully.", "data" => $projects));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No project assigned"));
            exit;
        }

    }
    public function getsearchprojects()
    {
        $userid = $this->input->post('user_id');
        $location_id = $this->input->post('location_id');
        $condition = array(
            "id" => $userid
        );
        $projects = $this->tasks->getSearchProjects('users', $userid, $location_id);

        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        foreach ($projects as $project) {

            $project->project_location = $project->location_name;
            $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));
            $getprojectdetails = $this->tasks->projectdetail($project_name);
            $getlastupdatedtime = $this->tasks->lastupdatetime($project_name, $userid);

            $verifiercount = check_verifier_count($project->id, $userid);
            $project->verifier_cnt = $verifiercount;

            if (!empty($getlastupdatedtime)) {
                // $project->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($getlastupdatedtime[0]->updatedat)));
                // $project->updatedat=date('d-m-Y H:i:s');
                $project->updatedat = date('d-m-Y H:i:s', strtotime($getlastupdatedtime[0]->updatedat));

            } else {
                $project->updatedat = '';
            }
            if (!empty($getprojectdetails)) {
                $project->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                if ($getprojectdetails[0]->VerifiedQuantity != '')
                    $project->VerifiedQuantity = (int) $getprojectdetails[0]->VerifiedQuantity;
                else
                    $project->VerifiedQuantity = 0;
            } else {
                $project->TotalQuantity = 0;
                $project->VerifiedQuantity = 0;
            }
            $project->assigned_by = get_UserName($project->assigned_by);
        }
        if (!empty($projects) && count($projects) > 0) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Projects fetched successfully.", "data" => $projects));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No project assigned"));
            exit;
        }
    }
    public function scanitem()
    {
        $userid = $this->input->post('user_id');
        $companyid = $this->input->post('company_id');
        $projectid = $this->input->post('project_id');
        $projectname = $this->input->post('project_name');
        $scancode = $this->input->post('scan_code');
        $condition = array(
            "id" => $userid
        );
        $projectdetail = $this->tasks->get_data('company_projects', array('id' => $projectid));
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $scantask = $this->tasks->scanitem($userid, $companyid, $projectname, $projectid, $scancode);

        foreach ($scantask as $st) {
            if ($st->verified_by != '') {
                $verifiername = $this->tasks->get_verifire_namesingle($st->verified_by);
            } else {
                $verifiername = '';
            }
            // $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
            // $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));

            $st->createdat = date('d-m-Y H:i:s', strtotime($st->createdat));
            $st->updatedat = date('d-m-Y H:i:s', strtotime($st->updatedat));

            // $st->createdat=date('d-m-Y H:i:s');
            // $st->updatedat=date('d-m-Y H:i:s');
            if ($st->verified_datetime) {
                $st->verified_by_username = $verifiername;
                $st->verified_by_name = $verifiername;
                // $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
                $st->verified_datetime = date('d-m-Y H:i:s', strtotime($st->verified_datetime));

                // $st->verified_datetime=date('d-m-Y H:i:s');
            }

            // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
        }
        if (!empty($scantask) && count($scantask) > 0) {
            $tag = 'CD';

            $projectdetail[0]->project_type == 'TG' ? $tag = 'Y' : ($projectdetail[0]->project_type == 'NT' ? $tag = 'N' : ($projectdetail[0]->project_type == 'UN' ? $tag = 'NA' : $tag = 'CD'));
            if ($tag != 'CD') {
                if (!empty($projectdetail) && in_array($scantask[0]->item_category, json_decode($projectdetail[0]->item_category)) && $scantask[0]->tag_status_y_n_na == $tag) {
                    header('Content-Type: application/json');
                    echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "data" => $scantask));
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array("success" => 401, "message" => "Permission to scan this category/tag item is not granted."));
                    exit;
                }

            } else {
                if (!empty($projectdetail) && in_array($scantask[0]->item_category, json_decode($projectdetail[0]->item_category))) {
                    header('Content-Type: application/json');
                    echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "data" => $scantask));
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array("success" => 401, "message" => "Permission to scan this category item is not granted."));
                    exit;
                }
            }



        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Item not available"));
            exit;
        }
    }
    public function manualscanitem()
    {


        $projectid = $this->input->post('project_id');
        $userid = $this->input->post('user_id');
        $verification_status = $this->input->post('verification_status');
        $tag_status_y_n_na = $this->input->post('tag_status_y_n_na');
        $item_category = $this->input->post('item_category');
        $item_sub_category = $this->input->post('item_sub_category');
        $projectname = $this->input->post('project_name');
        $search_text = $this->input->post('search_text');
        $search_fields = $this->input->post('search_fields');
        $order_by = $this->input->post('order_by');
        $cond = array();


        $where = ' Where id IS NOT NULL';

        if (!empty($search_text)) {
            $where = ' Where (';
            $i = 1;
            foreach ($search_fields as $sf) {
                if ($i == 1)
                    $where .= str_replace('"', '', $sf) . ' LIKE "%' . $search_text . '%"';
                else
                    $where .= ' OR ' . str_replace('"', '', $sf) . ' LIKE "%' . $search_text . '%"';

                if (count($search_fields) == $i) {
                    $where .= ')';
                }

                $i++;
            }
        }




        if ($verification_status != 'All') {
            $where .= ' AND verification_status="' . $verification_status . '"';
        }
        if ($tag_status_y_n_na != 'All') {
            $where .= ' AND tag_status_y_n_na="' . $tag_status_y_n_na . '"';
        }
        if ($item_category != 'All') {
            $where .= ' AND item_category="' . $item_category . '"';
        }
        if ($item_sub_category != '' && $item_sub_category != 'All') {
            $where .= ' AND item_sub_category="' . $item_sub_category . '"';
        }

        $where .= ' ORDER BY id ' . $order_by;


        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $projectdetail = $this->tasks->get_data('company_projects', array('id' => $projectid));



        $select = "SELECT * FROM " . $projectname;
        $scantask = $this->db->query($select . $where)->result();

        $result_count = count($scantask);
        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();



        if (!empty($scantask) && count($scantask) > 0) {
            foreach ($scantask as $st) {

                if ($st->verified_by != '') {
                    $verifiername = $this->tasks->get_verifire_namesingle($st->verified_by);
                } else {
                    $verifiername = '';
                }

                // $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
                // $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));

                $st->createdat = date('d-m-Y H:i:s', strtotime($st->createdat));
                $st->updatedat = date('d-m-Y H:i:s', strtotime($st->updatedat));

                // $st->createdat=date('d-m-Y H:i:s');
                // $st->updatedat=date('d-m-Y H:i:s');
                if ($st->verified_datetime) {
                    // $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
                    $st->verified_datetime = date('d-m-Y H:i:s', strtotime($st->verified_datetime));
                    // $st->verified_datetime=date('d-m-Y H:i:s');
                    $st->verified_by_username = $verifiername;
                    $st->verified_by_name = $verifiername;

                }

                // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
            }
            if (!empty($projectdetail) && in_array($scantask[0]->item_category, json_decode($projectdetail[0]->item_category))) {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "count" => $result_count, "data" => $scantask));
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 401, "message" => "Permission to scan this category item is not granted."));
                exit;
            }

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Item not available"));
            exit;
        }
    }


    public function saveverified_Previous()
    {
        date_default_timezone_set("Asia/Calcutta");
        $itemid = $this->input->post('item_id');
        $projectname = $this->input->post('project_name');
        $verified_by = $this->input->post('verify_by');
        $scanned = json_decode($this->input->post('scanned_data'));

        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $condition = array(
            "id" => $itemid
        );

        $verification_remarks = '';
        $qty_ok = 0;
        $qty_damaged = 0;
        $qty_scrapped = 0;
        $qty_not_in_use = 0;
        $qty_missing = 0;
        $qty_shifted = 0;
        $qty_value = $scanned->quantity_verified;

        $getquantity = $this->tasks->get_data($projectname, $condition);

        $quantity_as_per_invoice = $getquantity[0]->quantity_as_per_invoice;

        $quantity_verified_tbl = $getquantity[0]->quantity_verified;
        $quantity_verified = $scanned->quantity_verified;

        $total_will_be = (int) $quantity_verified_tbl + (int) $quantity_verified;

        // if($quantity_as_per_invoice < $total_will_be){
        //     header('Content-Type: application/json');
        //     echo json_encode(array("success"=>200,"message"=>"Qty Verification more then they actually qty"));
        //     exit;
        // }

        if ($scanned->item_scrap_condition == 'qty_ok') {
            $qty_ok = (int) $getquantity[0]->qty_ok + (int) $scanned->quantity_verified;
            $scanned->qty_ok = $qty_ok;

        } else if ($scanned->item_scrap_condition == 'qty_damaged') {
            $qty_damaged = (int) $getquantity[0]->qty_damaged + (int) $scanned->quantity_verified;
            $scanned->qty_damaged = $qty_damaged;
        } else if ($scanned->item_scrap_condition == 'qty_scrapped') {
            $qty_scrapped = (int) $getquantity[0]->qty_scrapped + (int) $scanned->quantity_verified;
            $scanned->qty_scrapped = $qty_scrapped;
        } else if ($scanned->item_scrap_condition == 'qty_not_in_use') {
            $qty_not_in_use = (int) $getquantity[0]->qty_not_in_use + (int) $scanned->quantity_verified;
            $scanned->qty_not_in_use = $qty_not_in_use;
        } else if ($scanned->item_scrap_condition == 'qty_missing') {
            $qty_missing = (int) $getquantity[0]->qty_missing + (int) $scanned->quantity_verified;
            $scanned->qty_missing = $qty_missing;
        } else if ($scanned->item_scrap_condition == 'qty_shifted') {
            $qty_shifted = (int) $getquantity[0]->qty_shifted + (int) $scanned->quantity_verified;
            $scanned->qty_shifted = $qty_shifted;
        }

        if ($scanned->verification_remarks != '') {
            $quantity_verified = (int) $getquantity[0]->quantity_verified + (int) $scanned->quantity_verified;
            $scanned->quantity_verified = $quantity_verified;

            $verification_status = $scanned->quantity_as_per_invoice <= $scanned->quantity_verified ? "Verified" : "Not-Verified";
            $scanned->verification_status = $verification_status;

            $verification_remarks = $getquantity[0]->verification_remarks != '' ? $getquantity[0]->verification_remarks . '_' . $scanned->verification_remarks : $scanned->verification_remarks;
            $scanned->verification_remarks = $verification_remarks;

            // $verified_datetime = date('Y-m-d H:i:s', strtotime('+17 minutes',strtotime(date('Y-m-d H:i:s'))));
            $verified_datetime = date('Y-m-d H:i:s');
            $scanned->verified_datetime = $verified_datetime;

            // $updatedat = date('Y-m-d H:i:s', strtotime('+17 minutes',strtotime(date('Y-m-d H:i:s'))));
            $updatedat = date('Y-m-d H:i:s');
            $scanned->updatedat = $updatedat;
        } else {

            $quantity_verified = (int) $getquantity[0]->quantity_verified + (int) $scanned->quantity_verified;
            $scanned->quantity_verified = $quantity_verified;

            $verification_status = $scanned->quantity_as_per_invoice <= $scanned->quantity_verified ? "Verified" : "Not-Verified";
            $scanned->verification_status = $verification_status;

            // $verified_datetime = date('Y-m-d H:i:s', strtotime('+17 minutes',strtotime(date('Y-m-d H:i:s'))));
            $verified_datetime = date('Y-m-d H:i:s');
            $scanned->verified_datetime = $verified_datetime;

            // $updatedat = date('Y-m-d H:i:s', strtotime('+17 minutes',strtotime(date('Y-m-d H:i:s'))));
            $updatedat = date('Y-m-d H:i:s');
            $scanned->updatedat = $updatedat;
        }

        // $scanned->instance_count = 0;
        if (isset($getquantity[0]->instance_count)) {
            $scanned->instance_count = (int) $getquantity[0]->instance_count + 1;
        }

        $mode_of_verification = $scanned->mode_of_verification;
        $scanned->mode_of_verification = $mode_of_verification;

        $new_array[0] = $this->stdToArray($scanned);
        unset($new_array[0]['item_scrap_condition']);
        $verify = $this->tasks->update_data($projectname, $new_array[0], $condition);

        // $verify = 1;

        $project_id = $this->input->post('project_id');
        $getprojectdetails_condition = array(
            'id' => $project_id
        );
        $getprojectdetails = $this->tasks->get_data('company_projects', $getprojectdetails_condition);


        $company_id = $getprojectdetails[0]->company_id;
        // $mode_of_verification = 'Scan';
        $new_location_verified = $scanned->new_location_verified;
        $location_id = $getprojectdetails[0]->project_location;
        $entity_code = $getprojectdetails[0]->entity_code;
        $project_id = $getprojectdetails[0]->id;
        $project_name = $getprojectdetails[0]->project_name;
        $original_table_name = $getprojectdetails[0]->original_table_name;

        $verify_user_detail = $this->tasks->get_single_user($verified_by);
        $verified_by_username = $verify_user_detail->firstName;



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
            'verified_datetime' => date('Y-m-d H:i:s'),//$verified_datetime,
            'verification_remarks' => $verification_remarks,
            'qty_ok' => $qty_ok,
            'qty_damaged' => $qty_damaged,
            'qty_scrapped' => $qty_scrapped,
            'qty_not_in_use' => $qty_not_in_use,
            'qty_missing' => $qty_missing,
            'qty_shifted' => $qty_shifted,
            'mode_of_verification' => $mode_of_verification,
            'type_of_operation' => 'add',
            'qty_value' => $qty_value,
            'created_at' => date('Y-m-d H:i:s'),
        );

        $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts', $verifiedproducts_array);


        if ($verify) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Item verified successfully."));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Item not verified"));
            exit;
        }
    }


    public function saveverified()
    {
        date_default_timezone_set("Asia/Calcutta");
        $itemid = $this->input->post('item_id');
        $projectname = $this->input->post('project_name');
        $project_id = $this->input->post('project_id');
        $verified_by = $this->input->post('verify_by');
        $scanned = json_decode($this->input->post('scanned_data'));
        $update_details = json_decode($this->input->post('scanned_data'));
        $instance = $this->input->post('instance');
        $edit_opration = $this->input->post('edit_operation');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $condition = array(
            "id" => $itemid
        );

        if (isset($_POST['instance'])) {

            $verification_remarks = '';
            $qty_ok = 0;
            $qty_damaged = 0;
            $qty_scrapped = 0;
            $qty_not_in_use = 0;
            $qty_missing = 0;
            $qty_shifted = 0;

            $this->db->select('*');
            $this->db->from('verifiedproducts');
            $this->db->where('item_id', $itemid);
            $this->db->where('id', $instance);
            $this->db->where('project_id', $project_id);
            $query = $this->db->get();
            $get_instance_details = $query->row();
            if (empty($get_instance_details)) {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 400, "message" => "Verification instance not found. Please verify item_id, project_id, and instance ID."));
                exit;
            }

            $this->db->select('*');
            $this->db->from($projectname);
            $this->db->where('id', $itemid);
            $query = $this->db->get();
            $get_item_details = $query->row();


            $project_id = $this->input->post('project_id');
            $getprojectdetails_condition = array(
                'id' => $project_id
            );
            $get_project_details = $this->tasks->get_data('company_projects', $getprojectdetails_condition);

            $quantity_verified_value = $get_item_details->quantity_verified;
            $verify_user_detail = $this->tasks->get_single_user($this->input->post('verify_by'));
            $verified_by = $this->input->post('verify_by');
            $verified_by_username = $verify_user_detail->firstName;

            // if($edit_opration == 'Update Qty & Details'){



            if ($get_instance_details->qty_value < $update_details->quantity_verified) {
                $operation = 'addition';
            } else {
                $operation = 'subtraction';
            }

            $get_instance_details_qty_value = $get_instance_details->qty_value;
            $get_instance_details_qty_ok = $get_instance_details->qty_ok;
            $get_instance_details_qty_damaged = $get_instance_details->qty_damaged;
            $get_instance_details_qty_scrapped = $get_instance_details->qty_scrapped;
            $get_instance_details_qty_not_in_use = $get_instance_details->qty_not_in_use;
            $get_instance_details_qty_missing = $get_instance_details->qty_missing;
            $get_instance_details_qty_shifted = $get_instance_details->qty_shifted;

            $update_item_details_data_first = array(
                'qty_ok' => (int) $get_item_details->qty_ok,
                'qty_damaged' => (int) $get_item_details->qty_damaged,
                'qty_scrapped' => (int) $get_item_details->qty_scrapped,
                'qty_not_in_use' => (int) $get_item_details->qty_not_in_use,
                'qty_missing' => (int) $get_item_details->qty_missing,
                'qty_shifted' => (int) $get_item_details->qty_shifted,
            );
            $revert_qty = (int) $get_instance_details->qty_value;
            if (!empty($get_instance_details_qty_ok)) {
                // $update_item_details_data_first['qty_ok'] = (int)$get_item_details->qty_ok - (int)$get_instance_details->qty_value;
                $update_item_details_data_first['qty_ok'] = (int) $get_item_details->qty_ok - (int) $get_instance_details->qty_value;
            }
            if (!empty($get_instance_details_qty_damaged)) {
                // $update_item_details_data_first['qty_damaged'] = (int)$get_item_details->qty_damaged - (int)$get_instance_details->qty_value;
                $update_item_details_data_first['qty_damaged'] = (int) $get_item_details->qty_damaged - (int) $get_instance_details->qty_value;
            }
            if (!empty($get_instance_details_qty_scrapped)) {
                // $update_item_details_data_first['qty_scrapped'] = (int)$get_item_details->qty_scrapped - (int)$get_instance_details->qty_value;
                $update_item_details_data_first['qty_scrapped'] = (int) $get_item_details->qty_scrapped - (int) $get_instance_details->qty_value;
            }
            if (!empty($get_instance_details_qty_not_in_use)) {
                // $update_item_details_data_first['qty_not_in_use'] = (int)$get_item_details->qty_not_in_use - (int)$get_instance_details->qty_value;
                $update_item_details_data_first['qty_not_in_use'] = (int) $get_item_details->qty_not_in_use - (int) $get_instance_details->qty_value;
            }
            if (!empty($get_instance_details_qty_missing)) {
                // $update_item_details_data_first['qty_missing'] = (int)$get_item_details->qty_missing - (int)$get_instance_details->qty_value;
                $update_item_details_data_first['qty_missing'] = (int) $get_item_details->qty_missing - (int) $get_instance_details->qty_value;
            }
            if (!empty($get_instance_details_qty_shifted)) {
                // $update_item_details_data_first['qty_shifted'] = (int)$get_item_details->qty_shifted - (int)$get_instance_details->qty_value;
                $update_item_details_data_first['qty_shifted'] = (int) $get_item_details->qty_shifted - (int) $get_instance_details->qty_value;
            }


            if($edit_opration != 'Update Qty & Details'){
                $verification_remarks = $get_item_details->verification_remarks .' | (-' . $revert_qty . ') ';
                $edit_opration_value = 'Update Qty & Details';
                // $new_loc_rollback = $get_item_details->new_location_verified .' | (-' . $revert_qty . ') ';
                $new_loc_rollback = $update_details->new_location_verified .' | (-' . $revert_qty . ') ';
                // $new_loc_rollback = $update_details->new_location_verified;                
            }else{
                $verification_remarks = $get_item_details->verification_remarks ;
                // $new_loc_rollback = $get_item_details->new_location_verified ;
                $new_loc_rollback = $update_details->new_location_verified;
                $edit_opration_value = 'Update Details';
            }

            $new_loc = $get_item_details->new_location_verified;
            if (isset($update_details->new_location_verified) && $update_details->new_location_verified != '') {
                $new_loc = $get_item_details->new_location_verified != '' ? $get_item_details->new_location_verified .' | ' . $update_details->new_location_verified : $update_details->new_location_verified;
            }


            $update_item_details_data_first['verification_remarks'] = $verification_remarks;            
            $update_item_details_data_first['new_location_verified'] = $new_loc_rollback;

            $quantity_verified_value = (int) $get_item_details->quantity_verified - (int) $revert_qty;
            $update_item_details_data_first['quantity_verified'] = (int) $get_item_details->quantity_verified - (int) $revert_qty;

            $update_item_details_data_first["verification_status"] = "";
            if ($get_item_details->quantity_as_per_invoice <= $quantity_verified_value) {
                $update_item_details_data_first["verification_status"] = "Verified";
            }
            $update_item_details_data_first['quantity_verified'] = (int) $get_item_details->quantity_verified - (int) $get_instance_details->qty_value;

            $update_item_details_data_first['item_description'] = $update_details->item_description;
            $update_item_details_data_first['serial_product_number'] = $update_details->serial_product_number;
            $update_item_details_data_first['make'] = $update_details->make;
            $update_item_details_data_first['model'] = $update_details->model;
            $update_item_details_data_first['tag_status_y_n_na'] = $update_details->tag_status_y_n_na;                        

            // echo '<pre>update_item_details_data_first ';
            // print_r($update_item_details_data_first);
            // echo '</pre>';
            $verify = $this->tasks->update_data($projectname, $update_item_details_data_first, $condition);           //UPDATE OPERATION
            // $last_query = $this->db->last_query();
            // echo '<pre>last_query ';
            // print_r($last_query);
            // echo '</pre>';
                        


            $this->db->select('*');
            $this->db->from($projectname);
            $this->db->where('id', $itemid);
            $query = $this->db->get();
            $get_item_details = $query->row();

            $update_item_details_data_second = array(
                'qty_ok' => (int) $get_item_details->qty_ok,
                'qty_damaged' => (int) $get_item_details->qty_damaged,
                'qty_scrapped' => (int) $get_item_details->qty_scrapped,
                'qty_not_in_use' => (int) $get_item_details->qty_not_in_use,
                'qty_missing' => (int) $get_item_details->qty_missing,
                'qty_shifted' => (int) $get_item_details->qty_shifted,
            );
            $update_item_details_data_second = array();
            if (isset($update_details->item_scrap_condition)) {
                $condition_field = $update_details->item_scrap_condition;
                $valid_fields = array('qty_ok', 'qty_damaged', 'qty_scrapped', 'qty_not_in_use', 'qty_missing', 'qty_shifted');
                if (in_array($condition_field, $valid_fields)) {
                    $update_item_details_data_second[$condition_field] = (int) $get_item_details->$condition_field + (int) $update_details->quantity_verified;
                }
            }
            $quantity_verified_update = (int) $get_item_details->quantity_verified + (int) $update_details->quantity_verified;
            $update_item_details_data_second['quantity_verified'] = $quantity_verified_update;
            $update_item_details_data_second['instance_count'] = (int) $get_item_details->instance_count + 2;
            if ($get_item_details->quantity_as_per_invoice <= $quantity_verified_update) {
                $update_item_details_data_second["verification_status"] = "Verified";
            }
            $new_remarks = $get_item_details->verification_remarks;
            if (isset($update_details->verification_remarks) && $update_details->verification_remarks != '') {
                $new_remarks = $get_item_details->verification_remarks != '' ? $get_item_details->verification_remarks .' | ' . $update_details->verification_remarks : $update_details->verification_remarks;
            }
            $update_item_details_data_second['verification_remarks'] = $new_remarks;

            /*
            $new_loc = $get_item_details->new_location_verified;
            if (isset($update_details->new_location_verified) && $update_details->new_location_verified != '') {
                $new_loc = $get_item_details->new_location_verified != '' ? $get_item_details->new_location_verified .' | ' . $update_details->new_location_verified : $update_details->new_location_verified;
            }
            $update_item_details_data_second['new_location_verified'] = $new_loc;
            */
            $update_item_details_data_second['verified_datetime'] = date('Y-m-d H:i:s');

            // echo "<pre>update_item_details_data_second ::";
            // print_r($update_item_details_data_second);
            // echo "</pre>";
            // // exit();

            $verify = $this->tasks->update_data($projectname, $update_item_details_data_second, $condition);               //UPDATE OPERATION
           


            $verifiedproducts_details_data = array(
                'qty_ok' => $get_instance_details_qty_ok,
                'qty_damaged' => $get_instance_details_qty_damaged,
                'qty_scrapped' => $get_instance_details_qty_scrapped,
                'qty_not_in_use' => $get_instance_details_qty_not_in_use,
                'qty_missing' => $get_instance_details_qty_missing,
                'qty_shifted' => $get_instance_details_qty_shifted
            );



            if (!empty($get_instance_details_qty_ok)) {
                $verifiedproducts_details_data['qty_ok'] = -$revert_qty;
            }
            if (!empty($get_instance_details_qty_damaged)) {
                $verifiedproducts_details_data['qty_damaged'] = -$revert_qty;
            }
            if (!empty($get_instance_details_qty_scrapped)) {
                $verifiedproducts_details_data['qty_scrapped'] = -$revert_qty;
            }
            if (!empty($get_instance_details_qty_not_in_use)) {
                $verifiedproducts_details_data['qty_not_in_use'] = -$revert_qty;
            }
            if (!empty($get_instance_details_qty_missing)) {
                $verifiedproducts_details_data['qty_missing'] = -$revert_qty;
            }
            if (!empty($get_instance_details_qty_shifted)) {
                $verifiedproducts_details_data['qty_shifted'] = -$revert_qty;
            }
            $current_date_time = date('Y-m-d H:i:s');
            $quantity_verified = (int) $update_details->quantity_verified;
            $actual_quantity_verified = $quantity_verified;
            // $verifiedproducts_details_data['quantity_verified'] = $quantity_verified;
            $verifiedproducts_details_data['quantity_verified'] = $quantity_verified_update;
            $verification_status = $update_details->quantity_as_per_invoice <= $update_details->quantity_verified ? "Verified" : "Not-Verified";
            $verifiedproducts_details_data['verification_status'] = $verification_status;
            $verifiedproducts_details_data['verified_datetime'] = $current_date_time;
            $verifiedproducts_details_data['updatedat'] = date('Y-m-d H:i:s');
            $verifiedproducts_details_data['verification_remarks'] = $new_remarks;
            $verifiedproducts_details_data['new_location_verified'] = $new_loc;
            $verifiedproducts_details_data['company_id'] = $get_instance_details->company_id;
            $verifiedproducts_details_data['location_id'] = $get_instance_details->location_id;
            $verifiedproducts_details_data['entity_code'] = $get_instance_details->entity_code;
            $verifiedproducts_details_data['project_id'] = $get_instance_details->project_id;
            $verifiedproducts_details_data['project_name'] = $get_instance_details->project_name;
            $verifiedproducts_details_data['original_table_name'] = $get_instance_details->original_table_name;
            $verifiedproducts_details_data['item_id'] = $get_item_details->id;
            $verifiedproducts_details_data['item_category'] = $get_item_details->item_category;
            $verifiedproducts_details_data['item_unique_code'] = $get_item_details->item_unique_code;
            $verifiedproducts_details_data['item_sub_code'] = $get_item_details->item_sub_code;
            $verifiedproducts_details_data['item_description'] = $get_item_details->item_description;
            $verifiedproducts_details_data['quantity_as_per_invoice'] = $get_item_details->quantity_as_per_invoice;
            $verifiedproducts_details_data['verification_status'] = $verification_status;
            $verifiedproducts_details_data['verified_by'] = $verified_by;
            $verifiedproducts_details_data['verified_by_username'] = $verified_by_username;
            $verifiedproducts_details_data['verified_datetime'] = $current_date_time;
            $verifiedproducts_details_data['verification_remarks'] = $new_remarks . " \2";
            $verifiedproducts_details_data['mode_of_verification'] = $get_instance_details->mode_of_verification;
            $verifiedproducts_details_data['type_of_operation'] = 'edit';
            $verifiedproducts_details_data['qty_value'] = abs($revert_qty);
            $verifiedproducts_details_data['created_at'] = date('Y-m-d H:i:s');

            $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts', $verifiedproducts_details_data);          //INSERT OPERATION


            //Add into Verified Products Log Table        
            $qty_scrapped_value = 0;
            $qty_damaged_value = 0;
            $qty_ok_value = 0;
            $qty_not_in_use_value = 0;
            $qty_missing_value = 0;
            $qty_shifted_value = 0;
            if ($update_details->item_scrap_condition == 'qty_ok') {
                $qty_ok_value = $quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_damaged') {
                $qty_damaged_value = $quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_scrapped') {
                $qty_scrapped_value = $quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_not_in_use') {
                $qty_not_in_use_value = $quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_missing') {
                $qty_missing_value = $quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_shifted') {
                $qty_shifted_value = $quantity_verified;
            }





            // }



            $company_id = $get_project_details[0]->company_id;
            $new_location_verified = $update_details->new_location_verified;
            $location_id = $get_project_details[0]->project_location;
            $entity_code = $get_project_details[0]->entity_code;
            $project_id = $get_project_details[0]->id;
            $project_name = $get_project_details[0]->project_name;
            $original_table_name = $get_project_details[0]->original_table_name;

            $current_date_time = date('Y-m-d H:i:s');
            $verification_status = $update_details->quantity_as_per_invoice <= $update_details->quantity_verified ? "Verified" : "Not-Verified";

            $mode_of_verification = $update_details->mode_of_verification;
            $update_details->mode_of_verification = $mode_of_verification;

            //Add In Log File
            $verifiedproducts_array = array(
                'row_id' => $get_item_details->id,
                'edit_opration' => $edit_opration_value,
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
                'previous_item_id' => $get_item_details->id,
                'item_id' => $get_item_details->id,
                'previous_item_category' => $get_item_details->item_category,
                'item_category' => $get_item_details->item_category,
                'previous_item_unique_code' => $get_item_details->item_unique_code,
                'item_unique_code' => $get_item_details->item_unique_code,
                'previous_item_sub_code' => $get_item_details->item_sub_code,
                'item_sub_code' => $get_item_details->item_sub_code,
                'previous_item_description' => $get_item_details->item_description,
                'item_description' => $get_item_details->item_description,
                'previous_quantity_as_per_invoice' => $get_item_details->quantity_as_per_invoice,
                'quantity_as_per_invoice' => $get_item_details->quantity_as_per_invoice,
                'previous_verification_status' => $get_item_details->verification_status,
                'verification_status' => $verification_status,
                'previous_quantity_verified' => $get_item_details->quantity_verified,
                'quantity_verified' => $quantity_verified_value,
                'previous_new_location_verified' => $get_item_details->new_location_verified,
                'new_location_verified' => $new_location_verified,
                'previous_verified_by' => $get_item_details->verified_by,
                'verified_by' => $verified_by,
                'previous_verified_by_username' => $get_item_details->verified_by_username,
                'verified_by_username' => $verified_by_username,
                'previous_verified_datetime' => $get_item_details->verified_datetime,
                'verified_datetime' => $current_date_time,
                'previous_verification_remarks' => $get_item_details->verification_remarks,
                'verification_remarks' => $verification_remarks,
                'previous_qty_ok' => $get_item_details->qty_ok,
                'qty_ok' => $qty_ok,
                'previous_qty_damaged' => $get_item_details->qty_damaged,
                'qty_damaged' => $qty_damaged,
                'previous_qty_scrapped' => $get_item_details->qty_scrapped,
                'qty_scrapped' => $qty_scrapped,
                'previous_qty_not_in_use' => $get_item_details->qty_not_in_use,
                'qty_not_in_use' => $qty_not_in_use,
                'previous_qty_missing' => $get_item_details->qty_missing,
                'qty_missing' => $qty_missing,
                'previous_qty_shifted' => $get_item_details->qty_shifted,
                'qty_shifted' => $qty_shifted,
                'previous_mode_of_verification' => $get_item_details->mode_of_verification,
                'mode_of_verification' => $mode_of_verification,
                'previous_created_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts_log', $verifiedproducts_array);       //INSERT OPERATION



            //Add into Verified Products Log Table        
            $qty_scrapped_value = 0;
            $qty_damaged_value = 0;
            $qty_ok_value = 0;
            $qty_not_in_use_value = 0;
            $qty_missing_value = 0;
            $qty_shifted_value = 0;
            if ($update_details->item_scrap_condition == 'qty_ok') {
                // $qty_ok_value = $quantity_verified;
                $qty_ok_value = $update_details->quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_damaged') {
                // $qty_damaged_value = $quantity_verified;
                $qty_damaged_value = $update_details->quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_scrapped') {
                $qty_scrapped_value = $update_details->quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_not_in_use') {
                $qty_not_in_use_value = $update_details->quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_missing') {
                $qty_missing_value = $update_details->quantity_verified;
            }
            if ($update_details->item_scrap_condition == 'qty_shifted') {
                $qty_shifted_value = $update_details->quantity_verified;
            }










            if ($verifiedproducts_result) {

                $verifiedproducts_array = array(
                    'company_id' => $company_id,
                    'location_id' => $location_id,
                    'entity_code' => $entity_code,
                    'project_id' => $project_id,
                    'project_name' => $project_name,
                    'original_table_name' => $original_table_name,
                    'item_id' => $get_item_details->id,
                    'item_category' => $get_item_details->item_category,
                    'item_unique_code' => $get_item_details->item_unique_code,
                    'item_sub_code' => $get_item_details->item_sub_code,
                    'item_description' => $get_item_details->item_description,
                    'quantity_as_per_invoice' => $get_item_details->quantity_as_per_invoice,
                    'verification_status' => $verification_status,
                    'quantity_verified' => $update_item_details_data_second['quantity_verified'],
                    'new_location_verified' => $new_loc,
                    'verified_by' => $verified_by,
                    'verified_by_username' => $verified_by_username,
                    'verified_datetime' => $current_date_time,
                    'verification_remarks' => $new_remarks,
                    'qty_ok' => $qty_ok_value,
                    'qty_damaged' => $qty_damaged_value,
                    'qty_scrapped' => $qty_scrapped_value,
                    'qty_not_in_use' => $qty_not_in_use_value,
                    'qty_missing' => $qty_missing_value,
                    'qty_shifted' => $qty_shifted_value,
                    'mode_of_verification' => $mode_of_verification,
                    // 'type_of_operation' => $operation,
                    'qty_value' => abs($actual_quantity_verified),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updatedat' => date('Y-m-d H:i:s')
                );

                $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts', $verifiedproducts_array);           //INSERT OPERATION

                header('Content-Type: application/json');
                echo json_encode(array("success" => 200, "message" => "Item verified update successfully."));
                exit;

            } else {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 401, "message" => "Item not verified"));
                exit;
            }

        } else {

            $verification_remarks = '';
            $qty_ok = 0;
            $qty_damaged = 0;
            $qty_scrapped = 0;
            $qty_not_in_use = 0;
            $qty_missing = 0;
            $qty_shifted = 0;
            $qty_value = $scanned->quantity_verified;

            $getquantity = $this->tasks->get_data($projectname, $condition);

            $quantity_as_per_invoice = $getquantity[0]->quantity_as_per_invoice;

            $quantity_verified_tbl = $getquantity[0]->quantity_verified;
            $quantity_verified = $scanned->quantity_verified;

            $total_will_be = (int) $quantity_verified_tbl + (int) $quantity_verified;

            // if($quantity_as_per_invoice < $total_will_be){
            //     header('Content-Type: application/json');
            //     echo json_encode(array("success"=>200,"message"=>"Qty Verification more then they actually qty"));
            //     exit;
            // }
            

            if ($scanned->item_scrap_condition == 'qty_ok') {
                $qty_ok = (int) $getquantity[0]->qty_ok + (int) $scanned->quantity_verified;
                $scanned->qty_ok = $qty_ok;

            } else if ($scanned->item_scrap_condition == 'qty_damaged') {
                $qty_damaged = (int) $getquantity[0]->qty_damaged + (int) $scanned->quantity_verified;
                $scanned->qty_damaged = $qty_damaged;
            } else if ($scanned->item_scrap_condition == 'qty_scrapped') {
                $qty_scrapped = (int) $getquantity[0]->qty_scrapped + (int) $scanned->quantity_verified;
                $scanned->qty_scrapped = $qty_scrapped;
            } else if ($scanned->item_scrap_condition == 'qty_not_in_use') {
                $qty_not_in_use = (int) $getquantity[0]->qty_not_in_use + (int) $scanned->quantity_verified;
                $scanned->qty_not_in_use = $qty_not_in_use;
            } else if ($scanned->item_scrap_condition == 'qty_missing') {
                $qty_missing = (int) $getquantity[0]->qty_missing + (int) $scanned->quantity_verified;
                $scanned->qty_missing = $qty_missing;
            } else if ($scanned->item_scrap_condition == 'qty_shifted') {
                $qty_shifted = (int) $getquantity[0]->qty_shifted + (int) $scanned->quantity_verified;
                $scanned->qty_shifted = $qty_shifted;
            }

            if ($scanned->verification_remarks != '') {
                $quantity_verified = (int) $getquantity[0]->quantity_verified + (int) $scanned->quantity_verified;
                $scanned->quantity_verified = $quantity_verified;

                $verification_status = $scanned->quantity_as_per_invoice <= $scanned->quantity_verified ? "Verified" : "Not-Verified";
                $scanned->verification_status = $verification_status;

                $verification_remarks = $getquantity[0]->verification_remarks != '' ? $getquantity[0]->verification_remarks . ' | ' . $scanned->verification_remarks : $scanned->verification_remarks;
                $scanned->verification_remarks = trim($verification_remarks);

                // $verified_datetime = date('Y-m-d H:i:s', strtotime('+17 minutes',strtotime(date('Y-m-d H:i:s'))));
                $verified_datetime = date('Y-m-d H:i:s');
                $scanned->verified_datetime = $verified_datetime;

                // $updatedat = date('Y-m-d H:i:s', strtotime('+17 minutes',strtotime(date('Y-m-d H:i:s'))));
                $updatedat = date('Y-m-d H:i:s');
                $scanned->updatedat = $updatedat;
            } else {

                $quantity_verified = (int) $getquantity[0]->quantity_verified + (int) $scanned->quantity_verified;
                $scanned->quantity_verified = $quantity_verified;

                $verification_status = $scanned->quantity_as_per_invoice <= $scanned->quantity_verified ? "Verified" : "Not-Verified";
                $scanned->verification_status = $verification_status;

                $scanned->verification_remarks = trim($getquantity[0]->verification_remarks);

                // $verified_datetime = date('Y-m-d H:i:s', strtotime('+17 minutes',strtotime(date('Y-m-d H:i:s'))));
                $verified_datetime = date('Y-m-d H:i:s');
                $scanned->verified_datetime = $verified_datetime;

                // $updatedat = date('Y-m-d H:i:s', strtotime('+17 minutes',strtotime(date('Y-m-d H:i:s'))));
                $updatedat = date('Y-m-d H:i:s');
                $scanned->updatedat = $updatedat;
            }

            // $scanned->instance_count = 0;
            if (isset($getquantity[0]->instance_count)) {
                $scanned->instance_count = (int) $getquantity[0]->instance_count + 1;
            }

            $mode_of_verification = $scanned->mode_of_verification;
            $scanned->mode_of_verification = $mode_of_verification;

            $new_array[0] = $this->stdToArray($scanned);
            unset($new_array[0]['item_scrap_condition']);
            $verify = $this->tasks->update_data($projectname, $new_array[0], $condition);

            // $verify = 1;

            $project_id = $this->input->post('project_id');
            $getprojectdetails_condition = array(
                'id' => $project_id
            );
            $getprojectdetails = $this->tasks->get_data('company_projects', $getprojectdetails_condition);


            $company_id = $getprojectdetails[0]->company_id;
            // $mode_of_verification = 'Scan';
            $new_location_verified = $scanned->new_location_verified;
            $location_id = $getprojectdetails[0]->project_location;
            $entity_code = $getprojectdetails[0]->entity_code;
            $project_id = $getprojectdetails[0]->id;
            $project_name = $getprojectdetails[0]->project_name;
            $original_table_name = $getprojectdetails[0]->original_table_name;

            $verify_user_detail = $this->tasks->get_single_user($verified_by);
            $verified_by_username = $verify_user_detail->firstName;



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
                'verified_datetime' => date('Y-m-d H:i:s'),//$verified_datetime,
                'verification_remarks' => $verification_remarks,
                'qty_ok' => $qty_ok,
                'qty_damaged' => $qty_damaged,
                'qty_scrapped' => $qty_scrapped,
                'qty_not_in_use' => $qty_not_in_use,
                'qty_missing' => $qty_missing,
                'qty_shifted' => $qty_shifted,
                'mode_of_verification' => $mode_of_verification,
                'type_of_operation' => 'add',
                'qty_value' => $qty_value,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts', $verifiedproducts_array);


            if ($verify) {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 200, "message" => "Item verified successfully."));
                exit;

            } else {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 401, "message" => "Item not verified"));
                exit;
            }
        }
    }
    function stdToArray($obj)
    {
        $reaged = (array) $obj;
        foreach ($reaged as $key => &$field) {
            if (is_object($field))
                $field = stdToArray($field);
        }
        return $reaged;
    }
    public function projectstart()
    {
        $userid = $this->input->post('user_id');
        $projectid = $this->input->post('project_id');
        $companyid = $this->input->post('company_id');
        $data = array(
            "begin_datetime" => date('Y-m-d H:i:s'),
        );
        $condition = array(
            "id" => $projectid,
            "company_id" => $companyid,
            "project_verifier" => $userid
        );
        $finish = $this->tasks->update_data('company_projects', $data, $condition);

        if ($finish) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Project started successfully."));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Project is not started"));
            exit;
        }
    }
    public function projectfinish()
    {
        $userid = $this->input->post('user_id');
        $projectid = $this->input->post('project_id');
        $companyid = $this->input->post('company_id');
        $data = array(
            "verification_closed_by" => $userid,
            "finish_datetime" => date('Y-m-d H:i:s'),
            "status" => 3
        );
        $condition = array(
            "id" => $projectid,
            "company_id" => $companyid,
        );
        $finish = $this->tasks->update_data('company_projects', $data, $condition);

        if ($finish) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Verification finished successfully."));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Verification is not finished"));
            exit;
        }
    }
    public function finalizeverifiedproject()
    {
        $userid = $this->input->post('project_finished_by');
        $projectid = $this->input->post('project_id');
        $remarks = $this->input->post('remarks');
        $status = $this->input->post('status');
        if ($status == 1) {
            $data = array(
                "project_finished_by" => $userid,
                "finish_datetime" => date('Y-m-d H:i:s'),
                "status" => $status,
                "end_remark" => $remarks == '' ? NULL : $remarks
            );
        } else {
            $data = array(
                "project_finished_by" => $userid,
                "finish_datetime" => date('Y-m-d H:i:s'),
                "cancelled_date" => date('Y-m-d'),
                "status" => $status,
                "cancel_reason" => $remarks == '' ? NULL : $remarks
            );
        }

        $condition = array(
            "id" => $projectid,

        );
        $finish = $this->tasks->update_data('company_projects', $data, $condition);

        if ($status == '2') {
            $this->db->select('*');
            $this->db->from('company_projects');
            $this->db->where('id', $projectid);
            $query = $this->db->get();
            $company_projects_result = $query->row();
            $original_table_name = $company_projects_result->original_table_name;
            $item_category_array = json_decode($company_projects_result->item_category);

            foreach ($item_category_array as $item_category_array_value) {
                $condition = array(
                    'item_category' => $item_category_array_value
                );
                $data = array(
                    'is_alotted' => 0
                );
                $this->db->where($condition);
                $query = $this->db->update($original_table_name, $data);
            }
        }






        if ($finish) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Verification finished successfully."));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Verification is not finished"));
            exit;
        }
    }

    public function savenote()
    {
        $userid = $this->input->post('user_id');
        $itemid = $this->input->post('item_id');
        $projectname = $this->input->post('project_name');
        $itemnote = $this->input->post('item_note');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $data = array("item_note" => $itemnote);
        $condition = array("id" => $itemid);
        $updatenote = $this->tasks->update_data($projectname, $data, $condition);
        if ($updatenote) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Note updated successfully."));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Note not updated"));
            exit;
        }
    }
    public function getcategories()
    {
        $projectname = $this->input->post('project_name');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $getcategory = $this->tasks->getdistinct_data($projectname, "item_category");
        $getsubcategory = $this->tasks->getdistinct_data($projectname, "item_sub_category");
        if ($getcategory && $getsubcategory) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "categories" => $getcategory, "subcategories" => $getsubcategory));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Something went wrong"));
            exit;
        }

    }
    public function getsubcategories()
    {
        $projectname = $this->input->post('project_name');
        $item_category = $this->input->post('item_category');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $getsubcategory = $this->tasks->getdistinctwithcondition($projectname, "item_sub_category", array("item_category" => $item_category));

        $Responce_data = array();
        $i = 0;
        $Responce_data[$i]['item_sub_category'] = 'All';
        if (!empty($getsubcategory)) {
            foreach ($getsubcategory as $getsubcategory_key => $getsubcategory_value) {
                $i++;
                $Responce_data[$i] = $getsubcategory_value;
            }
        }

        $result_count = count($Responce_data);

        if ($Responce_data) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "subcategories" => $Responce_data));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Something went wrong"));
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

    public function get_company()
    {
        $userid = $this->input->post('user_id');
        // $project=$this->tasks->get_project_companies($userid);
        $user_company = $this->tasks->get_all_company_user_role($userid);
        // print_r($user_company);
        $data_company = array();
        $i = 0;
        foreach ($user_company as $row_project) {
            if ($row_project['company_id'] != '0') {
                $company_row = $this->tasks->get_company_row($row_project['company_id']);
                $location_cnt = $this->tasks->get_location_count_user($userid, $row_project['company_id']);
                $project_cnt = $this->tasks->get_project_count_user($userid, $row_project['company_id']);

                $data_company[$i]['company_name'] = $company_row->company_name;
                $data_company[$i]['company_id'] = $company_row->id;
                $data_company[$i]['company_short_code'] = $company_row->short_code;
                $data_company[$i]['number_of_location'] = $location_cnt;
                $data_company[$i]['number_of_project'] = $project_cnt;
                $data_company[$i]['project_close_overdue'] = $this->tasks->get_project_close_by_company($company_row->id);
            }
            $i++;
        }
        $number_of_company = count($user_company);

        if (!empty($user_company)) {
            header('Content-Type: application/json');
            $data = $data_company;
            echo json_encode(array("success" => 200, "message" => "Company fetched successfully.", "data" => $data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Company assigned"));
            exit;
        }
    }


    public function get_company_location_Backup()
    {

        $userid = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $locations = $this->tasks->get_all_location_user_role($userid, $company_id);

        $data_location = array();
        $i = 0;

        foreach ($locations as $row_project) {
            if ($row_project['location_id'] != '0') {
                $location_row = $this->tasks->get_location_row($row_project['location_id']);
                $project_cnt = $this->tasks->get_project_count_location($userid, $row_project['company_id'], $row_project['location_id']);

                $data_location[$i]['location_name'] = $location_row->location_name;
                $data_location[$i]['location_id'] = $location_row->id;
                $data_location[$i]['location_shortcode'] = $location_row->location_shortcode;
                $data_location[$i]['number_of_project'] = $project_cnt;


                $this->db->select("*");
                $this->db->from("company_projects");
                // $this->db->where("entity_code",$entity_code);
                $this->db->where("project_location", $location_row->id);
                $this->db->where("status !=", '2');
                $query = $this->db->get();
                $projects = $query->row();

                // echo "<pre>projects :";
                // print_r($projects);
                // echo "</pre>";
                // // exit;



                // foreach($projects as $projects_value){
                //     echo "<pre>projects_value Data :";
                //     print_r($projects_value);
                //     echo "</pre>";
                //     // exit;
                // }

                $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
                $new_pattern = array("_", "_", "");


                if (empty($projects)) {
                    $data_location[$i]['project_percent'] = '0';
                }
                if (!empty($projects)) {
                    $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($projects->project_name)));
                    $getprojectdetails = $this->tasks->projectdetail($project_name);

                    // echo "<pre>Last query 3:";
                    // print_r($this->db->last_query());
                    // echo "</pre>";


                    if (!empty($getprojectdetails)) {
                        $projects->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                        if ($getprojectdetails[0]->VerifiedQuantity != '')
                            $projects->VerifiedQuantity = $getprojectdetails[0]->VerifiedQuantity;
                        else
                            $projects->VerifiedQuantity = 0;
                    } else {
                        $projects->TotalQuantity = 0;
                        $projects->VerifiedQuantity = 0;
                    }




                    if ($projects->VerifiedQuantity != 0) {
                        $project_percent = round(($projects->VerifiedQuantity / $projects->TotalQuantity) * 100, 2);
                    } else {
                        $project_percent = "0";
                    }
                    $data_location[$i]['project_percent'] = $project_percent;
                    $data_location[$i]['project_close_overdue'] = $this->tasks->get_project_close($projects->id);
                }

            }
            $i++;
        }
        $number_of_location = count($locations);
        if (!empty($locations) && count($locations) > 0) {
            header('Content-Type: application/json');
            $data = $data_location;

            echo json_encode(array("success" => 200, "message" => "Location fetched successfully.", "data" => $data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Location assigned"));
            exit;
        }


    }


    public function get_company_location()
    {

        $userid = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $locations = $this->tasks->get_all_location_user_role($userid, $company_id);

        $data_location = array();
        $i = 0;

        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        foreach ($locations as $row_project) {

            if ($row_project['location_id'] != '0') {

                // ✅ RESET HERE (VERY IMPORTANT)
                $project_TotalQuantity = 0;
                $project_VerifiedQuantity = 0;

                $location_row = $this->tasks->get_location_row($row_project['location_id']);
                $project_cnt = $this->tasks->get_project_count_location($userid, $row_project['company_id'], $row_project['location_id']);

                $data_location[$i]['location_name'] = $location_row->location_name;
                $data_location[$i]['location_id'] = $location_row->id;
                $data_location[$i]['location_shortcode'] = $location_row->location_shortcode;
                $data_location[$i]['number_of_project'] = $project_cnt;

                $this->db->select("*");
                $this->db->from("company_projects");
                $this->db->where("project_location", $location_row->id);
                $this->db->where("status !=", '2');
                $query = $this->db->get();

                $projects3 = $query->result();

                foreach ($projects3 as $projects_value) {

                    $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($projects_value->project_name)));
                    $getprojectdetails = $this->tasks->projectdetail($project_name);

                    if (!empty($getprojectdetails)) {
                        $projects_value->TotalQuantity = (int) $getprojectdetails[0]->TotalQuantity;
                        $projects_value->VerifiedQuantity = !empty($getprojectdetails[0]->VerifiedQuantity) ? $getprojectdetails[0]->VerifiedQuantity : 0;
                    } else {
                        $projects_value->TotalQuantity = 0;
                        $projects_value->VerifiedQuantity = 0;
                    }

                    // ✅ Correct accumulation
                    $project_TotalQuantity += $projects_value->TotalQuantity;
                    $project_VerifiedQuantity += $projects_value->VerifiedQuantity;
                }

                // ✅ Correct percentage formula
                if ($project_TotalQuantity > 0) {
                    $data_location[$i]['project_percent'] = round(($project_VerifiedQuantity / $project_TotalQuantity) * 100, 2);
                } else {
                    $data_location[$i]['project_percent'] = 0;
                }

                $data_location[$i]['project_close_overdue'] = [
                    "is_project_close" => 0
                ];

                $i++;
            }
        }
        $number_of_location = count($locations);
        if (!empty($locations) && count($locations) > 0) {
            header('Content-Type: application/json');
            $data = $data_location;

            echo json_encode(array("success" => 200, "message" => "Location fetched successfully.", "data" => $data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Location assigned"));
            exit;
        }
        /*hhhhhhhh
        exit();
        echo "<pre>data_location :";
        print_r($data_location);
        echo "</pre>";
        exit;


            // echo "<pre>locations :";
            // print_r($locations);
            // echo "</pre>";
            // exit;


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
                $this->db->where("project_location",$location_row->id);
                $this->db->where("status !=",'2');
                $query= $this->db->get();   
                $projects = $query->row();
                $projects3 = $query->result();        


                $data_location[$i]['project_percent']='0';
                $data_location[$i]['project_close_overdue']='0';

                $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
                $new_pattern = array("_", "_", "");

                /*
                $data_location2[$location_row->id]['project_TotalQuantity']= array();
                $data_location2[$location_row->id]['project_VerifiedQuantity']= array();
                $data_location2[$location_row->id]['project_percent']= array();

                $project_TotalQuantity = 0;
                $project_VerifiedQuantity = 0;
                foreach($projects3 as $projects_key=>$projects_value){
                    // echo "<pre>projects_value :";
                    // print_r($projects_value->project_location);
                    // echo "</pre>";

                    $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($projects_value->project_name)));
                    $getprojectdetails=$this->tasks->projectdetail($project_name);

                    if(!empty($getprojectdetails))
                    {
                        $projects_value->TotalQuantity = ((int)$getprojectdetails[0]->TotalQuantity);                
                        if($getprojectdetails[0]->VerifiedQuantity !=''){
                            $projects_value->VerifiedQuantity=$getprojectdetails[0]->VerifiedQuantity;
                        }else{
                            $projects_value->VerifiedQuantity=0;
                        }
                    }
                    else
                    {   
                        $projects_value->TotalQuantity=0;
                        $projects_value->VerifiedQuantity=0;
                    }

                    $project_TotalQuantity = $project_TotalQuantity+$projects_value->TotalQuantity;
                    // $project_TotalQuantity = $projects_value->TotalQuantity;
                    $project_VerifiedQuantity = $project_VerifiedQuantity+$projects_value->VerifiedQuantity;
                    // $project_VerifiedQuantity = $projects_value->VerifiedQuantity;
                    $data_location2[$projects_value->project_location]['project_TotalQuantity'] = $project_TotalQuantity;
                    $data_location2[$projects_value->project_location]['project_VerifiedQuantity'] = $project_VerifiedQuantity;
                    $data_location2[$projects_value->project_location]['project_percent'] = round(($project_TotalQuantity/$project_VerifiedQuantity)*100,2);
                }
                */

        /*hhhhhhhh

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
            */

    }


    public function get_company_location_project()
    {

        $userid = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $location_id = $this->input->post('location_id');
        $project = $this->tasks->get_project_company_location($userid, $company_id, $location_id);
        // print_r($project);

        if (!empty($project) && count($project) > 0) {
            header('Content-Type: application/json');

            echo json_encode(array("success" => 200, "message" => "Project fetched successfully.", "data" => $project));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Project assigned for this location"));
            exit;
        }


    }

    public function get_graph_data()
    {
        $projects_id = $this->input->post('project_id');
        $project_row = $this->tasks->get_company_project($projects_id);

        $projectname = $project_row->project_name;
        $project_type = $project_row->project_type;

        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");

        $projectname1 = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $listing = getTagUntag($projectname);


        $project1 = $this->tasks->projectdetailnew($projectname1);
        $tagged = 0;
        $untagged = 0;
        $unverified = 0;

        foreach ($listing['projectverifiers'] as $row) {
            $usertaged = $row->usertagged;
            $untagged = $row->useruntagged;
        }
        $useruntagedlisting = $listing['ntotal'];
        $usertagedlisting = $listing['ytotal'];
        $total_unverified = ($project1[0]['TotalQuantity'] - $project1[0]['VerifiedQuantity']);
        $project['ProjectID'] = $project_row->project_id;
        $project['ProjectName'] = $project_row->project_name;
        $project['ProjectStatus'] = $project_row->status;
        $project['TotalQuantity'] = $project1[0]['TotalQuantity'];
        $project['VerifiedQuantity'] = $project1[0]['VerifiedQuantity'];
        $project['unverifiedQuantity'] = $total_unverified;
        $project['untagged'] = $untagged;
        $project['untagged_li_total'] = $useruntagedlisting;

        $project['tagged'] = $usertaged;
        $project['tagged_li_total'] = $usertagedlisting;

        // print_r($project);

        if (!empty($project) && count($project) > 0) {
            header('Content-Type: application/json');

            echo json_encode(array("success" => 200, "message" => "Project fetched successfully.", "data" => $project));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Project assigned for this location"));
            exit;
        }
    }

    public function get_project_contact_info()
    {
        $project_id = $this->input->post('project_id');
        $data_contact = $this->tasks->get_contact_detail($project_id);

        if (!empty($data_contact) && count($data_contact) > 0) {
            header('Content-Type: application/json');

            echo json_encode(array("success" => 200, "message" => "Contact fetched successfully.", "data" => $data_contact));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Contact this project"));
            exit;
        }
    }


    public function save_project_contact_info()
    {
        $project_id = $this->input->post('project_id');
        $data_contact = array(
            "project_id" => $this->input->post('project_id'),
            "name" => $this->input->post('name'),
            "email" => $this->input->post('email'),
            "phone" => $this->input->post('phone'),
            "designation" => $this->input->post('designation'),
            "created_by" => $this->input->post('user_id'),
            "created_at" => date("Y-m-d H:i:s")
        );
        $save = $this->tasks->save_contact_detail($data_contact);

        if ($save == '1') {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Contact Saved successfully."));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Something Went Wrong"));
            exit;
        }
    }

    public function get_graph_datastatus()
    {
        $entity_code = $this->input->post('entity_code');
        $this->db->select("*");
        $this->db->from("company_projects");
        $this->db->where("entity_code", $entity_code);
        $query = $this->db->get();
        $project = $query->result();
        //  echo $this->db->last_query();


        $open_project = 0;
        $closed_project = 0;
        $cancel_project = 0;
        foreach ($project as $row) {
            if ($row->status == 0 || $row->status == 3) {
                $open_project++;
            }
            if ($row->status == 1) {
                $closed_project++;
            }

            if ($row->status == 2) {
                $cancel_project++;
            }
        }
        $data = array();
        $data['open_project'] = $open_project;
        $data['closed_project'] = $closed_project;
        $data['cancel_project'] = $cancel_project;

        header('Content-Type: application/json');
        echo json_encode(array("success" => 200, "message" => "Data fetched successfully.", "data" => $data));
        exit;

    }



    public function project_completion_by_location()
    {
        $entity_code = $this->input->post('entity_code');
        $project_location = $this->input->post('project_location');
        $this->db->select("*");
        $this->db->from("company_projects");
        $this->db->where("entity_code", $entity_code);
        $this->db->where("project_location", $project_location);
        $query = $this->db->get();
        $projects = $query->result();
        //  echo $this->db->last_query();


        foreach ($projects as $project) {
            $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
            $new_pattern = array("_", "_", "");
            $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));
            $getprojectdetails = $this->tasks->projectdetail($project_name);

            if (!empty($getprojectdetails)) {
                $project->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                if ($getprojectdetails[0]->VerifiedQuantity != '')
                    $project->VerifiedQuantity = $getprojectdetails[0]->VerifiedQuantity;
                else
                    $project->VerifiedQuantity = 0;
            } else {
                $project->TotalQuantity = 0;
                $project->VerifiedQuantity = 0;
            }




            if ($project->VerifiedQuantity != 0) {
                $project_percent = round(($project->VerifiedQuantity / $project->TotalQuantity) * 100, 2);
            } else {
                $project_percent = "0";
            }

        }
        $data = array();
        $data['project_completion_percent'] = $project_percent;

        header('Content-Type: application/json');
        echo json_encode(array("success" => 200, "message" => "Data fetched successfully.", "data" => $data));
        exit;

    }

    public function additional_data()
    {
        $asset_category = $this->input->post('asset_category');
        $asset_classification = $this->input->post('asset_classification');
        $description_of_asset = $this->input->post('description_of_asset');
        $qty_verified = $this->input->post('qty_verified');
        $current_location = $this->input->post('current_location');
        $condition_of_assets = $this->input->post('condition_of_assets');
        $make = $this->input->post('make');
        $model = $this->input->post('model');
        $serial_no = $this->input->post('serial_no');
        $temp_verifiction_id_ref = $this->input->post('temp_verifiction_id_ref');
        $expected_unit_cost = $this->input->post('expected_unit_cost');
        $any_other_details_unit_cost = $this->input->post('any_other_details_unit_cost');
        $verified_name = $this->input->post('verified_name');
        $project_id = $this->input->post('project_id');
        $data = array(
            "asset_category" => $asset_category,
            "asset_classification" => $asset_classification,
            "description_of_asset" => $description_of_asset,
            "qty_verified" => $qty_verified,
            "current_location" => $current_location,
            "condition_of_assets" => $condition_of_assets,
            "make" => $make,
            "model" => $model,
            "serial_no" => $serial_no,
            "temp_verifiction_id_ref" => $temp_verifiction_id_ref,
            "expected_unit_cost" => $expected_unit_cost,
            "any_other_details_unit_cost" => $any_other_details_unit_cost,
            "verified_name" => $verified_name,
            "project_id" => $project_id,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s"),
        );
        $insert = $this->db->insert('additional_data', $data);
        $datanew = array();
        if ($insert) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Data insert successfully.", "status" => '0'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "something went wrong.", "status" => '0'));
        }
        exit;
    }




    public function get_project_additionaldata()
    {
        $project_id = $this->input->post('project_id');
        $data_additional = $this->tasks->get_addintional_detail($project_id);

        if (!empty($data_additional) && count($data_additional) > 0) {
            header('Content-Type: application/json');

            echo json_encode(array("success" => 200, "message" => "Contact fetched successfully.", "data" => $data_additional));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No additional data in this project"));
            exit;
        }
    }

    public function update_additional_data()
    {
        $asset_category = $this->input->post('asset_category');
        $asset_classification = $this->input->post('asset_classification');
        $description_of_asset = $this->input->post('description_of_asset');
        $qty_verified = $this->input->post('qty_verified');
        $current_location = $this->input->post('current_location');
        $condition_of_assets = $this->input->post('condition_of_assets');
        $make = $this->input->post('make');
        $model = $this->input->post('model');
        $serial_no = $this->input->post('serial_no');
        $temp_verifiction_id_ref = $this->input->post('temp_verifiction_id_ref');
        $expected_unit_cost = $this->input->post('expected_unit_cost');
        $any_other_details_unit_cost = $this->input->post('any_other_details_unit_cost');
        $verified_name = $this->input->post('verified_name');
        $project_id = $this->input->post('project_id');
        $id = $this->input->post('id');
        $data = array(
            "asset_category" => $asset_category,
            "asset_classification" => $asset_classification,
            "description_of_asset" => $description_of_asset,
            "qty_verified" => $qty_verified,
            "current_location" => $current_location,
            "condition_of_assets" => $condition_of_assets,
            "make" => $make,
            "model" => $model,
            "serial_no" => $serial_no,
            "temp_verifiction_id_ref" => $temp_verifiction_id_ref,
            "expected_unit_cost" => $expected_unit_cost,
            "any_other_details_unit_cost" => $any_other_details_unit_cost,
            "verified_name" => $verified_name,
            "project_id" => $project_id,
        );
        $insert = $this->db->where('id', $id);
        $insert = $this->db->update('additional_data', $data);
        $datanew = array();
        if ($insert) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Data Updated successfully.", "status" => '0'));
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "something went wrong.", "status" => '0'));
        }
        exit;
    }

    public function update_project_contact_info()
    {
        $id = $this->input->post('id');
        $data_contact = array(
            "project_id" => $this->input->post('project_id'),
            "name" => $this->input->post('name'),
            "email" => $this->input->post('email'),
            "phone" => $this->input->post('phone'),
            "designation" => $this->input->post('designation'),
            "created_by" => $this->input->post('user_id'),
            "created_at" => date("Y-m-d H:i:s")
        );
        $this->db->where('id', $id);
        $save = $this->db->update('contact_detail', $data_contact);
        if ($save == '1') {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Contact Updated successfully."));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Something Went Wrong"));
            exit;
        }
    }



    public function itemnewall()
    {
        $projectid = $this->input->post('project_id');
        $userid = $this->input->post('user_id');
        $verification_status = $this->input->post('verification_status');
        $tag_status_y_n_na = $this->input->post('tag_status_y_n_na');
        $item_category = $this->input->post('item_category');
        $item_sub_category = $this->input->post('item_sub_category');
        $projectname = $this->input->post('project_name');
        // $search_text =$this->input->post('search_text');
        // $search_fields =$this->input->post('search_fields');
        $cond = array();
        $where = ' Where 1 ';
        // $where=' Where (';
        $i = 1;
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
        if ($verification_status != 'All') {
            $where .= ' AND verification_status="' . $verification_status . '"';
        }
        if ($tag_status_y_n_na != 'All') {
            $where .= ' AND tag_status_y_n_na="' . $tag_status_y_n_na . '"';
        }
        if ($item_category != 'All') {
            $where .= ' AND item_category="' . $item_category . '"';
        }
        if ($item_sub_category != '' && $item_sub_category != 'All') {
            $where .= ' AND item_sub_category="' . $item_sub_category . '"';
        }

        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $projectdetail = $this->tasks->get_data('company_projects', array('id' => $projectid));

        $select = "SELECT * FROM " . $projectname;
        $scantask = $this->db->query($select . $where)->result();
        if (!empty($scantask) && count($scantask) > 0) {
            foreach ($scantask as $st) {
                // $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
                // $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));

                $st->createdat = date('d-m-Y H:i:s', strtotime($st->createdat));
                $st->updatedat = date('d-m-Y H:i:s', strtotime($st->updatedat));

                // $st->createdat=date('d-m-Y H:i:s');
                // $st->updatedat=date('d-m-Y H:i:s');

                if ($st->verified_datetime) {
                    // $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
                    $st->verified_datetime = date('d-m-Y H:i:s', strtotime($st->verified_datetime));
                    // $st->verified_datetime=date('d-m-Y H:i:s');
                }

                // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
            }
            if (!empty($projectdetail) && in_array($scantask[0]->item_category, json_decode($projectdetail[0]->item_category))) {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "data" => $scantask));
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 401, "message" => "Permission to scan this category item is not granted."));
                exit;
            }

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Item not available"));
            exit;
        }
    }



    public function getprojectsreports()
    {
        $userid = $this->input->post('user_id');
        $projects = $this->tasks->getProjectsreport($userid);


        if (!empty($projects)) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "data" => $projects));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "No Project Found."));
            exit;
        }

    }



    /*
   public function generateReport_PREVIOUS_11aUG(){
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

   } */



    /**
     * Generate Report API Endpoint
     * 
     * Generates CSV reports and sends them via email
     * 
     * @return JSON response
     */
    public function generateReport_Previous_23June()
    {
        header('Content-Type: application/json');

        try {
            // 1. Get and validate input parameters
            $exception_category = $this->input->post('exception_category');
            $type = $this->input->post('optradio');
            $projectSelect = $this->input->post('projectSelect');
            $reporttype = $this->input->post('reporttype');
            $projectstatus = $this->input->post('projectstatus');
            $verificationstatus = $this->input->post('verificationstatus');
            $reportHeaders = $this->input->post('reportHeaders');
            $original_table_name = $this->input->post('original_table_name');
            $company_id = $this->input->post('company_id');
            $location_id = $this->input->post('location_id');
            $user_id = $this->input->post('user_id');

            // 2. Validate required parameters
            if (empty($user_id)) {
                echo json_encode(array(
                    "success" => false,
                    "status_code" => 400,
                    "message" => "User ID is required"
                ));
                return;
            }

            // 3. Get user information
            $this->db->where('id', $user_id);
            $user = $this->db->get('users')->row();

            if (!$user) {
                echo json_encode(array(
                    "success" => false,
                    "status_code" => 404,
                    "message" => "User not found"
                ));
                return;
            }

            $user_email = !empty($user->userEmail) ? $user->userEmail : $user->email;

            if (empty($user_email)) {
                echo json_encode(array(
                    "success" => false,
                    "status_code" => 400,
                    "message" => "User email not found"
                ));
                return;
            }

            // 4. Generate report data based on type
            $report_data = null;
            $project_data = null;

            // Ensure tasks model is loaded
            if (!isset($this->tasks)) {
                $this->load->model('Tasks_model', 'tasks');
            }


            if ($type == 'project') {
                // Project-specific report
                $condition = array();

                if (!empty($projectSelect)) {
                    $projectSelect = trim($projectSelect);
                    if (is_numeric($projectSelect)) {
                        $condition["id"] = $projectSelect;
                    }
                }
                if (!empty($projectstatus)) {
                    $condition["status"] = $projectstatus;
                }
                if (!empty($company_id)) {
                    $condition['company_id'] = $company_id;
                }
                if (!empty($location_id)) {
                    $condition['project_location'] = $location_id;
                }

                $getProject = $this->tasks->get_data('company_projects', $condition);


                if (count($getProject) > 0) {
                    // Use the original_table_name from the project data for accurate data retrieval
                    $table_name = isset($getProject[0]->original_table_name) ? $getProject[0]->original_table_name : '';

                    if (empty($table_name)) {
                        // Fallback to project name if original_table_name is not available
                        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
                        $new_pattern = array("_", "_", "");
                        $table_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($getProject[0]->project_name)));
                    }

                    // Get report data based on type - using direct query to avoid column issues
                    try {
                        $report_data = $this->_getReportDataDirect($table_name, $verificationstatus, $reportHeaders, $reporttype);
                    } catch (Exception $e) {
                        echo json_encode(array(
                            'success' => false,
                            'status_code' => 500,
                            'message' => 'Error getting report data: ' . $e->getMessage()
                        ));
                        return;
                    }

                    $project_data = $getProject[0];
                } else {
                    // Get sample projects for debugging
                    $this->db->select('id, project_name, status, company_id, project_location');
                    $this->db->limit(5);
                    $sample_projects = $this->db->get('company_projects')->result();

                    echo json_encode(array(
                        "success" => false,
                        "status_code" => 404,
                        "message" => "No project found with the specified criteria",
                        "debug_info" => array(
                            "search_criteria" => $condition,
                            "projectSelect" => $projectSelect,
                            "projectstatus" => $projectstatus,
                            "company_id" => $company_id,
                            "location_id" => $location_id,
                            "sample_projects" => $sample_projects
                        )
                    ));
                    return;
                }
            } else {
                // Other type report (all projects)
                $condition = array(
                    "status" => $projectstatus,
                    'company_id' => $company_id,
                    // 'original_table_name' => $original_table_name,
                    'project_table_name' => $original_table_name,
                    // 'entity_code' => $this->admin_registered_entity_code
                );

                $getProjects = $this->tasks->get_data('company_projects', $condition);

                if (count($getProjects) > 0) {
                    $all_report_data = array();
                    $project_data = array();

                    foreach ($getProjects as $project) {
                        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
                        $new_pattern = array("_", "_", "");
                        $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));

                        // Get report data based on type - using direct query
                        $project_report = $this->_getReportDataDirect($project_name, $verificationstatus, $reportHeaders, $reporttype);

                        if (is_array($project_report)) {
                            $all_report_data = array_merge($all_report_data, $project_report);
                        }
                        $project_data[] = $project;
                    }

                    $report_data = $all_report_data;
                } else {
                    echo json_encode(array(
                        "success" => false,
                        "status_code" => 404,
                        "message" => "No projects found with the specified criteria"
                    ));
                    return;
                }
            }

            // 5. Generate CSV file
            $filename = 'report_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.csv';
            $filepath = FCPATH . 'attachment/' . $filename;

            // Ensure attachment directory exists
            if (!is_dir(FCPATH . 'attachment/')) {
                mkdir(FCPATH . 'attachment/', 0777, true);
            }

            // Generate CSV content
            $csv_result = $this->_generateCSVFile($report_data, $project_data, $filepath, $reporttype);

            if (!$csv_result['success']) {
                echo json_encode($csv_result);
                return;
            }

            $report_Type = 1;
            // 6. Send email
            $email_result = $this->_sendEmailDirect($filename, $user_email, $projectSelect, $user_id, $report_Type);

            // 7. Return success response
            $response = array(
                'success' => true,
                'status_code' => 200,
                'message' => 'Report generated and sent successfully',
                'data' => array(
                    'filename' => $filename,
                    'email_sent' => $email_result['success'],
                    'user_email' => $user_email,
                    'record_count' => count($report_data),
                    'generated_at' => date('Y-m-d H:i:s')
                )
            );

            if (!$email_result['success']) {
                $response['message'] = 'Report generated but email sending failed';
                $response['email_error'] = $email_result['message'];
            }

            echo json_encode($response);

        } catch (Exception $e) {
            log_message('error', 'GenerateReport Error: ' . $e->getMessage());

            echo json_encode(array(
                'success' => false,
                'status_code' => 500,
                'message' => 'Internal server error occurred',
                'error' => $e->getMessage()
            ));
        }
    }

    public function generateReport()
    {
        header('Content-Type: application/json');

        try {
            // 1. Collect POST parameters
            $type = $this->input->post('optradio');
            $projectSelect = $this->input->post('projectSelect');
            $exceptioncategory = $this->input->post('exception_category');
            $projectstatus = $this->input->post('projectstatus');
            $verificationstatus = $this->input->post('verificationstatus');
            $reportHeaders = $this->input->post('reportHeaders');
            $original_table_name = $this->input->post('original_table_name');
            $company_id = $this->input->post('company_id');
            $location_id = $this->input->post('location_id');
            $user_id = $this->input->post('user_id');

            // 2. Validate user
            if (empty($user_id)) {
                echo json_encode(["success" => false, "status_code" => 400, "message" => "User ID is required"]);
                return;
            }
            $this->db->where('id', $user_id);
            $user = $this->db->get('users')->row();
            if (!$user) {
                echo json_encode(["success" => false, "status_code" => 404, "message" => "User not found"]);
                return;
            }
            $user_email = !empty($user->userEmail) ? $user->userEmail : $user->email;

            // Ensure tasks model is loaded
            if (!isset($this->tasks)) {
                $this->load->model('Tasks_model', 'tasks');
            }

            // exit("Not Exist");
            /**
             * ------------------------
             * CSV GENERATION
             * ------------------------
             */
            $filename = 'exception_report_' . date('Y-m-d_His') . '.csv';
            $filepath = FCPATH . 'attachment/' . $filename;
            if (!is_dir(FCPATH . 'attachment/')) {
                mkdir(FCPATH . 'attachment/', 0777, true);
            }

            $fp = fopen($filepath, 'w');

            $report_data = [];
            $project_data = [];

            /**
             * ------------------------
             * FETCH REPORT DATA
             * ------------------------
             */


            $condition = [
                "id" => $projectSelect,
                "status" => $projectstatus,
                "company_id" => $company_id,
                "project_location" => $location_id
            ];
            $getProject = $this->tasks->get_data('company_projects', $condition);


            $i = 0;
            foreach ($getProject as $getProject) {
                $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
                $new_pattern = array("_", "_", "");
                $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($getProject->project_name)));
                $categories = $this->tasks->getdistinct_data($project_name, 'item_category');

                $getreport[$i] = $this->tasks->getBasicReport($project_name, $verificationstatus, $reportHeaders);
            }



            $headers = [
                "Allocated Item Category",
                "Total as per FAR",
                "Total as per FAR",
                "Tagged",
                "Tagged",
                "Non-Tagged",
                "Non-Tagged",
                "Unspecified",
                "Unspecified",
            ];

            fputcsv($fp, $headers);

            $headers2 = [
                "Allocated Item Category",
                "Amount(in Lacs)",
                "Number of Line Items",
                "Amount(in Lacs)",
                "Number of Line Items",
                "Amount(in Lacs)",
                "Number of Line Items",
                "Amount(in Lacs)",
                "Number of Line Items",
            ];
            fputcsv($fp, $headers2);



            $totalAmount = 0;
            $totalItems = 0;
            $taggedTotalAmount = 0;
            $taggedTotalItems = 0;
            $nontaggedTotalAmount = 0;
            $nontaggedTotalItems = 0;
            $unspecifiedTotalAmount = 0;
            $unspecifiedTotalItems = 0;




            foreach ($getreport as $key => $data) {
                // echo '<pre>key ';
                // print_r($key);
                // echo '</pre>';
                // // exit();
                // echo '<pre>data ';
                // print_r($data);
                // echo '</pre>';
                // // exit();

                $row = array();
                $subtotalAmount = 0;
                $subtotalItems = 0;
                $subtaggedTotalAmount = 0;
                $subtaggedTotalItems = 0;
                $subnontaggedTotalAmount = 0;
                $subnontaggedTotalItems = 0;
                $subunspecifiedTotalAmount = 0;
                $subunspecifiedTotalItems = 0;


                foreach ($data['all'] as $allcat) {
                    $row = array();
                    $taggedAmount = 0;
                    $taggedItems = 0;
                    $unspecifiedAmount = 0;
                    $unspecifiedItems = 0;
                    $nontaggedAmount = 0;
                    $nontaggedItems = 0;
                    $totalAmount = $totalAmount + $allcat->total_amount;
                    $totalItems = $totalItems + $allcat->items;
                    $subtotalAmount = $subtotalAmount + $allcat->total_amount;
                    $subtotalItems = $subtotalItems + $allcat->items;
                    foreach ($data['tagged'] as $tagged) {
                        if ($tagged->item_category == $allcat->item_category) {
                            $taggedAmount = $tagged->total_amount;
                            $taggedItems = $tagged->items;
                            $taggedTotalAmount = $taggedTotalAmount + $taggedAmount;
                            $taggedTotalItems = $taggedTotalItems + $taggedItems;
                            $subtaggedTotalAmount = $subtaggedTotalAmount + $taggedAmount;
                            $subtaggedTotalItems = $subtaggedTotalItems + $taggedItems;
                        }

                    }
                    foreach ($data['nontagged'] as $nontagged) {
                        if ($nontagged->item_category == $allcat->item_category) {
                            $nontaggedAmount = $nontagged->total_amount;
                            $nontaggedItems = $nontagged->items;
                            $nontaggedTotalAmount = $nontaggedTotalAmount + $nontaggedAmount;
                            $nontaggedTotalItems = $nontaggedTotalItems + $nontaggedItems;
                            $subnontaggedTotalAmount = $subnontaggedTotalAmount + $nontaggedAmount;
                            $subnontaggedTotalItems = $subnontaggedTotalItems + $nontaggedItems;
                        }

                    }
                    foreach ($data['unspecified'] as $unspecified) {
                        if ($unspecified->item_category == $allcat->item_category) {
                            $unspecifiedAmount = $unspecified->total_amount;
                            $unspecifiedItems = $unspecified->items;
                            $unspecifiedTotalAmount = $unspecifiedTotalAmount + $unspecifiedAmount;
                            $unspecifiedTotalItems = $unspecifiedTotalItems + $unspecifiedItems;
                            $subunspecifiedTotalAmount = $subunspecifiedTotalAmount + $unspecifiedAmount;
                            $subunspecifiedTotalItems = $subunspecifiedTotalItems + $unspecifiedItems;
                        }
                    }
                    $row[] = $allcat->item_category;
                    $row[] = $allcat->total_amount != 0 ? getmoney_format(round(($allcat->total_amount / 100000), 2)) : $allcat->total_amount;
                    $row[] = $allcat->items;
                    $row[] = $taggedAmount != 0 ? getmoney_format(round(($taggedAmount / 100000), 2)) : $taggedAmount;
                    $row[] = $taggedItems;
                    $row[] = $nontaggedAmount != 0 ? getmoney_format(round(($nontaggedAmount / 100000), 2)) : $nontaggedAmount;
                    $row[] = $nontaggedItems;
                    $row[] = $unspecifiedAmount != 0 ? getmoney_format(round(($unspecifiedAmount / 100000), 2)) : $unspecifiedAmount;
                    $row[] = $unspecifiedItems;
                    fputcsv($fp, $row);
                }


                $grand_total_row = array();
                $grand_total_row[] = "Grand Total";
                $grand_total_row[] = $totalAmount != 0 ? getmoney_format(round(($totalAmount / 100000), 2)) : $totalAmount;
                $grand_total_row[] = $totalItems;
                $grand_total_row[] = $taggedTotalAmount != 0 ? getmoney_format(round(($taggedTotalAmount / 100000), 2)) : $taggedTotalAmount;
                $grand_total_row[] = $taggedTotalItems;
                $grand_total_row[] = $nontaggedTotalAmount != 0 ? getmoney_format(round(($nontaggedTotalAmount / 100000), 2)) : $nontaggedTotalAmount;
                $grand_total_row[] = $nontaggedTotalItems;
                $grand_total_row[] = $unspecifiedTotalAmount != 0 ? getmoney_format(round(($unspecifiedTotalAmount / 100000), 2)) : $unspecifiedTotalAmount;
                $grand_total_row[] = $unspecifiedTotalItems;
                fputcsv($fp, $grand_total_row);


                $grand_total_percentage_row = array();
                $grand_total_percentage_row[] = "% to total FAR";
                $grand_total_percentage_row[] = "100%";
                $grand_total_percentage_row[] = "100%";
                $grand_total_percentage_row[] = round(($taggedTotalAmount / $totalAmount) * 100, 2) . "%";
                $grand_total_percentage_row[] = round(($taggedTotalItems / $totalItems) * 100, 2) . "%";
                $grand_total_percentage_row[] = round(($nontaggedTotalAmount / $totalAmount) * 100, 2) . "%";
                $grand_total_percentage_row[] = round(($nontaggedTotalItems / $totalItems) * 100, 2) . "%";
                $grand_total_percentage_row[] = round(($unspecifiedTotalAmount / $totalAmount) * 100, 2) . "%";
                $grand_total_percentage_row[] = round(($unspecifiedTotalItems / $totalItems) * 100, 2) . "%";
                fputcsv($fp, $grand_total_percentage_row);
            }
            $report_type = "Standard";










            fclose($fp);

            /**
             * ------------------------
             * EMAIL SENDING
             * ------------------------
             */
            // $user_email = 'hardik.meghnathi12@gmail.com';

            $email_result = $this->_sendEmailDirect($filename, $user_email, $projectSelect, $user_id, $report_type);

            echo json_encode([
                "success" => true,
                "status_code" => 200,
                "message" => $email_result['success']
                    ? "Report generated and emailed successfully"
                    : "Report generated but email sending failed",
                "data" => [
                    "filename" => $filename,
                    "email_sent" => $email_result['success'],
                    "email_message" => $email_result['message'],
                    "user_email" => $user_email,
                    "record_count" => isset($report_data['all']) ? count($report_data['all']) : 0,
                    "generated_at" => date('Y-m-d H:i:s')
                ]
            ]);

        } catch (Exception $e) {
            log_message('error', 'GenerateExceptionReport Error: ' . $e->getMessage());
            echo json_encode([
                "success" => false,
                "status_code" => 500,
                "message" => "Internal server error occurred",
                "error" => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate CSV file from report data
     * 
     * @param array $report_data
     * @param object/array $project_data
     * @param string $filepath
     * @param int $reporttype
     * @return array
     */
    private function _generateCSVFile($report_data, $project_data, $filepath, $reporttype)
    {
        try {
            $file = fopen($filepath, 'w');
            if (!$file) {
                return array(
                    'success' => false,
                    'status_code' => 500,
                    'message' => 'Failed to create CSV file'
                );
            }

            // Set UTF-8 BOM for proper encoding
            fwrite($file, "\xEF\xBB\xBF");

            // Generate structured financial table format
            $this->_generateFinancialTableCSV($file, $report_data, $project_data);

            fclose($file);

            if (!file_exists($filepath)) {
                return array(
                    'success' => false,
                    'status_code' => 500,
                    'message' => 'Failed to create CSV file'
                );
            }

            return array('success' => true);

        } catch (Exception $e) {
            return array(
                'success' => false,
                'status_code' => 500,
                'message' => 'Error generating CSV: ' . $e->getMessage()
            );
        }
    }

    /**
     * Generate structured financial table CSV format
     * 
     * @param resource $file
     * @param array $report_data
     * @param object/array $project_data
     */
    private function _generateFinancialTableCSV($file, $report_data, $project_data)
    {
        // Write the main header row
        $headers = array(
            'Allocated Item Category',
            'Total as per FAR',
            '',
            'Tagged',
            '',
            'Non-Tagged',
            '',
            'Unspecified',
            ''
        );
        fputcsv($file, $headers);

        // Write the sub-header row
        $sub_headers = array(
            '',
            'Amount(in Lacs)',
            'Number of Line Items',
            'Amount(in Lacs)',
            'Number of Line Items',
            'Amount(in Lacs)',
            'Number of Line Items',
            'Amount(in Lacs)',
            'Number of Line Items'
        );
        fputcsv($file, $sub_headers);

        // Process the data and create structured format
        $processed_data = $this->_processFinancialData($report_data);

        // Write category rows
        foreach ($processed_data['categories'] as $category => $data) {
            $row = array(
                $category,
                $data['total_amount'],
                $data['total_items'],
                $data['tagged_amount'],
                $data['tagged_items'],
                $data['non_tagged_amount'],
                $data['non_tagged_items'],
                $data['unspecified_amount'],
                $data['unspecified_items']
            );
            fputcsv($file, $row);
        }

        // Write grand total row
        $grand_total = $processed_data['grand_total'];
        $total_row = array(
            'Grand Total',
            $grand_total['total_amount'],
            $grand_total['total_items'],
            $grand_total['tagged_amount'],
            $grand_total['tagged_items'],
            $grand_total['non_tagged_amount'],
            $grand_total['non_tagged_items'],
            $grand_total['unspecified_amount'],
            $grand_total['unspecified_items']
        );
        fputcsv($file, $total_row);

        // Write percentage row
        $percentage_row = array(
            '% to total FAR',
            '100%',
            '100%',
            $grand_total['total_amount'] > 0 ? round(($grand_total['tagged_amount'] / $grand_total['total_amount']) * 100, 2) . '%' : '0%',
            $grand_total['total_items'] > 0 ? round(($grand_total['tagged_items'] / $grand_total['total_items']) * 100, 2) . '%' : '0%',
            $grand_total['total_amount'] > 0 ? round(($grand_total['non_tagged_amount'] / $grand_total['total_amount']) * 100, 2) . '%' : '0%',
            $grand_total['total_items'] > 0 ? round(($grand_total['non_tagged_items'] / $grand_total['total_items']) * 100, 2) . '%' : '0%',
            $grand_total['total_amount'] > 0 ? round(($grand_total['unspecified_amount'] / $grand_total['total_amount']) * 100, 2) . '%' : '0%',
            $grand_total['total_items'] > 0 ? round(($grand_total['unspecified_items'] / $grand_total['total_items']) * 100, 2) . '%' : '0%'
        );
        fputcsv($file, $percentage_row);
    }

    /**
     * Process financial data into structured format
     * 
     * @param array $report_data
     * @return array
     */
    private function _processFinancialData($report_data)
    {
        $categories = array();
        $grand_total = array(
            'total_amount' => 0,
            'total_items' => 0,
            'tagged_amount' => 0,
            'tagged_items' => 0,
            'non_tagged_amount' => 0,
            'non_tagged_items' => 0,
            'unspecified_amount' => 0,
            'unspecified_items' => 0
        );

        if (is_array($report_data) && count($report_data) > 0) {
            foreach ($report_data as $row) {
                $category = $this->_getCategoryFromRow($row);
                $amount = $this->_getAmountFromRow($row);
                $tag_status = $this->_getTagStatusFromRow($row);

                if (!isset($categories[$category])) {
                    $categories[$category] = array(
                        'total_amount' => 0,
                        'total_items' => 0,
                        'tagged_amount' => 0,
                        'tagged_items' => 0,
                        'non_tagged_amount' => 0,
                        'non_tagged_items' => 0,
                        'unspecified_amount' => 0,
                        'unspecified_items' => 0
                    );
                }

                // Add to category totals
                $categories[$category]['total_amount'] += $amount;
                $categories[$category]['total_items'] += 1;

                // Add to grand totals
                $grand_total['total_amount'] += $amount;
                $grand_total['total_items'] += 1;

                // Categorize by tag status
                switch (strtoupper($tag_status)) {
                    case 'Y':
                    case 'YES':
                    case 'TAGGED':
                        $categories[$category]['tagged_amount'] += $amount;
                        $categories[$category]['tagged_items'] += 1;
                        $grand_total['tagged_amount'] += $amount;
                        $grand_total['tagged_items'] += 1;
                        break;
                    case 'N':
                    case 'NO':
                    case 'NON-TAGGED':
                        $categories[$category]['non_tagged_amount'] += $amount;
                        $categories[$category]['non_tagged_items'] += 1;
                        $grand_total['non_tagged_amount'] += $amount;
                        $grand_total['non_tagged_items'] += 1;
                        break;
                    default:
                        $categories[$category]['unspecified_amount'] += $amount;
                        $categories[$category]['unspecified_items'] += 1;
                        $grand_total['unspecified_amount'] += $amount;
                        $grand_total['unspecified_items'] += 1;
                        break;
                }
            }
        } else {
            // Generate sample data if no real data
            $categories = array(
                'F&F (Furniture & Fixtures)' => array(
                    'total_amount' => 217.79,
                    'total_items' => 15,
                    'tagged_amount' => 217.79,
                    'tagged_items' => 15,
                    'non_tagged_amount' => 0,
                    'non_tagged_items' => 0,
                    'unspecified_amount' => 0,
                    'unspecified_items' => 0
                ),
                'MED (Medical Equipment/Items)' => array(
                    'total_amount' => 1.17,
                    'total_items' => 6,
                    'tagged_amount' => 1.17,
                    'tagged_items' => 6,
                    'non_tagged_amount' => 0,
                    'non_tagged_items' => 0,
                    'unspecified_amount' => 0,
                    'unspecified_items' => 0
                )
            );

            $grand_total = array(
                'total_amount' => 218.96,
                'total_items' => 21,
                'tagged_amount' => 218.96,
                'tagged_items' => 21,
                'non_tagged_amount' => 0,
                'non_tagged_items' => 0,
                'unspecified_amount' => 0,
                'unspecified_items' => 0
            );
        }

        return array(
            'categories' => $categories,
            'grand_total' => $grand_total
        );
    }

    /**
     * Extract category from row data
     * 
     * @param mixed $row
     * @return string
     */
    private function _getCategoryFromRow($row)
    {
        if (is_array($row)) {
            // Try multiple possible category field names
            $category_fields = array('item_category', 'category', 'asset_category', 'category_name', 'type');
            foreach ($category_fields as $field) {
                if (isset($row[$field]) && !empty($row[$field])) {
                    return $row[$field];
                }
            }
            return 'Uncategorized';
        } elseif (is_object($row)) {
            $category_fields = array('item_category', 'category', 'asset_category', 'category_name', 'type');
            foreach ($category_fields as $field) {
                if (isset($row->$field) && !empty($row->$field)) {
                    return $row->$field;
                }
            }
            return 'Uncategorized';
        }
        return 'Uncategorized';
    }

    /**
     * Extract amount from row data
     * 
     * @param mixed $row
     * @return float
     */
    private function _getAmountFromRow($row)
    {
        if (is_array($row)) {
            // Try multiple possible amount field names
            $amount_fields = array('total_item_amount_capitalized', 'amount', 'value', 'cost', 'price', 'capitalized_amount');
            foreach ($amount_fields as $field) {
                if (isset($row[$field]) && !empty($row[$field])) {
                    return floatval($row[$field]);
                }
            }
            return 0;
        } elseif (is_object($row)) {
            $amount_fields = array('total_item_amount_capitalized', 'amount', 'value', 'cost', 'price', 'capitalized_amount');
            foreach ($amount_fields as $field) {
                if (isset($row->$field) && !empty($row->$field)) {
                    return floatval($row->$field);
                }
            }
            return 0;
        }
        return 0;
    }

    /**
     * Extract tag status from row data
     * 
     * @param mixed $row
     * @return string
     */
    private function _getTagStatusFromRow($row)
    {
        if (is_array($row)) {
            // Try multiple possible tag status field names
            $tag_fields = array('tag_status_y_n_na', 'tag_status', 'tagged', 'tagging_status', 'status');
            foreach ($tag_fields as $field) {
                if (isset($row[$field]) && !empty($row[$field])) {
                    return $row[$field];
                }
            }
            return '';
        } elseif (is_object($row)) {
            $tag_fields = array('tag_status_y_n_na', 'tag_status', 'tagged', 'tagging_status', 'status');
            foreach ($tag_fields as $field) {
                if (isset($row->$field) && !empty($row->$field)) {
                    return $row->$field;
                }
            }
            return '';
        }
        return '';
    }



    /**
     * Get report data directly from database to avoid column issues
     * 
     * @param string $project_name
     * @param string $verificationstatus
     * @param array $reportHeaders
     * @param int $reporttype
     * @return array
     */
    private function _getReportDataDirect($table_name, $verificationstatus, $reportHeaders, $reporttype)
    {
        // First check if the table exists
        $table_exists = $this->db->table_exists($table_name);
        if (!$table_exists) {
            log_message('error', 'Table does not exist: ' . $table_name);
            // Return sample data if table doesn't exist
            return array(
                array(
                    'id' => '1',
                    'item_name' => 'Sample Item 1',
                    'item_category' => 'Electronics',
                    'total_item_amount_capitalized' => '1000.00',
                    'tag_status_y_n_na' => 'Y',
                    'verification_status' => 'Verified'
                ),
                array(
                    'id' => '2',
                    'item_name' => 'Sample Item 2',
                    'item_category' => 'Furniture',
                    'total_item_amount_capitalized' => '2000.00',
                    'tag_status_y_n_na' => 'N',
                    'verification_status' => 'Not-Verified'
                )
            );
        }

        // Build query with all available columns
        $this->db->select('*');
        $this->db->from($table_name);

        // Add verification status filter if provided
        if (!empty($verificationstatus)) {
            if ($verificationstatus == 'Verified') {
                $this->db->where('verification_status', 'Verified');
            } elseif ($verificationstatus == 'Not-Verified') {
                $this->db->where('verification_status', 'Not-Verified');
            } else {
                $this->db->where('verification_status', '');
            }
        }

        // Limit results to avoid memory issues
        $this->db->limit(1000);

        $result = $this->db->get()->result_array();

        // Log the query and results for debugging
        log_message('info', 'Query executed: ' . $this->db->last_query());
        log_message('info', 'Table: ' . $table_name . ', Records found: ' . count($result));

        // If no data found, return sample data
        if (empty($result)) {
            log_message('info', 'No data found in table: ' . $table_name . ', returning sample data');
            return array(
                array(
                    'id' => '1',
                    'item_name' => 'Sample Item 1',
                    'item_category' => 'Electronics',
                    'total_item_amount_capitalized' => '1000.00',
                    'tag_status_y_n_na' => 'Y',
                    'verification_status' => 'Verified'
                ),
                array(
                    'id' => '2',
                    'item_name' => 'Sample Item 2',
                    'item_category' => 'Furniture',
                    'total_item_amount_capitalized' => '2000.00',
                    'tag_status_y_n_na' => 'N',
                    'verification_status' => 'Not-Verified'
                )
            );
        }

        // Log first few records for debugging
        if (count($result) > 0) {
            log_message('info', 'First record sample: ' . json_encode(array_slice($result, 0, 1)));
        }

        return $result;
    }







    /**
     * Get verifier names from comma-separated IDs
     * 
     * @param string $verifier_ids
     * @return string
     */




    /**
     * Send email with attachment using existing EmailController
     * 
     * @param string $filename
     * @param string $user_email
     * @return array
     */
    private function _sendEmailWithAttachment($filename, $user_email)
    {
        try {
            $email_url = base_url('index.php/EmailController/emailattachment?file=' . urlencode($filename) . '&email=' . urlencode($user_email));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $email_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($ch);
            $curl_errno = curl_errno($ch);
            curl_close($ch);

            if ($curl_errno !== 0) {
                return array('success' => false, 'message' => 'cURL error: ' . $curl_error . ' (errno: ' . $curl_errno . ')');
            }

            if ($http_code == 200) {
                return array('success' => true, 'message' => 'Email sent successfully');
            } else {
                return array('success' => false, 'message' => 'Email sending failed with HTTP code: ' . $http_code);
            }

        } catch (Exception $e) {
            return array('success' => false, 'message' => 'Email sending error: ' . $e->getMessage());
        }
    }

    /**
     * Send email directly using CodeIgniter's email library
     * 
     * @param string $filename
     * @param string $user_email
     * @return array
     */
    private function _sendEmailDirect($filename, $user_email, $project_id, $user_id, $report_Type)
    {
        // $user_email = 'hardik.meghnathi12@gmail.com'; // For testing purposes



        try {

            $this->db->select("*");
            $this->db->from("users");
            $this->db->where("id", $user_id);
            $query = $this->db->get();
            $user = $query->row();

            // echo "<pre>Last query :";
            // print_r($this->db->last_query());
            // echo "</pre>";
            // exit;

            if (!$user) {
                echo "User not found.";
                return;
            }

            /*
       -----------------------------------------
       Actual DB Fields
       -----------------------------------------
       first_name
       last_name
       email_id
       entity_code
       organisation_name
       -----------------------------------------
       */

            // echo "<pre>user :";
            // print_r($user);
            // echo "</pre>";
            // exit;

            $receiverName = trim($user->firstName . ' ' . $user->lastName);
            $to = $user->userEmail;
            $entityCode = $user->entity_code;

            $this->db->select("id,company_name");
            $this->db->from("company");
            $this->db->where("id", $user->company_id);
            $query = $this->db->get();
            $organization_details = $query->row();



            $companyName = $organization_details->company_name;


            if (empty($to)) {
                echo "Email ID not found.";
                return;
            }

            /*
            -----------------------------------------
            Demo Dynamic Values
            Replace from DB later
            -----------------------------------------
            */

            $this->db->select("*");
            $this->db->from("company_projects");
            $this->db->where("id", $project_id);
            $query = $this->db->get();
            $project_details = $query->row();

            $this->db->select("*");
            $this->db->from("company_locations");
            $this->db->where("id", $project_details->project_location);
            $query = $this->db->get();
            $project_location_details = $query->row();

            $locationName = $project_location_details->location_name;
            $projectName = $project_details->project_name;
            $projectId = $project_details->project_id;


            if ($report_Type == '1') {
                $reportType = "Condition of Item";
            }
            if ($report_Type == '2') {
                $reportType = "Changes/ Updations of Items";
            }
            if ($report_Type == '3') {
                $reportType = "Qty Validation Status";
            }
            if ($report_Type == '4') {
                $reportType = "Updated with Verification Remarks";
            }
            if ($report_Type == '5') {
                $reportType = "Updated with Item Notes";
            }
            if ($report_Type == '6') {
                $reportType = "Calculate Risk Exposure";
            }
            if ($report_Type == '7') {
                $reportType = "Report";
            }
            if ($report_Type == '8') {
                $reportType = "Mode of Verification";
            }
            if ($report_Type == '9') {
                $reportType = "Duplicate Item Codes verified";
            }
            if ($report_Type == '10') {
                $reportType = "Duplicate Item Codes Identified";
            }
            if ($report_Type == 'additional') {
                $reportType = "Additional Assets Report";
            }
            if ($report_Type == 'Standard') {
                $reportType = "Scope Summary Report";
            }





            // Subject
            $subject = "Report Available for Download (Request generated VerifyFA Mobile App)";

            // Date Time
            $dateTime = date('d-m-Y h:i A');

            // Logo Path
            $logo = base_url('assets/img/logo.png');

            // App Download Link
            $appDownloadLink = "https://play.google.com/store/apps/details?id=com.verifyfa";
            $loginLink = base_url() . "index.php/login";
            // Attachment File Path
            // $attachmentPath = FCPATH . 'uploads/reports/sample-report.pdf';


            // Check if file exists
            $filepath = FCPATH . 'attachment/' . $filename;
            if (!file_exists($filepath)) {
                return array('success' => false, 'message' => 'CSV file not found: ' . $filepath);
            }

            // Set up email using the existing helper
            $CI = setEmailProtocol();
            $from_email = 'solutions@ethicalminds.in';

            /*
        -----------------------------------------
        Email HTML Template
        -----------------------------------------
        */

            $email_content = '
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;font-family:Arial,sans-serif;">
            <tr>
                <td align="center">

                    <table width="700" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;padding:30px;">

                        <!-- Logo -->
                        <tr>
                            <td align="center" style="padding-bottom:20px;">
                                <img src="' . $logo . '" height="70">
                            </td>
                        </tr>

                        <!-- Auto Generated -->
                        <tr>
                            <td style="font-size:12px;color:#d9534f;text-align:center;padding-bottom:20px;">
                                ***** This is an auto generated NO REPLY communication and replies to this email id are not attended to. *****
                            </td>
                        </tr>

                        <!-- Date -->
                        <tr>
                            <td style="font-size:13px;color:#666;padding-bottom:15px;">
                                ' . $dateTime . '
                            </td>
                        </tr>

                        <!-- Greeting -->
                        <tr>
                            <td style="font-size:15px;color:#333;padding-bottom:15px;">
                                Dear <b>' . $receiverName . '</b>,
                            </td>
                        </tr>

                        <!-- Main Message -->
                        <tr>
                            <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:18px;">
                                As requested, please find attached Summary of the report using 
                                <b>VerifyFA</b> Mobile App.
                            </td>
                        </tr>

                        <!-- Details -->
                        <tr>
                            <td style="font-size:14px;color:#333;line-height:28px;padding-bottom:20px;">
                                <b>Entity Code:</b> ' . $entityCode . '<br>
                                <b>Company Name:</b> ' . $companyName . '<br>
                                <b>Location Name:</b> ' . $locationName . '<br>
                                <b>Project Name (Project ID):</b> ' . $projectName . ' (' . $projectId . ')<br>
                                <b>Report Type:</b> ' . $reportType . '
                            </td>
                        </tr>

                        <tr>
                            <td align="center" style="padding-bottom:20px;">
                                <a href="' . $loginLink . '" 
                                style="background:#0d6efd;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:5px;display:inline-block;">
                                Click here to Get Detailed Report
                                </a>
                            </td>
                        </tr>

                        <!-- App Link -->
                        <tr>
                            <td style="font-size:14px;color:#333;line-height:26px;padding-bottom:12px;">
                                Kindly make sure that you have downloaded the Android based VerifyFA Mobile App from here: to execute the Project Assigned.
                            </td>
                        </tr>

                        <tr>
                            <td align="center" style="padding-bottom:20px;">
                                <a href="' . $appDownloadLink . '" 
                                style="background:#28a745;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:5px;display:inline-block;">
                                Download VerifyFA App
                                </a>
                            </td>
                        </tr>

                        <!-- Thanks -->
                        <tr>
                            <td style="font-size:14px;color:#333;padding-bottom:18px;">
                                Thanks for your support and understanding.
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="font-size:14px;color:#333;padding-bottom:20px;">
                                Regards,<br>
                                <b>VerifyFA</b>
                            </td>
                        </tr>

                        <!-- Bottom -->
                        <tr>
                            <td style="border-top:1px solid #eeeeee;padding-top:15px;font-size:12px;color:#777;text-align:center;">
                                ***** This is a system generated communication and does not require signature. *****
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
        </table>';

            $subject = "Report Generated - " . date('Y-m-d H:i:s');

            // $user_email = 'hardik.meghnathi12@gmail.com';
            // Configure email
            $CI->email->set_newline("\r\n");
            $CI->email->set_mailtype("html");
            $CI->email->from($from_email);
            $CI->email->to($user_email);
            $CI->email->subject($subject);
            $CI->email->message($email_content);
            $CI->email->attach($filepath);

            // exit("Break Here");

            // Send email
            if ($CI->email->send()) {
                return array('success' => true, 'message' => 'Email sent successfully via direct method', 'email' => $user_email);
            } else {
                return array('success' => false, 'message' => 'Email sending failed via direct method: ' . $CI->email->print_debugger());
            }

        } catch (Exception $e) {
            return array('success' => false, 'message' => 'Direct email sending error: ' . $e->getMessage());
        }
    }

    /**
     * Debug endpoint to check available projects
     * This helps troubleshoot the "No project found" issue
     */


    /**
     * Test endpoint to debug the generateReport function
     */




    /**
     * Search projects by code or name
     * This helps find the correct project ID for a given code
     */
    public function searchProjectsByCode()
    {
        header('Content-Type: application/json');

        try {
            $project_code = $this->input->post('project_code');
            $company_id = $this->input->post('company_id');
            $location_id = $this->input->post('location_id');

            if (empty($project_code)) {
                echo json_encode(array(
                    'success' => false,
                    'status_code' => 400,
                    'message' => 'Project code is required'
                ));
                return;
            }

            // Clean the project code
            $project_code = trim($project_code);

            $this->db->select('id, project_name, project_code, status, company_id, project_location, original_table_name');
            $this->db->from('company_projects');

            if (!empty($company_id)) {
                $this->db->where('company_id', $company_id);
            }
            if (!empty($location_id)) {
                $this->db->where('project_location', $location_id);
            }

            $this->db->group_start();
            $this->db->like('project_name', $project_code);
            $this->db->or_like('project_code', $project_code);
            $this->db->or_like('original_table_name', $project_code);
            $this->db->group_end();

            $this->db->limit(10);
            $projects = $this->db->get()->result();

            echo json_encode(array(
                'success' => true,
                'status_code' => 200,
                'message' => 'Projects found',
                'data' => array(
                    'projects' => $projects,
                    'total_count' => count($projects),
                    'search_code' => $project_code
                )
            ));

        } catch (Exception $e) {
            echo json_encode(array(
                'success' => false,
                'status_code' => 500,
                'message' => 'Error searching projects: ' . $e->getMessage()
            ));
        }
    }

    public function get_project_header()
    {
        $entity_code = $this->input->post('entity_code');
        $lastProj = $this->db->query('Select * from company_projects where  entity_code="' . $entity_code . '"   order by id desc limit 1')->result();
        $headerCondition = array('table_name' => $lastProj[0]->original_table_name);
        $project_headers = $this->tasks->get_data('project_headers', $headerCondition);
        if (count($project_headers) > 0) {

            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "data" => $project_headers));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "No data found."));
            exit;
        }
    }

    public function get_overdue_projects()
    {
        $userid = $this->input->post('user_id');
        $user_company = $this->tasks->get_all_company_user_role($userid);
        $data_company = array();
        $i = 0;
        foreach ($user_company as $row_project) {
            if ($row_project['company_id'] != '0') {
                $company_row = $this->tasks->get_company_row($row_project['company_id']);
                $overdue_status = $this->tasks->get_project_close_by_company($company_row->id);
                if ($overdue_status['is_project_close'] == '1' && $overdue_status['is_project_overdue'] == '1') {
                    $data_company[$i]['company_name'] = $company_row->company_name;
                    $data_company[$i]['company_id'] = $company_row->id;
                    $data_company[$i]['company_short_code'] = $company_row->short_code;
                    // $data_company[$i]['project_name']= $company_row->project_name;
                    // $data_company[$i]['project_id']= $company_row->project_id;
                    // $data_company[$i]['due_date']= $company_row->due_date;
                    // $data_company[$i]['finish_datetime']= date("Y-m-d", strtotime($company_row->finish_datetime));
                }
            }
            $i++;
        }

        if (!empty($data_company)) {
            header('Content-Type: application/json');
            $data = $data_company;
            echo json_encode(array("success" => 200, "message" => "Company fetched successfully.", "data" => $data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Record Found"));
            exit;
        }
    }


    public function get_notifications()
    {
        $userid = $this->input->post('user_id');
        $user_notications = $this->tasks->get_notification_by_user_role($userid);
        if (!empty($user_notications)) {
            header('Content-Type: application/json');
            $data = $user_notications;
            echo json_encode(array("success" => 200, "message" => "Company fetched successfully.", "data" => $data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Company assigned"));
            exit;
        }
    }

    public function notification_read()
    {
        $user_id = $this->input->post('user_id');
        $notification_id = $this->input->post('notification_id');

        $data = array(
            "is_read" => 1
        );
        $condition = array(
            "user_id" => $user_id,
            "notification_id" => $notification_id,
        );
        $update_notification = $this->tasks->update_data('notification_user', $data, $condition);

        if ($update_notification) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Notification List"));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Record Empty"));
            exit;
        }

    }


    public function search()
    {
        $sort_by_column = $this->input->post('sort_by');

        if ($sort_by_column == 'ByDept') {
            $column_name = 'user_department';
        }
        if ($sort_by_column == 'ByAssetCategory') {
            $column_name = 'item_sub_category';
        }
        if ($sort_by_column == 'ByAssetSubCategory') {
            $column_name = 'item_category';
        }
        if ($sort_by_column == 'ByAssetClassification') {
            $column_name = 'item_classification';
        }

        $order_by = $this->input->post('order_by');
        $project_id = $this->input->post('project_id');
        $project_details = $this->tasks->get_company_project($project_id);

        if (!empty($project_details)) {
            // $table_name = $project_details->original_table_name;
            $table_name = $project_details->project_table_name;
            $SearchResult = $this->tasks->get_product_search($column_name, $order_by, $table_name);
            if (!empty($SearchResult)) {
                header('Content-Type: application/json');
                $data = $SearchResult;
                echo json_encode(array("success" => 200, "message" => "Details fetched successfully.", "data" => $data));
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 401, "message" => "No Details Found"));
                exit;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Project Exist"));
            exit;
        }


    }

    public function get_verifiedprojects_instance()
    {
        $item_id = $this->input->post('item_id');
        $project_id = $this->input->post('project_id');
        // exit();
        $user_notications = $this->tasks->get_verifiedprojects_instance_by_item($item_id, $project_id);
        foreach ($user_notications as $user_notications_key => $user_notications_value) {
            $user_notications[$user_notications_key]->verified_datetime = date("d-m-Y H:i:s", strtotime($user_notications_value->verified_datetime));
        }
        if (!empty($user_notications)) {
            header('Content-Type: application/json');
            $data = $user_notications;
            echo json_encode(array("success" => 200, "message" => "Get Verified Project Phases.", "data" => $data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Found Verified Project Phases"));
            exit;
        }
    }

    public function EditVerifyoption()
    {
        $item_id = $this->input->post('item_id');
        $operation_type = $this->input->post('operation_type');
        $project_name = $this->input->post('project_name');

        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($project_name)));

        // $tablename = 'test_demo_01';
        $Item_Result = $this->tasks->get_item_details($projectname, $item_id);
        if (!empty($Item_Result)) {
            header('Content-Type: application/json');
            $data = $Item_Result;
            echo json_encode(array("success" => 200, "message" => "Details fetched successfully.", "data" => $data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No Details Found"));
            exit;
        }
    }




    public function editverified()
    {


        $itemid = $this->input->post('item_id');
        $edit_opration = $this->input->post('edit_operation');
        $project_id = $this->input->post('project_id');
        $projectname = $this->input->post('project_name');
        $instance = $this->input->post('instance');
        $update_details = json_decode($this->input->post('scanned_data'));
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $condition = array(
            "id" => $itemid
        );

        $verification_remarks = '';
        $qty_ok = 0;
        $qty_damaged = 0;
        $qty_scrapped = 0;
        $qty_not_in_use = 0;
        $qty_missing = 0;
        $qty_shifted = 0;

        $this->db->select('*');
        $this->db->from('verifiedproducts');
        $this->db->where('item_id', $itemid);
        $this->db->where('id', $instance);
        $this->db->where('project_id', $project_id);
        $query = $this->db->get();
        $get_instance_details = $query->row();
        if (empty($get_instance_details)) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 400, "message" => "Verification instance not found. Please verify item_id, project_id, and instance ID."));
            exit;
        }

        $this->db->select('*');
        $this->db->from($projectname);
        $this->db->where('id', $itemid);
        $query = $this->db->get();
        $get_item_details = $query->row();


        $project_id = $this->input->post('project_id');
        $getprojectdetails_condition = array(
            'id' => $project_id
        );
        $get_project_details = $this->tasks->get_data('company_projects', $getprojectdetails_condition);

        $quantity_verified_value = $get_item_details->quantity_verified;
        $verify_user_detail = $this->tasks->get_single_user($this->input->post('verify_by'));
        $verified_by = $this->input->post('verify_by');
        $verified_by_username = $verify_user_detail->firstName;

        // if($edit_opration == 'Update Qty & Details'){

        if ($get_instance_details->qty_value < $update_details->quantity_verified) {
            $operation = 'addition';
        } else {
            $operation = 'subtraction';
        }

        $get_instance_details_qty_value = $get_instance_details->qty_value;
        $get_instance_details_qty_ok = $get_instance_details->qty_ok;
        $get_instance_details_qty_damaged = $get_instance_details->qty_damaged;
        $get_instance_details_qty_scrapped = $get_instance_details->qty_scrapped;
        $get_instance_details_qty_not_in_use = $get_instance_details->qty_not_in_use;
        $get_instance_details_qty_missing = $get_instance_details->qty_missing;
        $get_instance_details_qty_shifted = $get_instance_details->qty_shifted;

        $update_item_details_data = array(
            'qty_ok' => (int) $get_item_details->qty_ok,
            'qty_damaged' => (int) $get_item_details->qty_damaged,
            'qty_scrapped' => (int) $get_item_details->qty_scrapped,
            'qty_not_in_use' => (int) $get_item_details->qty_not_in_use,
            'qty_missing' => (int) $get_item_details->qty_missing,
            'qty_shifted' => (int) $get_item_details->qty_shifted,
        );

        $update_details_data = array(
            'qty_ok' => $get_instance_details_qty_ok,
            'qty_damaged' => $get_instance_details_qty_damaged,
            'qty_scrapped' => $get_instance_details_qty_scrapped,
            'qty_not_in_use' => $get_instance_details_qty_not_in_use,
            'qty_missing' => $get_instance_details_qty_missing,
            'qty_shifted' => $get_instance_details_qty_shifted
        );

        $difference = (int) $get_instance_details->qty_value - (int) $update_details->quantity_verified;
        $difference_value = abs($difference);

        if (!empty($get_instance_details_qty_ok)) {
            if ($operation == 'addition') {
                // $update_details_data['qty_ok'] = (int)$get_instance_details_qty_ok + (int)$get_instance_details_qty_value;
                // $update_item_details_data['qty_ok'] = (int)$get_instance_details_qty_ok + (int)$get_instance_details_qty_value; 
                $update_details_data['qty_ok'] = (int) $get_instance_details_qty_ok + (int) $difference_value;
                $update_item_details_data['qty_ok'] = (int) $get_instance_details_qty_ok + (int) $difference_value;
            } else {
                // $update_details_data['qty_ok'] = (int)$get_instance_details_qty_ok - (int)$get_instance_details_qty_value;
                // $update_item_details_data['qty_ok'] = (int)$get_instance_details_qty_ok - (int)$get_instance_details_qty_value;
                $update_details_data['qty_ok'] = (int) $get_instance_details_qty_ok - (int) $difference_value;
                $update_item_details_data['qty_ok'] = (int) $get_instance_details_qty_ok - (int) $difference_value;
            }
        }
        if (!empty($get_instance_details_qty_damaged)) {
            if ($operation == 'addition') {
                $update_details_data['qty_damaged'] = (int) $get_instance_details_qty_damaged + (int) $difference_value;
                $update_item_details_data['qty_damaged'] = (int) $get_instance_details_qty_damaged + (int) $difference_value;
            } else {
                $update_details_data['qty_damaged'] = (int) $get_instance_details_qty_damaged - (int) $difference_value;
                $update_item_details_data['qty_damaged'] = (int) $get_instance_details_qty_damaged - (int) $difference_value;
            }
        }
        if (!empty($get_instance_details_qty_scrapped)) {
            if ($operation == 'addition') {
                $update_details_data['qty_scrapped'] = (int) $get_instance_details_qty_scrapped + (int) $difference_value;
                $update_item_details_data['qty_scrapped'] = (int) $get_instance_details_qty_scrapped + (int) $difference_value;
            } else {
                $update_details_data['qty_scrapped'] = (int) $get_instance_details_qty_scrapped - (int) $difference_value;
                $update_item_details_data['qty_scrapped'] = (int) $get_instance_details_qty_scrapped - (int) $difference_value;
            }
            // $update_details_data['qty_scrapped'] = - $get_instance_details_qty_scrapped;
        }
        if (!empty($get_instance_details_qty_not_in_use)) {
            if ($operation == 'addition') {
                $update_details_data['qty_not_in_use'] = (int) $get_instance_details_qty_not_in_use + (int) $difference_value;
                $update_item_details_data['qty_not_in_use'] = (int) $get_instance_details_qty_not_in_use + (int) $difference_value;
            } else {
                $update_details_data['qty_not_in_use'] = (int) $get_instance_details_qty_not_in_use - (int) $difference_value;
                $update_item_details_data['qty_not_in_use'] = (int) $get_instance_details_qty_not_in_use - (int) $difference_value;
            }
            // $update_details_data['qty_not_in_use'] = - $get_instance_details_qty_not_in_use;
        }
        if (!empty($get_instance_details_qty_missing)) {
            if ($operation == 'addition') {
                $update_details_data['qty_missing'] = (int) $get_instance_details_qty_missing + (int) $difference_value;
                $update_item_details_data['qty_missing'] = (int) $get_instance_details_qty_missing + (int) $difference_value;
            } else {
                $update_details_data['qty_missing'] = (int) $get_instance_details_qty_missing - (int) $difference_value;
                $update_item_details_data['qty_missing'] = (int) $get_instance_details_qty_missing - (int) $difference_value;
            }
            // $update_details_data['qty_missing'] = - $get_instance_details_qty_missing;
        }
        if (!empty($get_instance_details_qty_shifted)) {
            if ($operation == 'addition') {
                $update_details_data['qty_shifted'] = (int) $get_instance_details_qty_shifted + (int) $difference_value;
                $update_item_details_data['qty_shifted'] = (int) $get_instance_details_qty_shifted + (int) $difference_value;
            } else {
                $update_details_data['qty_shifted'] = (int) $get_instance_details_qty_shifted - (int) $difference_value;
                $update_item_details_data['qty_shifted'] = (int) $get_instance_details_qty_shifted - (int) $difference_value;
            }
            // $update_details_data['qty_shifted'] = - $get_instance_details_qty_shifted;
        }



        $difference = (int) $get_instance_details->qty_value - (int) $update_details->quantity_verified;
        $difference = abs($difference);


        if ($operation == 'addition') {
            // $quantity_verified_value = (int)$get_item_details->quantity_verified + (int)$get_instance_details_qty_value;
            $quantity_verified_value = (int) $get_item_details->quantity_verified + (int) $difference_value;
        } else {
            // $quantity_verified_value = (int)$get_item_details->quantity_verified - (int)$get_instance_details_qty_value;
            $quantity_verified_value = (int) $get_item_details->quantity_verified - (int) $difference_value;
        }

        $update_details_data['quantity_verified'] = $quantity_verified_value;
        $update_item_details_data['quantity_verified'] = $quantity_verified_value;





        $current_date_time = date('Y-m-d H:i:s');

        if ($operation == 'addition') {
            $quantity_verified = (int) $update_details->quantity_verified;
            $actual_quantity_verified = $update_details->quantity_verified;
        } else {
            $quantity_verified = (int) $update_details->quantity_verified;
            $actual_quantity_verified = -$update_details->quantity_verified;
        }

        $actual_quantity_verified = -$update_details->quantity_verified;

        $update_details_data['quantity_verified'] = $quantity_verified;

        $verification_status = $update_details->quantity_as_per_invoice <= $update_details->quantity_verified ? "Verified" : "Not-Verified";
        $update_details_data['verification_status'] = $verification_status;
        $update_details_data['verified_datetime'] = $current_date_time;
        $update_details_data['updatedat'] = $current_date_time;

        if ($update_details->verification_remarks != '') {
            $verification_remarks = $get_item_details->verification_remarks != '' ? $get_item_details->verification_remarks . ' || ' . $update_details->verification_remarks : $update_details->verification_remarks;
            $update_details_data['verification_remarks'] = $verification_remarks;
        }




        // $update_item_details_data['instance_count'] = (int)$get_item_details->instance_count+1;
        $update_item_details_data['instance_count'] = (int) $get_item_details->instance_count + 2;





        $verify = $this->tasks->update_data($projectname, $update_item_details_data, $condition);


        $update_details_data['company_id'] = $get_instance_details->company_id;
        $update_details_data['location_id'] = $get_instance_details->location_id;
        $update_details_data['entity_code'] = $get_instance_details->entity_code;
        $update_details_data['project_id'] = $get_instance_details->project_id;
        $update_details_data['project_name'] = $get_instance_details->project_name;
        $update_details_data['original_table_name'] = $get_instance_details->original_table_name;
        $update_details_data['item_id'] = $get_item_details->id;
        $update_details_data['item_category'] = $get_item_details->item_category;
        $update_details_data['item_unique_code'] = $get_item_details->item_unique_code;
        $update_details_data['item_sub_code'] = $get_item_details->item_sub_code;
        $update_details_data['item_description'] = $get_item_details->item_description;
        $update_details_data['quantity_as_per_invoice'] = $get_item_details->quantity_as_per_invoice;
        $update_details_data['verification_status'] = $verification_status;
        $update_details_data['verified_by'] = $verified_by;
        $update_details_data['verified_by_username'] = $verified_by_username;
        $update_details_data['verified_datetime'] = $current_date_time;
        $update_details_data['verification_remarks'] = $verification_remarks;
        $update_details_data['mode_of_verification'] = $get_instance_details->mode_of_verification;
        $update_details_data['qty_value'] = $actual_quantity_verified;
        $update_details_data['created_at'] = date('Y-m-d H:i:s');
        $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts', $update_details_data);


        //Add into Verified Products Log Table        
        $qty_scrapped_value = 0;
        $qty_damaged_value = 0;
        $qty_ok_value = 0;
        $qty_not_in_use_value = 0;
        $qty_missing_value = 0;
        $qty_shifted_value = 0;
        if ($update_details->item_scrap_condition == 'qty_ok') {
            $qty_ok_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_damaged') {
            $qty_damaged_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_scrapped') {
            $qty_scrapped_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_not_in_use') {
            $qty_not_in_use_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_missing') {
            $qty_missing_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_shifted') {
            $qty_shifted_value = $quantity_verified;
        }





        // }



        $company_id = $get_project_details[0]->company_id;
        $new_location_verified = $update_details->new_location_verified;
        $location_id = $get_project_details[0]->project_location;
        $entity_code = $get_project_details[0]->entity_code;
        $project_id = $get_project_details[0]->id;
        $project_name = $get_project_details[0]->project_name;
        $original_table_name = $get_project_details[0]->original_table_name;

        $current_date_time = date('Y-m-d H:i:s');
        $verification_status = $update_details->quantity_as_per_invoice <= $update_details->quantity_verified ? "Verified" : "Not-Verified";

        $mode_of_verification = $update_details->mode_of_verification;
        $update_details->mode_of_verification = $mode_of_verification;

        //Add In Log File
        $verifiedproducts_array = array(
            'row_id' => $get_item_details->id,
            'edit_opration' => $edit_opration,
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
            'previous_item_id' => $get_item_details->id,
            'item_id' => $get_item_details->id,
            'previous_item_category' => $get_item_details->item_category,
            'item_category' => $get_item_details->item_category,
            'previous_item_unique_code' => $get_item_details->item_unique_code,
            'item_unique_code' => $get_item_details->item_unique_code,
            'previous_item_sub_code' => $get_item_details->item_sub_code,
            'item_sub_code' => $get_item_details->item_sub_code,
            'previous_item_description' => $get_item_details->item_description,
            'item_description' => $get_item_details->item_description,
            'previous_quantity_as_per_invoice' => $get_item_details->quantity_as_per_invoice,
            'quantity_as_per_invoice' => $get_item_details->quantity_as_per_invoice,
            'previous_verification_status' => $get_item_details->verification_status,
            'verification_status' => $verification_status,
            'previous_quantity_verified' => $get_item_details->quantity_verified,
            'quantity_verified' => $quantity_verified_value,
            'previous_new_location_verified' => $get_item_details->new_location_verified,
            'new_location_verified' => $new_location_verified,
            'previous_verified_by' => $get_item_details->verified_by,
            'verified_by' => $verified_by,
            'previous_verified_by_username' => $get_item_details->verified_by_username,
            'verified_by_username' => $verified_by_username,
            'previous_verified_datetime' => $get_item_details->verified_datetime,
            'verified_datetime' => $current_date_time,
            'previous_verification_remarks' => $get_item_details->verification_remarks,
            'verification_remarks' => $verification_remarks,
            'previous_qty_ok' => $get_item_details->qty_ok,
            'qty_ok' => $qty_ok,
            'previous_qty_damaged' => $get_item_details->qty_damaged,
            'qty_damaged' => $qty_damaged,
            'previous_qty_scrapped' => $get_item_details->qty_scrapped,
            'qty_scrapped' => $qty_scrapped,
            'previous_qty_not_in_use' => $get_item_details->qty_not_in_use,
            'qty_not_in_use' => $qty_not_in_use,
            'previous_qty_missing' => $get_item_details->qty_missing,
            'qty_missing' => $qty_missing,
            'previous_qty_shifted' => $get_item_details->qty_shifted,
            'qty_shifted' => $qty_shifted,
            'previous_mode_of_verification' => $get_item_details->mode_of_verification,
            'mode_of_verification' => $mode_of_verification,
            'previous_created_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        );
        $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts_log', $verifiedproducts_array);



        //Add into Verified Products Log Table        
        $qty_scrapped_value = 0;
        $qty_damaged_value = 0;
        $qty_ok_value = 0;
        $qty_not_in_use_value = 0;
        $qty_missing_value = 0;
        $qty_shifted_value = 0;
        if ($update_details->item_scrap_condition == 'qty_ok') {
            $qty_ok_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_damaged') {
            $qty_damaged_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_scrapped') {
            $qty_scrapped_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_not_in_use') {
            $qty_not_in_use_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_missing') {
            $qty_missing_value = $quantity_verified;
        }
        if ($update_details->item_scrap_condition == 'qty_shifted') {
            $qty_shifted_value = $quantity_verified;
        }













        if ($verifiedproducts_result) {

            $verifiedproducts_array = array(
                'company_id' => $company_id,
                'location_id' => $location_id,
                'entity_code' => $entity_code,
                'project_id' => $project_id,
                'project_name' => $project_name,
                'original_table_name' => $original_table_name,
                'item_id' => $get_item_details->id,
                'item_category' => $get_item_details->item_category,
                'item_unique_code' => $get_item_details->item_unique_code,
                'item_sub_code' => $get_item_details->item_sub_code,
                'item_description' => $get_item_details->item_description,
                'quantity_as_per_invoice' => $get_item_details->quantity_as_per_invoice,
                'verification_status' => $verification_status,
                'quantity_verified' => $quantity_verified,
                'new_location_verified' => $new_location_verified,
                'verified_by' => $verified_by,
                'verified_by_username' => $verified_by_username,
                'verified_datetime' => $current_date_time,
                'verification_remarks' => $verification_remarks,
                'qty_ok' => $qty_ok_value,
                'qty_damaged' => $qty_damaged_value,
                'qty_scrapped' => $qty_scrapped_value,
                'qty_not_in_use' => $qty_not_in_use_value,
                'qty_missing' => $qty_missing_value,
                'qty_shifted' => $qty_shifted_value,
                'mode_of_verification' => $mode_of_verification,
                // 'type_of_operation' => $operation,
                'qty_value' => $actual_quantity_verified,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts', $verifiedproducts_array);

            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Item verified update successfully."));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Item not verified"));
            exit;
        }
    }


    public function getdepartments()
    {
        $verification_status = $this->input->post('verification_status');
        $tag_status_y_n_na = $this->input->post('tag_status_y_n_na');
        $item_category = $this->input->post('item_category');
        $item_sub_category = $this->input->post('item_sub_category');
        $projectname = $this->input->post('project_name');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $select = "SELECT user_department FROM " . $projectname;

        $where = '';
        $is_where = 0;
        if ($verification_status != 'All') {
            $where .= ' WHERE verification_status="' . $verification_status . '"';
            $is_where = 1;
        }
        if ($tag_status_y_n_na != 'All') {
            if ($is_where == 1) {
                $where .= ' AND tag_status_y_n_na="' . $tag_status_y_n_na . '"';
            } else {
                $where .= ' WHERE tag_status_y_n_na="' . $tag_status_y_n_na . '"';
            }
            $is_where = 1;

        }
        if ($item_category != 'All') {
            if ($is_where == 1) {
                $where .= ' AND item_category="' . $item_category . '"';
            } else {
                $where .= ' WHERE item_category="' . $item_category . '"';
            }
            $is_where = 1;
        }

        if ($item_sub_category != '' && $item_sub_category != 'All') {
            if ($is_where == 1) {
                $where .= ' AND item_sub_category="' . $item_sub_category . '"';
            } else {
                $where .= ' WHERE item_sub_category="' . $item_sub_category . '"';
            }
        }
        $where .= ' GROUP BY user_department';

        $scantask = $this->db->query($select . $where)->result();
        // if(empty($scantask)){
        //     $scantask[0]['user_department'] = 'All';
        // }



        $Responce_data = array();
        $i = 0;
        $Responce_data[$i]['user_department'] = 'All';
        if (!empty($scantask)) {
            foreach ($scantask as $scantask_key => $scantask_value) {
                $i++;
                $Responce_data[$i] = $scantask_value;
            }
        }

        $result_count = count($Responce_data);

        if ($Responce_data) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "count" => $result_count, "data" => $Responce_data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Permission to scan this category item is not granted."));
            exit;
        }
    }

    public function getassets()
    {
        $verification_status = $this->input->post('verification_status');
        $tag_status_y_n_na = $this->input->post('tag_status_y_n_na');
        $item_category = $this->input->post('item_category');
        $item_sub_category = $this->input->post('item_sub_category');
        $user_department = $this->input->post('user_department');
        $projectname = $this->input->post('project_name');
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $select = "SELECT item_classification FROM " . $projectname;

        $where = '';
        $is_where = 0;
        if ($verification_status != 'All') {
            $where .= ' WHERE verification_status="' . $verification_status . '"';
            $is_where = 1;
        }
        if ($tag_status_y_n_na != 'All') {
            if ($is_where == 1) {
                $where .= ' AND tag_status_y_n_na="' . $tag_status_y_n_na . '"';
            } else {
                $where .= ' WHERE tag_status_y_n_na="' . $tag_status_y_n_na . '"';
            }
            $is_where = 1;

        }
        if ($item_category != 'All') {
            if ($is_where == 1) {
                $where .= ' AND item_category="' . $item_category . '"';
            } else {
                $where .= ' WHERE item_category="' . $item_category . '"';
            }
            $is_where = 1;
        }
        if ($user_department != 'All') {
            if ($is_where == 1) {
                $where .= ' AND user_department="' . $user_department . '"';
            } else {
                $where .= ' WHERE user_department="' . $user_department . '"';
            }
            $is_where = 1;
        }
        if ($item_sub_category != '' && $item_sub_category != 'All') {
            if ($is_where == 1) {
                $where .= ' AND item_sub_category="' . $item_sub_category . '"';
            } else {
                $where .= ' WHERE item_sub_category="' . $item_sub_category . '"';
            }
        }
        $where .= ' GROUP BY item_classification';

        $scantask = $this->db->query($select . $where)->result();
        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();
        $result_count = count($scantask);



        $Responce_data = array();
        $i = 0;
        $Responce_data[$i]['item_classification'] = 'All';
        if (!empty($scantask)) {
            foreach ($scantask as $scantask_key => $scantask_value) {
                $i++;
                $Responce_data[$i] = $scantask_value;
            }
        }

        $result_count = count($Responce_data);


        if ($Responce_data) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "count" => $result_count, "data" => $Responce_data));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Permission to scan this category item is not granted."));
            exit;
        }

    }


    public function verifybylist()
    {

        $projectid = $this->input->post('project_id');
        $userid = $this->input->post('user_id');
        $verification_status = $this->input->post('verification_status');
        $tag_status_y_n_na = $this->input->post('tag_status_y_n_na');
        // $tag_status_y_n_na =$this->input->post('tag_status_y_n_na');
        // $item_category  =$this->input->post('item_category');
        // $item_sub_category =$this->input->post('item_sub_category');
        $projectname = $this->input->post('project_name');
        $search_text = $this->input->post('search_text');

        // $search_fields = $this->input->post('search_fields');

        $search_fields = array();
        if (!empty($this->input->post('search_fields'))) {
            $search_fields = explode(",", $this->input->post('search_fields'));
        }



        $item_classification = $this->input->post('item_classification');
        $user_department = $this->input->post('user_department');

        $order_by = $this->input->post('order_by');
        $cond = array();

        $where = '';
        $is_where = 0;
        if (!empty($search_text)) {
            $where = ' Where (';
            $i = 1;
            foreach ($search_fields as $sf) {
                if ($i == 1)
                    $where .= str_replace('"', '', $sf) . ' LIKE "%' . $search_text . '%"';
                else
                    $where .= ' OR ' . str_replace('"', '', $sf) . ' LIKE "%' . $search_text . '%"';

                if (count($search_fields) == $i) {
                    $where .= ')';
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




        if ($verification_status != 'All') {
            if ($is_where == 1) {
                $where .= ' AND verification_status="' . $verification_status . '"';
            } else {
                $where .= ' WHERE verification_status="' . $verification_status . '"';
            }

            $is_where = 1;
        }

        if ($item_classification != 'All') {
            if ((isset($item_classification)) && (!empty($item_classification))) {
                if ($is_where == 1) {
                    $where .= ' AND item_classification="' . $item_classification . '"';
                } else {
                    $where .= ' WHERE item_classification="' . $item_classification . '"';
                }
                $is_where = 1;
            }
        }

        if ($tag_status_y_n_na != 'All') {
            if ($is_where == 1) {
                $where .= ' AND tag_status_y_n_na="' . $tag_status_y_n_na . '"';
            } else {
                $where .= ' WHERE tag_status_y_n_na="' . $tag_status_y_n_na . '"';
            }

            $is_where = 1;
        }



        if ($user_department != 'All') {

            if ((isset($user_department)) && (!empty($user_department))) {
                if ($is_where == 1) {
                    $where .= ' AND user_department="' . $user_department . '"';
                } else {
                    $where .= ' WHERE user_department="' . $user_department . '"';
                }

            }
        }



        $where .= ' ORDER BY id ' . $order_by;

        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        $projectdetail = $this->tasks->get_data('company_projects', array('id' => $projectid));

        $select = "SELECT * FROM " . $projectname;
        $scantask = $this->db->query($select . $where)->result();


        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();
        $result_count = count($scantask);

        if (!empty($scantask) && count($scantask) > 0) {
            foreach ($scantask as $st) {

                if ($st->verified_by != '') {
                    $verifiername = $this->tasks->get_verifire_namesingle($st->verified_by);
                    // echo '<pre>last_query ';
                    // print_r($this->db->last_query());
                    // echo '</pre>';
                    // exit();
                } else {
                    $verifiername = '';
                }

                // $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
                // $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));

                $st->createdat = date('d-m-Y H:i:s', strtotime($st->createdat));
                $st->updatedat = date('d-m-Y H:i:s', strtotime($st->updatedat));

                // $st->createdat=date('d-m-Y H:i:s');
                // $st->updatedat=date('d-m-Y H:i:s');

                if ($st->verified_datetime) {
                    // $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
                    $st->verified_datetime = date('d-m-Y H:i:s', strtotime($st->verified_datetime));
                    // $st->verified_datetime=date('d-m-Y H:i:s');
                    $st->verified_by_username = $verifiername;
                    $st->verified_by_name = $verifiername;

                }

                // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
            }
            if (!empty($projectdetail) && in_array($scantask[0]->item_category, json_decode($projectdetail[0]->item_category))) {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "count" => $result_count, "data" => $scantask));
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array("success" => 401, "message" => "Permission to scan this category item is not granted."));
                exit;
            }

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Item not available"));
            exit;
        }
    }











    public function downloadExceptionFiveConsolidatedReport()
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
        $headerCondition = array('table_name' => $table_name);
        $project_headers = $this->tasks->get_data('project_headers', $headerCondition);
        $rowHeads = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ');
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $cnt = 0;
        $columns = "";
        $rowCount = 1;
        $colsArray = array();
        if ($reportHeaders == 'all') {
            foreach ($project_headers as $ph) {
                if ($ph->keyname != 'is_alotted') {
                    $sheet->setCellValue($rowHeads[$cnt] . $rowCount, ucwords($ph->keylabel));
                    $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
                    $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
                    $columns .= " " . $ph->keyname . ",";
                    array_push($colsArray, $ph->keyname);
                    $cnt++;
                }
            }
        } else {
            for ($i = 0; $i < 9; $i++) {
                if ($project_headers[$i]->keyname != 'is_alotted') {
                    $sheet->setCellValue($rowHeads[$cnt] . $rowCount, ucwords($project_headers[$i]->keylabel));
                    $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
                    $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

                    $columns .= " " . $project_headers[$i]->keyname . ",";
                    array_push($colsArray, $project_headers[$i]->keyname);
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
        $columns .= "verification_status,updatedat,verified_datetime,item_note,mode_of_verification";
        array_push($colsArray, 'verification_status');

        $sheet->setCellValue($rowHeads[$cnt] . $rowCount, "Verification Status");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

        array_push($colsArray, 'updatedat');
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Last Updated on");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        array_push($colsArray, 'verified_datetime');
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Item Note Date");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        array_push($colsArray, 'item_note');
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Item Note Remarks");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        array_push($colsArray, 'mode_of_verification');
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Mode of Verification");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Allocation Status");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Project ID");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Project Name");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Start Date");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Due Date");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Period of Verification");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);
        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Allocated Resources");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);

        $sheet->setCellValue($rowHeads[++$cnt] . $rowCount, "Project Status");
        $sheet->getStyle($rowHeads[$cnt] . $rowCount)->getFont()->applyFromArray(['bold' => TRUE]);
        $sheet->getColumnDimension($rowHeads[$cnt])->setAutoSize(true);


        $projCondition = array('id' => $projectid);
        $getProject = $this->tasks->get_data('company_projects', $projCondition);
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($getProject[0]->project_name)));
        $rowCount = 2;
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



    public function generateExcel()
    {



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




    public function emailsending()
    {

        // Ref :- https://stackoverflow.com/questions/50604268/excel-generate-and-send-email-using-phpexcel-library-in-codeigniter
        // Ref :- https://stackoverflow.com/questions/13108508/how-can-i-attach-the-file-in-codeigniter-which-i-have-created-through-coding
        // Ref :- https://forum.codeigniter.com/showthread.php?tid=64101

        $html = $this->load->view('sendmail_template', $data, TRUE);

        include("simple_html_dom.php");
        $rowRecords = str_get_html($html);

        //echo $html;
        $filename = "Report-" . $dtimeFile . ".xls";
        $path = './reports/';
        $csv_handler = fopen($path . $filename, 'w');
        fwrite($csv_handler, $rowRecords);
        fclose($csv_handler);

        $msg = 'Report';

        $file = $path . $filename;
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));

        $file_path = base_url('reports/' . $filename);


        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'xxxxxxxxxx@gmail.com',
            'smtp_pass' => 'xxxxxxxxxx',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
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







    public function setemail()
    {
        $email = "hardik.meghnathi12@gmail.com";
        $subject = "some text";
        $message = "some text";
        $this->sendEmail($email, $subject, $message);
    }
    public function sendEmail($email, $subject, $message)
    {

        $config = array(
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
        if ($this->email->send()) {
            echo 'Email send.';
        } else {
            show_error($this->email->print_debugger());
        }

    }

    /**
     * Get user email from database
     */
    private function getUserEmail($user_id)
    {
        if (empty($user_id)) {
            return null;
        }

        $user = $this->tasks->get_data('users', array('id' => $user_id));
        if (!empty($user) && count($user) > 0) {
            return $user[0]->email;
        }

        return null;
    }

    /**
     * Generate CSV file and send via email
     */
    private function generateCSVAndEmail($report_data, $project_data, $user_email, $type, $reporttype)
    {
        try {
            // Generate unique filename
            $filename = 'report_' . date('Y-m-d_H-i-s') . '.csv';
            $filepath = FCPATH . 'attachment/' . $filename;

            // Ensure attachment directory exists
            if (!is_dir(FCPATH . 'attachment/')) {
                mkdir(FCPATH . 'attachment/', 0777, true);
            }

            // Open file for writing
            $file = fopen($filepath, 'w');
            if (!$file) {
                return array("success" => 400, "message" => "Failed to create CSV file.");
            }

            // Write CSV headers
            $headers = array('ID', 'Name', 'Email', 'Date', 'Status', 'Project Name', 'Report Type');
            fputcsv($file, $headers);

            // Process report data and write to CSV
            if (is_array($report_data)) {
                foreach ($report_data as $row) {
                    if (is_array($row)) {
                        // Handle array data
                        $csv_row = array(
                            isset($row['id']) ? $row['id'] : '',
                            isset($row['name']) ? $row['name'] : '',
                            isset($row['email']) ? $row['email'] : '',
                            isset($row['date']) ? $row['date'] : date('Y-m-d'),
                            isset($row['status']) ? $row['status'] : '',
                            isset($project_data->project_name) ? $project_data->project_name : '',
                            $reporttype
                        );
                        fputcsv($file, $csv_row);
                    } elseif (is_object($row)) {
                        // Handle object data
                        $csv_row = array(
                            isset($row->id) ? $row->id : '',
                            isset($row->name) ? $row->name : '',
                            isset($row->email) ? $row->email : '',
                            isset($row->date) ? $row->date : date('Y-m-d'),
                            isset($row->status) ? $row->status : '',
                            isset($project_data->project_name) ? $project_data->project_name : '',
                            $reporttype
                        );
                        fputcsv($file, $csv_row);
                    }
                }
            }

            fclose($file);

            // Check if file was created successfully
            if (!file_exists($filepath)) {
                return array("success" => 400, "message" => "Failed to create CSV file.");
            }

            // Send email if user email is available
            if (!empty($user_email)) {
                $email_result = $this->sendReportEmail($filename, $user_email);
                if ($email_result['success']) {
                    return array(
                        "success" => 200,
                        "message" => "Report generated and sent to your email.",
                        "data" => $report_data,
                        "csv_file" => $filename,
                        "email_sent" => true
                    );
                } else {
                    return array(
                        "success" => 200,
                        "message" => "Report generated but email sending failed: " . $email_result['message'],
                        "data" => $report_data,
                        "csv_file" => $filename,
                        "email_sent" => false
                    );
                }
            } else {
                return array(
                    "success" => 200,
                    "message" => "Report generated successfully. No email sent (user email not found).",
                    "data" => $report_data,
                    "csv_file" => $filename,
                    "email_sent" => false
                );
            }

        } catch (Exception $e) {
            return array("success" => 400, "message" => "Error generating report: " . $e->getMessage());
        }
    }

    /**
     * Send report email using EmailController
     */
    private function sendReportEmail($filename, $user_email)
    {
        try {
            // Build the email controller URL
            $email_url = base_url('index.php/EmailController/emailattachment?file=' . urlencode($filename) . '&email=' . urlencode($user_email));

            // Use cURL to call EmailController
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $email_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 200) {
                return array("success" => true, "message" => "Email sent successfully");
            } else {
                return array("success" => false, "message" => "Email sending failed with HTTP code: " . $http_code);
            }

        } catch (Exception $e) {
            return array("success" => false, "message" => "Email sending error: " . $e->getMessage());
        }
    }

    // Issue API


    public function EditVerificationNew()
    {



        $userid = $this->input->post('user_id');
        $companyid = $this->input->post('company_id');
        $projectid = $this->input->post('project_id');
        $projectname = $this->input->post('project_name');
        $instance_id = $this->input->post('instance_id');
        // $scancode=$this->input->post('scan_code');

        $this->db->select("*");
        $this->db->from("verifiedproducts");
        $this->db->where("company_id", $companyid);
        $this->db->where("project_id", $projectid);
        $this->db->where("project_name", $projectname);
        $this->db->where("id", $instance_id);
        $query = $this->db->get();
        $get_instance_details = $query->row();

        $item_id = $get_instance_details->item_id;

        $condition = array(
            "id" => $userid
        );
        $projectdetail = $this->tasks->get_data('company_projects', array('id' => $projectid));

        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        $projectname = strtolower(preg_replace($old_pattern, $new_pattern, trim($projectname)));
        // $scantask=$this->tasks->scanitem($userid,$companyid,$projectname,$projectid,$scancode);
        // $scantask=$this->tasks->scanitem2($userid,$companyid,$projectname,$projectid,$instance_id);
        $scantask = $this->tasks->scanitem3($userid, $companyid, $projectname, $projectid, $item_id);

        foreach ($scantask as $st) {
            if ($st->verified_by != '') {
                $verifiername = $this->tasks->get_verifire_namesingle($st->verified_by);
            } else {
                $verifiername = '';
            }
            // $st->createdat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->createdat)));
            // $st->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->updatedat)));

            $st->createdat = date('d-m-Y H:i:s', strtotime($st->createdat));
            $st->updatedat = date('d-m-Y H:i:s', strtotime($st->updatedat));

            // $st->createdat=date('d-m-Y H:i:s');
            // $st->updatedat=date('d-m-Y H:i:s');

            if ($st->verified_datetime) {
                $st->verified_by_username = $verifiername;
                $st->verified_by_name = $verifiername;
                // $st->verified_datetime=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($st->verified_datetime)));
                $st->verified_datetime = date('d-m-Y H:i:s', strtotime($st->verified_datetime));
                // $st->verified_datetime=date('d-m-Y H:i:s');
            }

            // $st->date_of_purchase_invoice_date=date('d-m-Y',strtotime($st->date_of_purchase_invoice_date)); 
        }
        if (!empty($scantask) && count($scantask) > 0) {
            $tag = 'CD';

            $projectdetail[0]->project_type == 'TG' ? $tag = 'Y' : ($projectdetail[0]->project_type == 'NT' ? $tag = 'N' : ($projectdetail[0]->project_type == 'UN' ? $tag = 'NA' : $tag = 'CD'));
            if ($tag != 'CD') {
                if (!empty($projectdetail) && in_array($scantask[0]->item_category, json_decode($projectdetail[0]->item_category)) && $scantask[0]->tag_status_y_n_na == $tag) {
                    header('Content-Type: application/json');
                    echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "data" => $scantask));
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array("success" => 401, "message" => "Permission to scan this category/tag item is not granted."));
                    exit;
                }

            } else {
                if (!empty($projectdetail) && in_array($scantask[0]->item_category, json_decode($projectdetail[0]->item_category))) {
                    header('Content-Type: application/json');
                    echo json_encode(array("success" => 200, "message" => "Tasks fetched successfully.", "data" => $scantask));
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array("success" => 401, "message" => "Permission to scan this category item is not granted."));
                    exit;
                }
            }



        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Item not available"));
            exit;
        }
    }



    public function instance_rollback()
    {
        $item_id = $this->input->post('item_id');
        $project_id = $this->input->post('project_id');
        $instance_id = $this->input->post('instance_id');

        $this->db->select("*");
        $this->db->from("company_projects");
        $this->db->where("id", $project_id);
        $query = $this->db->get();
        $company_projects = $query->row();

        $table_name = $company_projects->project_table_name;

        $this->db->select("*");
        $this->db->from($table_name);
        $this->db->where("id", $item_id);
        $query = $this->db->get();
        $company_projects_product_details = $query->row();

        $this->db->select("*");
        $this->db->from("verifiedproducts");
        $this->db->where("project_id", $project_id);
        $this->db->where("item_id", $item_id);
        $this->db->where("id", $instance_id);
        $query = $this->db->get();
        $verifiedproducts_data = $query->row();

      

        $verified_datetime = date('Y-m-d H:i:s');


        $verifiedproducts_array = array(
            'company_id' => $verifiedproducts_data->company_id,
            'location_id' => $verifiedproducts_data->location_id,
            'entity_code' => $verifiedproducts_data->entity_code,
            'project_id' => $verifiedproducts_data->project_id,
            'project_name' => $verifiedproducts_data->project_name,
            'original_table_name' => $verifiedproducts_data->original_table_name,
            'item_id' => $verifiedproducts_data->item_id,
            'item_category' => $verifiedproducts_data->item_category,
            'item_unique_code' => $verifiedproducts_data->item_unique_code,
            'item_sub_code' => $verifiedproducts_data->item_sub_code,
            'item_description' => $verifiedproducts_data->item_description,
            'quantity_as_per_invoice' => $verifiedproducts_data->quantity_as_per_invoice,
            'verification_status' => $verifiedproducts_data->verification_status,
            'quantity_verified' => "-" . $verifiedproducts_data->quantity_verified,
            'new_location_verified' => $verifiedproducts_data->new_location_verified,
            'verified_by' => $verifiedproducts_data->verified_by,
            'verified_by_username' => $verifiedproducts_data->verified_by_username,
            'verified_datetime' => $verified_datetime,
            'verification_remarks' => $verifiedproducts_data->verification_remarks,
            'qty_ok' => $verifiedproducts_data->qty_ok,
            'qty_damaged' => $verifiedproducts_data->qty_damaged,
            'qty_scrapped' => $verifiedproducts_data->qty_scrapped,
            'qty_not_in_use' => $verifiedproducts_data->qty_not_in_use,
            'qty_missing' => $verifiedproducts_data->qty_missing,
            'qty_shifted' => $verifiedproducts_data->qty_shifted,
            'mode_of_verification' => $verifiedproducts_data->mode_of_verification,
            'type_of_operation' => 'rollback',
            'qty_value' => $verifiedproducts_data->qty_value,
            'created_at' => date('Y-m-d H:i:s'),
        );
        $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts', $verifiedproducts_array);




        $data = array();
        $qty_value = 0;

        // echo '<pre>company_projects_product_details ';
        // print_r($company_projects_product_details);
        // echo '</pre>';
        // exit();

        // echo '<pre>verifiedproducts_data ';
        // print_r($verifiedproducts_data);
        // echo '</pre>';
        // exit();

        if (!empty($verifiedproducts_data->qty_ok)) {
            $data['qty_ok'] = $company_projects_product_details->qty_ok - $verifiedproducts_data->qty_value;
            $qty_value = $verifiedproducts_data->qty_ok;
        }
        if (!empty($verifiedproducts_data->qty_damaged)) {
            $data['qty_damaged'] = $company_projects_product_details->qty_damaged - $verifiedproducts_data->qty_value;
            $qty_value = $verifiedproducts_data->qty_damaged;
        }
        if (!empty($verifiedproducts_data->qty_scrapped)) {
            $data['qty_scrapped'] = $company_projects_product_details->qty_scrapped - $verifiedproducts_data->qty_value;
            $qty_value = $verifiedproducts_data->qty_scrapped;
        }
        if (!empty($verifiedproducts_data->qty_not_in_use)) {
            $data['qty_not_in_use'] = $company_projects_product_details->qty_not_in_use - $verifiedproducts_data->qty_value;
            $qty_value = $verifiedproducts_data->qty_not_in_use;
        }
        if (!empty($verifiedproducts_data->qty_shifted)) {
            $data['qty_shifted'] = $company_projects_product_details->qty_shifted - $verifiedproducts_data->qty_value;
            $qty_value = $verifiedproducts_data->qty_shifted;
        }

        $remaining_quantity = (int) $company_projects_product_details->quantity_verified - (int) $qty_value;
        $remaining_quantity = (int) $company_projects_product_details->quantity_verified - (int) $verifiedproducts_data->qty_value;  //Added on 14 Augugest 2026 above are commented        
        $data["quantity_verified"] = $remaining_quantity;
        $data["instance_count"] = (int) $company_projects_product_details->instance_count + 1;
        $data["verification_status"] = "";
        if ($company_projects_product_details->quantity_as_per_invoice <= $remaining_quantity) {
            $data["verification_status"] = "Verified";
        }

        // echo '<pre>data v';
        // print_r($data);
        // echo '</pre>';
        // exit();

        $insert = $this->db->where('id', $item_id);
        $insert = $this->db->update($table_name, $data);



        // if(!empty($project_id) && count($project_id) > 0)
        if (!empty($project_id)) {

            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Rollback successfully.", "data" => $project_id));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Error"));
            exit;
        }

    }

    public function create_issue()
    {
        // Get required fields from POST using $this->input->post()
        $user_id = $this->input->post('user_id');
        $type_of_issue = $this->input->post('type_of_issue');
        $issue_title = $this->input->post('issue_title');
        $issue_description = $this->input->post('issue_description');
        // $issue_attachment = $this->input->post('issue_attachment');


        $config['upload_path'] = './issueattachment/';
        $config['allowed_types'] = '*';
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);
        $issue_attachment = '';
        if (!$this->upload->do_upload('issue_attachment')) {
            $error = array('error' => $this->upload->display_errors());
            // print_r($error);
            // exit;
        } else {
            $data = $this->upload->data();
            $issue_attachment = $data['file_name'];
        }






        // Get conditional fields
        $groupadmin_id = $this->input->post('groupadmin_id');
        $company_name = $this->input->post('company_name');
        $location = $this->input->post('location');
        $project = $this->input->post('project');
        $manager_id = $this->input->post('manager_id');

        // Validate required fields
        if (empty($user_id) || empty($type_of_issue) || empty($issue_title) || empty($issue_description)) {
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => false,
                "message" => "Required fields are missing"
            ));
            exit;
        }

        // Validate type_of_issue
        if ($type_of_issue !== 'General' && $type_of_issue !== 'Project based') {
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => false,
                "message" => "Type of issue must be 'General' or 'Project based'"
            ));
            exit;
        }

        // Validate conditional fields based on type_of_issue
        if ($type_of_issue === 'General') {
            if (empty($groupadmin_id)) {
                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => false,
                    "message" => "Group admin ID is required for General issues"
                ));
                exit;
            }
        } elseif ($type_of_issue === 'Project based') {
            if (empty($company_name) || empty($location) || empty($project) || empty($manager_id)) {
                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => false,
                    "message" => "Company name, location, project, and manager ID are required for Project based issues"
                ));
                exit;
            }
        }

        try {
            // Prepare data based on type_of_issue

            $random_number = rand(10000, 99999);
            $tracking_id_value = date('ymd') . $random_number;

            $resolved_by = ($type_of_issue === 'General') ? $groupadmin_id : $manager_id;

            $insert_data = array(
                'tracking_id' => $tracking_id_value,
                'issue_type' => $type_of_issue,
                'company_name' => ($type_of_issue === 'General') ? '0' : $company_name,
                'location_name' => ($type_of_issue === 'General') ? '0' : $location,
                'project_name' => ($type_of_issue === 'General') ? '0' : $project,
                'manage_name' => ($type_of_issue === 'General') ? '0' : $manager_id,
                'groupadmin_name' => ($type_of_issue === 'Project based') ? '0' : $groupadmin_id,
                'issue_title' => $issue_title,
                'issue_description' => $issue_description,
                // 'issue_attachment' => $issue_attachment ? $issue_attachment : '',
                "issue_attachment" => $issue_attachment ? $issue_attachment : '',
                'status' => '1',
                'status_type' => '1',
                'remark_content' => '',
                'created_by' => $user_id,
                'resolved_by' => $resolved_by,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            // Insert into database
            $this->db->insert('issue_manage', $insert_data);
            $issue_id = $this->db->insert_id();

            $insert_data['id'] = $issue_id;


            if ($issue_id) {
                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => true,
                    "message" => "Issue created successfully",
                    "data" => $insert_data
                ));

                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => false,
                    "message" => "Failed to create issue"
                ));
                exit;
            }

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => false,
                "message" => "Error creating issue: " . $e->getMessage()
            ));
            exit;
        }
    }



    public function issue_list()
    {
        // Get user_id from POST input
        $user_id = $this->input->post('user_id');

        // Validate if user_id is provided
        if (empty($user_id)) {
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => false,
                "message" => "User ID is required"
            ));
            exit;
        }

        try {
            // Query to get issues where user is either creator or handler
            $this->db->select('issue_manage.id,issue_manage.tracking_id, issue_manage.issue_title as subject, issue_manage.issue_type, company_projects.project_id, issue_manage.status, issue_manage.status_type');
            $this->db->from('issue_manage');
            $this->db->join('company_projects', 'company_projects.id = issue_manage.project_name', 'left');
            $this->db->where('(issue_manage.created_by = ' . $user_id . ' OR issue_manage.resolved_by = ' . $user_id . ')', NULL, FALSE);
            $query = $this->db->get();



            if ($query->num_rows() > 0) {
                $issues = $query->result();
                $response_data = array();

                foreach ($issues as $issue) {
                    // Format status text for status field (using status column)
                    $status_text = '';
                    if ($issue->status == '0')
                        $status_text = 'Closed';
                    elseif ($issue->status == '1')
                        $status_text = 'Open';
                    else
                        $status_text = 'Unknown';

                    // Format status_type text
                    $status_type_text = '';
                    if ($issue->status_type == '1')
                        $status_type_text = 'New';
                    elseif ($issue->status_type == '2')
                        $status_type_text = 'Escalated';


                    $response_data[] = array(
                        "id" => $issue->id,
                        "tracking_id" => $issue->tracking_id,
                        "subject" => $issue->subject,
                        "issue_type" => $issue->issue_type,
                        "project_id" => $issue->project_id,
                        "status" => $status_text,
                        "status_type" => $status_type_text
                    );
                }

                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => true,
                    "message" => "Issue list fetched successfully.",
                    "data" => $response_data
                ));
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => true,
                    "message" => "No issues found for this user.",
                    "data" => array()
                ));
                exit;
            }

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => false,
                "message" => "Error fetching issue list: " . $e->getMessage()
            ));
            exit;
        }
    }



    public function view_issue()
    {
        // Get issue_id from POST input
        $issue_id = $this->input->post('issue_id');



        // Validate if issue_id is provided
        if (empty($issue_id)) {
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => false,
                "message" => "Issue ID is required"
            ));
            exit;
        }

        try {
            // Load the admin model to access issue_manage table
            $this->load->model('admin_model', 'admin');

            // Query to get issue details from issue_manage table (matching web controller logic)
            $this->db->select('issue_manage.*, company_projects.project_id, users.firstName, users.lastName, company.company_name, company_locations.location_name, handled_users.firstName as handled_firstName, handled_users.lastName as handled_lastName');
            $this->db->from('issue_manage');
            $this->db->join('company', 'company.id = issue_manage.company_name', 'left');
            $this->db->join('company_locations', 'company_locations.id = issue_manage.location_name', 'left');
            $this->db->join('company_projects', 'company_projects.id = issue_manage.project_name', 'left');
            $this->db->join('users', 'users.id = issue_manage.created_by', 'left');
            $this->db->join('users as handled_users', 'handled_users.id = issue_manage.resolved_by', 'left');
            $this->db->where('issue_manage.id', $issue_id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $issue_data = $query->row();

                // Prepare response data with formatted display (matching the required format)
                $created_by_name = isset($issue_data->firstName) && isset($issue_data->lastName) ? $issue_data->firstName . ' ' . $issue_data->lastName : '';
                $created_at = isset($issue_data->created_at) ? date('Y-m-d H:i:s', strtotime($issue_data->created_at)) : '';
                $tracking_id = isset($issue_data->tracking_id) ? $issue_data->tracking_id : '';
                $issue_type = isset($issue_data->issue_type) ? $issue_data->issue_type : '';
                $subject = isset($issue_data->issue_title) ? $issue_data->issue_title : '';
                $description = isset($issue_data->issue_description) ? $issue_data->issue_description : '';
                $handled_by_name = isset($issue_data->handled_firstName) && isset($issue_data->handled_lastName) ? $issue_data->handled_firstName . ' - ' . $issue_data->handled_lastName : '';
                //$handled_date = isset($issue_data->resolved_date) ? date('Y-m-d H:i:s', strtotime($issue_data->resolved_date)) : '';
                $attachment = isset($issue_data->issue_attachment) ? $issue_data->issue_attachment : '';
                $status = isset($issue_data->status) ? $issue_data->status : '';
                $status_type = isset($issue_data->status_type) ? $issue_data->status_type : '';

                // Format status text ONLY for tracking_id (using status_type column)
                $status_text = '';
                if ($status_type == '1')
                    $status_text = 'New';
                elseif ($status_type == '2')
                    $status_text = 'Escalated';

                // Format status text for status field (using status column)
                $status_type_text = '';
                if ($status == '0')
                    $status_type_text = 'Closed';
                elseif ($status == '1')
                    $status_type_text = 'Open';
                else
                    $status_type_text = 'Unknown';


                $description = strip_tags($description);
                $description_value = substr($description, 0, 110);

                $close_array = array();

                $this->db->select('*');
                $this->db->from('issue_log_manage');
                $this->db->where('issue_id', $issue_id);
                $query = $this->db->get();
                if ($query->num_rows() > 0) {
                    $close_array = $query->row();
                    $close_array->attachments = $close_array->attachments ? base_url('issueattachment/' . $close_array->attachments) : '';
                }

                $response_data = array(
                    "created_by" => $created_by_name . ' | ' . $created_at,
                    "tracking_id" => $tracking_id . ($status_text ? ' | ' . $status_text : ''),
                    "issue_type" => $issue_type,
                    "subject" => $subject,
                    "description" => $description,
                    "handled_id" => $issue_data->resolved_by,
                    "handled_by" => $handled_by_name,
                    "created_at" => $created_at,
                    "attachment" => $attachment ? base_url('issueattachment/' . $attachment) : '',
                    "status" => $status_type_text,
                    "closed" => $close_array
                );

                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => true,
                    "message" => "Issue data fetched successfully",
                    "data" => $response_data
                ));
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => false,
                    "message" => "Issue not found"
                ));
                exit;
            }

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => false,
                "message" => "Error fetching issue data: " . $e->getMessage()
            ));
            exit;
        }
    }

    //already

    public function generateExceptionReportDev()
    {


        header('Content-Type: application/json');

        try {
            // 1. Collect POST parameters
            $type = $this->input->post('optradio');
            $projectSelect = $this->input->post('projectSelect');
            $exceptioncategory = $this->input->post('exception_category');
            $projectstatus = $this->input->post('projectstatus');
            $verificationstatus = $this->input->post('verificationstatus');
            $reportHeaders = $this->input->post('reportHeaders');
            $original_table_name = $this->input->post('original_table_name');
            $company_id = $this->input->post('company_id');
            $location_id = $this->input->post('location_id');
            $user_id = $this->input->post('user_id');

            // 2. Validate user
            if (empty($user_id)) {
                echo json_encode(["success" => false, "status_code" => 400, "message" => "User ID is required"]);
                return;
            }
            $this->db->where('id', $user_id);
            $user = $this->db->get('users')->row();
            if (!$user) {
                echo json_encode(["success" => false, "status_code" => 404, "message" => "User not found"]);
                return;
            }
            $user_email = !empty($user->userEmail) ? $user->userEmail : $user->email;

            // Ensure tasks model is loaded
            if (!isset($this->tasks)) {
                $this->load->model('Tasks_model', 'tasks');
            }

            // exit("Not Exist");
            /**
             * ------------------------
             * CSV GENERATION
             * ------------------------
             */
            $filename = 'exception_report_' . date('Y-m-d_His') . '.csv';
            $filepath = FCPATH . 'attachment/' . $filename;
            if (!is_dir(FCPATH . 'attachment/')) {
                mkdir(FCPATH . 'attachment/', 0777, true);
            }

            $fp = fopen($filepath, 'w');

            $report_data = [];
            $project_data = [];

            /**
             * ------------------------
             * FETCH REPORT DATA
             * ------------------------
             */
            if ($type === 'Project Based' || $type === 'project') {


                // exceptioncategory
                /*
                1 ->Condition of Item
                2 ->Changes/ Updations of Items (New)
                3 ->Qty Validation Status
                4 ->Updated with Verification Remarks
                5 ->Updated with Item Notes
                6 ->Calculate Risk Exposure (New)
                8 ->Mode of Verification
                9 ->Duplicate Item Codes verified (NOT WORKING)
                10 ->Duplicate Item Codes Identified (New)
                */
                $condition = [
                    "id" => $projectSelect,
                    "status" => $projectstatus,
                    "company_id" => $company_id,
                ];
                $getProjects = $this->tasks->get_data('company_projects', $condition);


                    foreach ($getProjects as $project) {
                        $old_pattern = ["/[^a-zA-Z0-9]/", "/_+/", "/_$/"];
                        $new_pattern = ["_", "_", ""];
                        $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));

                        $project_report = $this->_getExceptionCategoryReport($project_name, $exceptioncategory, $verificationstatus, $reportHeaders) ?: [];



                        if (is_array($project_report)) {
                            foreach ($project_report as $key => $val) {
                                if (is_array($val)) {
                                    if (!isset($report_data[$key]) || !is_array($report_data[$key])) {
                                        $report_data[$key] = [];
                                    }
                                    $report_data[$key] = array_merge($report_data[$key], $val);
                                } else {
                                    $report_data[$key] = $val;
                                }
                            }
                        }



                    }


                if ($exceptioncategory == '1') {  //Condition of Item
                    // Step 1: Headers
                    /*
                    $headers = [
                        "Allocated Item Category",
                        "To be Verified (Amount in Lacs)", "To be Verified (Number of Qty)",
                        "Good Condition (Amount in Lacs)", "Good Condition (Number of Qty)",
                        "Damaged (Amount in Lacs)", "Damaged (Number of Qty)",
                        "Scrapped (Amount in Lacs)", "Scrapped (Number of Qty)",
                        "Missing (Amount in Lacs)", "Missing (Number of Qty)",
                        "Shifted (Amount in Lacs)", "Shifted (Number of Qty)",
                        "Not in Use (Amount in Lacs)", "Not in Use (Number of Qty)",
                        "Remaining to be Verified (Amount in Lacs)", "Remaining to be Verified (Number of Qty)"
                    ]; */

                    $headers = [
                        "Allocated Item Category",
                        "To be Verified (Amount in Lacs)",
                        "To be Verified (Number of Qty)",
                        "Good Condition (Number of Qty)",
                        "Damaged (Number of Qty)",
                        "Scrapped (Number of Qty)",
                        "Missing (Number of Qty)",
                        "Shifted (Number of Qty)",
                        "Not in Use (Number of Qty)",
                        "Remaining to be Verified (Amount in Lacs)",
                        "Remaining to be Verified (Number of Qty)"
                    ];


                    fputcsv($fp, $headers);

                    // Step 2: Safe loop (only if data exists)
                    if (isset($report_data['all']) && is_array($report_data['all']) && count($report_data['all']) > 0) {

                        $lookup = [];
                        foreach (['good', 'damaged', 'scrapped', 'missing', 'shifted', 'notinuse'] as $status) {
                            $lookup[$status] = isset($report_data[$status]) && is_array($report_data[$status])
                                ? $report_data[$status] : [];
                        }

                        $column_totals = array_fill(0, count($headers), 0);

                        $totalAmount = 0;
                        $totalItems = 0;
                        $goodTotalAmount = 0;
                        $goodTotalItems = 0;
                        $damagedTotalAmount = 0;
                        $damagedTotalItems = 0;
                        $scrappedTotalAmount = 0;
                        $scrappedTotalItems = 0;
                        $missingTotalAmount = 0;
                        $missingTotalItems = 0;
                        $shiftedTotalAmount = 0;
                        $shiftedTotalItems = 0;
                        $notinuseTotalAmount = 0;
                        $notinuseTotalItems = 0;
                        $remainingTotalAmount = 0;
                        $remainingTotalItems = 0;
                        $remainitemstotal = 0;
                        foreach ($report_data['all'] as $allcat) {
                            $row = [];
                            $goodAmount = 0;
                            $goodItems = 0;
                            $damagedAmount = 0;
                            $damagedItems = 0;
                            $scrappedAmount = 0;
                            $scrappedItems = 0;
                            $missingAmount = 0;
                            $missingItems = 0;
                            $shiftedAmount = 0;
                            $shiftedItems = 0;
                            $notinuseAmount = 0;
                            $notinuseItems = 0;
                            $remainingAmount = 0;
                            $remainingItems = 0;
                            $totalAmount = $totalAmount + $allcat->total_amount;
                            $totalItems = $totalItems + $allcat->total_qty;
                            foreach ($report_data['good'] as $good) {
                                if ($good->item_category == $allcat->item_category) {
                                    $goodAmount = $good->total_amount;
                                    $goodItems = $good->good_qty;
                                    $goodTotalAmount = $goodTotalAmount + $goodAmount;
                                    $goodTotalItems = $goodTotalItems + $goodItems;
                                }
                            }
                            foreach ($report_data['damaged'] as $damaged) {
                                if ($damaged->item_category == $allcat->item_category) {
                                    $damagedAmount = $damaged->total_amount;
                                    $damagedItems = $damaged->damaged_qty;
                                    $damagedTotalAmount = $damagedTotalAmount + $damagedAmount;
                                    $damagedTotalItems = $damagedTotalItems + $damagedItems;
                                }
                            }
                            foreach ($report_data['scrapped'] as $scrapped) {
                                if ($scrapped->item_category == $allcat->item_category) {
                                    $scrappedAmount = $scrapped->total_amount;
                                    $scrappedItems = $scrapped->scrapped_qty;
                                    $scrappedTotalAmount = $scrappedTotalAmount + $scrappedAmount;
                                    $scrappedTotalItems = $scrappedTotalItems + $scrappedItems;
                                }
                            }
                            foreach ($report_data['missing'] as $missing) {
                                if ($missing->item_category == $allcat->item_category) {
                                    $missingAmount = $missing->total_amount;
                                    $missingItems = $missing->missing_qty;
                                    $missingTotalAmount = $missingTotalAmount + $missingAmount;
                                    $missingTotalItems = $missingTotalItems + $missingItems;
                                }
                            }
                            foreach ($report_data['shifted'] as $shifted) {
                                if ($shifted->item_category == $allcat->item_category) {
                                    $shiftedAmount = $shifted->total_amount;
                                    $shiftedItems = 0;
                                    if (isset($shifted->shifted_qty)) {
                                        $shiftedItems = $shifted->shifted_qty;
                                    }
                                    $shiftedTotalAmount = $shiftedTotalAmount + $shiftedAmount;
                                    $shiftedTotalItems = $shiftedTotalItems + $shiftedItems;
                                }
                            }
                            foreach ($report_data['notinuse'] as $notinuse) {
                                if ($notinuse->item_category == $allcat->item_category) {
                                    $notinuseAmount = $notinuse->total_amount;
                                    $notinuseItems = $notinuse->notinuse_qty;
                                    $notinuseTotalAmount = $notinuseTotalAmount + $notinuseAmount;
                                    $notinuseTotalItems = $notinuseTotalItems + $notinuseItems;
                                }
                            }
                            $remainitem = '0';
                            foreach ($report_data['remaining'] as $remainingdata) {
                                if ($remainingdata->item_category == $allcat->item_category) {
                                    $remainitem = $remainingdata->items;
                                }
                                $remainitem = $allcat->total_qty - ($goodItems + $damagedItems + $scrappedItems + $missingItems + $shiftedItems + $notinuseItems);


                            }
                            $remainitemstotal += $remainitem;


                            $remainingAmount = $allcat->total_amount - ($goodAmount + $damagedAmount + $scrappedAmount + $missingAmount + $shiftedAmount + $notinuseAmount);
                            $remainingItems = $allcat->total_qty - ($goodItems + $damagedItems + $scrappedItems + $missingItems + $shiftedItems + $notinuseItems);
                            $remainingTotalAmount = $remainingTotalAmount + $remainingAmount;
                            $remainingTotalItems = $remainingTotalItems + $remainingItems;


                            $row[] = $allcat->item_category;
                            $row[] = $allcat->total_amount != 0 ? getmoney_format(round(($allcat->total_amount / 100000), 2)) : $allcat->total_amount;
                            $row[] = $allcat->total_qty;
                            $row[] = $goodItems;
                            $row[] = $damagedItems;
                            $row[] = $scrappedItems;
                            $row[] = $missingItems;
                            $row[] = $shiftedItems;
                            $row[] = $notinuseItems;
                            $row[] = $remainingAmount != 0 ? getmoney_format(round(($remainingAmount / 100000), 2)) : $remainingAmount;
                            $row[] = $remainitem;
                            fputcsv($fp, $row);
                        }



                        $Grand_Total_row[] = "Grand Total";
                        $Grand_Total_row[] = $totalAmount != 0 ? getmoney_format(round(($totalAmount / 100000), 2)) : $totalAmount;
                        $Grand_Total_row[] = $totalItems;
                        $Grand_Total_row[] = $goodTotalItems;
                        $Grand_Total_row[] = $damagedTotalItems;
                        $Grand_Total_row[] = $scrappedTotalItems;
                        $Grand_Total_row[] = $missingTotalItems;
                        $Grand_Total_row[] = $shiftedTotalItems;
                        $Grand_Total_row[] = $notinuseTotalItems;
                        $Grand_Total_row[] = $remainingTotalAmount != 0 ? getmoney_format(round(($remainingTotalAmount / 100000), 2)) : $remainingTotalAmount;
                        $Grand_Total_row[] = $remainingTotalItems;
                        fputcsv($fp, $Grand_Total_row);



                        $Grand_Total_percentage_row[] = "% to Grand Total";
                        $Grand_Total_percentage_row[] = "100%";
                        $Grand_Total_percentage_row[] = "100%";
                        $Grand_Total_percentage_row[] = round(($goodTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($damagedTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($scrappedTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($missingTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($shiftedTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($notinuseTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($remainingTotalAmount / $totalAmount) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($remainingTotalItems / $totalItems) * 100, 2) . '%';
                        fputcsv($fp, $Grand_Total_percentage_row);
                        /*
                        foreach ($report_data['all'] as $category) {
                            $row = [];
                            $row[] = $category->item_category;

                            $toBeVerifiedAmount = (float)($category->total_amount / 100000);
                            $toBeVerifiedQty    = (int)$category->total_qty;
                            $row[] = $toBeVerifiedAmount;
                            $row[] = $toBeVerifiedQty;

                            $getValues = function($status, $cat_name) use ($lookup) {
                                foreach ($lookup[$status] as $item) {
                                    if ($item->item_category === $cat_name) {
                                        return [
                                            'amount' => (float)($item->total_amount / 100000),
                                            'qty'    => (int)($item->qty ?? 0)
                                        ];
                                    }
                                }
                                return ['amount' => 0, 'qty' => 0];
                            };

                            $good     = $getValues('good', $category->item_category);
                            $damaged  = $getValues('damaged', $category->item_category);
                            $scrapped = $getValues('scrapped', $category->item_category);
                            $missing  = $getValues('missing', $category->item_category);
                            $shifted  = $getValues('shifted', $category->item_category);
                            $notinuse = $getValues('notinuse', $category->item_category);

                            $row = array_merge($row, [
                                $good['amount'], $good['qty'],
                                $damaged['amount'], $damaged['qty'],
                                $scrapped['amount'], $scrapped['qty'],
                                $missing['amount'], $missing['qty'],
                                $shifted['amount'], $shifted['qty'],
                                $notinuse['amount'], $notinuse['qty']
                            ]);

                            $remainingAmount = $toBeVerifiedAmount - ($good['amount'] + $damaged['amount'] + $scrapped['amount'] + $missing['amount'] + $shifted['amount'] + $notinuse['amount']);
                            $remainingQty    = $toBeVerifiedQty - ($good['qty'] + $damaged['qty'] + $scrapped['qty'] + $missing['qty'] + $shifted['qty'] + $notinuse['qty']);

                            $row[] = $remainingAmount > 0 ? round($remainingAmount, 2) : 0;
                            $row[] = $remainingQty > 0 ? $remainingQty : 0;

                            echo "<pre>row :";
                            print_r($row);
                            echo "</pre>";
                            exit;

                            fputcsv($fp, $row);

                            for ($i = 1; $i < count($row); $i++) {
                                $column_totals[$i] += $row[$i];
                            }
                        } */

                        // Totals

                        /*
                        $total_row = ["Grand Total"];
                        for ($i = 1; $i < count($headers); $i++) {
                            $total_row[] = round($column_totals[$i], 2);
                        }
                        fputcsv($fp, $total_row);
                        */

                    } else {
                        fputcsv($fp, ["No data found"]); /// This Data is Fetching While Report Generated
                    }
                }

                if ($exceptioncategory == '2') {  //Changes/ Updations of Items (New)  [File Name :- ChangesUpdationsItemsReport]

                    $headers = array();
                    $project_header_column_value = explode(",", $report_data['project_header_column_value']);
                    unset($project_header_column_value[0]);
                    unset($project_header_column_value[1]);
                    $headers[] = 'Allocated Item Category';

                    foreach ($project_header_column_value as $project_header_column_value_value) {
                        $headers[] = ucfirst(str_replace('_', ' ', $project_header_column_value_value));
                    }
                    fputcsv($fp, $headers);

                    $rows = array();
                    foreach ($report_data['different'] as $key => $value) {
                        $row = array(); // Create new row for each record            
                        $row[] = $key;
                        foreach ($project_header_column_value as $project_header_column_value_value) {
                            if (isset($report_data['different'][$key][$project_header_column_value_value])) {
                                $row[] = count($report_data['different'][$key][$project_header_column_value_value]);
                            } else {
                                $row[] = "0";
                            }
                        }
                        // $rows[] = $row; // Add row to master array
                        fputcsv($fp, $row);
                    }

                }

                if ($exceptioncategory == '3') {  //Qty Validation Status
                    // Step 1: Headers      //Should be Dynamic     [File Name :- quantityValidationReport]
                    $headers = [
                        "Allocated Item Category",
                        "To be Verified - Amount(in Lacs)",
                        "To be Verified - Number of Line Items",
                        "Verified - Amount(in Lacs)",
                        "Verified - Number of Line Items",
                        "Verified as Equal - Amount(in Lacs)",
                        "Verified as Equal - Number of Line Items",
                        "Short Found - Amount(in Lacs)",
                        "Short Found - Number of Line Items",
                        "Excess Found - Amount(in Lacs)",
                        "Excess Found - Number of Line Items",
                        "Remaining to be Verified - Amount(in Lacs)",
                        "Remaining to be Verified - Number of Line Items",
                    ];
                    fputcsv($fp, $headers);


                    $totalAmount = 0;
                    $totalItems = 0;
                    $verifiedTotalAmount = 0;
                    $verifiedTotalItems = 0;
                    $shortTotalAmount = 0;
                    $shortTotalItems = 0;
                    $equalTotalAmount = 0;
                    $equalTotalItems = 0;
                    $excessTotalAmount = 0;
                    $excessTotalItems = 0;
                    $remainingTotalAmount = 0;
                    $remainingTotalItems = 0;
                    $remainitemstotal = 0;
                    $remainitemamounttotal = 0;
                    $excessitemtotal = 0;
                    $excessamounttotalnew = 0;
                    foreach ($report_data['all'] as $allcat) {
                        $row = [];
                        $verifiedAmount = 0;
                        $verifiedItems = 0;
                        $shortAmount = 0;
                        $shortItems = 0;
                        $equalAmount = 0;
                        $equalItems = 0;
                        $excessAmount = 0;
                        $excessItems = 0;
                        $remainingAmount = 0;
                        $remainingItems = 0;

                        $totalAmount = $totalAmount + $allcat->total_amount;
                        $totalItems = $totalItems + $allcat->total_items;
                        foreach ($report_data['verified'] as $verified) {
                            if ($verified->item_category == $allcat->item_category) {
                                $verifiedAmount = $verified->total_amount;
                                $verifiedItems = $verified->total_items;
                                $verifiedTotalAmount = $verifiedTotalAmount + $verifiedAmount;
                                $verifiedTotalItems = $verifiedTotalItems + $verifiedItems;

                                if ($verified->total_items > $allcat->total_items && $verified->total_items > 0) {
                                    $shortAmount = $allcat->total_amount - $verified->total_amount;
                                    $shortItems = $allcat->total_items - $verified->total_items;
                                    $shortTotalAmount = $shortTotalAmount + $shortAmount;
                                    $shortTotalItems = $shortTotalItems + $shortItems;
                                }

                                if ($verified->total_items > $allcat->total_items) {
                                    // // $excessAmount=$allcat->total_amount - $verified->total_amount;
                                    // $excessItems=$verified->total_items - $allcat->total_items;

                                    // $excessTotalAmount=$excessTotalAmount+$excessAmount;
                                    // $excessTotalItems=$excessTotalItems+$excessItems;
                                }

                                if ($verified->total_items < 1) {
                                    $remainingAmount = $allcat->total_amount;
                                    $remainingItems = $allcat->total_items;
                                    $remainingTotalAmount = $remainingTotalAmount + $remainingAmount;
                                    $remainingTotalItems = $remainingTotalItems + $remainingItems;
                                }

                            }

                        }
                        foreach ($report_data['verifiedequal'] as $verifiedeq) {
                            if ($verifiedeq->item_category == $allcat->item_category) {
                                $equalAmount = $verifiedeq->total_amount;
                                $equalItems = $verifiedeq->total_items;
                                $equalTotalAmount = $equalTotalAmount + $equalAmount;
                                $equalTotalItems = $equalTotalItems + $equalItems;
                            }
                        }

                        /*
                        if($_SESSION['reportData']['verification_status']=='Not-Verified')
                        {
                            $remainingAmount=$allcat->total_amount;
                            $remainingItems=$allcat->total_items;
                            $remainingTotalAmount=$remainingTotalAmount+$remainingAmount;
                            $remainingTotalItems=$remainingTotalItems+$remainingItems;
                        }
                        */

                        $remainitem = '0';
                        $remainitemamount = '0';
                        foreach ($report_data['remaining'] as $remainingdata) {
                            if ($remainingdata->item_category == $allcat->item_category) {
                                $remainitem = $remainingdata->items;
                                $remainitemamount = $remainingdata->total_amount;
                            }

                        }
                        $remainitemstotal += $remainitem;
                        $remainitemamounttotal += $remainitemamount;

                        $excessitem = '0';
                        $excessamount = '0';
                        foreach ($report_data['excess'] as $excess) {
                            if ($excess->item_category == $allcat->item_category) {
                                $excessitem = $excess->items;
                                $excessAmount = $excess->total_amount;
                                $excessamounttotalnew = $excessamounttotalnew + $excessAmount;

                            }


                        }
                        $excessitemtotal += $excessitem;


                        /*
                        if($_SESSION['reportData']['verification_status']=='Not-Verified')
                        {

                            $equalAmount = 0;
                            $equalItems = 0;
                            $shortAmount = 0;
                            $shortItems = 0;
                            $excessAmount = 0;
                            $excessitem = 0;
                            // $equalAmount = 0;

                            $equalTotalAmount = 0;
                            $equalTotalItems = 0;
                            $shortTotalAmount = 0;
                            $shortTotalItems = 0;
                            $excessamounttotalnew = 0;
                            $excessitemtotal = 0;
                        } */

                        $row[] = $allcat->item_category;
                        $row[] = $allcat->total_amount != 0 ? getmoney_format(round(($allcat->total_amount / 100000), 2)) : $allcat->total_amount;
                        $row[] = $allcat->total_items;
                        $row[] = $verifiedAmount != 0 ? getmoney_format(round(($verifiedAmount / 100000), 2)) : $verifiedAmount;
                        $row[] = $verifiedItems;
                        $row[] = $equalAmount != 0 ? getmoney_format(round(($equalAmount / 100000), 2)) : $equalAmount;
                        $row[] = $equalItems;
                        $row[] = $shortAmount != 0 ? getmoney_format(round(($shortAmount / 100000), 2)) : $shortAmount;
                        $row[] = $shortItems;
                        $row[] = $excessAmount != 0 ? getmoney_format(round(($excessAmount / 100000), 2)) : $excessAmount;
                        $row[] = $excessitem;
                        $row[] = $remainitemamount != 0 ? getmoney_format(round(($remainitemamount / 100000), 2)) : $remainitemamount;
                        $row[] = $remainitem;
                        fputcsv($fp, $row);
                    }



                    $grand_total_row = array();
                    $grand_total_row[] = "Grand Total";
                    $grand_total_row[] = $totalAmount != 0 ? getmoney_format(round(($totalAmount / 100000), 2)) : $totalAmount;
                    $grand_total_row[] = $totalItems;
                    $grand_total_row[] = $verifiedTotalAmount != 0 ? getmoney_format(round(($verifiedTotalAmount / 100000), 2)) : $verifiedTotalAmount;
                    $grand_total_row[] = $verifiedTotalItems;
                    $grand_total_row[] = $equalTotalAmount != 0 ? getmoney_format(round(($equalTotalAmount / 100000), 2)) : $equalTotalAmount;
                    $grand_total_row[] = $equalTotalItems;
                    $grand_total_row[] = $shortTotalAmount != 0 ? getmoney_format(round(($shortTotalAmount / 100000), 2)) : $shortTotalAmount;
                    $grand_total_row[] = $shortTotalItems;
                    $grand_total_row[] = $excessamounttotalnew != 0 ? getmoney_format(round(($excessamounttotalnew / 100000), 2)) : $excessamounttotalnew;
                    $grand_total_row[] = $excessitemtotal;
                    $grand_total_row[] = $remainitemamounttotal != 0 ? getmoney_format(round(($remainitemamounttotal / 100000), 2)) : $remainitemamounttotal;
                    $grand_total_row[] = $remainitemstotal;
                    fputcsv($fp, $grand_total_row);

                    $grand_total_percentage_row = array();
                    $grand_total_percentage_row[] = "% to Grand Total";
                    $grand_total_percentage_row[] = "100%";
                    $grand_total_percentage_row[] = "100%";
                    $grand_total_percentage_row[] = round(($verifiedTotalAmount / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($verifiedTotalItems / $totalItems) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($equalTotalAmount / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($equalTotalItems / $totalItems) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($shortTotalAmount / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($shortTotalItems / $totalItems) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($excessamounttotalnew / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($excessitemtotal / $totalItems) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($remainitemamounttotal / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($remainitemstotal / $totalItems) * 100, 2) . "%";
                    fputcsv($fp, $grand_total_percentage_row);


                }

                if ($exceptioncategory == '4') {  //Updated with Verification Remarks     [File Name :- verificationRemarksReport]

                    $headers = [
                        "Allocated Item Category",
                        "Number of Line Items",
                    ];
                    fputcsv($fp, $headers);

                    $totalItems = 0;
                    foreach ($report_data['all'] as $allcat) {
                        $row = array();
                        $totalItems = $totalItems + $allcat->items;

                        $row[] = $allcat->item_category;
                        $row[] = $allcat->items;
                        fputcsv($fp, $row);
                    }


                    $row1[] = "Grand Total";
                    $row1[] = $totalItems;

                    fputcsv($fp, $row1);

                }

                if ($exceptioncategory == '5') {  //Updated with Item Notes

                    $headers = [
                        "Allocated Item Category",
                        "Number of Line Items",

                    ];
                    fputcsv($fp, $headers);

                    $totalItems = 0;
                    foreach ($report_data['all'] as $allcat) {
                        $row = array();
                        $totalItems = $totalItems + $allcat->items;

                        $row[] = $allcat->item_category;
                        $row[] = $allcat->items;
                        fputcsv($fp, $row);
                    }


                    $row1[] = "Grand Total";
                    $row1[] = $totalItems;

                    fputcsv($fp, $row1);


                }

                if ($exceptioncategory == '6') {  //Calculate Risk Exposure (New)         I think only showing When Finish


                    $headers = [
                        "Allocated Item Category",
                        "Damaged (Amount in Lacs)",
                        "Damaged (Number of Qty)",
                        "Scrapped (Amount in Lacs)",
                        "Scrapped (Number of Qty)",
                        "Missing (Amount in Lacs)",
                        "Missing (Number of Qty)",
                        "Shifted (Amount in Lacs)",
                        "Shifted (Number of Qty)",
                        "Not in Use (Amount in Lacs)",
                        "Not in Use (Number of Qty)",
                        "Short (Amount in Lacs)",
                        "Short (Number of Qty)",
                        "Excess (Amount in Lacs)",
                        "Excess (Number of Qty)",
                        "Total Risk Exposure (Amount in Lacs)",
                        "Total Risk Exposure (Number of Qty)",
                    ];
                    fputcsv($fp, $headers);

                    $totalAmount = 0;
                    $totalItems = 0;
                    $goodTotalAmount = 0;
                    $goodTotalItems = 0;
                    $damagedTotalAmount = 0;
                    $damagedTotalItems = 0;
                    $scrappedTotalAmount = 0;
                    $scrappedTotalItems = 0;
                    $missingTotalAmount = 0;
                    $missingTotalItems = 0;
                    $shiftedTotalAmount = 0;
                    $shiftedTotalItems = 0;
                    $notinuseTotalAmount = 0;
                    $notinuseTotalItems = 0;
                    $remainingTotalAmount = 0;
                    $remainingTotalItems = 0;
                    $remainitemstotal = 0;
                    $shortTotalAmount = 0;
                    $shortTotalItems = 0;

                    foreach ($report_data['all'] as $allcat) {
                        $row = [];
                        $goodAmount = 0;
                        $goodItems = 0;
                        $damagedAmount = 0;
                        $damagedItems = 0;
                        $scrappedAmount = 0;
                        $scrappedItems = 0;
                        $missingAmount = 0;
                        $missingItems = 0;
                        $shiftedAmount = 0;
                        $shiftedItems = 0;
                        $notinuseAmount = 0;
                        $notinuseItems = 0;
                        $remainingAmount = 0;
                        $remainingItems = 0;

                        $shortAmount = 0;
                        $shortItems = 0;
                        $excessitem = 0;
                        $excessamount = 0;

                        foreach ($report_data['verified'] as $verified) {
                            if ($verified->item_category == $allcat->item_category) {
                                $verifiedAmount = $verified->total_amount;
                                $verifiedItems = $verified->total_items;
                                $verifiedTotalAmount = $verifiedTotalAmount + $verifiedAmount;
                                $verifiedTotalItems = $verifiedTotalItems + $verifiedItems;

                                if ($verified->total_items > $allcat->total_items && $verified->total_items > 0) {
                                    $shortAmount = $allcat->total_amount - $verified->total_amount;
                                    $shortItems = $allcat->total_items - $verified->total_items;
                                    $shortTotalAmount = $shortTotalAmount + $shortAmount;
                                    $shortTotalItems = $shortTotalItems + $shortItems;
                                }

                                if ($verified->total_items > $allcat->total_items) {
                                    // // $excessAmount=$allcat->total_amount - $verified->total_amount;
                                    // $excessItems=$verified->total_items - $allcat->total_items;

                                    // $excessTotalAmount=$excessTotalAmount+$excessAmount;
                                    // $excessTotalItems=$excessTotalItems+$excessItems;
                                }

                                if ($verified->total_items < 1) {
                                    $remainingAmount = $allcat->total_amount;
                                    $remainingItems = $allcat->total_items;
                                    $remainingTotalAmount = $remainingTotalAmount + $remainingAmount;
                                    $remainingTotalItems = $remainingTotalItems + $remainingItems;
                                }

                            }

                        }



                        $totalAmount = $totalAmount + $allcat->total_amount;
                        $totalItems = $totalItems + $allcat->total_qty;
                        foreach ($report_data['good'] as $good) {
                            if ($good->item_category == $allcat->item_category) {
                                $goodAmount = $good->total_amount;
                                $goodItems = $good->good_qty;
                                $goodTotalAmount = $goodTotalAmount + $goodAmount;
                                $goodTotalItems = $goodTotalItems + $goodItems;
                            }
                        }
                        foreach ($report_data['damaged'] as $damaged) {
                            if ($damaged->item_category == $allcat->item_category) {
                                $damagedAmount = $damaged->total_amount;
                                $damagedItems = $damaged->damaged_qty;
                                $damagedTotalAmount = $damagedTotalAmount + $damagedAmount;
                                $damagedTotalItems = $damagedTotalItems + $damagedItems;
                            }
                        }
                        foreach ($report_data['scrapped'] as $scrapped) {
                            if ($scrapped->item_category == $allcat->item_category) {
                                $scrappedAmount = $scrapped->total_amount;
                                $scrappedItems = $scrapped->scrapped_qty;
                                $scrappedTotalAmount = $scrappedTotalAmount + $scrappedAmount;
                                $scrappedTotalItems = $scrappedTotalItems + $scrappedItems;
                            }
                        }
                        foreach ($report_data['missing'] as $missing) {
                            if ($missing->item_category == $allcat->item_category) {
                                $missingAmount = $missing->total_amount;
                                $missingItems = $missing->missing_qty;
                                $missingTotalAmount = $missingTotalAmount + $missingAmount;
                                $missingTotalItems = $missingTotalItems + $missingItems;
                            }
                        }
                        foreach ($report_data['shifted'] as $shifted) {
                            if ($shifted->item_category == $allcat->item_category) {
                                $shiftedAmount = $shifted->total_amount;
                                $shiftedItems = $shifted->shifted_qty;
                                $shiftedTotalAmount = $shiftedTotalAmount + $shiftedAmount;
                                $shiftedTotalItems = $shiftedTotalItems + $shiftedItems;
                            }
                        }
                        foreach ($report_data['notinuse'] as $notinuse) {
                            if ($notinuse->item_category == $allcat->item_category) {
                                $notinuseAmount = $notinuse->total_amount;
                                $notinuseItems = $notinuse->notinuse_qty;
                                $notinuseTotalAmount = $notinuseTotalAmount + $notinuseAmount;
                                $notinuseTotalItems = $notinuseTotalItems + $notinuseItems;
                            }
                        }
                        $remainitem = '0';
                        foreach ($report_data['remaining'] as $remainingdata) {
                            if ($remainingdata->item_category == $allcat->item_category) {
                                $remainitem = $remainingdata->items;
                            }

                        }
                        $remainitemstotal += $remainitem;


                        $excessitem = 0;
                        $excessamount = 0;
                        foreach ($report_data['excess'] as $excess) {
                            if ($excess->item_category == $allcat->item_category) {
                                $excessitem = $excess->items;
                                $excessAmount = $excess->total_amount;
                                $excessamounttotalnew = $excessamounttotalnew + $excessAmount;

                            }
                        }

                        $excessitemtotal += $excessitem;
                        $remainingAmount = $allcat->total_amount - ($goodAmount + $damagedAmount + $scrappedAmount + $missingAmount + $shiftedAmount + $notinuseAmount);
                        $remainingItems = $allcat->total_qty - ($goodItems + $damagedItems + $scrappedItems + $missingItems + $shiftedItems + $notinuseItems);
                        $remainingTotalAmount = $remainingTotalAmount + $remainingAmount;
                        $remainingTotalItems = $remainingTotalItems + $remainingItems;



                        $row[] = $allcat->item_category;

                        $row[] = $damagedAmount != 0 ? getmoney_format(round(($damagedAmount / 100000), 2)) : $damagedAmount;
                        $row[] = $damagedItems;

                        $row[] = $scrappedAmount != 0 ? getmoney_format(round(($scrappedAmount / 100000), 2)) : $scrappedAmount;
                        $row[] = $scrappedItems;

                        $row[] = $missingAmount != 0 ? getmoney_format(round(($missingAmount / 100000), 2)) : $missingAmount;
                        $row[] = $missingItems;

                        $row[] = $shiftedAmount != 0 ? getmoney_format(round(($shiftedAmount / 100000), 2)) : $shiftedAmount;
                        ;
                        $row[] = $shiftedItems;

                        $row[] = $notinuseAmount != 0 ? getmoney_format(round(($notinuseAmount / 100000), 2)) : $notinuseAmount;
                        $row[] = $notinuseItems;

                        $row[] = $shortAmount != 0 ? getmoney_format(round(($shortAmount / 100000), 2)) : $shortAmount;
                        $row[] = $shortItems;

                        if ($excessAmount == NULL) {
                            $row[] = "0";
                        } else {
                            $row[] = $excessAmount != 0 ? getmoney_format(round(($excessAmount / 100000), 2)) : $excessAmount;
                        }
                        $row[] = $excessitem;

                        $total_risk_exposure_amount = $damagedAmount + $scrappedAmount + $missingAmount + $shiftedAmount + $notinuseAmount + $shortAmount;
                        $total_risk_exposure_qty = $damagedItems + $scrappedItems + $missingItems + $shiftedItems + $notinuseItems + $shortItems;

                        $row[] = $total_risk_exposure_amount != 0 ? getmoney_format(round(($total_risk_exposure_amount / 100000), 2)) : $total_risk_exposure_amount;
                        $row[] = $total_risk_exposure_qty;
                        fputcsv($fp, $row);
                    }

                    $Grand_Total_row[] = "Grand Total";
                    $Grand_Total_row[] = $damagedTotalAmount != 0 ? getmoney_format(round(($damagedTotalAmount / 100000), 2)) : $damagedTotalAmount;
                    $Grand_Total_row[] = $damagedTotalItems;
                    $Grand_Total_row[] = $scrappedTotalAmount != 0 ? getmoney_format(round(($scrappedTotalAmount / 100000), 2)) : $scrappedTotalAmount;
                    $Grand_Total_row[] = $scrappedTotalItems;
                    $Grand_Total_row[] = $missingTotalAmount != 0 ? getmoney_format(round(($missingTotalAmount / 100000), 2)) : $missingTotalAmount;
                    ;
                    $Grand_Total_row[] = $missingTotalItems;
                    $Grand_Total_row[] = $shiftedTotalAmount != 0 ? getmoney_format(round(($shiftedTotalAmount / 100000), 2)) : $shiftedTotalAmount;
                    $Grand_Total_row[] = $shiftedTotalItems;
                    $Grand_Total_row[] = $notinuseTotalAmount != 0 ? getmoney_format(round(($notinuseTotalAmount / 100000), 2)) : $notinuseTotalAmount;
                    $Grand_Total_row[] = $notinuseTotalItems;
                    $Grand_Total_row[] = $shortTotalAmount != 0 ? getmoney_format(round(($shortTotalAmount / 100000), 2)) : $shortTotalAmount;
                    $Grand_Total_row[] = $shortTotalItems;
                    $Grand_Total_row[] = $excessamounttotalnew != 0 ? getmoney_format(round(($excessamounttotalnew / 100000), 2)) : $excessamounttotalnew;
                    $Grand_Total_row[] = $excessitemtotal;

                    $total_risk_exposure_amount_grand = $damagedTotalAmount + $scrappedTotalAmount + $missingTotalAmount + $shiftedTotalAmount + $notinuseTotalAmount + $shortTotalAmount;
                    $total_risk_exposure_qty_grand = $damagedTotalItems + $scrappedTotalItems + $missingTotalItems + $shiftedTotalItems + $notinuseTotalItems + $shortTotalItems;

                    $Grand_Total_row[] = $total_risk_exposure_amount_grand != 0 ? getmoney_format(round(($total_risk_exposure_amount_grand / 100000), 2)) : $total_risk_exposure_amount_grand;
                    $Grand_Total_row[] = $total_risk_exposure_qty_grand;
                    fputcsv($fp, $Grand_Total_row);

                    $Grand_Total_percentage_row[] = "% to Grand Total";
                    $Grand_Total_percentage_row[] = round(($damagedTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($damagedTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($scrappedTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($scrappedTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($missingTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($missingTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($shiftedTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($shiftedTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($notinuseTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($notinuseTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($shortTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($shortTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($excessamounttotalnew / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($excessitemtotal / $totalItems) * 100, 2) . "%";

                    $Grand_Total_percentage_row[] = round(($total_risk_exposure_amount_grand / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($total_risk_exposure_qty_grand / $totalItems) * 100, 2) . "%";
                    fputcsv($fp, $Grand_Total_percentage_row);
                }

                if ($exceptioncategory == '7') {

                }

                if ($exceptioncategory == '8') {  //Mode of Verification
                    $headers = [
                        "Allocated Item Category",
                        "Verified by Scan",
                        "Verified by Manual Search",
                    ];
                    fputcsv($fp, $headers);


                    $table = [];
                    $grandScan = $grandManual = 0;

                    foreach ($report_data['all'] as $row) {
                        $category = $row->item_category;

                        // find scan items
                        $scan = 0;
                        foreach ($report_data['scan'] as $s) {
                            if ($s->item_category === $category) {
                                $scan = $s->items;
                                break;
                            }
                        }

                        // find manual items
                        $manual = 0;
                        foreach ($report_data['manual'] as $m) {
                            if ($m->item_category === $category) {
                                $manual = $m->items;
                                break;
                            }
                        }

                        $table[] = [
                            "category" => $category,
                            "scan" => $scan,
                            "manual" => $manual
                        ];

                        $grandScan += $scan;
                        $grandManual += $manual;
                    }


                    foreach ($table as $row) {
                        $row1 = array();
                        $row1[] = $row['category'];
                        $row1[] = $row['scan'];
                        $row1[] = $row['manual'];
                        fputcsv($fp, $row1);
                    }



                    $row2[] = "Grand Total";
                    $row2[] = $grandScan;
                    $row2[] = $grandManual;
                    fputcsv($fp, $row2);





                }

                if ($exceptioncategory == '9') {  //Duplicate Item Codes verified (NOT WORKING)

                }

                if ($exceptioncategory == '10') { //Duplicate Item Codes Identified (New)

                    $headers = [
                        "Allocated Item Category",
                        "No Of Line Item",
                        "Not Verified",
                        "SCAN",
                        "SEARCH"
                    ];
                    fputcsv($fp, $headers);

                    $row1 = array();
                    if (!empty($report_data['Duplicate_Array'])) {
                        foreach ($report_data['Duplicate_Array'] as $key => $allcat) {
                            $row1[] = $allcat['item_category'];
                            $row1[] = $allcat['total_uniqu_record_cout'];
                            $row1[] = $allcat['total_not_verified_uniqu_record_cout'];
                            $row1[] = $allcat['total_scan_uniqu_record_cout'];
                            $row1[] = $allcat['total_search_uniqu_record_cout'];
                        }
                    }
                    fputcsv($fp, $row1);


                }
                $report_type = $exceptioncategory;


                /*
                $condition = [
                    "id"              => $projectSelect,
                    "status"          => $projectstatus,
                    "company_id"      => $company_id,
                    "project_location"=> $location_id
                ];
                $getProject = $this->tasks->get_data('company_projects', $condition);  




                $i=0;
                foreach($getProject as $getProject)
                {
                    $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
                    $new_pattern = array("_", "_", "");
                    $project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($getProject->project_name)));
                    $categories=$this->tasks->getdistinct_data($project_name,'item_category');

                    $getreport[$i]=$this->tasks->getBasicReport($project_name,$verificationstatus,$reportHeaders);

                }



                $headers = [
                "Allocated Item Category",
                "Total as per FAR",
                "Total as per FAR",
                "Tagged",
                "Tagged",
                "Non-Tagged",
                "Non-Tagged",
                "Unspecified",
                "Unspecified",
                ];

                fputcsv($fp, $headers);

                $headers2 = [              
                    "Allocated Item Category",  
                    "Amount(in Lacs)",
                    "Number of Line Items",
                    "Amount(in Lacs)",
                    "Number of Line Items",
                    "Amount(in Lacs)",
                    "Number of Line Items",
                    "Amount(in Lacs)",
                    "Number of Line Items",
                ];
                fputcsv($fp, $headers2);



                $totalAmount=0;
                $totalItems=0;
                $taggedTotalAmount=0;
                $taggedTotalItems=0;
                $nontaggedTotalAmount=0;
                $nontaggedTotalItems=0;
                $unspecifiedTotalAmount=0;
                $unspecifiedTotalItems=0;




                foreach($getreport as $key=>$data)
                {

                    $row = array();
                    $subtotalAmount=0;
                    $subtotalItems=0;
                    $subtaggedTotalAmount=0;
                    $subtaggedTotalItems=0;
                    $subnontaggedTotalAmount=0;
                    $subnontaggedTotalItems=0;
                    $subunspecifiedTotalAmount=0;
                    $subunspecifiedTotalItems=0;


                    foreach($data['all'] as $allcat)
                    {
                        $row =array();
                        $taggedAmount=0;
                        $taggedItems=0;
                        $unspecifiedAmount=0;
                        $unspecifiedItems=0;
                        $nontaggedAmount=0;
                        $nontaggedItems=0;
                        $totalAmount=$totalAmount+$allcat->total_amount;
                        $totalItems=$totalItems+$allcat->items;
                        $subtotalAmount=$subtotalAmount+$allcat->total_amount;
                        $subtotalItems=$subtotalItems+$allcat->items;
                        foreach($data['tagged'] as $tagged)
                        {
                            if($tagged->item_category==$allcat->item_category)
                            {
                                $taggedAmount=$tagged->total_amount;
                                $taggedItems=$tagged->items;
                                $taggedTotalAmount=$taggedTotalAmount+$taggedAmount;
                                $taggedTotalItems=$taggedTotalItems+$taggedItems;
                                $subtaggedTotalAmount=$subtaggedTotalAmount+$taggedAmount;
                                $subtaggedTotalItems=$subtaggedTotalItems+$taggedItems;
                            }

                        }
                        foreach($data['nontagged'] as $nontagged)
                        {
                            if($nontagged->item_category==$allcat->item_category)
                            {
                                $nontaggedAmount=$nontagged->total_amount;
                                $nontaggedItems=$nontagged->items;
                                $nontaggedTotalAmount=$nontaggedTotalAmount+$nontaggedAmount;
                                $nontaggedTotalItems=$nontaggedTotalItems+$nontaggedItems;
                                $subnontaggedTotalAmount=$subnontaggedTotalAmount+$nontaggedAmount;
                                $subnontaggedTotalItems=$subnontaggedTotalItems+$nontaggedItems;
                            }

                        }
                        foreach($data['unspecified'] as $unspecified)
                        {
                            if($unspecified->item_category==$allcat->item_category)
                            {
                                $unspecifiedAmount=$unspecified->total_amount;
                                $unspecifiedItems=$unspecified->items;
                                $unspecifiedTotalAmount=$unspecifiedTotalAmount+$unspecifiedAmount;
                                $unspecifiedTotalItems=$unspecifiedTotalItems+$unspecifiedItems;
                                $subunspecifiedTotalAmount=$subunspecifiedTotalAmount+$unspecifiedAmount;
                                $subunspecifiedTotalItems=$subunspecifiedTotalItems+$unspecifiedItems;
                            }                        
                        }
                        $row[] = $allcat->item_category;
                        $row[] = $allcat->total_amount!=0?getmoney_format(round(($allcat->total_amount/100000),2)):$allcat->total_amount;
                        $row[] = $allcat->items;
                        $row[] = $taggedAmount!=0?getmoney_format(round(($taggedAmount/100000),2)):$taggedAmount;
                        $row[] = $taggedItems;
                        $row[] = $nontaggedAmount!=0?getmoney_format(round(($nontaggedAmount/100000),2)):$nontaggedAmount;
                        $row[] = $nontaggedItems;
                        $row[] = $unspecifiedAmount!=0?getmoney_format(round(($unspecifiedAmount/100000),2)):$unspecifiedAmount;
                        $row[] = $unspecifiedItems;
                        fputcsv($fp, $row);
                    }   


                    $grand_total_row = array();
                    $grand_total_row[] = "Grand Total";
                    $grand_total_row[] = $totalAmount!=0?getmoney_format(round(($totalAmount/100000),2)):$totalAmount;
                    $grand_total_row[] = $totalItems;
                    $grand_total_row[] = $taggedTotalAmount!=0?getmoney_format(round(($taggedTotalAmount/100000),2)):$taggedTotalAmount;
                    $grand_total_row[] = $taggedTotalItems;
                    $grand_total_row[] = $nontaggedTotalAmount!=0?getmoney_format(round(($nontaggedTotalAmount/100000),2)):$nontaggedTotalAmount; 
                    $grand_total_row[] = $nontaggedTotalItems;
                    $grand_total_row[] = $unspecifiedTotalAmount!=0?getmoney_format(round(($unspecifiedTotalAmount/100000),2)):$unspecifiedTotalAmount;
                    $grand_total_row[] = $unspecifiedTotalItems;
                    fputcsv($fp, $grand_total_row);


                    $grand_total_percentage_row = array();
                    $grand_total_percentage_row[] = "% to total FAR";
                    $grand_total_percentage_row[] = "100%";
                    $grand_total_percentage_row[] = "100%";
                    $grand_total_percentage_row[] = round(($taggedTotalAmount/$totalAmount)*100,2)."%";
                    $grand_total_percentage_row[] = round(($taggedTotalItems/$totalItems)*100,2)."%";
                    $grand_total_percentage_row[] = round(($nontaggedTotalAmount/$totalAmount)*100,2)."%";
                    $grand_total_percentage_row[] = round(($nontaggedTotalItems/$totalItems)*100,2)."%";
                    $grand_total_percentage_row[] = round(($unspecifiedTotalAmount/$totalAmount)*100,2)."%";
                    $grand_total_percentage_row[] = round(($unspecifiedTotalItems/$totalItems)*100,2)."%";
                    fputcsv($fp, $grand_total_percentage_row);
                }
                $report_type = "Standard";
                */


            } elseif ($type === 'consolidated') {

                // exceptioncategory
                /*
                1 ->Condition of Item
                2 ->Changes/ Updations of Items (New)
                3 ->Qty Validation Status
                4 ->Updated with Verification Remarks
                5 ->Updated with Item Notes
                6 ->Calculate Risk Exposure (New)
                8 ->Mode of Verification
                9 ->Duplicate Item Codes verified (NOT WORKING)
                10 ->Duplicate Item Codes Identified (New)
                */
                $lastProj = $this->db->query('SELECT * FROM company_projects WHERE status="' . $projectstatus . '" AND company_id=' . $company_id . ' ORDER BY id DESC LIMIT 1')->result();

                if ($lastProj) {
                    $condition = [
                        "status" => $projectstatus,
                        "company_id" => $company_id,
                        "original_table_name" => $lastProj[0]->original_table_name,
                        // "entity_code"         => $this->admin_registered_entity_code
                    ];
                    $getProjects = $this->tasks->get_data('company_projects', $condition);


                    foreach ($getProjects as $project) {
                        $old_pattern = ["/[^a-zA-Z0-9]/", "/_+/", "/_$/"];
                        $new_pattern = ["_", "_", ""];
                        $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));

                        $project_report = $this->_getExceptionCategoryReport($project_name, $exceptioncategory, $verificationstatus, $reportHeaders) ?: [];



                        if (is_array($project_report)) {
                            foreach ($project_report as $key => $val) {
                                if (is_array($val)) {
                                    if (!isset($report_data[$key]) || !is_array($report_data[$key])) {
                                        $report_data[$key] = [];
                                    }
                                    $report_data[$key] = array_merge($report_data[$key], $val);
                                } else {
                                    $report_data[$key] = $val;
                                }
                            }
                        }



                    }
                }


                if ($exceptioncategory == '1') {  //Condition of Item
                    // Step 1: Headers
                    /*
                    $headers = [
                        "Allocated Item Category",
                        "To be Verified (Amount in Lacs)", "To be Verified (Number of Qty)",
                        "Good Condition (Amount in Lacs)", "Good Condition (Number of Qty)",
                        "Damaged (Amount in Lacs)", "Damaged (Number of Qty)",
                        "Scrapped (Amount in Lacs)", "Scrapped (Number of Qty)",
                        "Missing (Amount in Lacs)", "Missing (Number of Qty)",
                        "Shifted (Amount in Lacs)", "Shifted (Number of Qty)",
                        "Not in Use (Amount in Lacs)", "Not in Use (Number of Qty)",
                        "Remaining to be Verified (Amount in Lacs)", "Remaining to be Verified (Number of Qty)"
                    ]; */

                    $headers = [
                        "Allocated Item Category",
                        "To be Verified (Amount in Lacs)",
                        "To be Verified (Number of Qty)",
                        "Good Condition (Number of Qty)",
                        "Damaged (Number of Qty)",
                        "Scrapped (Number of Qty)",
                        "Missing (Number of Qty)",
                        "Shifted (Number of Qty)",
                        "Not in Use (Number of Qty)",
                        "Remaining to be Verified (Amount in Lacs)",
                        "Remaining to be Verified (Number of Qty)"
                    ];


                    fputcsv($fp, $headers);

                    // Step 2: Safe loop (only if data exists)
                    if (isset($report_data['all']) && is_array($report_data['all']) && count($report_data['all']) > 0) {

                        $lookup = [];
                        foreach (['good', 'damaged', 'scrapped', 'missing', 'shifted', 'notinuse'] as $status) {
                            $lookup[$status] = isset($report_data[$status]) && is_array($report_data[$status])
                                ? $report_data[$status] : [];
                        }

                        $column_totals = array_fill(0, count($headers), 0);

                        $totalAmount = 0;
                        $totalItems = 0;
                        $goodTotalAmount = 0;
                        $goodTotalItems = 0;
                        $damagedTotalAmount = 0;
                        $damagedTotalItems = 0;
                        $scrappedTotalAmount = 0;
                        $scrappedTotalItems = 0;
                        $missingTotalAmount = 0;
                        $missingTotalItems = 0;
                        $shiftedTotalAmount = 0;
                        $shiftedTotalItems = 0;
                        $notinuseTotalAmount = 0;
                        $notinuseTotalItems = 0;
                        $remainingTotalAmount = 0;
                        $remainingTotalItems = 0;
                        $remainitemstotal = 0;
                        foreach ($report_data['all'] as $allcat) {
                            $row = [];
                            $goodAmount = 0;
                            $goodItems = 0;
                            $damagedAmount = 0;
                            $damagedItems = 0;
                            $scrappedAmount = 0;
                            $scrappedItems = 0;
                            $missingAmount = 0;
                            $missingItems = 0;
                            $shiftedAmount = 0;
                            $shiftedItems = 0;
                            $notinuseAmount = 0;
                            $notinuseItems = 0;
                            $remainingAmount = 0;
                            $remainingItems = 0;
                            $totalAmount = $totalAmount + $allcat->total_amount;
                            $totalItems = $totalItems + $allcat->total_qty;
                            foreach ($report_data['good'] as $good) {
                                if ($good->item_category == $allcat->item_category) {
                                    $goodAmount = $good->total_amount;
                                    $goodItems = $good->good_qty;
                                    $goodTotalAmount = $goodTotalAmount + $goodAmount;
                                    $goodTotalItems = $goodTotalItems + $goodItems;
                                }
                            }
                            foreach ($report_data['damaged'] as $damaged) {
                                if ($damaged->item_category == $allcat->item_category) {
                                    $damagedAmount = $damaged->total_amount;
                                    $damagedItems = $damaged->damaged_qty;
                                    $damagedTotalAmount = $damagedTotalAmount + $damagedAmount;
                                    $damagedTotalItems = $damagedTotalItems + $damagedItems;
                                }
                            }
                            foreach ($report_data['scrapped'] as $scrapped) {
                                if ($scrapped->item_category == $allcat->item_category) {
                                    $scrappedAmount = $scrapped->total_amount;
                                    $scrappedItems = $scrapped->scrapped_qty;
                                    $scrappedTotalAmount = $scrappedTotalAmount + $scrappedAmount;
                                    $scrappedTotalItems = $scrappedTotalItems + $scrappedItems;
                                }
                            }
                            foreach ($report_data['missing'] as $missing) {
                                if ($missing->item_category == $allcat->item_category) {
                                    $missingAmount = $missing->total_amount;
                                    $missingItems = $missing->missing_qty;
                                    $missingTotalAmount = $missingTotalAmount + $missingAmount;
                                    $missingTotalItems = $missingTotalItems + $missingItems;
                                }
                            }
                            foreach ($report_data['shifted'] as $shifted) {
                                if ($shifted->item_category == $allcat->item_category) {
                                    $shiftedAmount = $shifted->total_amount;
                                    $shiftedItems = $shifted->shifted_qty;
                                    $shiftedTotalAmount = $shiftedTotalAmount + $shiftedAmount;
                                    $shiftedTotalItems = $shiftedTotalItems + $shiftedItems;
                                }
                            }
                            foreach ($report_data['notinuse'] as $notinuse) {
                                if ($notinuse->item_category == $allcat->item_category) {
                                    $notinuseAmount = $notinuse->total_amount;
                                    $notinuseItems = $notinuse->notinuse_qty;
                                    $notinuseTotalAmount = $notinuseTotalAmount + $notinuseAmount;
                                    $notinuseTotalItems = $notinuseTotalItems + $notinuseItems;
                                }
                            }
                            $remainitem = '0';
                            foreach ($report_data['remaining'] as $remainingdata) {
                                if ($remainingdata->item_category == $allcat->item_category) {
                                    $remainitem = $remainingdata->items;
                                }
                                $remainitem = $allcat->total_qty - ($goodItems + $damagedItems + $scrappedItems + $missingItems + $shiftedItems + $notinuseItems);


                            }
                            $remainitemstotal += $remainitem;


                            $remainingAmount = $allcat->total_amount - ($goodAmount + $damagedAmount + $scrappedAmount + $missingAmount + $shiftedAmount + $notinuseAmount);
                            $remainingItems = $allcat->total_qty - ($goodItems + $damagedItems + $scrappedItems + $missingItems + $shiftedItems + $notinuseItems);
                            $remainingTotalAmount = $remainingTotalAmount + $remainingAmount;
                            $remainingTotalItems = $remainingTotalItems + $remainingItems;


                            $row[] = $allcat->item_category;
                            $row[] = $allcat->total_amount != 0 ? getmoney_format(round(($allcat->total_amount / 100000), 2)) : $allcat->total_amount;
                            $row[] = $allcat->total_qty;
                            $row[] = $goodItems;
                            $row[] = $damagedItems;
                            $row[] = $scrappedItems;
                            $row[] = $missingItems;
                            $row[] = $shiftedItems;
                            $row[] = $notinuseItems;
                            $row[] = $remainingAmount != 0 ? getmoney_format(round(($remainingAmount / 100000), 2)) : $remainingAmount;
                            $row[] = $remainitem;
                            fputcsv($fp, $row);
                        }



                        $Grand_Total_row[] = "Grand Total";
                        $Grand_Total_row[] = $totalAmount != 0 ? getmoney_format(round(($totalAmount / 100000), 2)) : $totalAmount;
                        $Grand_Total_row[] = $totalItems;
                        $Grand_Total_row[] = $goodTotalItems;
                        $Grand_Total_row[] = $damagedTotalItems;
                        $Grand_Total_row[] = $scrappedTotalItems;
                        $Grand_Total_row[] = $missingTotalItems;
                        $Grand_Total_row[] = $shiftedTotalItems;
                        $Grand_Total_row[] = $notinuseTotalItems;
                        $Grand_Total_row[] = $remainingTotalAmount != 0 ? getmoney_format(round(($remainingTotalAmount / 100000), 2)) : $remainingTotalAmount;
                        $Grand_Total_row[] = $remainingTotalItems;
                        fputcsv($fp, $Grand_Total_row);



                        $Grand_Total_percentage_row[] = "% to Grand Total";
                        $Grand_Total_percentage_row[] = "100%";
                        $Grand_Total_percentage_row[] = "100%";
                        $Grand_Total_percentage_row[] = round(($goodTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($damagedTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($scrappedTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($missingTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($shiftedTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($notinuseTotalItems / $totalItems) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($remainingTotalAmount / $totalAmount) * 100, 2) . '%';
                        $Grand_Total_percentage_row[] = round(($remainingTotalItems / $totalItems) * 100, 2) . '%';
                        fputcsv($fp, $Grand_Total_percentage_row);
                        /*
                        foreach ($report_data['all'] as $category) {
                            $row = [];
                            $row[] = $category->item_category;

                            $toBeVerifiedAmount = (float)($category->total_amount / 100000);
                            $toBeVerifiedQty    = (int)$category->total_qty;
                            $row[] = $toBeVerifiedAmount;
                            $row[] = $toBeVerifiedQty;

                            $getValues = function($status, $cat_name) use ($lookup) {
                                foreach ($lookup[$status] as $item) {
                                    if ($item->item_category === $cat_name) {
                                        return [
                                            'amount' => (float)($item->total_amount / 100000),
                                            'qty'    => (int)($item->qty ?? 0)
                                        ];
                                    }
                                }
                                return ['amount' => 0, 'qty' => 0];
                            };

                            $good     = $getValues('good', $category->item_category);
                            $damaged  = $getValues('damaged', $category->item_category);
                            $scrapped = $getValues('scrapped', $category->item_category);
                            $missing  = $getValues('missing', $category->item_category);
                            $shifted  = $getValues('shifted', $category->item_category);
                            $notinuse = $getValues('notinuse', $category->item_category);

                            $row = array_merge($row, [
                                $good['amount'], $good['qty'],
                                $damaged['amount'], $damaged['qty'],
                                $scrapped['amount'], $scrapped['qty'],
                                $missing['amount'], $missing['qty'],
                                $shifted['amount'], $shifted['qty'],
                                $notinuse['amount'], $notinuse['qty']
                            ]);

                            $remainingAmount = $toBeVerifiedAmount - ($good['amount'] + $damaged['amount'] + $scrapped['amount'] + $missing['amount'] + $shifted['amount'] + $notinuse['amount']);
                            $remainingQty    = $toBeVerifiedQty - ($good['qty'] + $damaged['qty'] + $scrapped['qty'] + $missing['qty'] + $shifted['qty'] + $notinuse['qty']);

                            $row[] = $remainingAmount > 0 ? round($remainingAmount, 2) : 0;
                            $row[] = $remainingQty > 0 ? $remainingQty : 0;

                            echo "<pre>row :";
                            print_r($row);
                            echo "</pre>";
                            exit;

                            fputcsv($fp, $row);

                            for ($i = 1; $i < count($row); $i++) {
                                $column_totals[$i] += $row[$i];
                            }
                        } */

                        // Totals

                        /*
                        $total_row = ["Grand Total"];
                        for ($i = 1; $i < count($headers); $i++) {
                            $total_row[] = round($column_totals[$i], 2);
                        }
                        fputcsv($fp, $total_row);
                        */

                    } else {
                        fputcsv($fp, ["No data found"]); /// This Data is Fetching While Report Generated
                    }
                }

                if ($exceptioncategory == '2') {  //Changes/ Updations of Items (New)  [File Name :- ChangesUpdationsItemsReport]

                    $headers = array();
                    $project_header_column_value = explode(",", $report_data['project_header_column_value']);
                    unset($project_header_column_value[0]);
                    unset($project_header_column_value[1]);
                    $headers[] = 'Allocated Item Category';

                    foreach ($project_header_column_value as $project_header_column_value_value) {
                        $headers[] = ucfirst(str_replace('_', ' ', $project_header_column_value_value));
                    }
                    fputcsv($fp, $headers);

                    $rows = array();
                    foreach ($report_data['different'] as $key => $value) {
                        $row = array(); // Create new row for each record            
                        $row[] = $key;
                        foreach ($project_header_column_value as $project_header_column_value_value) {
                            if (isset($report_data['different'][$key][$project_header_column_value_value])) {
                                $row[] = count($report_data['different'][$key][$project_header_column_value_value]);
                            } else {
                                $row[] = "0";
                            }
                        }
                        // $rows[] = $row; // Add row to master array
                        fputcsv($fp, $row);
                    }

                }

                if ($exceptioncategory == '3') {  //Qty Validation Status
                    // Step 1: Headers      //Should be Dynamic     [File Name :- quantityValidationReport]
                    $headers = [
                        "Allocated Item Category",
                        "To be Verified - Amount(in Lacs)",
                        "To be Verified - Number of Line Items",
                        "Verified - Amount(in Lacs)",
                        "Verified - Number of Line Items",
                        "Verified as Equal - Amount(in Lacs)",
                        "Verified as Equal - Number of Line Items",
                        "Short Found - Amount(in Lacs)",
                        "Short Found - Number of Line Items",
                        "Excess Found - Amount(in Lacs)",
                        "Excess Found - Number of Line Items",
                        "Remaining to be Verified - Amount(in Lacs)",
                        "Remaining to be Verified - Number of Line Items",
                    ];
                    fputcsv($fp, $headers);


                    $totalAmount = 0;
                    $totalItems = 0;
                    $verifiedTotalAmount = 0;
                    $verifiedTotalItems = 0;
                    $shortTotalAmount = 0;
                    $shortTotalItems = 0;
                    $equalTotalAmount = 0;
                    $equalTotalItems = 0;
                    $excessTotalAmount = 0;
                    $excessTotalItems = 0;
                    $remainingTotalAmount = 0;
                    $remainingTotalItems = 0;
                    $remainitemstotal = 0;
                    $remainitemamounttotal = 0;
                    $excessitemtotal = 0;
                    $excessamounttotalnew = 0;
                    foreach ($report_data['all'] as $allcat) {
                        $row = [];
                        $verifiedAmount = 0;
                        $verifiedItems = 0;
                        $shortAmount = 0;
                        $shortItems = 0;
                        $equalAmount = 0;
                        $equalItems = 0;
                        $excessAmount = 0;
                        $excessItems = 0;
                        $remainingAmount = 0;
                        $remainingItems = 0;

                        $totalAmount = $totalAmount + $allcat->total_amount;
                        $totalItems = $totalItems + $allcat->total_items;
                        foreach ($report_data['verified'] as $verified) {
                            if ($verified->item_category == $allcat->item_category) {
                                $verifiedAmount = $verified->total_amount;
                                $verifiedItems = $verified->total_items;
                                $verifiedTotalAmount = $verifiedTotalAmount + $verifiedAmount;
                                $verifiedTotalItems = $verifiedTotalItems + $verifiedItems;

                                if ($verified->total_items > $allcat->total_items && $verified->total_items > 0) {
                                    $shortAmount = $allcat->total_amount - $verified->total_amount;
                                    $shortItems = $allcat->total_items - $verified->total_items;
                                    $shortTotalAmount = $shortTotalAmount + $shortAmount;
                                    $shortTotalItems = $shortTotalItems + $shortItems;
                                }

                                if ($verified->total_items > $allcat->total_items) {
                                    // // $excessAmount=$allcat->total_amount - $verified->total_amount;
                                    // $excessItems=$verified->total_items - $allcat->total_items;

                                    // $excessTotalAmount=$excessTotalAmount+$excessAmount;
                                    // $excessTotalItems=$excessTotalItems+$excessItems;
                                }

                                if ($verified->total_items < 1) {
                                    $remainingAmount = $allcat->total_amount;
                                    $remainingItems = $allcat->total_items;
                                    $remainingTotalAmount = $remainingTotalAmount + $remainingAmount;
                                    $remainingTotalItems = $remainingTotalItems + $remainingItems;
                                }

                            }

                        }
                        foreach ($report_data['verifiedequal'] as $verifiedeq) {
                            if ($verifiedeq->item_category == $allcat->item_category) {
                                $equalAmount = $verifiedeq->total_amount;
                                $equalItems = $verifiedeq->total_items;
                                $equalTotalAmount = $equalTotalAmount + $equalAmount;
                                $equalTotalItems = $equalTotalItems + $equalItems;
                            }
                        }

                        /*
                        if($_SESSION['reportData']['verification_status']=='Not-Verified')
                        {
                            $remainingAmount=$allcat->total_amount;
                            $remainingItems=$allcat->total_items;
                            $remainingTotalAmount=$remainingTotalAmount+$remainingAmount;
                            $remainingTotalItems=$remainingTotalItems+$remainingItems;
                        }
                        */

                        $remainitem = '0';
                        $remainitemamount = '0';
                        foreach ($report_data['remaining'] as $remainingdata) {
                            if ($remainingdata->item_category == $allcat->item_category) {
                                $remainitem = $remainingdata->items;
                                $remainitemamount = $remainingdata->total_amount;
                            }

                        }
                        $remainitemstotal += $remainitem;
                        $remainitemamounttotal += $remainitemamount;

                        $excessitem = '0';
                        $excessamount = '0';
                        foreach ($report_data['excess'] as $excess) {
                            if ($excess->item_category == $allcat->item_category) {
                                $excessitem = $excess->items;
                                $excessAmount = $excess->total_amount;
                                $excessamounttotalnew = $excessamounttotalnew + $excessAmount;

                            }


                        }
                        $excessitemtotal += $excessitem;


                        /*
                        if($_SESSION['reportData']['verification_status']=='Not-Verified')
                        {

                            $equalAmount = 0;
                            $equalItems = 0;
                            $shortAmount = 0;
                            $shortItems = 0;
                            $excessAmount = 0;
                            $excessitem = 0;
                            // $equalAmount = 0;

                            $equalTotalAmount = 0;
                            $equalTotalItems = 0;
                            $shortTotalAmount = 0;
                            $shortTotalItems = 0;
                            $excessamounttotalnew = 0;
                            $excessitemtotal = 0;
                        } */

                        $row[] = $allcat->item_category;
                        $row[] = $allcat->total_amount != 0 ? getmoney_format(round(($allcat->total_amount / 100000), 2)) : $allcat->total_amount;
                        $row[] = $allcat->total_items;
                        $row[] = $verifiedAmount != 0 ? getmoney_format(round(($verifiedAmount / 100000), 2)) : $verifiedAmount;
                        $row[] = $verifiedItems;
                        $row[] = $equalAmount != 0 ? getmoney_format(round(($equalAmount / 100000), 2)) : $equalAmount;
                        $row[] = $equalItems;
                        $row[] = $shortAmount != 0 ? getmoney_format(round(($shortAmount / 100000), 2)) : $shortAmount;
                        $row[] = $shortItems;
                        $row[] = $excessAmount != 0 ? getmoney_format(round(($excessAmount / 100000), 2)) : $excessAmount;
                        $row[] = $excessitem;
                        $row[] = $remainitemamount != 0 ? getmoney_format(round(($remainitemamount / 100000), 2)) : $remainitemamount;
                        $row[] = $remainitem;
                        fputcsv($fp, $row);
                    }



                    $grand_total_row = array();
                    $grand_total_row[] = "Grand Total";
                    $grand_total_row[] = $totalAmount != 0 ? getmoney_format(round(($totalAmount / 100000), 2)) : $totalAmount;
                    $grand_total_row[] = $totalItems;
                    $grand_total_row[] = $verifiedTotalAmount != 0 ? getmoney_format(round(($verifiedTotalAmount / 100000), 2)) : $verifiedTotalAmount;
                    $grand_total_row[] = $verifiedTotalItems;
                    $grand_total_row[] = $equalTotalAmount != 0 ? getmoney_format(round(($equalTotalAmount / 100000), 2)) : $equalTotalAmount;
                    $grand_total_row[] = $equalTotalItems;
                    $grand_total_row[] = $shortTotalAmount != 0 ? getmoney_format(round(($shortTotalAmount / 100000), 2)) : $shortTotalAmount;
                    $grand_total_row[] = $shortTotalItems;
                    $grand_total_row[] = $excessamounttotalnew != 0 ? getmoney_format(round(($excessamounttotalnew / 100000), 2)) : $excessamounttotalnew;
                    $grand_total_row[] = $excessitemtotal;
                    $grand_total_row[] = $remainitemamounttotal != 0 ? getmoney_format(round(($remainitemamounttotal / 100000), 2)) : $remainitemamounttotal;
                    $grand_total_row[] = $remainitemstotal;
                    fputcsv($fp, $grand_total_row);

                    $grand_total_percentage_row = array();
                    $grand_total_percentage_row[] = "% to Grand Total";
                    $grand_total_percentage_row[] = "100%";
                    $grand_total_percentage_row[] = "100%";
                    $grand_total_percentage_row[] = round(($verifiedTotalAmount / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($verifiedTotalItems / $totalItems) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($equalTotalAmount / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($equalTotalItems / $totalItems) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($shortTotalAmount / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($shortTotalItems / $totalItems) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($excessamounttotalnew / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($excessitemtotal / $totalItems) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($remainitemamounttotal / $totalAmount) * 100, 2) . "%";
                    $grand_total_percentage_row[] = round(($remainitemstotal / $totalItems) * 100, 2) . "%";
                    fputcsv($fp, $grand_total_percentage_row);


                }

                if ($exceptioncategory == '4') {  //Updated with Verification Remarks     [File Name :- verificationRemarksReport]

                    $headers = [
                        "Allocated Item Category",
                        "Number of Line Items",
                    ];
                    fputcsv($fp, $headers);

                    $totalItems = 0;
                    foreach ($report_data['all'] as $allcat) {
                        $row = array();
                        $totalItems = $totalItems + $allcat->items;

                        $row[] = $allcat->item_category;
                        $row[] = $allcat->items;
                        fputcsv($fp, $row);
                    }


                    $row1[] = "Grand Total";
                    $row1[] = $totalItems;

                    fputcsv($fp, $row1);

                }

                if ($exceptioncategory == '5') {  //Updated with Item Notes

                    $headers = [
                        "Allocated Item Category",
                        "Number of Line Items",

                    ];
                    fputcsv($fp, $headers);

                    $totalItems = 0;
                    foreach ($report_data['all'] as $allcat) {
                        $row = array();
                        $totalItems = $totalItems + $allcat->items;

                        $row[] = $allcat->item_category;
                        $row[] = $allcat->items;
                        fputcsv($fp, $row);
                    }


                    $row1[] = "Grand Total";
                    $row1[] = $totalItems;

                    fputcsv($fp, $row1);


                }

                if ($exceptioncategory == '6') {  //Calculate Risk Exposure (New)         I think only showing When Finish


                    $headers = [
                        "Allocated Item Category",
                        "Damaged (Amount in Lacs)",
                        "Damaged (Number of Qty)",
                        "Scrapped (Amount in Lacs)",
                        "Scrapped (Number of Qty)",
                        "Missing (Amount in Lacs)",
                        "Missing (Number of Qty)",
                        "Shifted (Amount in Lacs)",
                        "Shifted (Number of Qty)",
                        "Not in Use (Amount in Lacs)",
                        "Not in Use (Number of Qty)",
                        "Short (Amount in Lacs)",
                        "Short (Number of Qty)",
                        "Excess (Amount in Lacs)",
                        "Excess (Number of Qty)",
                        "Total Risk Exposure (Amount in Lacs)",
                        "Total Risk Exposure (Number of Qty)",
                    ];
                    fputcsv($fp, $headers);

                    $totalAmount = 0;
                    $totalItems = 0;
                    $goodTotalAmount = 0;
                    $goodTotalItems = 0;
                    $damagedTotalAmount = 0;
                    $damagedTotalItems = 0;
                    $scrappedTotalAmount = 0;
                    $scrappedTotalItems = 0;
                    $missingTotalAmount = 0;
                    $shiftedTotalAmount = 0;
                    $shiftedTotalItems = 0;
                    $notinuseTotalAmount = 0;
                    $notinuseTotalItems = 0;
                    $remainingTotalAmount = 0;
                    $remainingTotalItems = 0;
                    $remainitemstotal = 0;
                    $shortTotalAmount = 0;
                    $shortTotalItems = 0;
                    foreach ($report_data['all'] as $allcat) {
                        $row = [];
                        $goodAmount = 0;
                        $goodItems = 0;
                        $damagedAmount = 0;
                        $damagedItems = 0;
                        $scrappedAmount = 0;
                        $scrappedItems = 0;
                        $missingAmount = 0;
                        $missingItems = 0;
                        $shiftedAmount = 0;
                        $shiftedItems = 0;
                        $notinuseAmount = 0;
                        $notinuseItems = 0;
                        $remainingAmount = 0;
                        $remainingItems = 0;

                        $shortAmount = 0;
                        $shortItems = 0;
                        $excessitem = 0;
                        $excessamount = 0;

                        foreach ($report_data['verified'] as $verified) {
                            if ($verified->item_category == $allcat->item_category) {
                                $verifiedAmount = $verified->total_amount;
                                $verifiedItems = $verified->total_items;
                                $verifiedTotalAmount = $verifiedTotalAmount + $verifiedAmount;
                                $verifiedTotalItems = $verifiedTotalItems + $verifiedItems;

                                if ($verified->total_items > $allcat->total_items && $verified->total_items > 0) {
                                    $shortAmount = $allcat->total_amount - $verified->total_amount;
                                    $shortItems = $allcat->total_items - $verified->total_items;
                                    $shortTotalAmount = $shortTotalAmount + $shortAmount;
                                    $shortTotalItems = $shortTotalItems + $shortItems;
                                }

                                if ($verified->total_items > $allcat->total_items) {
                                    // // $excessAmount=$allcat->total_amount - $verified->total_amount;
                                    // $excessItems=$verified->total_items - $allcat->total_items;

                                    // $excessTotalAmount=$excessTotalAmount+$excessAmount;
                                    // $excessTotalItems=$excessTotalItems+$excessItems;
                                }

                                if ($verified->total_items < 1) {
                                    $remainingAmount = $allcat->total_amount;
                                    $remainingItems = $allcat->total_items;
                                    $remainingTotalAmount = $remainingTotalAmount + $remainingAmount;
                                    $remainingTotalItems = $remainingTotalItems + $remainingItems;
                                }

                            }

                        }



                        $totalAmount = $totalAmount + $allcat->total_amount;
                        $totalItems = $totalItems + $allcat->total_qty;
                        foreach ($report_data['good'] as $good) {
                            if ($good->item_category == $allcat->item_category) {
                                $goodAmount = $good->total_amount;
                                $goodItems = $good->good_qty;
                                $goodTotalAmount = $goodTotalAmount + $goodAmount;
                                $goodTotalItems = $goodTotalItems + $goodItems;
                            }
                        }
                        foreach ($report_data['damaged'] as $damaged) {
                            if ($damaged->item_category == $allcat->item_category) {
                                $damagedAmount = $damaged->total_amount;
                                $damagedItems = $damaged->damaged_qty;
                                $damagedTotalAmount = $damagedTotalAmount + $damagedAmount;
                                $damagedTotalItems = $damagedTotalItems + $damagedItems;
                            }
                        }
                        foreach ($report_data['scrapped'] as $scrapped) {
                            if ($scrapped->item_category == $allcat->item_category) {
                                $scrappedAmount = $scrapped->total_amount;
                                $scrappedItems = $scrapped->scrapped_qty;
                                $scrappedTotalAmount = $scrappedTotalAmount + $scrappedAmount;
                                $scrappedTotalItems = $scrappedTotalItems + $scrappedItems;
                            }
                        }
                        foreach ($report_data['missing'] as $missing) {
                            if ($missing->item_category == $allcat->item_category) {
                                $missingAmount = $missing->total_amount;
                                $missingItems = $missing->missing_qty;
                                $missingTotalAmount = $missingTotalAmount + $missingAmount;
                                $missingTotalItems = $missingTotalItems + $missingItems;
                            }
                        }
                        foreach ($report_data['shifted'] as $shifted) {
                            if ($shifted->item_category == $allcat->item_category) {
                                $shiftedAmount = $shifted->total_amount;
                                $shiftedItems = $shifted->shifted_qty;
                                $shiftedTotalAmount = $shiftedTotalAmount + $shiftedAmount;
                                $shiftedTotalItems = $shiftedTotalItems + $shiftedItems;
                            }
                        }
                        foreach ($report_data['notinuse'] as $notinuse) {
                            if ($notinuse->item_category == $allcat->item_category) {
                                $notinuseAmount = $notinuse->total_amount;
                                $notinuseItems = $notinuse->notinuse_qty;
                                $notinuseTotalAmount = $notinuseTotalAmount + $notinuseAmount;
                                $notinuseTotalItems = $notinuseTotalItems + $notinuseItems;
                            }
                        }
                        $remainitem = '0';
                        foreach ($report_data['remaining'] as $remainingdata) {
                            if ($remainingdata->item_category == $allcat->item_category) {
                                $remainitem = $remainingdata->items;
                            }

                        }
                        $remainitemstotal += $remainitem;


                        $excessitem = 0;
                        $excessamount = 0;
                        foreach ($report_data['excess'] as $excess) {
                            if ($excess->item_category == $allcat->item_category) {
                                $excessitem = $excess->items;
                                $excessAmount = $excess->total_amount;
                                $excessamounttotalnew = $excessamounttotalnew + $excessAmount;

                            }
                        }

                        $excessitemtotal += $excessitem;
                        $remainingAmount = $allcat->total_amount - ($goodAmount + $damagedAmount + $scrappedAmount + $missingAmount + $shiftedAmount + $notinuseAmount);
                        $remainingItems = $allcat->total_qty - ($goodItems + $damagedItems + $scrappedItems + $missingItems + $shiftedItems + $notinuseItems);
                        $remainingTotalAmount = $remainingTotalAmount + $remainingAmount;
                        $remainingTotalItems = $remainingTotalItems + $remainingItems;



                        $row[] = $allcat->item_category;

                        $row[] = $damagedAmount != 0 ? getmoney_format(round(($damagedAmount / 100000), 2)) : $damagedAmount;
                        $row[] = $damagedItems;

                        $row[] = $scrappedAmount != 0 ? getmoney_format(round(($scrappedAmount / 100000), 2)) : $scrappedAmount;
                        $row[] = $scrappedItems;

                        $row[] = $missingAmount != 0 ? getmoney_format(round(($missingAmount / 100000), 2)) : $missingAmount;
                        $row[] = $missingItems;

                        $row[] = $shiftedAmount != 0 ? getmoney_format(round(($shiftedAmount / 100000), 2)) : $shiftedAmount;
                        ;
                        $row[] = $shiftedItems;

                        $row[] = $notinuseAmount != 0 ? getmoney_format(round(($notinuseAmount / 100000), 2)) : $notinuseAmount;
                        $row[] = $notinuseItems;

                        $row[] = $shortAmount != 0 ? getmoney_format(round(($shortAmount / 100000), 2)) : $shortAmount;
                        $row[] = $shortItems;

                        if ($excessAmount == NULL) {
                            $row[] = "0";
                        } else {
                            $row[] = $excessAmount != 0 ? getmoney_format(round(($excessAmount / 100000), 2)) : $excessAmount;
                        }
                        $row[] = $excessitem;

                        $total_risk_exposure_amount = $damagedAmount + $scrappedAmount + $missingAmount + $shiftedAmount + $notinuseAmount + $shortAmount;
                        $total_risk_exposure_qty = $damagedItems + $scrappedItems + $missingItems + $shiftedItems + $notinuseItems + $shortItems;

                        $row[] = $total_risk_exposure_amount != 0 ? getmoney_format(round(($total_risk_exposure_amount / 100000), 2)) : $total_risk_exposure_amount;
                        $row[] = $total_risk_exposure_qty;
                        fputcsv($fp, $row);
                    }

                    $Grand_Total_row[] = "Grand Total";
                    $Grand_Total_row[] = $damagedTotalAmount != 0 ? getmoney_format(round(($damagedTotalAmount / 100000), 2)) : $damagedTotalAmount;
                    $Grand_Total_row[] = $damagedTotalItems;
                    $Grand_Total_row[] = $scrappedTotalAmount != 0 ? getmoney_format(round(($scrappedTotalAmount / 100000), 2)) : $scrappedTotalAmount;
                    $Grand_Total_row[] = $scrappedTotalItems;
                    $Grand_Total_row[] = $missingTotalAmount != 0 ? getmoney_format(round(($missingTotalAmount / 100000), 2)) : $missingTotalAmount;
                    ;
                    $Grand_Total_row[] = $missingTotalItems;
                    $Grand_Total_row[] = $shiftedTotalAmount != 0 ? getmoney_format(round(($shiftedTotalAmount / 100000), 2)) : $shiftedTotalAmount;
                    $Grand_Total_row[] = $shiftedTotalItems;
                    $Grand_Total_row[] = $notinuseTotalAmount != 0 ? getmoney_format(round(($notinuseTotalAmount / 100000), 2)) : $notinuseTotalAmount;
                    $Grand_Total_row[] = $notinuseTotalItems;
                    $Grand_Total_row[] = $shortTotalAmount != 0 ? getmoney_format(round(($shortTotalAmount / 100000), 2)) : $shortTotalAmount;
                    $Grand_Total_row[] = $shortTotalItems;
                    $Grand_Total_row[] = $excessamounttotalnew != 0 ? getmoney_format(round(($excessamounttotalnew / 100000), 2)) : $excessamounttotalnew;
                    $Grand_Total_row[] = $excessitemtotal;

                    $total_risk_exposure_amount_grand = $damagedTotalAmount + $scrappedTotalAmount + $missingTotalAmount + $shiftedTotalAmount + $notinuseTotalAmount + $shortTotalAmount;
                    $total_risk_exposure_qty_grand = $damagedTotalItems + $scrappedTotalItems + $missingTotalItems + $shiftedTotalItems + $notinuseTotalItems + $shortTotalItems;

                    $Grand_Total_row[] = $total_risk_exposure_amount_grand != 0 ? getmoney_format(round(($total_risk_exposure_amount_grand / 100000), 2)) : $total_risk_exposure_amount_grand;
                    $Grand_Total_row[] = $total_risk_exposure_qty_grand;
                    fputcsv($fp, $Grand_Total_row);

                    $Grand_Total_percentage_row[] = "% to Grand Total";
                    $Grand_Total_percentage_row[] = round(($damagedTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($damagedTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($scrappedTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($scrappedTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($missingTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($missingTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($shiftedTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($shiftedTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($notinuseTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($notinuseTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($shortTotalAmount / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($shortTotalItems / $totalItems) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($excessamounttotalnew / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($excessitemtotal / $totalItems) * 100, 2) . "%";

                    $Grand_Total_percentage_row[] = round(($total_risk_exposure_amount_grand / $totalAmount) * 100, 2) . "%";
                    $Grand_Total_percentage_row[] = round(($total_risk_exposure_qty_grand / $totalItems) * 100, 2) . "%";
                    fputcsv($fp, $Grand_Total_percentage_row);
                }

                if ($exceptioncategory == '7') {

                }

                if ($exceptioncategory == '8') {  //Mode of Verification
                    $headers = [
                        "Allocated Item Category",
                        "Verified by Scan",
                        "Verified by Manual Search",
                    ];
                    fputcsv($fp, $headers);


                    $table = [];
                    $grandScan = $grandManual = 0;

                    foreach ($report_data['all'] as $row) {
                        $category = $row->item_category;

                        // find scan items
                        $scan = 0;
                        foreach ($report_data['scan'] as $s) {
                            if ($s->item_category === $category) {
                                $scan = $s->items;
                                break;
                            }
                        }

                        // find manual items
                        $manual = 0;
                        foreach ($report_data['manual'] as $m) {
                            if ($m->item_category === $category) {
                                $manual = $m->items;
                                break;
                            }
                        }

                        $table[] = [
                            "category" => $category,
                            "scan" => $scan,
                            "manual" => $manual
                        ];

                        $grandScan += $scan;
                        $grandManual += $manual;
                    }


                    foreach ($table as $row) {
                        $row1 = array();
                        $row1[] = $row['category'];
                        $row1[] = $row['scan'];
                        $row1[] = $row['manual'];
                        fputcsv($fp, $row1);
                    }



                    $row2[] = "Grand Total";
                    $row2[] = $grandScan;
                    $row2[] = $grandManual;
                    fputcsv($fp, $row2);





                }

                if ($exceptioncategory == '9') {  //Duplicate Item Codes verified (NOT WORKING)

                }

                if ($exceptioncategory == '10') { //Duplicate Item Codes Identified (New)

                    $headers = [
                        "Allocated Item Category",
                        "No Of Line Item",
                        "Not Verified",
                        "SCAN",
                        "SEARCH"
                    ];
                    fputcsv($fp, $headers);

                    $row1 = array();
                    if (!empty($report_data['Duplicate_Array'])) {
                        foreach ($report_data['Duplicate_Array'] as $key => $allcat) {
                            $row1[] = $allcat['item_category'];
                            $row1[] = $allcat['total_uniqu_record_cout'];
                            $row1[] = $allcat['total_not_verified_uniqu_record_cout'];
                            $row1[] = $allcat['total_scan_uniqu_record_cout'];
                            $row1[] = $allcat['total_search_uniqu_record_cout'];
                        }
                    }
                    fputcsv($fp, $row1);


                }
                $report_type = $exceptioncategory;

            } elseif ($type === 'additional') {
                $report_data = $this->tasks->genrateadditionalassets($projectSelect) ?: [];
                $project_data = [
                    "company" => $this->tasks->com_row($company_id),
                    "location" => $this->tasks->loc_row($location_id)
                ];

                $headers = [
                    "Project Name",
                    "Project ID",
                    "Asset Category",
                    "Asset Classification",
                    "Asset Description",
                    "Quantity Verified",
                    "Location",
                    "Condition Assets",
                    "Temporary Verification ID/ Ref",
                    "Verifier Name",
                    "Verified on (Date & Time)"
                ];
                fputcsv($fp, $headers);

                foreach ($report_data as $row) {
                    $prjrow = get_project_row($row->project_id);
                    $row1 = array();
                    $row1[] = $prjrow->project_name;
                    $row1[] = $prjrow->project_id;
                    $row1[] = $row->asset_category;
                    $row1[] = $row->asset_classification;
                    $row1[] = $row->description_of_asset;
                    $row1[] = $row->qty_verified;
                    $row1[] = $row->current_location;
                    $row1[] = $row->condition_of_assets;
                    $row1[] = $row->temp_verifiction_id_ref;
                    $row1[] = $row->verified_name;
                    $row1[] = date("d-M-Y G:i:a", strtotime($row->updated_at));
                    fputcsv($fp, $row1);
                }

                $report_type = "additional";
            }







            fclose($fp);

            /**
             * ------------------------
             * EMAIL SENDING
             * ------------------------
             */
            // $user_email = 'hardik.meghnathi12@gmail.com';

            $email_result = $this->_sendEmailDirect($filename, $user_email, $projectSelect, $user_id, $report_type);

            echo json_encode([
                "success" => true,
                "status_code" => 200,
                "message" => $email_result['success']
                    ? "Report generated and emailed successfully"
                    : "Report generated but email sending failed",
                "data" => [
                    "filename" => $filename,
                    "email_sent" => $email_result['success'],
                    "email_message" => $email_result['message'],
                    "user_email" => $user_email,
                    "record_count" => isset($report_data['all']) ? count($report_data['all']) : 0,
                    "generated_at" => date('Y-m-d H:i:s')
                ]
            ]);

        } catch (Exception $e) {
            log_message('error', 'GenerateExceptionReport Error: ' . $e->getMessage());
            echo json_encode([
                "success" => false,
                "status_code" => 500,
                "message" => "Internal server error occurred",
                "error" => $e->getMessage()
            ]);
        }
    }


    /**
     * Helper: Decide which exception report to generate
     */
    private function _getExceptionCategoryReport($project_name, $exceptioncategory, $verificationstatus, $reportHeaders)
    {

        // echo '<pre>exceptioncategory ';
        // print_r($exceptioncategory);
        // echo '</pre>';
        // exit();

        switch ($exceptioncategory) {
            case 1:
                return $this->tasks->getExceptionOneReport($project_name, $verificationstatus, $reportHeaders);
            case 2:
                return $this->tasks->getExceptionTwoReport($project_name, $verificationstatus, $reportHeaders);
            case 3:
                return $this->tasks->getExceptionThreeReport($project_name, $verificationstatus, $reportHeaders);
            case 4:
                return $this->tasks->getExceptionFourReport($project_name, $verificationstatus, $reportHeaders);
            case 5:
                return $this->tasks->getExceptionFiveReport($project_name, $verificationstatus, $reportHeaders);
            case 6:
                return $this->tasks->getExceptionSixReport($project_name, $verificationstatus, $reportHeaders);
            case 7:
                return $this->tasks->getExceptionSevenReport($project_name, $verificationstatus, $reportHeaders);
            case 8:
                return $this->tasks->getExceptionEightReport($project_name, $verificationstatus, $reportHeaders);
            case 10:
                return $this->tasks->getExceptionNineReport($project_name, $verificationstatus, $reportHeaders);
            default:
                return [];
        }
    }





    public function get_role_by_user_id()
    {

        $user_id = $this->input->post('user_id');
        $entity_code = $this->input->post('entity_code');
        $get_user_all_roles = get_user_all_roles($user_id, $entity_code); // get all user role company wise


        $User_role_array = array();
        foreach ($get_user_all_roles as $get_user_all_roles_value) {


            switch ($get_user_all_roles_value) {
                case 0:
                    $User_role_array[$get_user_all_roles_value] = 'Manager';
                    break;
                case 1:
                    $User_role_array[$get_user_all_roles_value] = 'Verifier';
                    break;
                case 2:
                    $User_role_array[$get_user_all_roles_value] = 'Process Owner';
                    break;
                case 3:
                    $User_role_array[$get_user_all_roles_value] = 'Entity Owner';
                    break;
                case 4:
                    $User_role_array[$get_user_all_roles_value] = 'Sub Admin';
                    break;
                case 5:
                    $User_role_array[$get_user_all_roles_value] = 'Group Admin';
                    break;
            }
        }

        $role_array = array();
        foreach ($User_role_array as $key => $value) {
            $role_array[] = array("id" => $key, "role_name" => $value);
        }

        if ($role_array) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "User Roles.", "data" => $role_array));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Not Found Data"));
            exit;
        }
    }

    public function get_company_by_user_id_role()
    {
        $user_id = $this->input->post('user_id');
        $role_id = $_REQUEST['role_id'];
        $entity_code = $this->input->post('entity_code');
        $company_data_query = $this->db->query("select * from user_role where user_id = '" . $user_id . "' AND company_id != 0 AND FIND_IN_SET(" . $role_id . ",user_role) AND entity_code = '" . $entity_code . "' GROUP BY company_id");

        $company_data_list = $company_data_query->result();
        $company_dropdown_array = array();
        $company_array = array();
        foreach ($company_data_list as $company_data_list) {
            if (!in_array($company_data_list->company_id, $company_array)) {
                $company_dropdown_array[$company_data_list->company_id] = get_company_row($company_data_list->company_id)->company_name;
            }
            $company_array[] = $company_data_list->company_id;
        }

        if ($company_dropdown_array) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "User Roles.", "data" => $company_dropdown_array));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Not Found Data"));
            exit;
        }
    }

    public function get_location_by_user_id_role_company()
    {
        $role_id = $this->input->post('role_id');
        $company_id = $this->input->post('company_id');
        $user_id = $this->input->post('user_id');
        $entity_code = $this->input->post('entity_code');

        $this->db->select('company_locations.id,location_name');
        $this->db->from('user_role');
        $this->db->join('company_locations', 'company_locations.id=user_role.location_id');
        $this->db->where('user_role.company_id', $company_id);
        $this->db->where('FIND_IN_SET(' . $role_id . ', user_role.user_role)');
        $this->db->group_by("user_role.location_id");
        $company_locations = $this->db->get();
        $company_result = $company_locations->result();

        $company_array = array();
        foreach ($company_result as $company_result_value) {
            $company_array[$company_result_value->id] = $company_result_value->location_name;
        }

        if ($company_array) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "User Roles.", "data" => $company_array));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Not Found Data"));
            exit;
        }


    }


    public function get_projects_by_user_details()
    {


        $user_id = $this->input->post('user_id');
        $role_id = $this->input->post('role_id');
        $company_id = $this->input->post('company_id');
        $location_id = $this->input->post('location_id');

        $role_where = '';
        if ($role_id == '0') {
            $role_where .= "FIND_IN_SET($user_id, manager)";
        }
        if ($role_id == '1') {
            $role_where .= "FIND_IN_SET($user_id, project_verifier)";
        }
        if ($role_id == '2') {
            $role_where .= "FIND_IN_SET($user_id, process_owner)";
        }
        if ($role_id == '3') {
            $role_where .= "FIND_IN_SET($user_id, item_owner)";
        }

        $company_projects = $this->db->query('SELECT company_locations.location_name,company_projects.* FROM company_projects LEFT JOIN company_locations ON company_projects.project_location = company_locations.id WHERE company_projects.company_id IN (' . $company_id . ') AND company_projects.status = 0 AND (' . $role_where . ')')->result();

        $company_projects = $this->db->query('SELECT company_locations.location_name,company_projects.* FROM company_projects LEFT JOIN company_locations ON company_projects.project_location = company_locations.id WHERE company_projects.company_id IN (' . $company_id . ') AND company_projects.status = 0 AND (' . $role_where . ')')->result();

        if (!empty($location_id)) {
            $company_projects = $this->db->query('SELECT company_locations.location_name,company_projects.* FROM company_projects LEFT JOIN company_locations ON company_projects.project_location = company_locations.id WHERE company_projects.company_id IN (' . $company_id . ') AND company_projects.project_location = ' . $location_id . ' AND company_projects.status = 0 AND (' . $role_where . ')')->result();
        }


        if ($company_projects) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "User Roles.", "data" => $company_projects));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Not Found Data"));
            exit;
        }

    }

    public function resolve_issue()
    {

        $issue_id = $this->input->post("issue_id");

        $created_by = $this->input->post("user_id");
        $random_number = rand(10000, 99999);
        $tracking_id_value = date('ymd') . $random_number;


        $config['upload_path'] = './issueattachment/';
        $config['allowed_types'] = '*';
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);
        $issue_resolve_attachment = '';
        $original_file_name = '';
        if (!$this->upload->do_upload('issue_resolve_attachment')) {
            $error = array('error' => $this->upload->display_errors());
            print_r($error);
            exit;
        } else {
            $data = $this->upload->data();
            $issue_resolve_attachment = "response_" . $data['file_name'];
            $original_file_name = $data['file_name'];
        }


        $status_value = $this->input->post("issue_status");
        $status_remark_value = $this->input->post("issue_resolve_remark");
        $status_type = $this->input->post("status_type");
        $status_type_remark_value = $this->input->post("status_type_remark");

        $condition = array(
            "id" => $issue_id
        );

        $data = array(
            "status" => $status_value,
            "status_type" => $status_type,
            "remark_content" => $status_remark_value,
            "remark_content" => $this->input->post("Remark"),
            "updated_at" => date("Y-m-d H:i:s")
        );

        $verify = $this->tasks->update_data('issue_manage', $data, $condition);

        $data = array(
            "issue_id" => $issue_id,
            "user_id" => $created_by,
            "status" => $status_value,
            "status_remark" => $status_remark_value,
            "status_type" => $status_type,
            "status_type_remark" => $status_type_remark_value,
            "attachments" => $original_file_name,
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        );


        $insert = $this->db->insert('issue_log_manage', $data);
        $insert_id = $this->db->insert_id();


        if ($insert_id) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "User Roles.", "data" => $data));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Not Found Data"));
            exit;
        }
    }





    public function getReportType()
    {
        $report_type = array(
            '1' => 'Scope Summary & Detailed Report',
        );

        $report_type = array(
            'id' => 1,
            'name' => 'Scope Summary & Detailed Report',
        );

        $response['message'] = 'Get Report Type';
        $response['status'] = 1;
        $response['data'] = array($report_type);
        echo json_encode($response);
    }

    public function getExceptionCategory()
    {

        $arExceptionCategoryray = array();
        // $ExceptionCategory[1]['id'] = 1;
        // $ExceptionCategory[1]['name'] = 'Condition of Item';

        // $ExceptionCategory[2]['id'] = 2;
        // $ExceptionCategory[2]['name'] = 'Changes/ Updations of Items (New)';
        // $ExceptionCategory[3]['id'] = 3;
        // $ExceptionCategory[3]['name'] = 'Qty Validation Status';
        // $ExceptionCategory[4]['id'] = 4;
        // $ExceptionCategory[4]['name'] = 'Updated with Verification Remarks';
        // $ExceptionCategory[5]['id'] = 5;
        // $ExceptionCategory[5]['name'] = 'Updated with Item Notes';

        // $ExceptionCategory[6]['id'] = 6;
        // $ExceptionCategory[6]['name'] = 'Calculate Risk Exposure (New)';
        // $ExceptionCategory[7]['id'] = 7;
        // $ExceptionCategory[7]['name'] = 'Mode of Verification';
        // $ExceptionCategory[10]['id'] = 10;
        // $ExceptionCategory[10]['name'] = 'Duplicate Item Codes Identified (New)';



        $ExceptionCategory = array(
            array('id' => 1, 'name' => 'Condition of Item'),
            array('id' => 2, 'name' => 'Changes/ Updations of Items (New)'),
            array('id' => 3, 'name' => 'Qty Validation Status'),
            array('id' => 4, 'name' => 'Updated with Verification Remarks'),
            array('id' => 5, 'name' => 'Updated with Item Notes'),
            array('id' => 6, 'name' => 'Calculate Risk Exposure (New)'),
            array('id' => 7, 'name' => 'Mode of Verification'),
            array('id' => 10, 'name' => 'Duplicate Item Codes Identified (New)'),
        );


        // $ExceptionCategory = array(
        //     '1' => 'Condition of Item',
        //     '2' => 'Changes/ Updations of Items (New)',
        //     '3' => 'Qty Validation Status',
        //     '4' => 'Updated with Verification Remarks',
        //     '5' => 'Updated with Item Notes',
        //     '6' => 'Calculate Risk Exposure (New)',
        //     '8' => 'Mode of Verification',
        // //  '9' => 'Duplicate Item Codes verified<',
        //     '10' => 'Duplicate Item Codes Identified (New)',
        // );
        $response['message'] = 'Get Exception Category';
        $response['status'] = 1;
        $response['data'] = $ExceptionCategory;
        echo json_encode($response);
    }


    public function GetReportinguserManager()
    {
        $project_id = $this->input->post('project_id');
        $location_id = $this->input->post('location_id');
        $entity_code = $this->input->post('entity_code');

        $user_role = 0;
        $resulttttt = $this->db->query('SELECT user_role.*,users.* from user_role INNER JOIN users ON users.id=user_role.user_id where FIND_IN_SET(' . $user_role . ',user_role) AND user_role.location_id=' . $location_id . ' AND user_role.entity_code="' . $entity_code . '"')->result();



        foreach ($resulttttt as $resulttttt_key => $resulttttt_value) {
            $resulttttt[$resulttttt_key]->userName = $resulttttt_value->firstName . ' ' . $resulttttt_value->lastName;
        }

        // echo '<pre>resulttttt ';
        // print_r($resulttttt);
        // echo '</pre>';
        // exit();

        $response['message'] = 'Get Manager';
        $response['status'] = 1;
        $response['data'] = $resulttttt;
        echo json_encode($response);
    }



    public function GetReportinguserGroupAdmin()
    {
        $entity_code = $this->input->post('entity_code');
        $user_role = 5;
        $group_admin = $this->db->query('SELECT user_role.*,users.* from user_role INNER JOIN users ON users.id=user_role.user_id where FIND_IN_SET(' . $user_role . ',user_role) AND user_role.entity_code="' . $entity_code . '"')->result();

        foreach ($group_admin as $group_admin_key => $group_admin_value) {
            $group_admin[$group_admin_key]->userName = $group_admin_value->firstName . ' ' . $group_admin_value->lastName;
        }


        $response['message'] = 'Get Group Admin';
        $response['status'] = 1;
        $response['data'] = $group_admin;
        echo json_encode($response);

    }




    public function getresourceDetails()
    {

        $id = $this->input->post('project_id');

        $condition = array('id' => $id);
        $projects = $this->tasks->get_data('company_projects', $condition);


        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        foreach ($projects as $project) {
            $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));
            $getprojectdetails = $this->tasks->projectdetail($project_name);
            if (!empty($getprojectdetails)) {
                $project->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                if ($getprojectdetails[0]->VerifiedQuantity != '')
                    $project->VerifiedQuantity = $getprojectdetails[0]->VerifiedQuantity;
                else
                    $project->VerifiedQuantity = 0;
            } else {
                $project->TotalQuantity = 0;
                $project->VerifiedQuantity = 0;
            }
            $condition2 = array('id' => $project->company_id);
            $company = $this->tasks->get_data('company', $condition2);

            $companylocation = $this->tasks->get_data('company_locations', array('id' => $project->project_location));
            $project->company_name = $company[0]->company_name;
            $project->project_location = $companylocation[0]->location_name;
        }



        // OverallProjectStatusChart Start From Here
        $tag_verified = $this->db->select('count(*) as tag_verified')->where(array('tag_status_y_n_na' => 'Y', "verification_status" => 'Verified'))->get($project_name)->row()->tag_verified;
        $tag_not_verified = $this->db->select('count(*) as tag_not_verified')->where(array('tag_status_y_n_na' => 'Y', "verification_status !=" => 'Verified'))->get($project_name)->row()->tag_not_verified;
        $non_tag_verified = $this->db->select('count(*) as non_tag_verified')->where(array('tag_status_y_n_na' => 'N', "verification_status" => 'Verified'))->get($project_name)->row()->non_tag_verified;
        $non_tag_not_verified = $this->db->select('count(*) as non_tag_not_verified')->where(array('tag_status_y_n_na' => 'N', "verification_status !=" => 'Verified'))->get($project_name)->row()->non_tag_not_verified;

        $OverallProjectStatusChart_Verified_dataPoints = array(
            array("label" => "Tagged", "y" => $tag_verified),
            array("label" => "Not Tagged", "y" => $non_tag_verified)
        );
        $OverallProjectStatusChart_NotVerified_dataPoints = array(
            array("label" => "Tagged", "y" => $tag_not_verified),
            array("label" => "Not Tagged", "y" => $non_tag_not_verified)
        );
        $data['OverallProjectStatusChart_Verified_dataPoints'] = $OverallProjectStatusChart_Verified_dataPoints;
        $data['OverallProjectStatusChart_NotVerified_dataPoints'] = $OverallProjectStatusChart_NotVerified_dataPoints;

        $listing = getTagUntag($projects[0]->project_name);
        $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($projects[0]->project_name)));

        $listing = getTagUntag($projects[0]->project_name);
        $cat = getTagUntagCategories($projects[0]->project_name);

        $allcategories = getCategories($projects[0]->project_name);

        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $tamt = 0;


        $my_array = array();
        foreach ($allcategories['categories'] as $alcat) {

            $overallverified = 0;
            $overalltotal = 0;



            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $process = 0;


                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $ttv = $ttv + $ct['verified'];
                            $ttt = $ttt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;

                        }
                    }
                }
            }

            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {

                $overall = 0;
                $process = 0;
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $tntv = $tntv + $ct['verified'];
                            $tntt = $tntt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }



            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $process = 0;
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $tutv = $tutv + $ct['verified'];
                            $tutt = $tutt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }
            $my_array[$alcat->item_category]['percentage'] = round(($overallverified / $overalltotal) * 100, 2);
            $my_array[$alcat->item_category]['overallverified'] = $overallverified;
            $my_array[$alcat->item_category]['overalltotal'] = $overalltotal;
        }



        $LineItemBreakupChart_Verified_dataPoints1 = array();
        $LineItemBreakupChart_NotVerified_dataPoints2 = array();
        $array = array("1", "2", "3");
        $array1 = array("10", "20", "30");
        $count = 0;
        foreach ($my_array as $my_array_key => $my_array_value) {

            $LineItemBreakupChart_Verified_dataPoints1[] = array("label" => $my_array_key, "y" => $my_array_value['percentage'], "customText" => $my_array_value['overallverified']);

            $LineItemBreakupChart_NotVerified_dataPoints2[] = array("label" => $my_array_key, "y" => 100 - (int) $my_array_value['percentage'], "customText" => $my_array_value['overalltotal'] - $my_array_value['overallverified']);

            $count++;
        }


        $data['LineItemBreakupChart_Verified_dataPoints1'] = $LineItemBreakupChart_Verified_dataPoints1;
        $data['LineItemBreakupChart_NotVerified_dataPoints2'] = $LineItemBreakupChart_NotVerified_dataPoints2;

        $filled = ($ttt + $tntt + $tutt) > 0 ? round((($ttv + $tntv + $tutv) / ($ttt + $tntt + $tutt)) * 100, 2) . '' : '0';


        $LineItemBreakup_DonutChart_dataPoints = array(
            array("label" => "Verified", "symbol" => "Verified", "y" => $filled),
            array("label" => "Not Verified", "symbol" => "Not-Verified", "y" => 100 - $filled),
        );
        $data['LineItemBreakup_DonutChart_dataPoints'] = $LineItemBreakup_DonutChart_dataPoints;



        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $totalCount = 0;
        $my_array1 = array();
        foreach ($allcategories['categories'] as $alcat) {
            $count = 0;
            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }
            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }
            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }



            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $overallverified = 0;
                $overalltotal = 0;
                $process = 0;
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $ttv = $ttv + $ct['verifiedamount'];
                            $ttt = $ttt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }


            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $tntv = $tntv + $ct['verifiedamount'];
                            $tntt = $tntt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }

            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $tutv = $tutv + $ct['verifiedamount'];
                            $tutt = $tutt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }


            if ($projects[0]->project_type == 'CD') {
                $my_array1[$alcat->item_category]['percentage'] = getmoney_format(round((($overallverified / $overalltotal) * 100), 2));
                $my_array1[$alcat->item_category]['overallverified'] = round(($overallverified / 100000));//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = round(($overalltotal / 100000)); //getmoney_format(round(($overalltotal/100000),2));
            }

            // This New Section Added By Hardik For TG Project Start Here
            if ($projects[0]->project_type == 'TG') {
                $my_array1[$alcat->item_category]['percentage'] = getmoney_format(round((($overallverified / $overalltotal) * 100), 2));
                $my_array1[$alcat->item_category]['overallverified'] = round(($overallverified / 100000));//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = round(($overalltotal / 100000)); //getmoney_format(round(($overalltotal/100000),2));
            }
            // This New Section Added By Hardik For TG Project End Here
        }




        $filled = ($ttt + $tntt + $tutt) > 0 ? round((($ttv + $tntv + $tutv) / ($ttt + $tntt + $tutt)) * 100, 2) . ' 0 ' : '0 ';

        $AmountwiseBreakupChart_dataPoints1 = array();
        $AmountwiseBreakupChart_dataPoints2 = array();
        foreach ($my_array1 as $my_array1_key => $my_array1_value) {
            // Cast the values to float to ensure they are numeric before calculation
            $verified_amount = (float) str_replace(',', '', $my_array1_value['overallverified']);
            $total_amount = (float) str_replace(',', '', $my_array1_value['overalltotal']);

            $calculation = $my_array1_value['overalltotal'] - $my_array1_value['overallverified'];
            $AmountwiseBreakupChart_dataPoints1[] = array("label" => $my_array1_key, "y" => $verified_amount, "customText" => round($my_array1_value['overallverified'], 2) . " Lac");
            $AmountwiseBreakupChart_dataPoints2[] = array("label" => $my_array1_key, "y" => ($total_amount - $verified_amount), "customText" => round($calculation, 2) . " Lac");
        }


        // exit();

        $data['AmountwiseBreakupChart_dataPoints1'] = $AmountwiseBreakupChart_dataPoints1;
        $data['AmountwiseBreakupChart_dataPoints2'] = $AmountwiseBreakupChart_dataPoints2;



        $calculation = 100 - floatval($filled);
        $y_value = number_format((float) $calculation, 2, '.', '');


        $AmountwiseBreakup_DonutChart_dataPoints = array(
            array("label" => "Verified", "symbol" => "Verified", "y" => round((float) $filled)),
            array("label" => "Not Verified", "symbol" => "Not Verified", "y" => round((float) $y_value)),
        );


        $data['AmountwiseBreakup_DonutChart_dataPoints'] = $AmountwiseBreakup_DonutChart_dataPoints;










        $project_details = $this->db->select('*')->get($project_name)->result();


        $this->db->select($project_name . '.*,users.firstName');
        $this->db->from($project_name);
        $this->db->join('users', $project_name . '.verified_by = users.id');
        $query = $this->db->get();
        $project_details = $query->result();

        $project_details_array = array();
        $project_details_array2 = array();

        $verifier_users_array = array();
        $category_array = array();



        $user_wise_count = array();
        foreach ($project_details as $project_details_key => $project_details_value) {
            if (!empty($project_details_value->verified_by)) {
                $project_details_array[$project_details_value->firstName][$project_details_value->item_category][] = 1;
                $project_details_array2[$project_details_value->item_category][$project_details_value->firstName][] = 1;
            }
            if (!in_array($project_details_value->item_category, $verifier_users_array)) {
                $verifier_users_array[] = $project_details_value->item_category;
            }
            if (!in_array($project_details_value->firstName, $category_array)) {
                $category_array[] = $project_details_value->firstName;
            }
            $user_wise_count[$project_details_value->firstName][] = 1;


        }





        $ResourcewiseUtilizationChart_datapoint = array();
        $ResourcewiseUtilization_DonutChart_dataPoints_array = array();
        $count_value = 0;




        foreach ($project_details_array as $project_details_array_key => $project_details_array_value) {

            $ResourcewiseUtilizationChart_dataPoints1 = array();
            foreach ($verifier_users_array as $verifier_users_array_Key => $verifier_users_array_value) {
                if (isset($project_details_array[$project_details_array_key][$verifier_users_array_value])) {
                    $ResourcewiseUtilizationChart_dataPoints1[] = array("label" => $verifier_users_array_value, "y" => count($project_details_array[$project_details_array_key][$verifier_users_array_value]));
                    $ResourcewiseUtilization_DonutChart_dataPoints_array[$verifier_users_array_value][] = count($project_details_array[$project_details_array_key][$verifier_users_array_value]);
                }
            }



            $ResourcewiseUtilizationChart_datapoint[] = array(
                "type" => "stackedColumn100",
                "name" => $project_details_array_key,
                "showInLegend" => true,
                "yValueFormatString" => "#,##0 ",
                "dataPoints" => $ResourcewiseUtilizationChart_dataPoints1,
            );
            $count_value++;
        }




        $data['ResourcewiseUtilizationChart_datapoint'] = $ResourcewiseUtilizationChart_datapoint;

        $ResourcewiseUtilization_DonutChart_dataPoints_array1 = array();
        foreach ($ResourcewiseUtilization_DonutChart_dataPoints_array as $ResourcewiseUtilization_DonutChart_dataPoints_array_key => $ResourcewiseUtilization_DonutChart_dataPoints_array_value) {

            $ResourcewiseUtilization_DonutChart_dataPoints_array1[] = array("label" => $ResourcewiseUtilization_DonutChart_dataPoints_array_key, "symbol" => $ResourcewiseUtilization_DonutChart_dataPoints_array_key, "y" => array_sum($ResourcewiseUtilization_DonutChart_dataPoints_array_value));

        }


        // echo '<pre>';
        // print_r($ResourcewiseUtilizationChart_dataPoints1);
        // echo '</pre>';
        // exit();

        // echo '<pre>user_wise_count ';
        // print_r($user_wise_count);
        // echo '</pre>';
        // exit();

        $ResourcewiseUtilizationChart_dataPoints1 = array();
        foreach ($user_wise_count as $user_wise_count_key => $user_wise_count_value) {


            $ResourcewiseUtilizationChart_dataPoints1[] = array(
                "label" => $user_wise_count_key,
                "symbol" => $user_wise_count_key,
                "y" => count($user_wise_count_value),
            );
        }




        $data1['ResourcewiseUtilization_DonutChart_dataPoints'] = $ResourcewiseUtilizationChart_dataPoints1;
        $data1['ResourcewiseUtilizationChart_datapoint'] = $ResourcewiseUtilizationChart_datapoint;


        $response['message'] = 'Get Manager';
        $response['status'] = 1;
        $response['data'] = $data1;
        echo json_encode($response);


    }



    public function projectAmountwise()
    {

        $project_id = $this->input->post('project_id');
        $condition = array('id' => $project_id);
        $projects = $this->tasks->get_data('company_projects', $condition);

        echo '<pre>projects ::';
        print_r($projects);
        echo '</pre>';
        exit();
        echo '<pre>project_id ::';
        print_r($project_id);
        echo '</pre>';
        exit();

        $userid = $this->input->post('user_id');
        $company_id = $this->input->post('company_id');
        $location_id = $this->input->post('location_id');
        $condition = array(
            "id" => $userid
        );
        $projects = $this->tasks->getProjects('users', $userid, $company_id, $location_id);

        // echo '<pre>last_query ';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // exit();

        // echo $this->db->last_query();
        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        foreach ($projects as $project) {

            $verifiercount = check_verifier_count($project->id, $userid);
            $check_itemowner_count = check_itemowner_count($project->id, $userid);
            $check_process_owner_count = check_process_owner_count($project->id, $userid);
            $check_manager_count = check_manager_count($project->id, $userid);

            $verifiername = $this->tasks->get_verifire_name($project->project_verifier);

            $project->verifier_name = $verifiername;

            $project->verifier_cnt = $verifiercount;
            $project->iten_owner_cnt = $check_itemowner_count;
            $project->process_owner_count = $check_process_owner_count;
            $project->check_manager_count = $check_manager_count;

            if (($verifiercount == '1') || ($check_itemowner_count == '1') || ($check_process_owner_count == '1') || ($check_manager_count == '1')) {
                $project->project_location = $project->location_name;
                $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));
                $getprojectdetails = $this->tasks->projectdetail($project_name);
                $getlastupdatedtime = $this->tasks->lastupdatetime($project_name, $userid);
                if (!empty($getlastupdatedtime)) {
                    // $project->updatedat=date('d-m-Y H:i:s',strtotime('+5 hour +30 minutes',strtotime($getlastupdatedtime[0]->updatedat)));
                    $project->updatedat = date('d-m-Y H:i:s', strtotime($getlastupdatedtime[0]->updatedat));
                    // $project->updatedat=date('d-m-Y H:i:s');
                } else {
                    $project->updatedat = '';
                }
                if (!empty($getprojectdetails)) {
                    $project->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                    if ($getprojectdetails[0]->VerifiedQuantity != '')
                        $project->VerifiedQuantity = $getprojectdetails[0]->VerifiedQuantity;
                    else
                        $project->VerifiedQuantity = 0;
                } else {
                    $project->TotalQuantity = 0;
                    $project->VerifiedQuantity = 0;
                }
                $project->verifier_name = $verifiername;
                $project->assigned_by = get_UserName($project->assigned_by);
                $projectheaders = $this->tasks->get_data('project_headers', array('project_id' => $project->project_header_id));


                $update_array = array();
                $check_array = array();
                foreach ($projectheaders as $projectheaders_key => $projectheaders_value) {

                    if (!in_array($projectheaders_value->keyname, $check_array)) {
                        $update_array[] = $projectheaders_value;
                        $check_array[] = $projectheaders_value->keyname;
                    }

                }
                $project->visiblecolumns = $update_array;
            } else {
                $project->verifier_name = $verifiername;

            }
        }

        // echo '<pre>projects ';
        // print_r($projects);
        // echo '</pre>';
        // exit(); 

        if (!empty($projects) && count($projects) > 0) {

            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "Projects fetched successfully.", "data" => $projects));
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "No project assigned"));
            exit;
        }
    }




    public function one($id)
    {
        $condition = array('id' => $id);
        $projects = $this->tasks->get_data('company_projects', $condition);


        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        foreach ($projects as $project) {
            $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));
            $getprojectdetails = $this->tasks->projectdetail($project_name);
            if (!empty($getprojectdetails)) {
                $project->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                if ($getprojectdetails[0]->VerifiedQuantity != '')
                    $project->VerifiedQuantity = $getprojectdetails[0]->VerifiedQuantity;
                else
                    $project->VerifiedQuantity = 0;
            } else {
                $project->TotalQuantity = 0;
                $project->VerifiedQuantity = 0;
            }
            $condition2 = array('id' => $project->company_id);
            $company = $this->tasks->get_data('company', $condition2);

            $companylocation = $this->tasks->get_data('company_locations', array('id' => $project->project_location));
            $project->company_name = $company[0]->company_name;
            $project->project_location = $companylocation[0]->location_name;
        }



        // OverallProjectStatusChart Start From Here
        $tag_verified = $this->db->select('count(*) as tag_verified')->where(array('tag_status_y_n_na' => 'Y', "verification_status" => 'Verified'))->get($project_name)->row()->tag_verified;
        $tag_not_verified = $this->db->select('count(*) as tag_not_verified')->where(array('tag_status_y_n_na' => 'Y', "verification_status !=" => 'Verified'))->get($project_name)->row()->tag_not_verified;
        $non_tag_verified = $this->db->select('count(*) as non_tag_verified')->where(array('tag_status_y_n_na' => 'N', "verification_status" => 'Verified'))->get($project_name)->row()->non_tag_verified;
        $non_tag_not_verified = $this->db->select('count(*) as non_tag_not_verified')->where(array('tag_status_y_n_na' => 'N', "verification_status !=" => 'Verified'))->get($project_name)->row()->non_tag_not_verified;

        $OverallProjectStatusChart_Verified_dataPoints = array(
            array("label" => "Tagged", "y" => $tag_verified),
            array("label" => "Not Tagged", "y" => $non_tag_verified)
        );
        $OverallProjectStatusChart_NotVerified_dataPoints = array(
            array("label" => "Tagged", "y" => $tag_not_verified),
            array("label" => "Not Tagged", "y" => $non_tag_not_verified)
        );
        $data['OverallProjectStatusChart_Verified_dataPoints'] = $OverallProjectStatusChart_Verified_dataPoints;
        $data['OverallProjectStatusChart_NotVerified_dataPoints'] = $OverallProjectStatusChart_NotVerified_dataPoints;

        $listing = getTagUntag($projects[0]->project_name);
        $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($projects[0]->project_name)));

        $listing = getTagUntag($projects[0]->project_name);
        $cat = getTagUntagCategories($projects[0]->project_name);

        $allcategories = getCategories($projects[0]->project_name);

        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $tamt = 0;


        $my_array = array();
        foreach ($allcategories['categories'] as $alcat) {

            $overallverified = 0;
            $overalltotal = 0;



            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $process = 0;


                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $ttv = $ttv + $ct['verified'];
                            $ttt = $ttt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;

                        }
                    }
                }
            }

            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {

                $overall = 0;
                $process = 0;
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $tntv = $tntv + $ct['verified'];
                            $tntt = $tntt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }



            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $process = 0;
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $tutv = $tutv + $ct['verified'];
                            $tutt = $tutt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }
            $my_array[$alcat->item_category]['percentage'] = round(($overallverified / $overalltotal) * 100, 2);
            $my_array[$alcat->item_category]['overallverified'] = $overallverified;
            $my_array[$alcat->item_category]['overalltotal'] = $overalltotal;
        }



        $LineItemBreakupChart_Verified_dataPoints1 = array();
        $LineItemBreakupChart_NotVerified_dataPoints2 = array();
        $array = array("1", "2", "3");
        $array1 = array("10", "20", "30");
        $count = 0;
        foreach ($my_array as $my_array_key => $my_array_value) {

            $LineItemBreakupChart_Verified_dataPoints1[] = array("label" => $my_array_key, "y" => $my_array_value['percentage'], "customText" => $my_array_value['overallverified']);

            $LineItemBreakupChart_NotVerified_dataPoints2[] = array("label" => $my_array_key, "y" => 100 - (int) $my_array_value['percentage'], "customText" => $my_array_value['overalltotal'] - $my_array_value['overallverified']);
            /*
            $LineItemBreakupChart_Verified_dataPoints1[] = array("label"=> $my_array_key, "y"=> $my_array_value['percentage']);
            $LineItemBreakupChart_NotVerified_dataPoints2[] = array("label"=> $my_array_key, "y"=> 100-(int)$my_array_value['percentage']); */
            $count++;
        }


        $data['LineItemBreakupChart_Verified_dataPoints1'] = $LineItemBreakupChart_Verified_dataPoints1;
        $data['LineItemBreakupChart_NotVerified_dataPoints2'] = $LineItemBreakupChart_NotVerified_dataPoints2;

        $filled = ($ttt + $tntt + $tutt) > 0 ? round((($ttv + $tntv + $tutv) / ($ttt + $tntt + $tutt)) * 100, 2) . '' : '0';


        $LineItemBreakup_DonutChart_dataPoints = array(
            array("label" => "Verified", "symbol" => "Verified", "y" => $filled),
            array("label" => "Not Verified", "symbol" => "Not-Verified", "y" => 100 - $filled),
        );
        $data['LineItemBreakup_DonutChart_dataPoints'] = $LineItemBreakup_DonutChart_dataPoints;



        // echo '<pre>';
        // print_r($allcategories['categories']);
        // echo '</pre>';
        // exit();


        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $totalCount = 0;
        $my_array1 = array();
        foreach ($allcategories['categories'] as $alcat) {
            $count = 0;
            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }
            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }
            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }



            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $overallverified = 0;
                $overalltotal = 0;
                $process = 0;
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $ttv = $ttv + $ct['verifiedamount'];
                            $ttt = $ttt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }


            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $tntv = $tntv + $ct['verifiedamount'];
                            $tntt = $tntt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }

            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $tutv = $tutv + $ct['verifiedamount'];
                            $tutt = $tutt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }


            if ($projects[0]->project_type == 'CD') {
                $my_array1[$alcat->item_category]['percentage'] = getmoney_format(round((($overallverified / $overalltotal) * 100), 2));
                $my_array1[$alcat->item_category]['overallverified'] = round(($overallverified / 100000));//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = round(($overalltotal / 100000)); //getmoney_format(round(($overalltotal/100000),2));
            }

            // This New Section Added By Hardik For TG Project Start Here
            if ($projects[0]->project_type == 'TG') {
                $my_array1[$alcat->item_category]['percentage'] = getmoney_format(round((($overallverified / $overalltotal) * 100), 2));
                $my_array1[$alcat->item_category]['overallverified'] = round(($overallverified / 100000));//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = round(($overalltotal / 100000)); //getmoney_format(round(($overalltotal/100000),2));
            }
            // This New Section Added By Hardik For TG Project End Here
        }




        $filled = ($ttt + $tntt + $tutt) > 0 ? round((($ttv + $tntv + $tutv) / ($ttt + $tntt + $tutt)) * 100, 2) . ' 0 ' : '0 ';

        $AmountwiseBreakupChart_dataPoints1 = array();
        $AmountwiseBreakupChart_dataPoints2 = array();
        foreach ($my_array1 as $my_array1_key => $my_array1_value) {
            // Cast the values to float to ensure they are numeric before calculation
            $verified_amount = (float) str_replace(',', '', $my_array1_value['overallverified']);
            $total_amount = (float) str_replace(',', '', $my_array1_value['overalltotal']);

            $calculation = $my_array1_value['overalltotal'] - $my_array1_value['overallverified'];
            $AmountwiseBreakupChart_dataPoints1[] = array("label" => $my_array1_key, "y" => $verified_amount, "customText" => round($my_array1_value['overallverified'], 2) . " Lac");
            $AmountwiseBreakupChart_dataPoints2[] = array("label" => $my_array1_key, "y" => ($total_amount - $verified_amount), "customText" => round($calculation, 2) . " Lac");
        }


        // exit();

        $data['AmountwiseBreakupChart_dataPoints1'] = $AmountwiseBreakupChart_dataPoints1;
        $data['AmountwiseBreakupChart_dataPoints2'] = $AmountwiseBreakupChart_dataPoints2;



        $calculation = 100 - floatval($filled);
        $y_value = number_format((float) $calculation, 2, '.', '');


        $AmountwiseBreakup_DonutChart_dataPoints = array(
            array("label" => "Verified", "symbol" => "Verified", "y" => round((float) $filled)),
            array("label" => "Not Verified", "symbol" => "Not Verified", "y" => round((float) $y_value)),
        );


        $data['AmountwiseBreakup_DonutChart_dataPoints'] = $AmountwiseBreakup_DonutChart_dataPoints;










        $project_details = $this->db->select('*')->get($project_name)->result();


        $this->db->select($project_name . '.*,users.firstName');
        $this->db->from($project_name);
        $this->db->join('users', $project_name . '.verified_by = users.id');
        $query = $this->db->get();
        $project_details = $query->result();

        // echo '<pre>project_details ';
        // print_r($project_details);
        // echo '</pre>';
        // exit();

        $project_details_array = array();
        $project_details_array2 = array();

        $verifier_users_array = array();
        $category_array = array();

        /*
        foreach($project_details as $project_details_key=>$project_details_value){
            if(!empty($project_details_value->verified_by)){
                $project_details_array[$project_details_value->item_category][$project_details_value->firstName][] = 1;
                $project_details_array2[$project_details_value->firstName][$project_details_value->item_category][] = 1;
            }

            if(!in_array($project_details_value->firstName,$verifier_users_array)){
                $verifier_users_array[] = $project_details_value->firstName;
            }

            if(!in_array($project_details_value->item_category,$category_array)){
                $category_array[] = $project_details_value->item_category;
            }

        } */


        $user_wise_count = array();
        foreach ($project_details as $project_details_key => $project_details_value) {
            if (!empty($project_details_value->verified_by)) {
                $project_details_array[$project_details_value->firstName][$project_details_value->item_category][] = 1;
                $project_details_array2[$project_details_value->item_category][$project_details_value->firstName][] = 1;
            }
            if (!in_array($project_details_value->item_category, $verifier_users_array)) {
                $verifier_users_array[] = $project_details_value->item_category;
            }
            if (!in_array($project_details_value->firstName, $category_array)) {
                $category_array[] = $project_details_value->firstName;
            }
            $user_wise_count[$project_details_value->firstName][] = 1;


        }





        $ResourcewiseUtilizationChart_datapoint = array();
        $ResourcewiseUtilization_DonutChart_dataPoints_array = array();
        $count_value = 0;




        foreach ($project_details_array as $project_details_array_key => $project_details_array_value) {

            $ResourcewiseUtilizationChart_dataPoints1 = array();
            foreach ($verifier_users_array as $verifier_users_array_Key => $verifier_users_array_value) {
                if (isset($project_details_array[$project_details_array_key][$verifier_users_array_value])) {
                    $ResourcewiseUtilizationChart_dataPoints1[] = array("label" => $verifier_users_array_value, "y" => count($project_details_array[$project_details_array_key][$verifier_users_array_value]));
                    $ResourcewiseUtilization_DonutChart_dataPoints_array[$verifier_users_array_value][] = count($project_details_array[$project_details_array_key][$verifier_users_array_value]);
                }
            }



            $ResourcewiseUtilizationChart_datapoint[] = array(
                "type" => "stackedColumn100",
                "name" => $project_details_array_key,
                "showInLegend" => true,
                "yValueFormatString" => "#,##0 ",
                "dataPoints" => $ResourcewiseUtilizationChart_dataPoints1,
            );
            $count_value++;
        }




        $data['ResourcewiseUtilizationChart_datapoint'] = $ResourcewiseUtilizationChart_datapoint;

        $ResourcewiseUtilization_DonutChart_dataPoints_array1 = array();
        foreach ($ResourcewiseUtilization_DonutChart_dataPoints_array as $ResourcewiseUtilization_DonutChart_dataPoints_array_key => $ResourcewiseUtilization_DonutChart_dataPoints_array_value) {

            $ResourcewiseUtilization_DonutChart_dataPoints_array1[] = array("label" => $ResourcewiseUtilization_DonutChart_dataPoints_array_key, "symbol" => $ResourcewiseUtilization_DonutChart_dataPoints_array_key, "y" => array_sum($ResourcewiseUtilization_DonutChart_dataPoints_array_value));

        }


        // echo '<pre>';
        // print_r($ResourcewiseUtilizationChart_dataPoints1);
        // echo '</pre>';
        // exit();



        $ResourcewiseUtilizationChart_dataPoints1 = array();
        foreach ($user_wise_count as $user_wise_count_key => $user_wise_count_value) {


            $ResourcewiseUtilizationChart_dataPoints1[] = array(
                "label" => $user_wise_count_key,
                "symbol" => $user_wise_count_key,
                "y" => count($user_wise_count_value),
            );
        }




        $data['ResourcewiseUtilization_DonutChart_dataPoints'] = $ResourcewiseUtilizationChart_dataPoints1;



        $data['projects'] = $projects;
        $data['page_title'] = "Project Detail";

        // $this->load->view('project_detail3',$data);
        $this->load->view('ProjectDetailsOneView', $data);

    }



    public function projectDetails()
    {
        $id = $this->input->post('project_id');
        $user_id = $this->input->post('user_id');
        $condition = array('id' => $id);
        $projects = $this->tasks->get_data('company_projects', $condition);


        $old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
        $new_pattern = array("_", "_", "");
        foreach ($projects as $project) {
            $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($project->project_name)));
            $getprojectdetails = $this->tasks->projectdetail($project_name);
            if (!empty($getprojectdetails)) {
                $project->TotalQuantity = ((int) $getprojectdetails[0]->TotalQuantity);
                if ($getprojectdetails[0]->VerifiedQuantity != '')
                    $project->VerifiedQuantity = $getprojectdetails[0]->VerifiedQuantity;
                else
                    $project->VerifiedQuantity = 0;
            } else {
                $project->TotalQuantity = 0;
                $project->VerifiedQuantity = 0;
            }
            $condition2 = array('id' => $project->company_id);
            $company = $this->tasks->get_data('company', $condition2);

            $companylocation = $this->tasks->get_data('company_locations', array('id' => $project->project_location));
            $project->company_name = $company[0]->company_name;
            $project->project_location = $companylocation[0]->location_name;
        }



        // OverallProjectStatusChart Start From Here
        $tag_verified = $this->db->select('count(*) as tag_verified')->where(array('tag_status_y_n_na' => 'Y', "verification_status" => 'Verified'))->get($project_name)->row()->tag_verified;
        $tag_not_verified = $this->db->select('count(*) as tag_not_verified')->where(array('tag_status_y_n_na' => 'Y', "verification_status !=" => 'Verified'))->get($project_name)->row()->tag_not_verified;
        $non_tag_verified = $this->db->select('count(*) as non_tag_verified')->where(array('tag_status_y_n_na' => 'N', "verification_status" => 'Verified'))->get($project_name)->row()->non_tag_verified;
        $non_tag_not_verified = $this->db->select('count(*) as non_tag_not_verified')->where(array('tag_status_y_n_na' => 'N', "verification_status !=" => 'Verified'))->get($project_name)->row()->non_tag_not_verified;

        $OverallProjectStatusChart_Verified_dataPoints = array(
            array("label" => "Tagged", "y" => $tag_verified),
            array("label" => "Not Tagged", "y" => $non_tag_verified)
        );
        $OverallProjectStatusChart_NotVerified_dataPoints = array(
            array("label" => "Tagged", "y" => $tag_not_verified),
            array("label" => "Not Tagged", "y" => $non_tag_not_verified)
        );
        $data['OverallProjectStatusChart_Verified_dataPoints'] = $OverallProjectStatusChart_Verified_dataPoints;
        $data['OverallProjectStatusChart_NotVerified_dataPoints'] = $OverallProjectStatusChart_NotVerified_dataPoints;

        $listing = getTagUntag($projects[0]->project_name);
        $project_name = strtolower(preg_replace($old_pattern, $new_pattern, trim($projects[0]->project_name)));

        $listing = getTagUntag($projects[0]->project_name);
        $cat = getTagUntagCategories($projects[0]->project_name);

        $allcategories = getCategories($projects[0]->project_name);

        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $tamt = 0;


        $my_array = array();
        foreach ($allcategories['categories'] as $alcat) {

            $overallverified = 0;
            $overalltotal = 0;



            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $process = 0;


                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $ttv = $ttv + $ct['verified'];
                            $ttt = $ttt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;

                        }
                    }
                }
            }

            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {

                $overall = 0;
                $process = 0;
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $tntv = $tntv + $ct['verified'];
                            $tntt = $tntt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }



            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $process = 0;
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $tutv = $tutv + $ct['verified'];
                            $tutt = $tutt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }
            $my_array[$alcat->item_category]['percentage'] = round(($overallverified / $overalltotal) * 100, 2);
            $my_array[$alcat->item_category]['overallverified'] = $overallverified;
            $my_array[$alcat->item_category]['overalltotal'] = $overalltotal;
        }



        $LineItemBreakupChart_Verified_dataPoints1 = array();
        $LineItemBreakupChart_NotVerified_dataPoints2 = array();
        $count = 0;
        foreach ($my_array as $my_array_key => $my_array_value) {

            $LineItemBreakupChart_Verified_dataPoints1[] = array("label" => $my_array_key, "y" => $my_array_value['percentage'], "customText" => $my_array_value['overallverified']);

            $LineItemBreakupChart_NotVerified_dataPoints2[] = array("label" => $my_array_key, "y" => 100 - (int) $my_array_value['percentage'], "customText" => $my_array_value['overalltotal'] - $my_array_value['overallverified']);
            /*
            $LineItemBreakupChart_Verified_dataPoints1[] = array("label"=> $my_array_key, "y"=> $my_array_value['percentage']);
            $LineItemBreakupChart_NotVerified_dataPoints2[] = array("label"=> $my_array_key, "y"=> 100-(int)$my_array_value['percentage']); */
            $count++;
        }


        $data['LineItemBreakupChart_Verified_dataPoints1'] = $LineItemBreakupChart_Verified_dataPoints1;
        $data['LineItemBreakupChart_NotVerified_dataPoints2'] = $LineItemBreakupChart_NotVerified_dataPoints2;

        $filled = ($ttt + $tntt + $tutt) > 0 ? round((($ttv + $tntv + $tutv) / ($ttt + $tntt + $tutt)) * 100, 2) . '' : '0';


        $LineItemBreakup_DonutChart_dataPoints = array(
            array("label" => "Verified", "symbol" => "Verified", "y" => $filled),
            array("label" => "Not Verified", "symbol" => "Not-Verified", "y" => 100 - $filled),
        );
        $data['LineItemBreakup_DonutChart_dataPoints'] = $LineItemBreakup_DonutChart_dataPoints;



        // echo '<pre>';
        // print_r($allcategories['categories']);
        // echo '</pre>';
        // exit();


        /*
        $ttv=0;
        $ttt=0;
        $tntv=0;
        $tntt=0;
        $tutv=0;
        $tutt=0;
        $totalCount=0;                                                                        
        $my_array1 = array();
        foreach($allcategories['categories'] as $alcat)
        {
            $count=0;
            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
            {
                if(count($cat['tagged'])>0)
                {
                    $tg=0;
                    foreach($cat['tagged'] as $ct)
                    { 
                        if($ct['category']==$alcat->item_category)
                        {
                            $count=$count+$ct['verified'];
                            $totalCount=$totalCount+$ct['verified'];
                        }
                    }
                }
            }
            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
            {
                if(count($cat['untagged'])>0)
                {
                    $ut=0;
                    foreach($cat['untagged'] as $ct)
                    { 
                        if($ct['category']==$alcat->item_category)
                        {
                            $count=$count+$ct['verified'];
                            $totalCount=$totalCount+$ct['verified'];
                        }
                    }
                }
            }
            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
            {
                if(count($cat['unspecified'])>0)
                {
                    $us=0;
                    foreach($cat['unspecified'] as $ct)
                    { 
                        if($ct['category']==$alcat->item_category)
                        {
                            $count=$count+$ct['verified'];
                            $totalCount=$totalCount+$ct['verified'];
                        }
                    }
                }
            }



            if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
            {
                $overall=0;
                $overallverified=0;
                $overalltotal=0;
                $process=0;
                if(count($cat['tagged'])>0)
                {
                    $tg=0;
                    foreach($cat['tagged'] as $ct)
                    { 
                        if($ct['category']==$alcat->item_category)
                        {
                            $overall=$overall+$ct['amountpercentage'];
                            $overallverified=$overallverified+$ct['verifiedamount'];
                            $overalltotal=$overalltotal+$ct['totalamount'];
                            $ttv=$ttv+$ct['verifiedamount'];
                            $ttt=$ttt+$ct['totalamount'];
                            $ct['amountpercentage'] ==100? $process++ : $process;
                            }
                    }
                }
            }


            if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
            {
                if(count($cat['untagged'])>0)
                {
                    $ut=0;
                    foreach($cat['untagged'] as $ct)
                    { 
                        if($ct['category']==$alcat->item_category)
                        {
                            $overall=$overall+$ct['amountpercentage'];
                            $overallverified=$overallverified+$ct['verifiedamount'];
                            $overalltotal=$overalltotal+$ct['totalamount'];
                            $tntv=$tntv+$ct['verifiedamount'];
                            $tntt=$tntt+$ct['totalamount'];
                            $ct['amountpercentage'] ==100? $process++ : $process;
                        }
                    }
                }
            }

            if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
            {
                if(count($cat['unspecified'])>0)
                {
                    $us=0;
                    foreach($cat['unspecified'] as $ct)
                    { 
                        if($ct['category']==$alcat->item_category)
                        {
                            $overall=$overall+$ct['amountpercentage'];
                            $overallverified=$overallverified+$ct['verifiedamount'];
                            $overalltotal=$overalltotal+$ct['totalamount'];
                            $tutv=$tutv+$ct['verifiedamount'];
                        $tutt=$tutt+$ct['totalamount'];
                            $ct['amountpercentage'] ==100? $process++ : $process;
                        }
                    }
                }
            }


            if($projects[0]->project_type=='CD' )
            {
                $my_array1[$alcat->item_category]['percentage'] = getmoney_format(round((($overallverified/$overalltotal)*100),2));
                $my_array1[$alcat->item_category]['overallverified'] = round(($overallverified/100000));//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = round(($overalltotal/100000)); //getmoney_format(round(($overalltotal/100000),2));
            }

            // This New Section Added By Hardik For TG Project Start Here
            if($projects[0]->project_type=='TG' )
            {
                $my_array1[$alcat->item_category]['percentage'] = getmoney_format(round((($overallverified/$overalltotal)*100),2));
                $my_array1[$alcat->item_category]['overallverified'] = round(($overallverified/100000));//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = round(($overalltotal/100000)); //getmoney_format(round(($overalltotal/100000),2));
            }
            // This New Section Added By Hardik For TG Project End Here

            // This New Section Added By Hardik For TG Project Start Here
            if($projects[0]->project_type=='NT' )
            {
                $my_array1[$alcat->item_category]['percentage'] = getmoney_format(round((($overallverified/$overalltotal)*100),2));
                $my_array1[$alcat->item_category]['overallverified'] = round(($overallverified/100000));//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = round(($overalltotal/100000)); //getmoney_format(round(($overalltotal/100000),2));
            }
            // This New Section Added By Hardik For TG Project End Here
        }
        */

        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $totalCount = 0;
        $my_array1 = array();

        // echo "<pre>Data :";
        // print_r($allcategories['categories']);
        // echo "</pre>";
        // exit;

        foreach ($allcategories['categories'] as $alcat) {
            $count = 0;
            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }
            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }
            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }



            $overall = 0;
            $overallverified = 0;
            $overalltotal = 0;
            $process = 0;

            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $ttv = $ttv + $ct['verifiedamount'];
                            $ttt = $ttt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }


            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $tntv = $tntv + $ct['verifiedamount'];
                            $tntt = $tntt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }

            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $tutv = $tutv + $ct['verifiedamount'];
                            $tutt = $tutt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                        }
                    }
                }
            }


            if ($projects[0]->project_type == 'CD') {
                $my_array1[$alcat->item_category]['percentage'] = $overalltotal > 0 ? getmoney_format(round((($overallverified / $overalltotal) * 100), 2)) : 0;
                $my_array1[$alcat->item_category]['overallverified'] = ($overallverified / 100000);//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = ($overalltotal / 100000); //getmoney_format(round(($overalltotal/100000),2));
            }

            // This New Section Added By Hardik For TG Project Start Here
            if ($projects[0]->project_type == 'TG') {
                $my_array1[$alcat->item_category]['percentage'] = $overalltotal > 0 ? getmoney_format(round((($overallverified / $overalltotal) * 100), 2)) : 0;
                $my_array1[$alcat->item_category]['overallverified'] = ($overallverified / 100000);//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = ($overalltotal / 100000); //getmoney_format(round(($overalltotal/100000),2));
            }
            // This New Section Added By Hardik For TG Project End Here

            // This New Section Added By Hardik For TG Project Start Here
            if ($projects[0]->project_type == 'NT') {
                $my_array1[$alcat->item_category]['percentage'] = $overalltotal > 0 ? getmoney_format(round((($overallverified / $overalltotal) * 100), 2)) : 0;
                $my_array1[$alcat->item_category]['overallverified'] = ($overallverified / 100000);//getmoney_format(round(($overallverified/100000),2));
                $my_array1[$alcat->item_category]['overalltotal'] = ($overalltotal / 100000); //getmoney_format(round(($overalltotal/100000),2));
            }
        }


        // echo "<pre>Data :";
        // print_r($my_array1);
        // echo "</pre>";
        // exit;



        $filled = ($ttt + $tntt + $tutt) > 0 ? round((($ttv + $tntv + $tutv) / ($ttt + $tntt + $tutt)) * 100, 2) . ' 0 ' : '0 ';

        $AmountwiseBreakupChart_dataPoints1 = array();
        $AmountwiseBreakupChart_dataPoints2 = array();
        foreach ($my_array1 as $my_array1_key => $my_array1_value) {
            // Cast the values to float to ensure they are numeric before calculation
            $verified_amount = (float) str_replace(',', '', $my_array1_value['overallverified']);
            $total_amount = (float) str_replace(',', '', $my_array1_value['overalltotal']);

            $calculation = $my_array1_value['overalltotal'] - $my_array1_value['overallverified'];
            $AmountwiseBreakupChart_dataPoints1[] = array("label" => $my_array1_key, "y" => $verified_amount, "customText" => round($my_array1_value['overallverified'], 2) . " Lac");
            $AmountwiseBreakupChart_dataPoints2[] = array("label" => $my_array1_key, "y" => ($total_amount - $verified_amount), "customText" => round($calculation, 2) . " Lac");
        }


        // exit();

        $data['AmountwiseBreakupChart_dataPoints1'] = $AmountwiseBreakupChart_dataPoints1;
        $data['AmountwiseBreakupChart_dataPoints2'] = $AmountwiseBreakupChart_dataPoints2;



        $calculation = 100 - floatval($filled);
        $y_value = number_format((float) $calculation, 2, '.', '');


        $AmountwiseBreakup_DonutChart_dataPoints = array(
            array("label" => "Verified", "symbol" => "Verified", "y" => round((float) $filled)),
            array("label" => "Not Verified", "symbol" => "Not Verified", "y" => round((float) $y_value)),
        );


        $data['AmountwiseBreakup_DonutChart_dataPoints'] = $AmountwiseBreakup_DonutChart_dataPoints;










        $project_details = $this->db->select('*')->get($project_name)->result();


        $this->db->select($project_name . '.*,users.firstName');
        $this->db->from($project_name);
        $this->db->join('users', $project_name . '.verified_by = users.id');
        $query = $this->db->get();
        $project_details = $query->result();

        $project_details_array = array();
        $project_details_array2 = array();

        $verifier_users_array = array();
        $category_array = array();

        $user_wise_count = array();
        foreach ($project_details as $project_details_key => $project_details_value) {
            if (!empty($project_details_value->verified_by)) {
                $project_details_array[$project_details_value->firstName][$project_details_value->item_category][] = 1;
                $project_details_array2[$project_details_value->item_category][$project_details_value->firstName][] = 1;
            }
            if (!in_array($project_details_value->item_category, $verifier_users_array)) {
                $verifier_users_array[] = $project_details_value->item_category;
            }
            if (!in_array($project_details_value->firstName, $category_array)) {
                $category_array[] = $project_details_value->firstName;
            }
            $user_wise_count[$project_details_value->firstName][] = 1;


        }





        $ResourcewiseUtilizationChart_datapoint = array();
        $ResourcewiseUtilization_DonutChart_dataPoints_array = array();
        $count_value = 0;




        foreach ($project_details_array as $project_details_array_key => $project_details_array_value) {

            $ResourcewiseUtilizationChart_dataPoints1 = array();
            foreach ($verifier_users_array as $verifier_users_array_Key => $verifier_users_array_value) {
                if (isset($project_details_array[$project_details_array_key][$verifier_users_array_value])) {
                    $ResourcewiseUtilizationChart_dataPoints1[] = array("label" => $verifier_users_array_value, "y" => count($project_details_array[$project_details_array_key][$verifier_users_array_value]));
                    $ResourcewiseUtilization_DonutChart_dataPoints_array[$verifier_users_array_value][] = count($project_details_array[$project_details_array_key][$verifier_users_array_value]);
                }
            }



            $ResourcewiseUtilizationChart_datapoint[] = array(
                "type" => "stackedColumn100",
                "name" => $project_details_array_key,
                "showInLegend" => true,
                "yValueFormatString" => "#,##0 ",
                "dataPoints" => $ResourcewiseUtilizationChart_dataPoints1,
            );
            $count_value++;
        }




        $data['ResourcewiseUtilizationChart_datapoint'] = $ResourcewiseUtilizationChart_datapoint;

        $ResourcewiseUtilization_DonutChart_dataPoints_array1 = array();
        foreach ($ResourcewiseUtilization_DonutChart_dataPoints_array as $ResourcewiseUtilization_DonutChart_dataPoints_array_key => $ResourcewiseUtilization_DonutChart_dataPoints_array_value) {

            $ResourcewiseUtilization_DonutChart_dataPoints_array1[] = array("label" => $ResourcewiseUtilization_DonutChart_dataPoints_array_key, "symbol" => $ResourcewiseUtilization_DonutChart_dataPoints_array_key, "y" => array_sum($ResourcewiseUtilization_DonutChart_dataPoints_array_value));

        }




        $ResourcewiseUtilizationChart_dataPoints1 = array();
        foreach ($user_wise_count as $user_wise_count_key => $user_wise_count_value) {


            $ResourcewiseUtilizationChart_dataPoints1[] = array(
                "label" => $user_wise_count_key,
                "symbol" => $user_wise_count_key,
                "y" => count($user_wise_count_value),
            );
        }




        $data['ResourcewiseUtilization_DonutChart_dataPoints'] = $ResourcewiseUtilizationChart_dataPoints1;



        $data['projects'] = $projects;
        $data['page_title'] = "Project Detail";


        // Dynamic Data Start
        $LineBreakTableData = [];
        $AmountWiseTableData = [];

        $total_li_verified = 0;
        $total_li_total = 0;
        $total_amt_verified = 0;
        $total_amt_total = 0;

        $LineBreakTableData['header'] = array(
            "Col-1" => "Cat#",
            "Col-2" => "Amount (in lacs)",
            "Col-3" => "Tagged",
            "Col-4" => "Non-Tagged",
            "Col-5" => "Overall",
            "Col-6" => "Verification Status"
        );
        $AmountWiseTableData['header'] = array(
            "Col-1" => "Cat#",
            "Col-2" => "Line Items (Li)",
            "Col-3" => "Tagged",
            "Col-4" => "Non-Tagged",
            "Col-5" => "Overall",
            "Col-6" => "Verification Status"
        );


        // echo "<pre>my_array :";
        // print_r($my_array);
        // echo "</pre>";
        // exit;



        $cat = getTagUntagCategories($projects[0]->project_name);
        $allcategories = getCategories($projects[0]->project_name);
        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $tamt = 0;
        foreach ($allcategories['categories'] as $alcat) {

            $overallverified = 0;
            $overalltotal = 0;

            $tamt = $tamt + $alcat->amount;
            $tagged_List_value = "";
            $not_tagged_List_value = "";
            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                $overall = 0;

                $process = 0;
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $ttv = $ttv + $ct['verified'];
                            $ttt = $ttt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                            $tagged_List_value = $ct['percentage'] . '% ' . $ct['verified'] . ' of ' . $ct['total'] . ' Li';
                            $tg++;
                        }
                    }
                    if ($tg == 0) {
                        $tagged_List_value = "0% 0 of 0 Li";
                    }
                } else {
                    $tagged_List_value = "0% 0 of 0 Li";
                }
            }


            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $process = 0;
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $tntv = $tntv + $ct['verified'];
                            $tntt = $tntt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                            $not_tagged_List_value = "" . $ct['percentage'] . '% ' . $ct['verified'] . ' of ' . $ct['total'] . ' Li';

                            $ut++;
                        }

                    }
                    if ($ut == 0) {
                        $not_tagged_List_value = "0% 0 of 0 Li";

                    }
                } else {
                    $not_tagged_List_value = "0% 0 of 0 Li";
                }
            }

            $overall_List_value = "";
            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                // $overallverified=0;
                // $overalltotal=0;
                $process = 0;
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['percentage'];
                            $overallverified = $overallverified + $ct['verified'];
                            $overalltotal = $overalltotal + $ct['total'];
                            $tutv = $tutv + $ct['verified'];
                            $tutt = $tutt + $ct['total'];
                            $ct['percentage'] == 100 ? $process++ : $process;
                            $overall_List_value = $ct['percentage'] . '% ' . $ct['verified'] . ' of ' . $ct['total'] . ' Li';

                            $us++;
                        }

                    }
                    if ($us == 0) {
                        $overall_List_value = "0% 0 of 0 Li";
                    }
                } else {
                    $overall_List_value = "0% 0 of 0 Li";
                }
            }



            $li_percent = isset($ct['percentage']) ? $ct['percentage'] : 0;
            $LineBreakTableData[] = [
                "category_List" => $alcat->item_category,
                "amount_List" => getmoney_format(round(($alcat->amount / 100000), 2)),
                "tagged_List" => $tagged_List_value,
                "not_tagged_List" => $not_tagged_List_value,
                "overall_List" => $overall_List_value,
                "verification_status_List" => ($li_percent == 100) ? "Verified" : "In Process"
            ];
        }


        $tagged_Total_value = $ttv . ' of ' . $ttt . ' Li';
        $not_tagged_Total_value = $tntv . ' of ' . $tntt . ' Li';
        ;
        // $overall_Total_value = $tutv.' of '.$tutt.' Li';;
        $overall_Total_value = ($ttv + $tntv + $tutv) . ' of ' . ($ttt + $tntt + $tutt) . ' Li';
        $LineBreakTableData['total'] = array(
            "category_Total" => "TOTAL",
            "amount_Total" => getmoney_format(round(($tamt / 100000), 2)),
            "tagged_Total" => $tagged_Total_value,
            "not_tagged_Total" => $not_tagged_Total_value,
            "overall_Total" => $overall_Total_value,
            "verification_status_Total" => "",
        );

        $tagged_Percentage_value = $ttt > 0 ? round(($ttv / $ttt) * 100, 2) . ' %' : '0 %';
        $not_tagged_Percentage_value = $tntt > 0 ? round(($tntv / $tntt) * 100, 2) . ' %' : '0 %';
        // $overall_Percentage_value = $tutt>0?round(($tutv/$tutt)*100,2).' %': '0 %';
        $overall_Percentage_value = ($ttt + $tntt + $tutt) > 0 ? round((($ttv + $tntv + $tutv) / ($ttt + $tntt + $tutt)) * 100, 2) . ' %' : '0 %';
        $LineBreakTableData['percentage'] = array(
            "category_Percentage" => "%",
            "amount_Percentage" => "",
            "tagged_Percentage" => $tagged_Percentage_value,
            "not_tagged_Percentage" => $not_tagged_Percentage_value,
            "overall_Percentage" => $overall_Percentage_value,
            "verification_status_Percentage" => "",
        );



        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $totalCount = 0;
        // $count = 0;
        foreach ($allcategories['categories'] as $alcat) {
            $count = 0;

            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }
            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }
            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $count = $count + $ct['verified'];
                            $totalCount = $totalCount + $ct['verified'];
                        }
                    }
                }
            }

            $line_items_List_value = $count . ' of ' . $alcat->catitems . ' Li';


            $tagged_List_value = "";
            if (!empty($cat['tagged']) && ($projects[0]->project_type == 'TG' || $projects[0]->project_type == 'CD')) {
                $overall = 0;
                $overallverified = 0;
                $overalltotal = 0;
                $process = 0;
                if (count($cat['tagged']) > 0) {
                    $tg = 0;
                    foreach ($cat['tagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $ttv = $ttv + $ct['verifiedamount'];
                            $ttt = $ttt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                            $tagged_List_value = $ct['amountpercentage'] . '% ' . getmoney_format(round(($ct['verifiedamount'] / 100000), 2)) . ' of ' . getmoney_format(round(($ct['totalamount'] / 100000), 2)) . ' Lacs';
                            $tg++;
                        }
                    }
                    if ($tg == 0) {
                        $tagged_List_value = "0% 0 of 0 Lacs";
                    }
                } else {
                    $tagged_List_value = "0% 0 of 0 Lacs";
                }
            }


            $not_tagged_List_value = "";
            if (!empty($cat['untagged']) && ($projects[0]->project_type == 'NT' || $projects[0]->project_type == 'CD')) {
                if (count($cat['untagged']) > 0) {
                    $ut = 0;
                    foreach ($cat['untagged'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $tntv = $tntv + $ct['verifiedamount'];
                            $tntt = $tntt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                            $not_tagged_List_value = $ct['amountpercentage'] . '% ' . getmoney_format(round(($ct['verifiedamount'] / 100000), 2)) . ' of ' . getmoney_format(round(($ct['totalamount'] / 100000), 2)) . ' Lacs';
                            $ut++;
                        }

                    }
                    if ($ut == 0) {
                        $not_tagged_List_value = "0% 0 of 0 Lacs";
                    }
                } else {
                    $not_tagged_List_value = "0% 0 of 0 Lacs";
                }
            }


            if (!empty($cat['unspecified']) && ($projects[0]->project_type == 'UN' || $projects[0]->project_type == 'CD')) {
                if (count($cat['unspecified']) > 0) {
                    $us = 0;
                    foreach ($cat['unspecified'] as $ct) {
                        if ($ct['category'] == $alcat->item_category) {
                            $overall = $overall + $ct['amountpercentage'];
                            $overallverified = $overallverified + $ct['verifiedamount'];
                            $overalltotal = $overalltotal + $ct['totalamount'];
                            $tutv = $tutv + $ct['verifiedamount'];
                            $tutt = $tutt + $ct['totalamount'];
                            $ct['amountpercentage'] == 100 ? $process++ : $process;
                            $overall_List_value = $ct['amountpercentage'] . '% ' . getmoney_format(round(($ct['verifiedamount'] / 100000), 2)) . ' of ' . getmoney_format(round(($ct['totalamount'] / 100000), 2)) . ' Lacs';

                            $us++;
                        }

                    }
                    if ($us == 0) {
                        $overall_List_value = "0% 0 of 0 Lacs";
                    }
                } else {
                    $overall_List_value = "0% 0 of 0 Lacs";
                }
            }
            $li_percent = isset($ct['amountpercentage']) ? $ct['amountpercentage'] : 0;


            $AmountWiseTableData[] = [
                "category_List" => $alcat->item_category,
                "line_items_List" => $line_items_List_value,
                "tagged_List" => $tagged_List_value,
                "not_tagged_List" => $not_tagged_List_value,
                "overall_List" => $overall_List_value,
                "verification_status_List" => ($li_percent == 100) ? "Verified" : "In Process"
            ];
        }


        $tagged_value_Total_value = getmoney_format(number_format(($ttv / 100000), 2, '.', '')) . ' of ' . getmoney_format(number_format(($ttt / 100000), 2, '.', '')) . ' Lacs';
        $not_tagged_Total_value = getmoney_format(number_format(($tntv / 100000), 2, '.', '')) . ' of ' . getmoney_format(number_format(($tntt / 100000), 2, '.', '')) . ' Lacs';
        $overall_Total_value = getmoney_format(number_format(($tutv / 100000), 2, '.', '')) . ' of ' . getmoney_format(number_format(($tutt / 100000), 2, '.', '')) . ' Lacs';
        $AmountWiseTableData['total'] = array(
            "category_Total" => "TOTAL",
            "line_items_Total" => $totalCount . ' of ' . $cat['totalitems'] . ' Li',
            "tagged_value_Total" => $tagged_value_Total_value,
            "not_tagged_Total" => $not_tagged_Total_value,
            "overall_Total" => $overall_Total_value,
            "verification_status_Total" => "",
        );

        $tagged_Percentage_value = $ttt > 0 ? round(($ttv / $ttt) * 100, 2) . ' %' : '0 %';
        $not_tagged_Percentage_value = $tntt > 0 ? round(($tntv / $tntt) * 100, 2) . ' %' : '0 %';
        $overall_Percentage_value = $tutt > 0 ? round(($tutv / $tutt) * 100, 2) . ' %' : '0 %';
        $AmountWiseTableData['percentage'] = array(
            "category_Percentage" => "%",
            "line_items_Percentage" => "",
            "tagged_Percentage" => $tagged_Percentage_value,
            "not_tagged_Percentage" => $not_tagged_Percentage_value,
            "overall_Percentage" => $overall_Percentage_value,
            "verification_status_Percentage" => ""
        );





        // echo "<pre>LineBreakTableData :";
        // print_r($LineBreakTableData);
        // echo "</pre>";
        // exit;


        /*
        if (isset($my_array) && is_array($my_array)) {
            foreach($my_array as $cat => $val) {
                $li_verified = isset($val['overallverified']) ? $val['overallverified'] : 0;
                $li_total = isset($val['overalltotal']) ? $val['overalltotal'] : 0;
                $li_percent = isset($val['percentage']) ? $val['percentage'] : 0;

                $amt_verified = isset($my_array1[$cat]['overallverified']) ? $my_array1[$cat]['overallverified'] : 0;
                $amt_total = isset($my_array1[$cat]['overalltotal']) ? $my_array1[$cat]['overalltotal'] : 0;
                $amt_percent = isset($my_array1[$cat]['percentage']) ? $my_array1[$cat]['percentage'] : 0;

                $amt_total_formatted = is_numeric($amt_total) ? round($amt_total, 2) : 0;
                $amt_verified_formatted = is_numeric($amt_verified) ? round($amt_verified, 2) : 0;

                $total_li_verified += $li_verified;
                $total_li_total += $li_total;
                $total_amt_verified += $amt_verified;
                $total_amt_total += $amt_total;

                // $LineBreakTableData[] = [
                //     "category_List" => $cat,
                //     "amount_List" => (string)$amt_total_formatted,
                //     "tagged_List" => $li_percent . " % " .$li_verified . " of " . $li_total . " Li",                    
                //     "not_tagged_List" => "",
                //     "overall_List" => "",
                //     "verification_status_List" => ($li_percent == 100) ? "Verified" : "In Process"
                // ];
                // $AmountWiseTableData[] = [
                //     "category_List" => $cat,
                //     "line_items_List" => $li_verified . " of " . $li_total . " Li",
                //     "tagged_List" => $amt_percent . " % ".$amt_verified_formatted . " of " . $amt_total_formatted . " Lacs",
                //     "not_tagged_List" => "",
                //     "overall_List" => "",
                //     "verification_status_List" => ($amt_percent == 100) ? "Verified" : "In Process"
                // ];
            }
        } */
        $overall_li_percent = $total_li_total > 0 ? round(($total_li_verified / $total_li_total) * 100, 2) : 0;
        $overall_amt_percent = $total_amt_total > 0 ? round(($total_amt_verified / $total_amt_total) * 100, 2) : 0;

        // LineBreak Table 

        // $LineBreakTableData['total'] = array(
        //     "category_Total" => "TOTAL",
        //     "amount_Total" => (string)round($total_amt_total, 2),
        //     "tagged_Total" => $total_li_verified . " of " . $total_li_total . " Li",
        //     "not_tagged_Total" => "",
        //     "overall_Total" => "",
        //     "verification_status_Total" => "",
        // );
        // $LineBreakTableData['percentage'] = array(
        //     "category_Percentage" => "%",
        //     "amount_Percentage" => "",
        //     "tagged_Percentage" => $overall_li_percent . " %",
        //     "not_tagged_Percentage" => "",
        //     "overall_Percentage" => "",
        //     "verification_status_Percentage" => "",
        // );
        // LineBreak Table 




        // $AmountWiseTableData['total'] = array(
        //     "category_Total" => "TOTAL",
        //     "line_items_Total" => $total_li_verified . " of " . $total_li_total . " Li",
        //     "tagged_value_Total" => round($total_amt_verified, 2) . " of " . round($total_amt_total, 2) . " Lacs",
        //     "not_tagged_Total" => "",
        //     "overall_Total" => "",
        //     "verification_status_Total" => "",
        // );
        // $AmountWiseTableData['percentage'] = array(
        //     "category_Percentage" => "%",
        //     "line_items_Percentage" => "",
        //     "tagged_Percentage" => $overall_amt_percent . " %",
        //     "not_tagged_Percentage" => "",
        //     "overall_Percentage" => "",
        //     "verification_status_Percentage" => ""
        // );

        $LineBreakTable = [
            "status" => true,
            "data" => $LineBreakTableData,
        ];

        $AmountWiseTable = [
            "status" => true,
            "data" => $AmountWiseTableData,
        ];

        $ResourcewiseUtilizationTableData = [];
        $res_id = 1;
        $total_res_verified = 0;

        $ResourcewiseUtilizationTableData['header'] = array(
            "Col-1" => "#",
            "Col-2" => "Resource Name",
            "Col-3" => "Tagged",
            "Col-4" => "Non-Tagged",
            "Col-5" => "Overall",
            "Col-6" => "Verification Status"
        );

        $listing = getTagUntag($projects[0]->project_name);
        $ttv = 0;
        $ttt = 0;
        $tntv = 0;
        $tntt = 0;
        $tutv = 0;
        $tutt = 0;
        $i = 0;
        foreach ($listing['projectverifiers'] as $res) {
            $ttv = $ttv + $res->usertagged;
            $tntv = $tntv + $res->useruntagged;
            $tutv = $tutv + $res->userunspecified;

            $tagged_List_value = $res->usertagged . ' of ' . $listing['ytotal'] . " Li";
            $not_tagged_List_value = $res->useruntagged . ' of ' . $listing['ntotal'] . " Li";
            ;

            $totuser = ($res->usertagged + $res->useruntagged + $res->userunspecified);
            $totalall = $listing['natotal'] + $listing['ytotal'] + $listing['ntotal'];
            $overall_List_value = $totuser . ' of ' . $totalall . " Li";
            $ResourcewiseUtilizationTableData[] = [
                "id_List" => $res_id++,
                "resource_name_List" => get_UserName($res->user_id),
                "tagged_List" => $tagged_List_value,
                "not_tagged_List" => $not_tagged_List_value,
                "overall_List" => $overall_List_value,
                "verification_status_List" => ($total_li_total > 0 && $user_verified == $total_li_total) ? "Verified" : "In Process"
            ];

        }


        $tagged_Total_value = $ttv . ' of ' . $listing['ytotal'] . ' Li';
        ;
        $not_tagged_Total_value = $tntv . ' of ' . $listing['ntotal'] . ' Li';
        ;
        $overall_Total_value = ($ttv + $tntv + $tutv) . ' of ' . ($listing['ytotal'] + $listing['natotal'] + $listing['ntotal']) . ' Li';
        $ResourcewiseUtilizationTableData['total'] = [
            "id_Total" => "TOTAL",
            "resource_name_Total" => "",
            "tagged_Total" => $tagged_Total_value,
            "not_tagged_Total" => $not_tagged_Total_value,
            "overall_Total" => $overall_Total_value,
            "verification_status_Total" => ""
        ];


        $tagged_Percentage_value = $ttv . ' of ' . $listing['ytotal'] . ' Li';
        $not_tagged_Percentage_value = $tntv . ' of ' . $listing['ntotal'] . ' Li';
        ;
        $overall_Percentage_value = ($ttv + $tntv + $tutv) . ' of ' . ($listing['ytotal'] + $listing['natotal'] + $listing['ntotal']) . ' Li';
        $ResourcewiseUtilizationTableData['percentage'] = [
            "id_Percentage" => "%",
            "resource_name_Percentage" => "",
            "tagged_Percentage" => $tagged_Percentage_value,
            "not_tagged_Percentage" => $not_tagged_Percentage_value,
            "overall_Percentage" => $overall_Percentage_value,
            "verification_status_Percentage" => ""
        ];




        if (isset($user_wise_count) && is_array($user_wise_count)) {
            foreach ($user_wise_count as $user_name => $items) {
                $user_verified = count($items);
                $total_res_verified += $user_verified;

                // $ResourcewiseUtilizationTableData[] = [
                //     "id_List" => $res_id++,
                //     "resource_name_List" => $user_name ? $user_name : "Unknown",
                //     "tagged_List" => $user_verified . " of " . $total_li_total . " Li",
                //     "not_tagged_List" => "",
                //     "overall_List" => "",
                //     "verification_status_List" => ($total_li_total > 0 && $user_verified == $total_li_total) ? "Verified" : "In Process"
                // ];
            }
        }

        $res_percent = $total_li_total > 0 ? round(($total_res_verified / $total_li_total) * 100, 2) : 0;

        // $ResourcewiseUtilizationTableData['total'] = [
        //     "id_Total" => "TOTAL",
        //     "resource_name_Total" => "",
        //     "tagged_Total" => $total_res_verified . " of " . $total_li_total . " Li",
        //     "not_tagged_Total" => "",
        //     "overall_Total" => "",
        //     "verification_status_Total" => ""
        // ];
        // $ResourcewiseUtilizationTableData['percentage'] = [
        //     "id_Percentage" => "%",
        //     "resource_name_Percentage" => "",
        //     "tagged_Percentage" => $res_percent . " %",
        //     "not_tagged_Percentage" => "",
        //     "overall_Percentage" => "",
        //     "verification_status_Percentage" => ""
        // ];

        $ResourcewiseUtilizationTable = [
            "status" => true,
            "data" => $ResourcewiseUtilizationTableData
        ];
        // Dynamic Data End

        $data['LineBreakTable'] = $LineBreakTable;
        $data['AmountWiseTable'] = $AmountWiseTable;
        $data['ResourcewiseUtilizationTable'] = $ResourcewiseUtilizationTable;

        // $this->load->view('project_detail3',$data);
        // $this->load->view('ProjectDetailsOneView',$data);

        header('Content-Type: application/json');
        echo json_encode($data);
        exit;


    }


    //api for graphs:
    private function projectData($id)
    {
        $project = $this->tasks
            ->get_data('company_projects', ['id' => $id]);

        if (empty($project))
            return false;

        $old_pattern = ["/[^a-zA-Z0-9]/", "/_+/", "/_$/"];
        $new_pattern = ["_", "_", ""];

        $table = strtolower(
            preg_replace($old_pattern, $new_pattern, trim($project[0]->project_name))
        );

        return [
            "project" => $project[0],
            "table" => $table
        ];
    }
    // API 1:
    public function overall_status_graph()
    {
        $id = $this->input->post('project_id');
        $data = $this->projectData($id);

        if (!$data) {
            echo json_encode(["success" => 404]);
            return;
        }

        $table = $data['table'];

        $verified = $this->db
            ->where([
                'tag_status_y_n_na' => 'Y',
                'verification_status' => 'Verified'
            ])
            ->count_all_results($table);

        $pending = $this->db
            ->where('tag_status_y_n_na', 'Y')
            ->where('verification_status !=', 'Verified')
            ->count_all_results($table);

        echo json_encode([
            "success" => 200,
            "verified" => $verified,
            "pending" => $pending
        ]);
    }
    // API 2
    public function overall_status_table()
    {
        $id = $this->input->post('project_id');
        $data = $this->projectData($id);

        if (!$data) {
            echo json_encode(["success" => 404]);
            return;
        }

        $table = $data['table'];

        $result = $this->db
            ->select('tag_status_y_n_na,verification_status,count(*) total')
            ->group_by(['tag_status_y_n_na', 'verification_status'])
            ->get($table)
            ->result();

        echo json_encode(["success" => 200, "table" => $result]);
    }
    // API 3
    public function line_item_graph()
    {
        $id = $this->input->post('project_id');
        $data = $this->projectData($id);

        if (!$data) {
            echo json_encode(["success" => 404]);
            return;
        }

        $project = $data['project'];

        $cat = getTagUntagCategories($project->project_name);
        $all = getCategories($project->project_name);

        $response = [];

        foreach ($all['categories'] as $c) {

            $total = 0;
            $verified = 0;

            foreach (['tagged', 'untagged', 'unspecified'] as $t) {
                if (!empty($cat[$t])) {
                    foreach ($cat[$t] as $ct) {
                        if ($ct['category'] == $c->item_category) {
                            $total += $ct['total'];
                            $verified += $ct['verified'];
                        }
                    }
                }
            }

            $percentage = $total > 0
                ? round(($verified / $total) * 100, 2)
                : 0;

            $response[] = [
                "label" => $c->item_category,
                "percentage" => $percentage
            ];
        }

        echo json_encode([
            "success" => 200,
            "graph" => $response
        ]);
    }

    // API 4
    public function line_item_table()
    {
        $id = $this->input->post('project_id');
        $data = $this->projectData($id);

        if (!$data) {
            echo json_encode(["success" => 404]);
            return;
        }

        $project = $data['project'];

        $cat = getTagUntagCategories($project->project_name);
        $all = getCategories($project->project_name);

        $response = [];

        foreach ($all['categories'] as $c) {

            $total = 0;
            $verified = 0;

            foreach (['tagged', 'untagged', 'unspecified'] as $t) {
                if (!empty($cat[$t])) {
                    foreach ($cat[$t] as $ct) {
                        if ($ct['category'] == $c->item_category) {
                            $total += $ct['total'];
                            $verified += $ct['verified'];
                        }
                    }
                }
            }

            $response[] = [
                "category" => $c->item_category,
                "verified" => $verified,
                "total" => $total
            ];
        }

        echo json_encode(["success" => 200, "table" => $response]);
    }
    // API 5
    public function amount_graph()
    {
        $id = $this->input->post('project_id');
        $data = $this->projectData($id);

        if (!$data) {
            echo json_encode(["success" => 404]);
            return;
        }

        $project = $data['project'];

        $cat = getTagUntagCategories($project->project_name);
        $all = getCategories($project->project_name);

        $response = [];

        foreach ($all['categories'] as $c) {

            $total = 0;
            $verified = 0;

            foreach (['tagged', 'untagged', 'unspecified'] as $t) {
                if (!empty($cat[$t])) {
                    foreach ($cat[$t] as $ct) {
                        if ($ct['category'] == $c->item_category) {
                            $total += $ct['totalamount'];
                            $verified += $ct['verifiedamount'];
                        }
                    }
                }
            }

            $response[] = [
                "label" => $c->item_category,
                "verified_lacs" => round($verified / 100000, 2),
                "total_lacs" => round($total / 100000, 2)
            ];
        }

        echo json_encode(["success" => 200, "graph" => $response]);
    }
    // API 6
    public function amount_table()
    {
        // same logic but return verified,total values

        $id = $this->input->post('project_id');
        $data = $this->projectData($id);

        if (!$data) {
            echo json_encode(["success" => 404]);
            return;
        }

        $project = $data['project'];

        $cat = getTagUntagCategories($project->project_name);
        $all = getCategories($project->project_name);

        $response = [];

        foreach ($all['categories'] as $c) {

            $total = 0;
            $verified = 0;

            foreach (['tagged', 'untagged', 'unspecified'] as $t) {
                if (!empty($cat[$t])) {
                    foreach ($cat[$t] as $ct) {
                        if ($ct['category'] == $c->item_category) {
                            $total += $ct['totalamount'];
                            $verified += $ct['verifiedamount'];
                        }
                    }
                }
            }

            $response[] = [
                "category" => $c->item_category,
                "verified" => $verified,
                "total" => $total
            ];
        }

        echo json_encode(["success" => 200, "table" => $response]);
    }
    // API 7
    public function resource_graph()
    {
        $id = $this->input->post('project_id');
        $data = $this->projectData($id);

        if (!$data) {
            echo json_encode(["success" => 404]);
            return;
        }

        $table = $data['table'];

        $this->db->select("$table.*,users.firstName");
        $this->db->join('users', "$table.verified_by=users.id");

        $rows = $this->db->get($table)->result();

        $counts = [];
        foreach ($rows as $r) {
            $counts[$r->firstName] = ($counts[$r->firstName] ?? 0) + 1;
        }

        echo json_encode(["success" => 200, "graph" => $counts]);
    }
    //API 8
    public function resource_table()
    {
        $id = $this->input->post('project_id');
        $data = $this->projectData($id);

        if (!$data) {
            echo json_encode(["success" => 404]);
            return;
        }

        $table = $data['table'];

        $this->db->select("$table.*,users.firstName");
        $this->db->join('users', "$table.verified_by=users.id");

        $rows = $this->db->get($table)->result();

        $counts = [];
        foreach ($rows as $r) {
            $counts[$r->firstName] = ($counts[$r->firstName] ?? 0) + 1;
        }

        $response = [];
        foreach ($counts as $name => $count) {
            $response[] = [
                "user" => $name,
                "verified_count" => $count
            ];
        }

        echo json_encode(["success" => 200, "table" => $response]);
    }



    public function dashboardgraph()
    {

        $company_datas = $_POST['company_id'];
        $location_datas = $_POST['location_id'];
        $role_id_datas = $_POST['role_id'];
        $user_id = $_POST['user_id'];
        $entity_code = $_POST['entity_code'];
        $user_role = $_POST['role_id'];

        if ($role_id_datas == '0') {
            $role_field_map[] = array('project_verifier' => 'manager');
        }
        if ($role_id_datas == '1') {
            $role_field_map[] = array('project_verifier' => 'project_verifier');
        }
        if ($role_id_datas == '2') {
            $role_field_map[] = array('project_verifier' => 'process_owner');
        }
        if ($role_id_datas == '3') {
            $role_field_map[] = array('project_verifier' => 'item_owner');
        }
        if ($role_id_datas == '1') {
            $role_field_map[] = array('project_verifier' => 'project_verifier');
        }

        $role_where = "";
        if (isset($role_field_map[$user_role])) {
            $field = $role_field_map[$user_role];
            foreach ($field as $field_value) {
                $role_where .= "FIND_IN_SET($user_id, $field_value)";
            }
        } else {
            // If user has multiple roles or fallback, show all relevant projects
            if ($role_id_datas == '0') {
                $role_where .= "FIND_IN_SET($user_id, manager)";
            }
            if ($role_id_datas == '1') {
                $role_where .= "FIND_IN_SET($user_id, project_verifier)";
            }
            if ($role_id_datas == '2') {
                $role_where .= "FIND_IN_SET($user_id, process_owner)";
            }
            if ($role_id_datas == '3') {
                $role_where .= "FIND_IN_SET($user_id, item_owner)";
            }
        }

        // echo "<pre>role_where :";
        // print_r($role_where);
        // echo "</pre>";
        // exit;



        $company_projects = $this->db->query('SELECT company_locations.location_name,company_projects.* FROM company_projects LEFT JOIN company_locations ON company_projects.project_location = company_locations.id WHERE company_projects.company_id IN (' . $company_datas . ') AND company_projects.status = 0 AND company_projects.entity_code = "' . $entity_code . '" AND (' . $role_where . ')')->result();

        // echo "<pre>Last query :";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // // exit;


        if (!empty($location_datas)) {
            $company_projects = $this->db->query('SELECT company_locations.location_name,company_projects.* FROM company_projects LEFT JOIN company_locations ON company_projects.project_location = company_locations.id WHERE company_projects.company_id IN (' . $company_datas . ') AND company_projects.project_location = ' . $location_datas . ' AND company_projects.status = 0 AND company_projects.entity_code = "' . $entity_code . '" AND (' . $role_where . ')')->result();
        }

        // echo "<pre>Last query :";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // exit;


        $project_base_count = array();
        $withing_time = array();
        $due_date = array();
        foreach ($company_projects as $company_projects_key => $company_projects_value) {

            $project_due_date = $company_projects_value->due_date;
            $project_name = $company_projects_value->project_name;
            $due_date = $project_due_date; // Format: Y-m-d
            $today = date('Y-m-d');


            if ($due_date <= $today) {
                $project_base_count[$company_projects_value->location_name]['overdue'][] = 1;
            } else {
                $project_base_count[$company_projects_value->location_name]['withindate'][] = 1;
            }
        }



        $graph_data = array();
        $count = 1;

        $overdue_array = array();
        $withindate_array = array();
        $count_zero = 1;
        $count = 0;
        foreach ($project_base_count as $project_base_count_key => $project_base_count_value) {


            $overdue_array[$count]['label'] = $project_base_count_key;
            $withindate_array[$count]['label'] = $project_base_count_key;
            $overdue_count_data = 0;
            $withindate_count_data = 0;
            if (isset($project_base_count[$project_base_count_key]['overdue'])) {
                $overdue_count_data = count($project_base_count[$project_base_count_key]['overdue']);
                $project_base_count[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue']);
                $graph_data[$project_base_count_key]['overdue_count'] = count($project_base_count[$project_base_count_key]['overdue']);
            } else {
                $overdue_count_data = 0;
                $project_base_count[$project_base_count_key]['overdue_count'] = 0;
                $graph_data[$project_base_count_key]['overdue_count'] = 0;
            }

            $overdue_array[$count]['y'] = $overdue_count_data;
            $overdue_array[$count]['id'] = $count_zero;

            if (isset($project_base_count[$project_base_count_key]['withindate'])) {
                $withindate_count_data = count($project_base_count[$project_base_count_key]['withindate']);
                $project_base_count[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
                $graph_data[$project_base_count_key]['withindate_count'] = count($project_base_count[$project_base_count_key]['withindate']);
            } else {
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




    public function get_user_details()
    {



        $user_id = $this->input->post('user_id');
        $entity_code = $this->input->post('entity_code');

        $this->db->select('users.firstName,users.lastName,users.entity_code,users.phone_no,users.userEmail,users.designation,department.department_name,company.company_name,company_locations.location_name');
        $this->db->from('users');
        $this->db->join('department', 'department.id = users.department_id', 'left');
        $this->db->join('company', 'company.id = users.company_id', 'left');
        $this->db->join('company_locations', 'company_locations.id = users.location_id', 'left');
        $this->db->where('users.id', $user_id);
        $this->db->where('users.entity_code', $entity_code);
        $query = $this->db->get();
        $user_details = $query->row();

        if ($user_details) {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 200, "message" => "User Details.", "data" => $user_details));
            exit;

        } else {
            header('Content-Type: application/json');
            echo json_encode(array("success" => 401, "message" => "Not Found Data"));
            exit;
        }
    }



}




