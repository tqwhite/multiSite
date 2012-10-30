<?php

if(count($_POST)<1){echo "\$_POST['json'] is EMPTY<BR/>\n\n"; exit;}

$transactionInfo=json_decode($_POST['transactionInfo']);
$productList=json_decode($_POST['productList']);

echo json_encode(
	array(
		'status'=>1,
		'message'=>'test worked',
		'data'=>array('token'=>$_POST['token'], 'echo'=>array(
			'transactionInfo'=>$transactionInfo,
			'productList'=>$productList,
			'token'=>$_POST['token']
		))
	)
);