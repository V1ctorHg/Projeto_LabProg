<?php

class Aluno {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $CPF;

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($post) {
        $sql = "INSERT INTO alunos (nome, email, telefone, CPF) VALUES
        ('{$post['nome']}', '{$post['email']}', '{$post['telefone']}', '{$post['cpf']}')";
        print($sql);
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            echo "ERRO: $sql<br>".$this->conn->error."<br>";
            return false;
        }
    }

    public function read() {
        $sql = "SELECT * FROM alunos";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($id) {
        $sql = "SELECT * FROM alunos WHERE ID = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($id){
        $sql = "DELETE FROM alunos WHERE ID = $id";
        $result = $this->conn->query($sql);
        
        return $result;
    }


    public function update($post){

        $sql = "UPDATE alunos
                SET nome = '{$post['nome']}',
                    email = '{$post['email']}',
                    telefone = '{$post['telefone']}',
                    CPF = '{$post['cpf']}'
                WHERE ID = {$post['ID']}";

        $result = $this->conn->query($sql);

        return $result;
    }

    public function readPag($pag,$linhas) {
        $offset = $pag * $linhas;
        $sql = "SELECT * FROM alunos LIMIT $linhas OFFSET $offset";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}




?>