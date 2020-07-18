CREATE TABLE tblReadings(
	colDateTime DATETIME NOT NULL,
	colTemperature DECIMAL(3,1) NOT NULL,
	colHumidity DECIMAL(3,1) NOT NULL,
	PRIMARY KEY (colDateTime)
	);
