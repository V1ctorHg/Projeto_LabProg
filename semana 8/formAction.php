<?php 
    include "connect.inc.php";
    include "alunos.class.php";

    if (empty($_POST['action'])) {
        $action = $_GET['action'];
        $id = $_GET['id'];
    } else {
        $action = $_POST['action'];
    }

    $aluno = new Aluno($conn);

    if ($action == 'insert') {
        $aluno->create($_POST);
    }
    

    header('Location: crud.php');

    // print("<pre>");
    // var_dump($aluno);
?>