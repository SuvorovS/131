<?php
  
if(isset($_POST['key1']) && isset($_POST['key2']) && isset($_POST['noteDate']) && isset($_POST['noteText']) && isset($_POST['patientId']) && isset($_POST['doctorId'])) {
  $noteDate = $_POST['noteDate'];
  $noteText = $_POST['noteText'];
  $patientId = $_POST['patientId'];
  $doctorId = $_POST['doctorId'];


  // //подключение к базе
  // // $link = mysqli_connect("localhost", "root", "", "klinica");
  // // mysqli_set_charset($link, "utf8");
  require_once(realpath('../DBlink.php'));
  // обработка массива админов и докторов key1 и заполнение поля
  $link->query("INSERT INTO `visits` (`pacient_id`) VALUES ('$patientId')"); // заведение поля для нового визита и выделение ему своего visit_id

  $maxIdQuerry = $link->query("SELECT `id` FROM `visits` ORDER BY `id` DESC LIMIT 1"); 
  $maxVisitId = mysqli_fetch_assoc($maxIdQuerry)['id'];
  

  // // обработка массива админов и докторов key1
    $proc; // процент доктора, понадобиться в цикле ниже
    $payment;
    $technik;
    // $dolg;
    for ($k=0; $k < count($_POST['key1']); $k++) { 
      if ($_POST['key1'][$k] !== '') { // проверка на пустое значение
    //     $id = $_POST['key1'][0]['value']; // id для для sql-запроса ниже
        $label = $_POST['key1'][$k]['label'];
        $value = $_POST['key1'][$k]['value'];
        
        $link->query("UPDATE `visits` SET `$label`= '$value' WHERE id = '$maxVisitId'");
        
        if ($label === 'payment') {$payment = $value;}
        else if ($label === 'technik') {$technik = $value;}
        // else if ($label === 'dolg') {$dolg = $value;}
      }
    }

    
    // поиск процента доктора
    $procQ = $link->query("SELECT `procent` FROM `doctors` WHERE `id` = '$doctorId'");
    $proc = mysqli_fetch_assoc($procQ)['procent'];

    $delta = $payment - $technik;
    if($delta > 0){
      $doctor_reward = $delta / 100 * $proc;
      $link->query("UPDATE `visits` SET `doctor_reward` = '$doctor_reward' WHERE `id` = '$maxVisitId'");
    }
    else {
      $doctor_reward = 0;
    }

    $link->query(" UPDATE `visits` SET `doctor_id` = '$doctorId' WHERE `id` = '$maxVisitId'"); // добавление id доктора
  

  

  // обработка массива работ их цен и количества key2  

  for ($i=0; $i < count($_POST['key2']); $i++) {

    if ($_POST['key2'][$i] !== '') {
          $price = $_POST['key2'][$i]['price'];
          $quantity = $_POST['key2'][$i]['quantity'];
          $therapy = $_POST['key2'][$i]['therapy'];
          
      // if ($_POST['key2'][$i]['type'] === 'therapy') { // проверка на пустое значение
        $link->query("INSERT INTO `used_therapy` (`therapy`, `priceTherapy`, `quantity`, `visit_id`) VALUES ('$therapy', '$price', '$quantity', '$maxVisitId')");
      // }
      
    }
  }   

  

  // обработка массива заметки  
    if ($noteDate !== '') {
      $link->query("INSERT INTO `notes` (`date`, `note`, `patient_id`) VALUES ('$noteDate', '$noteText', '$patientId')");
    }

                
  // занеснеие общего долга в пациента
    $dolg = 0;
    $patientDolgQuery = $link->query("SELECT `dolg` FROM `visits` WHERE `pacient_id` = '$patientId'");
    for ($i1=0; $i1 < mysqli_num_rows($patientDolgQuery); $i1++) { 
      $row = mysqli_fetch_assoc($patientDolgQuery);
      $dolg = $dolg + $row['dolg'];
    }
    $link->query("UPDATE `pacients` SET `dolg` = '$dolg' WHERE `id` = '$patientId'");


  echo json_encode($_POST);
  // mysqli_close($link);
    
  // exit; // останавливаем дальнейшее выполнение скрипта
}

?>