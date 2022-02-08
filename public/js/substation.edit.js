//import Vue from 'vue';
let substationApp = null;
let equipmentApp = null;
let currentSubstationData = null;

function saveSubstationScheme(link) {
    if (link.href && currentSubstationData) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: link.href,
            data: JSON.stringify(currentSubstationData),
            dataType: 'json',
            processData: false, // не обрабатываем файлы
            contentType: 'text/json', // так jQuery скажет серверу что это строковой запрос
            method: "POST",
            success: function (result) {
                toastr.success('Данные сохранены...');
            },
            error: function (error) {
                // запрос прошел не успешно
                // всплывающая подсказка
                toastr.error('Ошибка при импорте данных...');
            }
        });
    }
    return false;
}

function clearSubstationScheme(link) {
    if (!confirm('Вы действительно хотите очистить текущую схему')) return false;
    if (link.href && currentSubstationData) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: link.href,
            contentType: 'text/json', // так jQuery скажет серверу что это строковой запрос
            method: "GET",
            success: function (result) {
                toastr.success('Данные схему удалены сохранены...');
                if (substationApp) {
                    substationApp.data = [];
                    equipmentApp.data = {};
                }
            },
            error: function (error, result) {
                alert(error);
                // запрос прошел не успешно
                // всплывающая подсказка
                toastr.error('Ошибка при импорте данных...');
            }
        });
    }
    return false;
}

function sendAndParseXsde(parseUrl, data) {
    // всплывающая подсказка
    toastr.info('Начался процесс импорта данных. Дождитесь, пожалуйста, его завершения...');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: parseUrl,
        data: data,
        dataType: 'json',
        processData: false, // не обрабатываем файлы
        contentType: false, // так jQuery скажет серверу что это строковой запрос
        method: "POST",
        success: function (result) {
            currentSubstationData = result;
            if (!substationApp) {
                $.get('/public/js/substation_cn.vue', function (template) {
                    substationApp = new Vue({
                            el: '#substactionData',
                            template: template,
                            data: {data: result.data},
                        }
                    );
                })
            } else {
                substationApp.data = result.data;
                equipmentApp.data = result.equipment;
            }
        },
        error: function (error) {
            // запрос прошел не успешно
            // всплывающая подсказка
            toastr.error('Ошибка при импорте данных...');
        }
    });
    return false;
}

function makeSubstationApp(template) {
    if (!substationApp) {

    }
}

$(document).ready(function () {
        $('#substationMainForm').show();
        $('#importUpload').change(
            function (ev) {

                let files = document.getElementById("importUpload").files;
                let ext = ((files[0].name).split('.').pop()).toUpperCase();

                if (ext !== 'XSDE') {
                    // всплывающая подсказка
                    toastr.error('Извините, данный формат не поддерживается! Используйте, пожалуйста, файлы с расширенинм [XSDE] ...');
                    // досрочный выход
                    return;
                }

                //console.log("Filename: " + files[0].name);
                //console.log("Type MIME: " + files[0].type);
                //console.log("Расширение через точку: " + (files[0].name).split('.').pop());
                //console.log("Size: " + files[0].size + " bytes");

                if (files.length > 0) {


                    // создание формы
                    let data = new FormData();
                    data.append("file", files[0]);

                    const parseUrl = window.location.href;
                    sendAndParseXsde(parseUrl, data);

                }
            }
        );
    }
);
