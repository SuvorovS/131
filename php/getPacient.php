<?php
if(isset($_POST)){
  $req = false; // изначально переменная для "ответа" - false
 // $name = $_POST['key'][1]['value'];
  $name = $_POST['name'];//$_POST['key'][1]['value'];
  $second_name = $_POST['second_name'];
  $surname = $_POST['surname'];



  // $link = mysqli_connect("localhost", "root", "", "klinica");
  // mysqli_set_charset($link, "utf8");
require_once ('DBlink.php');
//пациент
    $usersQuery = $link->query("SELECT * FROM `pacients` WHERE `name` = '$name' AND `second_name` = '$second_name' AND `surname` = '$surname'");
    $usersArr = mysqli_fetch_assoc($usersQuery);
    $usersId = $usersArr['id'];

//визиты
    $visitsQuery = $link->query("SELECT * FROM `visits` WHERE `pacient_id` = '$usersId'");
    $visitsArr = []; //массив визитов по одному пациенту
    $visitsId = [];  //массив ID-шек визитов по одному пациенту
    for ($i = 0; $i < mysqli_num_rows($visitsQuery); $i++){
        $visitsArr[$i] = mysqli_fetch_assoc($visitsQuery);
        $visitsId[$i] = $visitsArr[$i]['id'];
    }
//терапия
    $therapiesArr = [];
    for ($i1 = 0; $i1 < count($visitsId); $i1++) {
        $therapyRow = $link->query("SELECT * FROM `therapy` WHERE `visit_id` = '$visitsId[$i1]'");
        for ($i2 = 0; $i2 < mysqli_num_rows($therapyRow); $i2++){
            $therapiesArr[$i1][$i2] = mysqli_fetch_assoc($therapyRow);
        }
    }

//материалы
    $materialsArr = [];
    for ($i1 = 0; $i1 < count($visitsId); $i1++) {
        $materialsRow = $link->query("SELECT * FROM `used_materials` WHERE `visit_id` = '$visitsId[$i1]'");
        for ($i2 = 0; $i2 < mysqli_num_rows($materialsRow); $i2++){
            $materialsArr[$i1][$i2] = mysqli_fetch_assoc($materialsRow);
        }
    }

// сборка общего массива
    for($j = 0; $j < count($visitsArr); $j++){
        $usersArr['visits'][$j] = $visitsArr[$j];
        for ($k = 0; $k < count($therapiesArr[$j]); $k++) {
            $usersArr['visits'][$j]['терапия'.$k] = $therapiesArr[$j][$k];
        }
        for ($k = 0; $k < count($materialsArr[$j]); $k++) {
            $usersArr['visits'][$j]['материалы'.$k] = $materialsArr[$j][$k];
        }
    }
if(mysqli_num_rows($usersQuery)> 1){
    echo json_encode('уточните запрос');
}
else if(mysqli_num_rows($usersQuery)== 0){
    echo 'такого нет, проверьте правильность ввода поискового запроса';
}
else {
    echo json_encode($usersArr);
}

    mysqli_free_result($usersQuery);
    mysqli_close($link);
    $arr = $usersArr;
  exit; // останавливаем дальнейшее выполнение скрипта
}

?>