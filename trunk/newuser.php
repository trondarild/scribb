<html>

<head>
<title>Join Scribb!</title>
<base target="_self">
<script src="getxmlobject.js"></script>
<script src="md5.js"></script>
<script src="checknewuser.js"></script>
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

	
	if($_POST["doaction"]=="")
	{
		echo("<body><br><br><div align=\"center\">");
		if(!isset($u))
			exit("Error registering new user code 1");

		$result = $queryObj->getHashInfo($u);
		$hashok=false;
		$userMail="";
		while($row = mysql_fetch_row($result))
		{
			if($hash==$row[1])
			{
				$hashok = true;
				$userMail = $row[2];
				break;

			}
		}
		if(!$hashok)
		{
			echo("Sorry, this invitation to Scribb! is no longer valid</div>");
			exit;
		}
	}
        
	if($_POST["doaction"]=="add" )
	{
		
		echo("<body><br><br><div align=\"center\">");            
    //
    //erase invite from database
		$queryObj->removeInvite($_POST["hash"]);
		//insert new user into database
		$queryObj->insertNewUser($_POST["fullname"],$_POST["mailaddress"],$_POST["username"],$_POST["password"],$_POST["friendid"]);
		
		//Write thanks and show link to main page                
    echo("Thank you for joining Scribb! Please go <a href=\"http://scribb.com\">here</a> to log in</div>");
		exit;
                
	}
	


	
	echo("	<body bgcolor=\"".$pageBgColour."\"><br>\n");
	echo("		<font face=\"Century Gothic\" size=\"5\"><b><font color=\"#ffffff\">welcome to scr</font><font color=\"#ffcc00\">i</font><font color=\"#ffffff\">bb<i>!</i></font></b></font>\n");
	echo("		<br><br>\n");
	
	echo("		<form method=\"POST\" action=\"newuser.php\" name=\"newuserform\" id=\"newuserform\">\n");
	echo("		<table border=\"0\" width=\"".$tableWidth."\" bgcolor=\"".$tableBgColour."\">\n");

	echo("			<tr>\n");
	echo("				<td colspan=\"2\" bgcolor=\"".$tableHeaderCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">Register new user</font></td>\n");
	echo("			</tr>\n");

	
	//show name label
	echo("			<tr>\n");
	echo("				<td valign=\"top\" width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				Full name*:\n");
	echo("				</font></td>\n");

	//make name field
	echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				<input type=\"text\" id=\"fullname\" size=\"26\">\n");	
	echo("				</font></td>\n");
	echo("			</tr>\n");

	//make email label cell
	echo("			<tr>\n");
	echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				Email address*:\n");
	echo("				</font></td>\n");

	//make email cell
	echo("				<td width=\"10\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				<input type=\"text\"  id=\"mailaddress\" value=\"".$userMail."\" size=\"26\">\n");
	echo("				</font></td>\n");
	
	//show username label
	echo("			<tr>\n");
	echo("				<td valign=\"top\" width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				User name*:\n");
	echo("				</font></td>\n");

	//make username field
	echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				<input type=\"text\" id=\"username\" size=\"26\">\n");	
	echo("				</font></td>\n");
	echo("			</tr>\n");
	
	//show password label
	echo("			<tr>\n");
	echo("				<td valign=\"top\" width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				Password*:\n");
	echo("				</font></td>\n");

	//make password field
	echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				<input type=\"password\" id=\"password_a\" size=\"26\">\n");	
	echo("				</font></td>\n");
	echo("			</tr>\n");
	
	//show repeat password label
	echo("			<tr>\n");
	echo("				<td valign=\"top\" width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				Repeat password*:\n");
	echo("				</font></td>\n");

	//make repeat password field
	echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	echo("				<input type=\"password\" id=\"password_b\" size=\"26\">\n");	
	echo("				</font></td>\n");
	echo("			</tr>\n");

	echo("			</tr>\n");

	//make row for submit button
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\" colspan=\"2\">\n");
	echo("				<input id=\"submitbtn\" tabindex=\"1\" type=\"button\" value=\"Register\" name=\"submitbtn\" onclick=\"checkUserForm()\">\n");
	echo("				</td>\n");
	echo("			</tr>\n");
	


	//make bottom row just to have a nice look
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\" colspan=\"2\">\n");
        echo("		<div id=\"response\">&nbsp</div>\n");
	echo("				</td>\n");
	echo("			</tr>\n");
	echo("		</table>\n");



	//have a field for user id
	echo("		<input type=\"hidden\" value=\"\" id=\"doaction\" name=\"doaction\">\n");
	echo("		<input type=\"hidden\" value=\"".$row[1]."\" name=\"hash\">\n");

        //these fields will be set if everything is ok
        echo("		<input type=\"hidden\" value=\"\" id=\"fn\" name=\"fullname\">\n");
        echo("		<input type=\"hidden\" value=\"\" id=\"em\" name=\"mailaddress\">\n");
        echo("		<input type=\"hidden\" value=\"\" id=\"un\" name=\"username\">\n");
        echo("		<input type=\"hidden\" value=\"\" id=\"pw\" name=\"password\">\n");

	echo("		<input type=\"hidden\" value=\"".$u."\" name=\"friendid\">\n");
	echo("	</form>\n");

?>
</body>
</html>
