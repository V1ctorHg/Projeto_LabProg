<?php
    include "connect.inc.php";
    include "organizador.class.php";
    include "administrador.class.php";
    include "evento.class.php";
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

    $evento = new Evento($conn);
    $curso = new Curso($conn);

    $resevento = $evento->readorg($matricula);
    $rescurso = $curso->read();
?>

<!DOCTYPE html>
<html>  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/gerenciarEvento.css">
    <title>GERENCIAR</title>
</head>
<body>
    <div class="container_general">
        <header>
            <div class="container_top_info"></div>
            <nav class="side_menu">
                <ul class="menu_list">
                    <?php if($_SESSION['tipouser'] == 'organizador'){
                    echo"<a class='about_link' href='inicioOrganizador.php'>Voltar</a></p>";
                    }elseif($_SESSION['tipouser'] == 'administrador'){
                        echo"<a class='about_link' href='inicioAdministrador.php'>Voltar</a></p>";
                    }
                    ?>
                </ul>
            </nav>
        </header>

        <main>
            <div class="container_table">
                <h1>Gerenciar Eventos e Cursos</h1>
                <section class="table_info">
                    <?php if (!empty($resevento)): ?>
                        <table class="first_table">
                            <tr>
                                <th>ID do Evento</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Início (data e hora)</th>
                                <th>Término (data e hora)</th>
                                <th>Ação</th>
                            </tr>
                            <?php foreach ($resevento as $r): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($r['cod_evento']); ?></td>
                                    <td><?php echo htmlspecialchars($r['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($r['descricao']); ?></td>
                                    <td><?php echo htmlspecialchars($r['datahora_ini']); ?></td>
                                    <td><?php echo htmlspecialchars($r['datahora_fim']); ?></td>
                                    <td class="flex_container_cta">
                                        <button class='event-open-modal' data-evento-id='<?php echo htmlspecialchars($r['cod_evento']); ?>'> Editar Evento</button> <!-- -->
                                    </td>
                                    <td class="evclud">
                                    <form action="eventoud.php" method="POST">
                                        <input type="hidden" name="cod_evento" value= <?php echo ($r['cod_evento']); ?>>
                                        <input type="hidden" name="action" value="finaliza">
                                        <button type="submit" class="finaliza">Finalizar Evento</button>
                                    </form> 
                                    </td>
                                    <td class="evclud">
                                        <form action="eventoud.php" method="POST">
                                            <input type="hidden" name="cod_evento" value="<?php echo htmlspecialchars($r['cod_evento']); ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="delete">Excluir Evento</button>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <table width="100%">
                                            <tr>
                                                <th>Nome do Curso</th>
                                                <th>Data de Início</th>
                                                <th>Duração</th>
                                            </tr>
                                            <?php
                                            $stmt = $conn->prepare("SELECT curso.cod_curso, nome, DATE_FORMAT(datahora_ini, '%d/%m/%Y %H:%i:%s') AS datahora_ini, horas FROM curso JOIN evento_curso ON curso.cod_curso = evento_curso.cod_curso WHERE evento_curso.cod_evento = ?");
                                            $stmt->bind_param("i", $r['cod_evento']);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            while ($curso = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <?php $codc = $curso['cod_curso'];?>
                                                    <td><?php echo htmlspecialchars($curso['nome']); ?></td>
                                                    <td><?php echo htmlspecialchars($curso['datahora_ini']); ?></td>
                                                    <td><?php echo htmlspecialchars($curso['horas']); ?></td>
                                                    <td>
                                                        <button class='curso-open-modal' data-curso-id='<?php echo htmlspecialchars($curso['cod_curso']);?>'> Editar Curso</button>
                                                    </td>
                                                    <td class="evclud">
                                                        <form action="cursoud.php" method="POST">
                                                        <input type="hidden" name="cod_curso" value="<?php echo htmlspecialchars($codc); ?>">
                                                        <input type="hidden" name="action" value="finaliza">
                                                            <button type="submit" class="finaliza">Finalizar Curso</button>
                                                        </form> 
                                                        </td>
                                                        <td class="evclud">
                                                        <form action="cursoud.php" method="POST">
                                                        <input type="hidden" name="cod_curso" value="<?php echo htmlspecialchars($codc); ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                            <button type="submit" class="delete">Excluir Curso</button>
                                                        </form> 
                                                        </td>
                                                </tr>
                                            <?php endwhile; ?>
                                            <?php $stmt->close(); ?>
                                        </table>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>Sem Eventos no momento.</p>
                    <?php endif; ?>
                </section>
            </div>
        </main>

        <!-- #edicao de evento -->
        <div id="editaeventomodal" class="edit_modal">
            <div class="modal-content">
                <div class="container_top_modal">
                    <h2 class="title_modal">Editar Evento</h2>
                    <span class="close">&times;</span>
                </div>

                <form id="editaeventoform" action="eventoud.php" method="post">
                    <input type="hidden" id="action" name="action" value="update">
                    <input type="hidden" id="modal_evento_id" name="cod_evento">
                    <input type="hidden" name="matricula_organizador" value="<?php echo htmlspecialchars($matricula); ?>">
    
                    <label for="id_evento">ID do Evento:</label><br>
                    <input type="text" id="id_evento" name="id_evento" readonly><br><br>
                    <label for="nome_evento">Nome do Evento:</label><br>
                    <input type="text" id="nome_evento" name="nome" required><br><br>
                    <label for="descricao_evento">Descrição do Evento:</label><br>
                    <input type="text" id="descricao_evento" name="descricao" required><br><br>
                    <input type="submit" value="Atualizar Evento">
                </form>
            </div>
        </div>

        <!-- #edicao de curso -->
        <div id="editacursomodal" class="edit_modal">
            <div class="modal-content">
            <div class="container_top_modal">
                    <h2 class="title_modal">Editar Curso</h2>
                    <span class="closec">&times;</span>
                </div>
                <form id="editacursoform" action="cursoud.php" method="post">
                    <input type="hidden" id="action" name="action" value="update">
                    <input type="hidden" id="modal_curso_id" name="cod_curso">
                    <input type="hidden" name="matricula_organizador" value="<?php echo htmlspecialchars($matricula); ?>">
    
                    <label for="id_evento">ID do Curso:</label><br>
                    <input type="text" id="id_curso" name="id_curso" readonly><br><br>
                    <label for="nome_evento">Nome do Curso:</label><br>
                    <input type="text" id="nome_curso" name="nome" required><br><br>
                    <label for="horas_curso">Horas do Curso:</label><br>
                    <input type="text" id="horas_curso" name="horas" required><br><br>
    
                    <input type="submit" value="Atualizar Curso">
                </form>
            </div>
        </div>
    </div>
    

    <!-- #javascript -->

    <script>
        var modal = document.getElementById('editaeventomodal');
            document.querySelector('.event-open-modal').addEventListener('click', function() {
        modal.style.display = 'block';
        });
        var btns = document.querySelectorAll(".event-open-modal");
        var span = document.getElementsByClassName("close")[0];

        btns.forEach(function(btn) {
            btn.onclick = function() {
                var eventoId = this.getAttribute("data-evento-id");
                document.getElementById("modal_evento_id").value = eventoId;
                console.log(eventoId);

                // Requisição AJAX para obter os dados do evento
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "getevento.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function(){
                if (xhr.status == 200){
                    console.log(xhr.responseText);
                    var evento = JSON.parse(xhr.responseText);
                    console.log(evento.nome); // Adicione esta linha para depuração
                    document.getElementById("id_evento").value = evento.cod_evento;
                    document.getElementById("nome_evento").value = evento.nome;
                    document.getElementById("descricao_evento").value = evento.descricao;   
            } else {
                console.error("Erro na requisição: ", xhr.statusText);
            }
                };
    
                xhr.send("cod_evento=" + encodeURIComponent(eventoId));

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

<script>
        var modali = document.getElementById('editacursomodal');
        document.querySelector('.curso-open-modal').addEventListener('click', function() {
            modali.style.display = 'block';
        });
        var btns = document.querySelectorAll(".curso-open-modal");
        var spanc = document.getElementsByClassName("closec")[0];

        btns.forEach(function(btn) {
            btn.onclick = function() {
                var cursoId = this.getAttribute("data-curso-id");
                document.getElementById("modal_curso_id").value = cursoId;
                console.log(cursoId);

                // Requisição AJAX para obter os dados do evento
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "getcurso.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function(){
                if (xhr.status == 200){
                    console.log(xhr.responseText);
                    var curso = JSON.parse(xhr.responseText);
                    console.log(curso); // Adicione esta linha para depuração
                    document.getElementById("id_curso").value = curso.cod_curso;
                    document.getElementById("nome_curso").value = curso.nome;
                    document.getElementById("horas_curso").value = curso.horas;
            } else {
                console.error("Erro na requisição: ", xhr.statusText);
            }
                };
    
                xhr.send("cod_curso=" + encodeURIComponent(cursoId));

                modali.style.display = "block";
            }
        });

        spanc.onclick = function() {
            modali.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modali) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>