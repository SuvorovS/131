<?php
	if(isset($_POST['from']) && isset($_POST['to'])) {
	    $req = false; // изначально переменная для "ответа" - false
	    $from = trim($_POST['from']);
	    $to = trim($_POST['to']);

	//подключение к базе
	    // $link = mysqli_connect("localhost", "root", "", "klinica");
	    // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
	//затраты
	    $costsQuery = $link->query("SELECT * FROM `costs_bank` WHERE `date` BETWEEN '$from' AND '$to'");
	    $costsArr = []; // массив затрат
	   
   	for ($i = 0; $i < mysqli_num_rows($costsQuery); $i++){
   		$costsArr[$i] = mysqli_fetch_assoc($costsQuery);
	}

	$req = $costsArr;

	echo json_encode($req);



	    // mysqli_free_result($adminsQuery);
	   
	    mysqli_close($link);
	    $costsArr = null;
	   

	    exit; // останавливаем дальнейшее выполнение скрипта

	}
?>