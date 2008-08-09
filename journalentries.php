<?php

	include("pagelayout.php");
	include("databasequery.php");
	$layoutObj = new PageLayout;
	$queryObj = new DatabaseQuery;
	
	//number of entries displayed 
	$itemCount = 50;

	//get userid
	$userid = $HTTP_COOKIE_VARS["userid"];
	//get journalid
	if($journalid=="")
		$journalid=$_POST["journalid"];
	//echo("journalid=".$journalid);

	$isRead = false;
	//check if should delete or add
	$doAction = $_POST["doaction"];
	if($doAction=="")
		$doAction=$doaction;
	//echo("doactoin = ".$doAction."\nitemid=".$itemid."\n");
	if($doAction == "delete")
	{
		$queryObj->DeleteJournalItem($itemid);
	}
	if($doAction == "read")
	{
		$isRead = true;
	}
	//get which categories were used
	$tags = split(" ", $_POST["usedcat"]);
	//echo("doaction=".$_POST["doaction"]);
	/**/



	//retrieve variables
	$tableWidth = $layoutObj->getTableWidth();
	$tableBgColour = $layoutObj->getTableBgColour();
	$pageBgColour = $layoutObj->getPageBgColour();
	$tableHeaderCellColour = $layoutObj->getTableHeaderCellColour();
	$tableCellColour = $layoutObj->getTableCellColour();
	$fontName = $layoutObj->getFontName();
	$linkColour = $layoutObj->getLinkColour();
	$journalFontColour = $layoutObj->getJournalNameColour();

	//create font tag for tags
	$tagFont = "<font face=\"".$layoutObj->getTagFont()."\"";
	$tagFont = $tagFont." color=".$layoutObj->getTagColour()."\"";
	$tagFont = $tagFont." size=-".$layoutObj->getTagFontSize()."\">";

	//check what we got
	$journalItemText = $_POST["journalitemtext"];
	$isPublic = $_POST["ispublic"];


	//enter into database, then retrieve all items
	if($doAction == "add")
	{

		if($journalItemText != "")
			$queryObj->InsertJournalItem($journalid, $journalItemText, $tags, $isPublic);
	}
	else if($doAction == "update")
	{
		if($journalItemText != "")
			$queryObj->UpdateJournalItem($_POST["itemid"], $journalItemText, $tags, $isPublic);
	}
	//write out header and
	//write out name of journal	
	$journalName = $queryObj->getJournalName($journalid);
	
	echo("<html>\n");

	echo("<head>\n");
	echo("<title>Scribb: ".$journalName."</title>\n");
	echo("<base target=\"journalentriesframe\">\n");
	echo("</head>\n");
	echo("<body>\n");
	
	echo("<font face=\"".$fontName."\" color=\"".$journalFontColour."\">\n");
	echo("<b>\n");
	echo($journalName);
	echo("</b>\n");
	echo("</font>\n");
	echo("<br>\n");



	//get all journal items
	$itemsFrom = 0;
	$itemsTo = 0;
	if(!$isRead)
		$itemsTo = $itemsFrom + $itemCount;
		
	$result = $queryObj->GetJournalItems($journalid, $itemsFrom, $itemsTo);
	//insert categories into array
	$itemDate;
	$itemDatePrev;
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
		echo($time." ");
		//show text
		echo($row[2]);

		if(!$isRead)
		{
			//set up links for deleting and editing
			echo("&nbsp<a href=\"journalentries.php?doaction=delete&itemid=".$row[3]."&journalid=".$journalid."\">d</a>");
			echo("&nbsp<a target=\"dataentryframe\" href=\"dataentry.php?doaction=edit&itemid=".$row[3]."&journalid=".$journalid."\">e</a>");
			
		}
		echo("<br>");
		$extrabreak=false;
		

		//get tags for this item
		$tagresult = $queryObj->GetCategoriesForItem($row[3]);
		while($tagrow = mysql_fetch_row ($tagresult))
		{
			//set up font for tags
			echo($tagFont);
			echo(" ".$tagrow[0]);
			echo("</font>");
			$extrabreak=true;
		}
		
		//if item is updated, show the update date
		if($row[0] != $row[1] || $row[4] != $row[5])
		{
			echo($tagFont);
			echo("<i>");
			echo(" Updated&nbsp;".str_replace(" ", "&nbsp;", $row[1])."&nbsp;".$row[5]);
			echo("</i>");
			echo("</font>");
			$extrabreak=true;
		}
		if($extrabreak)
      echo("<br>");
		echo("<br>");

		echo("</li>\n");
	}
	echo("</ul>\n");
/**/

	?>
</body>
</html>
