<?php
    session_start();
    include "connect.inc.php";
    include "estudante.class.php";
    
    $estudante = new Estudante($conn);

    $res = $estudante->read();
?>



<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilos/organizador.css">
    <title>RELATÓRIO — ESTUDANTES</title>
</head>
<body>
    <div class="container_general">
        <header id="fixed_header">
            <div class="container_top_info"></div>
            <nav class="side_menu">
                <ul class="menu_list">
                    <?php echo '<li><a class="about_link" href="inicioAdministrador.php">Voltar</a></li>'; ?>
                </ul>
            </nav>
        </header>
        
        <main id="rel_stud_main">
            <div class="container_table">
                <h1>Relatório dos Estudantes</h1>
                <div class="table_info">
                    <table class="table">
                        <tr>
                            <th>Matricula</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Senha</th>
                        </tr>
                    
                        <?php
                        foreach ($res as $r) {
                            echo ("
                                <tr>
                                    <td>{$r['matricula']}</td>
                                    <td>{$r['nome']}</td>
                                    <td>{$r['email']}</td>
                                    <td>{$r['senha']}</td>
                                </tr>");
                            }
                        ?>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>