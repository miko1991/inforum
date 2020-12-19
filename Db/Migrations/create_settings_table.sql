CREATE TABLE IF NOT EXISTS settings (
    id int AUTO_INCREMENT NOT NULL,
    prop varchar(255) UNIQUE NOT NULL,
    value longtext NULL,
    title varchar(255) NOT NULL,
    group_id int NOT NULL,
    PRIMARY KEY (id)
);