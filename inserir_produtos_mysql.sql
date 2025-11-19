-- Script para inserir produtos na tabela produtos
-- Use este script no MySQL Workbench, phpMyAdmin ou linha de comando

-- Verificar estrutura da tabela (opcional)
-- DESCRIBE produtos;

-- Inserir produtos de exemplo para RB Wear
INSERT INTO produtos (nome_produto, descricao_prod, preco, categoria_id, imagem, ativo) VALUES

('Camiseta Jordan', 'Camiseta estilo Jordan com estampa moderna, tecido confortável.', 89.90, 1, 'camisajordanV1.jpeg', 1),

('Camiseta Sapato', 'Camiseta com estampa Sapato, design urbano e moderno.', 69.90, 1, 'camisasapatoV1.jpeg', 1),

('High Scorp', 'Camiseta High Scorp edição especial, estampa exclusiva.', 99.90, 1, 'highscorpV1.jpeg', 1),

('Nike Classic', 'Camiseta Nike clássica, qualidade premium e conforto.', 129.90, 1, 'nikeV1.jpeg', 1),

('Roxa Street', 'Camiseta Roxa estilo streetwear, design moderno.', 59.90, 1, 'roxaV1.jpeg', 1),

('Santa Cruz', 'Camiseta Santa Cruz, estilo californiano, algodão 100%.', 79.90, 1, 'santacruzV1.jpeg', 1),

('Trip Side', 'Camiseta Trip Side com estampa artística, edição limitada.', 89.90, 1, 'tripsideV1.jpeg', 1),

('Tugh Nine', 'Camiseta Tugh Nine, design exclusivo RB Wear.', 74.90, 1, 'tughnineV1.jpeg', 1),

('URG Street', 'Camiseta URG estilo urbano, confortável e resistente.', 69.90, 1, 'urgV1.jpeg', 1),

('Polo Classic', 'Camisa Polo clássica, tecido piqué, ideal para ocasiões formais.', 119.90, 4, 'polo1.jpeg', 1),

('Polo Sport', 'Camisa Polo esportiva, design moderno e confortável.', 109.90, 4, 'polo2.jpeg', 1),

('Polo Premium', 'Camisa Polo premium, acabamento de alta qualidade.', 139.90, 4, 'polo3.jpeg', 1),

('Regata Black', 'Regata preta básica, tecido leve e respirável.', 39.90, 1, 'regatablack.jpeg', 1),

('Regata White', 'Regata branca básica, ideal para dias quentes.', 39.90, 1, 'regatawhite.jpeg', 1),

('Calça Baggy Black', 'Calça baggy preta, estilo streetwear, confortável.', 199.90, 2, 'calcabaggyV1.jpeg', 1),

('Calça Cruz', 'Calça estilo cruz, design moderno e versátil.', 179.90, 2, 'calcacruzV1.jpeg', 1),

('Calça Marrom', 'Calça marrom casual, tecido resistente e confortável.', 169.90, 2, 'calcamarromV1.jpeg', 1),

('Calça Preta', 'Calça preta básica, ideal para o dia a dia.', 159.90, 2, 'calcapretaV1.jpeg', 1),

('Bermuda Sport 1', 'Bermuda esportiva, tecido leve e confortável.', 79.90, 5, 'short1.jpeg', 1),

('Bermuda Sport 2', 'Bermuda esportiva modelo 2, design moderno.', 84.90, 5, 'short2.jpeg', 1),

('Bermuda Sport 3', 'Bermuda esportiva modelo 3, ideal para atividades físicas.', 89.90, 5, 'short3.jpeg', 1),

('Bermuda Sport 4', 'Bermuda esportiva modelo 4, versátil e estilosa.', 94.90, 5, 'short4.jpeg', 1),

('Bermuda Sport 5', 'Bermuda esportiva modelo 5, conforto máximo.', 99.90, 5, 'short5.jpeg', 1),

('Kit Completo 1', 'Kit completo RB Wear com múltiplas peças.', 299.90, 3, 'kit1.jpeg', 1),

('Kit Completo 2', 'Kit especial RB Wear edição limitada.', 349.90, 3, 'kit2.jpeg', 1),

('Kit Completo 3', 'Kit básico RB Wear, peças essenciais.', 249.90, 3, 'kit3.jpeg', 1),

('Kit Completo 4', 'Kit premium RB Wear, alta qualidade.', 399.90, 3, 'kit4.jpeg', 1),

('Kit Completo 5', 'Kit street RB Wear, estilo urbano.', 279.90, 3, 'kit5.jpeg', 1);

-- Verificar produtos inseridos
-- SELECT * FROM produtos ORDER BY id DESC;

-- Contar produtos inseridos
-- SELECT COUNT(*) as total_produtos FROM produtos WHERE ativo = 1;
