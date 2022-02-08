<?php
$myClass = "\App\\" . (Str::contains($model, 'Admin') ? 'AdminModels' : 'Models') . "\\" . ucfirst($model);
if(defined($myClass . '::title1' )) $myTitle = ($myClass)::title1;
else $myTitle = $myClass;
$myTitle = Str::title(substr($myTitle, 0, 50));
?>

{{-- редактирование --}}

{{-- лайоут --}}
@extends("backend.layouts.main")

{{-- секция контента --}}
@section("content")
    <div>
        <div class="page-header">
            <div class="page-title">
                {{ $myTitle }}
            </div>
        </div>
        <div class="col-6">
          <model-edit-enum-allkindform-component model="{{ $model }}" :id="{{ isset($id) ? $id : 0 }}" ></model-edit-enum-allkindform-component>
        </div>
    </div>
@endsection
