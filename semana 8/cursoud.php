<?php
    include "connect.inc.php";
    include "organizador.class.php";
    include "administrador.class.php";
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

    $curso = new Curso($conn);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if($_POST['action'] == 'update'){
             $curso->update($_POST);
         } else if($_POST['action'] == 'delete'){
             //$cod_evento = $_POST['cod_evento'];
             //$evento->delete($cod_evento);
        }

        header("Location: gerenciareventos.php");

        exit;
    }