<?php
    $from = '2017-01-01';
    $to = '2017-02-05';
    
    // $link = mysqli_connect("localhost", "root", "", "klinica");
    // mysqli_set_charset($link, "utf8");
    

        require_once ('DBlink.php');


    echo "<pre>";
    $total = 0;
    //ДОКТОРА
    $doctorsQuerry = $link->query("SELECT `doctor`, `id`, `procent` FROM `doctors`");
    $doctors_count = mysqli_num_rows($doctorsQuerry);
    for ($i=0; $i < $doctors_count; $i++) { 
    	$row = mysqli_fetch_assoc($doctorsQuerry);
    	$doctor = $row['doctor']; 
        $doctor_id = $row['id'];
    	$procent = $row['procent'];
        echo '>> Доктор '.$doctor . ' ';
        echo $doctor_id;
        $totla_doctor_payment = 0;
        
        
        // поиск уплат в визитах по id доктора
        $visitsQuerry = $link->query("SELECT `payment` FROM `visits` WHERE `doctor_id`='$doctor_id' AND `data` BETWEEN '$from' AND '$to' ");
        $doctors_visits_count = mysqli_num_rows($visitsQuerry);
        if ($doctors_visits_count !== 0) {
            for ($ii=0; $ii < $doctors_visits_count; $ii++) { 
               $visits_payment = mysqli_fetch_assoc($visitsQuerry)['payment'];
               $doctor_payment = $visits_payment / 100 * $procent; 

            

                echo "<br>";
                echo "уплата в кассу " . $visits_payment. ", процент доктара" .$procent. "; процент доктору " . $doctor_payment;
                $totla_doctor_payment = $totla_doctor_payment + $doctor_payment;
                $total = $total+$doctor_payment; // добавление зарплат докторов в общий показатель
            }
        echo "<br>";
        echo "<br>";
        echo 'общая сума к выплате доктору '.$totla_doctor_payment;
        }
        else {
            echo "<br>";
            echo "визитов нету";
            echo "<br>";
            echo 'общая сума к выплате доктору '.$totla_doctor_payment;
        }

    echo "<br>";
    echo "<br>";
    }
    echo "<br>";
    echo "<br>";
    


    //АДМИНЫ

    $adminsQuerry = $link->query("SELECT `admin`, `id`, `stavka` FROM `admins`");
    $admins_count = mysqli_num_rows($adminsQuerry);
    for ($i=0; $i < $admins_count; $i++) { 
        $row = mysqli_fetch_assoc($adminsQuerry);
        $admin = $row['admin']; 
        $admin_id = $row['id'];
        $stavka = $row['stavka'];
        echo '>> Админ '.$admin . ' ';
        echo $admin_id;
        $totla_admin_payment = 0;
        
        
        // поиск рабочих смен админов
        $adminsChangeQuerry = $link->query("SELECT * FROM `adminWorkingDays` WHERE `admin_id`='$admin_id' AND `date` BETWEEN '$from' AND '$to' ");
        $visits_count = mysqli_num_rows($adminsChangeQuerry);
        if ($visits_count !== 0) {
            for ($ii=0; $ii < $visits_count; $ii++) { 
                $adminsWorkingChange_row = mysqli_fetch_assoc($adminsChangeQuerry);
                $adminWorkingDate =  $adminsWorkingChange_row['date'];
                echo "<br>";
                echo "рабочая смена " . $adminWorkingDate. ", ставка адина за день" .$stavka. "; за день админу " . $stavka;
                $totla_admin_payment = $totla_admin_payment + $stavka;
            }
        echo "<br>";
        echo "<br>";
        echo 'общая сума к выплате доктору '.$totla_admin_payment;
                $total = $total + $totla_admin_payment; // добавление зарплат админов в общий показатель
        }
        else {
            echo "<br>";
            echo "визитов нету";
            echo 'общая сума к выплате доктору '.$totla_admin_payment;
        }

    echo "<br>";
    echo "<br>";
    }
    echo "<br>";
    echo "<br>";


    //АСИСТЕНТЫ
    $asystentsQuery = $link->query("SELECT `asystent`, `id`, `stavka` FROM `asystents`");
    $asystents_count = mysqli_num_rows($asystentsQuery);
    for ($i=0; $i < $asystents_count; $i++) { 
        $row = mysqli_fetch_assoc($asystentsQuery);
        $asystent = $row['asystent']; 
        $asystent_id = $row['id'];
        $stavka = $row['stavka'];
        echo '>>  '.$asystent . ' ';
        echo $asystent_id;
        $totla_asystent_payment = 0;
        
        
        // поиск рабочих смен админов
        $adminsChangeQuerry = $link->query("SELECT * FROM `asystentWorkingDays` WHERE `asystent_id`='$asystent_id' AND `date` BETWEEN '$from' AND '$to' ");
        $visits_count = mysqli_num_rows($adminsChangeQuerry);
        if ($visits_count !== 0) {
            for ($ii=0; $ii < $visits_count; $ii++) { 
                $adminsWorkingChange_row = mysqli_fetch_assoc($adminsChangeQuerry);
                $adminWorkingDate =  $adminsWorkingChange_row['date'];
                echo "<br>";
                echo "рабочая смена " . $adminWorkingDate. ", ставка адина за день" .$stavka. "; за день админу " . $stavka;
                $totla_asystent_payment = $totla_asystent_payment + $stavka;
            }
        echo "<br>";
        echo "<br>";
        echo 'общая сума к выплате админу '.$totla_asystent_payment;
        $total = $total + $totla_asystent_payment;
        }
        else {
            echo "<br>";
            echo "визитов нету";
            echo 'общая сума к выплате админу '.$totla_asystent_payment;
        }

    echo "<br>";
    echo "<br>";
    }
    echo "<br>";
    echo "<br>";








    // $total = 
    // $totla_admin_payment;

    
    
    echo 'всего насчитано зарплат: '.$total;

?>