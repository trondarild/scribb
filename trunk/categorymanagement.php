<html>

<head>
<title>Categories</title>
<base target="_self">
</head>

<?php

	//echo 'Current PHP version: ' . phpversion().'<br>';

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
	$categoryBoxContent = "";

	//check what we should do..rename, delete, create or just get the categorys
	$doAction = $_POST["doaction"];
	if($doAction=="")
		$doAction=$doaction;
	//echo("doaction=".$doAction);

	//used to get all categories
	$allPersonalCatResult="";
	$allGlobalCatResult="";
	if($doAction == "rename")
	{
		//echo("renaming to \n");
		//echo($_POST["categoryname"]);
		//echo(" id = ");
		//echo($_POST["categoryid"]);
		//echo("<br>");
		$queryObj->setCategoryName($_POST["categoryid"], $_POST["categoryname"]);
	}
	else if($doAction == "getcategoryname")
	{
		//echo("getting category name\n");
		$categoryBoxContent = $categoryname;//_POST["categoryname"];
		//echo(" ");
		//echo($categoryBoxContent);
	}
	else if($doAction == "delete")
	{
		//must be a confirm dialog box before coming here..this will also delete from usedcategories
		//and itemcategory connection
		//echo("id=".$categoryid);
		$queryObj->deleteCategory($categoryid);//_POST["categoryid"]);


	}
	else if($doAction == "create")
	{
		//create a new category with the given name
		$id=$queryObj->createCategory($_POST["categoryname"], FALSE, $userid);
		//check if it should be used
		$idarray[] = $id;

		if($_POST["usenewcategory"] != "")
		{
			$queryObj->setCategoryUsed($idarray,$userid, TRUE);
		}


	}
	else if($doAction == "setused")
	{
		//create a new category with the given name
		//go through all checkboxes and extract categoryid and whether it is used
		$allPersonalCatResult = $queryObj->getAllCategoriesForUser($userid, TRUE);
		$allGlobalCatResult = $queryObj->getAllCategoriesForUser($userid, FALSE);
		$usedTags = array();
		$unusedTags = array();
		//build an array of personal ids..
		while ($row = mysql_fetch_row($allPersonalCatResult))
		{
			if($_POST["C_".$row[0]]!="")
				$usedTags[] = $row[0];
			else
				$unusedTags[] = $row[0];
		}
		//build an array of global ids..
		while ($row = mysql_fetch_row($allGlobalCatResult))
		{
			if($_POST["C_".$row[0]]!="")
				$usedTags[] = $row[0];
			else
				$unusedTags[] = $row[0];
		}

		$queryObj->setCategoryUsed($usedTags,$userid, TRUE);
		$queryObj->setCategoryUsed($unusedTags,$userid, FALSE);
	}

	//get all categorys
	//echo("getting categorys\n<br>");
	$allPersonalCatResult = $queryObj->getAllCategoriesForUser($userid, TRUE);
	//echo("line 84<br>");
	//get used categories
	//echo("getting used categories<br>\n");
	$usedCatResult = $queryObj->getUsedCategoryIds($userid);

	//fill array with used categories
	$num_rows = mysql_num_rows($usedCatResult);
	if($num_rows!=0)
	{
		while ($row = mysql_fetch_row ($usedCatResult))
			$usedCatArray[] = $row[0];
	}
	else
		$usedCatArray = "";

	echo("	<body bgcolor=\"".$pageBgColour."\">\n");

	echo("	<form method=\"POST\" action=\"categorymanagement.php\">\n");
	echo("		<table border=\"0\" width=\"".$tableWidth."\" bgcolor=\"".$tableBgColour."\">\n");

	echo("			<tr>\n");
	echo("				<td colspan=\"3\" bgcolor=\"".$tableHeaderCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">Personal tags</font></td>\n");
	echo("				<td colspan=\"1\" bgcolor=\"".$tableHeaderCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">Use</font></td>\n");

	echo("			</tr>\n");

	$checkBoxIx = 0;
	//if no categorys are present, print out a message
	$num_rows = mysql_num_rows($allPersonalCatResult);
	if($num_rows==0)
	{
		//make the row
		echo("			<tr>\n");
		echo("				<td bgcolor=\"".$tableCellColour."\" colspan=\"4\">\n");
		echo("				<font face=\"".$fontName."\">\n");
		echo("				Create a personal tag by typing in the name below and clicking \"Create\"\n");
		echo("				</font></td>\n");

	}
	else
	{

		//now loop through all the categorys
		while ($row = mysql_fetch_row ($allPersonalCatResult))
		{

			//show the name of the category
			echo("			<tr>\n");
			echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
			echo("				<font face=\"".$fontName."\">");
			echo($row[1]);
			echo("				</font></td>\n");

			//make delete cell
			echo("				<td width=\"245\" bgcolor=\"".$tableCellColour."\">\n");
			echo("				<font face=\"".$fontName."\">\n");
			echo("				<a href=\"categorymanagement.php?doaction=delete&categoryid=".$row[0]."\">\n");
			echo("				Delete</font></td>\n");

			//make rename cell
			echo("				<td width=\"10\" bgcolor=\"".$tableCellColour."\">\n");
			echo("				<font face=\"".$fontName."\">\n");
			echo("				<a href=\"categorymanagement.php?doaction=getcategoryname&categoryname=".$row[1]."&categoryid=".$row[0]."\">\n");
			echo("				Rename</font></td>\n");

			//make used checkbox cell
			echo("				<td width=\"10\" bgcolor=\"".$tableCellColour."\">\n");
			echo("				<font face=\"".$fontName."\">\n");

			echo("				<input type=\"checkbox\" name=\"C_".$row[0]."\" value=");
			//is this category marked as used?
			if($usedCatArray!="" && in_array($row[0], $usedCatArray))
				echo("\"ON\" checked" );
			else
				echo("\"OFF\"");
			echo("></font>");
			echo("				</td>\n");
			echo("			</tr>\n");

			$checkBoxIx++;
		}
	}

	//add global categories
	$allGlobalCatResult = $queryObj->getAllCategoriesForUser($userid, FALSE);
	$num_rows = mysql_num_rows($allGlobalCatResult);
	if($num_rows!=0)
	{

		echo("			<tr>\n");
		echo("				<td colspan=\"4\" bgcolor=\"".$tableHeaderCellColour."\">\n");
		echo("				<font face=\"".$fontName."\">Global tags</font></td>\n");
		echo("			</tr>\n");

		//now loop through all the categorys
		//now loop through all the categorys
		while ($row = mysql_fetch_row ($allGlobalCatResult))
		{

			//show the name of the category
			echo("			<tr>\n");
			echo("				<td colspan = \"3\"  bgcolor=\"".$tableCellColour."\">\n");
			echo("				<font face=\"".$fontName."\">");
			echo($row[1]);
			echo("				</font></td>\n");

			//make used checkbox cell
			echo("				<td width=\"10\" bgcolor=\"".$tableCellColour."\">\n");
			echo("				<font face=\"".$fontName."\">\n");

			echo("				<input type=\"checkbox\" name=\"C_".$row[0]."\" value=");
			//is this category marked as used?
			if($usedCatArray!="" && in_array($row[0], $usedCatArray))
				echo("ON checked");
			else
				echo("OFF");
			echo("></font></td>\n");

			echo("			</tr>\n");

			$checkBoxIx++;
		}
	}

	//row for submit button


	//text field for category name
	echo("			<tr>\n");
	echo("				<td colspan = \"1\" bgcolor=\"".$tableCellColour."\">\n");
	echo("					&nbsp\n");
	echo("				</td>\n");

	//submit button
	echo("				<td colspan = \"3\" bgcolor=\"".$tableCellColour."\">\n");
	echo("					<input type=\"submit\" value=\"Update used\" name=\"submitbtn\">\n");
	echo("				</td>\n");
	echo("			</tr>\n");

	//make bottom row just to have a nice look
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\" colspan=\"4\">\n");
	echo("				&nbsp\n");
	echo("				</td>\n");
	echo("			</tr>\n");
	echo("		</table>\n");
	echo("		<input type=\"hidden\" value=\"setused\" name=\"doaction\">\n");
	echo("	</form>\n");


	//make form for creating and renaming
	echo("	<form method=\"POST\" action=\"categorymanagement.php\">\n");

	echo("		<table border=\"0\" width=\"".$tableWidth."\" bgcolor=\"".$tableBgColour."\">\n");


	//header
	echo("			<tr>\n");
	echo("				<td colspan=\"3\" bgcolor=\"".$tableHeaderCellColour."\">\n");
	echo("				<font face=\"".$fontName."\">");
	if($doAction=="getcategoryname")
		echo("Rename tag");
	else
		echo("New tag");
	echo("</font></td>\n");
	echo("			</tr>\n");

	//text field for category name
	echo("			<tr>\n");
	echo("				<td bgcolor=\"".$tableCellColour."\">\n");
	echo("					<input type=\"text\" name=\"categoryname\" size=\"26\" value=\"".$categoryBoxContent."\">\n");
	if($doAction != "getcategoryname")
	{
		echo("				<input type=\"checkbox\" name=\"usenewcategory\"\n>");
		echo("				<font face=\"".$fontName."\">Use</font>\n");
	}
	echo("				</td>\n");

	//submit buttons
	echo("				<td bgcolor=\"".$tableCellColour."\">\n");
	if($doAction == "getcategoryname")
		echo("					<input type=\"submit\" value=\"Rename\" name=\"submitbtn\">\n");
	else
		echo("					<input type=\"submit\" value=\"Create\" name=\"submitbtn\">\n");
	echo("				</td>\n");
	echo("			</tr>\n");

	//make bottom row just to have a nice look
	echo("			<tr>\n");
	echo("				<td  bgcolor=\"".$tableCellColour."\" colspan=\"2\">\n");
	echo("				&nbsp\n");
	echo("				</td>\n");
	echo("			</tr>\n");

	echo("		</table>\n");

	//insert hidden field which tells whether should create or rename category
	if($doAction == "getcategoryname")
	{
		echo("		<input type=\"hidden\" value=\"rename\" name=\"doaction\">\n");
		echo("		<input type=\"hidden\" value=\"".$categoryid."\" name=\"categoryid\">\n");
	}
	else
		echo("		<input type=\"hidden\" value=\"create\" name=\"doaction\">\n");

	//have a field for user id
	echo("		<input type=\"hidden\" value=\"".$userid."\" name=\"userid\">\n");

	echo("	</form>\n");

?>
</body>
</html>
