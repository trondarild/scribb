<!DOCTYPE HTML SYSTEM>
<html>

<head>
<title>Dataentry</title>
<base target="journalentriesframe">

<script type="text/javascript">
	function setFocus()
	{
		document.getElementById("textbox").focus()

	}

	function onSubmit()
	{

		document.getElementById("journalitemtext").value = document.getElementById("textbox").value

		document.getElementById("textbox").value = ""
		document.getElementById("submitbtn").value ="Scribb!"
		document.getElementById("textbox").focus()

		//only do an update once for each page load,
		//want to go back to add mode after
		if(document.getElementById("lastaction").value=="")
			document.getElementById("lastaction").value=document.getElementById("doaction").value
		else
			document.getElementById("doaction").value = "add"

		//document.getElementById("usedtags").innerHTML="verdi "+document.getElementById("doaction").value
		document.getElementById("itemform").submit()
		//document.getElementById("journalitemtxt").select()



	}

	function tagClicked(id, name)
	{
		var vistext = document.getElementById("usedtags").innerHTML
		//check if contains the name
		if(vistext.search(name) != -1)
			vistext = vistext.replace(name, "")
		else
			vistext = vistext.concat(name).concat(" ")

		document.getElementById("usedtags").innerHTML=vistext

		//do the form field
		hiddentext = document.getElementById("usedcat").value
		if(hiddentext.search(id+" ") != -1)
			hiddentext = hiddentext.replace(id, "")
		else
			hiddentext = hiddentext.concat(id).concat(" ")

		hiddentext = document.getElementById("usedcat").value = hiddentext

	}

	function clearTags()
	{
		hiddentext = document.getElementById("usedcat").value = ""
		document.getElementById("usedtags").innerHTML=""
	}




</script>

</head>

