
<?php

	include("pagelayout.php");
	include("databasequery.php");
	$layoutObj = new PageLayout;
	$queryObj = new DatabaseQuery;

	//get userid

	//retrieve variables
	$tableWidth = $layoutObj->getTableWidth();
	$tableBgColour = $layoutObj->getTableBgColour();
	$pageBgColour = $layoutObj->getPageBgColour();
	$tableHeaderCellColour = $layoutObj->getTableHeaderCellColour();
	$tableCellColour = $layoutObj->getTableCellColour();
	$fontName = $layoutObj->getFontName();
	$linkColour = $layoutObj->getLinkColour();
	//$fontColour = $layoutObj->getFontColour();
	$fontSize = $layoutObj->getFontSize();

	//max length of tag name before truncating
	$cMAXNAMELEN = 22;

	if($type=="journals")
	{
		//get all non-archived journals and make a row
		$archived = 0;
		$result = $queryObj->getAllJournalsForUser($userid, $archived);
		if(mysql_num_rows($result)!=0)
		{
			echo("				<font face=\"".$fontName."\" size=\"-.".$fontSize."\">\n");

			while($row=mysql_fetch_row($result))
			{
				$name = $row[1];
				if(strlen($name) > $cMAXNAMELEN)
				{
					$name = substr($name, 0, $cMAXNAMELEN-2)."..";
				}

				echo("				&nbsp;&nbsp;<a href=\"dataentryframeset.php?journalid=".$row[0]."\">\n");
				echo("				".str_replace(" ","&nbsp;",$name)."<br>\n");
				echo("				</a>\n");
			}
			echo("				</font>\n");
		}


	}
	else if($type=="tags")
	{
		//get all tags
		//first get personal and global categories

		$catResult = $queryObj->getAllUsedCategoryNamesIds($userid);
		$num_rows = mysql_num_rows($catResult);

		if($num_rows!=0)
		{
			echo("				<font face=\"".$fontName."\" size=\"-.".$fontSize."\">\n");
			while($row = mysql_fetch_row ($catResult))
			{
				$name = $row[1];
				if(strlen($name) > $cMAXNAMELEN)
				{
					$name = substr($name, 0, $cMAXNAMELEN-2)."..";
				}

				echo("				&nbsp;&nbsp;<a href=\"search.php?searchstring=tag:".str_replace(" ","-",$row[1])."\">\n");
				echo("				".$name."<br>\n");
				echo("				</a>\n");
			}
			echo("				</font>\n");
		}


	}
?>