CREATE TABLE IF NOT EXISTS  `Orders`
(
    `id`         int auto_increment not null,
    `user_id`    int,
    `total_price` DECIMAL(8,2), -- 233.12*100 for ex, is 23312.00 = 7 digits in total
    `created`    timestamp default current_timestamp,
    `payment_method` VARCHAR(100),
    `address` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES Users(`id`), -- a user can have more than one order so I want to allow it and not make user_id unique
    CONSTRAINT CHK_Order CHECK (`total_price` > 000000.00 AND `payment_method`='Cash' OR `payment_method`='Visa' OR `payment_method`='MasterCard' OR `payment_method`='Amex')
);