'use strict'
var d = document;
var patients =[];
var searchInput = d.getElementById('patientsSearch');

d.getElementById('allPac').onclick = function () {
    var searchValue = searchInput.value;
    resiveData();
    showPatients(searchValue, 'php/getAllPacients.php');
}

d.getElementById('owerPac').onclick = function () {
    var searchValue = searchInput.value;
    resiveData();
    showPatients(searchValue, 'php/getAllOwer.php')
}


function showPatients(searchInput, php_script) {
    $.ajax({
        url: php_script,
        type: 'POST',
        dataType: 'json',
        data: {surname: searchInput}
        ,
        beforeSend: function () {},
        error: function (req, text, error) {console.log('Проблемма ' + text + error);},
        success: function (response) {
            patients = response;
            var vivod = d.querySelector('#users ol');
            var vstavka = '';
            for (var i = 0; i < patients.length; i++) {
                if (patients[i]['dolg'] > '0') {
                    vstavka += '<li class="patient ower" data-id =' +  patients[i]['id'] +'>'+ patients[i]['surname']+' '+patients[i]['name']+' '+ patients[i]['second_name']+'</li>';
                }
                else {
                    vstavka += '<li class="patient" data-id =' +  patients[i]['id'] +'>'+ patients[i]['surname']+' '+patients[i]['name']+' '+ patients[i]['second_name']+'</li>';
                }
            }
            vstavka += '<p><button id="addPatient">Добавить пациента</button></p>'
            vivod.innerHTML = vstavka;
            var patients_list = d.getElementsByClassName('patient');
            for (var f = 0; f<patients_list.length; f++){
                patients_list[f].onclick = showCart;// функция вывода карточки пацтента из showCart.js
            }
            var addPatientBtn = d.getElementById('addPatient');
            addPatientBtn.onclick = addPatientFunc;
        }
    });
}







// $.ajax({
    //     url: 'php/getAllPacients.php',
    //     type: 'POST',
    //     dataType: 'json',
    //     data: {surname: d.querySelector('#searchPacient input[name="surname"').value}
    //     ,
    //     beforeSend: function () {
    //     },
    //     error: function (req, text, error) {
    //         console.log('Проблемма ' + text + error);
    //     },
    //     success: function (response) {
    //         patients = response;
    //         //console.log(patients);
    //         var vivod = d.querySelector('#users ol');
    //         var vstavka = '';
    //         for (var i = 0; i < patients.length; i++) {
    //             if (patients[i]['dolg'] > '0') {
    //                 vstavka += '<li class="patient owner" data-id =' +  patients[i]['id'] +'>'+ patients[i]['surname']+' '+patients[i]['name']+' '+ patients[i]['second_name']+'</li>';
    //             }
    //             else {
    //                 vstavka += '<li class="patient" data-id =' +  patients[i]['id'] +'>'+ patients[i]['surname']+' '+patients[i]['name']+' '+ patients[i]['second_name']+'</li>';
    //             }
    //         }
    //         vstavka += '<p><button id="addPatient">Добавить пациента</button></p>'
    //         vivod.innerHTML = vstavka;
    //         var patients_list = d.getElementsByClassName('patient');
    //         for (var f = 0; f<patients_list.length; f++){
    //             patients_list[f].onclick = showCart;// функция вывода карточки пацтента из showCart.js
    //         }
    //         var addPatientBtn = d.getElementById('addPatient');
    //         addPatientBtn.onclick = addPatientFunc;
    //     }
    // });