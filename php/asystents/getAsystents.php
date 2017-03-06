<?php
    if(isset($_POST)) {
        $req = false; // изначально переменная для "ответа" - false
        //$patient_id = $_POST['patient_id'];

    //подключение к базе
        // $link = mysqli_connect("localhost", "root", "", "klinica");
        // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
    //админы
        $asystentsQuery = $link->query("SELECT * FROM `asystents`");
        $asystentsArr = []; // массив админов
       
        for ($i = 0; $i < mysqli_num_rows($asystentsQuery); $i++){
            $asystentsArr[$i] = mysqli_fetch_assoc($asystentsQuery);
        }

        $req = $asystentsArr;

        echo json_encode($req);

        mysqli_free_result($asystentsQuery);
           
        mysqli_close($link);
        $asystentsArr = null;
           
        exit; // останавливаем дальнейшее выполнение скрипта

    }

?>