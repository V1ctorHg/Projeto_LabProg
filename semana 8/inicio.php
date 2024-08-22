<?php
    session_start();
    include "connect.inc.php";
    include "estudante.class.php";
    include "curso.class.php";

// Verifica se os dados foram passados corretamente via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : '';
 } else if ($_SERVER["REQUEST_METHOD"] == "GET") {                                  //Necessário para retorno para pagina, enviando a matriculo
    $matricula = isset($_GET['matricula']) ? htmlspecialchars($_GET['matricula']) : '';
}
 else{                                  // Se os dados não foram passados, redirecionar para a página de login
    header('Location: login.php');
    exit;
}

if ($matricula) {                                                                                  //Pegando email e nome da matricula logada
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
                        <li><a class="about_link" href="#">Editar</a></li>                        <li class="vertical-row"></li>
                        <li><a class="about_link" href="login.php">Logout</a></li>
                    </ul>
                <li><strong>Matrícula:</strong> <?php echo $matricula; ?></a></li>
            </ul>
        </nav>
    </header>
    <section class="infos">                          <!-- Impressão dos cursos | Falta realizar a inscrição com o botão -->
    <?php if (!empty($res)): ?>
        <table>
            <tr>
                <th>cod</th>
                <th>nome</th>
                <th>data</th>
                <th>hora</th>
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
        <p>Sem cursos no momento.</p>
    <?php endif; ?>
    </section>                                       <!-- Fim da impressão -->
    
</body>
</html>
