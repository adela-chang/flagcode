<?php

// assumes that maximum latitude and longitude has been defined earlier in the code somewhere
require 'dbconnect.php';
ini_set("max_execution_time", 300) 
if ($result = $con->query("SELECT * from PLAYERSTATS WHERE Game_ID = 2")) {
	$rows = resultToArray($result);
	$result->free();
	$latarr = array_fill(0, count($rows), 1);
	$longarr = array_fill(0, count($rows), 1);
	$markerarr = createMarkerArray($rows);
	$start = microtime(true);
	set_time_limit(60);
	for ($i = 0; $i < 59; ++$i) {
		updateLocation($rows, $latarr, $longarr, $con);
	    time_sleep_until($start + $i + 1);
	}
}

mysqli_close($con); //Close the DB Connection


function updateLocation(&$rows, &$latarr, &$longarr, $con) {
	foreach ($rows as $key => &$value) {
		if ($latarr[$key] > 0 && $value['Player_lat'] >= $max_lat) {
			$latarr[$key] = -1;
		} else if ($latarr[$key] < 0 && $value['Player_lat'] <= $min_lat) {
			$latarr[$key] = -1;
		}
		
		if ($longarr[$key] > 0 && $value['Player_long'] >= $max_long) {
			$longarr[$key] = -1;
		} else if ($latarr[$key] < 0 && $value['Player_long'] <= $min_long) {
			$longarr[$key] = -1;
		}
		
		if ($latarr[$key] > 0) {
			$newlat = $value['Player_lat'] + .0001;
		} else {
			$newlat = $value['Player_lat'] - .0001;
		}
		
		if ($longarr[$key] > 0) {
			$newlong = $value['Player_long'] + .0001;
		} else {
			$newlong = $value['Player_long'] - .0001;
		}
		$value['Player_lat'] = $newlat;
		$value['Player_long'] = $newlong;
		
		$pid = $value['Player_ID'];
		$query_update_location = "UPDATE `PLAYERSTATS` SET `Player_lat`='$newlat', `Player_long`='$newlong' WHERE `Player_ID`='$pid' and`Game_ID`='2'";
		echo $query_update_location;
		$result_update_locations = mysqli_query($con, $query_update_location);
	    if (!$result_update_locations) {
			echo 'Database error';
		}
		
	}
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}


?>