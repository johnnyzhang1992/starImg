<?php


function _star_asset($file){
    $_file = $file;


    if(str_contains($file, "http://") || str_contains($file, "https://")){
        return $file;
    }

    if (0 == strpos($_file, '/')) {
        $_file = substr($_file, 1);
    }
    return '//cdn.starimg.cn/' . $_file;

}