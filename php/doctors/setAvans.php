<?php
  if (isset($_POST['doctor_id']) && isset($_POST['doctor']) && isset($_POST['avans']) && isset($_POST['date'])) {
    $doctor_id = $_POST['doctor_id'];
    $doctor = $_POST['doctor'];
    $avans = $_POST['avans'];
    $date = $_POST['date'];
      
    //   //подключение к базе      
      // $link = mysqli_connect("localhost", "root", "", "klinica");
      // mysqli_set_charset($link, "utf8");
      require_once(realpath('../DBlink.php'));
             
      $link->query("INSERT INTO `salary_doctors` (`doctor_id`, `doctor`, `avans`, `date`) VALUES ('$doctor_id', '$doctor', '$avans', '$date')");



     
        // echo json_encode($response); // $allAvans;
        echo json_encode($_POST); // $allAvans;
        mysqli_close($link);
  }
  else {
    echo json_encode('данные по AJAX не переданы');
  }        
?>