<?php
if(isset($_POST['authorization1'])) {
    if($_POST['authorization1'] === 'step1'){

    //подключение к базе
        // $link = mysqli_connect("localhost", "root", "", "klinica");
        // mysqli_set_charset($link, "utf8");
        require_once(realpath('../DBlink.php'));

    // //админы
        $adminsQuery = $link->query("SELECT `admin`, `id` FROM `admins`");
        $adminsArr = []; // массив админов
       
        for ($i = 0; $i < mysqli_num_rows($adminsQuery); $i++){
            $adminsArr[$i] = mysqli_fetch_assoc($adminsQuery);
        }
        mysqli_free_result($adminsQuery);
           
        echo json_encode($adminsArr);
        mysqli_close($link);
        $adminsArr = null;
        exit; // останавливаем дальнейшее выполнение скрипта
    }
}

?>