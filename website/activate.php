<!DOCTYPE html>
<html> 
<head><title>Activate</title></head> 

<body>

<?php
require ('dbconnect.php');
if (isset($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
 $email = $_GET['email'];
} 
if (isset($_GET['key']) && (strlen($_GET['key']) == 32))
 //The Activation key will always be 32 since it is MD5 Hash
{
 $key = $_GET['key'];
}

if (isset($email) && isset($key)) {

 // Update the database to set the "activation" field to null
 $query_activate_account = "UPDATE `PLAYERS` SET `Activation` = NULL WHERE (`Email` ='$email' AND `Activation`='$key') LIMIT 1";
 $result_activate_account = mysqli_query($con, $query_activate_account);

 // Print a customized message:
 if (mysqli_affected_rows($con) == 1) //if update query was successfull
 {
 echo '<div>Your account is now active. You may now <a href="login.php">Log in</a></div>';

 } else {
 echo '<div>Oops! Your account could not be activated. Please recheck the link or contact the system administrator.</div>';

 }

 mysqli_close($con);

} else {
 echo '<div>Error Occurred.</div>';
}
?>

</body>
</html>