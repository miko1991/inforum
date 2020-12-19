<?php


namespace Kernel;

use Kernel\Kernel;
use Kernel\Model;

class QueryBuilder
{
    private string $query = "";
    private array $params = [];
    private bool $isJson = false;
    private ?Model $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function select($columns = "*", $table): QueryBuilder
    {
        $this->query = "SELECT $columns FROM $table";
        return $this;
    }

    public function insert($table = "", $arr): QueryBuilder
    {
        $table = $table ?? $this->model->table;
        $this->query = "INSERT INTO {$table}";

        $fields = '(';
        $values = ' VALUES(';
        foreach ($arr as $key => $val) {
            $fields .= "$key, ";
            $values .= "?, ";
            $this->params[] = $val;
        }

        $fields = substr($fields, 0, -2);
        $fields .= ')';

        $values = substr($values, 0, -2);
        $values .= ');';

        $this->query .= $fields . $values;
        return $this;
    }

    public function update($arr): QueryBuilder
    {
        $this->query = "UPDATE `{$this->model->table}` SET";

        $fields = '';
        $index = 0;
        foreach ($arr as $key => $val) {
            if ($index == 0) {
                $fields .= " `$key` = ?, ";
            } else {
                $fields .= " `$key` = ?, ";
            }
            $this->params[] = $val;
        }

        $fields = substr($fields, 0, -2);
        $this->query .= $fields;
        return $this;
    }

    public function where($options): QueryBuilder
    {
        if (!$this->query) {
            $this->query = "SELECT * FROM {$this->model->table}";
        }

        $index = 0;
        foreach ($options as $key => $val) {

            if ($index == 0) {
                if ($val == null) {
                    $this->query .= " WHERE $key IS NULL";
                } else {
                    $this->query .= " WHERE $key = ?";
                }
            } else {
                if ($val == null) {
                    $this->query .= " AND $key IS NULL";
                } else {
                    $this->query .= " AND $key = ?";
                }
            }

            $this->params[] = $val;
            $index++;
        }

        return $this;
    }

    public function limit($limit): QueryBuilder
    {
        $this->query .= " LIMIT $limit";
        return $this;
    }

    public function delete($table = ""): QueryBuilder
    {
        $table = $table ?? $this->model->table;
        $this->query = "DELETE FROM {$table}";
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getQuery(): string
    {
        return $this->query;
    }


    public function first(): Model
    {
        $stmt = $this->exec();
        $record = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$record) return null;
        if (!$this->model) return $record;

        $object = $this->model->bind($record);
        $object->isJson = $this->isJson;

        if (count($this->model->eagerLoads)) {
            $object->bindEagerLoads($this->model->eagerLoads);
        }

        return $object;
    }

    public function get(): array
    {

        if (!$this->query) {
            $this->query = "SELECT * FROM {$this->model->table}";
        }

        $stmt = $this->exec($this->params);
        $records = $stmt->fetchAll(\PDO::FETCH_OBJ);


        $objects = $this->model->bindMany($records);
        if (count($this->model->eagerLoads)) {
            foreach ($objects as $object) {
                $object->isJson = $this->isJson;
                $object->bindEagerLoads($this->model->eagerLoads);
            }
        }

        return $objects;
    }

    public function exec()
    {
        $stmt = Kernel::$db->prepare($this->getQuery());
        $stmt->execute($this->getParams());
        return $stmt;
    }

    public function with($relationships): QueryBuilder
    {
        foreach ($relationships as $relationship) {
            $this->model->eagerLoads[] = $relationship;
        }
        return $this;
    }
}
