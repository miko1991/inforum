CREATE TABLE IF NOT EXISTS plugin_routes (
    id int AUTO_INCREMENT NOT NULL,
    plugin_id int NOT NULL,
    uri varchar(255) NOT NULL,
    controller varchar(255) NOT NULL,
    function varchar(255) NOT NULL,
    method varchar(50) NOT NULL,
    middlewares longtext NULL,
    PRIMARY KEY (id)
);