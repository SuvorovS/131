<?php
	if(isset($_POST['id']) && isset($_POST['data'])) {
	    $id = $_POST['id'];
	    $data = $_POST['data'];
	   
	//подключение к базе
	    // $link = mysqli_connect("localhost", "root", "", "klinica");
	    // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
	//Изменение в базе
	    $link->query("UPDATE `costs_technick` SET `name`='$data[0]',`coast`='$data[1]',`date`='$data[2]' WHERE `id`=$id");
	 
		echo json_encode('ок');
		mysqli_close($link);

		$id = null;
	    $data = null;
	   
	    exit; // останавливаем дальнейшее выполнение скрипта

	}
?>