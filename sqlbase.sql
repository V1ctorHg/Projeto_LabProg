CREATE DATABASE eventcontroldb;

CREATE TABLE estudante(
    matricula INTEGER PRIMARY KEY, 
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(30) NOT NULL,
    pontos INTEGER
);

CREATE TABLE curso(
    cod_curso INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    datahora_ini TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datahora_fim TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    horas INT NOT NULL
);

CREATE TABLE evento(
    cod_evento INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao VARCHAR(300),
    datahora_ini TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    datahora_fim TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE organizador(
    matricula_organizador INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(300) NOT NULL,
    senha VARCHAR(30) NOT NULL
);

CREATE TABLE administrador(
    matricula_admin INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    email VARCHAR(300) NOT NULL,
    senha VARCHAR(30) NOT NULL
);

CREATE TABLE inscricoes(
    cod_insc INT AUTO_INCREMENT PRIMARY KEY,
    mat_estudante INTEGER NOT NULL,
    cod_curso INTEGER NOT NULL,
    
    FOREIGN KEY (mat_estudante) REFERENCES estudante(matricula),
    FOREIGN KEY (cod_curso) REFERENCES curso(cod_curso)
);

CREATE TABLE evento_curso(
    cod_evcu INT AUTO_INCREMENT PRIMARY KEY,
    cod_evento INTEGER NOT NULL,
    cod_curso INTEGER NOT NULL,
    
    FOREIGN KEY (cod_evento) REFERENCES evento(cod_evento),
    FOREIGN KEY (cod_curso) REFERENCES curso(cod_curso)
);

CREATE TABLE organizador_evento(
    cod_orgev INT AUTO_INCREMENT PRIMARY KEY,
    mat_organizador INTEGER NOT NULL,
    cod_evento INTEGER NOT NULL,
    
    FOREIGN KEY (mat_organizador) REFERENCES organizador(matricula_organizador),
    FOREIGN KEY (cod_evento) REFERENCES evento(cod_evento)
);