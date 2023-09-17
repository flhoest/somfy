<?php

	/*
	  _________                ___________        
	 /   _____/  ____    _____ \_   _____/___.__. 
	 \_____  \  /  _ \  /     \ |    __) <   |  | 
	 /        \(  <_> )|  Y Y  \|     \   \___  | 
	/_______  / \____/ |__|_|  /\___  /   / ____| 
		\/               \/     \/    \/      
											  
					(c) 2023 - Frederic Lhoest  v 0.1 
	*/

	include_once "syCredentials.php";

	// Function index in alphabetical order (total 12)
	//------------------------------------------------

	// syGetDevices($tahomaPin,$token)
	// syGetToken($syUsername,$syPassword)
	// syLedOff($tahomaPin,$token,$LEDiD)
	// syLedOn($tahomaPin,$token,$LEDiD)
	// syLedSetIntensity($tahomaPin,$token,$LEDiD,$intensity)
	// syRoofClose($tahomaPin,$token,$roofID)
	// syRoofOpen($tahomaPin,$token,$roofID)
	// syRoofSetOrientation($tahomaPin,$token,$roofID,$position)
	// syRoofStop($tahomaPin,$token,$roofID)
	// syScreenDown($tahomaPin,$token,$screenID)
	// syScreenStop($tahomaPin,$token,$screenID)
	// syScreenUp($tahomaPin,$token,$screenID)
		
	// --------------------------------------------------------
	// Function to generate a token on the Somfy cloud platform
	// --------------------------------------------------------

	function syGetToken($syUsername,$syPassword)
	{
		global $endPointURL, $tahomaPin, $localTahomaIP;
		
		// ------------------
		// 1) Get Cookie ID
		// ------------------

		$api=$endPointURL."enduser-mobile-web/enduserAPI/login";
    $curl = curl_init($api);

		$data = array('userId' => $syUsernamz, 'userPassword' => $syPassword);
		$headers = array('Content-Type: application/x-www-form-urlencoded');

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_POST, true); 				
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data)); 

		$result = curl_exec($curl);
   	curl_close($curl);

   	$cookies = array();

		preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
		$cookies = array();
		foreach($matches[1] as $item) 
		{
			parse_str($item, $cookie);
			$cookies = array_merge($cookies, $cookie);
		}
		
		$sessionCookie=$cookies["JSESSIONID"];
		
		// ------------------
		// 2) Create token
		// ------------------
		
		$api=$endPointURL."enduser-mobile-web/enduserAPI/config/".$tahomaPin."/local/tokens/generate";				
    $curl = curl_init($api);

		$headers = array('Cookie: JSESSIONID='.$sessionCookie);		

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 		// Return the response as a string
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 		// Set custom headers

		$result = json_decode(curl_exec($curl));
    curl_close($curl);
    	
    $token=$result->token;
    	
		// ---------------------
		// 3) Activate the token
		// ---------------------

		$api=$endPointURL."enduser-mobile-web/enduserAPI/config/".$tahomaPin."/local/tokens";				
    $curl = curl_init($api);
    	
    $payLoad='{"label": "MyToken","token": "'.$token.'","scope": "devmode"}';
		$headers = array('Cookie: JSESSIONID='.$sessionCookie,'Content-Type: application/json','Cookie: JSESSIONID='.$sessionCookie);		

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 		// Return the response as a string
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); 		// Set custom headers
		curl_setopt($curl, CURLOPT_POST, true); 				// Set the request method to POST
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payLoad); 

		$result = curl_exec($curl);
    curl_close($curl);

		return($token);
	}

	// --------------------------------
	// Function to fully retract screen
	// --------------------------------

	function syScreenUp($tahomaPin,$token,$screenID)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"undeploy\"
				}
			  ],
			  \"deviceURL\": \"".$screenID."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}

	// -------------------------
	// Function to deploy screen
	// -------------------------

	function syScreenDown($tahomaPin,$token,$screenID)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"down\",
				  \"parameters\": [
					\"1\"
				  ]
				}
			  ],
			  \"deviceURL\": \"".$screenID."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}

	// ------------------------------
	// Function to stop moving screen
	// ------------------------------

	function syScreenStop($tahomaPin,$token,$screenID)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"stop\"
				}
			  ],
			  \"deviceURL\": \"".$screenID."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}
	
	// ---------------------
	// Function to open roof
	// ---------------------

	function syRoofOpen($tahomaPin,$token,$roofID)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"openSlats\"
				}
			  ],
			  \"deviceURL\": \"".$roofID."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}

	// ----------------------
	// Function to close roof
	// ----------------------

	function syRoofClose($tahomaPin,$token,$roofID)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"closeSlats\"
				}
			  ],
			  \"deviceURL\": \"".$roofID."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}

	// --------------------------------
	// Function to stop moving the roof
	// --------------------------------

	function syRoofStop($tahomaPin,$token,$roofID)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"stop\"
				}
			  ],
			  \"deviceURL\": \"".$roofID."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}

	// --------------------------------
	// Function to set roof orientation     <= not working
	// --------------------------------

	function syRoofSetOrientation($tahomaPin,$token,$roofID,$position)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"setOrientation\",
				  \"parameters\": [
					\"".$position."\"
					]
				}
			  ],
			  \"deviceURL\": \"".$roofID."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}

	// -------------------------
	// Function to switch LED on
	// -------------------------

	function syLedOn($tahomaPin,$token,$LEDiD)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"on\"
				}
			  ],
			  \"deviceURL\": \"".$LEDiD."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}
	
	// --------------------------
	// Function to switch LED off
	// --------------------------

	function syLedOff($tahomaPin,$token,$LEDiD)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"off\"
				}
			  ],
			  \"deviceURL\": \"".$LEDiD."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}
	
	// -----------------------------
	// Function to set LED intensity
	// -----------------------------

	function syLedSetIntensity($tahomaPin,$token,$LEDiD,$intensity)
	{
		$API="/enduser-mobile-web/1/enduserAPI/exec/apply";

		$config_params=" {
		  \"label\": \"myAction\",
		  \"actions\": [
			{
			  \"commands\": [
				{
				  \"name\": \"setIntensity\",
				  \"parameters\": [
					\"".$intensity."\"
					]
				}
			  ],
			  \"deviceURL\": \"".$LEDiD."\"
			}
		  ]
		}";
  
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$config_params);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($config_params),'Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$API);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);
		curl_close($curl);
		
		return $result;	
	}
	
	// -----------------------------------------
	// Function to get list of available devices
	// -----------------------------------------

	function syGetDevices($tahomaPin,$token)
	{
		
		$api="/enduser-mobile-web/1/enduserAPI/setup/devices";				
    	$curl = curl_init($api);

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Bearer '.$token));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 		// Return the response as a string
		curl_setopt($curl, CURLOPT_URL, "https://gateway-".$tahomaPin.".local:8443".$api);

		$result = json_decode(curl_exec($curl));
    	curl_close($curl);
    	
		$devices=array();
		for($i=0;$i<count($result);$i++)
		{
			$devices[$i]["name"]=$result[$i]->label;
			$devices[$i]["url"]=$result[$i]->deviceURL;		
			$devices[$i]["type"]=$result[$i]->definition->widgetName;		
				
		}

		return($devices);
	
	}

?>
