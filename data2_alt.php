<?php
	//setting header to json
	header('Content-Type: application/json');

	//database
	define('DB_HOST', 'localhost');
	define('DB_USERNAME', '');
	define('DB_PASSWORD', '');
	define('DB_NAME', '');


	$GETDate = htmlspecialchars(stripslashes(trim($_GET['dat'])));
	
	date_default_timezone_set("Europe/Lisbon");

	$dateStart	= date_create($GETDate);
	$dateEnd	= date_create($GETDate);

	$dateStart	= date_time_set($dateStart,00,00,00);
	$dateEnd	= date_time_set($dateEnd,23,59,59);

	//Timezone adjust
	$dateStart->add(new DateInterval("PT1H"));			
	$dateEnd->add(new DateInterval("PT1H"));


	//get connection
	$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if(!$mysqli){
	  die("Connection failed: " . $mysqli->error);
	}

	//query to get data from the table
	//$query = sprintf("SELECT * FROM SensorData WHERE time BETWEEN '".$dat." 00:00:00' and '".$dat." 23:59:59' AND fk_Sensor = '1' ORDER BY time");
	$query = sprintf("SELECT SQL_NO_CACHE * FROM SensorData WHERE time BETWEEN '".date_format($dateStart, "Y-m-d H:i:s")."' and '".date_format($dateEnd, "Y-m-d H:i:s")."' AND fk_Sensor = '1' ORDER BY time");


	//execute query
	$result = $mysqli->query($query);

	//loop through the returned data
	$data = array();
	foreach ($result as $row) {
	  $data[] = $row;
	}

	//free memory associated with result
	$result->close();

	//close connection
	$mysqli->close();

	//now print the data
	if (!empty($data))
	print json_encode($data);
   
?>
