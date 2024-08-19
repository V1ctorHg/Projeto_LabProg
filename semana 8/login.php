<?php 

    include "connect.inc.php";
    include "estudante.class.php";

    $matricula = 0;
    $action = 'insert';
    $actionVal = 'Logar';

    if (!empty($_GET['action'])) {
        
        $action = $_GET['action'];
        $actionVal = 'ATUALIZAR';
        $matricula = $_GET['matricula'];
    }

    $estudante = new Estudante($conn);

    $res = $estudante->read();
    


    $resOne = $estudante->readOne('-1');
?>


<?php
$matriErr = $senErr = "";
$senha = $matricula = "";
$valName = $valEmail = "";
$FormularioAcao = "login.php";

$EstaValido = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $EstaValido = True;

    if (empty($_POST["matricula"])) {
        $matriErr = "";
        $EstaValido = false;
    } else {
        $matricula = test_input($_POST["matricula"]);
        // Verifica se a matrícula existe no banco de dados
        $resOne = $estudante->readOne($matricula);
        if (empty($resOne)) {
            $matriErr = "* Matrícula inválida";
            $senErr = "";
            $EstaValido = false;
        }
    }
  
    if (empty($_POST["senha"] && $EstaValido)) {
        $senha = "";
        $EstaValido = false;
        $senErr = "* Senha Requerida";
    } else {
        $senha = test_input($_POST["senha"]);
        
        if ($EstaValido && $resOne[0]['senha'] != $senha) {
            $senErr = "* Senha incorreta";
            $EstaValido = false;
        }
    }
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if ($EstaValido) {

    $postData = [
        'matricula' => $resOne[0]['matricula'],
        'nome' => $resOne[0]['nome'],
        'email' => $resOne[0]['email'],
        'pontos' => $resOne[0]['pontos']
    ];

    echo "<form id='loginForm' action='inicio.php' method='post'>";
    foreach ($postData as $key => $value) {
        echo "<input type='hidden' name='$key' value='$value'>";
    }
    echo "</form>";
    echo "<script>document.getElementById('loginForm').submit();</script>";
    exit;
}
?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="estilos/login.css">
        <style>.error {color: #FF0000;}</style>
    </head>
    <body>
        <!-- Conteúdo -->
         <section class="container">
            <div class="">
                <form action="<?php echo $FormularioAcao; ?>" method="post">
                    <input type="hidden" name="action" value="<?php echo $action; ?>">
                    Matricula<span class="error"><?php echo $matriErr;?></span>
                    <div class="group">
                    <input type="text" name="matricula" value="<?php echo !empty($resOne) ? $resOne[0]['matricula'] : $matricula; ?>">
                    </div>
                    <br>
                    Senha <span class="error"><?php echo $senErr;?></span>
                    <div class="group">
                        <input type="password" name="senha">
                    </div>
                    <br>
                    
                    <input type="submit" name="submit" value="<?php echo $actionVal ?>">  
                    <a href="crud.php" class="cadastro">Ir para Cadastro</a>
                    <input type="hidden" name="valMat" value="">
                    <input type="hidden" name="valSenha" value="">
                </form>
            </div>            
         </section>  
    </body>

</html>