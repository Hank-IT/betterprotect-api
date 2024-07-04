<?php

namespace App\Services\User\Commands;

use App\Services\User\Actions\CreateUser as CreateUserAction;
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
    public function handle(CreateUserAction $createUser)
    {
        $username = $this->ask('What is the username?');

        $password = $this->secret('What is the password?');

        $createUser->execute($username, $password, 'administrator');

        $this->info('User ' . $username . ' was created successfully.');
    }
}
