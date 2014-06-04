<?php
require 'dbconnect.php';
//ini_set("max_execution_time", 300) 
if ($result = $con->query("SELECT p.Player_Name, ps.Player_lat, ps.Player_long, BIN(ps.Team+0) AS Team, ps.Status from PLAYERSTATS ps, PLAYERS p WHERE Game_ID = 2 AND p.Player_ID = ps.Player_ID")) {
	$rows = resultToArray($result);
	$result->free();

	foreach ($rows as $key => $value) {
		if ($value['Team'] == 1) {
			echo "var marker" . $key . " = new google.maps.Marker({
			    position: new google.maps.LatLng(" . $value['Player_lat'] . "," . $value['Player_long'] . "),
			    map: map,
			    title:'" . $value['Player_Name']. "',
				icon: 'http://ichinosekai.net/flag/images/markers/blue_MarkerB.png'
			});
			";
		} else if (distance(37.87175, -122.2602, $value['Player_lat'], $value['Player_long'])<= 100){
			echo "var marker" . $key . " = new google.maps.Marker({
			    position: new google.maps.LatLng(" . $value['Player_lat'] . "," . $value['Player_long'] . "),
			    map: map,
			    title:'" . $value['Player_Name']. "',
				icon: 'http://ichinosekai.net/flag/images/markers/red_MarkerR.png'
			});
			";
		}
	}

} else {
	echo 'Failed to connect to database';
}

function resultToArray($result) {
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    return $rows;
}

//gives distance between two points in meters
function distance($lat1, $lon1, $lat2, $lon2) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  return ($miles * 1.609344 * 1000);
}

?>