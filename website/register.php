<!DOCTYPE html>
<html> 
<head><title>Register</title></head> 

<body>
<center><img src="images/CaptureTheFlag.png">
<div style="width:450px;">
<form action="register.php" method="post" class="registration_form">
  <fieldset>
    <legend>Registration Form </legend>
	<table width="450px"><tr><td colspan="2">
   <p><small>Create A New Account</small></td><td align="right"><small><span style="background:#EAEAEA none repeat scroll 0 0;line-height:1;;padding:5px 7px;">
Already a member? <a href="login.html">Log in</a></span></small> </p>
	</td></tr><tr colspan="3"><div style="text-align:left; color:red;"><?php include 'registerscript.php';?></tr></div><tr><td width="120px">
    <div class="elements">
      <label for="name">Name:</label></td><td>
      <input type="text" id="name" name="name" size="25" />
    </div></td></tr><tr><td>
	
	<div class="elements">
      <label for="Password">Password:</label></td><td>
      <input type="password" id="Password" name="Password" size="25" />
    </div></td></tr><tr><td>
    
    <div class="elements">
      <label for="Password2">Verify Password:</label></td><td>
      <input type="password" id="Password2" name="Password2" size="25" />
  	</div></td></tr><tr><td>
	
    <div class="elements">
      <label for="e-mail">E-mail:</label></td><td>
      <input type="text" id="e-mail" name="e-mail" size="25" />
    </div></td></tr><tr><td colspan="2"></td><td align="right">
	
    <div class="submit">
     <input type="hidden" name="formsubmitted" value="TRUE" />
      <input type="submit" value="Register" />
    </div></td></tr></table>
	
  </fieldset>
</form>
<a href="demos.php">Return to Demos</a>
</div>

</center>
</body>
</html>