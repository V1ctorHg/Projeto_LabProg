<?php 
    include "connect.inc.php";
    include "estudante.class.php";

    if (empty($_POST['action'])) {
        $action = $_GET['action'];
        $matricula = $_GET['matricula'];
    } else {
        $action = $_POST['action'];
    }

    $estudante = new Estudante($conn);

    if ($action == 'insert') {
        $estudante->create($_POST);
    }else if ($action == 'delete') {
        $estudante->delete($matricula);

    }else if($action == 'update'){
        $estudante->update($_POST);
    }
    
    

    header('Location: crud.php');

    // print("<pre>");
    // var_dump($aluno);
?>