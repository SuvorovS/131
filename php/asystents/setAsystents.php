<?php
if(isset($_POST)) {
// $req = false; // изначально переменная для "ответа" - false
    
    $id = $_POST['id'];
    $data = $_POST['data'];
   
//подключение к базе
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
//Изменение в базе
    $link->query("UPDATE `asystents` SET `asystent` = '$data[1]',`name`='$data[2]',`second_name`='$data[3]',`birthday`='$data[4]', `stavka`='$data[5]' WHERE `id`=$id");
 
    echo json_encode('ок');
    mysqli_close($link);

    $id = null;
    $data = null;
   
    exit; // останавливаем дальнейшее выполнение скрипта

}
?>