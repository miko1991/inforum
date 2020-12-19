CREATE TABLE IF NOT EXISTS recipes (
    id int AUTO_INCREMENT NOT NULL,
    title varchar(255) NOT NULL,
    preparation_time int NOT NULL,
    cooking_time int NOT NULL,
    difficulty varchar(50) NOT NULL,
    ingredients longtext NOT NULL,
    tags longtext NULL,
    method longtext NOT NULL,
    PRIMARY KEY (id)
);