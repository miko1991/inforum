CREATE TABLE IF NOT EXISTS routes (
    id int AUTO_INCREMENT NOT NULL,
    uri varchar(255) NOT NULL,
    controller varchar(255) NOT NULL,
    function varchar(255) NOT NULL,
    method varchar(255) NOT NULL,
    middlewares longtext NULL,
    PRIMARY KEY (id)
);