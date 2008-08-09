<?php
	include("databasequery.php");

	//ob_start();

	$queryobj = new DatabaseQuery;
	$tst = $queryobj->checkPassword($_POST["username"], $_POST["password"]);

	/**/
	if($tst)
	{
		//set user as logged in
		$queryobj->setUserLoggedIn($_POST["username"], "1");
		//get user id for later use
		$userid=$queryobj->getUserId($_POST["username"]);
		//password is valid..make loggedin frameset

		//set cookie for user id
		setcookie("userid",$userid);
		echo("	<html>\n");
		echo("	<frameset cols=\"160,*\" framespacing=\"0\" border=\"0\" frameborder=\"0\">");
		echo("		  <frame name=\"navigation\" target=\"loggedinmain\" src=\"navigation.php\">");
		echo("		  <frame name=\"loggedinmain\" src=\"journalmanagement.php\" target=\"_self\">");
		echo("			<noframes>");
		echo("				<body>");
		echo("					<p>This page uses frames, but your browser doesn't support them.</p>");
		echo("				</body>");
		echo("	    </noframes>");
		echo("	 </frameset>");
		/**/
	}
	else
		echo("password is not correct<br>");
		//echo($_POST["password"]);





?>
</html>