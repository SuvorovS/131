<?php
if(isset($_POST)) {
    $req = false; // изначально переменная для "ответа" - false
    $data = $_POST['data'];

//подключение к базе
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
    $link->query("INSERT INTO `doctors` (`doctor`, `name`, `second_name`, `birthday`, `procent`) VALUES ('$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]')" );
   

    $req = $data;

    echo json_encode($req);



    mysqli_close($link);
    
   
    exit; // останавливаем дальнейшее выполнение скрипта

}
?>