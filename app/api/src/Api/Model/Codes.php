<?php

namespace Api\Model;

//use PDO;
//use RecursiveArrayIterator;

function guid() {
	if (function_exists('com_create_guid')) {
		return com_create_guid();
	} else {
		mt_srand((double) microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
		$uuid   = //chr(123)// "{"
		substr($charid, 0, 8).$hyphen
		.substr($charid, 8, 4).$hyphen
		.substr($charid, 12, 4).$hyphen
		.substr($charid, 16, 4).$hyphen
		.substr($charid, 20, 12);
		//.chr(125);// "}"
		return $uuid;
	}
}

class Codes {
	protected $codesAvailable;
	protected $servername;
	protected $username;
	protected $password;
	protected $conn;

	public function __construct($codes, $conn) {
		//$this->codes = $codes;
		$this->codesAvailable = array();
		// for ($i=0; $i < 10; $i++) {
		//  $key = array(
		//      'id' => guid(),
		//      'create_at' => date('Y-m-d'),
		//      );
		//  array_push($this->codesAvailable, $key);
		// }

		$this->servername = $conn['servername'];
		$this->basename   = $conn['basename'];
		$this->username   = $conn['username'];
		$this->password   = $conn['password'];

		try {
			$conn = new \PDO("mysql:host=$this->servername;dbname=$this->basename",
				$this->username, $this->password);
			// set the PDO error mode to exception
			$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		}
		 catch (PDOException $e) {
			throw new Exception(
				"Connection failed: ".$e->getMessage(), 404
			);
		}

	}

	public function __destruct() {
	}

	public function connect() {
		// Create connection
		try {
			$this->conn = new \PDO("mysql:host=$this->servername;dbname=$this->basename",
				$this->username, $this->password);
			// set the PDO error mode to exception
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		}
		 catch (PDOException $e) {
			throw new Exception(
				"Connection failed: ".$e->getMessage(), 404
			);
		}
	}

	public function disconnect() {
		$this->conn = null;
	}

	public function getAvailable() {
		$this->connect();

		$sql = "SELECT id, create_at FROM available";

		$this->codesAvailable = array();

		$stmt = $this->conn->prepare($sql);
		$stmt->execute();

		// set the resulting array to associative
		$result = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
		$rows   = $stmt->fetchAll();
		foreach ($rows as $row) {
			$key = array(
				'id'        => $row["id"],
				'create_at' => $row["create_at"],
			);
			array_push($this->codesAvailable, $key);
		}

		$this->disconnect();

		return $this->codesAvailable;
	}

	public function getSended() {

	}

	public function getActivated() {

	}

	public function getGenerateKeys() {
		$this->connect();

		$create_at = date('Y-m-d');
		for ($i = 0; $i < 5; $i++) {
			$id  = guid();
			$sql = "INSERT INTO available (id, create_at) VALUES ('$id', '$create_at')";

			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
		}
		$this->disconnect();
		return array(
			'state' => 'success',
		);
	}
}
