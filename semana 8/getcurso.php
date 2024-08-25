<?php

include "connect.inc.php";
include "curso.class.php";

if(isset($_POST['cod_curso'])){
    //var_dump($_POST);
    $cod_curso = $_POST['cod_curso'];
    $curso = new Curso($conn);
    $resa = $curso->readOne($cod_curso);
    $res = $resa[0];

    if ($res) {
        // Converta as datas para o formato datetime-local
        $res['datahora_ini'] = date('Y-m-d\TH:i', strtotime($res['datahora_ini']));
        $res['datahora_fim'] = date('Y-m-d\TH:i', strtotime($res['datahora_fim']));
    }
    echo json_encode($res);
}