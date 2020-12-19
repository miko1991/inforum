CREATE TABLE IF NOT EXISTS setting_groups (
    id int AUTO_INCREMENT NOT NULL,
    title varchar(255) NOT NULL,
    description longtext NULL,
    PRIMARY KEY (id)
);