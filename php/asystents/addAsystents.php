<?php
    if(isset($_POST)) {
        $req = false; // изначально переменная для "ответа" - false
        $data = $_POST['data'];

    //подключение к базе
        // $link = mysqli_connect("localhost", "root", "", "klinica");
        // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
        $link->query("INSERT INTO `asystents` (`asystent`, `name`, `second_name`, `birthday`, `stavka`) VALUES ('$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]')" );
       
        echo json_encode($data);

        mysqli_close($link);
        $req = null;
        $data = null;
       
        exit; // останавливаем дальнейшее выполнение скрипта

    }
?>