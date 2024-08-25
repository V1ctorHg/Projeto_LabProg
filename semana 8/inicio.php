<!-- TELA DO ESTUDANTE -->

<?php
    session_start();
    include "connect.inc.php";
    include "estudante.class.php";
    include "curso.class.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : '';
} #else if ($_SERVER["REQUEST_METHOD"] == "GET") { 
    #$matricula = isset($_GET['matricula']) ? htmlspecialchars($_GET['matricula']) : '';
#} 
else if (isset($_SESSION['matricula'])) {  
    $matricula = $_SESSION['matricula'];

} else {
    header('Location: login.php');
    exit;
}

$_SESSION['tipouser'] = 'estudante';


if ($matricula) { //Pegando email e nome da matricula logada
    $stmt = $conn->prepare("SELECT nome, email FROM estudante WHERE matricula = ?");
    $stmt->bind_param("i", $matricula);
    $stmt->execute();
    $stmt->bind_result($nome, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    header('Location: login.php');
    exit;
}

$cursos = new Curso($conn);
$res = $cursos->read();
$_SESSION['matricula'] = $matricula;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/inicio.css">
    <title>INÍCIO — <?php echo $nome; ?></title>
</head>
<body>
    <div class="container_general">
        <header>
            <div class="container_top_info">
                <p class="text_info username">Bem vindo, <span class="bold"><?php echo $nome; ?></span>!</p>
                <p class="text_info user_email">E-mail: <?php echo $email; ?></p>
                <p class="text_info user_mat">Matrícula: <span class="bold"><?php echo $matricula; ?></span></p>
            </div>
        
            <nav class="side_menu">
                <ul class="menu_list">
                    <li><a class="about_link" href="rela_estudante.php">Relatório Estudante</a></li>
                    <li><a class="about_link" href="rela_evento.php">Relatório Evento</a></li>
                    <li><a class="about_link" href="#ranking">Ranking</a></li>
                    <li><a class="about_link" href="editar.php">Editar</a></li>
                    <li><a class="about_link" href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
        <!-- Impressão dos cursos | Falta realizar a inscrição com o botão -->
        <div class="flex_container">
            <section class="table_info">
                <?php if (!empty($res)): ?>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Nome (Curso)</th>
                            <th>Data do Evento</th>
                            <th>Duração</th>
                            <th>Inscrever-se</th>
                        </tr>
                        <?php
                        foreach ($res as $r) {
                            // Verifica se o estudante já está inscrito no curso
                            $stmt = $conn->prepare("SELECT 1 FROM inscricoes WHERE mat_estudante = ? AND cod_curso = ?");
                            $stmt->bind_param("ii", $matricula, $r['cod_curso']);
                            $stmt->execute();
                            $stmt->store_result();
                            $isMatriculado = $stmt->num_rows > 0;
                            $stmt->close();

                            // Verifica se há conflito de datas
                            $stmt = $conn->prepare("SELECT 1 FROM inscricoes i JOIN curso c ON i.cod_curso = c.cod_curso WHERE i.mat_estudante = ? AND c.datahora_ini = ?");
                            $stmt->bind_param("is", $matricula, $r['datahora_ini']);
                            $stmt->execute();
                            $stmt->store_result();
                            $hasDateConflict = $stmt->num_rows > 0;
                            $stmt->close();

                            echo ("
                                <tr>
                                    <td>{$r['cod_curso']}</td>
                                    <td>{$r['nome']}</td>
                                    <td>{$r['datahora_ini']}</td>
                                    <td>{$r['horas']}</td>
                                    <td>");
                            if ($isMatriculado) {
                                echo "<form action='inscricao.php' method='post'>
                                        <input type='hidden' name='cod_curso' value='{$r['cod_curso']}'>
                                        <input type='hidden' name='matricula' value='{$matricula}'>
                                        <button type='submit' name='desinscrever'>Desinscrever-se</button>
                                      </form>";
                            } elseif ($hasDateConflict) {
                                echo "<button disabled>Conflito de Data</button>";
                            } else {
                                echo "<form action='inscricao.php' method='post'>
                                        <input type='hidden' name='cod_curso' value='{$r['cod_curso']}'>
                                        <input type='hidden' name='matricula' value='{$matricula}'>
                                        <button type='submit' name='inscrever'>Inscrever-se</button>
                                      </form>";
                            }
                            echo "</td></tr>";
                        }
                        ?>
                    </table>
                <?php else: ?>
                    <p class="no_courses">Não há cursos no momento</p>
                <?php endif; ?>
            </section>
        </div>
    </div>
</body>
</html>
