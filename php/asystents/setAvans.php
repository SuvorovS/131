<?php
  if (isset($_POST['asystentId']) && isset($_POST['asystent']) && isset($_POST['avans']) && isset($_POST['date'])) {
    $asystent = $_POST['asystent'];
    $asystentId = $_POST['asystentId'];
    $avans = trim($_POST['avans']);
    $date = trim($_POST['date']);
      
      //подключение к базе      
      // $link = mysqli_connect("localhost", "root", "", "klinica");
      // mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
      $link->query("INSERT INTO `salary_asystents` (`asystent`, `asystent_id`, `avans`, `date`) VALUES ('$asystent', '$asystentId', '$avans', '$date')");

      echo json_encode($_POST); // $allAvans;
      mysqli_close($link);
  }
  else {
    echo json_encode('данные по AJAX не переданы');
  }        
?>