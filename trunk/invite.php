<html>

<head>
<title>Invite a friend to Scribb!</title>
<base target="_self">
</head>

<?php

	include("pagelayout.php");
	include("databasequery.php");
	$layoutObj = new PageLayout;
	$queryObj = new DatabaseQuery;

	//get userid
	$userid = $HTTP_COOKIE_VARS["userid"];

	//retrieve variables
	$tableWidth = $layoutObj->getTableWidth();
	$tableBgColour = $layoutObj->getTableBgColour();
	$pageBgColour = $layoutObj->getPageBgColour();
	$tableHeaderCellColour = $layoutObj->getTableHeaderCellColour();
	$tableCellColour = $layoutObj->getTableCellColour();
	$fontName = $layoutObj->getFontName();
	$linkColour = $layoutObj->getLinkColour();
	//$fontColour = $layoutObj->getFontColour();
	$journalBoxContent = "";

	

	//get all journals
	//echo("getting journals\n");
	$result = $queryObj->getRemainingInvites($userid);
	$row = mysql_fetch_row ($result);
	$numInvites = $row[0];

	$result = $queryObj->getFullName($userid);
	$row = mysql_fetch_row ($result);
	$fullname = $row[0];
	
	if($_POST["doaction"]=="add" && $numInvites > 0)
	{
		//create hash for invite
		$hash = md5(time()+$userid);
		
		//add invite to database
		$queryObj->insertInvite($userid, $hash, $_POST["mailaddress"]);
		
		//decrement number of invites
		$queryObj->decrementInvites($userid);
		$numInvites--;
		
		//create link address
		$inviteAdress = "http://scribb.com/newuser.php?hash=".$hash."&u=".$userid;
		
		//create text
		$messageText = $fullname." has invited you to join Scribb!\n\n".$_POST["textbox"]."\n\nClick the link to register:\n".$inviteAdress;
		$messageSubject = "Invitation to join Scribb!";

		//sender address etc
		$headers = "From: Scribb! <noreply@scribb.com>";
		mail ( $_POST["mailaddress"], $messageSubject, $messageText, $headers); 
		
	}
	



	echo("	<body bgcolor=\"".$pageBgColour."\">\n");
	echo("		<form method=\"POST\" action=\"invite.php\">\n");
	echo("		<table border=\"0\" width=\"".$tableWidth."\" bgcolor=\"".$tableBgColour."\">\n");

	echo("			<tr>\n");
	echo("				<td colspan=\"2\" bgcolor=\"".$tableHeaderCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">Invite a friend to Scribb! (You have ".$numInvites." invites left)</font></td>\n");
	echo("			</tr>\n");

	
	//show Message label
	echo("			<tr>\n");
	echo("				<td valign=\"top\" width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				Message:\n");
	echo($row[1]);
	echo("				</a>");
	echo("				</font></td>\n");

	//make Message field
	echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				<textarea rows=\"9\" name=\"textbox\" id=\"textbox\" cols=\"40\">");
	echo(				"You have been invited to join Scribb!"); 
	echo(				"</textarea><br>\n");
	echo("				</font></td>\n");
	echo("			</tr>\n");

	//make email label cell
	echo("			<tr>\n");
	echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				Email address:\n");
	echo("				</font></td>\n");

	//make email cell
	echo("				<td width=\"10\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				<input type=\"text\" name=\"mailaddress\" size=\"26\">\n");
	echo("				<input id=\"submit\" tabindex=\"1\" type=\"submit\" value=\"Invite\" name=\"submitbtn\">\n");
	echo("				</font></td>\n");

	echo("			</tr>\n");



	//make bottom row just to have a nice look
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\" colspan=\"2\">\n");
	echo("				&nbsp\n");
	echo("				</td>\n");
	echo("			</tr>\n");
	echo("		</table>\n");



	//have a field for user id
	echo("		<input type=\"hidden\" value=\"add\" name=\"doaction\">\n");

	echo("	</form>\n");

?>
</body>
</html>
