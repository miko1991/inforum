CREATE TABLE IF NOT EXISTS `plugins` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `namespace` varchar(255) NOT NULL DEFAULT '',
    `default_uri` varchar(255) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;