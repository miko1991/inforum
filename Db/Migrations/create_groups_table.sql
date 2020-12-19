CREATE TABLE IF NOT EXISTS groups (
    id int AUTO_INCREMENT NOT NULL,
    title varchar(255) NOT NULL,
    is_root tinyint NULL,
    permission_set_id int NOT NULL,
    PRIMARY KEY (id)
);