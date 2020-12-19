<?php


namespace Models;
use Models\User;

class Group extends User
{
    public string $table = "groups";

    public function permissionSet() {
        return $this->hasOne(PermissionSet::class, "permission_set_id");
    }

    public function users() {
        return $this->hasMany(User::class, "group_id");
    }
}