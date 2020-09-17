<?php

namespace App\Models;

class UserModel extends BaseModel
{
    public $table = 'users';
    public $hidden = ['pwd'];
}
