<?php

namespace Api\Model;

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
		// 	$key = array(
		// 		'id' => guid(),
		// 		'create_at' => date('Y-m-d'),
		// 		);
		// 	array_push($this->codesAvailable, $key);
		// }

		$this->servername = $conn['servername'];
		$this->username   = $conn['username'];
		$this->password   = $conn['password'];
	}

	public function __destruct() {
	}

	public function connect() {
		// Create connection
		$this->conn = new mysqli($this->servername,
			$this->username,
			$this->password,
			$this->basename);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: ".$conn->connect_error);
		}
	}

	public function disconnect() {
		$this->conn->close();
	}

	public function getAvailable() {
		connect();

		$sql    = "SELECT id, create_at FROM Available";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row

			$this->codesAvailable = array();

			while ($row = $result->fetch_assoc()) {
				$key = array(
					'id'        => $row["id"],
					'create_at' => $row["create_at"],
				);
			}
		} else {
			echo "0 results";
		}

		disconnect();

		return $this->codesAvailable;
	}

	public function getSended() {

	}

	public function getActivated() {

	}
}