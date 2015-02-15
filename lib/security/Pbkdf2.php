<?php

namespace security;

/**
 * A class used to strengthen passwords against brute force attack.  This method is approved by the RSA as published in
 * http://www.ietf.org/rfc/rfc2898.txt - Sept 2000
 */
class Pbkdf2 {

	/**
	 * Number of SHA256 iterations to run
	 */
	const HASH_ITERATIONS	= 6000;

	/**
	 * Number of iterations to run to produce a random salt
	 */
	const SALT_ITERATIONS	= 10;

	/**
	 * Sounds more important to security that it is
	 */
	const POMPOUS_SECRET	= <<<TOKEN
vT@sw6b7,GD#orY8iQG%CbHLyzeziWFNWGnew=X]QuFfUtc(vP
TOKEN;

	/**
	 * Generate a random salt with plenty of entropy
	 *
	 * @static
	 * @param int $iterationCount	Optional. The number of times to run the operation (i.e. > 10000 times)
	 * @return string
	 */
	public static function generateRandomSalt($iterationCount = Pbkdf2::SALT_ITERATIONS) {
		if ($iterationCount < 10) {
			$iterationCount	= 10;
		}

		$rand	= array();
		for ($i = 0; $i < $iterationCount; ++$i) {
			$rand[]	= rand(0, 2147483647);
		}
		
		return strtolower(hash('sha256', implode('', $rand)));
	}

	/**
	 * Does the password match a hash?
	 *
	 * @static
	 * @param string $password		Plain-text password to hash using sha256
	 * @param string $hash			The sha256 hash to compare to
	 * @param string $salt			A consistent, secret random salt for the end-user
	 * @param int $iterationCount	Optional. The number of times to run the operation (i.e. > 10000 times)
	 * @return bool		Matches.
	 */
	public static function isMatch($password, $hash, $salt, $iterationCount = Pbkdf2::HASH_ITERATIONS, $secret = Pbkdf2::POMPOUS_SECRET) {
		$hashExpected	= self::hash($password, $salt, $iterationCount, $secret);
		return $hashExpected === $hash;
	}

	/**
	 * Hash a plain-text password, strengthening it to brute force.
	 *
	 * @static
	 * @param string $password		Plain-text password to hash using sha256
	 * @param string $salt			A consistent, secret random salt for the end-user
	 * @param int $iterationCount	Optional. The number of times to run the operation (i.e. > 10000 times)
	 * @param string $secret		Optional. A secret, known only to the application. This helps to add entropy.
	 * @return string
	 */
	public static function hash($password, $salt, $iterationCount = Pbkdf2::HASH_ITERATIONS, $secret = Pbkdf2::POMPOUS_SECRET) {
		$hash	= $password;
		for ($i = 0; $i < $iterationCount; ++$i) {
			$hash	= strtolower(hash('sha256', $secret . $hash . $salt));
		}

		return $hash;
	}
}
