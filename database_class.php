<?php
	class Database
	{
		private $host, $username, $password, $dbname;
		public $sql;
		function __construct($_host, $_username, $_password, $_dbname)
		{
			$this->host = $_host;
			$this->username = $_username;
			$this->password = $_password;
			$this->dbname = $_dbname;
		}
		function connect()
		{
			$this->sql = mysqli_connect($this->host, $this->username, $this->password, $this->dbname);
			if(mysqli_connect_errno())
				exit("Connect Error: ".mysqli_connect_error());
			if(!mysqli_set_charset($this->sql, "utf8"))
				exit("Error Loading Character Set: ".mysqli_error($this->sql));
		}
		function disconnect()
		{
			mysqli_close($this->sql);
		}
		function reconnect()
		{
			$this->disconnect();
			$this->connect();
		}
		function status()
		{
			print_r(mysqli_get_connection_stats($this->sql));
		}
		function __destruct()
		{
			$this->disconnect();
		}
	}
	#usage
	#$db = new Database("localhost", "root", "yourpassword", "database_name");
	#$db->connect();
	#$db->sql->query("show tables;");
	#$db->reconnect();
?>
