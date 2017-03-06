<?php
if(isset($_POST['asystent']) && isset($_POST['asistent_list_id']) && isset($_POST['date']) && isset($_POST['set'])) {
    $asystent = $_POST['asystent'];
    $date = $_POST['date'];
    $asistent_list_id = $_POST['asistent_list_id'];

    //подключение к базе
        require_once(realpath('../DBlink.php'));
        
        $stavkaQuerry = $link->query("SELECT `stavka` FROM `asystents` WHERE `id`='$asistent_list_id'");
        $stavka = mysqli_fetch_assoc($stavkaQuerry)['stavka'];

        $asystentQuerry = $link->query("SELECT `date` FROM `asystentworkingdays` WHERE `asystent_id` = '$asistent_list_id' AND `date` = '$date'");

        $count = mysqli_num_rows($asystentQuerry);
        if($count === 0){
            $link->query("INSERT INTO `asystentworkingdays` (`asystent`, `asystent_id`, `date`, `stavka`) VALUES ('$asystent', '$asistent_list_id', '$date', '$stavka')");
        }
        else{
            $link->query("DELETE FROM `asystentworkingdays` WHERE `asystent_id` = '$asistent_list_id' AND `date` = '$date'");
            $link->query("INSERT INTO `asystentworkingdays` (`asystent`, `asystent_id`, `date`, `stavka`) VALUES ('$asystent', '$asistent_list_id', '$date', '$stavka')");
        }
            

        // echo json_encode($asystentQuerry);
        echo json_encode($_POST);

        mysqli_close($link);
      
        exit; // останавливаем дальнейшее выполнение скрипта
}
else if( isset($_POST['get']) && isset($_POST['date'])){
    $date = $_POST['date'];
   
    // //подключение к базе
        $link = mysqli_connect("localhost", "root", "", "klinica");
        mysqli_set_charset($link, "utf8");

        //получение пароля

        $asystentQuerry = $link->query("SELECT * FROM `asystentworkingdays` WHERE `date` = '$date'");
        $count = mysqli_num_rows($asystentQuerry);
        $asystentArr = [];

        for ($i=0; $i < $count; $i++) { 
            $asystentArr[$i] = mysqli_fetch_assoc($asystentQuerry);
        }
        
            

        echo json_encode($asystentArr);

        mysqli_close($link);
      
        exit; // останавливаем дальнейшее выполнение скрипта
}
else if( isset($_POST['del']) && isset($_POST['id']) && isset($_POST['date'])){
    $date = $_POST['date'];
    $id = $_POST['id'];
   
    // //подключение к базе
        $link = mysqli_connect("localhost", "root", "", "klinica");
        mysqli_set_charset($link, "utf8");


        $link->query("DELETE FROM `asystentworkingdays` WHERE `date` = '$date' AND `id` = '$id'");
        
        
        echo json_encode($_POST);

        mysqli_close($link);
      
        exit; // останавливаем дальнейшее выполнение скрипта
}
?>