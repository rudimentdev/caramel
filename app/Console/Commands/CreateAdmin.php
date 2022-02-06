<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin {email} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Administrator user';

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
     * Simple password auto-generator.
     *
     * @throws Exception
     */
    protected function generatePassword(): string
    {
        $seed = random_int(10000, 99999);
        $salt = time();

        return str_shuffle(Hash::make("$seed $salt"));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->argument('name');
        $password = $this->ask('Enter password or leave empty to auto-generate');

        if (is_null($password)) {
            try {
                $password = $this->generatePassword();
            } catch (Exception) {
                $this->error('Failed to auto-generate password!');
                do {
                    $password = $this->ask('Please enter password manually');
                } while (is_null($password));
            }
        }

        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'password' => $password
        ], [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required|min:8'
        ]);

        if (!$validator->fails()) {
            $user = new User();
            $user->email = $email;
            $user->name = $name;
            $user->password = Hash::make($password);
            $user->is_admin = true;

            try {
                $user->save();
                $this->comment($password);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        } else {
            $this->error($validator->messages());
        }


        return 0;
    }
}
