<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelia:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate user of application';

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
        $count_of_user = \App\Models\User::get()->count();
        if($count_of_user < 1){
            self::createUser();
        }else{
            $this->comment("\n");
            $this->error('User is ready');
            if($this->confirm('Are you sure to reinstall user?')){
                self::dropUser();
                self::createUser();
            }else{
                exit();
            }
        }
    }

    protected function createUser()
    {
        $this->comment("\n");
        $this->info('----------------------------------------------');
        $this->info('================= CREATE USER ================');
        $this->info('----------------------------------------------');
        $this->comment("\n");
        $this->line('Please complete following quetions!');
        $name = $this->ask('What is your name?');
        $email = $this->ask('What is your email?');

        if(strlen($name) < 3){
            $this->error('Your name is very short for name of user!');
            exit();
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->error('Email address not valid email!');
            exit();
        }

        $password = $this->secret('What is your password?');

        if(strlen($password) < 8){
            $this->error('Password cannot be less than 8!');
            $this->error('Your password is not secure!');
            exit();
        }

        if ($this->confirm('Do you wish to continue?')) {
            $this->info('Please waiting....');
            \App\Models\User::create([
                'id' => \Str::orderedUuid()->toString(),
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'email_verified_at' => carbon()->toDateTimeString()
            ]);
            $this->callSilent('laravelia:menu');
        }

        $users = \App\Models\User::all();

        $bar = $this->output->createProgressBar(count($users));

        $bar->start();

        foreach ($users as $user) {
            $bar->advance();
        }

        $bar->finish();

        $this->comment("\n");

        $headers = ['NAME', 'EMAIL', 'AVATAR'];
        
        $users = \App\Models\User::all(['name', 'email'])->toArray();
        
        $this->table($headers, $users);
    }

    protected function dropUser()
    {
        $this->info('Please waiting....');
        \Schema::disableForeignKeyConstraints();
        \DB::table('permission_role')->truncate();
        \DB::table('permission_user')->truncate();
        \DB::table('role_user')->truncate();
        \App\Models\Menu::truncate();
        \App\Models\User::truncate();
        \App\Models\Role::truncate();
        \App\Models\Permission::truncate();
        \Schema::enableForeignKeyConstraints();
    }
}
