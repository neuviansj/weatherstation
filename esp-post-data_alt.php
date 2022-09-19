
<?php
	include_once('esp-database.php');

	$api_key_value 	= "";
	$api_key 		= $fk_Sensor  = $temperature = $humidity = $pressure = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST")  {

		$api_key = esc($_POST["api_key"]);

		if($api_key == $api_key_value)  {

			$fk_Sensor 		= esc($_POST["fk_Sensor"]);
			$temperature 	= esc($_POST["temperature"]);
			$humidity 		= esc($_POST["humidity"]);
			$pressure 		= esc($_POST["pressure"]);

			$result = insertReading($fk_Sensor, $temperature, $humidity, $pressure);
			echo $result;
		}

		else  {
			echo "Wrong API Key provided.";
		}

	}

	else  {
		echo "No data posted with HTTP POST.";
	}



	function test_input($data)  {

		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;

	}

	function esc($data)  {

		return htmlspecialchars(stripslashes(trim($data)));

	}

?>
