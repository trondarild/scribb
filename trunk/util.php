<?php
//include("pagelayout.php");
	
class Util
{
	function Util($aPageLayout)
	{
		$this->mPageLayout = $aPageLayout;
	}
	//get
	
	//set
	
	//is
	
	//other
	function makeJournalTableRows($aResult, $aArchived)
	{
		$layoutObj = $this->mPageLayout;
	
		//retrieve variables
		$tableWidth = $layoutObj->getTableWidth();
		$tableBgColour = $layoutObj->getTableBgColour();
		$pageBgColour = $layoutObj->getPageBgColour();
		$tableHeaderCellColour = $layoutObj->getTableHeaderCellColour();
		$tableCellColour = $layoutObj->getTableCellColour();
		$fontName = $layoutObj->getFontName();
		$linkColour = $layoutObj->getLinkColour();
		
		$retstr = "";
		
		//now loop through all the journals
		while ($row = mysql_fetch_row ($aResult))
		{

			$journalname = str_replace(" ", "&nbsp;", $row[1]);
			//show the name of the journal(hyperlink to view journal)
			$retstr=$retstr."			<tr>\n";
			$retstr=$retstr."				<td width=\"250\" bgcolor=\"".$tableCellColour."\">\n";
			$retstr=$retstr."				<font face=\"".$fontName."\">";
			$retstr=$retstr."				<a href=\"dataentryframeset.php?journalid=".$row[0]."\">\n";
			$retstr=$retstr.$journalname;
			$retstr=$retstr."				</a>";
			$retstr=$retstr."				</font></td>\n";

			//make read cell
			$retstr=$retstr."				<td width=\"200\" bgcolor=\"".$tableCellColour."\">\n";
			$retstr=$retstr."				<font face=\"".$fontName."\">";
			$retstr=$retstr."				<a href=\"journalentries.php?journalid=".$row[0]."&doaction=read\" target=_blank>\n";
			$retstr=$retstr."				Read";
			$retstr=$retstr."				</a></font></td>\n";


			//make rename cell
			$retstr=$retstr."				<td width=\"100\" bgcolor=\"".$tableCellColour."\">\n";
			$retstr=$retstr."				<font face=\"".$fontName."\">\n";
			$retstr=$retstr."				<a href=\"journalmanagement.php?doaction=getjournalname&journalname=".$row[1]."&journalid=".$row[0]."\">\n";
			$retstr=$retstr."				Rename\n";
			$retstr=$retstr."				</a></font></td>\n";

			//make archive cell
			$retstr=$retstr."				<td width=\"100\" bgcolor=\"".$tableCellColour."\">\n";
			$retstr=$retstr."				<font face=\"".$fontName."\">\n";
			if($aArchived)
			{
				$retstr=$retstr."				<a href=\"journalmanagement.php?doaction=archive&journalid=".$row[0]."&archive=0\">\n";
				$retstr=$retstr."				De-archive\n";
				
			}
			else
			{
				$retstr=$retstr."				<a href=\"journalmanagement.php?doaction=archive&journalid=".$row[0]."&archive=1\">\n";
				$retstr=$retstr."				Archive\n";
				
				
			}
			$retstr=$retstr."				</a></font></td>\n";


			//make delete cell
			$retstr=$retstr."				<td width=\"200\" bgcolor=\"".$tableCellColour."\">\n";
			$retstr=$retstr."				<font face=\"".$fontName."\">\n";
			$retstr=$retstr."				<a href=\"journalmanagement.php?doaction=delete&journalid=".$row[0]."\">\n";
			$retstr=$retstr."				Delete";
			$retstr=$retstr."				</a></font></td>\n";

			$retstr=$retstr."			</tr>\n";
			
		}
		return $retstr;
	}
	
	var $mPageLayout;	
}
?>