<?php

namespace App\Services\Authentication\Commands;

use App\Services\Authentication\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->ask('What is the username?');

        $password = $this->secret('What is the password?');

        User::create([
            'username' => $username,
            'password' => $password,
            'role' => 'administrator',
        ]);

        $this->info('User ' . $username . ' was created successfully.');
    }
}
