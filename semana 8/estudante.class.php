<?php

class Estudante {
    private $matricula;
    private $nome;
    private $email;
    private $senha;
    private $pontos;

    private $conn;

    

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($post) {
        $sql = "INSERT INTO estudante (matricula, nome, email, senha, pontos) VALUES
        ('{$post['matricula']}', '{$post['nome']}', '{$post['email']}', '{$post['senha']}', '0')";
        print($sql);
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            echo "ERRO: $sql<br>".$this->conn->error."<br>";
            return false;
        }
    }

    public function read() {
        $sql = "SELECT * FROM estudante";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($matricula) {
        $sql = "SELECT * FROM estudante WHERE matricula = $matricula";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($matricula){
        $sql = "DELETE FROM estudante WHERE matricula = $matricula";
        $result = $this->conn->query($sql);
        
        return $result;
    }


    public function update($post){

        $sql = "UPDATE estudante
                SET nome = '{$post['nome']}',
                    email = '{$post['email']}',
                    senha = '{$post['senha']}'
                WHERE matricula = {$post['matricula']}";

        $result = $this->conn->query($sql);

        return $result;
    }

    public function readPag($pag,$linhas) {
        $offset = $pag * $linhas;
        $sql = "SELECT * FROM estudante LIMIT $linhas OFFSET $offset";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}




?>