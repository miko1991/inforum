CREATE TABLE IF NOT EXISTS plugin_widgets (
    id int AUTO_INCREMENT NOT NULL,
    plugin_id int NOT NULL,
    class_name varchar(255) NOT NULL,
    title varchar(255) NOT NULL,
    resource_id int NULL,
    table_name varchar(255) NULL,
    PRIMARY KEY (id)
);