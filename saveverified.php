<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once APPPATH."/third_party/PHPExcel.php";

class saveverified extends CI_Controller
{

 public function saveverified1()
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

            if($edit_opration == 'Update Qty & Details'){
                $verification_remarks = $get_item_details->verification_remarks . ' || (-' . $revert_qty . ') || ';
                $new_loc_rollback = $get_item_details->new_location_verified . ' || (-' . $revert_qty . ') || ';
            }else{
                $verification_remarks = $get_item_details->verification_remarks . ' || (-' . $revert_qty . ') || ';
                $new_loc_rollback = $get_item_details->new_location_verified . ' || (-' . $revert_qty . ') || ';
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

            $verify = $this->tasks->update_data($projectname, $update_item_details_data_first, $condition);           //UPDATE OPERATION


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
                $new_remarks = $get_item_details->verification_remarks != '' ? $get_item_details->verification_remarks . ' || ' . $update_details->verification_remarks : $update_details->verification_remarks;
            }
            $update_item_details_data_second['verification_remarks'] = $new_remarks;

            $new_loc = $get_item_details->new_location_verified;
            if (isset($update_details->new_location_verified) && $update_details->new_location_verified != '') {
                $new_loc = $get_item_details->new_location_verified != '' ? $get_item_details->new_location_verified . ' || ' . $update_details->new_location_verified : $update_details->new_location_verified;
            }
            $update_item_details_data_second['new_location_verified'] = $new_loc;

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

                $scanned->verification_remarks = $getquantity[0]->verification_remarks;

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

}

    ?>