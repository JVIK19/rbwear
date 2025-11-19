-- Script para adicionar coluna de imagem hover na tabela produtos
-- Execute este script primeiro para adicionar a nova coluna

ALTER TABLE produtos ADD COLUMN imagem_hover_url VARCHAR(255) NULL AFTER imagem_url;

-- Verificar estrutura atualizada
-- DESCRIBE produtos;
