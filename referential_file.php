<?php

	// Здесь забираем выбор из браузера.

	$person = $_GET['person'];
	$start = $_GET['start'];
	$end = $_GET['end'];

	// Connecting database lib

	require('db_config.php');


  //sql-расчеты
		$sqlMin1 = "SELECT [ValueName], MIN(ValueAmount) AS MIN_RISK FROM server_name WHERE Date BETWEEN '$start' AND '$end' and ValueName='$person' group by ValueName";
		$sqlMax1 = "SELECT [ValueName], MAX(ValueAmount) AS MAX_RISK FROM server_name WHERE Date BETWEEN '$start' AND '$end' and ValueName='$person' group by ValueName";
		$sqlSumma2= "SELECT SUM(CAST(ValueAmount as float)*100) AS UN_SUMMA FROM server_name WHERE ValueName in('CapitalCons_CreditRisk_Group', 'CapitalCons_OperRisk_Group', 'CapitalCons_IRRTB_Group', 'CapitalCons_FXRisk_Group', 'CapitalCons_IRR_Group', 'CapitalCons_LiqRisk_Group') and Date BETWEEN '$start' AND '$end'";
	  $sqlSumma1= "SELECT SUM(CAST(ValueAmount as float)*100) AS ID_SUMMA FROM server_name where ValueName ='$person' and Date BETWEEN '$start' AND '$end'";
	
	$paramsPupa= array();
	$optionsPupa =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$pupaData = sqlsrv_query($conn, $sqlMin1, $paramsPupa, $optionsPupa);
	$row = sqlsrv_fetch_array($pupaData, SQLSRV_FETCH_ASSOC);
	$min_risk = round((($row['MIN_RISK'])*100), 2);

	$paramsPupa= array();
	$optionsPupa =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$pupaData = sqlsrv_query($conn, $sqlMax1, $paramsPupa, $optionsPupa);
	$row = sqlsrv_fetch_array($pupaData, SQLSRV_FETCH_ASSOC);
	$max_risk = round((($row['MAX_RISK'])*100), 2);
	
	$paramsPupa= array();
	$optionsPupa =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$pupaData = sqlsrv_query($conn, $sqlSumma1, $paramsPupa, $optionsPupa);
	$row = sqlsrv_fetch_array($pupaData, SQLSRV_FETCH_ASSOC);
	$summa1_risk = $row['ID_SUMMA'];

	$paramsPupa= array();
	$optionsPupa =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$pupaData = sqlsrv_query($conn, $sqlSumma2, $paramsPupa, $optionsPupa);
	$row = sqlsrv_fetch_array($pupaData, SQLSRV_FETCH_ASSOC);
	$summa2_risk = $row['UN_SUMMA'];

	$avgW = round(($summa1_risk/$summa2_risk), 2);
		

	$string = "Минимальное значение = $min_risk %";
	$string1= "Максимальное значение = $max_risk %";
	$string2= "Средневзвешенное значение = $avgW";
	
	// Возвращаем строку обратно в браузер
	$text = "\n$string\n$string1\n$string2";
  echo nl2br($text);
  // или так
	//echo "$string";
	//"\n"
	//echo $string1;
