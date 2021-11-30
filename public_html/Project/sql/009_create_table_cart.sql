CREATE TABLE IF NOT EXISTS  `Cart`
(
    `id`         int auto_increment not null,
    `product_id`    int,
    `user_id`    int,
    -- add desired_quentity and unit_cost here 
    `desired_quantity` int DEFAULT 1, -- if user adds an item to the cart that means he/she wants at least one of It
    `unit_cost` DECIMAL(7,2), -- 233.12*100 for ex, is 23312.00 = 7 digits in total
    -- `role_id`  int,
    -- `is_active`  TINYINT(1) default 1,
    `created`    timestamp default current_timestamp,
    `modified`   timestamp default current_timestamp on update current_timestamp,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES Users(`id`),
    FOREIGN KEY (`product_id`) REFERENCES Products(`id`)
    -- UNIQUE KEY (`user_id`, `role_id`)
)