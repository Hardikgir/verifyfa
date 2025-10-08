<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin_controller extends CI_Controller {
	public function __construct() {
		parent::__construct();		
		$this->load->library('session');	
		if (!$this->session->userdata('super_admin_logged_in')) {
            redirect(base_url()."index.php/super-admin-login", 'refresh');
		}
		$this->load->model('Super_admin_model');
		$this->load->model('Registered_user_model');
		
	}

    public function super_admin_dashboard_previous(){
        $data['page_title']="Dashboard";
        $this->load->view("super-admin/dashboard",$data);

    }

	public function super_admin_dashboard(){


		$current_date = date("Y-m-d");
		$last_ten_days_date = date("Y-m-d", strtotime("-10 days")); //for minus

		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$this->db->where('created_at BETWEEN "'. date('Y-m-d', strtotime($last_ten_days_date)). '" and "'. date('Y-m-d', strtotime($current_date)).'"');
		$query=$this->db->get();
		$registered_user_plan = $query->result();

		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();
		
		$now = time(); // or your date as well
		$Original_user_result = array();
		$count = 0;
		$Original_day_count = array();
		$Renewals_day_count = array();
		$Resubscriptions_day_count = array();
		

		foreach($registered_user_plan as $registered_user_plan_key=>$registered_user_plan_value){

			
			if($registered_user_plan_value->category_subscription == 'Original'){
				$your_date = strtotime($registered_user_plan_value->created_at);
				$datediff = $now - $your_date;
				$day_different = round($datediff / (60 * 60 * 24));
				$registered_user_plan[$registered_user_plan_key]->day_difference = $day_different;
				$Original_day_count[] = $day_different;
			}

			if($registered_user_plan_value->category_subscription == 'Renewals'){
				$your_date = strtotime($registered_user_plan_value->created_at);
				$datediff = $now - $your_date;
				$day_different = round($datediff / (60 * 60 * 24));
				$registered_user_plan[$registered_user_plan_key]->day_difference = $day_different;
				$Renewals_day_count[] = $day_different;
			}

			if($registered_user_plan_value->category_subscription == 'Resubscriptions'){
				$your_date = strtotime($registered_user_plan_value->created_at);
				$datediff = $now - $your_date;
				$day_different = round($datediff / (60 * 60 * 24));
				$registered_user_plan[$registered_user_plan_key]->day_difference = $day_different;
				$Resubscriptions_day_count[] = $day_different;
			}


			$count++;
		}

		$current_date = date("Y-m-d");
		$last_ten_days_date = date("Y-m-d", strtotime("-10 days")); //for minus
		

		$startTime = strtotime($last_ten_days_date);
		$endTime = strtotime($current_date);
		
		$count = 0;
		$daycount = 1;
		for($i=$startTime;$i<=$endTime;$i = $i + 86400){
			$thisDate = date('Y-m-d',$i);	

			$Original_user_result[$count]['label'] = "Day ".$daycount.' ('.$thisDate.')';
			$Original_user_result[$count]['y'] = count(array_keys($Original_day_count,$daycount));

			$Renewals_user_result[$count]['label'] = "Day ".$daycount.' ('.$thisDate.')';
			$Renewals_user_result[$count]['y'] = count(array_keys($Renewals_day_count,$daycount));

			$Resubscriptions_user_result[$count]['label'] = "Day ".$daycount.' ('.$thisDate.')';
			$Resubscriptions_user_result[$count]['y'] = count(array_keys($Resubscriptions_day_count,$i));
			$count++;
			$daycount++;
		}
		
		$data['Original_user_result']=$Original_user_result;
		$data['Renewals_user_result']=$Renewals_user_result;
		$data['Resubscriptions_user_result']=$Resubscriptions_user_result;



		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$query=$this->db->get();
		$registered_user_plan = $query->result();


		$this->db->select("id,title");
		$this->db->from('subscription_plan');
		$query=$this->db->get();
		$subscription_plan = $query->result();

		$subscription_plan_array = array();

		$TypeSubscriptionActiveChart_type = array('Original','Renewals','Resubscriptions');


		
		
		foreach($TypeSubscriptionActiveChart_type as $TypeSubscriptionActiveChart_type_key=>$TypeSubscriptionActiveChart_type_value){
			foreach($subscription_plan as $subscription_plan_key=>$subscription_plan_value){
				$subscription_plan_array[$TypeSubscriptionActiveChart_type_value][] = array("label"=> $subscription_plan_value->title, "id"=> $subscription_plan_value->id);
			}
		}

		$OriginalPoints = array();
		foreach($subscription_plan_array['Original'] as $subscription_plan_array_key=>$subscription_plan_array_value){
			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Original" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$OriginalPoints[] = $subscription_plan_array_value;
		}

		

		$RenewalsPoints = array();
		foreach($subscription_plan_array['Renewals'] as $subscription_plan_array_key=>$subscription_plan_array_value){

			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Renewals" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$RenewalsPoints[] = $subscription_plan_array_value;
		}

		$ResubscriptionsPoints = array();
		foreach($subscription_plan_array['Resubscriptions'] as $subscription_plan_array_key=>$subscription_plan_array_value){
			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Resubscriptions" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$ResubscriptionsPoints[] = $subscription_plan_array_value;
		}

		// echo '<pre>';
		// print_r($ResubscriptionsPoints);
		// echo '</pre>';
		// exit(); 

		$data['OriginalPoints'] = $OriginalPoints;
		$data['RenewalsPoints'] = $RenewalsPoints;
		$data['ResubscriptionsPoints'] = $ResubscriptionsPoints;


		$query = $this->db->query('
		SELECT
		COUNT(CASE WHEN balance_refundable BETWEEN 1000 AND 10000 THEN 1 END) AS "1000-10000",
		COUNT(CASE WHEN balance_refundable BETWEEN 10001 AND 20000 THEN 1 END) AS "10001-20000",
		COUNT(CASE WHEN balance_refundable BETWEEN 20001  AND 30000 THEN 1 END) AS "20001-30000",
		COUNT(CASE WHEN balance_refundable BETWEEN 30001 AND 40000 THEN 1 END) AS "30001-40000",
		COUNT(CASE WHEN balance_refundable > 40001 THEN 1 END) AS "Above 40000"
			FROM registred_users_payment
		');
		$registered_user_plan = $query->row();

	
		$my_array = array();
		$count_value = 1;
		foreach($registered_user_plan as $registered_user_plan_key=>$registered_user_plan_value){

			$my_array[] = array(
				'x' => $count_value,
				'y' => $registered_user_plan_value,
				'label' => $registered_user_plan_key,
				'color'=> "#4f81bc"
			);
			$count_value++;
		}
		$data['my_array'] = $my_array;


		$now = time(); 
		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$query=$this->db->get();
		$registered_user_plan = $query->result();

		$in_30_Day = array();
		$in_31_to_60_Day = array();
		$in_61_to_90_Day = array();
		$in_91_to_120_Day = array();

		foreach($registered_user_plan as $registered_user_plan_key=>$registered_user_plan_value){
			$plan_end_date = $registered_user_plan_value->plan_end_date;
			$your_date = strtotime($registered_user_plan_value->plan_end_date);
			
			$datediff = $now - $your_date;
			$day_different = round($datediff / (60 * 60 * 24));

			if($day_different < 0){

				$day_different = abs($day_different);

				if($day_different <= 30){
					$in_30_Day[] = 1;
				}
				if ($day_different >= 31 && $day_different <= 60) {
					$in_31_to_60_Day[] = 1;
				}
				if ($day_different >= 61 && $day_different <= 90) {
					$in_61_to_90_Day[] = 1;
				}
				if ($day_different >= 91 && $day_different <= 120) {
					$in_91_to_120_Day[] = 1;
				}
			}

		}

		// exit();

		$Total_Subscriptions_expiring_in = array(
			'in_30_Day' => count($in_30_Day),
			'in_31_to_60_Day' => count($in_31_to_60_Day),
			'in_61_to_90_Day' => count($in_61_to_90_Day),
			'in_91_to_120_Day' => count($in_91_to_120_Day),
		);


	



		$Total_Active_Subscription_Plans_query = $this->db->query('SELECT * FROM subscription_plan where status = "1"');
		$Total_Active_Subscription_Plans = $Total_Active_Subscription_Plans_query->num_rows();

		$Total_Registrations_query = $this->db->query('SELECT * FROM registered_user_plan');
		$Total_Registrations = $Total_Registrations_query->num_rows();

		$Total_Registered_Active_Subscriptions_query = $this->db->query('SELECT * FROM registered_user_plan WHERE CURDATE() BETWEEN plan_start_date AND plan_end_date');
		$Total_Registered_Active_Subscriptions = $Total_Registered_Active_Subscriptions_query->num_rows();

		$Total_Registrations_Unsubscribed_query = $this->db->query('SELECT * FROM registered_user_plan WHERE CURDATE() NOT BETWEEN plan_start_date AND plan_end_date');
		$Total_Registrations_Unsubscribed = $Total_Registrations_Unsubscribed_query->num_rows();

		// echo '<pre>total_count ';
		// print_r($total_count);
		// echo '</pre>';
		// exit(); 




		$data['Total_Active_Subscription_Plans'] = $Total_Active_Subscription_Plans;
		$data['Total_Registrations'] = $Total_Registrations;
		$data['Total_Registered_Active_Subscriptions'] = $Total_Registered_Active_Subscriptions;
		$data['Total_Subscriptions_expiring_in'] = $Total_Subscriptions_expiring_in;
		$data['Total_Registrations_Unsubscribed'] = $Total_Registrations_Unsubscribed;
		$data['Total_Registered_Users_where_Subscription_Link_Expired'] = 0;



		
        $data['page_title']="Dashboard";
        $this->load->view("super-admin/dashboard_second",$data);
		
    }


	public function super_admin_dashboard_SubscriptionTrend_result(){

		$SubscriptionTrendStartDate = $_POST['SubscriptionTrendStartDate'];
		$SubscriptionTrendEndDate = $_POST['SubscriptionTrendEndDate'];

		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$this->db->where('created_at BETWEEN "'. date('Y-m-d', strtotime($SubscriptionTrendStartDate)). '" and "'. date('Y-m-d', strtotime($SubscriptionTrendEndDate)).'"');
		$query=$this->db->get();
		$registered_user_plan = $query->result();
		
		
		$now = time(); // or your date as well
		$Original_user_result = array();
		$count = 0;
		$Original_day_count = array();
		$Renewals_day_count = array();
		$Resubscriptions_day_count = array();
		

		foreach($registered_user_plan as $registered_user_plan_key=>$registered_user_plan_value){

			
			if($registered_user_plan_value->category_subscription == 'Original'){
				$your_date = strtotime($registered_user_plan_value->created_at);
				$datediff = $now - $your_date;
				$day_different = round($datediff / (60 * 60 * 24));
				$registered_user_plan[$registered_user_plan_key]->day_difference = $day_different;
				$Original_day_count[] = $day_different;
			}

			if($registered_user_plan_value->category_subscription == 'Renewals'){
				$your_date = strtotime($registered_user_plan_value->created_at);
				$datediff = $now - $your_date;
				$day_different = round($datediff / (60 * 60 * 24));
				$registered_user_plan[$registered_user_plan_key]->day_difference = $day_different;
				$Renewals_day_count[] = $day_different;
			}

			if($registered_user_plan_value->category_subscription == 'Resubscriptions'){
				$your_date = strtotime($registered_user_plan_value->created_at);
				$datediff = $now - $your_date;
				$day_different = round($datediff / (60 * 60 * 24));
				$registered_user_plan[$registered_user_plan_key]->day_difference = $day_different;
				$Resubscriptions_day_count[] = $day_different;
			}


			$count++;
		}

		$current_date = date("Y-m-d");
		$last_ten_days_date = date("Y-m-d", strtotime("-10 days")); //for minus

		$startTime = strtotime($SubscriptionTrendStartDate);
		$endTime = strtotime($SubscriptionTrendEndDate);
		

			
		$count = 0;
		$daycount = 1;
		for($i=$startTime;$i<=$endTime;$i = $i + 86400){
			$thisDate = date('Y-m-d',$i);	

			$Original_user_result[$count]['label'] = "Day ".$daycount.' ('.$thisDate.')';
			$Original_user_result[$count]['y'] = count(array_keys($Original_day_count,$daycount));

			$Renewals_user_result[$count]['label'] = "Day ".$daycount.' ('.$thisDate.')';
			$Renewals_user_result[$count]['y'] = count(array_keys($Renewals_day_count,$daycount));

			$Resubscriptions_user_result[$count]['label'] = "Day ".$daycount.' ('.$thisDate.')';
			$Resubscriptions_user_result[$count]['y'] = count(array_keys($Resubscriptions_day_count,$i));
			$count++;
			$daycount++;
		}
		
		// $data['Original_user_result']=$Original_user_result;
		// $data['Renewals_user_result']=$Renewals_user_result;
		// $data['Resubscriptions_user_result']=$Resubscriptions_user_result;


		/*
		$k=0;
		for($i=1;$i<=10;$i++){
			$Original_user_result[$k]['label'] = "Day ".$i;
			$Original_user_result[$k]['y'] = count(array_keys($Original_day_count,$i));

			$Renewals_user_result[$k]['label'] = "Day ".$i;
			$Renewals_user_result[$k]['y'] = count(array_keys($Renewals_day_count,$i));

			$Resubscriptions_user_result[$k]['label'] = "Day ".$i;
			$Resubscriptions_user_result[$k]['y'] = count(array_keys($Resubscriptions_day_count,$i));
			$k++;
		}
		*/



		$dataarr = array(
			"Original_user_result" => $Original_user_result,
			"Renewals_user_result" => $Renewals_user_result,
			"Resubscriptions_user_result" => $Resubscriptions_user_result,
			"status"=>'1',
			"message"=>"Success",
	      );

        echo json_encode($dataarr);
	}


	public function super_admin_dashboard_TypeSubscriptionActive_result(){
		$TypeSubscriptionActiveStartDate = $_POST['TypeSubscriptionActiveStartDate'];
		$TypeSubscriptionActiveEndDate = $_POST['TypeSubscriptionActiveEndDate'];


	

		$this->db->select("id,title");
		$this->db->from('subscription_plan');
		$this->db->where('created_at BETWEEN "'. date('Y-m-d', strtotime($TypeSubscriptionActiveStartDate)). '" and "'. date('Y-m-d', strtotime($TypeSubscriptionActiveEndDate)).'"');
		$query=$this->db->get();
		$subscription_plan = $query->result();

		$subscription_plan_array = array();

		$TypeSubscriptionActiveChart_type = array('Original','Renewals','Resubscriptions');


		
		
		foreach($TypeSubscriptionActiveChart_type as $TypeSubscriptionActiveChart_type_key=>$TypeSubscriptionActiveChart_type_value){
			foreach($subscription_plan as $subscription_plan_key=>$subscription_plan_value){
				$subscription_plan_array[$TypeSubscriptionActiveChart_type_value][] = array("label"=> $subscription_plan_value->title, "id"=> $subscription_plan_value->id);
			}
		}

		$OriginalPoints = array();
		if(!empty($subscription_plan_array['Original'])){
			foreach($subscription_plan_array['Original'] as $subscription_plan_array_key=>$subscription_plan_array_value){
				$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Original" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
				$total_count = $query->num_rows();
				$subscription_plan_array_value['y'] = $total_count;
				$OriginalPoints[] = $subscription_plan_array_value;
			}
		}
		

		

		$RenewalsPoints = array();
		if(!empty($subscription_plan_array['Original'])){
			foreach($subscription_plan_array['Renewals'] as $subscription_plan_array_key=>$subscription_plan_array_value){

				$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Renewals" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
				$total_count = $query->num_rows();
				$subscription_plan_array_value['y'] = $total_count;
				$RenewalsPoints[] = $subscription_plan_array_value;
			}
		}

		$ResubscriptionsPoints = array();
		if(!empty($subscription_plan_array['Resubscriptions'])){
			foreach($subscription_plan_array['Resubscriptions'] as $subscription_plan_array_key=>$subscription_plan_array_value){
				$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Resubscriptions" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
				$total_count = $query->num_rows();
				$subscription_plan_array_value['y'] = $total_count;
				$ResubscriptionsPoints[] = $subscription_plan_array_value;
			}
		}

		// echo '<pre>';
		// print_r($ResubscriptionsPoints);
		// echo '</pre>';
		// exit(); 

		$data['OriginalPoints'] = $OriginalPoints;
		$data['RenewalsPoints'] = $RenewalsPoints;
		$data['ResubscriptionsPoints'] = $ResubscriptionsPoints;

 		echo json_encode($data);

	}


	public function super_admin_dashboard_SubscriptionAmount_result(){

		$query = $this->db->query('
		SELECT
		COUNT(CASE WHEN balance_refundable BETWEEN 1000 AND 10000 THEN 1 END) AS "1000-10000",
		COUNT(CASE WHEN balance_refundable BETWEEN 10001 AND 20000 THEN 1 END) AS "10001-20000",
		COUNT(CASE WHEN balance_refundable BETWEEN 20001  AND 30000 THEN 1 END) AS "20001-30000",
		COUNT(CASE WHEN balance_refundable BETWEEN 30001 AND 40000 THEN 1 END) AS "30001-40000",
		COUNT(CASE WHEN balance_refundable > 40001 THEN 1 END) AS "Above 40000"
			FROM v74_ci_verifyfa_db.registred_users_payment
		');
		$registered_user_plan = $query->row();

	
		$my_array = array();
		$count_value = 1;
		foreach($registered_user_plan as $registered_user_plan_key=>$registered_user_plan_value){

			$my_array[] = array(
				'x' => $count_value,
				'y' => $registered_user_plan_value,
				'label' => $registered_user_plan_key,
				'color'=> "#4f81bc"
			);
			$count_value++;
		}
		$data['my_array'] = $my_array;
		echo json_encode($data);

	}




	public function super_admin_dashboard_second2(){

		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$query=$this->db->get();
		$registered_user_plan = $query->result();
		
		$now = time(); // or your date as well
		$register_users = array();
		$count = 0;
		$day_count = array();



		

		foreach($registered_user_plan as $registered_user_plan_key=>$registered_user_plan_value){
			$your_date = strtotime($registered_user_plan_value->created_at);
			$datediff = $now - $your_date;
			$day_different = round($datediff / (60 * 60 * 24));
			$registered_user_plan[$registered_user_plan_key]->day_difference = $day_different;
			$day_count[] = $day_different;
			$count++;
		}
		$k=0;
		for($i=1;$i<11;$i++){
			$register_users[$k]['label'] = "Day ".$i;
			$register_users[$k]['y'] = count(array_keys($day_count,$i));;
			$k++;
		}
		$data['register_users']=$register_users;
		// $sql_query = "SELECT subscription_plan.id,subscription_plan.title,COUNT(registered_user_plan.plan_id) AS total_orders FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id GROUP BY registered_user_plan.plan_id";

		$sql_query = "SELECT subscription_plan.id,subscription_plan.title,registered_user_plan.category_subscription,COUNT(registered_user_plan.plan_id) AS total_orders,COUNT(registered_user_plan.category_subscription) AS category_subscription_count FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id GROUP BY registered_user_plan.plan_id";
		$sql_query_result = $this->db->query($sql_query)->result();

	

		$this->db->select('*');
		$this->db->from('subscription_plan');
		$planDetails = $this->db->get()->result();

		$my_array = array();
		foreach($planDetails as $planDetails_key=>$planDetails_value){

			$my_array['title'][] = $planDetails_value->title;
		}

	

		$TypeSubscriptionActiveChart_array = array();
		$count = 0;
		$TypeSubscriptionActiveChart_type = array('Original','Renewals','Resubscriptions');
		$my_Array = array();
		foreach($TypeSubscriptionActiveChart_type as $TypeSubscriptionActiveChart_type_key=>$TypeSubscriptionActiveChart_type_value){
			// $sql_query = "SELECT subscription_plan.id,subscription_plan.title,registered_user_plan.category_subscription,COUNT(registered_user_plan.plan_id) AS total_orders,COUNT(registered_user_plan.category_subscription) AS category_subscription_count FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where category_subscription = '".$TypeSubscriptionActiveChart_type_value."' AND plan_id = 4 GROUP BY registered_user_plan.plan_id";

			$sql_query = "SELECT subscription_plan.id,subscription_plan.title,registered_user_plan.category_subscription,COUNT(registered_user_plan.plan_id) AS total_orders,COUNT(registered_user_plan.category_subscription) AS category_subscription_count FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where category_subscription = '".$TypeSubscriptionActiveChart_type_value."' GROUP BY registered_user_plan.plan_id";

			$sql_query_result = $this->db->query($sql_query)->result();
			$my_Array[$TypeSubscriptionActiveChart_type_value] = $sql_query_result;
		}
		$TypeSubscriptionActiveChart_array = array();



		// echo '<pre>my_Array ';
		// print_r($my_Array);
		// echo '</pre>';
		// exit(); 
		
		$counr = 1;
		$Original_array = array();
		foreach($my_Array as $my_Array_key=>$my_Array_value){
			$TypeSubscriptionActiveChart_array[$count]['type'] = 'stackedColumn100';
			$TypeSubscriptionActiveChart_array[$count]['name'] = $my_Array_key;
			$TypeSubscriptionActiveChart_array[$count]['showInLegend'] = true;
			$TypeSubscriptionActiveChart_array[$count]['xValueFormatString'] = $my_Array_key;
			$TypeSubscriptionActiveChart_array[$count]['yValueFormatString'] = "#,##0\"%\"";
			$Original_array = array();
			foreach($my_Array[$my_Array_key] as $my_Array_value_key=>$my_Array_value_value){
				$Original_array[] = array('title'=>$my_Array_value_value->title,'x'=>$my_Array_value_value->total_orders,'y'=>$my_Array_value_value->total_orders);
			}
			$TypeSubscriptionActiveChart_array[$count]['dataPoints'] = $Original_array;
			$count++;
		}

		// echo '<pre>TypeSubscriptionActiveChart_array ';
		// print_r($TypeSubscriptionActiveChart_array);
		// echo '</pre>';
		// exit(); 



		$data['TypeSubscriptionActiveChart_array']=$TypeSubscriptionActiveChart_array;
        $data['page_title']="Dashboard";
        // $this->load->view("super-admin/dashboard_second",$data);
		// $this->load->view("super-admin/dashboard_third",$data);
		$this->load->view("super-admin/dashboard_forth",$data);
    }


	public function super_admin_dashboard_second3(){

		// registered_user_plan
		// subscription_plan

		$this->db->select("*");
		$this->db->from('registered_user_plan');
		$query=$this->db->get();
		$registered_user_plan = $query->result();


		$this->db->select("id,title");
		$this->db->from('subscription_plan');
		$query=$this->db->get();
		$subscription_plan = $query->result();

		$subscription_plan_array = array();

		$TypeSubscriptionActiveChart_type = array('Original','Renewals','Resubscriptions');


		
		
		foreach($TypeSubscriptionActiveChart_type as $TypeSubscriptionActiveChart_type_key=>$TypeSubscriptionActiveChart_type_value){
			foreach($subscription_plan as $subscription_plan_key=>$subscription_plan_value){
				$subscription_plan_array[$TypeSubscriptionActiveChart_type_value][] = array("label"=> $subscription_plan_value->title, "id"=> $subscription_plan_value->id);
			}
		}

		$OriginalPoints = array();
		foreach($subscription_plan_array['Original'] as $subscription_plan_array_key=>$subscription_plan_array_value){
			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Original" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$OriginalPoints[] = $subscription_plan_array_value;
		}

		

		$RenewalsPoints = array();
		foreach($subscription_plan_array['Renewals'] as $subscription_plan_array_key=>$subscription_plan_array_value){

			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Renewals" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$RenewalsPoints[] = $subscription_plan_array_value;
		}

		$ResubscriptionsPoints = array();
		foreach($subscription_plan_array['Resubscriptions'] as $subscription_plan_array_key=>$subscription_plan_array_value){
			$query = $this->db->query('SELECT * FROM subscription_plan LEFT JOIN registered_user_plan ON subscription_plan.id = registered_user_plan.plan_id where registered_user_plan.category_subscription = "Resubscriptions" AND registered_user_plan.plan_id = '.$subscription_plan_array_value['id']);
			$total_count = $query->num_rows();
			$subscription_plan_array_value['y'] = $total_count;
			$ResubscriptionsPoints[] = $subscription_plan_array_value;
		}

		$data['OriginalPoints'] = $OriginalPoints;
		$data['RenewalsPoints'] = $RenewalsPoints;
		$data['ResubscriptionsPoints'] = $ResubscriptionsPoints;

	
        $data['page_title']="Dashboard";
    	$this->load->view("super-admin/dashboard_forth",$data);
    }



	public function mange_subscription(){
        $data['page_title']="Manage Subscription";
		$data['plan']=$this->Super_admin_model->list_subscription_plan();
        $this->load->view("super-admin/manage-subscription",$data);

    }

	public function create_subscription(){
        $data['page_title']="Manage Subscription";
        $this->load->view("super-admin/create-subscription",$data);

    }
	public function save_subscription(){
		$data= array(
			"title"=>$this->input->post('title'),
			"subtitle"=>$this->input->post('subtitle'),
			"time_subscription"=>$this->input->post('time_subscription'),
			"description"=>$this->input->post('description'),
			"allowed_entities_no"=>$this->input->post('allowed_entities_no'),
			"location_each_entity"=>$this->input->post('location_each_entity'),
			"user_number_register"=>$this->input->post('user_number_register'),
			"line_item_avaliable"=>$this->input->post('line_item_avaliable'),
			"highlights"=>$this->input->post('highlights'),
			"validity_from"=>date("Y-m-d",strtotime($this->input->post('validity_from'))),
			"validity_to"=>date("Y-m-d",strtotime($this->input->post('validity_to'))),
			"amount"=>$this->input->post('amount'),
			"about_payment"=>$this->input->post('about_payment'),
			"terms_condition"=>$this->input->post('terms_condition'),
			"foot_notes"=>$this->input->post('foot_notes'),
			"created_at"=>date("Y-m-d H:i:s")
			);
			$this->Super_admin_model->save_subscription_plan($data);
			$this->session->set_flashdata('success', 'Plan Addedd Successfully');
			redirect("index.php/manage-subscription");
	}

	public function edit_subscription($id){
        $data['page_title']="Manage Subscription";
		$data['plan']=$this->Super_admin_model->get_subscription_plan_single($id);
        $this->load->view("super-admin/edit-subscription",$data);
    }

	public function edit_save_subscription(){
		$id = $this->input->post('id');
		$data= array(
			"title"=>$this->input->post('title'),
			"time_subscription"=>$this->input->post('time_subscription'),
			"subtitle"=>$this->input->post('subtitle'),
			"description"=>$this->input->post('description'),
			"allowed_entities_no"=>$this->input->post('allowed_entities_no'),
			"location_each_entity"=>$this->input->post('location_each_entity'),
			"user_number_register"=>$this->input->post('user_number_register'),
			"line_item_avaliable"=>$this->input->post('line_item_avaliable'),
			"highlights"=>$this->input->post('highlights'),
			"validity_from"=>date("Y-m-d",strtotime($this->input->post('validity_from'))),
			"validity_to"=>date("Y-m-d",strtotime($this->input->post('validity_to'))),
			"amount"=>$this->input->post('amount'),
			"about_payment"=>$this->input->post('about_payment'),
			"terms_condition"=>$this->input->post('terms_condition'),
			"foot_notes"=>$this->input->post('foot_notes'),
			"created_at"=>date("Y-m-d H:i:s")
			);
			$this->Super_admin_model->update_subscription_plan($data,$id);
			$this->session->set_flashdata('success', 'Plan Updated Successfully');
			redirect("index.php/edit-subscription/".$id);
	}
	
	public function view_subscription($id){
		$data['page_title']="Manage Subscription";
		$data['plan']=$this->Super_admin_model->get_subscription_plan_single($id);
        $this->load->view("super-admin/view-subscription",$data);
	}

	public function inactive_plan($id){
		$date_yesterday=date('Y-m-d',strtotime("-1 days"));
		$data=array("validity_to"=>$date_yesterday);
		$this->Super_admin_model->inactive_plan($id,$data);
		$this->session->set_flashdata('success', 'Plan Inactivated Successfully');
		redirect("index.php/manage-subscription");
	}

	public function active_plan($id){
		$data=array("status"=>"1");
		$this->Super_admin_model->active_plan($id,$data);
		$this->session->set_flashdata('success', 'Plan Activated Successfully');
		redirect("index.php/manage-subscription");
	}

	public function manage_user(){
		$data['page_title']="Manage User";
		$data['user_data']=$this->Super_admin_model->get_registered_users();
        $this->load->view("super-admin/manage-user",$data);
	}

	public function select_plan(){
		$data['page_title']="Manage User";
		$data['plan']=$this->Super_admin_model->get_active_plan();
        $this->load->view("super-admin/select-plan",$data);
	}

	public function create_user($plan_id){
		$data['page_title']="Manage User";
		$data['plan']=$this->Super_admin_model->get_plan_single($plan_id);
        $this->load->view("super-admin/create-user",$data);
	}

	public function calculate_user_plan(){
		$plan_id=$this->input->post('plan_id');
		$validity=$this->input->post('validity');
		$period=$this->input->post('period');
		$start_date=$this->input->post('start_date');
		if($period == 'days'){
			 $addduration= $validity." days";
			}
		if($period == 'month'){  
			$addduration= $validity." months";
			}
		if($period == 'year'){  
			 $addduration= $validity." years";
			}

      // Add days to date and display it
        $end_date = date('Y-m-d', strtotime($start_date. ' + '.$addduration));
		$plan = $this->Super_admin_model->get_subscription_plan_row($plan_id);
		$total_amount = ($plan->amount * $validity);
	
		$dataarr[]=array(
			"end_date"=>$end_date,
			"total_pament_amount"=>$total_amount,
	      );
        echo json_encode($dataarr);
	}
	
	public function test(){
		$data['page_title']="Manage User";
        $this->load->view("super-admin/test",$data);
	}
	public function confirmation_userdetail($id){
		date_default_timezone_set("Asia/Calcutta"); 
		$data['page_title']="Manage User";
		$data['user']=$this->Super_admin_model->get_registerd_user($id);

		// echo '<pre>last_query ';
		// print_r($this->db->last_query());
		// echo '</pre>';
		// exit();

		$to = $data['user']->email_id;
		// $to = 'hardik.meghnathi12@gmail.com';
		
		// $activation_link = '<a href="'.base_url().'index.php/registered-user-login/">Activate Your Account</a>';
		// $activation_link = '<a href="'.base_url().'index.php/activation-registered-user/">Activate Your Account</a>';
		$activation_link = '<a href="'.base_url().'index.php/generate-active-register-user/'.$id.'">Activate Your Account</a>';
		$TRANSACTIONRECORDDATETIME = date('d-m-Y h:i:s A');

		$APPLICATIONNAME = 'VerifyFA';
		$RECEIVERNAME = $data['user']->first_name;		
		$subject = $APPLICATIONNAME.' Activate Your Account and Setup New Password';

		$digits = 5;
		$TEMPORARYPASSWORD = rand(pow(10, $digits-1), pow(10, $digits)-1);
		$COMPANYNAME = $data['user']->organisation_name;

		$email_updated_content = '<body style="font-family: Helvetica, Arial, sans-serif; margin: 0px; padding: 0px; background-color: #ffffff;">
            <table role="presentation"
                style="width: 100%;border-collapse: collapse;border: 0px;border-spacing: 0px;font-family: Arial, Helvetica, sans-serif;background-color: rgb(250, 250, 250);">
                <tbody>
                <tr>
                    <td align="center" style="padding: 1rem 2rem; vertical-align: top; width: 100%;">
                    <table role="presentation" style="max-width: 600px; border-collapse: collapse; border: 0px; border-spacing: 0px; text-align: left;">
                        <tbody>
                        <tr>
                            <td style="padding: 40px 0px 0px;">
                            <div style="text-align: left;">
                                <div style="padding-bottom: 20px;text-align: center;">
                                    <img src="https://verifyfa.developmentdemo.co.in/assets/img/logo.png" alt="APPLICATIONLOGOCompany" style="width: 56px;">
                                </div>
                            </div>
                            <div style="padding: 20px;background-color: rgb(255, 255, 255);border: 1px solid grey;">
                                <div style="color: rgb(0, 0, 0); text-align: left;">

                                    <p style="font-size: 14px;color: gray;text-align: center;">
                                    ***** This is an auto generated NO REPLY communication and replies to this email id are
                                    not attended to. (Business Hours from Mon To Sat : 10:00am to 6:00pm) *****
                                    </p>

                                    <p style="font-size: 18px;"> '.$TRANSACTIONRECORDDATETIME.' </p>
                                    <p style="font-size: 18px;">Dear <b>'.$RECEIVERNAME.'</b>,</p>

                                    <p style="font-size: 18px;line-height: 28px;">
                                    Thanks for registering on <b>'.$APPLICATIONNAME.'</b>. It is important to activate your account in due time to continue further.
                                    <br>
									<br>
                                    Your Temporary Password for 1st time login is: <b>'.$TEMPORARYPASSWORD.'</b>.
                                    <br>
									<br>
                                    Please click on the link to Activate and setup your New Password. '.$activation_link.'
                                    </p>


                                <p style="font-size: 18px;">Thanks for your support and understanding. <br>
                                Regards, <br>
                                <b>'.$COMPANYNAME.'</b></p>

                                <p style="font-size: 14px;color: gray;text-align: center;">*****This is a system generated communication and does not require signature. *****</p>

                                </div>
                            </div>
                            <div style="padding-top: 20px; color: rgb(153, 153, 153); text-align: justify;">
                                Copyright <b>'.$COMPANYNAME.'</b>. All rights reserved. Terms & Conditions Please do not share your Login details, such as User ID / Password / OTP with anyone, either over phone or through email.
                                Do not click on link from unknown/ unsecured sources that seek your confidential information. 
                                This email is confidential. It may also be legally privileged. If you are not the addressee, you may not copy, forward, disclose or use any part of it. Internet communications cannot be guaranteed to be timely, secure, error or virus free. The sender does not accept liability for any errors or omissions. We maintain strict security standards and procedures to prevent unauthorised access to any personal information about you.
                                Kindly read through the Privacy Policy on our website for use of Personal Information.
                                </p>
                            

                            </div>
                            <div style="padding-top: 20px; color: rgb(153, 153, 153); text-align: center;">
                            <a href="javascript:void(0)">Home</a> | <a href="javascript:void(0)">Privacy Policy</a> | <a href="javascript:void(0)">Disclaimer</a>
                            </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
                </tbody>
            </table>
            </body>';

		
		$CI = setEmailProtocol();
		$from_email = 'solutions@ethicalminds.in';
		$CI->email->set_newline("\r\n");
		$CI->email->set_mailtype("html");
		$CI->email->set_header('Content-Type', 'text/html');
		$CI->email->from($from_email);
		$CI->email->to($to);
		$CI->email->subject($subject);
		$CI->email->message($email_updated_content);

		$mailsend = 0;
		if(server_check() == 'live'){
			if($CI->email->send()){
				$mailsend = 1;
			}
		}

		$data=array(
        "password"=>md5($TEMPORARYPASSWORD),
		"password_view"=>$TEMPORARYPASSWORD,
	 	);
	 	$this->Super_admin_model->update_confirmation_data_user($id,$data);

		
		$plan_data_details = $this->Super_admin_model->get_registered_user_plan($id);
		$data['plan_data'] = $plan_data_details;
		$data['page_title']="Manage User";
		$data['plan_details'] = get_plan_row($plan_data_details->plan_id);
		$data['user']=$this->Super_admin_model->get_registerd_user($id);
		$this->load->view("super-admin/confirmation-user-detail",$data);
	}

	public function save_registred_user(){
	
		if($this->input->post('is_gst') == '1'){
			$gst_no=$this->input->post('gst_no');
			$gst_org_name=$this->input->post('gst_org_name');
			$gst_org_address=$this->input->post('gst_org_address');
		}else{
			$gst_no='';
			$gst_org_name='';
			$gst_org_address='';
		}
		$digits = 5;
		$TEMPORARYPASSWORD = rand(pow(10, $digits-1), pow(10, $digits)-1);
		
		
		$urer_registration_no=date("Ym")."VFA".rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$data_user= array(
			"urer_registration_no"=>$urer_registration_no,
			"password"=>md5($TEMPORARYPASSWORD),
			"password_view"=>$TEMPORARYPASSWORD,
			"balance_due"=>$this->input->post('balance_refundable'),
			"plan_id"=>$this->input->post('plan'),
			"first_name"=>$this->input->post('first_name'),
			"last_name"=>$this->input->post('last_name'),
			"email_id"=>$this->input->post('email_id'),
			"phone_no"=>$this->input->post('phone_no'),
			"organisation_name"=>$this->input->post('organisation_name'),
			"entity_code"=>$this->input->post('entity_code'),
			"is_gst"=>$this->input->post('is_gst'),
			"gst_no"=>$gst_no,
			"name_org_gst"=>$gst_org_name,
			"address_org_gst"=>$gst_org_address,
			"created_at"=>date("Y-m-d H:i:s")
		);
	$registered_user_id= $this->Super_admin_model->save_registered_user($data_user);

	
	//step 2 data save//
	$data_plan= array(
		"regiistered_user_id"=>$registered_user_id,
		"plan_id"=>$this->input->post('plan'),
		"subscription_time_value"=>$this->input->post('subscription_time_value'),
		"time_subscription"=>$this->input->post('time_subscription'),
		"plan_start_date"=>date("Y-m-d",strtotime($this->input->post('plan_start_date'))),
		"plan_end_date"=>date("Y-m-d",strtotime($this->input->post('plan_end_date'))),
		"category_subscription"=>$this->input->post('category_subscription'),
		"credit_days"=>$this->input->post('credit_days'),
		"created_at"=>date("Y-m-d H:i:s")
	);
	$this->Super_admin_model->save_registered_user_plan($data_plan);
	//step 2 data save//

	// ??step 3 data save//
	$transection_id=date("YmdHis").rand(0,9).rand(0,9).rand(0,9);
	$data_payment= array(
		"regiistered_user_id"=>$registered_user_id,
		"plan_id"=>$this->input->post('plan'),
		"amount_subs_due"=>$this->input->post('amount_subs_due'),
		"paymentother_charge"=>$this->input->post('paymentother_charge'),
		"discount_credits"=>$this->input->post('discount_credits'),
		"total_amount_payble"=>$this->input->post('total_amount_payble'),
		"payment_amount"=>$this->input->post('payment_amount'),
		"balance_refundable"=>$this->input->post('balance_refundable'),
		"payment_date"=>$this->input->post('payment_date'),
		"mode_of_payment"=>$this->input->post('mode_of_payment'),
		"transection_ref"=>$this->input->post('transection_ref'),
		"payment_remarks"=>$this->input->post('payment_remarks'),
		"transection_id"=>$transection_id,
		"created_at"=>date("Y-m-d H:i:s")
	);
	$this->Super_admin_model->save_registered_user_payment($data_payment);
	// ??step 3 data save//


	// ??step 4 data save//
	$data_array=array(
	"plan_id"=>$this->input->post('plan'),
	"upgrated_plan_id"=>$this->input->post('plan'),
	"register_user_id"=>$registered_user_id,
	"created_at"=>date("Y-m-d H:i:s"),
	);
	$this->Super_admin_model->save_upgradePlan($data_array);
	// ??step 4 data save//



	$this->session->set_flashdata('success', 'User Created Successfully Now you Are In Confirmation Page');
	redirect("index.php/confirmation-user-detail/".$registered_user_id);
	}
 	



	





 public function generate_activation_link($id){
	$date = date("Y-m-d");
   // Use date_add() function to add date object
   $activation_link = base_url().'index.php/activation-registered-user/'.$id;

    $expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));
	$data=array(
        "activation_generete_link"=>"1",
		"activation_generete_link_date"=>$date,
		"activation_link"=>$activation_link,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);

	 $this->session->set_flashdata('success', 'Link Generated Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }



 public function send_activation_link($id){

	$date = date("Y-m-d");
	$expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));
	$login_link= base_url()."index.php/registered-user-login";
	$data=array(
        "is_activation_send"=>"1",
		"activation_send_date"=>$date,
		"link_expiry_date"=>$expire_date,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);

	 $userdata=$this->Super_admin_model->get_registerd_user($id);

	 $toemail=$userdata->email_id;
	 $password_view=$userdata->password_view;
	 $firstname=$userdata->first_name;
	 $lastname=$userdata->last_name;
     $activation_link=$userdata->activation_link;
     $subject="verifyfa.com Activation Account Email";

	 $message="Dear ".$firstname." ". $lastname."<br> <br> Your account activation link given below. Click below given link and activate your account.<br> Once you are activate your account login your account with given login credentials.<br> <b>Activate your account:</b> <a href='".$activation_link."' target='_blank'>Click Here</a>.<br><br>
	 <b>Login Url:</b> <a href='".$login_link."' target='_blank' >Click Here For login</a>
	 <b>Email-Id:</b> ".$toemail."
	 <b>Password:</b> ".$password_view."<br><br>Thank You.";
	 $this->sent_email($toemail,$subject,$message);


	 $this->session->set_flashdata('success', 'Activation Link Send Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }

 public function regenerate_activation_link($id){
	$date = date("Y-m-d");
	$data=array(
		"regenerate_activation_date"=>$date,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);
	 $this->session->set_flashdata('success', 'Link Re Generated Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }
 
 public function regenerate_activation_send_link($id){
	$date = date("Y-m-d");
	$expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));

	$data=array(
        "is_activation_send"=>"1",
		"activation_send_date"=>$date,
		"link_expiry_date"=>$expire_date,
		"regenrred_link_send_date"=>$date
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);

	 $userdata=$this->Super_admin_model->get_registerd_user($id);

	 $toemail=$userdata->email_id;
	 $password_view=$userdata->password_view;
	 $firstname=$userdata->first_name;
	 $lastname=$userdata->last_name;
     $activation_link=$userdata->activation_link;
     $subject="verifyfa.com Activation Account Email";

	 $message="Dear ".$firstname." ". $lastname."<br> <br> Your account activation link given below. Click below given link and activate your account.<br> Once you are activate your account login your account with given login credentials.<br> <b>Activate your account:</b> <a href='".$activation_link."' target='_blank'>Click Here</a>.<br><br>
	 <b>Login Url:</b> <a href='".$login_link."' target='_blank' >Click Here For login</a>
	 <b>Email-Id:</b> ".$toemail."
	 <b>Password:</b> ".$password_view."<br><br>Thank You.";
	 $this->sent_email($toemail,$subject,$message);


	 $this->session->set_flashdata('success', 'Activation Link Send Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }

 public function suspend_account($id){
	$date = date("Y-m-d");
	$expire_date= date('Y-m-d', strtotime($date. ' + 1 days'));

	$data=array(
        "is_active"=>"5",
		"suspend_date"=>$date,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);

	 $this->session->set_flashdata('success', 'Activation Suspended Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }


 public function unsubscribe_account($id){
	$date = date("Y-m-d");
	$data=array(
        "is_active"=>"6",
		"unsubscribe_date"=>$date,
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);
	 $this->session->set_flashdata('success', 'Activation Suspended Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }

 public function reactive_account($id){
	$date = date("Y-m-d");
	$data=array(
        "is_active"=>"4",
        "is_resubscribe_request"=>"4",
        "is_resubscribe_request_at"=>"",
	 );
	 $this->Super_admin_model->update_confirmation_data_user($id,$data);
	 $this->session->set_flashdata('success', 'Activation Reactivate Successfully');
	 redirect("index.php/confirmation-user-detail/".$id);
 }

 public function edit_user($id){
	$data['page_title']="Manage User";
	$data['plan']=$this->Super_admin_model->get_active_plan();
	$data['user_data']=$this->Super_admin_model->get_registerd_user($id);
	$data['payment_data']=$this->Super_admin_model->get_registred_users_payment($id);
	$data['plan_data']=$this->Super_admin_model->get_registered_user_plan($id);
	$this->load->view("super-admin/edit-user",$data);
 }
 

 
 public function view_user($id){
	$data['page_title']="Manage User";
	$data['plan']=$this->Super_admin_model->get_active_plan();
	$data['user_data']=$this->Super_admin_model->get_registerd_user($id);
	$data['payment_data']=$this->Super_admin_model->get_registred_users_payment($id);
	$data['plan_data']=$this->Super_admin_model->get_registered_user_plan($id);


	
	$this->db->select('register_user_plan_log.*, subscription_plan.*');
	$this->db->from(' subscription_plan');
	$this->db->join('register_user_plan_log','register_user_plan_log.plan_id= subscription_plan.id');
	$this->db->where('register_user_plan_log.register_user_id',$id);
	$getnotifications=$this->db->get();
	$result = $getnotifications->result();
	$data['payment_history'] = $result;

	
	$this->load->view("super-admin/view-user",$data);
 }
 


 public function save_edit_registred_user(){
	
	$registered_user_id=$this->input->post('register_user_id');
	if($this->input->post('is_gst') == '1'){
		$gst_no=$this->input->post('gst_no');
		$gst_org_name=$this->input->post('gst_org_name');
		$gst_org_address=$this->input->post('gst_org_address');
	}else{
		$gst_no='';
		$gst_org_name='';
		$gst_org_address='';
	}
	$data_user= array(
		"renew_request"=>'0',
		"request_renew_at"=>'',
		"balance_due"=>$this->input->post('balance_refundable'),
		"plan_id"=>$this->input->post('plan'),
		"first_name"=>$this->input->post('first_name'),
		"last_name"=>$this->input->post('last_name'),
		"email_id"=>$this->input->post('email_id'),
		"phone_no"=>$this->input->post('phone_no'),
		"organisation_name"=>$this->input->post('organisation_name'),
		"entity_code"=>$this->input->post('entity_code'),
		"is_gst"=>$this->input->post('is_gst'),
		"gst_no"=>$gst_no,
		"name_org_gst"=>$gst_org_name,
		"address_org_gst"=>$gst_org_address,
		"updated_at"=>date("Y-m-d H:i:s")
	);
$this->Super_admin_model->edit_save_registered_user($registered_user_id,$data_user);


//step 2 data save//
$data_plan= array(
	"regiistered_user_id"=>$registered_user_id,
	"plan_id"=>$this->input->post('plan'),
	"subscription_time_value"=>$this->input->post('subscription_time_value'),
	"time_subscription"=>$this->input->post('time_subscription'),
	"plan_start_date"=>date("Y-m-d",strtotime($this->input->post('plan_start_date'))),
	"plan_end_date"=>date("Y-m-d",strtotime($this->input->post('plan_end_date'))),
	"category_subscription"=>$this->input->post('category_subscription'),
	"credit_days"=>$this->input->post('credit_days'),
	"created_at"=>date("Y-m-d H:i:s")
);
$this->Super_admin_model->edit_save_registered_user_plan($registered_user_id,$data_plan);
//step 2 data save//

// ??step 3 data save//
if($this->input->post('payment_amount') !=''){
	$transection_id=date("YmdHis").rand(0,9).rand(0,9).rand(0,9);

$data_payment= array(
	"regiistered_user_id"=>$registered_user_id,
	"plan_id"=>$this->input->post('plan'),
	"amount_subs_due"=>$this->input->post('amount_subs_due'),
	"paymentother_charge"=>$this->input->post('paymentother_charge'),
	"discount_credits"=>$this->input->post('discount_credits'),
	"total_amount_payble"=>$this->input->post('total_amount_payble'),
	"payment_amount"=>$this->input->post('payment_amount'),
	"balance_refundable"=>$this->input->post('balance_refundable'),
	"payment_date"=>$this->input->post('payment_date'),
	"mode_of_payment"=>$this->input->post('mode_of_payment'),
	"transection_ref"=>$this->input->post('transection_ref'),
	"payment_remarks"=>$this->input->post('payment_remarks'),
	"transection_id"=>$transection_id,
	"created_at"=>date("Y-m-d H:i:s")
);
$this->Super_admin_model->save_registered_user_payment($data_payment);


$data_array=array(
"plan_id"=>0,
"upgrated_plan_id"=>$this->input->post('plan'),
"register_user_id"=>$registered_user_id,
"created_at"=>date("Y-m-d H:i:s"),
);
$this->Super_admin_model->save_upgradePlan($data_array);



}
$this->session->set_flashdata('success', 'User Details Updated Successfully Now you Are In Confirmation Page');
redirect("index.php/confirmation-user-detail/".$registered_user_id);

}

 //Email Function//
 public function sent_email($toemail,$subject,$message){
	$to = "hardik.meghnathi12@gmail.com"; 
	$from = 'support@verifyfa.com'; 
	$fromName = 'Verifyfa';  
	// Additional headers 
  

	$headers = 'From: '.$fromName.'<'.$from.'>'; 
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	// Send email 
	if(mail($to, $subject, $message, $headers)){ 
	   echo 'Email has sent successfully.'; 
	}else{ 
		
		echo 'Email sending failed.'; 
	}
}
//Email Function//
 

//check subscription exist behalf on title//
public function checksubscription(){
	$title = $this->input->post('title');
	$restitle = $this->Super_admin_model->checksubscription_title($title);
	echo $restitle;

}

//check useremail exist behalf on email//
public function checkuseremail(){
	$email_id = $this->input->post('email_id');
	$restitle = $this->Super_admin_model->checkuser_email($email_id);
	echo $restitle;

}
//check checkentitycode exist behalf on entitycode//
public function checkentitycode(){
	$entity_code = $this->input->post('entity_code');
	$restitle = $this->Super_admin_model->checkuser_entitycode($entity_code);
	echo $restitle;

}


//check checkentitycode exist behalf on entitycode//
public function upgrade_plan($register_user_id){
	$data['page_title']="Upgrade User";
	$datauser=$this->Super_admin_model->get_registerd_user($register_user_id);
	$data['user']=$this->Super_admin_model->get_registerd_user($register_user_id);
	$data['plan']=$this->Super_admin_model->get_active_plan_upgrade($datauser->plan_id);
	$this->load->view("super-admin/upgrade-plan",$data);

}

public function upgrade_plan_save(){
	$plan_id=$this->input->post('plan_id');
	$upgrated_plan_id=$this->input->post('upgrade_plan_id');
	$register_user_id=$this->input->post('register_user_id');

	$data_array=array(
	"plan_id"=>$plan_id,
	"upgrated_plan_id"=>$upgrated_plan_id,
	"register_user_id"=>$register_user_id,
	"created_at"=>date("Y-m-d H:i:s"),
	);

	$data_array1=array(
		"plan_id"=>$upgrated_plan_id,
	);
	$this->Super_admin_model->save_upgradePlan($data_array);
	$this->Super_admin_model->update_plan($data_array1,$register_user_id);
	$this->Super_admin_model->update_plan_plan($data_array1,$register_user_id);
	$this->session->set_flashdata('success', 'User Plan Upgrated Successfully Done');
	redirect("index.php/confirmation-user-detail/".$register_user_id);

}


}

