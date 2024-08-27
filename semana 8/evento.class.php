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

    public function readorg($orgmat) {
        $sql = "SELECT evento.cod_evento, nome, descricao, DATE_FORMAT(datahora_ini, '%d/%m/%Y %H:%i:%s') AS datahora_ini, DATE_FORMAT(datahora_fim, '%d/%m/%Y %H:%i:%s') AS datahora_fim 
                FROM evento
                JOIN organizador_evento ON organizador_evento.cod_evento = evento.cod_evento
                WHERE organizador_evento.mat_organizador = $orgmat";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readOne($cod_evento) {
        $sql = "SELECT *
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
    try {
        // Prepara a consulta SQL
        $stmt = $this->conn->prepare("UPDATE evento
                                       SET nome = ?, 
                                           descricao = ?, 
                                           datahora_ini = ?, 
                                           datahora_fim = ?
                                       WHERE cod_evento = ?");
        
        // Vincula os parâmetros
        $stmt->bind_param("ssssi", 
                          $post['nome'], 
                          $post['descricao'], 
                          $post['datahora_ini'], 
                          $post['datahora_fim'], 
                          $post['cod_evento']);
        
        // Executa a consulta
        $stmt->execute();
        
        // Verifica se a consulta foi bem-sucedida
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        // Captura e retorna a mensagem de erro
        return "Erro ao atualizar o evento: " . $e->getMessage();
    }
}


    
}
?>