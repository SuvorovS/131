<?php //СОЗДАНИЕ АДМИНА
    if(isset($_POST['data'])) {
        $data = $_POST['data'];

    //подключение к базе
        // $link = mysqli_connect("localhost", "root", "", "klinica");
        // mysqli_set_charset($link, "utf8");
        require_once(realpath('../DBlink.php'));


        $link->query("INSERT INTO `admins` (`admin`, `name`, `second_name`, `birthday`, `stavka`, `pass`) VALUES ('$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]')" );
       
       


        echo json_encode($data[0]);

        mysqli_close($link);
  
        $data = null;
       
        exit; // останавливаем дальнейшее выполнение скрипта

    }
?>