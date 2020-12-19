<?php


namespace Kernel;

use Kernel\Kernel;
use Kernel\QueryBuilder;
use stdClass;


class Model extends stdClass
{
    public string $table;
    private QueryBuilder $queryBuilder;
    private int $insertId;
    public array $eagerLoads = [];
    public array $attributes = [];
    public bool $isJson = false;

    public function __construct($json = false)
    {
        $this->queryBuilder = new QueryBuilder($this);
    }

    public static function all($json = false)
    {
        $obj = self::instantiate($json);
        return $obj->queryBuilder->select("*", $obj->table)->get();
    }

    public static function delete(): QueryBuilder
    {
        $obj = self::instantiate();
        return $obj->queryBuilder->delete($obj->table);
    }

    public static function with($relationships, $json = false): QueryBuilder
    {
        $obj = self::instantiate($json);

        foreach ($relationships as $relationship) {
            $obj->eagerLoads[] = $relationship;
        }
        return $obj->queryBuilder;
    }


    public static function where($options, $json = false): QueryBuilder
    {
        $obj = self::instantiate($json);

        if (!$obj->queryBuilder->getQuery()) {
            $obj->queryBuilder->select("*", $obj->table);
        }

        $obj->queryBuilder->where($options);
        return $obj->queryBuilder;
    }

    public static function findById($id, $json = false)
    {
        $obj = self::instantiate($json);
        return $obj->queryBuilder->where(["id" => $id])->first();
    }



    private function create($arr)
    {
        $this->queryBuilder->insert($this->table, $arr)->exec();
        $this->insertId = Kernel::$db->lastInsertId();
        $this->id = $this->insertId;
        $this->params = [];
        return $this;
    }

    private function update($arr): QueryBuilder
    {
        $this->queryBuilder->update($arr);
        return $this->queryBuilder;
    }

    public function save(): Model
    {
        if (!isset($this->attributes["id"])) {
            return $this->create($this->attributes);
        }

        $this->update($this->attributes)->where(['id' => $this->attributes['id']])->exec();
        return $this;
    }

    public function remove(): bool
    {
        if (!$this->attributes["id"]) return false;
        $this->queryBuilder->delete()->where(["id" => $this->attributes["id"]])->exec();
        return true;
    }

    public function bind($record): Model
    {

        $object = null;

        $class = get_called_class();
        $object = new $class();
        foreach ($record as $key => $value) {
            $object->{$key} = $value;
        }

        return $object;
    }

    public function bindMany($records = []): array
    {
        $objects = [];

        foreach ($records as $record) {
            $object = $this->bind($record);
            $objects[] = $object;
        }

        return $objects;
    }

    public function bindEagerLoads($eagerLoads)
    {
        foreach ($eagerLoads as $eagerLoad) {
            if (strpos($eagerLoad, ".") == true) {
                $this->bindNested($eagerLoad);
            } else {
                $records = $this->$eagerLoad();
                $this->$eagerLoad = $records;
            }
        }
    }

    protected function hasOne(string $class, $foreign_key)
    {
        $obj = $class::where(["id" => $this->$foreign_key])->first();
        if (!$obj) return null;

        return $obj;
    }

    protected function hasMany(string $class, $foreign_key): array
    {
        $objects = $class::where([$foreign_key => $this->attributes["id"]])->get();
        if (!count($objects)) return [];

        return $objects;
    }

    protected function bindNested($eagerLoad, $obj = null)
    {
        $nested = explode(".", $eagerLoad);
        if (!$nested[0]) return null;

        $match = $nested[0];
        array_shift($nested);


        $groups = explode(",", $match);

        foreach ($groups as $group) {
            $newLoad = implode(".", $nested);

            if (!$obj) {
                $this->{$group} = $this->{$group}();
                $obj = $this->{$group};
                $this->bindNested($newLoad, $obj);
            } else {
                $obj->{$group} = $obj->{$group}();
                $group = $obj->{$group};
                $this->bindNested($newLoad, $group);
            }
        }
    }

    public function export()
    {
        $arr = [];
        foreach ($this->attributes as $key => $value) {
            $arr[$key] = $value;
        }

        return $arr;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    private static function instantiate($json = false): Model
    {
        $class = get_called_class();
        return new $class($json);
    }
}
