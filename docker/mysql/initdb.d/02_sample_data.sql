-- サンプルデータの挿入

USE sample_db;

-- ユーザーデータ
INSERT INTO users (username, email, password_hash) VALUES
('user1', 'user1@example.com', '$2y$10$XNdQJLO71GUQ8lnLQqv3G.LF8ZfD.7V3JpIXS2qY7YZzUbcZYNY2m'), -- password: password123
('user2', 'user2@example.com', '$2y$10$XNdQJLO71GUQ8lnLQqv3G.LF8ZfD.7V3JpIXS2qY7YZzUbcZYNY2m'), -- password: password123
('user3', 'user3@example.com', '$2y$10$XNdQJLO71GUQ8lnLQqv3G.LF8ZfD.7V3JpIXS2qY7YZzUbcZYNY2m'); -- password: password123

-- 商品データ
INSERT INTO products (name, description, price, stock) VALUES
('ノートパソコン', '高性能なビジネス向けノートPC', 120000.00, 50),
('スマートフォン', '最新モデルのスマートフォン', 85000.00, 100),
('ワイヤレスイヤホン', 'ノイズキャンセリング機能付き', 15000.00, 200),
('タブレット', '10インチディスプレイ搭載', 45000.00, 30),
('スマートウォッチ', '健康管理機能付き', 25000.00, 75);

-- 注文データ
INSERT INTO orders (user_id, total_amount, status) VALUES
(1, 120000.00, 'delivered'),
(1, 15000.00, 'shipped'),
(2, 85000.00, 'processing'),
(3, 70000.00, 'pending');

-- 注文詳細データ
INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES
(1, 1, 1, 120000.00),
(2, 3, 1, 15000.00),
(3, 2, 1, 85000.00),
(4, 3, 2, 15000.00),
(4, 5, 1, 25000.00);
