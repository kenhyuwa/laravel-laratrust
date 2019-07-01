<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class LaraveliaInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelia:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravelia installer';

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
        $this->comment("\n");
        $this->line('-----------------------------------------------');
        $this->info('============ WELCOME TO LARAVELIA =============');
        $this->info('================= VERSION .1 ==================');
        $this->line('-----------------------------------------------');
        $this->comment("\n");
        $this->info('================= PLEASE WAIT =================');
        $this->comment("\n");
        
        $this->call('migrate:refresh');
        $this->call('laravelia:user');
        $this->call('laravelia:menu');

        $this->comment("\n");
        $this->info('----------------------------------------------');
        $this->info('  AMAZING!, LARAVELIA INSTALLED SUCCESSFULLY  ');
        $this->info('----------------------------------------------');
    }
}
