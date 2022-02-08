{{-- отладка запросов по API - админка --}}

{{-- содержимое --}}

{{-- средний зафмксированный столбец --}}
<div class="page-aside">

    <div class="page-aside-switch">
        <i class="icon wb-chevron-left" aria-hidden="true"></i>
        <i class="icon wb-chevron-right" aria-hidden="true"></i>
    </div>

    <div class="page-aside-inner page-aside-scroll">
        <div data-role="container">
            <div data-role="content">
                <section class="page-aside-section">
                    <h5 class="page-aside-title">Примеры запросов на временный хост [http://cim.topliner.ru/api/]</h5>

                    {{-- примеры запросов --}}
                    <div class="panel-body">
                        <strong>1) Парсинг json-файла (отделить фото от текста):</strong>
                        <br>
                        файл: "uploads/models/task/1/20200720_015847.json"
                        <button class="btn" onclick="myFunAjaxAPITest('parseJsonFile')">test</button>

                        <br>
                        <strong>2) Паспортизация ЛЭП (task, 1-й скрин):</strong>

                        <br>
                        POST: getTasks?user=2&type=1
                        <button class="btn" onclick="myFunAjaxAPITest('getTasks')">test</button>

                        <br><br>
                        <strong>3) Получить одну задачу Task (3-й скрин, сразу все обьекты в запросе):</strong>

                        <br>
                        POST: getTask?task=1&user=2
                        <button class="btn" onclick="myFunAjaxAPITest('getTask')">test</button>

                        <br>
                        <strong>Объекты внутри задачи Task:</strong>

                        <br>
                        - Point (точка, 4,5-й скрины):
                        <button class="btn" onclick="myFunAjaxAPITest('getPoint')">view</button>

                        <br>
                        - LineSegment (линия 701, шаг 1 из 6):
                        <button class="btn" onclick="myFunAjaxAPITest('getLineSegment', 701)">view</button>

                        <br>
                        - LineSegment (линия 702, шаг 1 из 6):
                        <button class="btn" onclick="myFunAjaxAPITest('getLineSegment', 702)">view</button>

                        <br>
                        - NewPoint (новая точка, шаг 2 из 6):
                        <button class="btn" onclick="myFunAjaxAPITest('getNewPoint')">view</button>

                        <br>
                        - Tower (опора, шаг 3 из 6):
                        <button class="btn" onclick="myFunAjaxAPITest('getTower')">view</button>

                        <br>
                        - TowerMounting (подвес опоры, шаги 4, 5 из 6):
                        <button class="btn" onclick="myFunAjaxAPITest('getTowerMounting')">view</button>

                        <br>
                        - PotrebitelPoint (ввод к потребителю, шаг 6 из 6):
                        <button class="btn" onclick="myFunAjaxAPITest('getPotrebitelPoint')">view</button>

                        <br>
                        - Схема-изображение фидера (последняя страница):
                        <button class="btn" onclick="myFunAjaxAPITest('getFile')">view</button>

                        <br>
                        <strong>4) Сохранение задачи Task:</strong>

                        <br><br>
                        POST: saveTask

                        <br><br>
                        ожидаемые параметры:
                        <br>
                        - task=1;
                        <br>
                        - user=9;
                        <br>
                        - file=myfile;
                        <br>
                        - hash=71f88be0a1c5e50dec8a6855419ad0ed;

                        <br><br>
                        <strong>5) Смена статуса задачи Task (например, когда взяли в работу):</strong>

                        <br>
                        POST: changeTaskStatus?task=1&status=10
                        <button class="btn" onclick="myFunAjaxAPITest('changeTaskStatus')">test</button>

                        <br>
                        <strong>6) Записать, когда задача Task началась (например, когда взяли в работу):</strong>

                        <br>
                        POST: changeTaskStart?task=2
                        <button class="btn" onclick="myFunAjaxAPITest('changeTaskStart')">test</button>

                        <br>
                        <strong>7) Записать, когда задача Task завершилась:</strong>

                        <br>
                        POST: changeTaskEnd?task=3
                        <button class="btn" onclick="myFunAjaxAPITest('changeTaskEnd')">test</button>

                        <br>
                        <strong>8) Получить список всех таблиц, имеющихся в БД:</strong>

                        <br>
                        GET: getTable
                        <button class="btn" onclick="myFunAjaxAPITest('getTable')">test</button>

                        <br>
                        <strong>9) Получить список всех таблиц, имеющие название:</strong>

                        <br>
                        GET: getTable?onlywithcomment=yes
                        <button class="btn" onclick="myFunAjaxAPITest('getTable', 1)">test</button>

                        <br>
                        <strong>10) Получить данные указанной таблицы:</strong>

                        <br>
                        GET: getTable?table=towermaterial
                        <button class="btn" onclick="myFunAjaxAPITest('getTable', 2)">test</button>

                        <br>
                        <strong>11) Получить данные одной строки по id из указанной таблицы:</strong>

                        <br>
                        GET: getTable?table=towermaterial&id=2
                        <button class="btn" onclick="myFunAjaxAPITest('getTable', 3)">test</button>

                    </div>

                </section>
            </div>
        </div>
    </div>
