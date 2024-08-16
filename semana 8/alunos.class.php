<?php

class Aluno {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $cpf;

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($post) {
        $sql = "INSERT INTO aluno (Nome, Email, Telefone, Cpf) VALUES
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
        $sql = "SELECT * FROM aluno";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($id) {
        $sql = "SELECT * FROM aluno WHERE ID = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }


}

?>