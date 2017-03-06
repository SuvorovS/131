<?php //создание аванса
  if (isset($_POST['adminId']) && isset($_POST['avans']) && isset($_POST['date'])) {
    $adminId = $_POST['adminId'];
    $avans = $_POST['avans'];
    $date = $_POST['date'];
      
      //подключение к базе      
      // $link = mysqli_connect("localhost", "root", "", "klinica");
      // mysqli_set_charset($link, "utf8");
    require_once(realpath('../DBlink.php'));

      $link->query("INSERT INTO `salary_admins` (`admin_id`, `avans`, `date`) VALUES ('$adminId', '$avans', '$date')");

       
      echo json_encode($_POST); // $allAvans;
      mysqli_close($link);
  }
  else {
    echo json_encode('данные по AJAX не переданы');
  }        
?>