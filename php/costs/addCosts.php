<?php
	if(isset($_POST['data'])) {
	    //$req = false; // изначально переменная для "ответа" - false
	    $data = ($_POST['data']);


		//подключение к базе
	    // $link = mysqli_connect("localhost", "root", "", "klinica");
	    // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
		    //$qurent_date = date('Y-m-d');

		//добавить затрату
	    $link->query("INSERT INTO `costs`(`name`, `coast`, `date`) VALUES ('$data[0]', '$data[1]', '$data[2]')");

		echo json_encode($data); // отдача данных на клиент
		
	    mysqli_close($link);
	    exit; // останавливаем дальнейшее выполнение скрипта

	}
?>