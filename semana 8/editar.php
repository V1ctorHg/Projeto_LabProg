<?php 
    include "connect.inc.php";
    include "estudante.class.php";
    include "organizador.class.php";

    
    $matricula = 0;
    $action = 'update';
    $actionVal = 'Atualizar';

    $estudante = new Estudante($conn);
    $organizador = new Organizador($conn);

    $resest = $estudante->readOne('-1');
    $resorg = $organizador->readOne('-1');

    

?>