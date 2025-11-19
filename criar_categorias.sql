-- Script SQL para criar categorias (se ainda não existirem)
-- Execute este script primeiro se precisar criar as categorias

INSERT IGNORE INTO categorias (id, nome, created_at) VALUES
(1, 'Camisetas', NOW()),
(2, 'Calças', NOW()),
(3, 'Kits', NOW()),
(4, 'Polos', NOW()),
(5, 'Bermudas', NOW());

-- Verificar categorias criadas
-- SELECT * FROM categorias ORDER BY id;
