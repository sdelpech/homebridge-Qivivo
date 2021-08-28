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
	


	

	
	$_qivivo = new qivivoAPI($qivivo_user, $qivivo_pass);
	if (isset($_qivivo->error)) echo $_qivivo->error;
	
	//set thermostat temperature (Second argument is duration in minutes, can be omitted default 120. Last argument not necessary if one thermostat only):

	switch($target){
		case 0:
			// OFF
			$data[targetHeatingCoolingState]=$target;		
			$_qivivo->cancelZoneOrder();
			$_qivivo->setAway();
		case 1:
			// TEMPO
			$data[targetHeatingCoolingState]=$target;
			$_qivivo->cancelZoneOrder();
			//consigne
			$heating = $_qivivo->getHeating();
			$consigne=$heating['result']['zones'][0]['set_point']['instruction'];			
			//recuperation des températues prédefinies dans Comap
			$settings = $_qivivo->getTempSettings();
			
			if(is_float($consigne))
			{
				$consigne=$consigne;
			}
			else
			{
				$consigne=$settings['result']['custom_temperatures'][$consigne];
			}
			$_qivivo->setTemperature($target,120);
		case 3:
			// AUTO
			$data[targetHeatingCoolingState]=$target;
			$_qivivo->cancelZoneOrder();
	}
	echo $target;
	
	$newJsonString = json_encode($data);
	file_put_contents($url, $newJsonString);	
?>