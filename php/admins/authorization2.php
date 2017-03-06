<?php
if(isset($_POST['authorization2'])) {
    $adminId = $_POST['authorization2'];
    
    // //подключение к базе
        // $link = mysqli_connect("localhost", "root", "", "klinica");
        // mysqli_set_charset($link, "utf8");
    require_once(realpath('../DBlink.php'));

    //получение пароля
        $adminsQuery = $link->query("SELECT `pass` FROM `admins` WHERE `id`='$adminId'");
        $pass = mysqli_fetch_assoc($adminsQuery)['pass'];
               
        echo json_encode($pass);

        mysqli_free_result($adminsQuery);
           
        mysqli_close($link);
  
        
        exit; // останавливаем дальнейшее выполнение скрипта
    }


?>