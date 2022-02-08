{{-- подгружаемые скрипты --}}

<!-- Core  -->
<script src="/public/assets/backend/global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
<script src="/public/assets/backend/global/vendor/jquery/jquery.js"></script>
<script src="/public/assets/backend/global/vendor/popper-js/umd/popper.min.js"></script>
<script src="/public/assets/backend/global/vendor/bootstrap/bootstrap.js"></script>
<script src="/public/assets/backend/global/vendor/animsition/animsition.js"></script>
<script src="/public/assets/backend/global/vendor/mousewheel/jquery.mousewheel.js"></script>
<script src="/public/assets/backend/global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
<script src="/public/assets/backend/global/vendor/asscrollable/jquery-asScrollable.js"></script>
<script src="/public/assets/backend/global/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>
<script src="/public/assets/backend/global/vendor/waves/waves.js"></script>

<!-- Plugins -->
<script src="/public/assets/backend/global/vendor/switchery/switchery.js"></script>
<script src="/public/assets/backend/global/vendor/intro-js/intro.js"></script>
<script src="/public/assets/backend/global/vendor/screenfull/screenfull.js"></script>
<script src="/public/assets/backend/global/vendor/slidepanel/jquery-slidePanel.js"></script>

<script src="/public/assets/backend/global/vendor/peity/jquery.peity.min.js"></script>
<script src="/public/assets/backend/global/vendor/jquery-placeholder/jquery.placeholder.js"></script>

<script src="/public/assets/backend/libs/summernote/summernote-lite.js"></script>

<script src="/public/assets/backend/global/vendor/jquery-ui/jquery-ui.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-tmpl/tmpl.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-canvas-to-blob/canvas-to-blob.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-load-image/load-image.all.min.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-file-upload/jquery.fileupload.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-file-upload/jquery.fileupload-process.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-file-upload/jquery.fileupload-image.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-file-upload/jquery.fileupload-audio.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-file-upload/jquery.fileupload-video.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-file-upload/jquery.fileupload-validate.js"></script>
<script src="/public/assets/backend/global/vendor/blueimp-file-upload/jquery.fileupload-ui.js"></script>
<script src="/public/assets/backend/global/vendor/dropify/dropify.min.js"></script>
<script src="/public/assets/backend/global/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="/public/assets/backend/global/vendor/timepicker/jquery.timepicker.min.js"></script>
<script src="/public/assets/backend/global/vendor/datepair/datepair.min.js"></script>
<script src="/public/assets/backend/global/vendor/datepair/jquery.datepair.min.js"></script>
<script src="/public/assets/backend/global/vendor/formatter/jquery.formatter.js"></script>

<!-- Scripts -->
<script src="/public/assets/backend/global/js/Component.js"></script>
<script src="/public/assets/backend/global/js/Plugin.js"></script>
<script src="/public/assets/backend/global/js/Base.js"></script>
<script src="/public/assets/backend/global/js/Config.js"></script>

<script src="/public/assets/backend/js/Section/Menubar.js"></script>
<script src="/public/assets/backend/js/Section/GridMenu.js"></script>
<script src="/public/assets/backend/js/Section/Sidebar.js"></script>
<script src="/public/assets/backend/js/Section/PageAside.js"></script>
<script src="/public/assets/backend/js/Plugin/menu.js"></script>

<script src="/public/assets/backend/global/js/config/colors.js"></script>
<script src="/public/assets/backend/js/config/tour.js"></script>
<script>Config.set('assets', '/public/assets/common');</script>

<!-- Page -->
<script src="/public/assets/backend/js/Site.js"></script>
<script src="/public/assets/backend/global/js/Plugin/asscrollable.js"></script>
<script src="/public/assets/backend/global/js/Plugin/slidepanel.js"></script>
<script src="/public/assets/backend/global/js/Plugin/switchery.js"></script>

<script src="/public/assets/backend/global/js/Plugin/peity.js"></script>
<script src="/public/assets/backend/global/js/Plugin/asselectable.js"></script>
<script src="/public/assets/backend/global/js/Plugin/selectable.js"></script>
<script src="/public/assets/backend/global/js/Plugin/table.js"></script>
<script src="/public/assets/backend/global/js/Plugin/jquery-placeholder.js"></script>
<script src="/public/assets/backend/global/js/Plugin/input-group-file.js"></script>
<script src="/public/assets/backend/global/js/Plugin/bootstrap-datepicker.js"></script>
<script src="/public/assets/backend/global/js/Plugin/jt-timepicker.js"></script>
<script src="/public/assets/backend/global/js/Plugin/datepair.js"></script>
<script src="/public/assets/backend/global/js/Plugin/formatter.js"></script>

<script>
    (function (document, window, $) {
        'use strict';

        var Site = window.Site;
        $(document).ready(function () {
            Site.run();
        });
    })(document, window, jQuery);
</script>

{{-- подгружаемые скрипты общие --}}
<script type="text/javascript" src="/public/assets/common/libs/toastr/toastr.min.js"></script>
{{-- подгружаемые скрипты для бекенда --}}
<script type="text/javascript" src="/public/assets/backend/js/jquery.selectric.min.js"></script>
<script type="text/javascript" src="/public/assets/backend/js/myscript.js?<?php echo time(); ?>"></script>