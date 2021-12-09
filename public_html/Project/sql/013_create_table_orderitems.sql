CREATE TABLE IF NOT EXISTS  `OrderItems`
(
    `id`         int auto_increment not null,
    `product_id`    int,
    `order_id`    int,
    -- add quantity and unit_price here 
    `unit_price` DECIMAL(5,2),
    `quantity` int,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`order_id`) REFERENCES Orders(`id`),
    FOREIGN KEY (`product_id`) REFERENCES Products(`id`),
    check (`unit_price` > 000.00), 
    check (`quantity` > 0)
);
