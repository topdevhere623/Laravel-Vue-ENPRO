<style>

    /* --------------------------------------------------------------- */
    /* всплывающий балун у точек (марка опоры, фото опоры с другой линии и прочее) */
    .balloon_photo {
        height: 100px;
        max-width: 150px;
        border: solid 1px #ccc;
    }

    .modal_merge_other_photo {
        height: 75px;
        max-width: 100px;
        border: solid 1px #ccc;
        float: left;
        margin: 5px;
    }

    /* --------------------------------------------------------------- */
    /* множественный выбор */
    #dClipboard {
        margin: 10px 10px;
        font-size: 11px;
        font-style: italic;
        display: none;
    }

    /* --------------------------------------------------------------- */
    /* легенда под картой */
    .legend {
        margin: 5px 5px;
        font-size: 11px;
        font-style: italic;
    }

    .legend img {
        width: 15px;
        margin: 0 3px 0 0;
    }

    /* --------------------------------------------------------------- */
    /* галерея для фото точек */
    .thumb {
        -webkit-filter: grayscale(0);
        filter: none;
        border-radius: 5px;
        background-color: #fff;
        border: 1px solid #ddd;
        padding: 3px;
    }

    .thumb:hover {
        -webkit-filter: grayscale(1);
        filter: grayscale(1);
    }

    /* --------------------------------------------------------------- */
    /* кнопка применить у каждой строки */
    .b-apply {
        width: 50px !important;
        height: 63px !important;
        padding: 0;
    }

    /* --------------------------------------------------------------- */
    /* правый блок */
    .dRB {
        display: none;
        padding: 25px 0 15px 20px;
        background-color: #2F3136;
    }

    .dRB h3 {
        font-family: IBMPlexMono, Arial, sans-serif !important;
        font-style: normal;
        font-weight: 600;
        font-size: 18px;
        line-height: 23px;
        margin-bottom: 20px;
        padding-right: 40px;
    }

    /* --------------------------------------------------------------- */
    /* блок импорта в правой части */
    .dRBImport {
        padding: 0 30px 30px 0;
    }

</style>
