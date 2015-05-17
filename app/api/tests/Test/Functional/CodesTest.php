<?php

namespace Test\Functional;

use \Api\Model\Codes;

class CodesTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->codes = '';
		$this->conn  = array(
			'servername' => 'localhost',
			'basename'   => 'gencode',
			'username'   => 'gencode',
			'password'   => 'MHFFssAqNL8SA8L7',
		);
	}

	public function testCreateCodes() {
		$codes = new Codes($this->codes, $this->conn);
	}

	public function testCodesMethods() {
		$codes = new Codes($this->codes, $this->conn);
		$res   = $codes->getActivated();
		echo "test res = "+$res;
		$this->assertEquals("test", $res);
	}
}