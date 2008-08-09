<?php

	include("databasequery.php");

	//ob_start();

	$queryobj = new DatabaseQuery;
	if($doaction=="logout")
	{
		$userid = $HTTP_COOKIE_VARS["userid"];
		$queryobj->setUserLoggedIn($userid, "0");
		//delete cookie
		setcookie("userid", "");
	}



?>
<html>

<head>
<title>Log in</title>
<base target="_self">
<script src="md5.js"></script>
<script type="text/javascript">
	function setFocus()
	{
		document.getElementById("username").focus()

	}

	function onSubmit()
	{
		//hash password
		document.getElementById("password").value = hex_md5(document.getElementById("pw").value)
		
		//document.getElementById("usedtags").innerHTML="verdi "+document.getElementById("doaction").value
		document.getElementById("form_login").submit()
		//document.getElementById("journalitemtxt").select()



	}
</script>
</head>

<body onload="setFocus()" bgcolor="#336699">

<form method="POST" action="checkpassword.php" id="form_login" target="_self">
  <table border="0" width="578" bgcolor="#FFFFFF">
    <tr>
      <td width="568" colspan="3" bgcolor="#DAD3C5"><font face="Arial">Log in</font></td>
    </tr>
    <tr>
      <td width="206" bgcolor="#EEEBE3"><font face="Arial">User name</font></td>
      <td width="206" bgcolor="#EEEBE3"><input type="text" name="username" id="username" size="26"></td>
      <td width="144" bgcolor="#EEEBE3">&nbsp;</td>
    </tr>
    <tr>
      <td width="206" bgcolor="#EEEBE3"><font face="Arial">Password</font></td>
      <td width="206" bgcolor="#EEEBE3"><input type="password" name="pw" id="pw" size="26"></td>
      <td width="144" bgcolor="#EEEBE3"><input type="button" onclick="onSubmit()" value="Login" name="B1"></td>
			<input type="hidden" name="password" id="password" value="">
    </tr>
  </table>
</form>

</body>

</html>
