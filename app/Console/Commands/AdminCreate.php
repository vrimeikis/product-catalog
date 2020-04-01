<?php

declare(strict_types = 1);

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Class AdminCreate
 *
 * @package App\Console\Commands
 */
class AdminCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void {
        $name = $this->enterName();
        $email = $this->enterEmail();
        $password = $this->enterPassword();

        $user = new User();

        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password); //bcrypt($password);

        $user->save();

        $this->info('User created!!!');

    }

    /**
     * @return string
     */
    private function enterName(): string {
        $name = $this->ask('Enter admin name');

        $validator = Validator::make(['name' => $name], [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first('name'));

            return $this->enterName();
        }

        return $name;
    }

    /**
     * @return string
     */
    private function enterEmail(): string {
        $email = $this->ask('Enter admin E-mail');
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users|max:255',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first('email'));

            return $this->enterEmail();
        }

        return $email;
    }

    /**
     * @return string
     */
    private function enterPassword(): string {
        $password = $this->secret('Enter admin password');
        $passwordConfirm = $this->secret('Repeat password');

        $validator = Validator::make([
            'password' => $password,
            'password_confirmation' => $passwordConfirm,
        ], [
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first('password'));

            return $this->enterPassword();
        }

        return $password;
    }

}
