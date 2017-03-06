<?php
if(isset($_POST)) {
    $req = false; // изначально переменная для "ответа" - false
    //$patient_id = $_POST['patient_id'];

//подключение к базе
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
require_once ('DBlink.php');
//доктора
    $doctorsQuery = $link->query("SELECT `doctor` FROM `doctors`");
    $doctorsArr = [];

for ($i = 0; $i < mysqli_num_rows($doctorsQuery); $i++){
    $doctorsArr[$i] = mysqli_fetch_assoc($doctorsQuery)['doctor'];
}
//Админы
$adminsQuery = $link->query("SELECT `admin` FROM `admins`");
$adminsArr = [];
for ($i = 0; $i < mysqli_num_rows($adminsQuery); $i++){
    $adminsArr[$i] = mysqli_fetch_assoc($adminsQuery)['admin'];
}
$arr = []; // общий массив

$arr['doctor'] = $doctorsArr;
$arr['admin'] = $adminsArr;

//    echo '<pre>';
// print_r($arr);

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
    mysqli_free_result($adminsQuery);
    mysqli_close($link);
    $doctorsArr = null;
    $adminsArr = null;
    $materialsArr = null;
    $therapiesArr = null;

    exit; // останавливаем дальнейшее выполнение скрипта

}
?>