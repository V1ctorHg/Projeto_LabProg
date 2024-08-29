<?php
    include "connect.inc.php";
    include "evento.class.php";
    include "curso.class.php";


    $evento = new Evento($conn);
    $res = $evento->read();

    

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/organizador.css">
    <title>RELATÓRIO — EVENTOS</title>
</head>
<body>
    <div class="container_general">
        <header id="fixed_header">
            <div class="container_top_info"></div>
            <nav class="side_menu">
                <ul class="menu_list">
                    <?php echo '<li><a class="about_link" href="inicioAdministrador.php?matricula=<?php echo $matricula; ?>">Voltar</a></li>'; ?>
                </ul>
            </nav>
        </header>

        <main id="rel_stud_main">
            <div class="container_table">
                <h1>Relatório de Eventos</h1>
                <?php if (!empty($res)): ?>
                    <table>
                        <tr>
                            <th>Cod</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Data_início</th>
                            <th>Data_fim</th>
                        </tr>
                        <?php foreach ($res as $r): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($r['cod_evento']); ?></td>
                                <td><?php echo htmlspecialchars($r['nome']); ?></td>
                                <td><?php echo htmlspecialchars($r['descricao']); ?></td>
                                <td><?php echo htmlspecialchars($r['datahora_ini']); ?></td>
                                <td><?php echo htmlspecialchars($r['datahora_fim']); ?></td>
                            </tr>
                        <tr>
                            <td colspan="6">
                                <table border="1" width="100%">
                                    <tr>
                                        <th>Nome do Curso</th>
                                        <th>Data de Início</th>
                                        <th>Duração</th>
                                    </tr>
                                    <?php
                                    $stmt = $conn->prepare("SELECT nome, datahora_ini, horas FROM curso JOIN evento_curso ON curso.cod_curso = evento_curso.cod_curso WHERE evento_curso.cod_evento = ?");
                                    $stmt->bind_param("i", $r['cod_evento']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($curso = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($curso['nome']); ?></td>
                                            <td><?php echo htmlspecialchars($curso['datahora_ini']); ?></td>
                                            <td><?php echo htmlspecialchars($curso['horas']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    <?php $stmt->close(); ?>
                                </table>
                            </td>
                        </tr>
                
                        <?php endforeach; ?>
                        <?php else: ?>
                            <p>Sem Eventos no momento.</p>
                        <?php endif; ?>
                    </table>
            </div>
        </main>
    </div>
</body>
</html>