<?php

	include("pagelayout.php");
	include("databasequery.php");
	$layoutObj = new PageLayout;
	$queryObj = new DatabaseQuery;

	//get userid
	$userid = $HTTP_COOKIE_VARS["userid"];
	//echo("journalid=".$journalid);

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
	$tagColour = $layoutObj->getTagColour();

	//create font tag for tags
	$tagFont = "<font face=\"".$layoutObj->getTagFont()."\"";
	$tagFont = $tagFont." color=\"".$layoutObj->getTagColour()."\"";
	$tagFont = $tagFont.">";



	//check what we should do..edit an existing or new item
	$doAction = $_POST["doaction"];
	if($doAction=="")
		$doAction=$doaction;
	//echo("doaction=".$doAction);
	//echo("<br>");
	$itemCategoryArray=array();
	$journalText="";
	$usedCatNames="";
	$usedCatIds="";
	$updateBtnTxt = "Scribb!";

	if($doAction=="edit")
	{
		//get item text
		$result = $queryObj->getJournalTextIsPublic($itemid);
		$row = mysql_fetch_row ($result);

		$journalText = $row[0];
		$isPublic = $row[1];

		//get the categories set for this item
		$result = $queryObj->getCategoriesForItem($itemid);
		//make strings of categories
		while($row = mysql_fetch_row ($result))
		{
			$usedCatIds = $usedCatIds.$row[1]." ";
			$usedCatNames= $usedCatNames.$row[0]." ";

		}
		$updateBtnTxt = "Update Scribb!";

	}

	//get used categories
	//first get personal and global categories
	$globalCategoriesId = array();
	$globalCategoriesName = array();
	$personalCategoriesId = array();
	$personalCategoriesName = array();

	$catResult = $queryObj->getAllUsedCategoryNamesIds($userid);
	$num_rows = mysql_num_rows($catResult);

	if($num_rows!=0)
	{

		while($row = mysql_fetch_row ($catResult))
		{
			//check if global
			if($row[2]==1)
			{
				$globalCategoriesId[]=$row[0];
				$globalCategoriesName[]=$row[1];
			}
			else
			{
				$personalCategoriesId[]=$row[0];
				$personalCategoriesName[]=$row[1];
			}
		}
	}

	$numGlobalCat = sizeof($globalCategoriesId);
	$numPersCat = sizeof($personalCategoriesId);
	//check whether there is more personal or global categories
	$maxCatRows = 2;//$numGlobalCat > $numPersCat ? $numGlobalCat : $numPersCat;


	echo("	<body onload=\"setFocus()\" bgcolor=\"".$pageBgColour."\">\n");

	echo("	<form method=\"POST\" action=\"journalentries.php\" id=\"itemform\">\n");
	echo("		<table border=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"".$tableBgColour."\">\n");
	$rowspannr = $maxCatRows+1;
	echo("			<tr>\n");
	echo("				<td valign=\"top\" width=\"200\">\n");
	echo("				<textarea rows=\"9\" name=\"textbox\" id=\"textbox\" cols=\"40\">");
	if($journalText!="")
		echo($journalText."\n");
	echo(				"</textarea><br>\n");
	echo("				</td>\n");

	//add personal categories
	echo("				<td bgcolor=\"".$tableCellColour."\" valign=\"top\">\n");
	echo("				<p>\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				Personal tags:\n");
	echo("				</font><br>\n");

	$tabix = 1;
	for($i=0; $i<$numPersCat; $i++)
	{
		$tabix++;
		echo("				<a href=\"#\" target=\"dataentryframe\"  onclick=\"tagClicked('".$personalCategoriesId[$i]."','".$personalCategoriesName[$i]."')\" >\n");
		echo("				".$tagFont."\n");

		echo("				".$personalCategoriesName[$i]."</font></a>\n ");

	}
	echo("				</p>\n");

	//add global categories
	echo("				<p>\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				Global tags:\n");
	echo("				</font><br>\n");

	
	for($i=0; $i<$numGlobalCat; $i++)
	{
		$tabix++;
		echo("				<a href=\"#\" target=\"dataentryframe\"  onclick=\"tagClicked('".$globalCategoriesId[$i]."','".$globalCategoriesName[$i]."')\" >\n");
		echo("				".$tagFont."\n");

		echo("				".$globalCategoriesName[$i]."</font></a> ");

	}
	$tabix++;
	echo("				</p>\n");
	echo("				<p valign=\"bottom\">\n");
	echo("				<a href=\"#\" target=\"dataentryframe\"  onclick=\"clearTags()\">\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				[Clear tags]\n");
	echo("				</font></a><br>\n");
	echo("				</p>\n");

	//add button for clearing tags


	echo("				</td>\n");
	echo("			</tr>\n");

	echo("			<tr>\n");
	echo("				<td  colspan=\"2\">\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				Tags:\n");
	echo("				</font>\n");
	echo("				".$tagFont."\n");
	echo("				<span id=\"usedtags\">".$usedCatNames."</span>\n");
	echo("				</font><br>\n");
	echo("				<input id=\"submitbtn\" tabindex=\"1\" type=\"submit\" value=\"".$updateBtnTxt."\" name=\"submitbtn\" onclick=\"onSubmit()\">\n");
	$tabix++;
	echo("				<input type=\"checkbox\"   name=\"ispublic\"");
	if($isPublic==1)
		echo(" checked value=\"ON\"");
	echo(">\n");
	echo("				<font face=\"".$fontName."\">\n");
	echo("				Shared\n");
	echo("				</font>\n");
	echo("				</td>\n");
	echo("			</tr>\n");

	echo("		</table>\n");

	//add hidden fields
	if($doAction=="edit")
	{
		echo("		<input type=\"hidden\" value=\"".$itemid."\" name=\"itemid\">\n");
		$doAction = "update";
	}
	else
	{
		$doAction = "add";
	}
	echo("		<input type=\"hidden\" value=\"".$doAction."\" name=\"doaction\" id=\"doaction\">\n");
	echo("		<input type=\"hidden\" value=\"".$journalid."\" name=\"journalid\">\n");


	//make a string of all categories used, so can extract it later
	echo("		<input type=\"hidden\" value=\"".$usedCatIds."\" name=\"usedcat\" id=\"usedcat\" >\n");
	echo("		<input type=\"hidden\" value=\"\" name=\"journalitemtext\" id=\"journalitemtext\" >\n");
	echo("	</form>\n");
	echo("	<form>\n");
	echo("		<input type=\"hidden\" value=\"\" name=\"lastaction\" id=\"lastaction\" >\n");
	echo("	</form>\n");


	?>
	</body>
</html>