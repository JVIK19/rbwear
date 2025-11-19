-- Script para inserir produtos com imagens V2 (hover effect)
-- Execute depois de criar a coluna imagem_hover

-- Inserir produtos com V2 (hover effect)
INSERT INTO produtos (nome_produto, descricao_prod, preco, categoria_id, imagem_url, ativo, imagem_hover_url) VALUES

-- Camisetas com V2
('Camiseta Blunt', 'Camiseta estampa exclusiva Blunt, algodão premium, modelo unissex.', '79.90', '1', '/RBWEAR_SITE/public/assets/camisabluntV1.jpeg', '1', '/RBWEAR_SITE/public/assets/camisabluntV2.jpeg'),

('Camiseta Jordan', 'Camiseta estilo Jordan com estampa moderna, tecido confortável.', '89.90', '1', '/RBWEAR_SITE/public/assets/camisajordanV1.jpeg', '1', '/RBWEAR_SITE/public/assets/camisajordanV2.jpeg'),

('Camiseta Sapato', 'Camiseta com estampa Sapato, design urbano e moderno.', '69.90', '1', '/RBWEAR_SITE/public/assets/camisasapatoV1.jpeg', '1', '/RBWEAR_SITE/public/assets/camisasapatoV2.jpeg'),

('High Scorp', 'Camiseta High Scorp edição especial, estampa exclusiva.', '99.90', '1', '/RBWEAR_SITE/public/assets/highscorpV1.jpeg', '1', '/RBWEAR_SITE/public/assets/highscorpV2.jpeg'),

('Nike Classic', 'Camiseta Nike clássica, qualidade premium e conforto.', '129.90', '1', '/RBWEAR_SITE/public/assets/nikeV1.jpeg', '1', '/RBWEAR_SITE/public/assets/nikeV2.jpeg'),

('Roxa Street', 'Camiseta Roxa estilo streetwear, design moderno.', '59.90', '1', '/RBWEAR_SITE/public/assets/roxaV1.jpeg', '1', '/RBWEAR_SITE/public/assets/roxaV2.jpeg'),

('Santa Cruz', 'Camiseta Santa Cruz, estilo californiano, algodão 100%.', '79.90', '1', '/RBWEAR_SITE/public/assets/santacruzV1.jpeg', '1', '/RBWEAR_SITE/public/assets/santacruzV2.jpeg'),

('Trip Side', 'Camiseta Trip Side com estampa artística, edição limitada.', '89.90', '1', '/RBWEAR_SITE/public/assets/tripsideV1.jpeg', '1', '/RBWEAR_SITE/public/assets/tripsideV2.jpeg'),

('Tugh Nine', 'Camiseta Tugh Nine, design exclusivo RB Wear.', '74.90', '1', '/RBWEAR_SITE/public/assets/tughnineV1.jpeg', '1', '/RBWEAR_SITE/public/assets/tughnineV2.jpeg'),

('URG Street', 'Camiseta URG estilo urbano, confortável e resistente.', '69.90', '1', '/RBWEAR_SITE/public/assets/urgV1.jpeg', '1', '/RBWEAR_SITE/public/assets/urgV2.jpeg'),

-- Calças com V2
('Calça Baggy Black', 'Calça baggy preta, estilo streetwear, confortável.', '199.90', '2', '/RBWEAR_SITE/public/assets/calcabaggyV1.jpeg', '1', '/RBWEAR_SITE/public/assets/calcabaggyV2.jpeg'),

('Calça Cruz', 'Calça estilo cruz, design moderno e versátil.', '179.90', '2', '/RBWEAR_SITE/public/assets/calcacruzV1.jpeg', '1', '/RBWEAR_SITE/public/assets/calcacruzV2.jpeg'),

('Calça Marrom', 'Calça marrom casual, tecido resistente e confortável.', '169.90', '2', '/RBWEAR_SITE/public/assets/calcamarromV1.jpeg', '1', '/RBWEAR_SITE/public/assets/calcamarromV2.jpeg'),

('Calça Preta', 'Calça preta básica, ideal para o dia a dia.', '159.90', '2', '/RBWEAR_SITE/public/assets/calcapretaV1.jpeg', '1', '/RBWEAR_SITE/public/assets/calcapretaV2.jpeg');

-- Inserir produtos sem V2 (sem hover effect)
INSERT INTO produtos (nome_produto, descricao_prod, preco, categoria_id, imagem_url, ativo) VALUES

-- Regatas (sem V2)
('Regata Black', 'Regata preta básica, tecido leve e respirável.', '39.90', '1', '/RBWEAR_SITE/public/assets/regatablack.jpeg', '1'),

('Regata White', 'Regata branca básica, ideal para dias quentes.', '39.90', '1', '/RBWEAR_SITE/public/assets/regatawhite.jpeg', '1'),

-- Polos (sem V2)
('Polo Classic', 'Camisa Polo clássica, tecido piqué, ideal para ocasiões formais.', '119.90', '4', '/RBWEAR_SITE/public/assets/polo1.jpeg', '1'),

('Polo Sport', 'Camisa Polo esportiva, design moderno e confortável.', '109.90', '4', '/RBWEAR_SITE/public/assets/polo2.jpeg', '1'),

('Polo Premium', 'Camisa Polo premium, acabamento de alta qualidade.', '139.90', '4', '/RBWEAR_SITE/public/assets/polo3.jpeg', '1'),

-- Bermudas (sem V2)
('Bermuda Sport 1', 'Bermuda esportiva, tecido leve e confortável.', '79.90', '5', '/RBWEAR_SITE/public/assets/short1.jpeg', '1'),

('Bermuda Sport 2', 'Bermuda esportiva modelo 2, design moderno.', '84.90', '5', '/RBWEAR_SITE/public/assets/short2.jpeg', '1'),

('Bermuda Sport 3', 'Bermuda esportiva modelo 3, ideal para atividades físicas.', '89.90', '5', '/RBWEAR_SITE/public/assets/short3.jpeg', '1'),

('Bermuda Sport 4', 'Bermuda esportiva modelo 4, versátil e estilosa.', '94.90', '5', '/RBWEAR_SITE/public/assets/short4.jpeg', '1'),

('Bermuda Sport 5', 'Bermuda esportiva modelo 5, conforto máximo.', '99.90', '5', '/RBWEAR_SITE/public/assets/short5.jpeg', '1'),

-- Kits (sem V2)
('Kit Completo 1', 'Kit completo RB Wear com múltiplas peças.', '299.90', '3', '/RBWEAR_SITE/public/assets/kit1.jpeg', '1'),

('Kit Completo 2', 'Kit especial RB Wear edição limitada.', '349.90', '3', '/RBWEAR_SITE/public/assets/kit2.jpeg', '1'),

('Kit Completo 3', 'Kit básico RB Wear, peças essenciais.', '249.90', '3', '/RBWEAR_SITE/public/assets/kit3.jpeg', '1'),

('Kit Completo 4', 'Kit premium RB Wear, alta qualidade.', '399.90', '3', '/RBWEAR_SITE/public/assets/kit4.jpeg', '1'),

('Kit Completo 5', 'Kit street RB Wear, estilo urbano.', '279.90', '3', '/RBWEAR_SITE/public/assets/kit5.jpeg', '1');

-- Verificar produtos inseridos
-- SELECT * FROM produtos ORDER BY id DESC;

-- Contar produtos inseridos
-- SELECT COUNT(*) as total_produtos FROM produtos WHERE ativo = 1;

-- Verificar produtos com imagem hover
-- SELECT nome_produto, imagem, imagem_hover FROM produtos WHERE imagem_hover IS NOT NULL;
