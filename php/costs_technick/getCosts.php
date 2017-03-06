<?php
	if(isset($_POST['from']) && isset($_POST['to'])) {
	    $from = trim($_POST['from']);
	    $to = trim($_POST['to']);

	//подключение к базе
	require_once(realpath('../DBlink.php'));
	//затраты
	    $technik_cost_Query = $link->query("SELECT `data`, `pacient_id`, `technik` FROM `visits` WHERE `data` BETWEEN '$from' AND '$to'");
	    $costsArr = []; // массив затрат
	   
   	for ($i = 0; $i < mysqli_num_rows($technik_cost_Query); $i++){
   		$row = mysqli_fetch_assoc($technik_cost_Query);
   		$pacient_id = $row['pacient_id'];

   		$pacientQuerry = $link->query("SELECT `name`, `second_name`, `surname` FROM `pacients` WHERE `id` = '$pacient_id'");
   		
   		$pac_row = mysqli_fetch_assoc($pacientQuerry);

   		$pacient = $pac_row['surname'] . ' ' . $pac_row['name'] . ' ' . $pac_row['second_name'];
   	
   		// if ($row['technik'] !== '0') {
   			
	   		$costsArr[$i]['date'] =$row['data'];
	   		$costsArr[$i]['technik'] =$row['technik'];
	   		$costsArr[$i]['pacient'] =$pacient;
   		// }


   	}

	
	echo json_encode($costsArr);



	    mysqli_free_result($technik_cost_Query);
	   
	    mysqli_close($link);
	    $costsArr = null;
	   

	    exit; // останавливаем дальнейшее выполнение скрипта

	}
?>