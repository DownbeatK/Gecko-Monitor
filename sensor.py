#!/usr/bin/python
import sys, time, Adafruit_DHT, mysql.connector as mariadb
from datetime import datetime

humidity, temperature = Adafruit_DHT.read_retry(Adafruit_DHT.AM2302, 3)
temperature = temperature * 9/5.0 + 32

if humidity is not None and temperature is not None:

	revised_temp = "{0:0.1f}".format(temperature)
	revised_hum = "{0:0.1f}".format(humidity)

	mariadb_connection = mariadb.connect(host='localhost', port='3306', user='user', password='pass', database='database')
	cursor = mariadb_connection.cursor()
	try:
		now = datetime.now()
		cursor.execute("INSERT INTO tblReadings (colDateTime, colTemperature, colHumidity) VALUES (%s, %s, %s)", (now.strftime("%Y-%m-%d %H:%M:%S"), revised_temp, revised_hum))
	except mariadb.Error as error:
		print("Error: {}".format(error))
	mariadb_connection.commit()
else:
	sys.exit(1)
