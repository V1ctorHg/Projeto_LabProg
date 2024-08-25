<?php

class Organizador {
    private $matricula_organizador;
    private $nome;
    private $email;
    private $senha;

    private $conn;

    

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($post) {
        $sql = "INSERT INTO organizador (matricula_organizador, nome, email, senha) VALUES
        ('{$post['matricula']}','{$post['nome']}', '{$post['email']}', '{$post['senha']}')";
        print($sql);
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            echo "ERRO: $sql<br>".$this->conn->error."<br>";
            return false;
        }
    }

    public function read() {
        $sql = "SELECT * FROM organizador";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($matricula_organizador) {
        $sql = "SELECT * FROM organizador WHERE matricula_organizador = $matricula_organizador";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($matricula_organizador){
        $sql = "DELETE FROM organizador WHERE matricula_organizador = $matricula_organizador";
        $result = $this->conn->query($sql);
        
        return $result;
    }


    public function update($post){

        $sql = "UPDATE organizador
                SET nome = '{$post['nome']}',
                    email = '{$post['email']}',
                    senha = '{$post['senha']}'
                WHERE matricula_organizador = {$post['matricula_organizador']}";

        $result = $this->conn->query($sql);

        return $result;
    }

    
}




