<?php include("/customers/scribb.com/scribb.com/httpd.www/password_protect.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>GTD with GMail todolist generator</title>
<script type="text/javascript">
	function setFocus()
	{
		document.getElementById("todotext").focus()

	}
</script>

<link type="text/css" rel="stylesheet" href="test.css">
</head>
<body>

<div id="container">
<div id="header"><h1>GTD todolist generator</h1></div>

<div id="wrapper">
<div id="content">
	<br>
	<form action="test.php" method="POST">
		<input type="text" id="todotext" name="todotext" size="40">
		<input type="submit" value="Send">
	</form>

	<?php

		//send to mail account if not empty
		if(isset($_POST["todotext"])){
			$subject = $_POST["todotext"];
			$body = ".";
			$address="trondarild@gmail.com";

			$headers = "From: Trond Arild <trondarild@gmail.com>";
			mail ( $address, $subject, $body, $headers);

			echo("<br><strong>This was sent to your email:</strong><br>");
			echo($_POST["todotext"]);
		}


	?>

</div>
</div>
<div id="navigation">
<p> </p>

</div>
<div id="extra">
<p> </p>
</div>
<div id="footer"><p><center>(c) 2006 Scribb</center></p></div>
</div>
</body>
</html>