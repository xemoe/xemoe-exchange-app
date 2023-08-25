<?php

namespace Database\Seeders;

use App\Models\CryptoCurrency;
use App\Models\Enums\RoleNameEnum;
use App\Models\Role;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws BindingResolutionException
     */
    public function run(): void
    {
        $this->command->newLine();

        //
        // If User not empty then show message and return
        //
        if (User::count() > 0) {
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
        $this->command->info('  Creating default user role...');
        $this->command->info('  Name: ' . RoleNameEnum::Admin->name);
        $this->command->info('  Name: ' . RoleNameEnum::Regular->name);
        $this->command->newLine();

        $this->createAdminRole();
        $this->createRegularRole();

        $password = fake()->password(16);
        $adminUser = $this->createAdminUser($password);

        $this->command->info('  Creating default user...');
        $this->command->info('  Name: ' . $adminUser->name);
        $this->command->info('  Email: ' . $adminUser->email);
        $this->command->warn('  Password: ' . $password);
        $this->command->newLine();

        $seedWalletSymbols = ['BTC', 'ETH', 'XRP'];
        $this->createWalletForUser($adminUser, $seedWalletSymbols);
    }

    private function createAdminRole(): void
    {
        Role::create([
            'name' => RoleNameEnum::Admin,
            'description' => 'Administrator role. This role has all the permissions by default.',
        ]);
    }

    private function createRegularRole(): void
    {
        Role::create([
            'name' => RoleNameEnum:: Regular,
            'description' => 'Regular user role. This role is default for all users.',
        ]);
    }

    private function createAdminUser(string $password): User
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john_doe@example.com',
            'password' => $password
        ]);

        $adminRole = Role::where(['name' => RoleNameEnum::Admin])->firstOrFail();
        $user->roleUsers()->create([
            'role_id' => $adminRole->id,
        ]);

        return $user;
    }

    /**
     * @throws BindingResolutionException
     */
    private function createWalletForUser(User $user, array $symbols = []): void
    {
        if (empty($symbols)) {
            $symbols = ['BTC', 'ETH', 'XRP', 'BUSD'];
        }

        $this->command->info('  Creating default wallet for user...' . $user->name);

        $cryptoCurrencies = CryptoCurrency::whereIn('symbol', $symbols)->get();
        $walletService = app()->make(WalletService::class);

        foreach ($cryptoCurrencies as $cryptoCurrency) {
            $w = $walletService->addBySymbol([
                'user_id' => $user->id,
                'symbol' => $cryptoCurrency->symbol,
            ]);

            $this->command->warn('  Symbol: ' . $cryptoCurrency->symbol . ' Address: ' . $w->address);
        }

        $this->command->newLine();
    }
}
