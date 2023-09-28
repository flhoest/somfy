<?php

	include_once "syFramework.php";

	$domoticzIP="<your_domoticz_ip>";
	$sensorID=<your_sensor_id>;
	$maxTemp=<your_max_temperature>;
	$roofTimer="<your_timer_moving_the_roof>";
	$roofAddress="<your_roof_address>";

	// Set the default time zone for data-based functions
	date_default_timezone_set("Europe/Brussels");
	
	//--------------------------------------------------------------------
	// Function retrieving the sunrise and sunset time for the current day 
	//--------------------------------------------------------------------

	function dzGetSunRiseSet($domoticzIP)
	{
		$api="/json.htm?type=command&param=getSunRiseSet";		

    		$curl = curl_init($api);

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 		// Return the response as a string
		curl_setopt($curl, CURLOPT_URL, "https://".$domoticzIP.$api);

		$result = json_decode(curl_exec($curl));
    		curl_close($curl);

		return($result);
	
	}

	//---------------------------------------------------------------
	// Function retrieving the values from a specific Domoticz sensor
	//---------------------------------------------------------------

	function dzGetSensor($domoticzIP,$idx)
	{
		$api="/json.htm?type=devices&rid=".$idx;		

    		$curl = curl_init($api);

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 		// Return the response as a string
		curl_setopt($curl, CURLOPT_URL, "https://".$domoticzIP.$api);

		$result = json_decode(curl_exec($curl));
    		curl_close($curl);

		return($result);
	
	}

	//===========================================
	// ENTRY POINT
	//===========================================

	/*
		This script open the roof slats of a Somfy-based bioclimatic pergola if : 
			- This is daytime
			- Temperature is above or equal $maxTemp
			- The roof is closed
	*/

	
	// ++++++++++++++++++++++
	// Get actual temperature
	// ++++++++++++++++++++++
	
	$temp=dzGetSensor($domoticzIP,$sensorID);
	// Get relevant field
	$temp=$temp->result[0]->Data;
	// Only need temperature 
	$temp=explode(",",$temp)[0];
	// remove leading " C" and make sure this is a number
	$currTemperature=(float)substr($temp, 0, strlen($temp)-2);

	// +++++++++++++++++++
	// Get dailight status
	// +++++++++++++++++++
		
	$times=dzGetSunRiseSet($domoticzIP);
	$sunSet=$times->Sunset;
	$sunRise=$times->Sunrise;
	$currTime=date('H:i:s');

	// +++++++++++++++++
	// Get roof position
	// +++++++++++++++++

	$currRoofPos=syGetDeviceState($tahomaPin,$token,"io://2022-3032-0596/415298");

	// Need to address right key in the array, this is not always the same. Sometimes 4 sometimes 5
	for($i=0;$i<count($currRoofPos);$i++)
	{
    		// print($currRoofPos[$i]->name."\n");
		if($currRoofPos[$i]->name == "core:ManufacturerSettingsState") $tmp=$currRoofPos[$i]->value->current_position;
	}
	$currRoofPos=$tmp;
	
	print("Starting roof routine...\n");
	
	// Check if we are in daytime
	if($currTime>$sunRise and $currTime<$sunSet)
	{
		print("This is day time.\n");
		if($currRoofPos==51200)
		{
			print("Roof is closed.\n");
			if($currTemperature>=$maxTemp)
			{
				print("It is hot (".$currTemperature."°C), let's open the roof\n");
				
				syRoofOpen($tahomaPin,$token,$roofAddress);
				sleep($roofTimer);
				syRoofStop($tahomaPin,$token,$roofAddress);

			}
			else print("It is not warm enough (".$currTemperature."°C), let's keep roof closed\n\n");
		}
		else print("Roof is open.\n");
	}
	else print("This is night time.\n");
	
?>
