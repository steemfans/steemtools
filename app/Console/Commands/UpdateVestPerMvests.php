<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Settings;

class UpdateVestPerMvests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'steem_per_mvests:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update steem per mvests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $steem_per_vests = steem_per_mvests();
        if ($steem_per_vests) {
            $setting = Settings::where('skey', 'steem_per_mvests')->first();
            if (!$setting) {
                $setting = new Settings;
                $setting->skey = 'steem_per_mvests';
            }
            $setting->sval = $steem_per_vests;
            $setting->save();
            $this->info('update steem_per_mvests success');
        } else {
            $this->error('update steem_per_mvests failed');
        }
    }
}
