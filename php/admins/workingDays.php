<?php  // занесение рабочей смены админа
if(isset($_POST['admin']) && isset($_POST['adminId']) && isset($_POST['date'])) {
    $admin = $_POST['admin'];
    $adminId = $_POST['adminId'];
    $date = $_POST['date'];
    
    // //подключение к базе
        // $link = mysqli_connect("localhost", "root", "", "klinica");
        // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
    //получение размера ставки за день
        $stavkaQuerry = $link->query("SELECT `stavka` FROM `admins` WHERE `id` = '$adminId'");
        $stavka = mysqli_fetch_assoc($stavkaQuerry)['stavka'];

    //удаление всех записей рабочей смены у данного админа по этому дню
        $link->query("DELETE FROM `adminworkingdays` WHERE `admin_id` = '$adminId' AND `date` = '$date' ");

    //занесение информации о рабочей смене
        $link->query("INSERT INTO `adminworkingdays` (`admin`, `admin_id`, `date`, `stavka`) VALUES ('$admin', '$adminId', '$date', '$stavka')");


        // INSERT INTO `adminWorkingDays`(`id`, `admin`, `date`, `admin_id`) VALUES ([value-1],[value-2],[value-3],[value-4])
        

        echo json_encode($_POST);

        mysqli_close($link);
  
        exit; // останавливаем дальнейшее выполнение скрипта
    }
?>