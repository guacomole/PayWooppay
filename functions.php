<?php

function debug($arr)
{
    echo '<pre>' . print_r($arr, true) . '</pre>';
}

function debugErrors($arr)
{
    foreach($arr as $key => $arrErrors){
        foreach($arrErrors as $key => $error) {
            echo '<p style="color: red"><em>', $error, '</em><br></p>';
        }
    }
}



