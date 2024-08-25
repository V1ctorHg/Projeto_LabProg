<?php
    include "connect.inc.php";
    include "estudante.class.php";

    session_start();

    if(isset ($_SESSION['matricula'])){
        $matricula = $_SESSION['matricula'];
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
                    <li><a href="inicio.php">Voltar</a></li>
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