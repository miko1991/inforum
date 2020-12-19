<?php


namespace Controllers;


use Kernel\Kernel;

class AutocompleteController
{
    public function search() {
        $table = $_GET["table"];
        $column = $_GET["column"];
        $query = $_GET["query"];

        $stmt = Kernel::$db->prepare("SELECT * FROM $table WHERE $column LIKE ?");
        $stmt->bindValue(1, "%$query%", \PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_OBJ);

        echo json_encode($results);
    }
}