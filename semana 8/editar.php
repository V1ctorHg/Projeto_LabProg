<?php 
    include "connect.inc.php";
    include "estudante.class.php";
    include "organizador.class.php";

    session_start(); #inicia a sessão no arquivo
    
    // Retirado o var_dump porque ficava no top da tela me atrapalhando
    // var_dump($_SESSION);

    if ($_SESSION['tipouser'] == 'estudante') {
        $matricula = $_SESSION['matricula'];
    } elseif ($_SESSION['tipouser'] == 'organizador') {
        $matricula = $_SESSION['matricula_organizador'];
    } else {
        header("Location: login.php");
        exit;
    }
    $action = 'update';
    $actionVal = 'Atualizar';

    $estudante = new Estudante($conn); #instancia um objeto da classe Estudante
    $organizador = new Organizador($conn); #instancia um objeto da classe Organizador

    $estavalido = false;
    $organiza = false;

    $resnum = $estudante->readOne($matricula); #chama o método readOne da classe Estudante
    if(empty($resnum)){
        $resnum = $organizador->readOne($matricula); #chama o método readOne da classe Organizador se não for um estudante
        $organiza = true;
    }

    $res = $resnum[0]; #captura o primeiro elemento do array
    $senhaerr = $emailerr = $nomeerr = "";
    if($organiza){
        $matricula = $res["matricula_organizador"];
    }else{
        $matricula = $res["matricula"];
    }
    $nome = $res['nome']; 
    $senha = $res['senha'];
    $email = $res['email'];
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

      echo $organiza;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $estavalido = True;

        #$matricula = $_SESSION['matricula']; #captura a matricula da sessão
        
        if (empty($_POST["nome"])) {
            $nameErr = "Um nome é necessário!";
            $nome = $res['nome'];
            $estavalido = False;
        } else {
            $nome = test_input($_POST["nome"]);
            if (!preg_match("/^[\p{L}\'\- ]+$/u", $nome)) {
                $valnome = "ERRADO";
                $estavalido = False;
            } else {
                $valnome = "CERTO";
            }
        }
          
        if (empty($_POST["email"])) {
            $emailerr = "Um e-mail é necessário!";
            $email = $res['email'];
            $estavalido = False;
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailerr = "Formato de e-mail inválido!";
                $valemail = "ERRADO";
                $estavalido = False;
            } else {
                $valemail = "CERTO";
            }
        }
          
        if (empty($_POST["senha"])) {
            $senha = $res['senha'];
            $estavalido = False;
            $senErr = "Senha necessária!";
            $valsenha = "ERRADO";
        } else {
            $senha = test_input($_POST["senha"]);
            $valsenha = "CERTO";
        }

<<<<<<< HEAD
        var_dump($_SESSION['matricula']);
        if($estavalido) {
            if($organiza) {
                $organizador->update($_POST);
                $_SESSION['matricula'] = $matricula;
                header('Location: inicio.php');
                exit;
            } else {
                $estudante->update($_POST);
                $_SESSION['matricula'] = $matricula;
                header("Location: inicio.php?matricula=" . urlencode($matricula));
                exit;
=======
            if($estavalido){
                if($organiza){
                    $organizador->update($_POST);
                    $_SESSION['matricula_organizador'] = $matricula;
                    #header("Location: inicioOrganizador.php?matricula_organizador=" . urlencode($matricula));
                    header("Location: inicioOrganizador.php");
                    exit;
                } else {
                    $estudante->update($_POST);
                    $_SESSION['matricula'] = $matricula;
                    #header("Location: inicio.php?matricula=" . urlencode($matricula));
                    header("Location: inicio.php");
                    exit;
                }
>>>>>>> 5521643c9b123ab0c630dffb1e62b4b15b3e091d
            }
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./estilos/inicioEditar.css">
        <title>EDITAR</title>
    </head>
    <body>
        <section class="container_general">
            <header>
                <div class="container_top_info">
                    <div class="empty_container"></div>
                </div>
            
                <nav class="side_menu">
                    <ul class="menu_list">
<<<<<<< HEAD
                        <li><a class="about_link" href="inicio.php?matricula=<?php echo $matricula; ?>">Voltar</a></li>
=======
                        <?php if($organiza){
                            echo '<li><a class="about_link" href="inicioOrganizador.php">Voltar</a></li>';
                        }else{
                            echo '<li><a class="about_link" href="inicio.php">Voltar</a></li>';
                        }
                        ?>
>>>>>>> 5521643c9b123ab0c630dffb1e62b4b15b3e091d
                    </ul>
                </nav>
            </header>

            <div class="container_forms">
                <form method="post">
                    <h2 class="title">Editar Dados</h2>
                    <input type="hidden" name="action" value="<?php echo $action; ?>">

                    <label for="matricula">Matrícula:</label>
                    <input type="text" name="matricula" value="<?php echo $matricula; ?>" readonly>
                    <input type="hidden" name="matricula_organizador" value="<?php echo $matricula; ?>">

                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" id="nome" value="<?php echo $nome; ?>" required>
                    <span class="error">* <?php echo $nomeerr;?></span>

                    <label for="email">E-mail:</label>
                    <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
                    <span class="error">* <?php echo $emailerr;?></span>

                    <label for="senha">Senha:</label>
                    <input type="text" name="senha" id="senha" value="<?php echo $senha; ?>" required>
                    <span class="error">* <?php echo $senhaerr;?></span>

                    <input type="submit" name="submit" value="<?php echo $actionVal; ?>">

                    <input type="hidden" name="valnome" value="<?php echo $valnome; ?>">
                    <input type="hidden" name="valemail" value="<?php echo $valemail; ?>">
                    <input type="hidden" name="valSenha" value="<?php echo $valsenha; ?>">
                </form>
            </div>
        </section>
    </body>
</html>