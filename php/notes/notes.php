<?php
	if(isset($_POST['date']) && isset($_POST['birth_date'])){ //{
		$date = $_POST['date'];
		$birth_date = $_POST['birth_date'];

		 
		//Подключение к базе
	 //    $link = mysqli_connect("localhost", "root", "", "klinica");
		// mysqli_set_charset($link, "utf8");
require_once(realpath('../DBlink.php'));
		// поиск напоминаний
		$recordsQuerry = $link->query("SELECT * FROM `notes` WHERE `date` = '$date'");
		$count = mysqli_num_rows($recordsQuerry);
		$notesArr = [];
		
		for ($i = 0; $i < $count; $i++){
		    $row = mysqli_fetch_assoc($recordsQuerry);
		    $note = $row['note'];
		    $noteDate = $row['date'];
		    $patient_id = $row['patient_id'];
			$patientQuerry = $link->query("SELECT `name`, `second_name`, `surname`, `phone` FROM `pacients` WHERE `id` = '$patient_id'");
			

			$patientData = mysqli_fetch_assoc($patientQuerry);
			$phone = $patientData['phone'];
			$full_name = $patientData['surname'].' '.$name =  $patientData['name'].' '.$patientData['second_name'];
		    

		    $notesArr[$i]['note'] = $note;
		    $notesArr[$i]['full_name'] = $full_name;
		    $notesArr[$i]['phone'] = $phone;
		    $notesArr[$i]['noteDate'] = $noteDate;
		}


		//поиск дней рождений
		$birthdayQuerry = $link->query("SELECT `name`, `second_name`, `surname`, `phone` FROM `pacients` WHERE `age` LIKE '%$birth_date'");
		$count1 = mysqli_num_rows($birthdayQuerry);
										
		$birth_dateArr = [];

		for ($ii = 0; $ii < $count1; $ii++){
		    
		    $birth_date_Data = mysqli_fetch_assoc($birthdayQuerry);;
			
			$phone = $birth_date_Data['phone'];
			$full_name = $birth_date_Data['surname'].' '.$birth_date_Data['name'].' '.$birth_date_Data['second_name'];
		    

		    $birth_dateArr[$ii]['phone'] = $phone;
		    $birth_dateArr[$ii]['full_name'] = $full_name;
		}



		$resp = [];
		$resp['notes'] = $notesArr;
		$resp['birth_date'] = $birth_dateArr;



		  echo json_encode($resp); 


		// mysqli_free_result($q);
		// $arr = null;
		mysqli_close($link);
	  	exit; // останавливаем дальнейшее выполнение скрипта
	}
?>