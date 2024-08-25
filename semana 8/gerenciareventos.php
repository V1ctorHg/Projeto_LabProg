<?php
    include "connect.inc.php";
    include "organizador.class.php";
    include "administrador.class.php";
    include "evento.class.php";
    include "curso.class.php";

    session_start();

    if(isset ($_SESSION['matricula']) or isset ($_SESSION['matricula_organizador'])){
        if($_SESSION['tipouser'] == 'organizador'){
            $matricula = $_SESSION['matricula_organizador'];
        } elseif ($_SESSION['tipouser'] == 'administrador'){
            $matricula = $_SESSION['matricula'];
        }   
    } else {
        header('Location: login.php');
        exit;
    }

    $evento = new Evento($conn);
    $curso = new Curso($conn);

    $resevento = $evento->read();
    $rescurso = $curso->read();

    
?>