'use strict'

function addPatientFunc() {
	//console.log(rd);
	new addNewPatient();
}




function addNewPatient () { // вывод формы для добавления нового пациента
	var newPatientForm = d.createElement('div');
	newPatientForm.className = ('modal');
    


    var show = function showVisits(){
        d.body.appendChild(newPatientForm);
        newPatientForm.innerHTML = '<h3>Новый пациент</h3><button id="close">close</button><form id="patientData">\
            <p><span class="inputLabel">Фамилия </span><input type="text" placeholder="Фамилия" data-name="surname"></p>\
            <p><span class="inputLabel">Имя </span><input type="text" placeholder="Имя" data-name="name"></span></p>\
            <p><span class="inputLabel">Отчество </span><input type="text" placeholder="Отчество" data-name="second_name"></p>\
            <p><span class="inputLabel">Дата рождения </span><input type="date" data-name="age"></p>\
            <p><span class="inputLabel">телефон </span><input type="text" placeholder="093-555-55-55" data-name="phone"></p>\
            <p><span class="inputLabel">% скидки </span><input type="text" placeholder="0" data-name="discount"></p>\
            <button id="patientDataSave">Сохранить</button>\
            </form>';
    }
    var close = function () {
        d.getElementById('close').onclick = function () {newPatientForm.remove();}
    }
    var save = saveNewPatient;

    var init = function(){
        show();
        close();
        save();
    }
    init();
}

function saveNewPatient () {
   	// обработка кнопки сохранения данных пациента
    var patientDataSave = d.getElementById('patientDataSave'); 
    patientDataSave.onclick = function (e) {
        e.preventDefault();
     	//console.log(this.parentNode.elements);   
        var parentNodeElements = this.parentNode.elements;
        var patientData = {}; // даные пацтента, собраные для отправки на сервер
        for (var k = 0; k<parentNodeElements.length; k++) { // сборка массива данных для отправки на сервер
            if(parentNodeElements[k].nodeName === 'INPUT'){
                if(parentNodeElements[k].value !== ''){
                    parentNodeElements[k].style.border = '4px solid green';
                    var name = parentNodeElements[k].getAttribute('data-name');
                    var value = parentNodeElements[k].value
                    patientData[name] = value;
                }
                else {
                    parentNodeElements[k].style.border = '4px solid red';
                    console.log('AAAAAA');
                    return
                }
            }
        }
        console.log(patientData);
        $.ajax({
            url: 'php/patients/changeVisit.php',
            type: 'POST',
            dataType: 'json',
            data: {'key5': patientData},
            beforeSend: function () {
                console.log('данные отправлены на сервер');
            },
            error: function (req, text, error) {
                console.log('Проблемма ' + text + error);
            },
            success: function (response) {
                console.log(response);
                alert('данные изменены');
                d.getElementsByClassName('modal')[0].remove(); // удаление модального окна
                var bbb = d.getElementById('allPac');
                bbb.onclick();
			}
        });
    }
}
