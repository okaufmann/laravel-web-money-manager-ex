<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ManageUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmex:user {email?} {password?} {--delete} {--admin} {--noadmin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add, remove and change users from commandline';

    /**
     * Create a new command instance.
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
        $email = $this->argument('email');
        $password = $this->argument('password');

        $delete = $this->option('delete');
        $admin = $this->option('admin');
        $noadmin = $this->option('noadmin');

        $user = User::whereEmail($email)->first();

        if ($delete) {
            if (!$user) {
                $this->error(sprintf('User %s does not exist!', $email));
            } else {
                $user->delete();

                $this->warn(sprintf('%s was deleted', $user->username));
            }

            return;
        }

        if (empty($email) && empty($password)) {
            $headers = ['Name', 'Email', 'Admin', 'MMEX Guid', 'Locale', 'API Key', 'Created at'];

            $users = User::all(['name', 'email', 'is_admin', 'mmex_guid', 'locale', 'api_key', 'created_at'])->toArray();

            $this->info(sprintf('%d users found', count($users)));

            $this->table($headers, $users);

            return;
        }

        if (!$user) {
            $name = $this->ask('Name of user?');
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => bcrypt($password),
            ]);

            $this->info(sprintf('User %s was created!', $user->username));
        } else {
            if ($password) {
                $user->password = bcrypt($password);
                $user->save();

                $this->info(sprintf('%s\'s password was updated!', $user->name));
            }
        }

        if ($admin || $noadmin) {
            if (!$user) {
                $this->error(sprintf('User %s does not exist!', $email));
            } else {

                $user->is_admin = $admin || $noadmin;
                $user->save();

                if ($admin == true) {
                    $this->info('Admin privileges set');
                } else {
                    $this->info('Admin privileges removed');
                }

                return;
            }
        }
    }
}
