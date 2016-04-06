# $ lsb_release -d
# Description:	Ubuntu 15.10
# 
# $ free -h
#              total       used       free     shared    buffers     cached
# Mem:          3.7G       2.6G       1.1G       257M       108M       1.4G
# -/+ buffers/cache:       1.1G       2.7G
# 置換：         1.9G       642M       1.2G
# 
# $ df -h
# 檔案系統          容量   已用  可用  已用% 掛載點
# udev            1.9G     0  1.9G    0% /dev
# tmpfs           384M  6.4M  378M    2% /run
# /dev/sda6       183G   45G  130G   26% /
# tmpfs           1.9G  4.7M  1.9G    1% /dev/shm
# tmpfs           5.0M  4.0K  5.0M    1% /run/lock
# tmpfs           1.9G     0  1.9G    0% /sys/fs/cgroup
# /dev/sda1       296M   58M  239M   20% /boot/efi
# /dev/sda9       119G  4.9G  108G    5% /home
# tmpfs           384M   80K  384M    1% /run/user/1000
# 
# $ mysql -u root -p --local-infile

# Q0 - Import the datasets.

SHOW DATABASES;
USE assignments;
CREATE TABLE ontime (
	Year INT, Month INT, DayOfMonth INT, DayOfWeek INT, DepTime INT,
	CRSDepTime INT, ArrTime INT, CRSArrTime INT, UniqueCarrier VARCHAR(5),
	FlightNum INT, TailNum VARCHAR(8), ActualElapsedTime INT, CRSElapsedTime INT,
	AirTime INT, ArrDelay INT, DepDelay INT, Origin VARCHAR(3), Dest VARCHAR(3),
	Distance INT, TaxiIn INT, TaxiOut INT, Cancelled INT, CancellationCode VARCHAR(1),
	Diverted VARCHAR(1), CarrierDelay INT, WeatherDelay INT, NASDelay INT,
	SecurityDelay INT, LateAircraftDelay INT
);
SHOW FULL COLUMNS FROM ontime;
LOAD DATA LOCAL INFILE '/tmp/2004.csv'
	INTO TABLE ontime
	FIELDS TERMINATED BY ','
	LINES TERMINATED BY '\n'
	IGNORE 1 LINES;
-- Query OK, 7129270 rows affected, 65535 warnings (3 min 7.46 sec)
-- Records: 7129270  Deleted: 0  Skipped: 0  Warnings: 821678
SHOW WARNINGS LIMIT 10;
LOAD DATA LOCAL INFILE '/tmp/2005.csv'
	INTO TABLE ontime
	FIELDS TERMINATED BY ','
	LINES TERMINATED BY '\n'
	IGNORE 1 LINES;
-- Query OK, 7140596 rows affected, 65535 warnings (3 min 10.90 sec)
-- Records: 7140596  Deleted: 0  Skipped: 0  Warnings: 858492
SHOW WARNINGS LIMIT 10;
LOAD DATA LOCAL INFILE '/tmp/2006.csv'
	INTO TABLE ontime
	FIELDS TERMINATED BY ','
	LINES TERMINATED BY '\n'
	IGNORE 1 LINES;
-- Query OK, 7141922 rows affected, 65535 warnings (3 min 12.79 sec)
-- Records: 7141922  Deleted: 0  Skipped: 0  Warnings: 796352
SHOW WARNINGS LIMIT 10;
LOAD DATA LOCAL INFILE '/tmp/2007.csv'
	INTO TABLE ontime
	FIELDS TERMINATED BY ','
	LINES TERMINATED BY '\n'
	IGNORE 1 LINES;
-- Query OK, 7453215 rows affected, 65535 warnings (3 min 22.57 sec)
-- Records: 7453215  Deleted: 0  Skipped: 0  Warnings: 1034198
SHOW WARNINGS LIMIT 10;
LOAD DATA LOCAL INFILE '/tmp/2008.csv'
	INTO TABLE ontime
	FIELDS TERMINATED BY ','
	LINES TERMINATED BY '\n'
	IGNORE 1 LINES;
-- Query OK, 7009728 rows affected, 65535 warnings (3 min 25.98 sec)
-- Records: 7009728  Deleted: 0  Skipped: 0  Warnings: 28602754
SHOW WARNINGS LIMIT 10;
ALTER TABLE ontime ADD INDEX Year (Year);
-- Query OK, 0 rows affected (5 min 30.55 sec)
-- Records: 0  Duplicates: 0  Warnings: 0
ALTER TABLE ontime ADD INDEX Date (Year, Month, DayofMonth);
-- Query OK, 0 rows affected (9 min 18.92 sec)
-- Records: 0  Duplicates: 0  Warnings: 0
ALTER TABLE ontime ADD INDEX Origin (Origin);
-- Query OK, 0 rows affected (6 min 7.66 sec)
-- Records: 0  Duplicates: 0  Warnings: 0
ALTER TABLE ontime ADD INDEX Dest (Dest);
-- Query OK, 0 rows affected (6 min 38.85 sec)
-- Records: 0  Duplicates: 0  Warnings: 0
CREATE TABLE plane (
	TailNum VARCHAR(7), Type VARCHAR(19), Manufacturer VARCHAR(30), IssueDate DATE,
	Model VARCHAR(17), Status VARCHAR(17), AircraftType VARCHAR(24),
	EngineType VARCHAR(13), Year INT
);
SHOW FULL COLUMNS FROM plane;
LOAD DATA LOCAL INFILE '/tmp/plane-data.csv'
	INTO TABLE plane
	FIELDS TERMINATED BY ','
	LINES TERMINATED BY '\n'
	IGNORE 1 LINES
	(TailNum, Type, Manufacturer, @var, Model, Status, AircraftType, EngineType, Year)
	SET IssueDate = STR_TO_DATE(@var, '%m/%d/%Y');
-- Query OK, 5029 rows affected, 3989 warnings (1.66 sec)
-- Records: 5029  Deleted: 0  Skipped: 0  Warnings: 3989
SHOW WARNINGS LIMIT 10;
CREATE TABLE airport (
	Iata VARCHAR(4), Airport VARCHAR(41), City VARCHAR(33), State VARCHAR(2),
	Country VARCHAR(30), Lat NUMERIC(11, 8), Lon NUMERIC(12, 8)
);
SHOW FULL COLUMNS FROM airport;
LOAD DATA LOCAL INFILE '/tmp/airports.csv'
	INTO TABLE airport
	FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
	LINES TERMINATED BY '\n'
	IGNORE 1 LINES;
-- Query OK, 3376 rows affected (0.78 sec)
-- Records: 3376  Deleted: 0  Skipped: 0  Warnings: 0

# Q1 - Please list the number of all different routes (origin -> destination)
#      and contain “DISTINCT” syntax in the usage of SQL.

SELECT COUNT(*) FROM (SELECT DISTINCT Origin, Dest FROM ontime) AS temp;
-- +----------+
-- | COUNT(*) |
-- +----------+
-- |     6901 |
-- +----------+
-- 1 row in set (2 min 1.59 sec)

# Q2 - Please list the number of flights whose destination is JFK
#      and the actual flight time is between 1 to 3 hours
#      and contain “BETWEEN” syntax in the usage of SQL.

SELECT COUNT(*) FROM ontime WHERE
	Dest = 'JFK' AND (ActualElapsedTime BETWEEN 1 AND 3);
-- +----------+
-- | COUNT(*) |
-- +----------+
-- |        1 |
-- +----------+
-- 1 row in set (6.08 sec)
