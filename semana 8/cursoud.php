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
        $cod_curso = $_POST['cod_curso'];
        error_log("Received cod_curso: " . $cod_curso); // Log para depuração
        if($_POST['action'] == 'update'){
            $curso->update($_POST);
        } else if($_POST['action'] == 'delete'){
            $curso->delete($cod_curso);
        } else if($_POST['action'] == 'finaliza'){
            $stmt = $conn->prepare(" UPDATE estudante 
            SET pontos = COALESCE(pontos, 0) + 10  
            WHERE matricula IN (
                SELECT mat_estudante 
                FROM inscricoes 
                WHERE cod_curso = ?)");
            $stmt->bind_param("i", $_POST['cod_curso']);
            $stmt->execute();


            $curso->delete($_POST['cod_curso']);
        }

        header("Location: gerenciareventos.php");

        exit;
    }


        
    
  
