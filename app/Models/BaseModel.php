<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel disable()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel enable()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel page($page, $take = 20)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel query()
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    public const ENABLE = 1;
    public const DISABLE = 0;
    public const ERROR = -1;
    public const TAKE = 20;

    public function scopeEnable($query)
    {
        return $query->whereStatus(self::ENABLE);
    }

    public function scopeDisable($query)
    {
        return $query->whereStatus(self::DISABLE);
    }

    public function scopePage($query, $page, $take = self::TAKE)
    {
        --$page;
        if ($page < 0) {
            $page = 0;
        }
        if ($take > 100) {
            $take = 500;
        }
        return $query->skip($page * $take)->take($take);
    }

    protected $casts = [];

    final public function getDates(): array
    {
        return [];
    }
}
