<?php


namespace Models;
use Kernel\Model;
use Models\User;

class Session extends Model
{
    public string $table = "sessions";

    public function user() {
        return $this->hasOne(User::class, "user_id");
    }
}