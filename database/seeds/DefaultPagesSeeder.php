<?php

use Illuminate\Database\Seeder;
use App\Model\PagesJump;

class DefaultPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PagesJump::create([
            'keyword' => 'steemh',
            'url' => 'https://steemh.org/',
            'sitename' => 'Steem 指南',
            'descp' => 'Steem 中文社区集体创作, 主编: @dapeng, 副主编: @maiyude',
            'status' => 1,
            'order_index' => 0,
        ]);
        PagesJump::create([
            'keyword' => 'steemyy',
            'url' => 'https://steemyy.com/steemit-tools/',
            'sitename' => 'SteemYY',
            'descp' => '@justyy',
            'status' => 1,
            'order_index' => 1,
        ]);
        PagesJump::create([
            'keyword' => 'witness',
            'url' => 'https://www.eztk.net/witnesses.php',
            'sitename' => '见证人列表',
            'descp' => '@oflyhigh',
            'status' => 1,
            'order_index' => 2,
        ]);
        PagesJump::create([
            'keyword' => 'steemgg',
            'url' => 'https://steemgg.com/',
            'sitename' => 'SteemGG',
            'descp' => '@bobdos @bizheng @bonjovis @kanny10 @stabilowl',
            'status' => 1,
            'order_index' => 3,
        ]);
        PagesJump::create([
            'keyword' => 'steemeditor',
            'url' => 'https://steemeditor.com/',
            'sitename' => 'SteemEditor',
            'descp' => '@ety001',
            'status' => 1,
            'order_index' => 4,
        ]);
    }
}
