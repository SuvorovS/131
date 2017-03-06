<?php
if(isset($_POST)) {
    $req = false; // изначально переменная для "ответа" - false
    //$patient_id = $_POST['patient_id'];

//подключение к базе
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
//доктора
    $doctorsQuery = $link->query("SELECT * FROM `doctors`");
    $doctorsArr = [];


for ($i = 0; $i < mysqli_num_rows($doctorsQuery); $i++){
    $doctorsArr[$i] = mysqli_fetch_assoc($doctorsQuery);
}
    $req = $doctorsArr;

    echo json_encode($req);

    mysqli_free_result($doctorsQuery);
   
    mysqli_close($link);
    $doctorsArr = null;
   
    exit; // останавливаем дальнейшее выполнение скрипта

}
?>