<?php
	if(isset($_POST['surname'])){
	  $req = false; // изначально переменная для "ответа" - false
	  $surname = trim(stripcslashes(strip_tags($_POST['surname']))); // очистка данных запроса

	    //$description = trim(stripcslashes(strip_tags($_POST['description'])));


	//     $link = mysqli_connect("localhost", "root", "", "klinica");
	// mysqli_set_charset($link, "utf8");
require_once ('DBlink.php');

	$q = $link->query("SELECT * FROM pacients WHERE `surname` LIKE '%$surname%' ORDER BY `surname` ASC");

	$rows = mysqli_num_rows($q);

	$arr = [];

	for ($i = 0 ; $i < $rows ; ++$i){
	    $arr[$i] = mysqli_fetch_assoc($q);;

	}

	  echo json_encode($arr); //$req =  $res; //'<p>Получили значение равное: <strong>' . $key . '</strong></p>'; // присваиваем переменной нужные данные
	   // возвращаем данные ответом, преобразовав в JSON-строку


	mysqli_free_result($q);
	$arr = null;
	mysqli_close($link);
	  exit; // останавливаем дальнейшее выполнение скрипта
	}
?>