<html>

<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
<title>Navigation</title>
<base target="loggedinmain">
<script src="getitems.js"></script>
</head>

<?php

	include("pagelayout.php");

	$layoutObj = new NavigatorPageLayout;

	$userid = $HTTP_COOKIE_VARS["userid"];

	//retrieve variables
	$tableWidth = $layoutObj->getTableWidth();
	$tableBgColour = $layoutObj->getTableBgColour();
	$pageBgColour = $layoutObj->getPageBgColour();
	$tableCellColour = $layoutObj->getTableCellColour();
	$fontName = $layoutObj->getFontName();
	$linkColour = $layoutObj->getLinkColour();
	$fontColour = $layoutObj->getFontColour();

	/**/
	echo("<body bgcolor=\"".$pageBgColour."\" link=\"".$linkColour."\" vlink=\"".$linkColour."\">\n");

	echo("<div align=\"left\">\n");
	echo("<table border=\"0\" width=\"".$tableWidth."\" cellpadding=\"0\" cellspacing=\"4\">\n");

	echo("   <tr>\n");
	echo("      <td width=\"100%\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font color=\"".$fontColour."\" face=\"".$fontName."\">\n");
	echo("					<a href=\"journalmanagement.php\" target=\"loggedinmain\">\n");
	echo("					journals</a></font> \n");
	echo("					<a href=\"#\" target=\"navigation\" onmouseup=\"listItems('".$userid."','journals')\">\n");
	echo("					<span id=\"journalexpander\">+</span></a><br>\n");
	echo("					<span id=\"journallist\"></span>\n");
	echo("		</td>\n");
	echo("   </tr>\n");
	echo("   <tr>\n");



	echo("      <td width=\"100%\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font color=\"".$fontColour."\" face=\"".$fontName."\">\n");
	echo("					<a href=\"categorymanagement.php\" target=\"loggedinmain\">\n");
	echo("					tags</a></font> \n");
	echo("					<a href=\"#\" target=\"navigation\" onmouseup=\"listItems('".$userid."','tags')\">\n");
	echo("					<span id=\"tagexpander\">+</span></a><br>\n");
	echo("					<span id=\"taglist\"></span>\n");
	echo("		</td>\n");
	echo("   </tr>\n");

	//list tags here

	echo("   <tr>\n");
	echo("      <td width=\"100%\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font color=\"".$fontColour."\" face=\"".$fontName."\">\n");
	echo("					<a href=\"search.php\" target=\"loggedinmain\">\n");
	echo("					search</a></font></td>\n");
	echo("   </tr>\n");

	
	echo("   <tr>\n");
	echo("      <td width=\"100%\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font color=\"".$fontColour."\" face=\"".$fontName."\">\n");
	echo("					<a href=\"invite.php\" target=\"loggedinmain\">\n");
	echo("					invite</a></font></td>\n");
	echo("   </tr>\n");

	echo("   <tr>\n");
	echo("      <td width=\"100%\" bgcolor=\"".$tableCellColour."\">\n");
	echo("				<font color=\"".$fontColour."\" face=\"".$fontName."\">\n");
	echo("					<a href=\"login.php?doaction=logout\" target=\"main\">\n");
	echo("					log out</a></font></td>\n");
	echo("   </tr>\n");



	echo("</table>\n");
	echo("</div>\n");
	echo("</body>\n");


?>


</html>
