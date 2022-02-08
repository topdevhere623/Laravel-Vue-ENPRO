<?php

function myGetImg($content)
{


    $img = base64_decode($content);
    header("Content-type: image/jpeg");
    echo "data:image/png;base64,".$img;

    //$img = $content;
    //echo "<img src='" . $img . "' width='200px'>";
}