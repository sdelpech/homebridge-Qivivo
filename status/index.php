<?php
	
	//include('class/quivivoAPI.php');
	require('../class/qivivoAPIv2.php');
	
	//config.json
	$url = 'config.json';
	$jsonString = file_get_contents($url);
	$data = json_decode($jsonString, true);
	
	$qivivo_user=***********;
	$qivivo_pass=***********;
	
	
	$_qivivo = new qivivoAPI($qivivo_user, $qivivo_pass);
	if (isset($_qivivo->error)) echo $_qivivo->error;
	
	//get heating:
	$heating = $_qivivo->getHeating();
	//echo "<pre>_____>heating:<br>".json_encode($heating, JSON_PRETTY_PRINT)."</pre><br>";	
	
	//temperature
	$heating = $_qivivo->getHeating();
	$temp=$heating['result']['zones'][0]['temperature'];
	//print_r($temp);
	
	//consigne
	$heating = $_qivivo->getHeating();
	$consigne=$heating['result']['zones'][0]['set_point']['instruction'];
	
	//recuperation des températues prédefinies dans Comap
	$settings = $_qivivo->getTempSettings();
	
	if(is_float($consigne))
	{
		$consigne=$consigne;
		$data[targetHeatingCoolingState]=1;
	}
	else
	{
		if($consigne=="away")
		{
			$data[targetHeatingCoolingState]=0;
		}
		else
		{
			$data[targetHeatingCoolingState]=3;
		}
		$consigne=$settings['result']['custom_temperatures'][$consigne];
	}
	//status
	$heating = $_qivivo->getHeating();
	$status=$heating['result']['zones'][0]['heating_status'];
	//echo $status;
	switch ($status)
	{
		case "cooling":
			$status="0";
		break;
		case "heating":
			$status="1";
		break;
	}
	
	//humidité
	$heating = $_qivivo->getHeating();
	$hum=$heating['result']['zones'][0][humidity];
	//print_r($hum);
	

	$data[currentRelativeHumidity]=$hum;
	$data[currentTemperature]=$temp;
	$data[currentHeatingCoolingState]=$status;
	$data[targetTemperature]=$consigne;
	
	//print_r($data);
	$newJsonString = json_encode($data);
	file_put_contents($url, $newJsonString);
	
	$myfile = fopen($url, "r") or die("Unable to open file!");
	echo fread($myfile,filesize($url));
	fclose($myfile);
?>