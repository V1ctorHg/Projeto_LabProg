<?php
include "connect.inc.php";
include "organizador.class.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_evento = htmlspecialchars($_POST['nome_evento']);
    $descricao = htmlspecialchars($_POST['descricao']);
    $data_inicio = htmlspecialchars($_POST['data_inicio']);
    $data_fim = htmlspecialchars($_POST['data_fim']);
    $matricula_organizador = htmlspecialchars($_POST['matricula_organizador']);

    $stmt = $conn->prepare("INSERT INTO evento (nome, descricao, datahora_ini, datahora_fim) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome_evento, $descricao, $data_inicio, $data_fim);
    $stmt->execute();

    $cod_evento = $conn->insert_id;  //Pegar o ID do evento criado

    $stmt = $conn->prepare("INSERT INTO organizador_evento (mat_organizador, cod_evento) VALUES (?, ?)");
    $stmt->bind_param("ii", $matricula_organizador, $cod_evento);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    //header("Location: adicionar_curso.php?cod_evento=$cod_evento");
    header("Location: inicioOrganizador.php?matricula_organizador=" . urlencode($matricula_organizador));

    exit;
}
?>