<?php
ini_set('memory_limit', '16M');
set_time_limit(0);
require_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';

$filename = dirname(__FILE__) . '/xls/test_data.xls';

$objReader = new PHPExcel_Reader_Excel5();
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load( $filename );

$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

$sheet = $objPHPExcel->getActiveSheet();

$array_data = array();
foreach($rowIterator as $row){
	$cellIterator = $row->getCellIterator();
	$cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set

	// if(1 == $row->getRowIndex()) continue;//skip first row
	// $rowIndex = $row->getRowIndex();
	// $array_data[$rowIndex] = array('A'=>'', 'B'=>'','C'=>'','D'=>'');
	
	foreach ($cellIterator as $cell) {
		getStyle('A1')->getFill()->getStartColor()->getRGB();
	// 	if('A' == $cell->getColumn()){
	// 		$array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
	// 	} else if('B' == $cell->getColumn()){
	// 		$array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
	// 	} else if('C' == $cell->getColumn()){
	// 		$array_data[$rowIndex][$cell->getColumn()] = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD');
	// 	} else if('D' == $cell->getColumn()){
	// 		$array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
	// 	}
	}
}
print_r($array_data);
