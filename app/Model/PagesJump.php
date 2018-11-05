<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PagesJump extends Model
{
    protected $table = 'pages_jump';
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'keyword',
        'url',
        'sitename',
        'descp',
        'status',
        'order_index',
    ];
}
