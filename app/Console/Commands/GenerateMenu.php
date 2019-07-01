<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelia:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate all menu of application';

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
        $this->call('db:seed', [
            '--class' => 'MenuTableSeeder'
        ]);

        $this->callSilent('db:seed', [
            '--class' => 'PermissionTableSeeder'
        ]);
    }
}