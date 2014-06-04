<?php include 'header.php'; ?>

<table><tr>
<td>Choose Game:</td>

<td>
<form method="post">
<select name="game">
<?php
// connect to mysql database
include ('dbconnect.php');

if ($result = $con->query("SELECT Game_ID, Game_Name FROM GAMES WHERE Status = 2")) {
    while ($row = $result->fetch_row()) {
    	echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
    }
    $result->close();
} else {
    echo "failed";
}

echo "</select></td></tr>
<tr><td style='vertical-align:top;'>Sort by:</td><td>
	<input type='radio' name='sort' value='Team'>Team
	<input type='radio' name='sort' value='Kills'>Kills
	<input type='radio' name='sort' value='Deaths'>Deaths
</td></tr>
<tr><td><input type='Submit' value='View Breakdown!!'></td></tr></table>";

$game = $_POST["game"];
$sort = $_POST["sort"];

/*if (isset($_POST["sort"])) {
	$selected = $_POST["sort"];
	if ($selected == 'Kills') {
		$sort = "Kills";
	} else if ($selected == 'Team') {
		$sort = "Team";
	} else if ($selected == 'Deaths') {
		$sort = "Deaths";
	}
}*/

if ($result = $con->query("SELECT p.Player_Name, BIN(s.Team), s.Kills, s.Deaths
FROM PLAYERS p, PLAYERSTATS s
WHERE s.Game_ID = '$game'
AND p.Player_ID = s.Player_ID
ORDER BY $sort DESC")) {
	$redkills = 0;
	$reddeaths = 0;
	$bluekills = 0;
	$bluedeaths = 0;
	echo "<p><hr></p><p><h2>$name</h2></p>
	<table border='1' cellpadding='5'><tr><td>Player</td><td>Team</td><td>Kills</td><td>Deaths</td></tr>";
    while ($row = $result->fetch_row()) {
    	echo "<tr>";
	    echo "<td>" . $row[0] . "</td>";
	    if ($row[1] == true) {
	    	echo "<td>Blue</td>";
	    	$bluekills += $row[2];
	    	$bluedeaths += $row[3];
	    } else {
	    	echo "<td>Red</td>";
	    	$redkills += $row[2];
	    	$reddeaths += $row[3];
	    }
	    echo "<td>" . (int)$row[2] . "</td><td>" . (int)$row[3] . "</td>";
    }
    echo "</tr><tr><td colspan=4 style='border:none;'><font style='color:white;'>a</font></td></tr><tr><td><b>WINNER</b></td><td><b>";
    if ($bluekills*2-$bluedeaths > $redkills*2-$reddeaths) {
    	echo "Blue</b></td><td><b>$bluekills</b></td><td><b>$bluedeaths</b></td></tr>
    	<tr><td><b>LOSER</b></td><td><b>Red</b></td><td><b>$redkills</b></td><td><b>$reddeaths";
    } else {
        echo "Red</b></td><td><b>$redkills</b></td><td><b>$reddeaths</b></td></tr>
    	<tr><td><b>LOSER</b></td><td><b>Blue</b></td><td><b>$bluekills</b></td><td><b>$bluedeaths";
    }
    echo "</b></td></tr></table>";
    $result->close();
}
?>
</form>

<?php include 'footer.php'; ?>
