<?php

namespace stache;

require_once __DIR__ . "/../vendor/autoload.php";

use security\Pbkdf2;

class Pbkdf2Test extends \PHPUnit_Framework_TestCase {

	public function testRandomSalt() {
		$saltA = Pbkdf2::generateRandomSalt(0);
		$this->assertNotEmpty($saltA);
		$this->assertNotEquals($saltA, Pbkdf2::generateRandomSalt(0));

		$saltB = Pbkdf2::generateRandomSalt(1);
		$this->assertNotEmpty($saltB);
		$this->assertNotEquals($saltA, Pbkdf2::generateRandomSalt(1));

		$saltC = Pbkdf2::generateRandomSalt();
		$this->assertNotEmpty($saltC);
		$this->assertNotEquals($saltA, Pbkdf2::generateRandomSalt());
	}

	public function testHash() {
		$hashA = Pbkdf2::hash('password', 'salt', 0);
		$this->assertEquals('password', $hashA);

		$hashB = Pbkdf2::hash('password', 'salt', 1);
		$this->assertNotEquals('password', $hashB);
	}

	public function testMatch() {
		$pass = '8A6FnUVeGY7?#v#egwz';
		$salt = Pbkdf2::generateRandomSalt();
		$passHash = Pbkdf2::hash($pass, $salt);

		$this->assertTrue(Pbkdf2::isMatch($pass, $passHash, $salt));

		$this->assertFalse(Pbkdf2::isMatch($pass.' ', $passHash, $salt));
	}
}