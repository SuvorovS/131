<?php
	if (isset($_POST['searchFrom']) && isset($_POST['searchTo'])) {
		$searchFrom = $_POST['searchFrom'];
		$searchTo = $_POST['searchTo'];
		$resp = null;
		
		// $link = mysqli_connect("localhost", "root", "", "klinica");
		// mysqli_set_charset($link, "utf8");
		require_once(realpath('../DBlink.php'));

		$total_costs = 0;
		// затраты общие
		$costsQuery = $link->query("SELECT `coast` FROM `costs` WHERE `date` BETWEEN '$searchFrom' AND '$searchTo'");
		$count = mysqli_num_rows($costsQuery);
		$costs = 0;
		if($count !== 0){
			for ($i=0; $i <$count ; $i++) { 
				$row = mysqli_fetch_assoc($costsQuery);
				$total_costs = $total_costs + $row['coast'];
				$costs = $costs + $row['coast'];
			}
		}
		//затраты на техник
		$costs_technick_Query = $link->query("SELECT `technik` FROM `visits` WHERE `data` BETWEEN '$searchFrom' AND '$searchTo'");
		$count = mysqli_num_rows($costs_technick_Query);
		$costs_technick = 0;
		if($count !== 0){
			for ($i=0; $i < $count; $i++) { 
				$row = mysqli_fetch_assoc($costs_technick_Query);
				$total_costs = $total_costs + $row['technik'];
				$costs_technick = $costs_technick+$row['technik'];
			}
		}
		//затраты на банк
		$costs_bank_Query = $link->query("SELECT `coast` FROM `costs_bank` WHERE `date` BETWEEN '$searchFrom' AND '$searchTo'");
		$count = mysqli_num_rows($costs_bank_Query);
		$costs_bank = 0;
		if($count !== 0){
			for ($i=0; $i < $count; $i++) { 
				$row = mysqli_fetch_assoc($costs_bank_Query);
				$total_costs = $total_costs + $row['coast'];
				$costs_bank = $costs_bank + $row['coast'];
			}
		}
		// доходы
		$profitQuery = $link->query("SELECT `payment` FROM `visits` WHERE `data` BETWEEN '$searchFrom' AND '$searchTo'");
		$payment = 0;
		$count = mysqli_num_rows($profitQuery);
		if ($count !== 0) {
			for ($i=0; $i<$count; $i++) { 
				$row = mysqli_fetch_assoc($profitQuery);
				$payment += $row['payment'];
			}
		}
		// ЗАРПЛАТЫ
		
	    $total = 0; // общий котел зарплат
	    	    
	    //Доктора
	    $patients = [];
	    $doctorsQuerry = $link->query("SELECT `data`, `doctor`, `pacient_id`, `payment`, `technik`, `doctor_reward` FROM `visits` WHERE `data` BETWEEN '$searchFrom' AND '$searchTo'");

	    // $doctorsQuerry = $link->query("SELECT * FROM `visits` WHERE `data` BETWEEN '$searchFrom' AND '$searchTo'");
	    for ($i=0; $i < mysqli_num_rows($doctorsQuerry); $i++) {
	    	$row_doctors = mysqli_fetch_assoc($doctorsQuerry);
	    	$pacient_id = $row_doctors['pacient_id'];
	    	$doctor_Q = $link->query("SELECT `name`, `second_name`, `surname` FROM `pacients` WHERE `id` = '$pacient_id'");
	    	$row_patient = mysqli_fetch_assoc($doctor_Q);
	    	$patients_full_name = $row_patient['surname'] . ' ' . $row_patient['name'] . ' ' . $row_patient['second_name'];

	    	$total = $total + $row_doctors['doctor_reward'];
	    	$patients[$i] = $row_doctors;
	    	$patients[$i]['patients_full_name'] = $patients_full_name;

	    }

	    //АДМИНЫ

	    $adminsQuerry = $link->query("SELECT `stavka` FROM `adminworkingdays` WHERE `date` BETWEEN '$searchFrom' AND '$searchTo'");
	    $admins_count = mysqli_num_rows($adminsQuerry);
	    for ($i=0; $i < $admins_count; $i++) {
	    	$stavka = mysqli_fetch_assoc($adminsQuerry)['stavka'];
	    	$total = $total + $stavka;
        }
	    
	    //АСИСТЕНТЫ
	    $asystentsQuery = $link->query("SELECT `stavka` FROM `asystentworkingdays` WHERE `date` BETWEEN '$searchFrom' AND '$searchTo'");
	    $asystents_count = mysqli_num_rows($asystentsQuery);
	    for ($i=0; $i < $asystents_count; $i++) { 
	        $asystent_stavka = mysqli_fetch_assoc($asystentsQuery)['stavka'];
          	$total = $total + $asystent_stavka;
	    }
	
		$resp = [];

		$resp['total_costs'] = $total_costs;
		$resp['profit'] = $payment;
		$resp['costs_technick'] = $costs_technick;
		$resp['costs_bank'] = $costs_bank;
		$resp['costs'] = $costs;
		$resp['salary'] = $total;
		$resp['result'] = $payment - $total - $total_costs;
		$resp['patients'] = $patients;

		echo json_encode($resp);

		mysqli_free_result($costsQuery);
		mysqli_free_result($costs_technick_Query);
		mysqli_free_result($costs_bank_Query);
		mysqli_free_result($profitQuery);
		mysqli_free_result($doctorsQuerry);
		mysqli_free_result($adminsQuerry);
		mysqli_free_result($asystentsQuery);
		mysqli_close($link);
		exit; // останавливаем дальнейшее выполнение скрипта
	}
?>