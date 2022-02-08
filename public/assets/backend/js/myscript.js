/* мои скрипты */

$(document).ready(function () {
    // после загрузки страницы

    // настройка всплывающего окна по-умолчанию
    toastr.options.closeButton = true; // крестик добавить
    toastr.options.timeOut = 1000; // время отображения

    // настройка summernote
    if ($('.summernote').length > 0) {
        $('.summernote').summernote({
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold']],
                ['para', ['ul', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]

                /*['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]*/
            ],
            styleTags: ['h2', 'h3', 'p'],
            callbacks: {

                onMediaDelete: function (target) {
                    var src = target[0].src;
                    console.log(src);
                }
            }
        });
    }

    $('.js-nav-trigger').on('click', function () {
        const $parent = $(this).parent('.js-nav-group');
        $parent.toggleClass('opened');
        $(this).next().stop(true).slideToggle()
    });

    const mapSize = function () {
        const footerHeight = $('.site-footer').height() || $('.dClipboard').height() || 0;
        const height = ($(window).height() - footerHeight - $('.map-auto-height').offset().top - 25);
        $('.map-auto-height').height(height)
    };

    if ($('.map-auto-height').length) {
        mapSize();
        window.onresize = mapSize;
    }

    const tableSize = function () {
        const footerHeight = $('.site-footer').height() || $('.legend').height() || 0;
        const tableFooterHeight = $('.table-bottom-bar').height() || 0;
        const searchBarHeight = $('.search-bar').height() || 0;
        const height = ($(window).height() - footerHeight - tableFooterHeight - searchBarHeight - $('.table-auto-height').offset().top - 25);
        $('.table-auto-height').css({maxHeight: height})
    };

    if ($('.table-auto-height').length) {
        tableSize();
        window.onresize = tableSize;
    }

    $('.selectric').selectric({
        arrowButtonMarkup: '<span class="icon icon-chevron arrow"></span>'
    });

    setInterval(function () {
        $('.selectric').each(function () {
            if (!$(this).parent().hasClass('selectric-hide-select') && !$(this).parent().hasClass('selectric-wrapper')) {
                $(this).selectric({
                    arrowButtonMarkup: '<span class="icon icon-chevron arrow"></span>'
                });
            }
        }, 2500)
    });

    //GALLERY UPLOAD
    const $fileInput = $('input[name="bGetFiles"]');
    $fileInput.attr('id', 'galleryUpload');
    $fileInput.parent().addClass('gallery-upload');
    $('.gallery-upload').append($('#dPlacemarkPhotos'));
});

// импорт таблицы
function myFunAjaxImportTable(model) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: 'ajaxImportTable',
        method: "POST",
        data: {
            "model": model,
        }
    }).done(function (result) {
        // нужно для отладки
        //console.log(result);
        var result = JSON.parse(result);
        console.log(result.dataFB);
        // записать полученный результат в div сообщения
        $('#messages').empty();
        $('#messages').html('Импорт данных завершен! <br/><br/>Поля таблицы в Firebird:<br/><br/>' + result.fieldsFB + '<br/><br/>Поля в MySQL:<br/><br/>' + result.fieldsMySQL + '<br/><br/>Используемые поля для импорта:<br/><br/>' + result.fieldsMySQLImport + '<br/><br/>Ошибки импорта: ' + result.hasError + '<br/><br/>Импортировано строк: ' + result.countStrInsert + ' из ' + result.countStrAll);
        // всплывающая подсказка
        //toastr.success('Импорт данных завершен...');
        // показать модальное окно
        $('#ModalMessage').modal();
    });
}

// очистка таблицы модели
function myFunAjaxClearTable(table) {
    if (!confirm('Вы уверены?')) return;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: 'ajaxClearTable',
        method: "POST",
        data: {
            "table": table,
        }
    }).done(function (result) {
        // нужно для отладки
        //console.log(result);
        // записать полученный результат в div сообщения
        $('#messages').empty();
        $('#messages').html('Таблица очищена!');

        // всплывающая подсказка
        toastr.success('Таблица успешно очищена...');
    });
}

// функция AJAX для смены статуса (а также hot, new)
function myFunAjaxChangeField(id, model, field = 'status') {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: 'ajaxChangeField',
        method: "POST",
        data: {
            "id": id,
            "model": model,
            "field": field,
        }
    }).done(function (result) {
        // нужно для отладки
        //console.log(result);
        // всплывающая подсказка
        toastr.success('Значение успешно изменено...');
    });
}

// функция AJAX очистка кеша Laravel
function myFunAjaxClearCache() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: 'ajaxClearCache',
        method: "POST",
        data: {}
    }).done(function (result) {
        // нужно для отладки
        //console.log(result);
        // всплывающая подсказка
        toastr.success('Кеш успешно очищен...');
    });
}
