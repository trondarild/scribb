//check new user

function checkUserForm()
{
	var responseText =""
	
	//check full name
	if(document.getElementById("fullname").value =="")
		responseText = responseText.concat( "Missing full name<br>")
	
	if(document.getElementById("mailaddress").value =="")
		responseText = responseText.concat( "Missing mail adress<br>")
		
	if(document.getElementById("username").value=="")
		responseText = responseText.concat( "Missing user name<br>")
		
	
	
	if(document.getElementById("password_a").value=="" && document.getElementById("password_b").value=="")
		responseText = responseText.concat( "Missing password<br>")
	else if(document.getElementById("password_a").value != document.getElementById("password_b").value)
		responseText = responseText.concat( "Passwords do not match <br>")
	//document.getElementById("response").innerHTML = responseText
	
	//do unique check on username..
	
	if(responseText == "")
		checkUniqueUserName(document.getElementById("username").value, document.getElementById("mailaddress").value)
	else
		document.getElementById("response").innerHTML = responseText
	
}

function checkUniqueUserName(username,email)
{
	var url = "checkuniqueusername.php?username=" + username + "&email=" + email
	xmlHttp=GetXmlHttpObject(checkUserNameResponse)
	xmlHttp.open("GET", url , true)
	xmlHttp.send(null)
}

function checkUserNameResponse()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
			var responsecode = xmlHttp.responseText
			//put 
			//document.getElementById("response").innerHTML = responsecode + " OK"
			if ( responsecode == "3")
			{
				
				document.getElementById("doaction").value = "add"
				
				//set values in hidden fields
				document.getElementById("fn").value = document.getElementById("fullname").value
				document.getElementById("em").value = document.getElementById("mailaddress").value
				document.getElementById("un").value = document.getElementById("username").value
				document.getElementById("pw").value = hex_md5(document.getElementById("password_a").value)
				
				//disable everything
				document.getElementById("fullname").disabled = "disabled"
				document.getElementById("mailaddress").disabled = "disabled"
				document.getElementById("username").disabled = "disabled"
				document.getElementById("password_a").disabled = "disabled"
				document.getElementById("password_b").disabled = "disabled"
				
				
				
				//send the form 
				document.getElementById("newuserform").submit()
				//document.getElementById("submitbtn").value = "OK register!"
				//document.getElementById("submitbtn").type = "submit"
				
				//document.getElementById("response").innerHTML = " OK"
			}
			else if (responsecode == "2")
			{
				document.getElementById("response").innerHTML = " User name occupied, please try another<br>"
			}
			else if (responsecode == "1")
			{
				document.getElementById("response").innerHTML = " Email address occupied, please try another<br>"
			}
			else if (responsecode == "0")
			{
				document.getElementById("response").innerHTML = " Email address and user name occupied, please try another<br>"
			}
			else 
			{
				document.getElementById("response").innerHTML = responsecode
			}
	}
}