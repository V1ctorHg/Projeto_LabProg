<?php
    session_start();
    include "connect.inc.php";
    include "estudante.class.php";
    
    if (isset($_GET['cod_curso'])) {
        $cod_curso = intval($_GET['cod_curso']);
    
        $stmt = $conn->prepare("
            SELECT e.matricula, e.nome, e.email, e.senha 
            FROM inscricoes i 
            JOIN estudante e ON i.mat_estudante = e.matricula 
            WHERE i.cod_curso = ?");
        $stmt->bind_param("i", $cod_curso);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo "Código do curso não fornecido.";
        exit();
    }
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscritos no Curso</title>
    <link rel="stylesheet" href="./estilos/estilo.css">
</head>
<body>
    <div class="container">
        <h1>Estudantes Inscritos no Curso</h1>
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <tr>
                    <th>Matricula</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Senha</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['matricula']); ?></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['senha']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Nenhum estudante inscrito neste curso.</p>
        <?php endif; ?>
        <?php $stmt->close(); ?>
    </div>
</body>
</html>