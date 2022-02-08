{{-- модальное окно --}}

<div class="modal fade modal-blue" id="modalMessage" aria-hidden="true" aria-labelledby="exampleModalInfo" role="dialog" tabindex="-1" style="width:98% !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            {{-- верхний хеадер окна --}}
            <div class="modal-header">
                <button type="button" class="close" style="margin-top: 0;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 id="modalMessageTitle" class="modal-title">Модальное окно</h4>
            </div>

            <div class="modal-body" style="background-color: #202346;">

                {{-- содержимое окна --}}
                <div id="modalMessageContent" style="color: #000;"></div>

            </div>

            {{-- нижний футер окна --}}
            <div class="modal-footer" style="background-color: #202346;">
                <button type="button" class="btn btn-info" data-dismiss="modal">Закрыть</button>
            </div>

        </div>
    </div>
</div>
