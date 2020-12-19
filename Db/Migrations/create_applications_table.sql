CREATE TABLE IF NOT EXISTS applications (
    id int AUTO_INCREMENT NOT NULL,
    title varchar(255) NOT NULL,
    prop varchar(255) UNIQUE NOT NULL,
    locked tinyint NULL,
    enabled tinyint NOT NULL,
    position int NULL,
    PRIMARY KEY (id)
);