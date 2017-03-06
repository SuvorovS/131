<?php //ПОЛУЧЕНИЕ СПИСКА АДМИНОВ
    if(isset($_POST)) {
        $req = false; // изначально переменная для "ответа" - false
        //$patient_id = $_POST['patient_id'];

    //подключение к базе
        // $link = mysqli_connect("localhost", "root", "", "klinica");
        // mysqli_set_charset($link, "utf8");
        require_once(realpath('../DBlink.php'));

    //админы
        $adminsQuery = $link->query("SELECT * FROM `admins`");
        $adminsArr = []; // массив админов
       
       for ($i = 0; $i < mysqli_num_rows($adminsQuery); $i++){
        // $adminsArr[$i] = mysqli_fetch_assoc($adminsQuery)['doctor'];
        $adminsArr[$i] = mysqli_fetch_assoc($adminsQuery);
    }

        $req = $adminsArr;

        echo json_encode($req);

        mysqli_free_result($adminsQuery);
           
        mysqli_close($link);
        $adminsArr = null;
           
        exit; // останавливаем дальнейшее выполнение скрипта

    }

?>