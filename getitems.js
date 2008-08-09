var xmlHttp

function listItems(userid, type)
{ 
	//check what to do
	
	
	var url="getitems.php?userid="+userid+"&type="+type
	if(type=="journals")
	{
		if(document.getElementById("journallist").innerHTML=="")
		{
			xmlHttp=GetXmlHttpObject(listJournals)
			xmlHttp.open("GET", url , true)
			xmlHttp.send(null)
			
		}
		else
		{
			document.getElementById("journallist").innerHTML=""
			//change the character
			document.getElementById("journalexpander").innerHTML="+"
		}
			
	}
	else
	{
		if(document.getElementById("taglist").innerHTML=="")
		{
			xmlHttp=GetXmlHttpObject(listTags)
			xmlHttp.open("GET", url , true)
			xmlHttp.send(null)
		}
		else
		{
			document.getElementById("taglist").innerHTML=""
			//change the character
			document.getElementById("tagexpander").innerHTML="+"
		}
	}	
	
} 

function listJournals() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		//put 
		document.getElementById("journallist").innerHTML= xmlHttp.responseText 
		document.getElementById("journalexpander").innerHTML="-"
	} 
} 

function listTags()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		//put 
		document.getElementById("taglist").innerHTML=xmlHttp.responseText 
		document.getElementById("tagexpander").innerHTML="-"
	} 
}

function GetXmlHttpObject(handler)
{ 
	var objXmlHttp=null

	if (navigator.userAgent.indexOf("Opera")>=0)
	{
		alert("This example doesn't work in Opera") 
		return 
	}
	if (navigator.userAgent.indexOf("MSIE")>=0)
	{ 
		var strName="Msxml2.XMLHTTP"
		if (navigator.appVersion.indexOf("MSIE 5.5")>=0)
		{
			strName="Microsoft.XMLHTTP"
		} 
		try
		{ 
			objXmlHttp=new ActiveXObject(strName)
			objXmlHttp.onreadystatechange=handler 
			return objXmlHttp
		} 
		catch(e)
		{ 
			alert("Error. Scripting for ActiveX might be disabled") 
			return 
		} 
	} 
	if (navigator.userAgent.indexOf("Mozilla")>=0)
	{
		objXmlHttp=new XMLHttpRequest()
		objXmlHttp.onload=handler
		objXmlHttp.onerror=handler 
		return objXmlHttp
	}
} 