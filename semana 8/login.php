<?php 
    include "connect.inc.php";
    include "estudante.class.php";
    include "organizador.class.php";
    include "administrador.class.php";

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
    
    $organizador = new Organizador($conn);
    $rga = $organizador->read();
    $rgaOne = $organizador->readOne('-1');

    $resOne = $estudante->readOne('-1');

    $administrador = new Administrador($conn);
    $radm = $administrador->read();
    $radmOne = $administrador->readOne('-1');
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

        if (!ctype_digit($matricula)) {
            $matriErr = "* Matrícula deve conter apenas números!";
            $EstaValido = false;
        }else{
        // Verifica se a matrícula existe no banco de dados
        $resOne = $estudante->readOne($matricula);
        $rgaOne = $organizador->readOne($matricula);
        $radmOne = $administrador->readOne($matricula);
    }
        if (empty($resOne) && empty($rgaOne) && empty($radmOne)) {
            $matriErr = "* Matrícula inválida!";
            $senErr = "";
            $EstaValido = false;
        }
    }
  
    if (empty($_POST["senha"] && $EstaValido)) {
        $senha = "";
        $EstaValido = false;
        $senErr = "* Senha requerida!";
    } else {
        $senha = test_input($_POST["senha"]);
        
        if ($EstaValido) {
            if (!empty($resOne) && $resOne[0]['senha'] != $senha) {
                $senErr = "* Senha incorreta!";
                $EstaValido = false;
            } elseif (!empty($rgaOne) && $rgaOne[0]['senha'] != $senha) {
                $senErr = "* Senha incorreta!";
                $EstaValido = false;
            } elseif (!empty($radmOne) && $radmOne[0]['senha'] != $senha) {
                $senErr = "* Senha incorreta!";
                $EstaValido = false;
            }
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

    if (!empty($resOne)) {
        $postData = [
            'matricula' => $resOne[0]['matricula'],
            'nome' => $resOne[0]['nome'],
            'email' => $resOne[0]['email'],
            'pontos' => $resOne[0]['pontos']
        ];
        $redirectPage = 'inicio.php';                       // Página do estudante
    } elseif (!empty($rgaOne)) {
        $postData = [
            'matricula_organizador' => $rgaOne[0]['matricula_organizador'],
            'nome' => $rgaOne[0]['nome'],
            'email' => $rgaOne[0]['email']
        ];
        $redirectPage = 'inicioOrganizador.php';              // Página do organizador
    } elseif (!empty($radmOne)) {
        $postData = [
            'matricula_admin' => $radmOne[0]['matricula_admin'],
            'nome' => $radmOne[0]['nome'],
            'email' => $radmOne[0]['email']
        ];
        $redirectPage = 'inicioAdministrador.php';              // Página do organizador
    }

    echo "<form id='loginForm' action='$redirectPage' method='post'>";
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
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./estilos/loginCad.css">
        <!-- <style>.error {color: #FF0000;}</style> -->
        <title>LOGIN</title>
    </head>
    <body>
        <!-- Conteúdo -->
        <main>
        <a href="./index.html" class="home">SISCEA</a>
            <section class="container_general">
                <div class="container_forms">
                    <form action="<?php echo $FormularioAcao; ?>" method="post">
                        <input type="hidden" name="action" value="<?php echo $action; ?>">

                        <label for="matricula">Matrícula</label>
                        <span class="error"><?php echo $matriErr;?></span>
                        <input type="text" name="matricula" id="matricula" value="<?php echo !empty($resOne) ? $resOne[0]['matricula'] : $matricula; ?>">
                        
                        <label for="password">Senha</label>
                        <span class="error"><?php echo $senErr;?></span>
                        <input type="password" name="senha" id="password">
            
                        <input type="submit" name="submit" value="<?php echo $actionVal ?>">

                        <p class="login_text">Ainda não possui conta? <a href="crud.php" class="cadastro">Cadastre sua conta</a></p>
                        <input type="hidden" name="valMat" value="">
                        <input type="hidden" name="valSenha" value="">
                    </form>
                </div>
            </section>

            <section class="bg_grid"></section>
        </main>
    </body>
</html>