<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxUsers extends Model
{
    protected $table = 'wx_users';
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'wx_openid',
        'email',
        'sc_code',
        'sc_access_token',
        'sc_refresh_token',
        'sc_expires_in',
        'user_info',
        'settings',
    ];

    protected $setting_items = [
        'replies',
        'transfer',
        'delegate_vesting_shares',
        'account_witness_vote',
    ];

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

    public function saveSettings($settings) {
        $res = [];
        foreach($this->setting_items as $v) {
            if (isset($settings[$v])) {
                $res[$v] = 1;
            } else {
                $res[$v] = 0;
            }
        }
        $this->settings = json_encode($res);
        return $this->save();
    }
}
