<!DOCTYPE html>
<html> 
<head><title>Register</title></head> 
<body>
<h2>Register an Account</h2> 
<p>When this app is in mobile form, UUIDs will be automatically generated by your phone. For now, please use <a href="http://www.uuidgenerator.net">this tool</a> to generate a UUID and copy-paste it into the form.</p>
<form action="old_generatepass.php" method="post">
	<table>
	<tr><td><label for="uuid">UUID:</label></td>
    <td><input type="text" name="uuid" /></td>
    <tr><td><label for="name">Username:</label></td>
    <td><input type="text" name="name" /></td>
    <tr><td><label for="password">Password:</label></td>
    <td><input type="text" name="password" /></td>
    <tr><td><input type="submit" value="Register" /></td></tr>
    </table>
</form>
</body>
</html>