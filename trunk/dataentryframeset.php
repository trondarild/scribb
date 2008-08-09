
<html>

<head>
<title>Dataentry</title>
</head>

<frameset framespacing="0" border="0" rows="*,250" frameborder="1">

  <?php
  	echo("<frame name=\"journalentriesframe\" src=\"journalentries.php?journalid=".$journalid."\" target=\"dataentryframe\" scrolling=\"auto\">");
  	echo("<frame name=\"dataentryframe\" src=\"dataentry.php?journalid=".$journalid."\"  target=\"journalentriesframe\">");
  ?>
  <noframes>
  <body>

  <p>This page uses frames, but your browser doesn't support them.</p>

  </body>
  </noframes>
</frameset>

</html>
