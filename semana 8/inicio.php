<?php
// Verifica se os dados foram passados corretamente via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = isset($_POST['matricula']) ? htmlspecialchars($_POST['matricula']) : '';
    $nome = isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $pontos = isset($_POST['pontos']) ? htmlspecialchars($_POST['pontos']) : '';
} else {
    // Se os dados não foram passados, redirecionar para a página de login
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Página Inicial</title>
</head>
<body>

    <h1>Bem-vindo, <?php echo $nome; ?>!</h1>

    <p><strong>Matrícula:</strong> <?php echo $matricula; ?></p>
    <p><strong>Email:</strong> <?php echo $email; ?></p>
    <p><strong>Pontos:</strong> <?php echo $pontos; ?></p>

    <!-- Aqui você pode adicionar mais conteúdo ou navegação da página -->
    
</body>
</html>
