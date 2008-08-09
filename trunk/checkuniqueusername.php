<?php

	include("databasequery.php");
	$queryObj = new DatabaseQuery;
	
	if(isset($username) && isset($email))
	{
		//check if can find user name and email
		echo($queryObj->isUserNameOk($username, $email));
		exit;
		
		
	}
	else
		echo("0");
?>