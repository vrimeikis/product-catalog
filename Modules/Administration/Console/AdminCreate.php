<?php

declare(strict_types = 1);

namespace Modules\Administration\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Modules\Administration\Entities\Roles;
use Modules\Administration\Services\AdminService;
use Throwable;

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
     * @var AdminService
     */
    private $adminService;

    /**
     * Create a new command instance.
     *
     * @param AdminService $adminService
     */
    public function __construct(AdminService $adminService)
    {
        parent::__construct();

        $this->adminService = $adminService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $roleId = $this->getRoleId();
        $email = $this->enterEmail();
        $password = $this->enterPassword();

        $admin = $this->adminService->create($email, $password, true);

        $admin->roles()->sync([$roleId]);

        $this->info('User created!!!');

    }

    /**
     * @return string
     */
    private function enterEmail(): string
    {
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
    private function enterPassword(): string
    {
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

    /**
     * @return int
     */
    private function getRoleId(): int
    {
        $newRoleCreate = 'Create new';

        $roles = Roles::query()
            ->orderBy('id')
            ->pluck('name', 'id');

        $role = $this->choice(
            'Which Role set to Admin user?',
            array_merge([$newRoleCreate], $roles->toArray())
        );

        if ($role !== $newRoleCreate) {
            return (int)$roles->search($role);
        }

        return $this->createRole();
    }

    /**
     * @return int
     */
    private function createRole(): int
    {
        $name = $this->getRoleName();
        $description = $this->getRoleDescription();
        $fullAccess = $this->getRoleFullAccess();
        $accessibleRoutes = [];

        try {
            $role = new Roles();

            $role->name = $name;
            $role->description = $description;
            $role->full_access = $fullAccess;
            $role->accessible_routes = $accessibleRoutes;

            $role->saveOrFail();

            return $role->id;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return $this->createRole();
        }
    }

    /**
     * @return string
     */
    private function getRoleName(): string
    {
        $name = $this->ask('Enter new Role name');

        $validator = Validator::make([
            'name' => $name,
        ], [
            'name' => 'required|string|min:3|max:100|unique:roles',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first('name'));

            return $this->getRoleName();
        }

        return $name;
    }

    /**
     * @return string|null
     */
    private function getRoleDescription(): ?string
    {
        $description = $this->ask('Enter Role description or not');

        $validator = Validator::make([
            'description' => $description,
        ], [
            'description' => 'nullable|max:1000',
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first('description'));

            return $this->getRoleDescription();
        }

        return $description;
    }

    /**
     * @return bool
     */
    private function getRoleFullAccess(): bool
    {
        return $this->confirm('Has Role full access?', false);
    }

}
