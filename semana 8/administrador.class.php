<?php

class Administrador {
    private $matricula_admin;
    private $nome;
    private $email;
    private $senha;
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($post) {
        $sql = "INSERT INTO administrador (nome, email, senha) VALUES
        ('{$post['nome']}', '{$post['email']}', '{$post['senha']}')";
        print($sql);
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            echo "ERRO: $sql<br>".$this->conn->error."<br>";
            return false;
        }
    }

    public function read() {
        $sql = "SELECT * FROM administrador";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($matricula_admin) {
        $sql = "SELECT * FROM administrador WHERE matricula_admin = $matricula_admin";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($matricula_admin){
        $sql = "DELETE FROM administrador WHERE matricula_admin = $matricula_admin";
        $result = $this->conn->query($sql);
        
        return $result;
    }

    public function update($post){
        $sql = "UPDATE administrador
                SET nome = '{$post['nome']}',
                    email = '{$post['email']}',
                    senha = '{$post['senha']}'
                WHERE matricula_admin = {$post['matricula_admin']}";

        $result = $this->conn->query($sql);

        return $result;
    }

    
}
?>