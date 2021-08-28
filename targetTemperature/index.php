<?php
 
	//include('class/quivivoAPI.php');
	require('../class/qivivoAPIv2.php');
	
	$qivivo_user=***********;
	$qivivo_pass=***********;

	$target=$_GET["value"];
	
	//config.json
	$url = '../status/config.json';
	$jsonString = file_get_contents($url);
	$data = json_decode($jsonString, true);
	$data[targetTemperature]=$target;
	$data[currentTemperature]=$target;
	$newJsonString = json_encode($data);
	file_put_contents($url, $newJsonString);	
	

	
	$_qivivo = new qivivoAPI($qivivo_user, $qivivo_pass);
	if (isset($_qivivo->error)) echo $_qivivo->error;

	//set thermostat temperature (Second argument is duration in minutes, can be omitted default 120. Last argument not necessary if one thermostat only):
	$_qivivo->cancelZoneOrder();
	$_qivivo->setTemperature($target,120);


?>
