{{-- редактирование статуса --}}

<div class="form-field">
    <h4 class="example-title">Статус</h4>
    <input type="checkbox" data-plugin="switchery" name="status" @if(!isset($content->status) or $content->status == 1) checked @endif />
</div>
