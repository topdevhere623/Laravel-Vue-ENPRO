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
        @if(isset($id))
        <div class="col-6">
          <model-edit-allkindform-component model="{{ $model }}" id="{{ $id }}"></model-edit-allkindform-component>
        </div>
        @endif
        @if(!isset($id))
        <div class="col-6">
          <model-edit-allkindform-component model="{{ $model }}"></model-edit-allkindform-component>
        </div>
        @endif
    </div>
@endsection
