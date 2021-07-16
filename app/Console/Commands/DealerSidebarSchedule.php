<?php

namespace App\Console\Commands;

use App\Models\Dealer;
use App\Models\User;
use App\Services\LMS\ClientLmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DealerSidebarSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dealer:sidebar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates dealers sidebar cache daily';

    private $clientLmsService;

    /**
     * Create a new command instance.
     *
     * @param ClientLmsService $clientLmsService
     */
    public function __construct(ClientLmsService $clientLmsService)
    {
        parent::__construct();
        $this->clientLmsService = $clientLmsService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dealers = Dealer::has('users')->get();
        foreach ($dealers as $dealer) {
            // clear dealer's sidebar cache first
            $menuCacheName = "dealer_{$dealer->id}_sidebar_menu";
            Cache::forget($menuCacheName);

            $user = $dealer->users->first();
            $this->clientLmsService->fetchCourseLibrary($user);
        }
    }
}
