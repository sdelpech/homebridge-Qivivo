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
	$state=$data[targetHeatingCoolingState];

	$_qivivo = new qivivoAPI($qivivo_user, $qivivo_pass);
	if (isset($_qivivo->error)) echo $_qivivo->error;
	
	switch($target){
		case 0:
			// OFF
			$data[targetHeatingCoolingState]=$target;
			if($state==1)
				$_qivivo->cancelZoneOrder();
			$_qivivo->setAway();
		break;
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
			echo $consigne;
			$_qivivo->setTemperature($consigne,120);
		break;
		case 3:
			// AUTO
			$data[targetHeatingCoolingState]=$target;
			if($state==1)
				$_qivivo->cancelZoneOrder();
			if($state==0)
				$_qivivo->cancelAway();
		break;
	}
	echo $target;
	
	$newJsonString = json_encode($data);
	file_put_contents($url, $newJsonString);	
?>