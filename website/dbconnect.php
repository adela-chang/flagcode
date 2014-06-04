<?php

/*Default time zone ,to be able to send mail */
date_default_timezone_set('UTC');

/*You might not need this */
//ini_set('SMTP', "mail.ichinosekai.net");
// Overide The Default Php.ini settings for sending mail

$con=mysqli_connect("localhost","scanlat1_test","test1","scanlat1_flag");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

?>