<?php 

if(isset($_POST['instance'])){
        
            $verification_remarks = '';
            $qty_ok = 0;
            $qty_damaged = 0;
            $qty_scrapped = 0;
            $qty_not_in_use = 0;
            $qty_missing = 0;
            $qty_shifted = 0;

            $this->db->select('*');
            $this->db->from('verifiedproducts');
            $this->db->where('item_id',$itemid);
            $this->db->where('id',$instance);
            $this->db->where('project_id',$project_id);
            $query = $this->db->get();
            $get_instance_details= $query->row();
            
            $this->db->select('*');
            $this->db->from($projectname);        
            $this->db->where('id',$itemid);
            $query = $this->db->get();
            $get_item_details= $query->row();


            $project_id=$this->input->post('project_id');
            $getprojectdetails_condition = array(
                'id' => $project_id
            );
            $get_project_details = $this->tasks->get_data('company_projects',$getprojectdetails_condition);

            $quantity_verified_value = $get_item_details->quantity_verified;
            $verify_user_detail = $this->tasks->get_single_user($this->input->post('verify_by'));
            $verified_by = $this->input->post('verify_by');
            $verified_by_username = $verify_user_detail->firstName;

            // if($edit_opration == 'Update Qty & Details'){

                if($get_instance_details->qty_value < $update_details->quantity_verified){
                    $operation = 'addition';
                }else{
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
                    'qty_ok' => (int)$get_item_details->qty_ok,
                    'qty_damaged' => (int)$get_item_details->qty_damaged,
                    'qty_scrapped' => (int)$get_item_details->qty_scrapped,
                    'qty_not_in_use' => (int)$get_item_details->qty_not_in_use,
                    'qty_missing' => (int)$get_item_details->qty_missing,
                    'qty_shifted' => (int)$get_item_details->qty_shifted,
                );

                $update_details_data = array(
                    'qty_ok' => $get_instance_details_qty_ok,
                    'qty_damaged' => $get_instance_details_qty_damaged, 
                    'qty_scrapped' => $get_instance_details_qty_scrapped,
                    'qty_not_in_use' => $get_instance_details_qty_not_in_use,
                    'qty_missing' => $get_instance_details_qty_missing,
                    'qty_shifted' => $get_instance_details_qty_shifted
                );

                $difference = (int)$get_instance_details->qty_value - (int)$update_details->quantity_verified;         
                $difference_value = abs($difference);

                if(!empty($get_instance_details_qty_ok)){
                    if($operation == 'addition'){  
                        // $update_details_data['qty_ok'] = (int)$get_instance_details_qty_ok + (int)$get_instance_details_qty_value;
                        // $update_item_details_data['qty_ok'] = (int)$get_instance_details_qty_ok + (int)$get_instance_details_qty_value; 
                        $update_details_data['qty_ok'] = (int)$get_instance_details_qty_ok + (int)$difference_value;
                        $update_item_details_data['qty_ok'] = (int)$get_instance_details_qty_ok + (int)$difference_value; 
                    }else{
                        // $update_details_data['qty_ok'] = (int)$get_instance_details_qty_ok - (int)$get_instance_details_qty_value;
                        // $update_item_details_data['qty_ok'] = (int)$get_instance_details_qty_ok - (int)$get_instance_details_qty_value;
                        $update_details_data['qty_ok'] = (int)$get_instance_details_qty_ok - (int)$difference_value;
                        $update_item_details_data['qty_ok'] = (int)$get_instance_details_qty_ok - (int)$difference_value;
                    }
                }
                if(!empty($get_instance_details_qty_damaged)){
                    if($operation == 'addition'){  
                        $update_details_data['qty_damaged'] = (int)$get_instance_details_qty_damaged + (int)$difference_value;
                        $update_item_details_data['qty_damaged'] = (int)$get_instance_details_qty_damaged + (int)$difference_value;
                    }else{
                        $update_details_data['qty_damaged'] = (int)$get_instance_details_qty_damaged - (int)$difference_value;
                        $update_item_details_data['qty_damaged'] = (int)$get_instance_details_qty_damaged - (int)$difference_value;
                    }
                }
                if(!empty($get_instance_details_qty_scrapped)){
                    if($operation == 'addition'){  
                        $update_details_data['qty_scrapped'] = (int)$get_instance_details_qty_scrapped + (int)$difference_value;
                        $update_item_details_data['qty_scrapped'] = (int)$get_instance_details_qty_scrapped + (int)$difference_value;
                    }else{
                        $update_details_data['qty_scrapped'] = (int)$get_instance_details_qty_scrapped - (int)$difference_value;
                        $update_item_details_data['qty_scrapped'] = (int)$get_instance_details_qty_scrapped - (int)$difference_value;
                    }
                    // $update_details_data['qty_scrapped'] = - $get_instance_details_qty_scrapped;
                }
                if(!empty($get_instance_details_qty_not_in_use)){
                    if($operation == 'addition'){  
                        $update_details_data['qty_not_in_use'] = (int)$get_instance_details_qty_not_in_use + (int)$difference_value;
                        $update_item_details_data['qty_not_in_use'] = (int)$get_instance_details_qty_not_in_use + (int)$difference_value;
                    }else{
                        $update_details_data['qty_not_in_use'] = (int)$get_instance_details_qty_not_in_use - (int)$difference_value;
                        $update_item_details_data['qty_not_in_use'] = (int)$get_instance_details_qty_not_in_use - (int)$difference_value;
                    }
                    // $update_details_data['qty_not_in_use'] = - $get_instance_details_qty_not_in_use;
                }
                if(!empty($get_instance_details_qty_missing)){
                    if($operation == 'addition'){  
                        $update_details_data['qty_missing'] = (int)$get_instance_details_qty_missing + (int)$difference_value;
                        $update_item_details_data['qty_missing'] = (int)$get_instance_details_qty_missing + (int)$difference_value;
                    }else{
                        $update_details_data['qty_missing'] = (int)$get_instance_details_qty_missing - (int)$difference_value;
                        $update_item_details_data['qty_missing'] = (int)$get_instance_details_qty_missing - (int)$difference_value;
                    }
                    // $update_details_data['qty_missing'] = - $get_instance_details_qty_missing;
                }
                if(!empty($get_instance_details_qty_shifted)){
                    if($operation == 'addition'){  
                        $update_details_data['qty_shifted'] = (int)$get_instance_details_qty_shifted + (int)$difference_value;
                        $update_item_details_data['qty_shifted'] = (int)$get_instance_details_qty_shifted + (int)$difference_value;
                    }else{
                        $update_details_data['qty_shifted'] = (int)$get_instance_details_qty_shifted - (int)$difference_value;
                        $update_item_details_data['qty_shifted'] = (int)$get_instance_details_qty_shifted - (int)$difference_value;
                    }
                    // $update_details_data['qty_shifted'] = - $get_instance_details_qty_shifted;
                }   



                $difference = (int)$get_instance_details->qty_value - (int)$update_details->quantity_verified;         
                $difference = abs($difference);
        

                if($operation == 'addition'){                 
                    // $quantity_verified_value = (int)$get_item_details->quantity_verified + (int)$get_instance_details_qty_value;
                    $quantity_verified_value = (int)$get_item_details->quantity_verified + (int)$difference_value;
                }else{
                    // $quantity_verified_value = (int)$get_item_details->quantity_verified - (int)$get_instance_details_qty_value;
                    $quantity_verified_value = (int)$get_item_details->quantity_verified - (int)$difference_value;
                }

                $update_details_data['quantity_verified'] = $quantity_verified_value;
                $update_item_details_data['quantity_verified'] = $quantity_verified_value;


            

            
                $current_date_time = date('Y-m-d H:i:s');

                if($operation == 'addition'){  
                    $quantity_verified = (int)$update_details->quantity_verified;
                    $actual_quantity_verified = $update_details->quantity_verified;
                }else{
                    $quantity_verified = (int)$update_details->quantity_verified;
                    $actual_quantity_verified = -$update_details->quantity_verified;
                }

                $actual_quantity_verified = -$update_details->quantity_verified;

                $update_details_data['quantity_verified'] = $quantity_verified;

                $verification_status = $update_details->quantity_as_per_invoice <= $update_details->quantity_verified ? "Verified":"Not-Verified";
                $update_details_data['verification_status'] = $verification_status;
                $update_details_data['verified_datetime'] = $current_date_time;
                $update_details_data['updatedat'] = $current_date_time;

                if($update_details->verification_remarks!='')
                {
                    $verification_remarks = $get_item_details->verification_remarks != '' ? $get_item_details->verification_remarks.' || '.$update_details->verification_remarks:$update_details->verification_remarks;
                    $update_details_data['verification_remarks']= $verification_remarks;
                }
                

                

                // $update_item_details_data['instance_count'] = (int)$get_item_details->instance_count+1;
                $update_item_details_data['instance_count'] = (int)$get_item_details->instance_count+2;
                
            



                $verify=$this->tasks->update_data($projectname,$update_item_details_data,$condition);

                
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
                $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts',$update_details_data);


                //Add into Verified Products Log Table        
                $qty_scrapped_value = 0;
                $qty_damaged_value = 0;
                $qty_ok_value = 0;
                $qty_not_in_use_value = 0;
                $qty_missing_value = 0;
                $qty_shifted_value = 0;
                if($update_details->item_scrap_condition =='qty_ok')
                {
                $qty_ok_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_damaged')
                {
                $qty_damaged_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_scrapped')
                {
                $qty_scrapped_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_not_in_use')
                {
                $qty_not_in_use_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_missing')
                {
                $qty_missing_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_shifted')
                {
                $qty_shifted_value = $quantity_verified;
                }
                



                
                // }

                

                $company_id = $get_project_details[0]->company_id;
                $new_location_verified = $update_details->new_location_verified;
                $location_id = $get_project_details[0]->project_location;
                $entity_code =  $get_project_details[0]->entity_code;
                $project_id = $get_project_details[0]->id;
                $project_name = $get_project_details[0]->project_name;
                $original_table_name = $get_project_details[0]->original_table_name;

                $current_date_time = date('Y-m-d H:i:s');
                $verification_status = $update_details->quantity_as_per_invoice <= $update_details->quantity_verified ? "Verified":"Not-Verified";

                $mode_of_verification = $update_details->mode_of_verification;
                $update_details->mode_of_verification= $mode_of_verification;

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
                $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts_log',$verifiedproducts_array);



                //Add into Verified Products Log Table        
                $qty_scrapped_value = 0;
                $qty_damaged_value = 0;
                $qty_ok_value = 0;
                $qty_not_in_use_value = 0;
                $qty_missing_value = 0;
                $qty_shifted_value = 0;
                if($update_details->item_scrap_condition =='qty_ok')
                {
                $qty_ok_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_damaged')
                {
                $qty_damaged_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_scrapped')
                {
                $qty_scrapped_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_not_in_use')
                {
                $qty_not_in_use_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_missing')
                {
                $qty_missing_value = $quantity_verified;
                }
                if($update_details->item_scrap_condition =='qty_shifted')
                {
                $qty_shifted_value = $quantity_verified;
                }
                

        

            






                
                
                if($verifiedproducts_result)
                {

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
                    $verifiedproducts_result = $this->tasks->insert_data('verifiedproducts',$verifiedproducts_array);

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

        ?>