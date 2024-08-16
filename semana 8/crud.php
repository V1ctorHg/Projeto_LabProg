<?php 

    include "connect.inc.php";
    include "alunos.class.php";

    $id = 0;

    if (empty($_GET['action'])) {
        $action = 'insert';
        $actionVal = 'CADASTRAR';
    } else {
        $action = $_GET['action'];
        $actionVal = 'ATUALIZAR';
        $id = $_GET['ID'];
    }

    $aluno = new Aluno($conn);

    $res = $aluno->read();

    //$result = $aluno->read();
    //$result = $aluno->readOne(1);

    //print("<pre>");
    //var_dump($result);
?>


<?php
$nameErr = $emailErr = $telErr = $cpfErr = "";
$nome = $email = $telefone = $cpf = "";
$valName = $valEmail = $valTel = $valCpf = "";
$formAction = "crud.php";
$button = "Enviar";
$isValid = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $isValid = True;

  if (empty($_POST["nome"])) {
    $nameErr = "Name is required";
    $isValid = False;
  } else {
    $nome = test_input($_POST["nome"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$nome)) {
        $nameErr = "Only letters and white space allowed";
        $valName = "Incorreto";
        $isValid = False;
    } else {
        $valName = "Correto";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
    $isValid = False;
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $valEmail = "Incorreto";
        $isValid = False;
    } else {
        $valEmail = "Correto";
    }
  }

  if (empty($_POST["telefone"])) {
    $telefone = "";
  } else {
    $telefone = test_input($_POST["telefone"]);
    if (validarTelefone($telefone)) {
        $valTel = "Correto";
    } else {
        $valTel = "Incorreto";
        $telErr = "Invalid telephone format";
        $isValid = False;
    }
  }

  if (empty($_POST["cpf"])) {
    $cpf = "";
  } else {
    $cpf = test_input($_POST["cpf"]);
    if (validarCPF($cpf)) {
        $valCpf = "Correto";
    } else {
        $valCpf = "Incorreto";
        $cpfErr = "Invalid CPF format";
        $isValid = False;
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

if ($isValid) {

    if (empty($_POST['action'])) {
        $action = $_GET['action'];
        $id = $_GET['id'];
    } else {
        $action = $_POST['action'];
    }

    if ($action == 'insert') {
        $aluno->create($_POST);
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
                <form action="<?php echo $formAction; ?>" method="post">
                    <input type="hidden" name="ID" value="">
                    <input type="hidden" name="action" value="<?php echo $action; ?>">

                    Name: <input type="text" name="nome" value="<?php echo $nome; ?>">
                    <span class="error">* <?php echo $nameErr;?></span>
                    <br><br>
                    E-mail: <input type="text" name="email" value="<?php echo $email; ?>">
                    <span class="error">* <?php echo $emailErr;?></span>
                    <br><br>
                    Telefone: <input type="text" name="telefone" value="<?php echo $telefone; ?>">
                    <span class="error"><?php echo $telErr;?></span>
                    <br><br>
                    CPF: <input type="text" name="cpf" value="<?php echo $cpf; ?>">
                    <span class="error"><?php echo $cpfErr;?></span>
                    <br><br>
                    <input type="submit" name="submit" value="<?php echo $button ?>">  
                    <input type="hidden" name="valTel" value="<?php echo $valTel; ?>">
                    <input type="hidden" name="valName" value="<?php echo $valName; ?>">
                    <input type="hidden" name="valEmail" value="<?php echo $valEmail; ?>">
                    <input type="hidden" name="valCpf" value="<?php echo $valCpf; ?>">
                </form>
            </div>            
         </section>         
<br><br><br><br><br><br>
        <!-- Lista -->
        <section class="container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Cpf</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
<?php 
    foreach ($res as $r) {
        echo ("
            <tr>
                <td>{$r['ID']}</td>
                <td>{$r['Nome']}</td>
                <td>{$r['Email']}</td>
                <td>{$r['Telefone']}</td>
                <td>{$r['Cpf']}</td>
                <td>E</td>
                <td>X</td>
            </tr>");
    }
?>
            </table>
        </section>
    </body>

</html>