<?php
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

//pretvaranje praznog niza u prazan string
function convert($array) {
    return (count($array) === 0) ? "" : $array;
}