<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProjectDetails extends CI_Controller {

	public function __construct() {
		parent::__construct();		
        $this->load->model('Tasks_model','tasks');	
        $this->load->model('Login_model','plancycle');

		$this->load->library('form_validation');
		$this->load->helper('function_helper','helper');
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
            if($session['main_role'] !='5'){
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
	}

	public function one($id)
	{
		$condition=array('id'=>$id);
		$projects=$this->tasks->get_data('company_projects',$condition);	
		

		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		foreach($projects as $project)
		{
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
			$condition2=array('id'=>$project->company_id);
			$company=$this->tasks->get_data('company',$condition2);

			$companylocation=$this->tasks->get_data('company_locations',array('id'=>$project->project_location));
			$project->company_name=$company[0]->company_name;
			$project->project_location=$companylocation[0]->location_name;
		}



		// OverallProjectStatusChart Start From Here
		$tag_verified=$this->db->select('count(*) as tag_verified')->where(array('tag_status_y_n_na'=>'Y',"verification_status"=>'Verified'))->get($project_name)->row()->tag_verified;
		$tag_not_verified=$this->db->select('count(*) as tag_not_verified')->where(array('tag_status_y_n_na'=>'Y',"verification_status !="=>'Verified'))->get($project_name)->row()->tag_not_verified;
		$non_tag_verified=$this->db->select('count(*) as non_tag_verified')->where(array('tag_status_y_n_na'=>'N',"verification_status"=>'Verified'))->get($project_name)->row()->non_tag_verified;
		$non_tag_not_verified=$this->db->select('count(*) as non_tag_not_verified')->where(array('tag_status_y_n_na'=>'N',"verification_status !="=>'Verified'))->get($project_name)->row()->non_tag_not_verified;
		
		$OverallProjectStatusChart_Verified_dataPoints = array(
			array("label"=> "Tagged", "y"=> $tag_verified),
			array("label"=> "Not Tagged", "y"=> $non_tag_verified)			
		);
		$OverallProjectStatusChart_NotVerified_dataPoints = array(
			array("label"=> "Tagged", "y"=> $tag_not_verified),
			array("label"=> "Not Tagged", "y"=> $non_tag_not_verified)
		);
		$data['OverallProjectStatusChart_Verified_dataPoints']=$OverallProjectStatusChart_Verified_dataPoints;
		$data['OverallProjectStatusChart_NotVerified_dataPoints']=$OverallProjectStatusChart_NotVerified_dataPoints;
		
		$listing=getTagUntag($projects[0]->project_name);
		$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($projects[0]->project_name)));    

		$listing=getTagUntag($projects[0]->project_name);
		$cat=getTagUntagCategories($projects[0]->project_name);

		$allcategories=getCategories($projects[0]->project_name);

		$ttv=0;
		$ttt=0;
		$tntv=0;
		$tntt=0;
		$tutv=0;
		$tutt=0;
		$tamt=0;


		$my_array = array();
		foreach($allcategories['categories'] as $alcat){

			$overallverified=0;
			$overalltotal=0;
		


			if(!empty($cat['tagged']) && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
			{
				$overall=0;
				$process=0;

				
				if(count($cat['tagged'])>0)
				{
					$tg=0;
					foreach($cat['tagged'] as $ct)
					{ 
						if($ct['category']==$alcat->item_category)
						{
							$overall=$overall+$ct['percentage'];
							$overallverified=$overallverified+$ct['verified'];
							$overalltotal=$overalltotal+$ct['total'];
							$ttv=$ttv+$ct['verified'];
							$ttt=$ttt+$ct['total'];
							$ct['percentage'] ==100? $process++ : $process;

						}
					}
				}
			}

			if(!empty($cat['untagged']) && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD'))
			{

				$overall=0;
				$process=0;
				if(count($cat['untagged'])>0)
				{
					$ut=0;
					foreach($cat['untagged'] as $ct)
					{ 
						if($ct['category']==$alcat->item_category)
						{
							$overall=$overall+$ct['percentage'];
							$overallverified=$overallverified+$ct['verified'];
							$overalltotal=$overalltotal+$ct['total'];
							$tntv=$tntv+$ct['verified'];
							$tntt=$tntt+$ct['total'];
							$ct['percentage'] ==100? $process++ : $process;
						}
					}
				}
			}



			if(!empty($cat['unspecified']) && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
			{
				$overall=0;
				$process=0;
				if(count($cat['unspecified'])>0)
				{
					$us=0;
					foreach($cat['unspecified'] as $ct)
					{ 
						if($ct['category']==$alcat->item_category)
						{
							$overall=$overall+$ct['percentage'];
							$overallverified=$overallverified+$ct['verified'];
							$overalltotal=$overalltotal+$ct['total'];
							$tutv=$tutv+$ct['verified'];
							$tutt=$tutt+$ct['total'];
							$ct['percentage'] ==100? $process++ : $process;
						}
					}
				}
			}
            $my_array[$alcat->item_category]['percentage'] = round(($overallverified/$overalltotal)*100,2);
            $my_array[$alcat->item_category]['overallverified'] = $overallverified;
            $my_array[$alcat->item_category]['overalltotal'] = $overalltotal;
		}


		
		$LineItemBreakupChart_Verified_dataPoints1 = array();
		$LineItemBreakupChart_NotVerified_dataPoints2 = array();
		$array = array("1","2","3");
		$array1 = array("10","20","30");
		$count = 0;
		foreach($my_array as $my_array_key=>$my_array_value){		
			
		$LineItemBreakupChart_Verified_dataPoints1[] = array("label"=> $my_array_key, "y"=> $my_array_value['percentage'],"customText" => $my_array_value['overallverified']);

		$LineItemBreakupChart_NotVerified_dataPoints2[] = array("label"=> $my_array_key, "y"=> 100-(int)$my_array_value['percentage'],"customText" => $my_array_value['overalltotal']-$my_array_value['overallverified']); 
			/*
			$LineItemBreakupChart_Verified_dataPoints1[] = array("label"=> $my_array_key, "y"=> $my_array_value['percentage']);
			$LineItemBreakupChart_NotVerified_dataPoints2[] = array("label"=> $my_array_key, "y"=> 100-(int)$my_array_value['percentage']); */
			$count++; 
		}

		
		$data['LineItemBreakupChart_Verified_dataPoints1']=$LineItemBreakupChart_Verified_dataPoints1;
		$data['LineItemBreakupChart_NotVerified_dataPoints2']=$LineItemBreakupChart_NotVerified_dataPoints2;
		
		$filled = ($ttt+$tntt+$tutt) > 0 ? round((($ttv+$tntv+$tutv)/($ttt+$tntt+$tutt))*100,2).'':'0';

		
		$LineItemBreakup_DonutChart_dataPoints = array( 
			array("label"=>"Verified", "symbol" => "Verified","y"=>$filled),
			array("label"=>"Not Verified", "symbol" => "Not-Verified","y"=>100-$filled),
		);
		$data['LineItemBreakup_DonutChart_dataPoints']=$LineItemBreakup_DonutChart_dataPoints;

		

		// echo '<pre>';
		// print_r($allcategories['categories']);
		// echo '</pre>';
		// exit();


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
				// $my_array1[$alcat->item_category]['overallverified'] = getmoney_format(round(($overallverified/100000),2));
				// $my_array1[$alcat->item_category]['overalltotal'] = getmoney_format(round(($overalltotal/100000),2));

				$my_array1[$alcat->item_category]['overallverified'] = round(($overallverified/100000));//getmoney_format(round(($overallverified/100000),2));
				$my_array1[$alcat->item_category]['overalltotal'] = round(($overalltotal/100000)); //getmoney_format(round(($overalltotal/100000),2));
			}
		}

	

		$filled = ($ttt+$tntt+$tutt) > 0 ? round((($ttv+$tntv+$tutv)/($ttt+$tntt+$tutt))*100,2).' 0 ': '0 ';

		// 	$AmountwiseBreakupChart_dataPoints1 = array();
		// 	$AmountwiseBreakupChart_dataPoints2 = array();
		// 	foreach($my_array1 as $my_array1_key=>$my_array1_value){			
		// 		$AmountwiseBreakupChart_dataPoints1[] = array("label"=> $my_array1_key, "y"=> $my_array1_value['percentage']);
		// 		$AmountwiseBreakupChart_dataPoints2[] = array("label"=> $my_array1_key, "y"=> 100-(int)$my_array1_value['percentage']);
		// 	}
		
		// 	$data['AmountwiseBreakupChart_dataPoints1']=$AmountwiseBreakupChart_dataPoints1;
		// 	$data['AmountwiseBreakupChart_dataPoints2']=$AmountwiseBreakupChart_dataPoints2;
			

			
		// 	$calculation = 100-floatval($filled);
		// 	$y_value = number_format((float)$calculation, 2, '.', '');
			
			
		//    $AmountwiseBreakup_DonutChart_dataPoints = array( 
		// 	   array("label"=>"Verified", "symbol" => "Verified","y"=>round((float)$filled)),
		// 	   array("label"=>"Not Verified", "symbol" => "Not Verified","y"=>round((float)$y_value)),
		//    );


		// 	$data['AmountwiseBreakup_DonutChart_dataPoints']=$AmountwiseBreakup_DonutChart_dataPoints;


		// amount wise breakup chart code end here4


		// echo '<pre>my_array1 ';
		// print_r($my_array1);
		// echo '</pre>';
		// exit();

	    $AmountwiseBreakupChart_dataPoints1 = array();
        $AmountwiseBreakupChart_dataPoints2 = array();
        foreach($my_array1 as $my_array1_key=>$my_array1_value){          
            // Cast the values to float to ensure they are numeric before calculation
            $verified_amount = (float) str_replace(',', '', $my_array1_value['overallverified']);
            $total_amount = (float) str_replace(',', '', $my_array1_value['overalltotal']);

			$calculation = $my_array1_value['overalltotal']-$my_array1_value['overallverified'];
			// echo '<pre>calculation ';
			// print_r($calculation);
			// echo '</pre>';
			// exit();
            $AmountwiseBreakupChart_dataPoints1[] = array("label"=> $my_array1_key, "y"=> $verified_amount,"customText" => round($my_array1_value['overallverified'],2)." Lac");

            $AmountwiseBreakupChart_dataPoints2[] = array("label"=> $my_array1_key, "y"=> ($total_amount - $verified_amount), "customText" => round($calculation,2)." Lac");
        }

		// exit();
   
        $data['AmountwiseBreakupChart_dataPoints1']=$AmountwiseBreakupChart_dataPoints1;
        $data['AmountwiseBreakupChart_dataPoints2']=$AmountwiseBreakupChart_dataPoints2;
       
 
       
       $calculation = 100-floatval($filled);
       $y_value = number_format((float)$calculation, 2, '.', '');
       
       
      $AmountwiseBreakup_DonutChart_dataPoints = array( 
          array("label"=>"Verified", "symbol" => "Verified","y"=>round((float)$filled)),
          array("label"=>"Not Verified", "symbol" => "Not Verified","y"=>round((float)$y_value)),
      );


      $data['AmountwiseBreakup_DonutChart_dataPoints']=$AmountwiseBreakup_DonutChart_dataPoints;










		$project_details=$this->db->select('*')->get($project_name)->result();


		 $this->db->select($project_name.'.*,users.firstName');
		$this->db->from($project_name);
		$this->db->join('users', $project_name.'.verified_by = users.id'); 
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
		foreach($project_details as $project_details_key=>$project_details_value){
			if(!empty($project_details_value->verified_by)){
				$project_details_array[$project_details_value->firstName][$project_details_value->item_category][] = 1;
				$project_details_array2[$project_details_value->item_category][$project_details_value->firstName][] = 1;
			}
			if(!in_array($project_details_value->item_category,$verifier_users_array)){
				$verifier_users_array[] = $project_details_value->item_category;
			}
			if(!in_array($project_details_value->firstName,$category_array)){
				$category_array[] = $project_details_value->firstName;
			}
			$user_wise_count[$project_details_value->firstName][] = 1;

			
		}


	


		$ResourcewiseUtilizationChart_datapoint = array();
		$ResourcewiseUtilization_DonutChart_dataPoints_array = array();
		$count_value = 0;

	


		foreach($project_details_array as $project_details_array_key=>$project_details_array_value){

			$ResourcewiseUtilizationChart_dataPoints1 = array();
			foreach($verifier_users_array as $verifier_users_array_Key=>$verifier_users_array_value){
				if(isset($project_details_array[$project_details_array_key][$verifier_users_array_value])){
					$ResourcewiseUtilizationChart_dataPoints1[] = array("label"=> $verifier_users_array_value, "y"=> count($project_details_array[$project_details_array_key][$verifier_users_array_value]));
					$ResourcewiseUtilization_DonutChart_dataPoints_array[$verifier_users_array_value][] = count($project_details_array[$project_details_array_key][$verifier_users_array_value]);
				}
			}

			

			$ResourcewiseUtilizationChart_datapoint[] = array(
				"type"=> "stackedColumn100",
				"name"=> $project_details_array_key,
				"showInLegend"=>true,
				"yValueFormatString"=>"#,##0 ",
				"dataPoints" => $ResourcewiseUtilizationChart_dataPoints1,
			);
			$count_value++;
		}
		



		$data['ResourcewiseUtilizationChart_datapoint'] = $ResourcewiseUtilizationChart_datapoint;

		$ResourcewiseUtilization_DonutChart_dataPoints_array1 = array();
		foreach($ResourcewiseUtilization_DonutChart_dataPoints_array as $ResourcewiseUtilization_DonutChart_dataPoints_array_key=>$ResourcewiseUtilization_DonutChart_dataPoints_array_value){
			
			$ResourcewiseUtilization_DonutChart_dataPoints_array1[] = array("label"=>$ResourcewiseUtilization_DonutChart_dataPoints_array_key, "symbol" => $ResourcewiseUtilization_DonutChart_dataPoints_array_key,"y"=>array_sum($ResourcewiseUtilization_DonutChart_dataPoints_array_value));

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
			foreach($user_wise_count as $user_wise_count_key=>$user_wise_count_value){

				
				$ResourcewiseUtilizationChart_dataPoints1[] = array(
						"label"=> $user_wise_count_key, 
						"symbol"=> $user_wise_count_key, 
						"y"=> count($user_wise_count_value),
					);
			}
			
			

		
		$data['ResourcewiseUtilization_DonutChart_dataPoints']=$ResourcewiseUtilizationChart_dataPoints1;
		

	
		$data['projects']=$projects;
		$data['page_title']="Project Detail";

		// $this->load->view('project_detail3',$data);
		$this->load->view('ProjectDetailsOneView',$data);
		
	}

	public function two($id)
	{
		$condition=array('id'=>$id);
		$projects=$this->tasks->get_data('company_projects',$condition);	
		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		foreach($projects as $project)
		{
			$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
			$getprojectdetails=$this->tasks->projectdetail($project_name);
			// echo $this->db->last_query();
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
			$condition2=array('id'=>$project->company_id);
			$company=$this->tasks->get_data('company',$condition2);
			$companylocation=$this->tasks->get_data('company_locations',array('id'=>$project->project_location));
			$project->company_name=$company[0]->company_name;
			$project->project_location=$companylocation[0]->location_name;
		}



		$listing=getTagUntag($projects[0]->project_name);
		$cat=getTagUntagCategories($projects[0]->project_name);
		$allcategories=getCategories($projects[0]->project_name);
		

		// print_r($projects);
		$data['projects']=$projects;
		$data['page_title']="Dashboard";

		$this->load->view('ProjectDetailsTwoView',$data);
		
	}

	public function three($id)
	{
		$condition=array('id'=>$id);
		$projects=$this->tasks->get_data('company_projects',$condition);	

		$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
		$new_pattern = array("_", "_", "");
		foreach($projects as $project)
		{
			$project_name=strtolower(preg_replace($old_pattern, $new_pattern , trim($project->project_name)));
			$getprojectdetails=$this->tasks->projectdetail($project_name);
			// echo $this->db->last_query();
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
			$condition2=array('id'=>$project->company_id);
			$company=$this->tasks->get_data('company',$condition2);
			$companylocation=$this->tasks->get_data('company_locations',array('id'=>$project->project_location));
			$project->company_name=$company[0]->company_name;
			$project->project_location=$companylocation[0]->location_name;
		}



		$listing=getTagUntag($projects[0]->project_name);
		$cat=getTagUntagCategories($projects[0]->project_name);
		$allcategories=getCategories($projects[0]->project_name);


		//HArdik Code Start From HErer

		if($projects[0]->project_type=='TG')
		{

		}
		if($projects[0]->project_type=='NT')
		{

		}

		if($projects[0]->project_type=='UN')
		{

		}

		if($projects[0]->project_type=='CD')
		{
			if($listing['ytotal']!=0 && $listing['ntotal']!=0 && $listing['natotal']!=0){

			}else if($listing['ytotal']!=0 && $listing['ntotal']!=0 && $listing['natotal']==0){

			}else if($listing['ytotal']!=0 && $listing['ntotal']==0 && $listing['natotal']==0){
			
			}else if($listing['ytotal']!=0 && $listing['ntotal']==0 && $listing['natotal']!=0){

			}else if($listing['ytotal']==0 && $listing['ntotal']==0 && $listing['natotal']!=0){

			}else if($listing['ytotal']==0 && $listing['ntotal']!=0 && $listing['natotal']!=0){

			}else if($listing['ytotal']==0 && $listing['ntotal']!=0 && $listing['natotal']==0){

			}
		}

		
			$tcatlabels=array();
			$tcatdata=array();
			$atcatlabels=array();
			$atcatdata=array();
			$cnt1=0;
			$tcattotalpercentage=0;
			$atcattotalpercentage=0;
			$taggedOverall=[];
			$totaltaggedOverall=[];
			$amounttaggedOverall=[];
			$amounttotaltaggedOverall=[];


			$taggedpieChart = array();
			$amounttaggedpieChart = array();
			$count_zero = 0;
			$count =1;
			if(count($cat['tagged'])>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD')){		//taggedpieChart	//amounttaggedpieChart
				foreach($cat['tagged'] as $tcat)
				{
					$taggedOverall[$tcat['category']]=$tcat['verified'];
					$totaltaggedOverall[$tcat['category']]=$tcat['total'];
					$amounttaggedOverall[$tcat['category']]=$tcat['verifiedamount'];
					$amounttotaltaggedOverall[$tcat['category']]=$tcat['totalamount'];
					$tcattotalpercentage=$tcattotalpercentage+$tcat['percentage'];
					$atcattotalpercentage=$atcattotalpercentage+$tcat['amountpercentage'];
					array_push($tcatlabels,$tcat['category'].' ('.round(($tcat['percentage']/count($cat['tagged'])),2).' %)');
					array_push($tcatdata,round(($tcat['percentage']/count($cat['tagged'])),2));
					array_push($atcatlabels,$tcat['category'].' ('.$tcat['amountpercentage'].' %)');
					array_push($atcatdata,$tcat['amountpercentage']);
					$cnt1++;

					$taggedpieChart[$count_zero]['label'] = $tcat['category'];
					$taggedpieChart[$count_zero]['y'] = round(($tcat['percentage']/count($cat['tagged'])),2);
					$taggedpieChart[$count_zero]['id'] = $count;


					$amounttaggedpieChart[$count_zero]['label'] = $tcat['category'];
					$amounttaggedpieChart[$count_zero]['y'] = $tcat['amountpercentage'];;
					$amounttaggedpieChart[$count_zero]['id'] = $count;


					$count++;
					$count_zero++;
				}
			}


			$data['taggedpieChart'] = $taggedpieChart; 
			$data['amounttaggedpieChart'] = $amounttaggedpieChart; 
			// echo '<pre>tcatlabels ::';
			// print_r($tcatlabels);
			// echo '</pre>';
			// echo '<pre>tcatdata ::';
			// print_r($tcatdata);
			// echo '</pre>';
			// exit();
			// exit();

			$utcatlabels=[];
			$utcatdata=[];
			$autcatlabels=[];
			$autcatdata=[];
			$cnt2=0;
			$utcattotalpercentage=0;
			$autcattotalpercentage=0;
			$untaggedOverall=[];
			$totaluntaggedOverall=[];
			$amountuntaggedOverall=[];
			$amounttotaluntaggedOverall=[];
			if(count($cat['untagged'])>0 && ($projects[0]->project_type=='NT' || $projects[0]->project_type=='CD')){	//untaggedpieChart	//amountuntaggedpieChart
				foreach($cat['untagged'] as $utcat)
				{
					$untaggedOverall[$utcat['category']]=$utcat['verified'];
					$totaluntaggedOverall[$utcat['category']]=$utcat['total'];
					$amountuntaggedOverall[$utcat['category']]=$utcat['verifiedamount'];
					$amounttotaluntaggedOverall[$utcat['category']]=$utcat['totalamount'];
					$utcattotalpercentage+=$utcat['percentage'];
					$autcattotalpercentage+=$utcat['amountpercentage'];
					array_push($utcatlabels,$utcat['category'].' ('.round(($utcat['percentage']/count($cat['untagged'])),2).' %)');
					array_push($utcatdata,round(($utcat['percentage']/count($cat['untagged'])),2));
					array_push($autcatlabels,$utcat['category'].' ('.$utcat['amountpercentage'].' %)');
					array_push($autcatdata,$utcat['amountpercentage']);
					$cnt2++;
				}
			}

			$uscatlabels=array();
			$uscatdata=array();
			$auscatlabels=array();
			$auscatdata=array();
			$cnt3=0;
			$uscattotalpercentage=0;
			$auscattotalpercentage=0;
			$ustaggedOverall=[];
			$totalustaggedOverall=[];
			$amountustaggedOverall=[];
			$amounttotalustaggedOverall=[];
			if(count($cat['unspecified'])>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD')){			//unspecifiedpieChart	//amountunspecifiedpieChart
				foreach($cat['unspecified'] as $uscat)
				{
					$untaggedOverall[$uscat['category']]=$uscat['verified'];
					$totalustaggedOverall[$uscat['category']]=$uscat['total'];
					$amountustaggedOverall[$uscat['category']]=$uscat['verifiedamount'];
					$amounttotalustaggedOverall[$uscat['category']]=$uscat['totalamount'];
					$uscattotalpercentage+=$uscat['percentage'];
					$auscattotalpercentage+=$uscat['amountpercentage'];
					array_push($uscatlabels,$uscat['category'].' ('.round(($uscat['percentage']/count($cat['unspecified'])),2).' %)');
					array_push($uscatdata,round(($uscat['percentage']/count($cat['unspecified'])),2));
					array_push($auscatlabels,$uscat['category'].' ('.$uscat['amountpercentage'].' %)');
					array_push($auscatdata,$uscat['amountpercentage']);
					$cnt3++;
				}
			}

			$ResourcewiseUtilizationChart = array();
			$count_zero = 0;
			$count =1;

			$yverifiernames=array();
			$yverifierperc=array();
			if($listing['ytotal']>0 && ($projects[0]->project_type=='TG' || $projects[0]->project_type=='CD'))
			{		//resourcetaggedpieChart	//resourceuntaggedpieChart
				foreach($listing['projectverifiers'] as $list)
				{
				
					array_push($yverifiernames,get_UserName($list->user_id).' ('.round(($list->usertagged/$listing['ytotal'])*100,2).' %)');
					array_push($yverifierperc,round(($list->usertagged/$listing['ytotal'])*100,2));


					$ResourcewiseUtilizationChart[$count_zero]['label'] = get_UserName($list->user_id);
					$ResourcewiseUtilizationChart[$count_zero]['y'] = round(($list->usertagged/$listing['ytotal'])*100,2);
					$ResourcewiseUtilizationChart[$count_zero]['id'] = $count;
					$count++;
					$count_zero++;

				}

			}

			$data['ResourcewiseUtilizationChart'] = $ResourcewiseUtilizationChart;

			$yverifiernames=array();
			$yverifierperc=array();
			if($listing['natotal']>0 && ($projects[0]->project_type=='UN' || $projects[0]->project_type=='CD'))
			{		//resourceunspecifiedpieChart
				foreach($listing['projectverifiers'] as $list)
				{
					array_push($yverifiernames,get_UserName($list->user_id).' ('.round(($list->userunspecified/$listing['natotal'])*100,2).' %)');
					array_push($yverifierperc,round(($list->userunspecified/$listing['natotal'])*100,2));
				
				}
			}
			




			$merger=[];
			$totalmerger=[];
			$amountmerger=[];
			$amounttotalmerger=[];
			if(count($cat['tagged'])>0 && count($cat['untagged'])>0 && count($cat['unspecified'])>0)
			{
				foreach (array_keys($taggedOverall + $untaggedOverall+$ustaggedOverall) as $item) {
				$merger[$item] = (isset($taggedOverall[$item]) ? $taggedOverall[$item] : 0) + (isset($untaggedOverall[$item]) ? $untaggedOverall[$item] : 0) + (isset($ustaggedOverall[$item]) ? $ustaggedOverall[$item] : 0);
				}
				foreach (array_keys($totaltaggedOverall + $totaluntaggedOverall+$ustaggedOverall) as $item) {
					$totalmerger[$item] = (isset($totaltaggedOverall[$item]) ? $totaltaggedOverall[$item] : 0) + (isset($totaluntaggedOverall[$item]) ? $totaluntaggedOverall[$item] : 0) + (isset($totalustaggedOverall[$item]) ? $totalustaggedOverall[$item] : 0);
				}
				foreach (array_keys($amounttaggedOverall + $amountuntaggedOverall+$amountustaggedOverall) as $item) {
					$amountmerger[$item] = (isset($amounttaggedOverall[$item]) ? $amounttaggedOverall[$item] : 0) + (isset($amountuntaggedOverall[$item]) ? $amountuntaggedOverall[$item] : 0) + (isset($amountustaggedOverall[$item]) ? $amountustaggedOverall[$item] : 0);
				}
				foreach (array_keys($amounttotaltaggedOverall + $amounttotaluntaggedOverall+$amountustaggedOverall) as $item) {
					$amounttotalmerger[$item] = (isset($amounttotaltaggedOverall[$item]) ? $amounttotaltaggedOverall[$item] : 0) + (isset($amounttotaluntaggedOverall[$item]) ? $amounttotaluntaggedOverall[$item] : 0) + (isset($amounttotalustaggedOverall[$item]) ? $amounttotalustaggedOverall[$item] : 0);
				}
				
			}
			else if(count($cat['tagged'])>0 && count($cat['untagged'])>0 && count($cat['unspecified'])==0)
			{
				foreach (array_keys($taggedOverall + $untaggedOverall) as $item) {
					$merger[$item] = (isset($taggedOverall[$item]) ? $taggedOverall[$item] : 0) + (isset($untaggedOverall[$item]) ? $untaggedOverall[$item] : 0);
				}
				foreach (array_keys($totaltaggedOverall + $totaluntaggedOverall) as $item) {
					$totalmerger[$item] = (isset($totaltaggedOverall[$item]) ? $totaltaggedOverall[$item] : 0) + (isset($totaluntaggedOverall[$item]) ? $totaluntaggedOverall[$item] : 0);
				}
				foreach (array_keys($amounttaggedOverall + $amountuntaggedOverall) as $item) {
					$amountmerger[$item] = (isset($amounttaggedOverall[$item]) ? $amounttaggedOverall[$item] : 0) + (isset($amountuntaggedOverall[$item]) ? $amountuntaggedOverall[$item] : 0);
				}
				foreach (array_keys($amounttotaltaggedOverall + $amounttotaluntaggedOverall) as $item) {
					$amounttotalmerger[$item] = (isset($amounttotaltaggedOverall[$item]) ? $amounttotaltaggedOverall[$item] : 0) + (isset($amounttotaluntaggedOverall[$item]) ? $amounttotaluntaggedOverall[$item] : 0);
				}
			}
			else if(count($cat['tagged'])>0 && count($cat['untagged'])==0 && count($cat['unspecified'])>0)
			{
				foreach (array_keys($taggedOverall + $ustaggedOverall) as $item) {
					$merger[$item] = (isset($taggedOverall[$item]) ? $taggedOverall[$item] : 0) + (isset($ustaggedOverall[$item]) ? $ustaggedOverall[$item] : 0);
				}
				foreach (array_keys($totaltaggedOverall + $ustaggedOverall) as $item) {
					$totalmerger[$item] = (isset($totaltaggedOverall[$item]) ? $totaltaggedOverall[$item] : 0) + (isset($totalustaggedOverall[$item]) ? $totalustaggedOverall[$item] : 0);
				}
				foreach (array_keys($amounttaggedOverall+$amountustaggedOverall) as $item) {
					$amountmerger[$item] = (isset($amounttaggedOverall[$item]) ? $amounttaggedOverall[$item] : 0) + (isset($amountustaggedOverall[$item]) ? $amountustaggedOverall[$item] : 0);
				}
				foreach (array_keys($amounttotaltaggedOverall +$amountustaggedOverall) as $item) {
					$amounttotalmerger[$item] = (isset($amounttotaltaggedOverall[$item]) ? $amounttotaltaggedOverall[$item] : 0) + (isset($amounttotalustaggedOverall[$item]) ? $amounttotalustaggedOverall[$item] : 0);
				}
			}
			else if(count($cat['tagged'])==0 && count($cat['untagged'])>0 && count($cat['unspecified'])>0)
			{
				foreach (array_keys($untaggedOverall+$ustaggedOverall) as $item) {
					$merger[$item] = (isset($untaggedOverall[$item]) ? $untaggedOverall[$item] : 0) + (isset($ustaggedOverall[$item]) ? $ustaggedOverall[$item] : 0);
				}
				foreach (array_keys($totaluntaggedOverall+$ustaggedOverall) as $item) {
					$totalmerger[$item] = (isset($totaluntaggedOverall[$item]) ? $totaluntaggedOverall[$item] : 0) + (isset($totalustaggedOverall[$item]) ? $totalustaggedOverall[$item] : 0);
				}
				foreach (array_keys($amountuntaggedOverall+$amountustaggedOverall) as $item) {
					$amountmerger[$item] = (isset($amountuntaggedOverall[$item]) ? $amountuntaggedOverall[$item] : 0) + (isset($amountustaggedOverall[$item]) ? $amountustaggedOverall[$item] : 0);
				}
				foreach (array_keys($amounttotaluntaggedOverall+$amountustaggedOverall) as $item) {
					$amounttotalmerger[$item] = (isset($amounttotaluntaggedOverall[$item]) ? $amounttotaluntaggedOverall[$item] : 0) + (isset($amounttotalustaggedOverall[$item]) ? $amounttotalustaggedOverall[$item] : 0);
				}
			}
			else if(count($cat['tagged'])==0 && count($cat['untagged'])==0 && count($cat['unspecified'])>0)
			{
				$merger=$ustaggedOverall;
				$totalmerger=$totalustaggedOverall;
				$amountmerger=$amountustaggedOverall;
				$amounttotalmerger=$amounttotalustaggedOverall;
			}
			else if(count($cat['tagged'])==0 && count($cat['untagged'])>0 && count($cat['unspecified'])==0)
			{
				$merger=$untaggedOverall;
				$totalmerger=$totaluntaggedOverall;
				$amountmerger=$amountuntaggedOverall;
				$amounttotalmerger=$amounttotaluntaggedOverall;
			}
			else if(count($cat['tagged'])>0 && count($cat['untagged'])==0 && count($cat['unspecified'])==0)
			{
				$merger=$taggedOverall;
				$totalmerger=$totaltaggedOverall;
				$amountmerger=$amounttaggedOverall;
				$amounttotalmerger=$amounttotaltaggedOverall;
			}


			// echo '<pre>merger ::';
			// print_r($merger);
			// echo '</pre>';
			// exit();

			$libarlabels=[];
			$libarvalues=[];
			$amountbarlabels=[];
			$amountbarvalues=[];
			foreach($merger as $key=> $item )
			{
				array_push($libarlabels,$key);
				array_push($libarvalues,round(($item/$totalmerger[$key])*100,2));
				
			}
			foreach($amountmerger as $key=> $item )
			{
				array_push($amountbarlabels,$key);
				array_push($amountbarvalues,round(($item/$amounttotalmerger[$key])*100,2));
			}


			///libarchart		//amountbarchart




		//HArdik Code End Here




		/*
		echo '<pre>listing ';
		print_r($listing);
		echo '</pre>';
		// exit();


		$value1 = round(($listing['yverified']/$listing['ytotal'])*100,2);
		$value2 = 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));
		$data = array($value1,$value2);

		echo '<pre>data ';
		print_r($data);
		echo '</pre>';
		// exit();

		$donutlabel1_cal = round(($listing['yverified']/$listing['ytotal'])*100,2);
		$donutlabel1 = "Tagged (".$donutlabel1_cal." %): ".$listing['yverified']." of ".$listing['ytotal']." Li";

		$donutlabel2_cal = 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));		
		$my_value = $listing['ytotal']-$listing['yverified'];
		$donutlabel2 = "Unverified (".$donutlabel2_cal." %) : ".$my_value.") of ".($listing['ytotal'])." Li";
		*/

		$tagged_filled_percentage = round(($listing['yverified']/$listing['ytotal'])*100,2);
		$unverify_filled_percentage = 100-(round((($listing['yverified'])/($listing['ytotal']))*100,2));

		$tagged_count_value = $listing['yverified'];
		$unverify_count_value = $listing['ytotal']-$listing['yverified'];
		$my_value = $listing['ytotal']-$listing['yverified'];


		$graph_array_data[0]['label'] = "Tagged";
		$graph_array_data[0]['y'] = $tagged_filled_percentage;
		// $graph_array_data[0]['y'] = $tagged_count_value;
		$graph_array_data[0]['id'] = 1;

		
		$graph_array_data[1]['label'] = "Unverified";
		$graph_array_data[1]['y'] = $unverify_filled_percentage;
		// $graph_array_data[1]['y'] = $unverify_count_value;
		$graph_array_data[1]['id'] = 2;

		$data['graph_array_data'] = $graph_array_data;

		

		// echo '<pre>projects :';
		// print_r($projects);
		// echo '</pre>';
		// exit();

	
		$data['projects']=$projects;
		$data['page_title']="Dashboard";

		$this->load->view('ProjectDetailsThreeView',$data);
		
	}
	
}
 