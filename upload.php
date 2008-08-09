
<html>
<body>
<?php
include("databasequery.php");


class FileImporter
{
	
	function FileImporter($queryObject)
	{
		//initialize date variables 
		$this->entrydate = date("Y-m-d");
		$this->entrytime = date("G:i:s");
		
		$this->entries = array();
		$this->queryObj = $queryObject;
	}

	function isDate($str)
	{
		return substr_count($str, ".") == 2 && strlen($str) <= 10;
	}

	function parseLine($buf, $linenum)
	{
		$buffer = trim($buf);
		//echo("buffer = ".$buffer);
		if($linenum==0)
			$this->journalname = $buffer;
		else
		{
			if($this->isDate($buffer))
			{
				//echo(" buffer is date ");
				//split string on .
				$compositeDate = explode('.',$buffer);
					
				$day = $compositeDate[0];
				$month = $compositeDate[1];
				$year = $compositeDate[2];
				//set current date, format = yyyy-mm-dd
				$this->entrydate = $year."-".$month."-".$day;
				//echo("date=".$this->entrydate." ");

			}
			else
			{
				$time = trim(substr($buffer, 0,5));
				//get first 5 chars and check if it is a time
				if(substr_count($time,":")==1)
				{
					//add a 0 if 
					if(strpos($time,":")==1)
						$time = "0".$time;

					$this->entrytime = $time.":00";
					//echo($this->entrytime."<br>");
					$this->text = substr($buffer,5);

				}
				else
					$this->text = $buffer;
				
				// add entry
				$tmp = array();
				$tmp["date"] = $this->entrydate;
				$tmp["time"] = $this->entrytime;
				$tmp["text"] = $this->text;
				$this->entries[] = $tmp;
				//echo("text=".$this->text." ");
			}
			//echo("<br>");
		}
		
	}

	function import($handle, $userid)
	{
		$linenum = 0;
		while (!feof($handle)) 
		{
			$buffer = fgets($handle, 4096);
			$this->parseLine($buffer, $linenum);		
			
			$linenum++;
		}
		fclose($handle);
		//create new journal
		if($this->journalname != "")
		{
   		//try to get journalid by name and userid
			$journalid = $this->queryObj->getJournalId($this->journalname, $userid);
			//create new journal
			if($journalid=="")
			{
   			$this->queryObj->createJournal($this->journalname, $userid);	
   			$journalid = $this->queryObj->getJournalId($this->journalname, $userid);
			}
   		echo("<br>journalid=".$journalid."<br>");
			if($journalid != "")
   		{
				foreach($this->entries as $entry)
				{

					$datetime = $entry["date"]." ".$entry["time"]; 
					//
					$this->queryObj->insertImportJournalItem($journalid, $entry["text"], $datetime);
				}
				echo("uploaded ".count($this->entries)." entries in journal ".$this->journalname."<br>");
			}
   	}
	}

var $entrydate;
var $entrytime;
var $journalname;
var $text;
var $entries;
var $queryObj;
}

$queryObj = new DatabaseQuery;

// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

$uploaddir = '';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
   echo "File is valid, and was successfully uploaded.\n";
} else {
   echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

//open the file and print the contents
$myFile = $uploadfile;
$handle = fopen($myFile, 'r');
$userid=1;
$importer = new FileImporter($queryObj);
if ($handle) {
	$importer->import($handle,$userid);   
}
print "</pre>";

/*
parser:
name of journal
<date>
<time>text

*/

?> 

<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="upload.php" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="userfile" type="file" />
    
    <input type="submit" value="Send File" />
</form>
</body>
</html>