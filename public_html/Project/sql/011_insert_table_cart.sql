INSERT INTO Cart (product_id, user_id, unit_cost) VALUES
(1, 22, 5.66)
ON DUPLICATE KEY UPDATE desired_quantity = desired_quantity + 1;