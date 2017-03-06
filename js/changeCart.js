'use strict'

var y = new Date().getFullYear();
var m = + new Date().getMonth() +1;
var day = + new Date().getDate();
var curentData = y + '-' + ((m >=10) ? m : '0' + m) + '-' + ((day >=10) ? day : '0' + day); // дата сегодняшнего дня

// обработка кнопОК изменения данных пациента
function patienChange() {
    // обработка кнопки изменения данных пациента
    var patientDataChange = d.getElementById('patientDataChange'); 
    patientDataChange.onclick = function (e) {
        e.preventDefault();
        var parentNodeElements = this.parentNode.elements;
        for (var i = 0; i<parentNodeElements.length; i++) { // разблокировка полкй
            if (parentNodeElements[i].name !== 'dolg') {
                parentNodeElements[i].disabled = false;
            }
        }
    }
    // обработка кнопки сохранения данных пациента
    var patientDataSave = d.getElementById('patientDataSave'); 
    patientDataSave.onclick = function (e) {
        e.preventDefault();
        var parentNodeElements = this.parentNode.elements;
        var patientData = {}; // даные пацтента, собраные для отправки на сервер
        for (var k = 0; k<parentNodeElements.length; k++) { // сборка массива данных для отправки на сервер
            if(parentNodeElements[k].nodeName === 'INPUT'){
                if(parentNodeElements[k].value !== ''){
                    parentNodeElements[k].style.border = '4px solid green';
                    var name = parentNodeElements[k].name;
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
            data: {'key4': patientData},
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


// обработка кнопОК изменения данных визитОВ
var changeBtns = d.getElementsByClassName('change');

function changeBtn() {
    for (var a = 0; a<changeBtns.length; a++){
        changeBtns[a].addEventListener('click', function (e) { // 1-й этап: деактивация кнопки + мигающая кнопка 
            e.preventDefault();
            console.log('обработчик тут'); // метка для отслеживания где обработчик
            this.nextSibling.className = 'save blinked';
            var inputs = this.parentNode.parentNode.elements; // массив инпутов для обработки 
            makeSelect(inputs);
            this.disabled = true;
            (function getCurentDoctorId(){ //добавление атрибута data-doctorid при отрисовке визитов
                var selectDoctor = d.querySelectorAll('select[data-name="doctor"]'); //data-name="doctor"
                for (var i = 0; i < selectDoctor.length; i++) {
                    var elements = selectDoctor[i].childNodes;
                    var doctorId = getSelectedOption(elements).getAttribute('data-id');
                    console.log(doctorId);
                    function getSelectedOption(arr) {
                        for (var i = 0; i < arr.length; i++) {
                            if(arr[i].selected === true){
                                return arr[i];
                            }
                        }
                    };
                    selectDoctor[i].setAttribute('data-doctorid', doctorId);
                }
            })();

            getDoctorId();
            therapy_count_action();
            saveBtn(); // функция обработки кнопок сохранения изменений старого визита
        });
        changeBtns[a].addEventListener('click', function (e) { //2-й этап формарование и вывод панели с терапиями
            // e.preventDefault();
            var checkboxId = this.parentNode.parentNode.elements[0].value;
            console.log(checkboxId);
            var notePanel = d.createElement('div');
            notePanel.className = 'note';
            var mainDiv = this.parentNode.parentNode.parentNode;
            mainDiv.appendChild(notePanel);
            var form = this.parentNode.parentNode;
            form.className = 'visit_form';

            var checkboxVstavka = '';
            for (var a = 0; a < rd.therapy.length; a++) {
                checkboxVstavka += '<span><input data-price="'+ rd.therapy[a]['price'] +'" type="checkbox" id="'+checkboxId+ 'checkbox'+ a +'"><label for="'+checkboxId+'checkbox'+a+'">'+ rd.therapy[a]['therapy'] + '</label></span>';
            }
            var vstavka = '<div class="note_inputs">' + checkboxVstavka + '</div><div><button class="add">добавить к визиту</button></div>';
            notePanel.innerHTML = vstavka;
        });

        changeBtns[a].addEventListener('click', function (e) { //3-й этап подключение всех функция для работы панели с терапиями и изменения визита
            addTherapyToOldVisit();
            //обработка изменения поля ТЕХНИК
            var technikCost = d.querySelectorAll('input[data-name="technik"]');
            for (var i = 0; i < technikCost.length; i++) {
                technikCost[i].onkeyup = function () {
                    calc(this);
                }
            }
             
            //обработка изменения поля УПЛАТА
            var payment = d.querySelectorAll('input[data-name="payment"]');
            for (var i = 0; i < payment.length; i++) {
                payment[i].onkeyup = function () {
                    calc(this);
                }
            }

        });
    }
}

function addTherapyToOldVisit(){
    var addBtns = d.getElementsByClassName('add'); //
    for (var i = 0; i < addBtns.length; i++) {
        addBtns[i].onclick = function () {
            makeTherapiesToOldVisit(this);
        }
    }
}

function makeTherapiesToOldVisit (self) {
    var checkedLabels = []; // массив данных терапий для вывода в карточке визита
    var labels = self.parentNode.previousSibling.children; //  чекбоксы
    // console.log(labels);
    //формирование 
    for(var i = 0; i<labels.length; i++){
        if (labels[i].children[0].checked === true) {
            var price = labels[i].children[0].getAttribute('data-price'); //цена услуги, взятая из аттрибута
            var label = labels[i].children[1].innerHTML;
            checkedLabels[i] = {'price' : price, 'label' : label}; // console.log(rd.therapy[i]);
        }
    }
    var visitData = self.parentNode.parentNode.parentNode.children[0]; // форма со списком позиций визита: дата, админ, доктор и тд ! без списка всех терапий
    var visitDataList = visitData.children; // массив позиций визита: дата, админ, доктор и тд ! без списка всех терапий
    console.log(visitDataList);
    var visitTherapyPanel; // = getInput(visitDataList, 'class', 'visitTherapyPanel');
    for(var i=0; i<visitDataList.length; i++){
        if(visitDataList[i].className === 'visitTherapyPanel'){
            visitTherapyPanel = visitDataList[i];
        }
    }
    if (visitTherapyPanel === undefined) {
        visitTherapyPanel = d.createElement('div');
        visitTherapyPanel.className = 'visitTherapyPanel';
        var visitControls;
        for(var i=0; i<visitDataList.length; i++){
            if(visitDataList[i].className === 'visitControls'){
                visitControls = visitDataList[i];
            }
        }
        visitData.insertBefore(visitTherapyPanel, visitControls);
    }
    // console.log(visitTherapyPanel);


    var vstavkaNewVisitTherapyPanel = '';
    for (var i = 0; i < checkedLabels.length; i++) {
        if (checkedLabels[i] !== undefined) {
            vstavkaNewVisitTherapyPanel += '<p class="new_therapy"><span class="price">' + 
                                            checkedLabels[i].price + ': грн </span><span class="label">' + 
                                            checkedLabels[i].label + '</span>' +
                                            '<input class="therapy_count" type="text" value="1"></p>';
        }
    }
    visitTherapyPanel.innerHTML = vstavkaNewVisitTherapyPanel;
    console.log(visitTherapyPanel.children);
    
    checkAllPrise(visitTherapyPanel.children, visitDataList);
    therapy_count_action();
    checkOwer();
}

function makeSelect(list){ // добавление селектов с опциями
    for(var i = 0; i<list.length; i++){
        var parentElement = list[i].parentNode; //hjlbntkm 
        var options = '';
            if(list[i].getAttribute('data-name') === 'admin'){
                for (var k = 0; k<rd.admin.length; k++){
                    if(list[i].value === rd.admin[k]){
                        options += '<option selected>'+rd.admin[k]+'</option>'
                    }
                    else {
                        options += '<option>'+rd.admin[k]+'</option>'
                    }
                }                          
                var newElement = d.createElement('select');
                newElement.setAttribute('data-name', 'admin');
                newElement.innerHTML = options;
                list[i].remove();
                parentElement.appendChild(newElement);
            }
            if(list[i].getAttribute('data-name') === 'doctor'){
                for (var k = 0; k<rd.doctor.length; k++){
                    if(list[i].value === rd.doctor[k].doctor){
                        options += '<option data-id="'+rd.doctor[k].id+'" selected>'+rd.doctor[k].doctor+'</option>'
                    }
                    else {
                        options += '<option data-id="'+rd.doctor[k].id+'" >'+rd.doctor[k].doctor+'</option>'
                    }
                }                          
                var newElement = d.createElement('select');
                newElement.setAttribute('data-name', 'doctor');
                newElement.innerHTML = options;
                list[i].remove();
                parentElement.appendChild(newElement);
                newElement.addEventListener('click', function getId() {
                var elements = this.childNodes;
                var doctorId = getSelectedOption(elements).getAttribute('data-id');
                console.log(doctorId);
                function getSelectedOption(arr) {
                    for (var i = 0; i < arr.length; i++) {
                        if(arr[i].selected === true){
                            return arr[i];
                        }
                    }
                };
                var inputDocror_id = this.parentNode.nextSibling.childNodes[1];
                inputDocror_id.value = doctorId;
                console.log(inputDocror_id.value);
            });
        }

        else {
            if (list[i].getAttribute('data-name') !== 'doctor_cost' && list[i].getAttribute('data-name') !== 'total_Price' && list[i].getAttribute('data-name')!== 'dolg') {
                list[i].disabled = false;
            }
        }
    }
    
}

// функция запроса на метаданные по админам, докторам и терапиям
function resiveData() { //changeBtn();
    $.ajax({
        url: 'php/getCartInterData.php',
        type: 'POST',
        dataType: 'json',
        data: {key: '123'},
        beforeSend: function () {},
        error: function (req, text, error) {
            console.log('Проблемма ' + text + error);
        },
        success: function (response) {
         //   console.log(response);
            rd = response; //глобальная переменная с данными по докторам, админам, 
            // материалам, ценам и терапиям из sgowCart.js
            return rd;
        }
    });
}

// обработка кнопОК сохранения изменениЙ данных визитОВ
function saveBtn() {
    var saveBtns = d.getElementsByClassName('save');
    for (var i = 0; i < saveBtns.length; i++) {
        saveBtns[i].onclick = function (e) {
            e.preventDefault();
            



            var dataElems = this.parentNode.parentNode.children; // d.getElementsByClassName('newVisitData'); //элементы для массива а1
            var a1 = []; // это массив для чaсти данных: дата визита, админ, доктор 
            var a2 = []; // это массив для части данных: терапии и их цены
            for (var i = 0; i < dataElems.length; i++) {
                if (dataElems[i].className === 'visitData') {
                    var label = dataElems[i].children[1].getAttribute('data-name');
                    var value = dataElems[i].children[1].value;
                    a1[i] =  {'label' : label, 'value' : value}
                }
                else if(dataElems[i].className === 'visitTherapyPanel'){
                    if (dataElems[i].children.length !== 0) {
                        // console.log(dataElems[i].children);
                        for (var k = 0; k < dataElems[i].children.length; k++) {
                            var therapy = dataElems[i].children[k].children[1].innerHTML;
                            var price = parseFloat( dataElems[i].children[k].children[0].innerHTML);
                            var quantity = dataElems[i].children[k].children[2].value;

                            a2[k] = {'therapy' : therapy, 'price' : price, 'quantity' : quantity}
                        }

                    }
                    else {
                        console.log('no data');
                    }
                }
            }
            
            var patientId = d.querySelector('input[hidden]').value; // скрытое поле с id пациента стоис самым первым из скрытых полей, поэтому не querySelectorAll!
            // console.log(patientId);
            var doctorInput = getInput(dataElems, 'data-name', 'doctor');
            var doctorId = doctorInput.getAttribute('data-doctorid');
            // console.log(patientId);
           
            var visitId = dataElems[0].children[0].value;//.value;

            console.log(visitId);


            $.ajax({
                url: 'php/patients/changeVisit.php',
                type : 'POST',
                dataType : 'json',
                data : {'key1': a1, 'key2': a2, 'patientId' : patientId, 'doctorId' : doctorId, 'visitId' : visitId},
                beforeSend: function () {
                    console.log('данные отправлены на сервер');
                },
                error: function (req, text, error) {
                    console.log('Проблемма ' + text + error);
                },
                success: function (response) {
                    console.log(response);
                    alert('Визит измененн');
                    // d.getElementsByClassName('modalVisit')[0].remove(); // удаление модального окна записи визита
                    d.getElementsByClassName('modal')[0].remove(); // удаление модального окна пациента
                    var allPac = d.getElementById('allPac');
                    allPac.onclick();
                }
            });
        }
    }
}


//НОВЫЙ ВИЗИТ

// обработка кнопки добавления визита
function addVisit() {
    var addVisitBtn = d.getElementById('addVisitBtn');      
    addVisitBtn.onclick = function (e) {
        e.preventDefault();
        new addNewVisit();
    }
}


function addNewVisit () { // вывод формы для добавления нового визита
    var newVisitForm = d.createElement('div');
    newVisitForm.className = ('modalVisit');
    var height =  getComputedStyle(d.getElementsByClassName('modal')[0]).height  ;
    newVisitForm.style.height = height; // подгонка нового модального окна под размеры имеющегося
    d.body.scrollTop=0; //скрол на самый верх

    
    var show = function showVisits(){
        d.body.appendChild(newVisitForm);

        var vstavkaAdmins = '<option></option>'; // список опция для селекта админов
        for (var i = 0; i<rd.admin.length; i++) { // формарование списка опций 
            vstavkaAdmins += '<option>' + rd.admin[i] + '</option>';
        }
        var vstavkaDoctors = '<option></option>'; // список опция для селекта докторов
        for (var i = 0; i<rd.doctor.length; i++) { // формарование списка опций 
            vstavkaDoctors += '<option data-id="' + rd.doctor[i].id + '">' + rd.doctor[i].doctor + '</option>';
        }
        
        newVisitForm.innerHTML = '<h3>Новый ВИЗИТ</h3><button id="closeAddVisitForm">close</button>';
        var visit_main_div = d.createElement('div');
        visit_main_div.className = 'visit_main_div';

        visit_main_div.innerHTML = '<form id="modalVisitForm">\
                                    <h3>Новый визит</h3>\
                                    <p class="visitData">\
                                        <span class="inputLabel">Дата</span>\
                                        <input type="date" id="new_visit_data" data-name="data">\
                                    </p>\
                                    <p class="visitData">\
                                        <span class="inputLabel">Админ</span>\
                                        <select data-name="admin">'+ vstavkaAdmins+'</select>\
                                    </p>\
                                    <p class="visitData">\
                                        <span class="inputLabel">Доктор</span>\
                                        <select data-name="doctor">'+ vstavkaDoctors+ '</select>\
                                    </p>\
                                    <p class="visitData">\
                                        <span class="inputLabel">Долг</span>\
                                        <input disabled value="0" type="text" data-name="dolg">\
                                    </p>\
                                    <p class="visitData">\
                                        <span class="inputLabel">Общая сумма</span>\
                                        <input disabled type="text" value="0" data-name="total_Price">\
                                    </p>\
                                    <p class="visitData">\
                                        <span class="inputLabel">Cумма на клинику</span>\
                                        <input disabled type="text" value="0" data-name="doctor_cost">\
                                    </p>\
                                    <p class="visitData">\
                                        <span class="inputLabel sanpaket">Техник (санпакет)</span>\
                                        <input  type="text" data-name="technik" value="0">\
                                    </p>\
                                    <p class="visitData">\
                                        <span class="inputLabel">Уплата</span>\
                                        <input value="0" type="text" id="new_visit_payment" data-name="payment">\
                                    </p>\
                                    <div class="visitTherapyPanel"></div>\
                                    <p><button class="saveVisitBtn">save</button></p>\
                                </form>';
        visit_main_div.innerHTML += '<p class="next_visit">\
                                    <span class="inputLabel">Напоминание</span>\
                                    <input type="date" data-name="dateNote">\
                                    <textarea data-name="textNote">текст напоминания</textarea>\
                                  </p>';


        var checkboxVstavka = '';
        for (var a = 0; a < rd.therapy.length; a++) {
            checkboxVstavka += '<span><input data-price="'+ rd.therapy[a]['price'] +'" type="checkbox" id="checkbox'+ a +'"><label for="checkbox'+a+'">'+ rd.therapy[a]['therapy'] + '</label></span>';
        }
        
        var vstavkaBtns = '<button class="add">добавить к визиту</button>';
        visit_main_div.innerHTML +=   '<div class="note"><div class="note_inputs">' + checkboxVstavka+ '</div><div>' + vstavkaBtns+ '</div></div>';
            
        

        newVisitForm.appendChild(visit_main_div);


        //подтягивание свежей даты
        d.getElementById('new_visit_data').value = curentData

        //обработка кнопки добавления терапии
        addTherapy();

        //обработка изменения поля ТЕХНИК
        var technikCost = d.querySelectorAll('input[data-name="technik"]');
        for (var i = 0; i < technikCost.length; i++) {
            technikCost[i].onkeyup = function () {
                calc(this);
            }
        }
         
        //обработка изменения поля УПЛАТА
        var payment = d.querySelectorAll('input[data-name="payment"]');
        for (var i = 0; i < payment.length; i++) {
            payment[i].onkeyup = function () {
                calc(this);
            }
        }
        getDoctorId();
    }

    var close = function () {
        d.getElementById('closeAddVisitForm').onclick = function () {newVisitForm.remove();}
    }
    
    var init = function(){
        show();
        close();
        saveNewVisit();
    }
    init();
}
//конец showVisits

function addTherapy() {
    var addBtns = d.getElementsByClassName('add'); //
    for (var i = 0; i < addBtns.length; i++) {
        addBtns[i].onclick = function () {
            makeTherapies(this);
        }
    }
}

function calc (self){
    var list = self.parentNode.parentNode.children; // список инпутов визита
    var dolg = getInput(list, 'data-name', 'dolg');
    var dolgValue = +dolg.value;
    var total_Price = getInput(list, 'data-name', 'total_Price');
    var total_PriceValue = +total_Price.value;
    var payment = getInput(list, 'data-name', 'payment');
    var paymentValue = +payment.value;
    var technik = getInput(list, 'data-name', 'technik');
    var technikValue = +technik.value;
    var doctor_cost = getInput(list, 'data-name', 'doctor_cost');
    var doctor_costValue = +doctor_cost.value;
    var skidka = d.querySelector('input[name="discount"]').value;
    console.log(skidka);
    skidka = doctor_costValue / 100 * skidka
    console.log(skidka);

    total_Price.value = technikValue + doctor_costValue - skidka;
    dolg.value = +total_Price.value - payment.value;
    checkOwer();
}

function getInput(list, dataName, name){ //ф-я поиска html элемента 1) список элементов 2) название атрибута 3) имя атрибута
    for(var i=0; i<list.length; i++){
        if(list[i].className === 'visitData'){
            var input_name = list[i].children[1].getAttribute(dataName);
            if(input_name === name){
                return list[i].children[1]
            }
        }
    }
}

function getDoctorId() {
    var selectDoctor = d.querySelectorAll('select[data-name="doctor"]'); //data-name="doctor"
    for (var i = 0; i < selectDoctor.length; i++) {
        selectDoctor[i].onclick = function () {
            console.log(this);
            var elements = this.childNodes;
            var doctorId = getSelectedOption(elements).getAttribute('data-id');
            console.log(doctorId);
            function getSelectedOption(arr) {
                for (var i = 0; i < arr.length; i++) {
                    if(arr[i].selected === true){
                        return arr[i];
                    }
                }
            };
            this.setAttribute('data-doctorid', doctorId);
        }
    }
}



function makeTherapies (self) {
    var checkedLabels = []; // массив данных терапий для вывода в карточке визита
    var labels = self.parentNode.previousSibling.children; // отмеченые чекбоксы
    console.log(labels);
    //формирование 
    for(var i = 0; i<labels.length; i++){
        if (labels[i].children[0].checked === true) {
            var price = labels[i].children[0].getAttribute('data-price'); //цена услуги, взятая из аттрибута
            var label = labels[i].children[1].innerHTML;
            checkedLabels[i] = {'price' : price, 'label' : label}; // console.log(rd.therapy[i]);
        }
    }
    var visitDataList = self.parentNode.parentNode.parentNode.parentNode.children[2].children[0]; // список позиций нового визита: дата, админ, доктор и тд ! без списка всех терапий
    console.log(visitDataList);
    var newVisitTherapyPanel = visitDataList.children[9]; //9-я позиция в предыдущем списке и есть панель для заноса данных по терапиям
    var vstavkaNewVisitTherapyPanel = '';
    for (var i = 0; i < checkedLabels.length; i++) {
        if (checkedLabels[i] !== undefined) {
            vstavkaNewVisitTherapyPanel += '<p class="new_therapy"><span class="price">' + 
                                            checkedLabels[i].price + ': грн </span><span class="label">' + 
                                            checkedLabels[i].label + '</span>' +
                                            '<input class="therapy_count" type="text" value="1"></p>';
        }
    }
    newVisitTherapyPanel.innerHTML = vstavkaNewVisitTherapyPanel;
    
    checkAllPrise(newVisitTherapyPanel.children, visitDataList);
    therapy_count_action();
    // checkOwer();
}

function checkAllPrise (list, list2) { //list1 - список добавленных терапий; list2 - позиции визита на случай если все терапии удалили
    var total_cost = 0;
    for (var i = 0; i < list.length; i++){
        var price = parseFloat(list[i].children[0].innerHTML);
        var cuantity = +list[i].children[2].value;
        total_cost += (price * cuantity);
    }
    if (list[0]) {
        var visitDataList = list[0].parentNode.parentNode.children; // массив полей нового визита
        var doctor_cost = getInput(visitDataList, 'data-name', 'doctor_cost'); //поле доход на клинику
        doctor_cost.value = total_cost;
    }
    else {
        console.log(list2.children[6]);
        list2.children[6].children[1].value = 0; //обнуление поля "на клинику"
        list2.children[5].children[1].value = list2.children[7].children[1].value; //общая сумма = суммме на техника
        list2.children[4].children[1].value = list2.children[5].children[1].value - list2.children[8].children[1].value; //долг = 
        checkOwer();
    }
    var technik = +getInput(visitDataList, 'data-name', 'technik').value;
    var total_Price = getInput(visitDataList, 'data-name', 'total_Price');
    // console.log(technik);
    var skidka = d.querySelector('input[name="discount"]').value;
    console.log(skidka);
    skidka = total_cost / 100 * skidka;

    total_Price.value = total_cost - skidka + technik;
    var dolg = getInput(visitDataList, 'data-name', 'dolg');
    var payment = getInput(visitDataList, 'data-name', 'payment');
    dolg.value = total_Price.value - payment.value
}   



function therapy_count_action () { // обработка работы стрелок вверх\вниз на количестве терапий
    var therapy_counts = d.getElementsByClassName('therapy_count');
    for (var i = 0; i < therapy_counts.length; i++) {
        therapy_counts[i].onkeyup = function(e){
            var v = +this.value;
            if(v > 1){
                if(e.keyCode === 38){
                    v += 1;
                    this.value = v;
                }
                else if(e.keyCode === 40){
                    v -=1;
                    this.value = v;
                }
            }
            else if(v === 1){
                if(e.keyCode === 38){
                    v += 1;
                    this.value = v;
                }
            }
            //обработка измеения количества одного вида терапии
            var list = this.parentNode.parentNode.children;
            checkAllPrise(list);
            checkOwer();
        }
    }
}


function checkOwer() { //отслеживание баланса поля долга и его подсвечивание
    var dolg = d.querySelectorAll('input[data-name="dolg"]');
    // console.log(dolg);
    for (var i = 0; i < dolg.length; i++) {
        if(+dolg[i].value > 0){
            dolg[i].style.background = 'red';
            dolg[i].style.color = 'white';
            dolg[i].parentNode.parentNode.parentNode.previousSibling.style.background = '#d58787';
        }
        else if(+dolg[i].value === 0){
            dolg[i].style.background = 'white';
            dolg[i].style.color = 'black';
            dolg[i].parentNode.parentNode.parentNode.previousSibling.style.background = '#7ca5af';

        }
        else if(+dolg[i].value < 0){
            dolg[i].style.background = 'blue';
            dolg[i].style.color = 'white';
        }

    }
}


// сохранение данных нового визита
function saveNewVisit () {
    // обработка кнопки сохранения данных визита
    var saveVisitBtn = d.getElementsByClassName('saveVisitBtn'); 
    for (var i = 0; i < saveVisitBtn.length; i++) {
        saveVisitBtn[i].onclick = function (e) {
        e.preventDefault();
        // console.log(this);
        var dataElems = this.parentNode.parentNode.children; // d.getElementsByClassName('newVisitData'); //элементы для массива а1
        var a1 = []; // это массив для чaсти данных: дата визита, админ, доктор 
        var a2 = []; // это массив для части данных: терапии и их цены
        for (var i = 0; i < dataElems.length; i++) {
            if (dataElems[i].className === 'visitData') {
                var label = dataElems[i].children[1].getAttribute('data-name');
                var value = dataElems[i].children[1].value;
                a1[i] =  {'label' : label, 'value' : value}
            }
            else if(dataElems[i].className === 'visitTherapyPanel'){
                if (dataElems[i].children.length !== 0) {
                    // console.log(dataElems[i].children);
                    for (var k = 0; k < dataElems[i].children.length; k++) {
                        var therapy = dataElems[i].children[k].children[1].innerHTML;
                        var price = parseFloat( dataElems[i].children[k].children[0].innerHTML);
                        var quantity = dataElems[i].children[k].children[2].value;

                        a2[k] = {'therapy' : therapy, 'price' : price, 'quantity' : quantity}
                    }

                }
                else {
                    console.log('no data');
                }
            }
        }
        
        console.log(a1);
        console.log(a2);
        
        var patientId = d.querySelector('input[hidden]').value; // скрытое поле с id пациента стоис самым первым из скрытых полей, поэтому не querySelectorAll!
        
        var noteDate = this.parentNode.parentNode.nextSibling.children[1].value;
        var noteText = this.parentNode.parentNode.nextSibling.children[2].value;
        

            var doctorInput = getInput(dataElems, 'data-name', 'doctor');
            var doctorId = doctorInput.getAttribute('data-doctorid');
            

            if(doctorId === null){ //валидация поля доктор
                doctorInput.style.border = '4px solid red';
                console.log(doctorId);
                return
            }
            console.log(dataElems);


            $.ajax({
                url: 'php/patients/addVisit.php',
                type: 'POST',
                dataType: 'json',
                data: {'key1': a1, 'key2': a2, 'noteDate': noteDate, 'noteText': noteText, 'patientId' : patientId, 'doctorId' : doctorId},
                beforeSend: function () {
                    console.log('данные отправлены на сервер');
                },
                error: function (req, text, error) {
                    console.log('Проблемма ' + text + error);
                },
                success: function (response) {
                    console.log(response);
                    alert('Визит сохранен');
                    d.getElementsByClassName('modalVisit')[0].remove(); // удаление модального окна записи визита
                    d.getElementsByClassName('modal')[0].remove(); // удаление модального окна пациента
                    var allPac = d.getElementById('allPac');
                    allPac.onclick();
                }
            });
        }
    }
}

