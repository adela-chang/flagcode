<?php

/*

The purpose of this file is to demonstrate how to hash a password with Phpass.
This is useful when creating an account for a user.

Storing and retrieving the actual hash is out of the scope of this project,
for secure database interaction please check out github.com/sunnysingh/database

*/

require("PasswordHash.php");

$hasher = new PasswordHash(8, false);

// In a typical situation, you will have a form with the "method" attribute set to "post" with an input of name "password"
$name = $_POST["name"];
$password = $_POST["password"];

// Passwords should never be longer than 72 characters to prevent DoS attacks
if (strlen($password) > 72) { die("Password must be 72 characters or less"); }

// The $hash variable will contain the hash of the password
$hash = $hasher->HashPassword($password);

echo $hash;

$hasher2 = new PasswordHash(8, false);

if ($hasher2->CheckPassword($password, $hash)) {
	echo 'check matched using hasher 2';
} else {
	echo 'check failed :(';
}

/** 
 * Note: in a real app, the sn/pass to access the db should be secured from within the app
 * not displayed out in the open like it is below
 */
 
// connect to mysql database
$con=mysqli_connect("localhost","scanlat1_test","test1","scanlat1_flag");



// Check connection
if (mysqli_connect_errno($con))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }


if (strlen($hash) >= 20) {

 // store the value of $hash in a database or something
 
} else {

 // something went wrong
  
}

?>