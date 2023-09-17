<?php

	// Some variable definition
	
	$endPointURL="https://ha101-1.overkiz.com/";
	$tahomaPin="20xx-30xx-05xx";
	$localTahomaIP="https://gateway-".$tahomaPin.".local:8443";
	$token="650635243e54fd50afb0";    // <- fake one of course

	$syUsername="my_user_name"	;
	$syPassword="my_password";
	
	$infra=array(
		0 => array(
			"name" => "RSCREEN",
			"url" => "io://20xx-30xx-05xx/5247014"),
		1 => array(
			"name" => "LSCREEN",
			"url" => "io://20xx-30xx-05xx/16249868"),
		2 => array(
			"name" => "CSCREEN",
			"url" => "io://20xx-30xx-05xx/6245842"),
		3 => array(
			"name" => "RLED",
			"url" => "io://20xx-30xx-05xx/5839490"),
		4 => array(
			"name" => "LLED",
			"url" => "io://20xx-30xx-05xx/5101624"),
		5 => array(
			"name" => "ROOF",
			"url" => "io://20xx-30xx-05xx/415298")
			);

?>
