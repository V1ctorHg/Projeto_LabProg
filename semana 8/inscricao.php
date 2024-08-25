<?php
session_start();
include "connect.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : '';
    $cod_curso = isset($_POST['cod_curso']) ? (int)$_POST['cod_curso'] : 0;

    if (isset($_POST['inscrever'])) {
        // Inscrição
        $stmt = $conn->prepare("INSERT INTO inscricoes (mat_estudante, cod_curso) VALUES (?, ?)");
        $stmt->bind_param("ii", $matricula, $cod_curso);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['desinscrever'])) {
        // Desinscrição
        $stmt = $conn->prepare("DELETE FROM inscricoes WHERE mat_estudante = ? AND cod_curso = ?");
        $stmt->bind_param("ii", $matricula, $cod_curso);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: inicio.php?matricula=$matricula");
    exit;
} else {
    header("Location: inicio.php?matricula=" . urlencode($matricula));
    exit;
}
?>