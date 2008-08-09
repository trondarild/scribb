<?php
	include ("databaseconnect.php");

	class DatabaseQuery
	{
		//constructor
		function DatbaseQuery()
		{
			//get database connection

			$this->mConnection = new DatabaseConnection();
		}

		function sendQuery($aQueryString)
		{
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			//echo($aQueryString."\n");
			//do the update
			return mysql_query($aQueryString, $con);

		}

		function sendMultipleQuery($aQueryArray)
		{
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();

			foreach($aQueryArray as $value)
				mysql_query($value, $con);
		}

		//get

		//Get all the journals registered for given user
		//Param: aUser the user id
		//Return: A result set with journals
		function getAllJournalsForUser($aUser, $aArchived)
		{
			//build query string
			$queryString = "select journal_id,journal_name from JOURNAL where journal_user_id=".$aUser;
			$queryString = $queryString." and journal_isarchived=".$aArchived;
			$queryString = $queryString." order by journal_name";
			//echo($queryString."<br>\n");
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			return mysql_query($queryString, $con);
		}

		//get array of journal names and ids where name is key
		function GetJournalNamesIdsArray($aUserId)
		{
			$returnArray;
			$result = $this->getAllJournalsForUser($aUserId, 1);
			while($row = mysql_fetch_row($result))
			{
				$returnArray[strtolower($row[1])] = $row[0];
			}

			return $returnArray;
		}


		//get journal name
		function getJournalName($aJournalId)
		{
			$queryString = "select JOURNAL_NAME from JOURNAL where JOURNAL_ID=".$aJournalId;
			$result = $this->sendQuery($queryString);
			$row = mysql_fetch_row ($result);
			return $row[0];

		}


		//Get the user id number for a given user name
		//param: aUserName the user name to get id for
		//return: the user id number
		function getUserId($aUserName)
		{
			$queryString = "select user_id from USER where user_user_name='".$aUserName."'";
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			$result = mysql_query($queryString, $con);

			return mysql_result($result,0,"user_id");
		}


		//get the text for a journal item with given id and whether it is public
		//param: aJournalId
		//return: text for journal item
		function getJournalTextIsPublic($aJournalItmId)
		{
			$queryString = "select JOURNALITEM_TEXT,JOURNALITEM_ISPUBLIC from JOURNALITEM where JOURNALITEM_ID=".$aJournalItmId;
			return  $this->sendQuery($queryString);

		}
		
		
		//get number of remaining invites
		function getRemainingInvites($aUserId)
		{
			$queryString = "select user_num_invites from USER where user_id=".$aUserId;
			return  $this->sendQuery($queryString); 
		}
		
		function getHashInfo($aUserId)
		{
			$queryString = "select * from USERINVITES where userinvites_userid=".$aUserId;
			return  $this->sendQuery($queryString); 
		}

		function getFullName($aUserId)
		{
			$queryString = "select user_full_name from USER where user_id=".$aUserId;
			return  $this->sendQuery($queryString); 
		}

		function getJournalId($aJournalName, $aUserId)
		{
			$queryString = "select journal_id from JOURNAL where journal_user_id=".$aUserId;
			$queryString = $queryString." and journal_name = '".$aJournalName."'";  
			echo($queryString);
			$result = $this->sendQuery($queryString);

			$num_rows = mysql_num_rows($result);
			if($num_rows>0)
			{
				$row = mysql_fetch_row ($result);
				return $row[0];
			}
			return "";
		}


		//set

		//Sets whether the user is logged
		//Param: aUserName
		//Param: aLoggedIn 1 if user is logged in, 0 if logged out
		//Return nothing
		function setUserLoggedIn($aUserName, $aLoggedIn)
		{

			$queryString = "update USER set user_loggedin=".$aLoggedIn." where user_user_name='".aUserName."'";
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			//do the update
			mysql_query($queryString, $con);
		}


		//Sets name of journal
		//param: aJournalId id number of journal
		//param: aName name to set
		//return nothing
		function setJournalName($aJournalId, $aName)
		{
			$queryString = "update JOURNAL set journal_name='".$aName."' where journal_id=".$aJournalId;
			$this->sendQuery($queryString);

		}
		
		
		//Sets journal archived status
		function setJournalArchived($aJournalId, $aArchived)
		{
				$queryString = "update JOURNAL set journal_isarchived='".$aArchived."' where journal_id=".$aJournalId;
				//echo($queryString);
				$this->sendQuery($queryString);			
		}
		//is

		//other

		//delete a journal
		function deleteJournal($aJournalId)
		{
			$queryString = "delete from JOURNAL where JOURNAL_ID = ".$aJournalId;
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			//echo($queryString."\n");
			//do the update
			mysql_query($queryString, $con);
		}


		//Create a new journal
		function createJournal($aName, $aUserId)
		{
			$queryString = "insert into JOURNAL (journal_name, journal_user_id) values ('".$aName."',".$aUserId.")";
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			//echo($queryString."\n");
			//do the update
			mysql_query($queryString, $con);
		}


		//Sets name of category
		function setCategoryName($aCategoryId, $aName)
		{
			$queryString = "update CATEGORY set CATEGORY_NAME='".$aName."' where CATEGORY_ID=".$aCategoryId;

			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			//do the update
			mysql_query($queryString, $con);

		}


		//delete a category
		function deleteCategory($aCategoryId)
		{
			$queryString = "delete from CATEGORY where CATEGORY_ID = ".$aCategoryId;
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			//do the update
			mysql_query($queryString, $con);

			$queryString = "delete from USEDCATEGORIES where USEDCATEGORIES_CATEGORY_ID = ".$aCategoryId;
			mysql_query($queryString, $con);

			$queryString = "delete from CATEGORYITEMCONNECTION where CATEGORYITEMCONNECTION_CATEGORY_ID = ".$aCategoryId;
			mysql_query($queryString, $con);
		}


		//Create a new category
		function createCategory($aName, $aGlobal, $aUserId)
		{
			$queryString = "insert into CATEGORY (CATEGORY_NAME,  CATEGORY_USER_ID, CATEGORY_ISGLOBAL) values ('".$aName."',".$aUserId;
			if($aGlobal)
				$queryString = $queryString.",1)";
			else
				$queryString = $queryString.",0)";

			$this->sendQuery($queryString);

			return mysql_insert_id();
		}


		//set whether a category is used by a particular user
		function setCategoryUsed($aCategoryId, $aUserId, $aIsUsed)
		{
			$queryArray=array();
			if($aIsUsed)
			{
				foreach($aCategoryId as $value)
				{
					$queryString = "insert into USEDCATEGORIES (USEDCATEGORIES_CATEGORY_ID, USEDCATEGORIES_USER_ID )";
					$queryString = $queryString." values (".$value.",".$aUserId.")";

					$queryArray[] = $queryString;
				}
			}
			else
			{
				foreach($aCategoryId as $value)
				{
					$queryString = "delete from USEDCATEGORIES where USEDCATEGORIES_CATEGORY_ID=".$value;
					$queryString = $queryString." and USEDCATEGORIES_USER_ID=".$aUserId;
					$queryArray[] = $queryString;
				}
			}
			//echo($queryString);
			$this->sendMultipleQuery($queryArray);

		}

		//gets the used category ids for a user
		function getUsedCategoryIds($aUserId)
		{
			$queryString = "select USEDCATEGORIES_CATEGORY_ID from USEDCATEGORIES";
			$queryString = $queryString." where USEDCATEGORIES_USER_ID=".$aUserId;


			return $this->sendQuery($queryString);
		}



		//get all categories for user
		function getAllCategoryNamesIds($aUserId)
		{
			$queryString = "select CATEGORY.CATEGORY_ID,CATEGORY.CATEGORY_NAME,CATEGORY.CATEGORY_ISGLOBAL";
			$queryString = $queryString." from CATEGORY";
			$queryString = $queryString." where CATEGORY_USER_ID=".$aUserId;
			$queryString = $queryString." or CATEGORY_ISGLOBAL=1";
			$queryString = $queryString." order by CATEGORY.CATEGORY_ISGLOBAL, CATEGORY.CATEGORY_NAME";
			//echo($queryString);
			return $this->sendQuery($queryString);

		}
		//get all used categories for user
		function getAllUsedCategoryNamesIds($aUserId)
		{
			$queryString = "select distinct CATEGORY.CATEGORY_ID,CATEGORY.CATEGORY_NAME,CATEGORY.CATEGORY_ISGLOBAL";
			$queryString = $queryString." from CATEGORY,USEDCATEGORIES";
			$queryString = $queryString." where USEDCATEGORIES.USEDCATEGORIES_USER_ID=".$aUserId;
			$queryString = $queryString." and CATEGORY.CATEGORY_ID=USEDCATEGORIES.USEDCATEGORIES_CATEGORY_ID";
			$queryString = $queryString." order by CATEGORY.CATEGORY_ISGLOBAL,CATEGORY.CATEGORY_NAME";

			//echo($queryString);
			return $this->sendQuery($queryString);

		}




		//gets all categories for a user, global and personal
		function getAllCategoriesForUser($aUserId, $aPersonal)
		{
			$queryString = "select CATEGORY_ID, CATEGORY_NAME, CATEGORY_ISGLOBAL from CATEGORY";
			$queryString = $queryString." where"; 
			
			$queryString = $queryString." CATEGORY_ISGLOBAL =";
			if(!$aPersonal)
				$queryString = $queryString."1";
			else
			{
				$queryString = $queryString."0";
				$queryString = $queryString." and CATEGORY_USER_ID = ".$aUserId;
			}
			$queryString = $queryString." order by CATEGORY_NAME";


			return $this->sendQuery($queryString);
		}



		//Check password for user
		//Param: aUser the user id
		//Return: true if password is valid, false otherwise
		function checkPassword($aUser, $aPassword)
		{
			//build query string
			$queryString = "select * from USER where user_user_name='".$aUser."'";
			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			$result = mysql_query($queryString, $con);

			//check if got a result
			$num_rows = mysql_num_rows($result);
			if($num_rows>0)
			{

				//get the password from the result set
				$password = mysql_result($result,0,"user_password");
				return ($password == $aPassword);
			}
			else
				return false;

		}



		//Insert item into journal
		//Param
		function InsertJournalItem($aJournalId, $aText, $aTags, $aIsPublic){
			$aIsPublic = $aIsPublic == "" ? 0 : 1;
			//build query string
			$queryString = "INSERT INTO JOURNALITEM ";
			$queryString = $queryString."( JOURNALITEM_JOURNAL_ID ,";
			$queryString = $queryString."JOURNALITEM_DATE_CREATED , JOURNALITEM_DATE_MODIFIED ,";
			$queryString = $queryString."JOURNALITEM_TEXT , JOURNALITEM_ISPUBLIC ,";
			$queryString = $queryString."JOURNALITEM_ISAPPROVED )";
			$queryString = $queryString."VALUES (".$aJournalId.", NOW( ) , NOW( ) , '".$aText."', ".$aIsPublic.", '1')";

			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			//echo($queryString."\n");
			//do the update
			mysql_query($queryString, $con);
			//now connect categories/tags to journal item
			//bug..should not use journalid here, must get id for item
			$itemid = $this->GetLatestItemIdForJournal($aJournalId);
			foreach($aTags as $tag){
				$queryString = "INSERT INTO CATEGORYITEMCONNECTION";
				$queryString = $queryString."( CATEGORYITEMCONNECTION_CATEGORY_ID , CATEGORYITEMCONNECTION_ITEM_ID )";
				$queryString = $queryString."VALUES (".$tag.", ".$itemid.")";
				//echo($queryString."\n");
				mysql_query($queryString, $con);

			}


		}

		//Update an item
		function UpdateJournalItem($aItemId, $aText, $aTags, $aIsPublic)
		{
			$aIsPublic = $aIsPublic == "" ? 0 : 1;
			//build query string
			$queryString = "update JOURNALITEM set";
			$queryString = $queryString." JOURNALITEM_DATE_MODIFIED=NOW(),";
			$queryString = $queryString." JOURNALITEM_TEXT = '".$aText."',";
			$queryString = $queryString." JOURNALITEM_ISPUBLIC=".$aIsPublic;
			$queryString = $queryString." where JOURNALITEM_ID=".$aItemId;

			$conobj = new DatabaseConnection();
			$con = $conobj->getConnection();
			//echo($queryString."\n");
			//do the update
			mysql_query($queryString, $con);

			//update tags..first delete all existing tags
			//delete item-category connection
			$queryString = "delete from CATEGORYITEMCONNECTION where CATEGORYITEMCONNECTION_ITEM_ID = ".$aItemId;
			mysql_query($queryString, $con);

			//then set new categories
			foreach($aTags as $tag){
				$queryString = "INSERT INTO CATEGORYITEMCONNECTION";
				$queryString = $queryString."( CATEGORYITEMCONNECTION_CATEGORY_ID , CATEGORYITEMCONNECTION_ITEM_ID )";
				$queryString = $queryString."VALUES (".$tag.", ".$aItemId.")";
				//echo($queryString."\n");
				mysql_query($queryString, $con);

			}

		}


		//Get items from journal
		function GetJournalItems($aJournalId, $aFrom, $aTo){
			//build query string
			$queryString = "SELECT";
			$queryString = $queryString." DATE_FORMAT(JOURNALITEM_DATE_CREATED , '%d %b %Y' ),";
			$queryString = $queryString." DATE_FORMAT(JOURNALITEM_DATE_MODIFIED, '%d %b %Y' ),";
			$queryString = $queryString." JOURNALITEM_TEXT, JOURNALITEM_ID,";
			$queryString = $queryString." DATE_FORMAT(JOURNALITEM_DATE_CREATED , '%H:%i' ),";
			$queryString = $queryString." DATE_FORMAT(JOURNALITEM_DATE_MODIFIED , '%H:%i' )";
			$queryString = $queryString." FROM JOURNALITEM";
			$queryString = $queryString." WHERE JOURNALITEM_JOURNAL_ID =".$aJournalId;
			$queryString = $queryString." ORDER BY JOURNALITEM_DATE_CREATED DESC";
			if($aFrom >0 || $aTo>0)
				$queryString = $queryString." LIMIT ".$aFrom.",".$aTo;
			
			//echo($queryString);
			return $this->sendQuery($queryString);
		}




		//fill an array with category names as key and id as content
		function GetCategoryNamesIdArray($aUserId)
		{
			$categoriesId = array();
			$catResult = $this->getAllCategoryNamesIds($aUserId);
			$num_rows = mysql_num_rows($catResult);

			if($num_rows!=0)
			{

				while($row = mysql_fetch_row ($catResult))
				{
					//check if global
					$categoriesId[strtolower($row[1])]=$row[0];
				}
			}

			return $categoriesId;
		}

		//get tags for a particular item
		function GetCategoriesForItem($aItemId)
		{
			$queryString = "select CATEGORY.CATEGORY_NAME, CATEGORY.CATEGORY_ID";
			$queryString = $queryString." from CATEGORY, CATEGORYITEMCONNECTION";
			$queryString = $queryString." where CATEGORY.CATEGORY_ID=";
			$queryString = $queryString." CATEGORYITEMCONNECTION.CATEGORYITEMCONNECTION_CATEGORY_ID";
			$queryString = $queryString." and CATEGORYITEMCONNECTION.";
			$queryString = $queryString."CATEGORYITEMCONNECTION_ITEM_ID =".$aItemId;
			$queryString = $queryString." order by CATEGORY.CATEGORY_NAME";
			//echo($queryString);

			return $this->sendQuery($queryString);

		}


		//delete a journal item
		function DeleteJournalItem($aItemId)
		{
			//echo("deleting item ".$aItemId."\n");
			$queryString = "delete from JOURNALITEM where JOURNALITEM_ID = ".$aItemId;
						$conobj = new DatabaseConnection();
						$con = $conobj->getConnection();

			//do the update
			mysql_query($queryString, $con);
			//delete item-category connection
			$queryString = "delete from CATEGORYITEMCONNECTION where CATEGORYITEMCONNECTION_ITEM_ID = ".$aItemId;
			mysql_query($queryString, $con);
		}

		function GetLatestItemIdForJournal($aJournalId)
		{
			$queryString = "select MAX(JOURNALITEM_ID) from JOURNALITEM where JOURNALITEM_JOURNAL_ID = ".$aJournalId;
			$result =  $this->sendQuery($queryString);
			$row = mysql_fetch_row($result);
			return $row[0];


		}

		//parses a search string and returns an sql search string
		function parseSearchString($aSearchString, $aUserId)
		{
			//find all tags/categories
			$tagname = "tag:";
			//delimiter is space
			$delimiter=" ";
			$journalname = "journal:";


			//first divide on delimiter
			$splitString = split($delimiter, $aSearchString);


			//go through array and sort into the different categories
			$freetext=array();
			$tags=array();
			$journals = array();
			//get all tags
			$categoryArray = $this->GetCategoryNamesIdArray($aUserId);
			//get all journals
			$journalArray = $this->GetJournalNamesIdsArray($aUserId);
			foreach($splitString as $value)
			{
				if(stristr($value, $tagname))
				{
					//make lowercase and look up in array, remove "tag:" and '-'s (used for space)
					$name = str_replace($tagname, "", $value);
					$name = str_replace("-", " ", $name);
					$name = strtolower($name);

					$id = $categoryArray[$name];
					//echo("tagname=".str_replace($tagname, "", $value)." tagid=".$id." ");
					if($id != "")
						$tags[] = $id;
				}
				else if(stristr($value, $journalname))
				{
					//make lowercase, remove "journal:"  look up in array
					$name = str_replace($journalname, "", $value);
					$name = str_replace("-", " ", $name);
					$name = strtolower($name);

					$id = $journalArray[$name];
					if($id != "")
						$journals[] = $id;
				}
				else
					$freetext[] = $value;
			}

			//create the sql string
			$queryString = "SELECT DISTINCT";
			$queryString = $queryString." DATE_FORMAT(JOURNALITEM.JOURNALITEM_DATE_CREATED , ' %d %b %Y' ),";
			$queryString = $queryString." DATE_FORMAT(JOURNALITEM.JOURNALITEM_DATE_MODIFIED, ' %d %b %Y' ),";
			$queryString = $queryString." JOURNALITEM.JOURNALITEM_TEXT, JOURNALITEM.JOURNALITEM_ID,";
			$queryString = $queryString." DATE_FORMAT(JOURNALITEM.JOURNALITEM_DATE_CREATED , ' %H %i' ),";
			$queryString = $queryString." JOURNAL.JOURNAL_NAME";
			
			if(count($tags) != 0)
				$queryString = $queryString." ,COUNT(JOURNALITEM.JOURNALITEM_ID) AS journalitemcount";
			
			$queryString = $queryString." FROM JOURNAL, JOURNALITEM, CATEGORYITEMCONNECTION";
			$queryString = $queryString." WHERE";
			$queryString = $queryString." JOURNAL.JOURNAL_ID = JOURNALITEM.JOURNALITEM_JOURNAL_ID";
			$queryString = $queryString." AND";
			$queryString = $queryString." (JOURNAL.JOURNAL_USER_ID=".$aUserId;
			$queryString = $queryString." OR JOURNALITEM.JOURNALITEM_ISPUBLIC=1";
			$queryString = $queryString." OR JOURNALITEM.JOURNALITEM_ISPUBLIC=2)";

			//add journals
			$numJournals = count($journals);
			foreach($journals as $value)
			{
				if($value == $journals[0])
				{
					$queryString = $queryString." AND";
					if($numJournals > 1)
						$queryString = $queryString." (";
				}
				else
					$queryString = $queryString." OR";
				$queryString = $queryString." JOURNAL.JOURNAL_ID=".$value;

				if($value == $journals[count($journals)-1] && $numJournals > 1)
					$queryString = $queryString.")";


			}

			//add tags

			foreach($tags as $value)
			{

				if($value==$tags[0])
				{
					$queryString = $queryString." AND";
					$queryString = $queryString." JOURNALITEM.JOURNALITEM_ID = CATEGORYITEMCONNECTION.CATEGORYITEMCONNECTION_ITEM_ID";
					$queryString = $queryString." AND";
					$queryString = $queryString." (CATEGORYITEMCONNECTION.CATEGORYITEMCONNECTION_CATEGORY_ID IN (";
				}
				else
				{
					$queryString = $queryString.",";
				}
				$queryString = $queryString.$value;

				if($value == $tags[count($tags)-1])
				{
					$queryString = $queryString."))";
					//$queryString = $queryString." GROUP BY JOURNALITEM.JOURNALITEM_ID HAVING COUNT (JOURNALITEM.JOURNALITEM_ID) = ".count($tags);
				}


			}




			//add freetext
			if(count($freetext) != 0){
				$queryString = $queryString." AND";
				$queryString = $queryString." MATCH(JOURNALITEM.JOURNALITEM_TEXT) AGAINST ('";
				foreach ($freetext as $value)
				{
					if($value != $freetext[0])
						$queryString = $queryString." ";
					$queryString = $queryString.$value;
				}
				$queryString = $queryString."' IN BOOLEAN MODE)";
			}
			//only add this if there are tags, otherwise will get no results


			if(count($tags) != 0)
			{
				//$queryString = $queryString." GROUP BY JOURNALITEM.JOURNALITEM_ID HAVING COUNT (JOURNALITEM.JOURNALITEM_ID) = ".count($tags);
				$queryString = $queryString." GROUP BY JOURNALITEM.JOURNALITEM_ID HAVING journalitemcount = ".count($tags);

			}

			$queryString = $queryString." ORDER BY JOURNALITEM.JOURNALITEM_ID DESC";


			//echo($queryString);

			return $queryString;



		}
		
		function insertInvite($aUserid, $aHash, $aAddress)
		{
			$queryString = "insert into USERINVITES (userinvites_userid, userinvites_hash, userinvites_invitee_mail)";
			$queryString = $queryString." values (".$aUserid.",'".$aHash."','".$aAddress."')";
			
			//echo($queryString);
			$this->sendQuery($queryString);
		}
		
		
		function removeInvite($aHash)
		{
			$queryString = "delete from USERINVITES where USERINVITES_HASH='".$aHash."'";
			$this->sendQuery($queryString);
		}
		
		
		function decrementInvites($aUserid)
		{
			$queryString = "update USER set user_num_invites = user_num_invites-1 where user_id=".$aUserid;
			$this->sendQuery($queryString);
		}
		/**/

		function insertNewUser($aFullName, $aEmail, $aUserName, $aPassword, $aFriendId)
		{
			$queryString = "insert into USER (user_full_name, user_user_name, user_password, user_email) ";
			$queryString = $queryString."values ('".$aFullName."','".$aUserName."','".$aPassword."','".$aEmail."')";
			$this->sendQuery($queryString);
			
			//get id of new user
			$queryString = "select user_id from USER where user_user_name='".$aUserName."'";
			$result = $this->sendQuery($queryString);
			$row = mysql_fetch_row($result);
			$newuserid = $row[0];
			
			//add friends
			$queryString = "insert into FRIENDS (friend_a,friend_b) values ('".$aFriendId."','".$newuserid."')";
			$this->sendQuery($queryString);
			
			$queryString = "insert into FRIENDS (friend_a,friend_b) values ('".$newuserid."','".$aFriendId."')";
			$this->sendQuery($queryString);
			
		}

		function removeUser($aUserId)
		{
			//delete from administrators

			//delete from moderators

			//delete from friends 

			//get list of all journal item ids

			//delete category-item connections

			//delete journal items

			//delete journals

			//delete categories? (if not used by anyone else)

			//delete used categories

			//delete from user

			
		}
		
		function isUserNameOk($aUserName, $aMailAdr)
		{
			$queryString = "select * from USER where user_user_name='".$aUserName."'";
			$resulta = $this->sendQuery($queryString);
                        $retval = (mysql_num_rows($resulta) == 0) ? 1 : 0;

                        $queryString = "select * from USER where user_email='".$aMailAdr."'";
			$resultb = $this->sendQuery($queryString);
                        $retval = $retval + ((mysql_num_rows($resultb) == 0) ? 2 : 0);

                        return $retval;

		}

		function insertImportJournalItem($aJournalId, $aText, $aDateTime)
		{
			//build query string
			$queryString = "INSERT INTO JOURNALITEM ";
			$queryString = $queryString."( JOURNALITEM_JOURNAL_ID ,";
			$queryString = $queryString."JOURNALITEM_DATE_CREATED , JOURNALITEM_DATE_MODIFIED ,";
			$queryString = $queryString."JOURNALITEM_TEXT , JOURNALITEM_ISPUBLIC ,";
			$queryString = $queryString."JOURNALITEM_ISAPPROVED )";
			$queryString = $queryString."VALUES (".$aJournalId.",'".$aDateTime."' , '".$aDateTime."' , '".$aText."', '0', '1')";
			//echo($aJournalId.", ".$aDateTime.", ".$aText); 
			$this->sendQuery($queryString);
		}

		//member variables
		var $mConnection;

	}
?>