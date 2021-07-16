<?php

namespace App\Console\Commands;

use App\Models\UserUnit;
use DB;
use Illuminate\Console\Command;

class SetExistingVideosToPlayed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:playedvideos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Existing Watched Units and Modules videos to Played';

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
        $this->info('Setting watched unit videos to played...');
        $userUnits = UserUnit::whereNotNull('date_completed')
            ->update([
                'played'    =>  true,
            ]);

        if ($userUnits) {
            $this->info('Done.');
        }

        $this->info('Setting watched module videos to played...');

        $moduleUsers = DB::table('module_user')
            ->where('intro_video_watched', true)
            ->where('played', false)
            ->update([
                'played'    =>  true,
            ]);

        if ($moduleUsers) {
            $this->info('Done.');
        }
    }
}
