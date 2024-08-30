<?php
    include "connect.inc.php";
    include "estudante.class.php";
    include "organizador.class.php";

    $matricula = 0;
    $action = 'insert';
    $actionVal = 'Cadastrar';

    if (!empty($_GET['action'])) {
        
        $action = $_GET['action'];
        $actionVal = 'Atualizar';
        $matricula = $_GET['matricula'];
    }

    $estudante = new Estudante($conn);
    $organizador = new Organizador($conn);

    $res = $estudante->read();
    $resOne = $estudante->readOne('-1');

    if($action =='update'){
        $resOne = $estudante->readOne($matricula);
    }
?>


<?php
$nameErr = $emailErr = $senErr = $matriErr = "";
$nome = $email = $senha= $matricula = "";
$valName = $valEmail = "";
$FormularioAcao = "crud.php";

$EstaValido = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $EstaValido = True;

  if (empty($_POST["nome"])) {
    $nameErr = "Adicione um nome!";
    $EstaValido = False;
  } else {
    $nome = test_input($_POST["nome"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
        $nameErr = "Apenas letras e espaços são permitidos";
        $valName = "ERRADO";
        $EstaValido = False;
    } else {
        $valName = "CERTO";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Um e-mail é necessário!";
    $EstaValido = False;
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Formato de e-mail inválido!";
        $valEmail = "ERRADO";
        $EstaValido = False;
    } else {
        $valEmail = "CERTO";
    }
  }
  
  if (empty($_POST["senha"])) {
    $senha = "";
    $EstaValido = False;
    $senErr = "Senha necessária!";
  } else {
    $senha = test_input($_POST["senha"]);
    $valsenha = "CERTO";
  }

  if (empty($_POST["matricula"])) {
    $matricula = "";
    $EstaValido = False;
    $matriErr = "Matricula necessária!";
  } else {
    $matricula = test_input($_POST["matricula"]);
    if (!ctype_digit($matricula)) {
        $EstaValido = false;
        $matriErr = "A matrícula deve conter apenas números!";
    }else
    $valmat = "CERTO";
  }
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if ($EstaValido) {
    if (empty($_POST['action'])) {
        $action = $_GET['action'];
        $matricula = $_GET['matricula'];
    } else {
        $action = $_POST['action'];
    }
    if ($action == 'insert') {
        if($_POST['tipouser'] == 'estudante'){
            $estudante->create($_POST);
        } else {
            $organizador->create($_POST);
        }
    } else if ($action == 'update') { #precisa de um update aqui?
        $estudante->update($_POST);
    }
    
    header('Location: login.php');
  }
?>


<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./estilos/crudCad.css">
        <style>.error {color: #FF0000;}</style>
        <title>CADASTRO</title>
    </head>
    <body>
        <!-- Conteúdo -->
        <main>
            <a href="./index.html" class="home">SISCEA</a>
            <section class="container_general">
                <div class="container_form">
                    <form action="<?php echo $FormularioAcao; ?>" method="post">
                        <input type="hidden" name="action" value="<?php echo $action; ?>">

                        <label for="name">Nome: </label>
                        <input type="text" name="nome" id="name" placeholder="Digite seu nome..." value="<?php echo !empty($resOne) ? $resOne[0]['nome'] : $nome; ?>">
                        <span class="error">* <?php echo $nameErr;?></span>

                        <!-- <br> -->
                        <label for="email">Email: </label>
                        <input type="text" name="email" id="email" placeholder="Digite seu e-mail..." value="<?php echo !empty($resOne) ? $resOne[0]['email'] : $email; ?>">
                        <span class="error">* <?php echo $emailErr;?></span>

                        <!-- <br> -->
                        <label for="password">Senha: </label>
                        <input type="text" name="senha" id="password" placeholder="Digite uma senha..." value="<?php echo !empty($resOne) ? $resOne[0]['senha'] : $senha; ?>">

                        <!-- <br> -->
                        <label for="matricula">Matricula: </label>
                        <input type="text" name="matricula" id="matricula" placeholder="Digite sua matrícula..." value="<?php echo !empty($resOne) ? $resOne[0]['matricula'] : $matricula; ?>">
                        <span class="error">* <?php echo $matriErr;?></span>
                        <!-- <br> -->
                        <label for="tipouser">Tipo de Usuário: </label>
                        <select name="tipouser" id="tipouser">
                            <option value="estudante">Estudante</option>
                            <option value="organizador">Organizador</option>

                        </select>
                        <br>
                        <input type="submit" name="submit" value="<?php echo $actionVal ?>">

                        <p class="login_text">Já possui uma conta? <a href="login.php" class="btn btn-secondary">Faça seu login</a></p>

                        <input type="hidden" name="valMat" value="">
                        <input type="hidden" name="valName" value="<?php echo $valName; ?>">
                        <input type="hidden" name="valEmail" value="<?php echo $valEmail; ?>">
                        <input type="hidden" name="valSenha" value="">
                    </form>
                </div>
            </section>

            <section class="bg_grid"></section>
        </main>
        <!-- Lista -->
        <!-- <section class="container">
            <table>
                <tr>
                    <th>matricula</th>
                    <th>nome</th>
                    <th>e-mail</th>
                    <th>senha</th>

                    <th>Editar</th>
                    <th>Deletar</th>
                </tr>
<?php 
    foreach ($res as $r) {
        echo ("
            <tr>
                <td>{$r['matricula']}</td>
                <td>{$r['nome']}</td>
                <td>{$r['email']}</td>
                <td>{$r['senha']}</td>

                <td><a href='crud.php?action=update&matricula={$r['matricula']}'>E</a></td>
                <td><a href='formAction.php?action=delete&matricula={$r['matricula']}'>X</a></td>
            </tr>");
    }
?>
            </table>
        </section> -->
    </body>

</html>