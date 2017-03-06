<?php

  // ЗАПРОС НА ИЗМЕНЕНИЕ ВСЕХ ПОЛЕЙ ВИЗИТА
  if(isset($_POST['key1']) && isset($_POST['key2']) && isset($_POST['patientId']) && isset($_POST['doctorId']) && isset($_POST['visitId'])  ) {
    $doctorId = $_POST['doctorId'];
    $patientId = $_POST['patientId'];
    $visitId = $_POST['visitId'];

    //подключение к базе
    require_once(realpath('../DBlink.php'));
    
  // обработка массива админов и докторов key1
    $proc; // процент доктора, понадобиться в цикле ниже
    $payment;
    $technik;
    // $dolg;
    for ($k=0; $k < count($_POST['key1']); $k++) { 
      if ($_POST['key1'][$k] !== '') { // проверка на пустое значение
        $label = $_POST['key1'][$k]['label'];
        $value = $_POST['key1'][$k]['value'];
        
        $link->query("UPDATE `visits` SET `$label`= '$value' WHERE id = '$visitId'");
        
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
      $link->query("UPDATE `visits` SET `doctor_reward` = '$doctor_reward' WHERE `id` = '$visitId'");
    }
    else {
      $doctor_reward = 0;
    }

    $link->query(" UPDATE `visits` SET `doctor_id` = '$doctorId' WHERE `id` = '$visitId'"); // добавление id доктора
  

  

  // обработка массива работ их цен и количества key2  

  $link->query("DELETE FROM `used_therapy` WHERE `visit_id` = '$visitId' ");

  for ($i=0; $i < count($_POST['key2']); $i++) {

    if ($_POST['key2'][$i] !== '') {
          $price = $_POST['key2'][$i]['price'];
          $quantity = $_POST['key2'][$i]['quantity'];
          $therapy = $_POST['key2'][$i]['therapy'];
          
        $link->query("INSERT INTO `used_therapy` (`therapy`, `priceTherapy`, `quantity`, `visit_id`) VALUES ('$therapy', '$price', '$quantity', '$visitId')");
    }
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
    mysqli_close($link);
    exit; // останавливаем дальнейшее выполнение скрипта
  }

//запрос на изменение данных ПАциента (!!не визита) key4

  else if(isset($_POST['key4'])) {
      $age = $_POST['key4']['age'];
      $discount = $_POST['key4']['discount'];
      $id = $_POST['key4']['id'];
      $name = $_POST['key4']['name'];
      $phone = $_POST['key4']['phone'];
      $second_name = $_POST['key4']['second_name'];
      $surname = $_POST['key4']['surname'];
      $avans = $_POST['key4']['avans'];
      

      $link = mysqli_connect("localhost", "root", "", "klinica");
      mysqli_set_charset($link, "utf8");



      $link->query("UPDATE `pacients` SET `age`= '$age', `discount`= '$discount', `name`= '$name', `phone`= '$phone', `second_name`= '$second_name', `surname`= '$surname', `avans`= '$avans'  WHERE id = '$id'");
        
          //Дебаг с внешним файлом
          // $file = 'file.txt';
          //$data .= $i. ')) ' .$name.' ' . $value.' '."\r\n" ;//['id'];
          // file_put_contents($file, $data);
          // file_put_contents($file, $_POST['key4']); //][0]['value']);


      echo json_encode($_POST);

      mysqli_close($link);
    
      exit; // останавливаем дальнейшее выполнение скрипта
  }


  // Добавление нового пациента (из js/addPatient.js)
  else if(isset($_POST['key5'])) {
      $age = $_POST['key5']['age'];
      $discount = $_POST['key5']['discount'];
      $name = $_POST['key5']['name'];
      $phone = $_POST['key5']['phone'];
      $second_name = $_POST['key5']['second_name'];
      $surname = $_POST['key5']['surname'];


      $link = mysqli_connect("localhost", "root", "", "klinica");
      mysqli_set_charset($link, "utf8");



      $link->query("INSERT INTO `pacients` (`name`, `second_name`, `surname`, `age`, `phone`, `discount`) VALUES ('$name', '$second_name', '$surname', '$age', '$phone', '$discount')");
        
        //Дебаг с внешним файлом
        // $file = 'file.txt';
        //$data .= $i. ')) ' .$name.' ' . $value.' '."\r\n" ;//['id'];
        // file_put_contents($file, $data);
        // file_put_contents($file, $_POST['key4']); //][0]['value']);

      echo json_encode($_POST);

      mysqli_close($link);
    
      exit; // останавливаем дальнейшее выполнение скрипта
  }
?>