<?php

class Curso {
    private $cod_curso;
    private $nome;
    private $datahora_ini;
    private $datahora_fim;
    private $horas;
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($post) {
        $sql = "INSERT INTO curso (nome, datahora_ini, datahora_fim, horas) VALUES
        ('{$post['nome']}', '{$post['datahora_ini']}', '{$post['datahora_fim']}', '{$post['horas']}')";
        print($sql);
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            echo "ERRO: $sql<br>".$this->conn->error."<br>";
            return false;
        }
    }

    public function read() {
        $sql = "SELECT cod_curso, nome, DATE_FORMAT(datahora_ini, '%d/%m/%Y %H:%i:%s') AS datahora_ini, DATE_FORMAT(datahora_fim, '%d/%m/%Y %H:%i:%s') AS datahora_fim, horas
                FROM curso";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($cod_curso) {
        $sql = "SELECT * FROM curso WHERE cod_curso = $cod_curso";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    public function delete($cod_curso) {
        $sql = "DELETE FROM curso WHERE cod_curso = $cod_curso";
        $result = $this->conn->query($sql);
        
        return $result;
    }

    public function update($post){

        $sql = "UPDATE curso
                SET nome = '{$post['nome']}',
                    datahora_ini = '{$post['datahora_ini']}',
                    datahora_fim = '{$post['datahora_fim']}',
                    horas= '{$post['horas']}'
                WHERE cod_curso = {$post['cod_curso']}";

        $result = $this->conn->query($sql);

        return $result;
    }

    
}
?>