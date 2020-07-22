<?php
	$dbConnection = mysqli_connect("localhost", "user", "pass", "database");
	if($dbConnection->connect_error)
		die("Database connection failed: " . $dbConnection->connect_error);

	$query = mysqli_query($dbConnection, "SELECT * FROM tblReadings LIMIT 144")
		or die(mysqli_error($dbConnection));

	$arrTemperature = "";
	$arrHumidity = "";
	$arrDateTime = "";

	while($row = mysqli_fetch_array($query)) {
		$firstExplode = explode(" ", $row['colDateTime']);
		$secondExplode = explode(":", $firstExplode[1]);
		$arrDateTime .= "\"" . date("g:i", strtotime(ltrim($secondExplode[0], '0') . ":" . $secondExplode[1])) . "\", ";
		$arrTemperature .= $row['colTemperature'] . ", ";
		$arrHumidity .= $row['colHumidity'] . ", ";
	}

	$arrTemperature = substr($arrTemperature, 0, -2);
	$arrHumidity = substr($arrHumidity, 0, -2);
	$arrDateTime = substr($arrDateTime, 0, -2);
?>
<html>
	<head>
		<title>
			Gecko
		</title>
		<style>
			@font-face {
				font-family: OpenSans-Regular;
				src: url(OpenSans-Regular.ttf);
			}
			@font-face {
				font-family: OpenSans-Semibold;
				src: url(OpenSans-Semibold.ttf);
			}
			body {
				background-color: #463F3A;
			}
			td {
				font-family: OpenSans-Regular;
				color: #CCCCCC;
				font-weight: bold;
				font-size: 32px;
				height: 50px;
				padding: 10px 5px;
			}
			#good {
				color: #00FF00;
			}
			#bad {
				color: #FF0000;
			}
		</style>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="favicon.ico">
		<script src="canvasjs.min.js"></script>
	</head>
	<body>
<?php
	echo "<table align='center'><tr><td><img src='thermometer.png' width='64' height='64'></td><td>" . substr($arrTemperature, -4) . "&#176; F</td>";
	echo "<td><img src='humidity.png' width='64' height='64'></td><td>" . substr($arrHumidity, -4) . "%</td></tr></table>";
?>
		<script>
			window.onload = function() {
				
			}
		</script>
	</body>
</html>
