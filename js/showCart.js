var rd; // метаданные по докторам, админам, ценам, материалам и тд.
// функция отобрфжения карты пациента в модальном окне
function showCart() {
    $.ajax({
        url: 'php/patients/getCartData.php',
        type: 'POST',
        dataType: 'json',
        data: {patient_id: this.getAttribute('data-id'),},
        beforeSend: function () {},
        error: function (req, text, error) {
            console.log('Проблемма ' + text + error);
        },
        success: function (response) {
            visitsData = response;
            console.log(response);
            var modal = new Modal();
            changeBtn();
            resiveData();
            // saveBtn();
            patienChange();
            addVisit();
            slidingForms();
            checkOwer();
        }
    });
}

function Modal() {
    var element = d.createElement('div');
    element.className = ('modal');
    var show = function showVisits(){
        d.body.appendChild(element);
        element.innerHTML = vivodPatientData;
        renderVisits(visitsData);
        element.innerHTML += vivodVisits;
        renderPatient(visitsData);
        var inputPayment = d.querySelectorAll('input[data-name="payment"]');
        for (var i = 0; i<inputPayment.length; i++) {
            inputPayment[i].onkeyup = function () {
                console.log(this);
                // getDolg(this);
            }
        }
        var inputTechnik = d.querySelectorAll('input[data-name="technik"]');
        for (var i = 0; i<inputTechnik.length; i++) {
            inputTechnik[i].onkeyup = function () {
                console.log(this);
                checkPrise(this);
                getDolg(this);
            }
        }

    }
    var close = function () {
        d.getElementById('close').onclick = function () {
            var blinked = d.getElementsByClassName('blinked');
            console.log(blinked.length > 0);
            if(blinked.length > 0){
                alert('НЕ ВСЕ ВИЗИТЫ СОХРАНЕНЫ');
            }
            else {
                element.remove();
                var bbb = d.getElementById('allPac');
                console.log(bbb);
                bbb.onclick();
          }
        }
    }
    var init = function(){
        show();
        close();
    }
    init();
}
var vivodPatientData = '<h2>Пациент</h2><button id="close">close</button><form id="patientData">\
            <input hidden name="id">\
            <p><span class="inputLabel">Фамилия </span><input disabled type="text" value="Фамилия" name="surname"></p>\
            <p><span class="inputLabel">Имя </span><input disabled type="text" value="Имя" name="name"></span></p>\
            <p><span class="inputLabel">Отчество </span><input disabled type="text" value="Отчество" name="second_name"></p>\
            <p><span class="inputLabel">Дата рожд. </span><input disabled type="date" name="age"></p>\
            <p><span class="inputLabel">телефон </span><input disabled type="text" value="Телефон" name="phone"></p>\
            <p><span class="inputLabel">скидка </span><input disabled type="text" name="discount"></p>\
            <p><span class="inputLabel">аванс </span><input disabled type="text" name="avans"></p>\
            <p><span class="inputLabel">долг </span><input disabled type="text" name="dolg"></p>\
            <button id="patientDataChange">Изменить</button>\
            <button disabled id="patientDataSave">Сохранить</button>\
            </form>';
            //<p><span></span><textarea class="patientNote" name="patientNote">Заметка</textarea></p>\
var vivodVisits = '';

function renderPatient(Arr) {
    var userForm = d.getElementById('patientData');
    for (var i = 0; i < userForm.elements.length; i++) {
        if (userForm.elements[i].name !== 'change'){
            userForm.elements[i].value = Arr[userForm.elements[i].name];
        }
    }
}

function renderVisits(Arr) {
    vivodVisits = ''; //обнуление переменной вывода визитов

    if (Arr.visits !== undefined) {

        for (var i = 0; i < Arr.visits.length; i++) {
            vivodVisits += '<h3>Визит #' + (+i + 1) + '</h3><div class="visit_main_div sliding"><form>';   //style="display: none; border: 1px solid black"
            for(var key in Arr.visits[i]){
                if (typeof Arr.visits[i][key] !== 'object') {
                    
                    if(key === 'id'){
                        vivodVisits += '<p><input hidden type="text" data-name="'+key+ '" value=' + Arr.visits[i][key] + '></p>';
                    }
                    else if(key === 'admin'){
                        vivodVisits += '<p class="visitData"><span class="inputLabel">Админ</span><input disabled type="text" data-name="'+key+ '" value=' + Arr.visits[i][key] + '></p>';
                    }
                    else if(key === 'data'){
                        vivodVisits += '<p  class="visitData"><span class="inputLabel">Дата</span><input disabled type="date" data-name="'+key+ '" value="' + Arr.visits[i][key] + '"></p>';
                    }
                    else if(key === 'doctor'){
                        vivodVisits += '<p class="visitData"><span class="inputLabel">Доктор</span><input disabled type="text" data-name="'+key+ '" value=' + Arr.visits[i][key] + '></p>';
                    }
                    else if(key === 'dolg'){
                        vivodVisits += '<p class="visitData"><span class="inputLabel">Долг</span><input disabled type="text" data-name="'+key+ '" value=' + Arr.visits[i][key] + '></p>';
                    }
                    else if(key === 'total_Price'){
                        vivodVisits += '<p class="visitData"><span class="inputLabel">Общая сумма</span><input disabled type="text" data-name="'+key+ '" value=' + Arr.visits[i][key] + '></p>';
                    }
                    else if(key === 'doctor_cost'){
                        vivodVisits += '<p class="visitData"><span class="inputLabel">Сумма на клинику</span><input disabled type="text" data-name="'+key+ '" value=' + Arr.visits[i][key] + '></p>';
                    }
                    else if(key === 'technik'){
                        vivodVisits += '<p class="visitData"><span class="inputLabel">Сума на техника</span><input disabled type="text" data-name="'+key+ '" value=' + Arr.visits[i][key] + '></p>';
                    }
                    else if(key === 'payment'){
                        vivodVisits += '<p class="visitData"><span class="inputLabel">Уплата</span><input disabled type="text" data-name="'+key+ '" value=' + Arr.visits[i][key] + '></p>';
                    }
                }                
                else {
                    vivodVisits += '<div class="visitTherapyPanel">'; // отрисовка терапий

                    for(var key2 in Arr.visits[i][key]){
                        vivodVisits += '<p class="therapy">\
                                            <span class="price">' + Arr.visits[i][key][key2].priceTherapy + ' грн </span>\
                                            <span class="label">' + Arr.visits[i][key][key2].therapy + '</span>\
                                            <input class="therapy_count" type="text"  value="' + Arr.visits[i][key][key2].quantity+'">\
                                        </p>';
                    }
                    vivodVisits += '</div>';
                }
            }
            vivodVisits += '<p class="visitControls"><button class="change">change</button><button disabled class="save">save</button></p></form></div>';
        }
    }

    else {
        vivodVisits += '<h2>ВИЗИТОВ НЕТ</h2><br>';
    }
    vivodVisits += '<button id="addVisitBtn">добавить визит</button>';
}

function slidingForms () { //ф-я слайдинга окнон визитов, вызывается выше
    var h3 = d.getElementsByTagName('h3');
    for(var i = 0; i<h3.length; i++){
        h3[i].onclick = function(){
           $(this).next().slideToggle()

        }
    }
}