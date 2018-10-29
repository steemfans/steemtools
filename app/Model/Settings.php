<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    public $keyType = 'string';
    public $primaryKey = 'skey';

    static public function steem_per_mvests() {
        $data = self::where('skey', 'steem_per_mvests')->first();
        if ($data) {
            return $data['sval'];
        } else {
            return false;
        }
    }
}
