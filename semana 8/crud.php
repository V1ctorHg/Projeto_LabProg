<?php 

    include "connect.inc.php";
    include "alunos.class.php";

    $id = 0;
    $action = 'insert';
    $actionVal = 'CADASTRAR';

    if (!empty($_GET['action'])) {
        
        $action = $_GET['action'];
        $actionVal = 'ATUALIZAR';
        $id = $_GET['id'];
    }

    $aluno = new Aluno($conn);

    $res = $aluno->read();
    


    $resOne = $aluno->readOne('-1');

    

    if($action =='update'){
        $resOne = $aluno->readOne($id);
    }
    
?>


<?php
$nameErr = $emailErr = $telErr = $cpfErr = "";
$nome = $email = $telefone = $cpf = "";
$valName = $valEmail = $valTel = $valCpf = "";
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

  if (empty($_POST["telefone"])) {
    $telefone = "";
    $EstaValido = False;
    $telErr = "Telefone requerido";
  } else {
    $telefone = test_input($_POST["telefone"]);
    if (validarTelefone($telefone)) {
        $valTel = "CERTO";
    } else {
        $valTel = "ERRADO";
        $telErr = "Invalid telephone format";
        $EstaValido = False;
    }
  }

  if (empty($_POST["cpf"])) {
    $cpf = "";
    $EstaValido = False;
    $cpfErr = "CPF requerido";
  } else {
    $cpf = test_input($_POST["cpf"]);
    if (validarCPF($cpf)) {
        $valCpf = "CERTO";
    } else {
        $valCpf = "ERRADO";
        $cpfErr = "Invalid CPF format";
        $EstaValido = False;
    }
  }
}

function validarTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
        return false;
    }
    if (!preg_match('/^(?:\d{10}|\d{11})$/', $telefone)) {
        return false;
    }
    return true;
}

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11) {
        return false;
    }
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($i = 9; $i < 11; $i++) {
        $sum = 0;
        for ($j = 0; $j < $i; $j++) {
            $sum += $cpf[$j] * (($i + 1) - $j);
        }
        $sum = ((10 * $sum) % 11) % 10;
        if ($cpf[$j] != $sum) {
            return false;
        }
    }
    return true;
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
        $id = $_GET['id'];
    } else {
        $action = $_POST['action'];
    }

    if ($action == 'insert') {
        $aluno->create($_POST);
    } else if ($action == 'update') {
        $aluno->update($_POST);
    }
    

    header('Location: crud.php');
  }
?>

<html>
    <head>
        <title>CRUD</title>
        <link rel="stylesheet" href="styles.css">
        <style>.error {color: #FF0000;}</style>
    </head>
    <body>
        <!-- Conteúdo -->
         <section class="container">
            <div class="">
                <form action="<?php echo $FormularioAcao; ?>" method="post">
                    <input type="hidden" name="ID" value="<?php echo !empty($resOne) ? $resOne[0]['ID'] : ''; ?>">
                    <input type="hidden" name="action" value="<?php echo $action; ?>">

                    Name: <input type="text" name="nome" value="<?php echo !empty($resOne) ? $resOne[0]['nome'] : $nome; ?>">
                    <span class="error">* <?php echo $nameErr;?></span>
                    <br>
                    E-mail: <input type="text" name="email" value="<?php echo !empty($resOne) ? $resOne[0]['email'] : $email; ?>">
                    <span class="error">* <?php echo $emailErr;?></span>
                    <br>
                    Telefone: <input type="text" name="telefone" value="<?php echo !empty($resOne) ? $resOne[0]['telefone'] : $telefone; ?>">
                    <span class="error"><?php echo $telErr;?></span>
                    <br>
                    CPF: <input type="text" name="cpf" value="<?php echo !empty($resOne) ? $resOne[0]['cpf'] : $cpf; ?>">
                    <span class="error"><?php echo $cpfErr;?></span>
                    <br>
                    <input type="submit" name="submit" value="<?php echo $actionVal ?>">  
                    <input type="hidden" name="valTel" value="<?php echo $valTel; ?>">
                    <input type="hidden" name="valName" value="<?php echo $valName; ?>">
                    <input type="hidden" name="valEmail" value="<?php echo $valEmail; ?>">
                    <input type="hidden" name="valCpf" value="<?php echo $valCpf; ?>">
                </form>
            </div>            
         </section>  
        <!-- Lista -->
        <section class="container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>nome</th>
                    <th>e-mail</th>
                    <th>telefone</th>
                    <th>CPF</th>
                    <th>Editar</th>
                    <th>Deletar</th>
                </tr>
<?php 
    foreach ($res as $r) {
        echo ("
            <tr>
                <td>{$r['ID']}</td>
                <td>{$r['nome']}</td>
                <td>{$r['email']}</td>
                <td>{$r['telefone']}</td>
                <td>{$r['cpf']}</td>
                <td><a href='crud.php?action=update&id={$r['ID']}'>E</a></td>
                <td><a href='formAction.php?action=delete&id={$r['ID']}'>X</a></td>
            </tr>");
    }
?>
            </table>
        </section>
    </body>

</html>