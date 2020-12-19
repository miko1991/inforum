CREATE TABLE IF NOT EXISTS users (
    id int AUTO_INCREMENT NOT NULL,
    displayName varchar(255) UNIQUE NOT NULL,
    email varchar(255) UNIQUE NOT NULL,
    password varchar(255) NOT NULL,
    group_id int NOT NULL,
    token varchar(255) NULL,
    PRIMARY KEY (id)
);