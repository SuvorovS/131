<?php
  	if (isset($_POST['date']) && isset($_POST['save'])) { //сохранение данных с клиента
      $date = $_POST['date'];
      
      //подключение к базе      
      	// $link = mysqli_connect("localhost", "root", "", "klinica");
      	// mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
		$records_Querry = $link->query("SELECT * FROM `records` WHERE `date`='$date'"); 
		if (mysqli_num_rows($records_Querry) !== 0) { // если есть записи на этот день, то удаляем всё
			$link->query("DELETE FROM `records` WHERE `date`='$date'");
		}
     

      	if (isset($_POST['records'])) {
      		$records = $_POST['records'];
	      	
	      	foreach ($records as $key => $value) {
				for ($i=0; $i < count($value); $i++) { 
					if ($value[$i]) {
						$label = $value[$i]['label'];
    					$phone = $value[$i]['phone'];
    					$time = $value[$i]['time'];
    					
						$link->query("INSERT INTO `records` (`doctor`, `date`, `label`, `phone`, `time` ) VALUES  ('$key', '$date', '$label', '$phone', '$time')");
	    			}
				}
			}
		}
		echo json_encode($_POST);
        mysqli_close($link);
	}
	else if(isset($_POST['resive']) && isset($_POST['date'])){ // отправка данных клиенту
		$date = $_POST['date'];
		
		//подключение к базе      
      	$link = mysqli_connect("localhost", "root", "", "klinica");
      	mysqli_set_charset($link, "utf8");

      	$records_Querry = $link->query("SELECT * FROM `records` WHERE `date`='$date'");

      	$arr = [];
      	
      		for ($i=0; $i < mysqli_num_rows($records_Querry); $i++) { 
				$row = mysqli_fetch_assoc($records_Querry);
				$arr[$i] = $row;
      		}


		echo json_encode($arr);
        mysqli_close($link);
	}
  	else {
    	echo json_encode('данные по AJAX не переданы');
  	}        
      	//Дебаг с внешним файлом
        // $file = 'file.txt';
        // $data ='';
		// $data .= $key. ' ' . $date.' '. $label.' '. $phone.' '. $time."\r\n" ;//['id'];
        // file_put_contents($file, $data);
      	
?>