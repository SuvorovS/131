<?php //ПОЛУЧЕНЕ ДАННЫХ ПО ЗП АДМИНА ЗА ПЕРИОД
  if (isset($_POST['adminId']) && isset($_POST['from']) && isset($_POST['to'])) {
      
    $adminId = trim($_POST['adminId']);
    $from = trim($_POST['from']);
    $to = trim($_POST['to']);
    
    //подключение к базе
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
    require_once(realpath('../DBlink.php'));

    //запрос на записи рабочих дней
    $adminWorkingDaysQuery = $link->query("SELECT `date`, `stavka` FROM  `adminworkingdays` WHERE `admin_id` = '$adminId' AND `date` BETWEEN '$from' AND '$to' ORDER BY `date` ASC");

    $adminWorkingDays = [];
    $payment = 0;

    
    for($i = 0; $i < mysqli_num_rows($adminWorkingDaysQuery); $i++){
        $row = mysqli_fetch_assoc($adminWorkingDaysQuery);
        $workingDay = $row['date'];
        $stavka = $row['stavka'];
        $adminWorkingDays[$i] = $workingDay;
        $payment = $payment + $stavka;
    }
    $workingDaysCount = count(array_unique($adminWorkingDays));
        
    //запрос на дневную ставку админа
    // $stavkaQ = $link->query("SELECT `stavka` FROM  `admins` WHERE `id` = '$adminId'");
    // $row = mysqli_fetch_assoc($stavkaQ);
    // $stavka = $row['stavka'];
    
   



      //Заптос на авансы


        $avans_adminsQ = $link->query("SELECT `avans` , `date` FROM `salary_admins` WHERE `admin_id` = '$adminId' AND `date` BETWEEN '$from' AND '$to'");
        $avansArr = [];

        

       $allAvans = 0;
        for ($k=0; $k < mysqli_num_rows($avans_adminsQ); $k++) { 
          $row = mysqli_fetch_assoc($avans_adminsQ);
          $allAvans = $allAvans + $row['avans'];
          $avansArr[$k] = $row; //['avans'];
        }
        $response = [];
        $response['workingDaysCount'] = $workingDaysCount;
        $response['payment'] = $payment;
        $response['avans'] = $avansArr;

        echo json_encode($response); // $allAvans;
       







        $adminWorkingDaysQuery = null;
        $res = null;
        $adminWorkingDays = null;
        $response = null;

        mysqli_close($link);

  }
  else {
    echo json_encode('данные по AJAX не переданы');
  }        
?>