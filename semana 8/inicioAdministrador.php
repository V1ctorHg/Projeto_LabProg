<!-- TELA DO ADMINISTRADOR -->

<?php
    session_start();
    include "connect.inc.php";
    include "administrador.class.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = isset($_POST['matricula_admin']) ? htmlspecialchars($_POST['matricula_admin']) : '';
} #else if ($_SERVER["REQUEST_METHOD"] == "GET") { 
    #$matricula = isset($_GET['matricula_admin']) ? htmlspecialchars($_GET['matricula_admin']) : '';
#} 
else if (isset($_SESSION['matricula'])) {  
    $matricula = $_SESSION['matricula'];

} else {
    header('Location: login.php');
    exit;
}

$_SESSION['tipouser'] = 'administrador';


if ($matricula) { //Pegando email e nome da matricula logada
    $stmt = $conn->prepare("SELECT nome, email FROM administrador WHERE matricula_admin = ?");
    $stmt->bind_param("i", $matricula);
    $stmt->execute();
    $stmt->bind_result($nome, $email);
    $stmt->fetch();
    $stmt->close();
} else {
    header('Location: login.php');
    exit;
}

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
                    
                    <li><a class="about_link" href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
        
        
    </div>
</body>
</html>