</div>

{{-- крайний правый столбец --}}
<div class="page-main">
    <div class="page-header">
        <h1 class="page-title">Результат выполнения запроса</h1>
    </div>
    <div class="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Ответ в JSON-формате:</h3>
            </div>
            <div class="panel-body">

                {{-- изображения --}}
                <div class="row">
                    <div class="otladka_zaprosov">
                        <img id="img1" src="">
                        <img id="img2" src="">
                    </div>
                </div>

                {{-- окно сообщений --}}
                <div class="row">
                    <div>
                    <pre id="messageBox">
                    </pre>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- область моих скриптов на странице --}}
@section('scripts')
    <script>

        {{-- функция AJAX для смены статуса и рекомендованного --}}
        function myFunAjaxAPITest(api, regim = null) {

            // всплывающая подсказка
            toastr.info('Запрос отправлен...');

            // другие переменные
            onlywithcomment = null;
            table = null;
            id = null;
            user = null;
            task = null;
            type = null;
            pointType = null;
            connectorId = null;
            hash = null;
            status = null;
            file = null;

            var img1 = '';
            var img2 = '';

            if (api == 'parseJsonFile') {
                file = 'uploads/models/task/1/20200720_015847.json';
            }

            if (api == 'getTasks') {
                img1 = '/public/uploads/instruction_api_prototip/001/001.png';
                user = 2;
                type = 1;
            }

            if (api == 'getTask') {
                img1 = '/public/uploads/instruction_api_prototip/001/003.png';
                task = 1;
                user = 5;
                pointType = 702;
                connectorId = 1;
            }

            if (api == 'changeTaskStatus') {
                img1 = '/public/uploads/instruction_api_prototip/001/003.png';
                task = 1;
                status = 10;
            }

            if (api == 'changeTaskStart') {
                img1 = '/public/uploads/instruction_api_prototip/001/003.png';
                task = 2;
            }

            if (api == 'changeTaskEnd') {
                img1 = '/public/uploads/instruction_api_prototip/001/003.png';
                task = 3;
            }

            if (api == 'getPoint') {
                img1 = '/public/uploads/instruction_api_prototip/001/005.jpg';
            }

            if (api == 'getLineSegment') {
                pointType = regim;
                switch (pointType) {
                    case 701:
                        img1 = '/public/uploads/instruction_api_prototip/001/006.jpg';
                        break;
                    case 702:
                        img1 = '/public/uploads/instruction_api_prototip/001/007.jpg';
                        break;
                }
            }

            if (api == 'getNewPoint') {
                img1 = '/public/uploads/instruction_api_prototip/001/008.jpg';
            }

            if (api == 'getTower') {
                img1 = '/public/uploads/instruction_api_prototip/001/009.jpg';
            }

            if (api == 'getTowerMounting') {
                img1 = '/public/uploads/instruction_api_prototip/001/010.jpg';
                img2 = '/public/uploads/instruction_api_prototip/001/011.jpg';
            }

            if (api == 'getPotrebitelPoint') {
                img1 = '/public/uploads/instruction_api_prototip/001/013.jpg';
            }

            if (api == 'getFile') {
                img1 = '/public/uploads/instruction_api_prototip/001/016.png';
            }

            if (api == 'getTable') {
                if (regim == 1) {
                    onlywithcomment = 'yes';
                }
                if (regim == 2) {
                    table = 'towermaterial';
                }
                if (regim == 3) {
                    table = 'towermaterial';
                    id = 2;
                }
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: 'apiQueries/ajaxApiTest',
                method: "POST",
                dataType: 'json',
                data: {
                    'api': api,
                    'onlywithcomment': onlywithcomment,
                    'table': table,
                    'id': id,
                    'user': user,
                    'task': task,
                    'type': type,
                    'pointType': pointType,
                    'connectorId': connectorId,
                    'hash': hash,
                    'status': status,
                    'file': file,
                }
            }).done(function (result) {
                // нужно для отладки
                console.log(result);
                //result = JSON.stringify(result);
                //result = JSON.parse(result);
                // записать полученный результат в поле
                $('#messageBox').empty();
                $('#messageBox').html(result);
                // изображения
                $('#img1').attr('src', img1);
                $('#img2').attr('src', img2);
                // всплывающая подсказка
                toastr.success('Запрос выполнен...');
            });
        }

    </script>
@endsection