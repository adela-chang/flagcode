<!DOCTYPE html>
<html> 
<head><title>Login</title></head> 

<body>
	<center>
	<img src="images/CaptureTheFlag.png" style="margin-top:50px;"><br />
	<div style="width:350px;">
	<form method="post" action="login.php">
		<fieldset>
			<table cellpadding="2px">
				<legend>Login </legend>
				<tr colspan="2"><div><?php include 'loginscript.php'; ?></div></tr>
	  			<tr><td>Username: </td><td><input type="text" name="username" size="15" /></td></tr>
	  			<tr><td>Password:</td><td> <input type="password" name="password" size="15" /></td></tr>
	  			<tr><td><a href="register.php" style="font-size:.8em;">Register</a></td><td align="right">
					<input type="hidden" name="formsubmitted" value="TRUE" />
		     		<input type="submit" value="Login" /></td></tr></table>
		</fieldset>
	</form>
	</div>
	</center>
</body>

</html>