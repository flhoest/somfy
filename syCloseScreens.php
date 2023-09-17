<?php

  // This small script is showing how to use the syFramework.php. It actually closes my 3 screens (left, center and right) for a duration of 10 secs and then stop them.
  // The result is : my screens are approximatively closed at 50%

	include_once 'syFramework.php';

	syScreenDown($tahomaPin,$token,"io://20xx-30xx-05xx/16249868");
	syScreenDown($tahomaPin,$token,"io://20xx-30xx-05xx/5247014");
	syScreenDown($tahomaPin,$token,"io://20xx-30xx-05xx/6245842");

	sleep(10);
	
	// Stop all screens

	syScreenStop($tahomaPin,$token,"io://20xx-30xx-05xx/16249868");
	syScreenStop($tahomaPin,$token,"io://20xx-30xx-05xx/5247014");
	syScreenStop($tahomaPin,$token,"io://20xx-30xx-05xx/6245842");


?>
