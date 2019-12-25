<?php

require_once './private/init.php';

$predmet = new Predmet();

if (isset($_POST['id'])) {
    $element_id = intval($_POST['id']);
    $predmet->delete($element_id);
    //poruka za registraciju
    Session::msg('home', 'Uspesno ste obrisali smer!');
    //redirektuj
    Redirect::to('smer.php');
}