-- =====================================================
-- SAEP - Modelo de CRUD
-- Importe este arquivo no phpMyAdmin (aba "Importar")
-- ou rode: mysql -u root -p < banco.sql
-- =====================================================

CREATE DATABASE IF NOT EXISTS saep_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE saep_db;

DROP TABLE IF EXISTS produtos;

CREATE TABLE produtos (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nome        VARCHAR(100)   NOT NULL,
  descricao   VARCHAR(255)   NULL,
  preco       DECIMAL(10,2)  NOT NULL DEFAULT 0,
  quantidade  INT            NOT NULL DEFAULT 0,
  criado_em   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO produtos (nome, descricao, preco, quantidade) VALUES
('Teclado Mecânico', 'Teclado ABNT2 com switch azul', 249.90, 15),
('Mouse Gamer', 'Mouse óptico 6 botões', 89.90, 30),
('Monitor 24"', 'Monitor Full HD 75Hz', 799.00, 8);
