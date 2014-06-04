<?php

require 'dbconnect.php';
require 'PasswordHash.php';
include 'redirect.php';
ob_start();
if (isset($_POST['formsubmitted'])) {
	// Initialize a session:
	session_start();
	$error = array(); //this array will store all error messages
	if (empty($_POST['username'])) {
		$error[] = 'Please enter your username.';
	} else {
		$username = $_POST['username'];
	}

	if (empty($_POST['password'])) {
		$error[] = 'Please enter your password ';
	} else {
		$password = $_POST['password'];
	}

	if (empty($error)) //if the array is empty , it means no error found
	{
		$query_check_credentials = "SELECT * FROM PLAYERS WHERE Player_Name='$username' AND Activation IS NULL";
		$result_check_credentials = mysqli_query($con, $query_check_credentials);
		if(!$result_check_credentials){ //If the Query Failed
			echo 'Query Failed ';
		}
		if (@mysqli_num_rows($result_check_credentials) == 1)//if Query is successfull
		{ // A match was made.
			$result = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);
			$hasher = new PasswordHash(8, false);
			$check = $hasher->CheckPassword($password, $result['Password']);
			if ($check) {
				$_SESSION = $result; //Assign the result of this query to SESSION Global Variable	
				echo 'Authentication successful! Return to <a href="demos.php">Demos</a>.';
			} else {
				
			}

		}
		else
		{
			$msg_error= 'Either your account is inactive or your username/password combination is incorrect.';
		}
	} else {
		echo '<div> <ol>';
		foreach ($error as $key => $values) {
			echo '&nbsp;&nbsp; &nbsp;<li>'.$values.'</li>';
		}
		echo '</ol></div>';
	}
	if(isset($msg_error)){
		echo '<div>'.$msg_error.' </div>';
	}
	/// var_dump($error);
	mysqli_close($con);
} // End of the main Submit conditional.
ob_flush();

?>