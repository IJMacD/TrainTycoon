<?php

class Debug {
	
	function log($message, $level=1)
	{
		if(isset($_GET['debug']))
		{
			if(strlen($_GET['debug']))
				$debug = $_GET['debug'];
			else
				$debug = 1;
		}
		else
			$debug = 0;
		
		if($debug >= $level)
			echo $message."<br>\n";
	}
}
$debug = new Debug;