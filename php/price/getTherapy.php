<?php
    if(isset($_POST)) {
        $req = false; // изначально переменная для "ответа" - false
        //$patient_id = $_POST['patient_id'];

    //подключение к базе
        // $link = mysqli_connect("localhost", "root", "", "klinica");
        // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
    //доктора
        $therapyQuery = $link->query("SELECT * FROM `therapy`");
        $therapyArr = [];


    for ($i = 0; $i < mysqli_num_rows($therapyQuery); $i++){
        $therapyArr[$i] = mysqli_fetch_assoc($therapyQuery);
    }
        $req = $therapyArr;

        echo json_encode($req);

        mysqli_free_result($therapyQuery);
       
        mysqli_close($link);
        $therapyQuery = null;
        $therapyArr = null;
       
        exit; // останавливаем дальнейшее выполнение скрипта

    }
?>