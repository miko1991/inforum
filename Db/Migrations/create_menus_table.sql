CREATE TABLE IF NOT EXISTS menus (
    id int AUTO_INCREMENT NOT NULL,
    title varchar(255) NOT NULL,
    is_active tinyint NULL,
    PRIMARY KEY (id)
);