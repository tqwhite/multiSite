<?php $destinationAdr='tq@justkidding.com'; $extraMessage='hello'; require_once($_SERVER['DOCUMENT_ROOT'].'/php/mailNotificationInclude.php');?>
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html>
<head>
	<META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=UTF-8'>

	<title>Template Page Title</title>

	<script type='text/javascript' src='http://code.jquery.com/jquery-1.6.1.min.js'></script>
	<!--link rel='stylesheet' type='text/css' href='css/main.css' /-->

	<style type='text/css'><!--

		body {color:orange;}

	--></style>

</head>
<body>

	Hello World

</body>

<script type='text/javascript'>
	/* <![CDATA[ */
	$(document).ready(function(){


		$('body').append("<p>Thanks for visiting!");


	});
	/* ]]> */
</script>

</html>