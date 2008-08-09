<html>

<head>
<title>Search journals</title>
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

	//create font tag for tags
	$tagFont = "<font face=\"".$layoutObj->getTagFont()."\"";
	$tagFont = $tagFont." color=".$layoutObj->getTagColour()."\"";
	$tagFont = $tagFont." size=-".$layoutObj->getTagFontSize()."\">";

	//create font tag for journal name
	$journalFont = "<font face=\"".$layoutObj->getTagFont()."\"";
	$journalFont = $journalFont." color=".$layoutObj->getJournalNameColour()."\"";
	$journalFont = $journalFont." size=-".$layoutObj->getTagFontSize()."\">";
	
	//create tag for highlighting
	$highlightFont = "<font style=\"BACKGROUND-COLOR: yellow\">";

	echo("	<body bgcolor=\"".$pageBgColour."\">\n");

	echo("		<table border=\"0\" width=\"".$tableWidth."\" bgcolor=\"".$tableBgColour."\">\n");

	//make form for creating and renaming
	echo("	<form method=\"POST\" action=\"search.php\">\n");


	echo("			<tr>\n");
	echo("				<td colspan=\"4\" bgcolor=\"".$tableHeaderCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">Search journals</font></td>\n");
	echo("			</tr>\n");

	//text field for journal name
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\">\n");
	echo("					<input type=\"text\" name=\"searchstring\" size=\"26\" value=\"".$searchstring."\">\n");
	echo("				</td>\n");

	//submit button
	echo("				<td bgcolor=\"".$tableCellColour."\">\n");
	echo("					<input type=\"submit\" value=\"Search\" name=\"submitbtn\">\n");
	echo("				</td>\n");
	echo("			</tr>\n");

	//make bottom row just to have a nice look
	echo("			<tr>\n");
	echo("				<td  bgcolor=\"".$tableCellColour."\" colspan=\"2\">\n");
	echo("				&nbsp\n");
	echo("				</td>\n");
	echo("			</tr>\n");

	echo("		</table>\n");

	echo("	</form>\n");

	//check if should do search

	//$searchstring;
	//check whether was posted or sent as parameter
	if($_POST["searchstring"]!="")
		$searchstring = $_POST["searchstring"];

	if($searchstring!="")
	{
		$sqlstring = $queryObj->parseSearchString($searchstring, $userid);
		$result = $queryObj->sendQuery($sqlstring);

		//check if contains any freetex phrases
		$splitString = split(" ", $searchstring);
		$freetext=array();
		foreach($splitString as $val)
		{
			if(!stristr($val, ":"))
				$freetext[] = $val;
		}
		//make a table
		echo("		<br><table border=\"0\" width=\"".$tableWidth."\" bgcolor=\"".$tableBgColour."\">\n");
		echo("			<tr>\n");
		echo("				<td colspan=\"4\" bgcolor=\"".$tableHeaderCellColour."\">\n");
		echo("				<font face=\"".$fontName."\">\n");
		echo("				Search results - Found ".mysql_num_rows($result)." entries\n");
		echo("				</font></td>\n");
		echo("			</tr>\n");

		echo("			<tr>\n");
		echo("				<td bgcolor=\"".$tableCellColour."\">\n");

		$itemDate;
		$itemDatePrev;
		$journalName;
		$journalNamePrev;

		while($row = mysql_fetch_row ($result))
		{



			//get date
			$itemDate = $row[0];
			if($itemDate != $itemDatePrev)
			{
				//first time, don't set this tag
				if($itemDatePrev!="")
					echo("</ul>\n");

				echo("<b>".$itemDate."</b>\n");
				$itemDatePrev = $itemDate;
				echo("<ul>\n");
			}
			echo("<li>");
			//show time
			$time = $row[4];
			$time = substr($time, 1);
			$time = str_replace(" ", ":", $time);
			echo($time." ");
			//highlight any freetext phrases
			$highlightedtext=$row[2];
			foreach($freetext as $phrase)
			{
				$replacement = $highlightFont.$phrase."</font>";
				$highlightedtext = str_ireplace($phrase, $replacement, $highlightedtext);	
			}
			//show text
			echo($highlightedtext);

			//get tags for this item
			$tagresult = $queryObj->GetCategoriesForItem($row[3]);
			echo($tagFont);
			while($tagrow = mysql_fetch_row ($tagresult))
			{
				//set up font for tags
				echo(" ".$tagrow[0]);

			}
			echo("</font>");

			//add name of journal
			echo($journalFont);
			$journalname = str_replace(" ", "&nbsp;", $row[5]);
			echo(" ".$journalname);
			echo("</font>");


			//set up links for deleting and editing
			echo("&nbsp<a href=\"journalentries.php?doaction=delete&itemid=".$row[3]."&journalid=".$journalid."\">d</a>");

			echo("</li>\n");

		}
		echo("					</ul>\n");
		echo("				</td>\n");
		echo("		</table>\n");

	}

?>
</body>
</html>