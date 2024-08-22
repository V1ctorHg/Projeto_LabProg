<?php

session_start();

    include "connect.inc.php";
    include "organizador.class.php";
    include "evento.class.php";
    include "curso.class.php";

// Verifica se os dados foram passados corretamente via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = isset($_POST['matricula_organizador']) ? htmlspecialchars($_POST['matricula_organizador']) : '';
 } else if ($_SERVER["REQUEST_METHOD"] == "GET") {                                  //Necessário para retorno para pagina, enviando a matriculo
    $matricula = isset($_GET['matricula_organizador']) ? htmlspecialchars($_GET['matricula_organizador']) : '';
}
 else{                                  // Se os dados não foram passados, redirecionar para a página de login
    header('Location: login.php');
    exit;
}

if ($matricula) {                                                                                  //Pegando email e nome da matricula logada
    $stmt = $conn->prepare("SELECT nome, email FROM organizador WHERE matricula_organizador = ?");
    $stmt->bind_param("i", $matricula);
    $stmt->execute();
    $stmt->bind_result($nome, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    header('Location: login.php');
    exit;
}

$_SESSION['matricula_organizador'] = $matricula;

$evento = new Evento($conn);
$res = $evento->read();

$query = "
    SELECT e.cod_evento, e.nome AS evento_nome, e.descricao, e.datahora_ini, e.datahora_fim, c.cod_curso, c.nome AS curso_nome
    FROM evento e
    LEFT JOIN evento_curso ec ON e.cod_evento = ec.cod_evento
    LEFT JOIN curso c ON ec.cod_curso = c.cod_curso
";

$result = $conn->query($query);
$eventos = [];


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/organizador.css">
    <title>Página Inicial</title>
</head>
<body>
    <header class="container_top">
        <nav>
            <ul>
                <li><h1>Bem-vindo, <?php echo $nome; ?>!</h1></li>
                    <ul class="selections">
                        <li><a class="about_link" href="#ranking">Ranking</a></li>
                        <li class="vertical-row"></li>
                        <li><a class="about_link" href="#eventos">Gerenciar Eventos</a></li>
                        <li class="vertical-row"></li>
                        <li><a class="about_link" href="#">Editar</a></li>                        <li class="vertical-row"></li>
                        <li><a class="about_link" href="login.php">Logout</a></li>
                    </ul>
                <li><strong>Matrícula:</strong> <?php echo $matricula; ?></a></li>
            </ul>
        </nav>
    </header>
    <section class="event">
    <h2>Criar Evento</h2>
    <form action="criar_evento.php" method="post">
        <label for="nome_evento">Nome do Evento:</label><br>
        <input type="hidden" name="matricula_organizador" value="<?php echo htmlspecialchars($matricula); ?>">  
        <input type="text" id="nome_evento" name="nome_evento" required><br><br>
        
        <label for="descricao">Descrição:</label><br>
        <textarea id="descricao" name="descricao" required></textarea><br><br>
        
        <label for="data_inicio">Data de Início:</label><br>
        <input type="datetime-local" id="data_inicio" name="data_inicio" required><br><br>
        
        <label for="data_fim">Data de Fim:</label><br>
        <input type="datetime-local" id="data_fim" name="data_fim" required><br><br>
        
        <input type="submit" value="Criar Evento">
    </form>
</section>

<section class="infos">                          <!-- Impressão dos eventos e cursos -->
        <?php if (!empty($res)): ?>
            <table>
                <tr>
                    <th>Cod</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Data_início</th>
                    <th>Data_fim</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($res as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['cod_evento']); ?></td>
                        <td><?php echo htmlspecialchars($r['nome']); ?></td>
                        <td><?php echo htmlspecialchars($r['descricao']); ?></td>
                        <td><?php echo htmlspecialchars($r['datahora_ini']); ?></td>
                        <td><?php echo htmlspecialchars($r['datahora_fim']); ?></td>
                        <td>
                            <button class='open-modal' data-evento-id='<?php echo htmlspecialchars($r['cod_evento']); ?>'>Adicionar Curso</button>
                        </td>
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
            </table>
        <?php else: ?>
            <!-- Mensagem se não houver eventos -->
            <p>Sem Eventos no momento.</p>
        <?php endif; ?>
    </section>     

    <div id="cursoModal" class="modal">         <!-- Modal para tela de criação de cursos -->               
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Adicionar Curso ao Evento</h2>
            <form id="cursoForm" action="adicionar_curso.php" method="post">
                <input type="hidden" id="modal_evento_id" name="cod_evento">
                <input type="hidden" name="matricula_organizador" value="<?php echo htmlspecialchars($matricula); ?>">  
                <label for="nome_curso">Nome do Curso:</label><br>
                <input type="text" id="nome_curso" name="nome_curso" required><br><br>
                
                <label for="data_inicio_curso">Data de Início:</label><br>
                <input type="datetime-local" id="data_inicio_curso" name="data_inicio_curso" required><br><br>
                
                <label for="horas_curso">Duração (em horas):</label><br>
                <input type="number" id="horas_curso" name="horas_curso" required><br><br>
                
                <input type="submit" value="Adicionar Curso">
            </form>
        </div>
    </div>

    <script>
        // Script para abrir e fechar o modal
        var modal = document.getElementById("cursoModal");
        var btns = document.querySelectorAll(".open-modal");
        var span = document.getElementsByClassName("close")[0];
        var form = document.getElementById("cursoForm");

        btns.forEach(function(btn) {
            btn.onclick = function() {
                var eventoId = this.getAttribute("data-evento-id");
                document.getElementById("modal_evento_id").value = eventoId;
                modal.style.display = "block";
            }
        });

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>
