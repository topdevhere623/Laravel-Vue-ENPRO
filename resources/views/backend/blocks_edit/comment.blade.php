{{-- редактирование поля комментарий --}}

<div class="row">
    <div class="form-group col-md-12">
        <h4 class="example-title">Комментарий</h4>
        <input type="text" class="form-control"
               name="comment"
               value="{{ old('comment' , $content->comment) }}"
               placeholder="комментарий">
    </div>
</div>