<?/*php require_once($_SERVER['DOCUMENT_ROOT'].'/tools/mailNotificationInclude.php');*/?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN'
   'http://www.w3.org/TR/html4/strict.dtd'>
<html>
<head>
<title>Template Page Title</title>

<script type='text/javascript' src='http://code.jquery.com/jquery-1.6.1.min.js'></script>
<!--link rel='stylesheet' type='text/css' href='css/main.css' /-->

<style type='text/css'>

	body {color:orange;}

</style>

</head>
<body>
	<?php
		$path=realpath('.');
		$server=$_SERVER['SERVER_NAME'];
		$ip=$_SERVER['SERVER_ADDR'];
		$file=__FILE__;
		echo "
		<div style='font-family:sans-serif;margin-top:50px;color:red;font-size:14px;margin-left:30px;font-style:italic;'>
		Welcome to <span style='font-size:125%;color:gray;font-style:normal;'>$server <span style='font-size:50%;'>($ip)</span></span>

		<div> Located at <span style='font-size:125%;color:gray;font-style:normal;'>$file</span></div>
		</div>
		";
	?>
</body>

<script type='text/javascript'>
/* <![CDATA[ */
$(document).ready(function(){


	$('body').append("<p>Thanks for visiting!");


});
/* ]]> */
</script>

</html>