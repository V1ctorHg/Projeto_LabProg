<?php

include "connect.inc.php";
include "evento.class.php";

if(isset($_POST['cod_evento'])){
    //var_dump($_POST);
    $cod_evento = $_POST['cod_evento'];
    $evento = new Evento($conn);
    $resa = $evento->readOne($cod_evento);
    $res = $resa[0];

    if ($res) {
        // Converta as datas para o formato datetime-local
        $res['datahora_ini'] = date('Y-m-d\TH:i', strtotime($res['datahora_ini']));
        $res['datahora_fim'] = date('Y-m-d\TH:i', strtotime($res['datahora_fim']));
    }
    echo json_encode($res);
}