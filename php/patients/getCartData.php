<?php
if(isset($_POST['patient_id'])){
    // $req = false; // изначально переменная для "ответа" - false

    $patient_id = $_POST['patient_id'];
    // $patient_id = 10;

       require_once(realpath('../DBlink.php'));
// пациенты
    $usersQuery = $link->query("SELECT * FROM `pacients` WHERE `id` = '$patient_id'");
    $userArr = mysqli_fetch_assoc($usersQuery); // массив с данными по пациенту (ФИО, телефон, ДР и тд.)
    $userId = $userArr['id']; // id пациента

//визиты
    $visitsQuery = $link->query("SELECT * FROM `visits` WHERE `pacient_id` = '$userId'"); // запрос на все визиты пациента по id
    $visitsArr = []; //массив визитов по одному пациенту
    $visitsId = [];  //массив ID-шек визитов по одному пациенту
    $visitsArrQuantity = mysqli_num_rows($visitsQuery); // количество визитов
    for ($i = 0; $i < $visitsArrQuantity; $i++){
        $visitsArr[$i] = mysqli_fetch_assoc($visitsQuery); // формарование массива визитов в $visitsArr
        $visitsId[$i] = $visitsArr[$i]['id']; // формарование массива id-шек в $visitsId
    }
//терапия
    $therapiesArr = []; 
    for ($i = 0; $i < count($visitsId); $i++) {
        $therapyRow = $link->query("SELECT * FROM `used_therapy` WHERE `visit_id` = '$visitsId[$i]'");
        for ($k = 0; $k < mysqli_num_rows($therapyRow); $k++){
            $therapiesArr[$i][$k] = mysqli_fetch_assoc($therapyRow);
        }
    }


// сборка общего массива
    for($j = 0; $j < count($visitsArr); $j++){
        $userArr['visits'][$j] = $visitsArr[$j];
        if (isset($therapiesArr[$j]) ) { // если есть массив иерапий
            
            for ($k = 0; $k < count($therapiesArr[$j]); $k++) {

                $userArr['visits'][$j]['therapy'][$k] = @$therapiesArr[$j][$k];
            }
        }
    }


    // if(mysqli_num_rows($usersQuery)> 1){
    //     echo json_encode('уточните запрос');
    // }
    // else if(mysqli_num_rows($usersQuery)== 0){
    //     echo 'такого нет, проверьте правильность ввода поискового запроса';
    // }
    // else {
        echo json_encode($userArr);
    // }/
        // echo "<pre>";
        // print_r($userArr);

    // mysqli_free_result($usersQuery);
    // mysqli_close($link);
    // $arr = $usersArr;
    exit; // останавливаем дальнейшее выполнение скрипта
}

?>