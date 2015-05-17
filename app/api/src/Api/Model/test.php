<?php

//namespace Api\Model;

$servername = "localhost";
$basename   = 'gencode';
$username   = "gencode";
$password   = "MHFFssAqNL8SA8L7";

try {
	$conn = new \PDO("mysql:host=$servername;dbname=$basename", $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	echo "Connected successfully";

	$sql = "SELECT id, create_at FROM available";

	$stmt = $conn->prepare($sql);
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
	$rows   = $stmt->fetchAll();
	foreach ($rows as $row) {
		echo $row["id"]+" at "+$row["create_at"]+"\n";
	}

}
 catch (PDOException $e) {
	echo "Connection failed: ".$e->getMessage();
}
?>