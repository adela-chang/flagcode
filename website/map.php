<!DOCTYPE html>
<html>
<head>
	<title>Game Simulation</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<style type="text/css">
	html { height: 100% }
	body { height: 100%; margin: 0; padding: 0 }
	#map-canvas { height: 568px; width:320px; margin: 30px auto }
	#selection { position: absolute; top:50px; left:100px; width:300px }
	#toprightlink { position: absolute; right:50px; top:10px}
	</style>

	<?php

	include 'dbconnect.php';
	if ($result = $con->query("SELECT * FROM GAMES WHERE Game_ID = 2")) {
		$current_game = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$max_lat = $current_game['Max_lat'];
		$max_long = $current_game['Max_long'];
		$min_lat = $current_game['Min_lat'];
		$min_long = $current_game['Min_long'];
		if ($_POST['latitude']) {
			$mylat = $_POST['latitude'];
		} else {
			$mylat = ($max_lat+$min_lat)/2;
		}
		if ($_POST['longitude']) {
			$mylong = $_POST['longitude'];
		} else {
			$mylong =($max_long+$min_long)/2;
		}
	} else {
		echo ' Unable to connect to Database ';
	}
	
	if ($result = $con->query("SELECT * from PLAYERSTATS WHERE Game_ID = 2")) {
		$game_row_cnt = $result->num_rows;
		$gamerows = resultToArray($result);
		$result->free();
	}
	
	if ($_POST['team'] == "blue") {
		$team = 1;
	} else if ($_POST['team'] == "red"){
		$team = 0;
	}
	$markerstring = '';
	
	if ($result = $con->query("SELECT p.Player_Name, ps.Player_lat, ps.Player_long, BIN(ps.Team+0) AS Team, ps.Status from PLAYERSTATS ps, PLAYERS p WHERE Game_ID = 2 AND p.Player_ID = ps.Player_ID")) {
		$rows = resultToArray($result);
		$result->free();

		foreach ($rows as $key => $value) {
			if ($value['Team'] == $team) {
				$markerstring .= "var marker" . $key . " = new google.maps.Marker({
				    position: new google.maps.LatLng(" . $value['Player_lat'] . "," . $value['Player_long'] . "),
				    map: map,
				    title:'" . $value['Player_Name']. "',
					";
					if ($team == 1) {
						$markerstring .= "icon: 'http://ichinosekai.net/flag/images/markers/blue_MarkerB.png'
						});
						";
					} else {
						$markerstring .= "icon: 'http://ichinosekai.net/flag/images/markers/red_MarkerR.png'
						});
						";						
					}
			} else if (distance($mylat, $mylong, $value['Player_lat'], $value['Player_long'])<= 100){
				$markerstring .= "var marker" . $key . " = new google.maps.Marker({
					position: new google.maps.LatLng(" . $value['Player_lat'] . "," . $value['Player_long'] . "),
					map: map,
					title:'" . $value['Player_Name']. "',
					";
				if ($team == 1) {
					$markerstring .= "icon: 'http://ichinosekai.net/flag/images/markers/red_MarkerR.png'
					});
					";
				} else {
					$markerstring .= "icon: 'http://ichinosekai.net/flag/images/markers/blue_MarkerB.png'
					});
					";						
				}
			}
		}

	} else {
		echo 'Failed to connect to database';
	}
	
		if ($result = $con->query("SELECT Mine_lat, Mine_long from MINES m, PLAYERSTATS ps WHERE m.Creator_ID = ps.Player_ID AND m.Game_ID = 2 AND ps.Game_ID = 2 AND BIN(ps.Team+0) = ". $team)) {
			$rows = resultToArray($result);
			$result->free();

			foreach ($rows as $key => $value) {

				$markerstring .= "var marker" . $key . " = new google.maps.Marker({
					position: new google.maps.LatLng(" . $value['Mine_lat'] . "," . $value['Mine_long'] . "),
					map: map,
					title:'MINE',
					icon: 'http://ichinosekai.net/flag/images/markers/orange_MarkerM.png'
					});
					";
			}	
		}
		if ($result = $con->query("SELECT Flag_lat, Flag_long from FLAGS WHERE Game_ID = 2 AND BIN(Team+0) = " . $team)) {
			$rows = resultToArray($result);
			$result->free();

			foreach ($rows as $key => $value) {

				$markerstring .= "var marker" . $key . " = new google.maps.Marker({
					position: new google.maps.LatLng(" . $value['Flag_lat'] . "," . $value['Flag_long'] . "),
					map: map,
					title:'FLAG!!!',
					icon: 'http://ichinosekai.net/flag/images/markers/purple_MarkerF.png'
					});
					";
			}
			
		}	
	
	//shell_exec('nohup php http://ichinosekai.net/flag/simulator.php > /dev/null &');

	mysqli_close($con);

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

	<script type="text/javascript"
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyChWfj4kM6WT8dYZEqMNxtsnvoy5wTxS34&sensor=false">
	</script>
	<script type="text/javascript">
	function initialize() {
		
		var mapOptions = {
			center: new google.maps.LatLng(<?php echo ($max_lat+$min_lat)/2 . ',' . ($max_long+$min_long)/2; ?>),
			zoom: 16,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		
		var myLocation = new google.maps.Marker({
			position: new google.maps.LatLng(<?php echo $mylat . ',' . $mylong; ?>),
			map: map,
			title:'YOU ARE HERE',
			icon: 'http://ichinosekai.net/flag/images/markers/pink_MarkerY.png'
		});

		// Define the LatLng coordinates for the polygons path.
		var gameBounds = [
		new google.maps.LatLng(<?php echo $max_lat . ',' . $min_long; ?>),
		new google.maps.LatLng(<?php echo $min_lat . ',' . $min_long; ?>),
		new google.maps.LatLng(<?php echo $min_lat . ',' . $max_long; ?>),
		new google.maps.LatLng(<?php echo $max_lat . ',' . $max_long; ?>)
		];

		// Construct the polygon.
		game = new google.maps.Polygon({
			paths: gameBounds,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35
		});

		game.setMap(map);
				
		<?php echo $markerstring; ?>
	}
	google.maps.event.addDomListener(window, 'load', initialize);	
	</script>
</head>
<body>
	<div id="map-canvas"></div>
	
		<div id="selection">
			<h4>Map Simulation</h4>
			In a real game, your location will be determined by the phone. For now, please enter any location within the boundaries <?php echo (float)$max_lat . ", " . (float)$min_lat . ", " . (float)$max_long . ", and " . (float)$min_long; ?>.
			<br /><br />
			<form method="post">
			Select Team:    
			<input type="radio" name="team" value="red">Red</option>    
			<input type="radio" name="team" value="blue">Blue</option> <br />
			Enter latitude: <input type="text" name="latitude"><br />
			Enter longitude: <input type="text" name="longitude"><br />
			<input type="submit" value="submit">
			</form>
			<br />
			<small>*you should only be able to see enemies within a certain radius.</small>
		</div>
		<div id="toprightlink"><p><a href="demos.php">Back to Demos</a></p></div>
		
</body>
</html>