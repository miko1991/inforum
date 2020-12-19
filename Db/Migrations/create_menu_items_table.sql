CREATE TABLE IF NOT EXISTS menu_items (
    id int AUTO_INCREMENT NOT NULL,
    menu_id int NOT NULL,
    uri varchar(255) NOT NULL,
    title varchar(255) NOT NULL,
    display_conditions longtext NULL,
    PRIMARY KEY (id)
);