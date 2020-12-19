CREATE TABLE IF NOT EXISTS forums (
    id int AUTO_INCREMENT NOT NULL,
    title varchar(255) NOT NULL,
    parent_id int NULL,
    children_ids longtext NULL,
    position int NULL,
    PRIMARY KEY (id)
);