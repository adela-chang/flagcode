<?php

require 'dbconnect.php';
require 'PasswordHash.php';
if (isset($_POST['formsubmitted'])) {
    $error = array(); //Declare An Array to store any error message
    if (empty($_POST['name'])) { //if no name has been supplied
        $error[] = 'Name field cannot be blank'; //add to array "error"
    } else {
        $name = $_POST['name']; //else assign it a variable
    }

    if (empty($_POST['e-mail'])) {
        $error[] = 'Email field cannot be blank';
    } else {
		if (filter_var($_POST['e-mail'], FILTER_VALIDATE_EMAIL)) {
            $email = $_POST['e-mail'];
        } else {
            $error[] = 'Please enter a valid email address';
        }

    }

    if (empty($_POST['Password'])) {
        $error[] = 'Password field cannot be blank';
    } else {
	
		if (strlen($_POST['Password']) > 72) {
			$error[] = 'Password must be 72 characters or less';
		}
		
		if (strcmp($_POST['Password'], $_POST['Password2']) != 0) {
			$error[] = 'Passwords do not match';
		}
        $Password = $_POST['Password'];
    }

    if (empty($error)) //send to Database if there's no error '

    { // If everything's OK...

        // Make sure the email address is available:
        $query_verify_email = "SELECT * FROM PLAYERS WHERE Email ='$email'";
        $result_verify_email = mysqli_query($con, $query_verify_email);

        if (!$result_verify_email) { //if the Query Failed ,similar to if($result_verify_email==false)
            echo ' Database Error Occurred ';
        } 

        if (mysqli_num_rows($result_verify_email) == 0) { // IF no previous user is using this email .

            // Create a unique  activation code:
            $activation = md5(uniqid(rand(), true));

			$uuid = gen_uuid();
			$hasher = new PasswordHash(8, false);
			$hash = $hasher->HashPassword($Password);

            $query_insert_user =
                "INSERT INTO `PLAYERS` ( `Player_ID`, `Player_Name`, `Email`, `Password`, `Activation`) VALUES ( '$uuid', '$name', '$email', '$hash', '$activation')";
            $result_insert_user = mysqli_query($con, $query_insert_user);
            if (!$result_insert_user) {
                echo 'Query Failed ';
            }

            if (mysqli_affected_rows($con) == 1) { //If the Insert Query was successfull.

                // Send the email:
                $message = " To activate your account, please click on this link:\n\n";
                $message .= 'http://ichinosekai.net/flag/activate.php?email=' . urlencode($email) . "&key=$activation";

                mail($email, 'Registration Confirmation', $message, 'From: activate@ichinosekai.net');

                // Flush the buffered output.

                // Finish the page:
                echo '<div class="success">Thank you for
registering! A confirmation email
has been sent to ' . $email .
                    ' Please click on the Activation Link to Activate your account </div>';

            } else { // If it did not run OK.
                echo '<div class="errormsgbox">You could not be registered due to a system
error. We apologize for any
inconvenience.</div>';
            }

        } else { // The email address is not available.
            echo '<div class="errormsgbox" >That email
address has already been registered.
</div>';
        }

    } else { //If the "error" array contains error msg , display them

        echo '<div class="errormsgbox"> <ol>';
        foreach ($error as $key => $values) {

            echo '	<li>' . $values . '</li>';

        }
        echo '</ol></div>';

    }

    mysqli_close($con); //Close the DB Connection

} // End of the main Submit conditional.

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
?>
