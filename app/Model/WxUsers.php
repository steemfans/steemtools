<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxUsers extends Model
{
    protected $table = 'wx_users';
    protected $setting_items = ['replies', 'transfer', 'delegate_vesting_shares'];

    public function getSettingsIcon() {
        // var_dump($this->settings);
        if ($this->settings) {
            $settings = json_decode($this->settings, true);
            $res = [];
            foreach($this->setting_items as $v) {
                if (isset($settings[$v])) {
                    $res[$v] = $settings[$v] == 1 ? ['r' => 1, 'icon' => '[✓]'] : ['r' => 0, 'icon' => '[x]'];
                } else {
                    $res[$v] = ['r' => 0, 'icon' => '[x]'];
                }
            }
            return $res;
        } else {
            $res = [];
            foreach($this->setting_items as $v) {
                $res[$v] = ['r' => 0, 'icon' => '[x]'];
            }
            return $res;
        }
    }
}
