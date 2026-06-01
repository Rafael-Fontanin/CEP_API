-- ============================================================
--  Banco de dados: pwiiib
--  Tabela: endereco
--  Executar no phpMyAdmin (XAMPP) antes de usar o projeto
-- ============================================================

CREATE DATABASE IF NOT EXISTS pwiiib
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE pwiiib;

CREATE TABLE IF NOT EXISTS `endereco` (
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `cep`         VARCHAR(8)   NOT NULL,
    `logradouro`  VARCHAR(150) NOT NULL,
    `bairro`      VARCHAR(100) NOT NULL,
    `cidade`      VARCHAR(100) NOT NULL,
    `uf`          CHAR(2)      NOT NULL,
    `pais`        VARCHAR(50)  NOT NULL DEFAULT 'Brasil',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
