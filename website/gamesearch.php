<?php include 'header.php'; ?>
<p>In the app, searches will be automatic, based off your current location. Here, two preset locations have been provided for simulation purposes. </p>
<table><tr>
<td>Choose Location:</td>
<td><form method="post">
<select name="location">
	<option value="Berkeley">Berkeley</option>
	<option value="SF">San Francisco</option>
</select>
</td></tr>
<tr><td><input type='Submit' value='View Available Games'></td></tr></table>
</form>

<?php

$location = $_POST["location"];
$mylat = 0.0;
$mylong = 0.0;

if ($location == "Berkeley") {
	$mylat = 37.87159;
	$mylong = -122.27275;
} else if ($location == "SF") {
	$mylat = 37.775;
	$mylong = -122.4194;
}

//function that estimates the distance between 2 points in km
function distance($lat1, $lon1, $lat2, $lon2) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);
  return ($miles * 1.609344);
  
}
// connect to mysql database
include ('dbconnect.php');

echo "<p><hr></p><p><h2>Open Games near $location</h2></p>
<table border='1' cellpadding='5'><tr><td>Game name</td><td>Creator</td></tr>";

if ($result = $con->query("SELECT g.Game_ID, p.Player_Name, g.Game_Name, (g.Max_lat+g.Min_lat)/2, (g.Max_long+g.Min_long)/2 FROM GAMES g, PLAYERS p WHERE Status = 0 AND g.Creator_ID = p.Player_ID")) {
    while ($row = $result->fetch_row()) {
    	if (distance($mylat, $mylong, $row[3], $row[4]) < 5) {
    		echo "<tr><td>" . $row[2] . "</td><td>" . $row[1] . "</td></tr>";
    	}
    }
    $result->close();
} else {
    echo "failed";
}
echo "</table>";
?>
</form>

<?php include 'footer.php'; ?>
