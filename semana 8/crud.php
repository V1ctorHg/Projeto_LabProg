<?php 

    include "connect.inc.php";
    include "estudante.class.php";

    $matricula = 0;
    $action = 'insert';
    $actionVal = 'CADASTRAR';

    if (!empty($_GET['action'])) {
        
        $action = $_GET['action'];
        $actionVal = 'ATUALIZAR';
        $matricula = $_GET['matricula'];
    }

    $estudante = new Estudante($conn);

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
    $nameErr = "Name is required";
    $EstaValido = False;
  } else {
    $nome = test_input($_POST["nome"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
        $nameErr = "Only letters and white space allowed";
        $valName = "ERRADO";
        $EstaValido = False;
    } else {
        $valName = "CERTO";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
    $EstaValido = False;
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $valEmail = "ERRADO";
        $EstaValido = False;
    } else {
        $valEmail = "CERTO";
    }
  }

  if (empty($_POST["senha"])) {
    $senha = "";
    $EstaValido = False;
    $senErr = "Senha requerida";
  } else {
    $senha = test_input($_POST["senha"]);
    
        $valsenha = "CERTO";
    
  }

  if (empty($_POST["matricula"])) {
    $matricula = "";
    $EstaValido = False;
    $matriErr = "Matricula requerida";
  } else {
    $matricula = test_input($_POST["matricula"]);
    
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
        $estudante->create($_POST);
    } else if ($action == 'update') {
        $estudante->update($_POST);
    }
    

    header('Location: login.php');
  }
?>

<html>
    <head>
        <title>Cadastro</title>
        <link rel="stylesheet" href="./estilos/styles.css">
        <style>.error {color: #FF0000;}</style>
    </head>
    <body>
        <!-- Conteúdo -->
         <section class="container">
            <div class="">
                <form action="<?php echo $FormularioAcao; ?>" method="post">
                    
                    <input type="hidden" name="action" value="<?php echo $action; ?>">

                    Name: <input type="text" name="nome" value="<?php echo !empty($resOne) ? $resOne[0]['nome'] : $nome; ?>">
                    <span class="error">* <?php echo $nameErr;?></span>
                    <br>
                    E-mail: <input type="text" name="email" value="<?php echo !empty($resOne) ? $resOne[0]['email'] : $email; ?>">
                    <span class="error">* <?php echo $emailErr;?></span>
                    <br>
                    Senha: <input type="text" name="senha" value="<?php echo !empty($resOne) ? $resOne[0]['senha'] : $senha; ?>">
                    
                    <br>
                    Matricula: <input type="text" name="matricula" value="<?php echo !empty($resOne) ? $resOne[0]['matricula'] : $matricula; ?>">
                    
                    <br>
                    <input type="submit" name="submit" value="<?php echo $actionVal ?>">
                    <a href="login.php" class="btn btn-secondary">Login</a>  
                    <input type="hidden" name="valMat" value="">
                    <input type="hidden" name="valName" value="<?php echo $valName; ?>">
                    <input type="hidden" name="valEmail" value="<?php echo $valEmail; ?>">
                    <input type="hidden" name="valSenha" value="">
                </form>
            </div>            
         </section>  
        <!-- Lista -->
        <section class="container">
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
        </section>
    </body>

</html>