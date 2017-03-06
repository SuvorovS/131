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
   $link->query("UPDATE `doctors` SET `doctor` = '$data[1]',`name`='$data[2]',`second_name`='$data[3]',`birthday`='$data[4]', `procent`='$data[5]' WHERE `id`=$id"); // $data[0] это скрытое поле id из формы 
 
    

    $file = 'file.txt';
    // $current = $_POST['data'];
    $current = $data;
    // Пишем содержимое обратно в файл
    file_put_contents($file, $current);


    echo json_encode($_POST);
    mysqli_close($link);
   
    exit; // останавливаем дальнейшее выполнение скрипта

}
?>