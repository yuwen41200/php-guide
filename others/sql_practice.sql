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
	Dest = 'JFK' AND (ActualElapsedTime BETWEEN 1*60 AND 3*60);
-- +----------+
-- | COUNT(*) |
-- +----------+
-- |   303868 |
-- +----------+
-- 1 row in set (36.54 sec)

# Q3 - Please list both the aircraft type names and the amounts
#      whose aircraft type name begins with “737”
#      and contain “LIKE” syntax in the usage of SQL.

SELECT Model, COUNT(Model) FROM plane WHERE Model LIKE '737%' GROUP BY Model;
-- +-----------+--------------+
-- | Model     | COUNT(Model) |
-- +-----------+--------------+
-- | 737-230   |            1 |
-- | 737-236   |            4 |
-- | 737-282   |            1 |
-- | 737-282C  |            1 |
-- | 737-2P6   |            1 |
-- | 737-2X6C  |            1 |
-- | 737-2Y5   |            1 |
-- | 737-301   |            7 |
-- | 737-317   |            4 |
-- | 737-322   |           64 |
-- | 737-33A   |            4 |
-- | 737-3A4   |            9 |
-- | 737-3B7   |           16 |
-- | 737-3G7   |           20 |
-- | 737-3H4   |          147 |
-- | 737-3K2   |            2 |
-- | 737-3L9   |            2 |
-- | 737-3Q8   |            9 |
-- | 737-3S3   |            4 |
-- | 737-3T5   |            3 |
-- | 737-3TO   |           50 |
-- | 737-3Y0   |            6 |
-- | 737-401   |           13 |
-- | 737-490   |           16 |
-- | 737-4B7   |           27 |
-- | 737-4Q8   |           22 |
-- | 737-4S3   |            1 |
-- | 737-522   |           30 |
-- | 737-524   |           56 |
-- | 737-5H4   |           25 |
-- | 737-705   |            1 |
-- | 737-724   |           36 |
-- | 737-73A   |            2 |
-- | 737-76N   |           27 |
-- | 737-76Q   |            2 |
-- | 737-790   |           19 |
-- | 737-7AD   |            1 |
-- | 737-7BD   |           32 |
-- | 737-7H4   |          308 |
-- | 737-7Q8   |            2 |
-- | 737-824   |          100 |
-- | 737-832   |           68 |
-- | 737-890   |           33 |
-- | 737-8FH   |            1 |
-- | 737-924   |           12 |
-- | 737-924ER |            8 |
-- | 737-990   |           12 |
-- +-----------+--------------+
-- 47 rows in set (0.00 sec)

# Q4 - Please list the average speed (using the actual arrival time)
#      of all aircraft types and contain “AVG” in the usage of SQL.
# 
# The data are too huge for my computer (Intel Pentium 2020M w/ 3.7GiB RAM) to process.
# I had executed this query for more than 30 minutes
# and the system resources were almost used up.

ALTER TABLE ontime ADD INDEX TailNum (TailNum);
-- Query OK, 0 rows affected (7 min 27.65 sec)
-- Records: 0  Duplicates: 0  Warnings: 0
SELECT plane.Model, AVG(ontime.Distance/ontime.ActualElapsedTime*60) AS AvgSpeed
	FROM plane INNER JOIN ontime ON plane.TailNum = ontime.TailNum
	GROUP BY plane.Model;
-- ^CCtrl-C -- sending "KILL QUERY 5" to server ...
-- Ctrl-C -- query aborted.
-- ERROR 1317 (70100): Query execution was interrupted

# Q5 - Please list the top 50 timezone counts and routes (origin -> destination)
#      by the timezone count of routes (assume one timezone is formed by 15 degree
#      longitude and is not related to countries) in decreasing order
#      and contain “ORDER BY” in the usage of SQL.
# 
# The data are too huge for my computer (Intel Pentium 2020M w/ 3.7GiB RAM) to process.
# I had executed this query for more than 30 minutes
# and the system resources were almost used up.

SELECT DISTINCT
	FLOOR(ABS(temp1.Lon-temp2.Lon)/15) AS Timezone, ontime.Origin, ontime.Dest
	FROM ontime
	INNER JOIN airport AS temp1 ON ontime.Origin = temp1.Iata
	INNER JOIN airport AS temp2 ON ontime.Dest = temp2.Iata
	ORDER BY Timezone DESC, ontime.Origin ASC, ontime.Dest ASC
	LIMIT 0, 50;
-- ^CCtrl-C -- sending "KILL QUERY 5" to server ...
-- Ctrl-C -- query aborted.
-- ERROR 1317 (70100): Query execution was interrupted

# Q6 - Please list the aircraft types which never appeared after 2008 (appeared before)
#      and contain “NOT IN” syntax in the usage of SQL.

SELECT DISTINCT plane.Model FROM plane
	INNER JOIN ontime ON plane.TailNum = ontime.TailNum
	WHERE plane.Model NOT IN (
		SELECT DISTINCT plane.Model FROM plane
		INNER JOIN ontime ON plane.TailNum = ontime.TailNum
		WHERE ontime.Year >= 2008
	);
-- ^CCtrl-C -- sending "KILL QUERY 5" to server ...
-- Ctrl-C -- query aborted.
-- ERROR 1317 (70100): Query execution was interrupted
SELECT DISTINCT Model FROM plane WHERE TailNum IN (
	SELECT DISTINCT TailNum FROM ontime WHERE TailNum NOT IN (
		SELECT DISTINCT TailNum FROM ontime WHERE Year >= 2008
	)
);
-- Empty set (1 min 55.69 sec)
