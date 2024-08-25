<?php
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

</head>
<body>
<section class="infos">                         
        
            <table>
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
</section> 


</body>
</html>