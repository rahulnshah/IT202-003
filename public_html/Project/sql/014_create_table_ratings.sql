CREATE TABLE IF NOT EXISTS  `Ratings`
(
    `id`         int auto_increment not null,
    `product_id`      int,
    `comment` TEXT NOT NULL,
    `user_id`   int,
    `created`    timestamp default current_timestamp,
    `rating` decimal(3,2),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES Users(`id`),
    FOREIGN KEY (`product_id`) REFERENCES Products(`id`),
    CONSTRAINT chk_rating CHECK (`rating` >= 1.00 AND `rating` <= 5.00)
)