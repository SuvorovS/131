<?php
if(isset($_POST['admin']) && isset($_POST['from']) && isset($_POST['to'])) {
    $admin = trim($_POST['admin']);
    $from = trim($_POST['from']);
    $to = trim($_POST['to']);
    
    // //подключение к базе
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
    require_once(realpath('../DBlink.php'));

    //получение пароля
    $adminWorkingDaysQuery = $link->query("SELECT `date` FROM  `adminworkingdays` WHERE `admin` = '$admin' AND `date` BETWEEN '$from' AND '$to' ORDER BY `date` ASC");

    $adminWorkingDays = [];
    for($i = 0; $i < mysqli_num_rows($adminWorkingDaysQuery); $i++){
        $row = mysqli_fetch_assoc($adminWorkingDaysQuery);
        $workingDay = $row['date'];
        $adminWorkingDays[$i] = $workingDay;
    }
    $res = array_unique($adminWorkingDays);

    echo json_encode(count($res));

    $adminWorkingDaysQuery = null;
    $res = null;
    $adminWorkingDays = null;
    mysqli_close($link);
    


    exit; // останавливаем дальнейшее выполнение скрипта
}
?>