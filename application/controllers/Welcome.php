<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		require_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';
		$filename = './testnew.xls';
		$objReader = new PHPExcel_Reader_Excel5();
//$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load( $filename );

$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

$sheet = $objPHPExcel->getActiveSheet();

$array_data = array();
$tablename="`fa_verification_for_test_data`";
$main=0;
foreach($rowIterator as $row){
	$cellIterator = $row->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
	if(1 == $row->getRowIndex())
	{
		
		//skip first row
		
		$createquery="CREATE TABLE IF NOT EXISTS ".$tablename." (
			id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";
		foreach ($cellIterator as $cell) {
			$old_pattern = array("/[^a-zA-Z0-9]/", "/_+/", "/_$/");
			$new_pattern = array("_", "_", "");

			if(trim($cell->getCalculatedValue())=="Item Category")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NOT NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Item Unique Code")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NOT NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Item Description")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." TEXT NOT NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Location of the Item last verified")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NOT NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Quantity as per Invoice")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." INT (11) NOT NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Verifiable Status (Y/ N/ NA)")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NOT NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Tag Status (Y/ N/ NA)")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(10) NOT NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Total Item Amount Capitalized")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DECIMAL(15,2) NOT NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Accounting Voucher Date")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="PO/ WO Date")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Date of Purchase /Invoice date")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Date of Item Capitalization")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
			}
			else if(trim($cell->getCalculatedValue())=="Date of Sale")
			{
				$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." DATE NULL,";
			}
			else
			{
				if($cell->getCalculatedValue() != '')
				{
					$createquery.=strtolower(preg_replace($old_pattern, $new_pattern , trim($cell->getCalculatedValue())))." VARCHAR(255) NULL,";
				}
				
			}
			
		}
		
	
		$createquery.="verification_status VARCHAR(50) NOT NULL DEFAULT 'Not-Verified' ,quantity_verified INT(11) NOT NULL DEFAULT '0',new_location_verified VARCHAR(255) NULL,verified_by VARCHAR(255) NULL,verified_by_username VARCHAR(255) NULL,verified_datetime DATETIME NULL,verification_remarks TEXT NULL, createdat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updatedat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)";		
		$this->db->query($createquery);
		
	}
	else
	{
		$rowIndex = $row->getRowIndex();
		
		$columns=$this->db->list_fields($tablename);
		
		$insertarray=array();
		$i=1;
		foreach ($cellIterator as $cell) {
			
			if($i <=69)
			{
				if($i==21 || $i==25 || $i==27 || $i==49 || $i==28)
				{
					if($cell->getCalculatedValue() != '')
						$insertarray["".$columns[$i].""]="".PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD')."";
				}
				else
				{
					if($cell->getCalculatedValue() != '')
						$insertarray["".$columns[$i].""]="".$cell->getCalculatedValue()."";
				}
			}
			$i++;
			
		}
		
		$insert=$this->db->insert($tablename,$insertarray);
		
	}
	$main++;
	
}
		echo date('Y-m-d H:s:i', strtotime('+17 minutes',strtotime(date('Y-m-d H:s:i'))) );
		$this->load->view('welcome_message');
	}
}
