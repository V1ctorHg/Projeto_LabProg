<?php
session_start();

include "connect.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_curso = htmlspecialchars($_POST['nome_curso']);
    $data_inicio_curso = htmlspecialchars($_POST['data_inicio_curso']);
    $horas_curso = htmlspecialchars($_POST['horas_curso']);
    $cod_evento = htmlspecialchars($_POST['cod_evento']);
    $matricula_organizador = $_SESSION['matricula_organizador'];


    // Insere o curso na tabela 'curso'
    $stmt = $conn->prepare("INSERT INTO curso (nome, datahora_ini, horas) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome_curso, $data_inicio_curso, $horas_curso);
    $stmt->execute();

    // Recupera o ID do curso recém-criado
    $cod_curso = $conn->insert_id;

    // Relaciona o curso ao evento
    $stmt = $conn->prepare("INSERT INTO evento_curso (cod_evento, cod_curso) VALUES (?, ?)");
    $stmt->bind_param("ii", $cod_evento, $cod_curso);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    // Redireciona de volta para a página de início do organizador
    header("Location: inicioOrganizador.php?matricula_organizador=" . urlencode($matricula_organizador));
    exit;
}