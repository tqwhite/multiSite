<?php
require_once(dirname(__FILE__) . '/../../../library/Q/Utils.php');


$transactionJson="{'purchaserName':'TQ White II','purchaserPhone':'708-763-0100','purchaserEmail':'tq@justkidding.com','totalPaid':'1029.09','taxPaid':'1.25'}";
$prodListJson="[{'prodCode':'prodA','quantity':'4'},{'prodCode':'prodB','quantity':'44'}]";

$data=array(
    'transactionInfo' => $transactionJson,
    'productList' => $prodLIstJson,
    'token' => '923c61c5c46c97442c15aa9527315b00'
);


\Q\Utils::dumpWeb($data, 'To Be Posted');

if (count($_POST)>0){
echo "<HR>";

echo "<div style='color:red;'>Data posted to: {$_POST['url']}</div>";


	$ch =curl_init($_POST['url']);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('hello'=>'goodbye'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	curl_close($ch);

	$postResult=json_decode($result, true);
\Q\Utils::dumpWeb($postResult, "Returned Data");
}
else{
	echo "<div style='font-size:10pt;margin:20px 20px 20px 0px;'>The default URL (index.php) hits TQ's test post receiver. The data it returns
	is valid for the store application.</div>";
}

$outString="
<form action='{$_SERVER['PHP_SELF']}' method='POST'>
<input type='text' name='url' value='http://store.tensigma.local/test/index.php' style='width:500px;'>
<input type='submit' name='post'>
</form>

";

echo $outString;