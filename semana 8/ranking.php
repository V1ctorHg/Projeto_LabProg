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

<!DOCTYPE html>
<html>  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/ranking2.css">
    <title>RANKINGS</title>
</head>
<body>
    <div class="container_general">
        <header>
            <div class="container_top_info"></div>
            <nav class="side_menu">
                <ul class="menu_list">
                    <?php
                        if($_SESSION['tipouser'] == 'estudante'){
                            echo "<li><a class='about_link' href='inicio.php'>Voltar</a></li>";
                        } elseif ($_SESSION['tipouser'] == 'organizador'){
                            echo "<li><a class='about_link' href='inicioOrganizador.php'>Voltar</a></li>";
                        }elseif ($_SESSION['tipouser'] == 'administrador'){
                            echo "<li><a class='about_link' href='inicioAdministrador.php'>Voltar</a></li>";
                        }
                    ?>
                </ul>
            </nav>
        </header>

        <main>
            <section id="ranking">
                <h1>Ranking</h1>
                <div class="container_table">
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
            </section>
        </main>
    </div>
</body>
</html>