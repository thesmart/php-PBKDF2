php-PBKDF2
==========

An implementation of PBKDF2 invented by RSA Laboratories. Useful for password stretching / strengthening.

The [technique](http://en.wikipedia.org/wiki/Key_stretching) is useful for making user passwords and keys much tougher to reverse.  This is very valuable for preventing [high profile](http://news.cnet.com/8301-1009_3-57448079-83/millions-of-linkedin-passwords-reportedly-leaked-online/) and [embarrassing](http://www.engadget.com/2011/06/02/sony-pictures-hacked-by-lulz-security-1-000-000-passwords-claim/) releases of user passwords.

For more detailed information, please visit the geniuses at RSA Labs:
[http://www.ietf.org/rfc/rfc2898.txt](http://www.ietf.org/rfc/rfc2898.txt).

Usage
-----

Usage of this library is very simple.

###Strengthen a new password

```php
$pass = $_POST['user_created_password'];
$salt = Pbkdf2::generateRandomSalt();
$passHash = Pbkdf2::hash($pass, $salt);
unset($pass);
// store $passHash and $salt in the database
```

###Test a password for match

```php
// get $passHash and $salt from the database
$isMatch = Pbkdf2::isMatch($_POST['user_password'], $passHash, $salt);
if ($isMatch) {
	// grant login attempt
} else {
	// reject login attempt
}
```

Additional Security
-----

You can also pass an optional arguments for additional security, with a trade-off of performance.

```php
define('CRAZY_LONG_HASH', 'p,gx>vrQ<ayWY9hCd8YZ3KJGNsczWddv?)rMCLVujcPX/=BGVE');
define('CRAZY_HASH_ITERATIONS', 100000);

$pass = $_POST['user_created_password'];
$salt = Pbkdf2::generateRandomSalt();
$passHash = Pbkdf2::hash($pass, $salt, CRAZY_HASH_ITERATIONS, CRAZY_LONG_HASH);
unset($pass);
// store $passHash and $salt in the database
```

Make sure you use the same number of iterations

```php
// get $passHash and $salt from the database
$isMatch = Pbkdf2::isMatch($_POST['user_password'], $passHash, $salt, CRAZY_HASH_ITERATIONS);
if ($isMatch) {
	// grant login attempt
} else {
	// reject login attempt
}
```