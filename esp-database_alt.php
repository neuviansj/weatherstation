
<?php

	$conn;

  	function connectDB()  {

		global $conn;

		$servername	= "localhost";
		$dbname 	= "";
		$username 	= "";
		$password 	= "";

		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error)  {
			die("Connection failed: " . $conn->connect_error);
		} 

	}


	function insertReading($fk_Sensor, $temperature, $humidity, $pressure) {
	
		global $conn;

		connectDB();

		$sql = "INSERT INTO SensorData (fk_Sensor, temperature, humidity, pressure)	VALUES ('" . $fk_Sensor . "', '" . $temperature . "', '" . $humidity . "', '" . $pressure . "')";

		if ($conn->query($sql) === TRUE)  {
			return "New record created successfully";
		}

		else  {
			return "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();
	}


	function getAllReadings($limit) {
	
		global $conn;

		connectDB();

		$sql = "SELECT * FROM SensorData order by time desc limit " . $limit;

		if ($result = $conn->query($sql))  {
			return $result;
		}

		else  {
			return false;
		}

		$conn->close();
	}


	function getLastReadings() {
	
		global $conn;

		connectDB();

		$sql = "SELECT * FROM SensorData order by time desc limit 1" ;

		if ($result = $conn->query($sql))  {
			return $result->fetch_assoc();
		}

		else  {
			return false;
		}

		$conn->close();
	}


	function minReading($limit, $value) {
	
		global $conn;

		connectDB();

		$sql = "SELECT MIN(" . $value . ") AS min_amount FROM (SELECT " . $value . " FROM SensorData order by time desc limit " . $limit . ") AS min";
	
		if ($result = $conn->query($sql))  {
			return $result->fetch_assoc();
		}

		else  {
			return false;
		}
		
		$conn->close();
	}


	function maxReading($limit, $value) {

		global $conn;

		connectDB();

		$sql = "SELECT MAX(" . $value . ") AS max_amount FROM (SELECT " . $value . " FROM SensorData order by time desc limit " . $limit . ") AS max";

		if ($result = $conn->query($sql))  {
			return $result->fetch_assoc();
		}

		else  {
			return false;
		}
		
		$conn->close();
	}


	function avgReading($limit, $value)  {

		global $conn;

		connectDB();

		$sql = "SELECT AVG(" . $value . ") AS avg_amount FROM (SELECT " . $value . " FROM SensorData order by time desc limit " . $limit . ") AS avg";

		if ($result = $conn->query($sql))  {
			return $result->fetch_assoc();
		}

		else  {
			return false;
		}
		
		$conn->close();
	}


?>
