<?php

class Evento {
    private $cod_evento;
    private $nome;
    private $descricao;
    private $datahora_ini;
    private $datahora_fim;
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($post) {
        $sql = "INSERT INTO evento (nome, descricao, datahora_ini, datahora_fim) VALUES
        ('{$post['nome']}', '{$post['descricao']}', '{$post['datahora_ini']}', '{$post['datahora_fim']}')";
        print($sql);
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            echo "ERRO: $sql<br>".$this->conn->error."<br>";
            return false;
        }
    }

    public function read() {
        $sql = "SELECT cod_evento, nome, descricao, DATE_FORMAT(datahora_ini, '%d/%m/%Y %H:%i:%s') AS datahora_ini, DATE_FORMAT(datahora_fim, '%d/%m/%Y %H:%i:%s') AS datahora_fim FROM evento";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($cod_evento) {
        $sql = "SELECT cod_evento, nome, descricao, DATE_FORMAT(datahora_ini, '%d/%m/%Y %H:%i:%s') AS datahora_ini, DATE_FORMAT(datahora_fim, '%d/%m/%Y %H:%i:%s') AS datahora_fim
        FROM evento WHERE cod_evento = $cod_evento";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete($cod_evento){
        $sql = "DELETE FROM evento WHERE cod_evento = $cod_evento";
        $result = $this->conn->query($sql);
        
        return $result;
    }

    public function update($post){

        $sql = "UPDATE evento
                SET nome = '{$post['nome']}',
                    descricao = '{$post['descricao']}',
                    datahora_ini = '{$post['datahora_ini']}',
                    datahora_fim = '{$post['datahora_fim']}'
                WHERE cod_evento = {$post['cod_evento']}";

        $result = $this->conn->query($sql);

        return $result;
    }

    
}
?>