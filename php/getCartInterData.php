<?php
if(isset($_POST)) {
    $req = false; // изначально переменная для "ответа" - false
    //$patient_id = $_POST['patient_id'];

// //подключение к базе
//     $link = mysqli_connect("localhost", "root", "", "klinica");
//     mysqli_set_charset($link, "utf8");
require_once ('DBlink.php');
//доктора
    $doctorsQuery = $link->query("SELECT `doctor`, `id` FROM `doctors`");
    $doctorsArr = [];

for ($i = 0; $i < mysqli_num_rows($doctorsQuery); $i++){
    $doctorsArr[$i] = mysqli_fetch_assoc($doctorsQuery);
}

//Админы
$adminsQuery = $link->query("SELECT `admin` FROM `admins`");
$adminsArr = [];
for ($i = 0; $i < mysqli_num_rows($adminsQuery); $i++){
    $adminsArr[$i] = mysqli_fetch_assoc($adminsQuery)['admin'];
}

//Терапия
$therapiesQuery = $link->query("SELECT `therapy`, `price` FROM `therapy`");
$therapiesArr = [];
for ($i = 0; $i < mysqli_num_rows($therapiesQuery); $i++){
    $row = mysqli_fetch_assoc($therapiesQuery);
    $therapiesArr[$i]['therapy'] = $row['therapy'];
    $therapiesArr[$i]['price'] = $row['price'];
}







$arr['doctor'] = $doctorsArr;
$arr['admin'] = $adminsArr;
$arr['therapy'] = $therapiesArr;
// $arr['therapyPrice'] = $therapiesPriceArr;

//    echo '<pre>';
//print_r($arr);
//print_r($materialsArr);
    $req = $arr;
echo json_encode($req);


//    if(mysqli_num_rows($usersQuery)> 1){
//        echo json_encode('уточните запрос');
//    }
//    else if(mysqli_num_rows($usersQuery)== 0){
//        echo 'такого нет, проверьте правильность ввода поискового запроса';
//    }
//    else {
//        echo json_encode($usersArr);
//    }
//
    mysqli_free_result($doctorsQuery);
    // mysqli_free_result($materialsQuery);
    mysqli_free_result($therapiesQuery);
    mysqli_free_result($adminsQuery);
    mysqli_close($link);
    $doctorsArr = null;
    $adminsArr = null;
    // $materialsArr = null;
    $therapiesArr = null;

    exit; // останавливаем дальнейшее выполнение скрипта

}
?>