<?php

namespace Database\Seeders;

use App\Models\Enums\RoleNameEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //
        // If User not empty then show message and return
        //
        if (User::count() > 0) {
            $this->command->newLine();
            $this->command->info('  <error> WARNING </error>');
            $this->command->info('  User table is not empty, skipping...');
            $this->command->info('  If you want to seed the User table, first delete the existing records from the table.');
            $this->command->warn('  Run the following command to delete the existing records:');
            $this->command->warn('  php artisan db:seed --class=UserSeeder');
            $this->command->newLine();
            return;
        }

        //
        // Create default user role
        //
        $this->command->newLine();
        $this->command->info('  Creating default user role...');
        $this->command->info('  Name: ' . RoleNameEnum::Admin->name);
        $this->command->info('  Name: ' . RoleNameEnum::Regular->name);
        $this->command->newLine();

        $adminRole = Role::create([
            'name' => RoleNameEnum::Admin,
            'description' => 'Administrator role. This role has all the permissions by default.',
        ]);

        Role::create([
            'name' => RoleNameEnum:: Regular,
            'description' => 'Regular user role. This role is default for all users.',
        ]);

        $password = fake()->password();
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john_doe@example.com',
            'password' => $password
        ]);

        $this->command->info('  Creating default user...');
        $this->command->info('  Name: ' . $user->name);
        $this->command->info('  Email: ' . $user->email);
        $this->command->warn('  Password: ' . $password);

        $this->command->info('  Attaching default user role...');
        $this->command->info('  Role: ' . $adminRole->id);
        $this->command->newLine();

        $user->roleUsers()->create([
            'role_id' => $adminRole->id,
        ]);
    }
}
