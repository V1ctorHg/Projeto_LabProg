<?php
    include "connect.inc.php";
    include "estudante.class.php";

    session_start();

    if(isset ($_SESSION['matricula']) or isset ($_SESSION['matricula_organizador'])){
        if($_SESSION['tipouser'] == 'estudante'){
            $matricula = $_SESSION['matricula'];
        } elseif ($_SESSION['tipouser'] == 'organizador'){
            $matricula = $_SESSION['matricula_organizador'];
        }   
    } else {
        header('Location: login.php');
        exit;
    }   

    $estudante = new Estudante($conn);

    $result = $estudante->readrank();
?>

<html>  
<head>
    <meta charset="UTF-8">
    <title>RANKING</title>
    <link rel="stylesheet" href="">
</head>
<body>  

    <section>
        <header>
            <nav>
                <ul>
                    <?php
                        if($_SESSION['tipouser'] == 'estudante'){
                            echo "<li><a href='inicio.php'>Voltar</a></li>";
                        } elseif ($_SESSION['tipouser'] == 'organizador'){
                            echo "<li><a href='inicioOrganizador.php'>Voltar</a></li>";
                        }elseif ($_SESSION['tipouser'] == 'administrador'){
                            echo "<li><a href='inicioAdministrador.php'>Voltar</a></li>";
                        }
                        ?>
                </ul>
            </nav>
        </header>
    </section>

    <h1>Ranking</h1>
    <div>
        <table>
            <tr>
                <th>Nome</th>
                <th>Pontos</th>
            </tr>
            <?php
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>".$row['nome']."</td>";
                    echo "<td>".$row['pontos']."</td>";
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
</body>
</html>