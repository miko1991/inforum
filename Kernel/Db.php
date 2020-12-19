<?php

namespace Kernel;
use mysql_xdevapi\Exception;
use PDO;

class Db {
    public PDO $connection;
    private array $args = [
        "host" => "",
        "database" => "",
        "user" => "",
        "password" => ""
    ];

    public function __construct($host, $dbname, $dbuser, $dbpwd) {
        $this->args["host"] = $host;
        $this->args["database"] = $dbname;
        $this->args["user"] = $dbuser;
        $this->args["password"] = $dbpwd;

        return $this->connect();
    }

    public function connect() {
        try {
            $dsn = "mysql:host=".$this->args["host"].";dbname=".$this->args["database"];
            $this->connection = new PDO($dsn, $this->args["user"], $this->args["password"]);
            $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (\Exception $exception) {
            return false;
        }
    }

}