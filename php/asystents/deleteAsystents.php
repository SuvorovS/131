<?php
	if(isset($_POST)) {
	// $req = false; // изначально переменная для "ответа" - false
	    
	    $id = $_POST['id'];
	    
	//подключение к базе
	    // $link = mysqli_connect("localhost", "root", "", "klinica");
	    // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
	//Изменение в базе
	   $link->query("DELETE FROM `asystents` WHERE `id`=$id");
	 

	    echo json_encode($id);
	    mysqli_close($link);
	    $id = null;   
	    exit; // останавливаем дальнейшее выполнение скрипта

	}
?>