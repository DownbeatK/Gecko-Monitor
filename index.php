<?php
	$dbConnection = mysqli_connect("<ADDRESS>", "<USERNAME>", "<PASSWORD>", "<DATABASE>");
	if($dbConnection->connect_error)
		die("Database connection failed: " . $dbConnection->connect_error);

	$query = mysqli_query($dbConnection, "SELECT * FROM (SELECT * FROM tblReadings ORDER BY colDateTime DESC LIMIT 24) sub ORDER BY colDateTime ASC")
		or die(mysqli_error($dbConnection));

	$arrTemperature = array();
	$arrHumidity = array();
	$arrDateTime = array();

	while($row = mysqli_fetch_array($query)) {
		$firstExplode = explode(" ", $row['colDateTime']);
		$secondExplode = explode(":", $firstExplode[1]);
		array_push($arrDateTime, date("g:i", strtotime(ltrim($secondExplode[0], '0') . ":" . $secondExplode[1])));
		array_push($arrTemperature, $row['colTemperature']);
		array_push($arrHumidity, $row['colHumidity']);
	}

	$dataTemp = array();
	$dataHum = array();
	$x = 0;

	foreach($arrDateTime as $dt) {
		array_push($dataTemp, array("label"=> $dt, "y"=> $arrTemperature[$x]));
		array_push($dataHum, array("label"=> $dt, "y"=> $arrHumidity[$x]));
		$x++;
	}

	if(min($arrHumidity) > min($arrTemperature))
		$chartMin = round(min($arrTemperature)) - 2;
	else
		$chartMin = round(min($arrHumidity)) - 2;

	if(max($arrHumidity) > max($arrTemperature))
		$chartMax = round(max($arrHumidity)) + 2;
	else
		$chartMax = round(max($arrTemperature)) + 2;
?>
<html>
	<head>
		<title>
			Gecko
		</title>
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
		<style>
			@font-face {
				font-family: Montserrat-Regular;
				src: url(Montserrat-Regular.otf);
			}
			body {
				font-family: Montserrat-Regular;
				background-color: #463F3A;
			}
			td {
				color: #CCCCCC;
				font-size: 32px;
			}
		</style>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" href="favicon.ico">
		<script src="canvasjs.min.js"></script>
	</head>
	<body>
<?php
	echo "<table align='center'><tr><td><img src='thermometer.png' width='64' height='64'></td><td>" . end($arrTemperature) . "&#176; F</td>";
	echo "<td><img src='humidity.png' width='64' height='64'></td><td>" . end($arrHumidity) . "%</td></tr></table>";
?>

		<div id="chartContainer"></div>
		<script>
			window.onload = function () {
				var chart = new CanvasJS.Chart("chartContainer", {
					backgroundColor: "#463F3A",
					axisY: {
						maximum: <?php echo $chartMax; ?>,
						minimum: <?php echo $chartMin; ?>,
						labelFontColor: "#CCCCCC",
					},
					axisY2: {
						axisYType: "secondary",
					},
					axisX: {
						labelFontColor: "#CCCCCC",
					},
					data: [{
						type: "line",
						dataPoints:<?php echo json_encode($dataHum, JSON_NUMERIC_CHECK); ?>
					}, {
						type: "line",
						dataPoints:<?php echo json_encode($dataTemp, JSON_NUMERIC_CHECK); ?>
					}]
				});
				chart.render();
			}
		</script>
	</body>
</html>
