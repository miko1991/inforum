<?php


namespace Models;


use Kernel\Model;
use Models\Group;

class User extends Model
{
    public string $table = "users";

    public function group() {
        return $this->hasOne(Group::class, "group_id");
    }
}