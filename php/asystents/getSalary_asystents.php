<?php
  if (isset($_POST['asystentId']) && isset($_POST['from']) && isset($_POST['to'])) {
      
    $asystentId = $_POST['asystentId'];
    $from = $_POST['from'];
    $to = $_POST['to'];
    
    //подключение к базе
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
    //запрос на записи рабочих дней
    $asystentWorkingDaysQuery = $link->query("SELECT `date` FROM  `asystentworkingdays` WHERE `asystent_id` = '$asystentId' AND `date` BETWEEN '$from' AND '$to' ORDER BY `date` ASC");

    $asystentWorkingDays = [];
    for($i = 0; $i < mysqli_num_rows($asystentWorkingDaysQuery); $i++){
        $row = mysqli_fetch_assoc($asystentWorkingDaysQuery);
        $workingDay = $row['date'];
        $asystentWorkingDays[$i] = $workingDay;
    }
    
    $workingDaysCount = count(array_unique($asystentWorkingDays));
        
    //запрос на дневную ставку админа
    $stavkaQ = $link->query("SELECT `stavka` FROM  `asystents` WHERE `id` = '$asystentId'");
    $row = mysqli_fetch_assoc($stavkaQ);
    $stavka = $row['stavka'];
    
    $payment = $stavka * $workingDaysCount;
   
      //Запрос на авансы


        $avans_asystentsQ = $link->query("SELECT `avans` , `date` FROM `salary_asystents` WHERE `asystent_id` = '$asystentId' AND `date` BETWEEN '$from' AND '$to'");
        $avansArr = [];

        

       $allAvans = 0;
        for ($k=0; $k < mysqli_num_rows($avans_asystentsQ); $k++) { 
          $row = mysqli_fetch_assoc($avans_asystentsQ);
          $allAvans = $allAvans + $row['avans'];
          $avansArr[$k] = $row; //['avans'];
        }
        $response = [];
        $response['workingDaysCount'] = $workingDaysCount;
        $response['payment'] = $payment;
        $response['avans'] = $avansArr;

        echo json_encode($response); // $allAvans;
        // echo json_encode($_POST); // $allAvans;
        
        $asystentWorkingDaysQuery = null;
        $res = null;
        $adminWorkingDays = null;
        $response = null;

        mysqli_close($link);

  }
  else {
    echo json_encode('данные по AJAX не переданы');
  }        
?>