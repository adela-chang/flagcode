<?php include 'header.php'; ?>

<table><tr>
<td>Choose Player:</td>

<td>
<form method="post">
<select name="sn">
<?php
// connect to mysql database
include ('dbconnect.php');

if ($result = $con->query("SELECT Distinct Player_Name FROM PLAYERS p, PLAYERSTATS s WHERE p.Player_ID = s.Player_ID")) {
    while ($row = $result->fetch_row()) {
    	echo "<option>" . $row[0] . "</option>";
    }
    $result->close();
} else {
    echo "failed";
}

echo "</select></td></tr>

<tr><td><input type='Submit' value='View Breakdown!!'></td></tr></table>";

$name = $_POST["sn"];
$totalkills = 0;
$totaldeaths = 0;

if ($result = $con->query("SELECT g.Game_Name, s.Kills, s.Deaths
FROM PLAYERS p, PLAYERSTATS s, GAMES g
WHERE p.Player_Name = '$name'
AND p.Player_ID = s.Player_ID
AND s.Game_ID = g.Game_ID
AND g.Status = 2
")) {
	echo "<p><hr></p><p><h2>$name</h2></p><table><tr><td>
	<table border='1' cellpadding='5'><tr><td>Recent Games</td><td>Kills</td><td>Deaths</td></tr>";
    while ($row = $result->fetch_row()) {
    	echo "<tr>";
	    echo "<td>" . $row[0] . "</td><td>" . (int)$row[1] . "</td><td>" . (int)$row[2] . "</td>";
	    $totalkills += $row[1];
	    $totaldeaths += $row[2];
    }
    echo "</table></td><tr><td>";
    echo "<p><b>Total kills:</b> $totalkills&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total deaths:</b> $totaldeaths</p></td></tr></table>";
    $result->close();
}
?>
</form>

<?php include 'footer.php'; ?>
