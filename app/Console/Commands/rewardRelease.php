<?php

namespace App\Console\Commands;

use App\Http\Controllers\scriptController;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class rewardRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reward:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reward calculate & release';

    /**
     * Execute the console command.
     */
    public function handle(Request $request)
    {
        $appHome = new scriptController;

        $appHome->calculateWeeklyBusiness($request);
    }
}
