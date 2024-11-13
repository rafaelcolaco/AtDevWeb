create schema devweb2

CREATE TABLE empresas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivel_acesso ENUM('admin', 'logado') NOT NULL,
    empresa_id INT,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id)
);

CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    data_publicacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    FOREIGN KEY (empresa_id) REFERENCES empresas(id)
);


INSERT INTO `empresas` (`id`, `nome`) VALUES ('2', 'rafa');