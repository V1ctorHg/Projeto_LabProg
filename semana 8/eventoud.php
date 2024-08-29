<?php
    include "connect.inc.php";
    include "organizador.class.php";
    include "administrador.class.php";
    include "evento.class.php";

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cod_evento = $_POST['cod_evento'];
   if($_POST['action'] == 'update'){
        $evento->update($_POST);
    } else if($_POST['action'] == 'delete'){
        $evento->delete($cod_evento);           
    }else if($_POST['action'] == 'finaliza') {
        $stmt = $conn->prepare("
            SELECT cod_curso 
            FROM evento_curso 
            WHERE cod_evento = ?
        ");
        $stmt->bind_param("i", $_POST['cod_evento']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($curso = $result->fetch_assoc()) {
            $cod_curso = $curso['cod_curso'];
    
            $stmt_update = $conn->prepare("
                UPDATE estudante 
                SET pontos = COALESCE(pontos, 0) + 10  
                WHERE matricula IN (
                    SELECT mat_estudante 
                    FROM inscricoes 
                    WHERE cod_curso = ?
                )
            ");
            $stmt_update->bind_param("i", $cod_curso);
            $stmt_update->execute();
        }
    
        $evento->delete($_POST['cod_evento']);
    }
}


        
    header("Location: gerenciareventos.php");

    exit;

?>
