{{-- -вывод заголовка страницы --}}

@php
    $myTitle = '';
    if (isset($content))
    {
        if (isset($content->identifiedobject->name))
        {
        // имя хранится в IO
        $myTitle = $content->identifiedobject->name;
        }
        else
        {
        // имя хранится в таблице этой же модели
        $myTitle = $content->name;
        }
    }
    else
    {
    // заголовок из свойства модели
    $myClass = "\App\\" . (Str::contains($modelName, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($modelName);
    $myTitle = ($myClass)::title1;
    }
    // ограничить по длине
    $myTitle = Str::title(substr($myTitle, 0, 50));
@endphp

{{-- секция тайтла страницы и мета-данных --}}
@section('title')
    {{ $myTitle }}
@endsection
