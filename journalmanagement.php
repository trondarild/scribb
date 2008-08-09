<html>

<head>
<title>Journals</title>
<base target="_self">
</head>

<?php

	include("pagelayout.php");
	include("databasequery.php");
	include("util.php");
	$layoutObj = new PageLayout;
	$queryObj = new DatabaseQuery;
	$util = new Util($layoutObj);
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

	//check what we should do..rename, delete, create or just get the journals
	$doAction = $_POST["doaction"];
	if($doAction=="")
		$doAction=$doaction;
	//echo("doaction=".$doAction);
	//echo("<br>");
	if($doAction == "rename")
	{
		//echo("renaming to \n");
		//echo($_POST["journalname"]);
		//echo(" id = ");
		//echo($_POST["journalid"]);
		//echo("<br>");
		$queryObj->setJournalName($_POST["journalid"], $_POST["journalname"]);
	}
	else if($doAction == "getjournalname")
	{
		//echo("getting journal name\n");
		$journalBoxContent = $journalname;//_POST["journalname"];
		//echo(" ");
		//echo($journalBoxContent);
	}
	else if($doAction == "delete")
	{
		//must be a confirm dialog box before coming here
		$queryObj->deleteJournal($journalid);//_POST["journalid"]);
	}
	else if($doAction == "create")
	{
		//create a new journal with the given name
		$queryObj->createJournal($_POST["journalname"],$userid);
	}
	else if($doAction == "archive")
	{
		//set given journal to archived
		$queryObj->setJournalArchived($journalid, $archive);
	}

	$getArchived = 0;
	//get all journals
	//echo("getting journals\n");
	$result = $queryObj->getAllJournalsForUser($userid, $getArchived);

	//constant which gives how many columns are used
	$numCols = 5;

	echo("	<body bgcolor=\"".$pageBgColour."\">\n");

	echo("		<table border=\"0\" width=\"".$tableWidth."\" bgcolor=\"".$tableBgColour."\">\n");

	echo("			<tr>\n");
	echo("				<td colspan=\"".$numCols."\" bgcolor=\"".$tableHeaderCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">Journals</font></td>\n");
	echo("			</tr>\n");

	//if no journals are present, print out a message
	$num_rows = mysql_num_rows($result);
	if($num_rows==0)
	{
		//make the row
		echo("			<tr>\n");
		echo("				<td bgcolor=\"".$tableCellColour."\" colspan=\"".$numCols."\">\n");
		echo("				<font face=\"".$fontName."\">\n");
		echo("				Create a journal by typing in the name below and clicking \"Create\".\n");
		echo("				</font></td>\n");

	}
	else
	{
		//make rows for the journals
		$tablerows = $util->makeJournalTableRows($result, $getArchived);
		echo($tablerows);
		
	}
	
	//make a separating row just to have a nice look
	echo("			<tr>\n");
	echo("				<td  bgcolor=\"".$tableCellColour."\" colspan=\"".$numCols."\">\n");
	echo("				&nbsp\n");
	echo("				</td>\n");
	echo("			</tr>\n");

	//make row for showing archived
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\" colspan=\"".$numCols."\">\n");
	echo("				<font face=\"".$fontName."\">\n");
	if($showarchived==1)
	{
		echo("				<a href=\"journalmanagement.php?showarchived=0\">\n");
		echo("				Hide archived\n");
		echo("				</a></font>\n");
		echo("				</td>\n");
		echo("			</tr>\n");
		
		$getArchived = 1;
		$result = $queryObj->getAllJournalsForUser($userid, $getArchived);
		//make rows for the journals
		$tablerows = $util->makeJournalTableRows($result, $getArchived);
		echo($tablerows);
		
		
	
	}
	else
	{
		echo("				<a href=\"journalmanagement.php?showarchived=1\">\n");
		echo("				Show archived\n");
		echo("				</a></font>\n");
		echo("				</td>\n");
		echo("			</tr>\n");
	}
	
	echo("		</table>\n");

	
	//make bottom row just to have a nice look
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\" colspan=\"".$numCols."\">\n");
	echo("				&nbsp\n");
	echo("				</td>\n");
	echo("			</tr>\n");
	echo("		</table>\n");


	//make form for creating and renaming
	echo("	<form method=\"POST\" action=\"journalmanagement.php\">\n");

	echo("		<table border=\"0\" width=\"".$tableWidth."\" bgcolor=\"".$tableBgColour."\">\n");


	//header
	echo("			<tr>\n");
	echo("				<td colspan=\"3\" bgcolor=\"".$tableHeaderCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	if($doAction=="getjournalname")
		echo("Rename journal");
	else
		echo("New journal");
	echo("</font></td>\n");
	echo("			</tr>\n");

	//text field for journal name
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\">\n");
	echo("					<input type=\"text\" name=\"journalname\" size=\"26\" value=\"".$journalBoxContent."\">\n");
	echo("				</td>\n");

	//reset and submit buttons
	echo("				<td bgcolor=\"".$tableCellColour."\">\n");
	//echo("					<input type=\"reset\" value=\"Reset\" name=\"resetbtn\">\n");
	if($doAction == "getjournalname")
		echo("					<input type=\"submit\" value=\"Rename\" name=\"submitbtn\">\n");
	else
		echo("					<input type=\"submit\" value=\"Create\" name=\"submitbtn\">\n");
	echo("				</td>\n");
	echo("			</tr>\n");



	echo("		</table>\n");

	//insert hidden field which tells whether should create or rename journal
	if($doAction == "getjournalname")
	{
		echo("		<input type=\"hidden\" value=\"rename\" name=\"doaction\">\n");
		echo("		<input type=\"hidden\" value=\"".$journalid."\" name=\"journalid\">\n");
	}
	else
		echo("		<input type=\"hidden\" value=\"create\" name=\"doaction\">\n");

	//have a field for user id
	echo("		<input type=\"hidden\" value=\"".$userid."\" name=\"userid\">\n");

	echo("	</form>\n");

?>
</body>
</html>
