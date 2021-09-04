<?php
 
	//include('class/quivivoAPI.php');
	require('../class/qivivoAPIv2.php');
	
	$qivivo_user='******';
	$qivivo_pass='******';

	$target=$_GET["value"];
	
	//config.json
	$url = '../status/config.json';
	$jsonString = file_get_contents($url);
	$data = json_decode($jsonString, true);
	$data[targetHeatingCoolingState]=1;
	$data[targetTemperature]=$target;
	$newJsonString = json_encode($data);
	file_put_contents($url, $newJsonString);	
	

	
	$_qivivo = new qivivoAPI($qivivo_user, $qivivo_pass);
	if (isset($_qivivo->error)) echo $_qivivo->error;

	$_qivivo->cancelZoneOrder();
	$_qivivo->setTemperature($target,120);


?>
