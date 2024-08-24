<!-- TELA DO ESTUDANTE -->

<?php
    session_start();
    include "connect.inc.php";
    include "estudante.class.php";
    include "curso.class.php";
// Verifica se os dados foram passados corretamente via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : '';
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {   //Necessário para retorno para pagina, enviando a matricula
    $matricula = isset($_GET['matricula']) ? htmlspecialchars($_GET['matricula']) : '';
} else {    // Se os dados não foram passados, redirecionar para a página de login
    $matricula = $_SESSION['matricula'];
    var_dump($matricula);
    if (!$matricula) { #se não tiver matricula na sessão, redireciona para o login
        header('Location: login.php');
        exit;
    }
}

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
                    <li><a class="about_link" href="#ranking">Ranking</a></li>
                    <li><a class="about_link" href="editar.php">Editar</a></li>
                    <li><a class="about_link" href="login.php">Logout</a></li>
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
                        </tr>
                        <?php
                        foreach ($res as $r) {
                            echo ("
                                <tr>
                                    <td>{$r['cod_curso']}</td>
                                    <td>{$r['nome']}</td>
                                    <td>{$r['datahora_ini']}</td>
                                    <td>{$r['horas']}</td>
                                </tr>");
                        }
                        ?>
                    </table>
                <?php else: ?>
                    <p class="no_courses">Você não possui cadastro em nenhum curso no momento.</p>
                <?php endif; ?>
            </section>  <!-- Fim da impressão -->
        </div>
    </div>
</body>
</html>
