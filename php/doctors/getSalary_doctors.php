<?php
  if (isset($_POST['doctor_id']) && isset($_POST['from']) && isset($_POST['to'])) {
      
    // $response = null;
    $doctor_id = $_POST['doctor_id'];
    $from = $_POST['from'];
    $to = $_POST['to'];
      
    //подключение к базе      
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
    require_once(realpath('../DBlink.php'));
             
    // формирование сумы денег зарабонатых на клинику
    $doctor_costQuery = $link->query("SELECT `payment` FROM `visits` WHERE `doctor_id` = '$doctor_id' AND `data` BETWEEN '$from' AND '$to'");
    $doctor_cost = 0;

    for ($i=0; $i < mysqli_num_rows($doctor_costQuery); $i++) { 
      $doctor_cost = $doctor_cost + mysqli_fetch_assoc($doctor_costQuery)['payment'];
    }

    // формирование сумы денег зарабонатых врачём
    $doctor_rewardQuery = $link->query("SELECT `doctor_reward` FROM `visits` WHERE `doctor_id` = '$doctor_id' AND `data` BETWEEN '$from' AND '$to'");
    $salary = 0;

    for ($i=0; $i < mysqli_num_rows($doctor_rewardQuery); $i++) { 
      $salary = $salary + mysqli_fetch_assoc($doctor_rewardQuery)['doctor_reward'];
    }

    // запрос на авансы и их даты 
    $avans_doctorsQ = $link->query("SELECT `avans`, `date` FROM `salary_doctors` WHERE  `doctor_id` = '$doctor_id' AND `date` BETWEEN '$from' AND '$to'");
    $count = mysqli_num_rows($avans_doctorsQ);
    $avansArr = [];

    // формирование массива авансов
    $allAvans = 0;
    for ($k=0; $k < $count; $k++) { 
      $row = mysqli_fetch_assoc($avans_doctorsQ);
      $allAvans = $allAvans + $row['avans'];
      $avansArr[$k] = $row; //['avans'];
    }

    $response['salary'] = $doctor_cost; // 
    $response['payment'] = $salary; // 
    $response['avans'] = $avansArr; // авансы

    echo json_encode($response);
  }
  else {
    echo json_encode('данные по AJAX не переданы');
  }        
?>