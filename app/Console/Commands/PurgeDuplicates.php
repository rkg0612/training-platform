<?php

namespace App\Console\Commands;

use Facades\App\Services\PurgeDuplicateService;
use Illuminate\Console\Command;

class PurgeDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge DB record duplicates';

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
        $count = PurgeDuplicateService::purgeUserUnits();
        $this->info('Duplicate Records Purged: '.$count);
    }
}
