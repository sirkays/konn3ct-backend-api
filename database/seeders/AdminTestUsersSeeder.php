<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTestUsersSeeder extends Seeder
{
    public function run()
    {
        $u1 = User::updateOrCreate(
            ['email' => 'suspend.test@konn3ct.dev'],
            [
                'firstname'      => 'Suspend',
                'lastname'       => 'TestUser',
                'password'       => Hash::make('Test@1234'),
                'type'           => 'user',
                'plan'           => 1,
                'account_status' => null,
            ]
        );

        $u2 = User::updateOrCreate(
            ['email' => 'ban.test@konn3ct.dev'],
            [
                'firstname'      => 'Ban',
                'lastname'       => 'TestUser',
                'password'       => Hash::make('Test@1234'),
                'type'           => 'user',
                'plan'           => 1,
                'account_status' => null,
            ]
        );

        $u3 = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'firstname'      => 'Admin',
                'lastname'       => 'User',
                'password'       => Hash::make('password'),
                'type'           => 'admin',
                'plan'           => 1,
                'account_status' => null,
            ]
        );

        $u4 = User::updateOrCreate(
            ['email' => 'admin_mfa@example.com'],
            [
                'firstname'         => 'Admin',
                'lastname'          => 'MFA',
                'password'          => Hash::make('password'),
                'type'              => 'admin',
                'plan'              => 1,
                'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
                'account_status'    => null,
            ]
        );

        $this->command->info('');
        $this->command->info('✅ Test users created:');
        $this->command->info('');
        $this->command->info('  [SUSPEND THIS ONE]');
        $this->command->info('  ID:    ' . $u1->id);
        $this->command->info('  Email: ' . $u1->email);
        $this->command->info('');
        $this->command->info('  [BAN THIS ONE]');
        $this->command->info('  ID:    ' . $u2->id);
        $this->command->info('  Email: ' . $u2->email);
        $this->command->info('');
        $this->command->info('  [STANDARD ADMIN]');
        $this->command->info('  ID:    ' . $u3->id);
        $this->command->info('  Email: ' . $u3->email);
        $this->command->info('');
        $this->command->info('  [MFA ADMIN]');
        $this->command->info('  ID:    ' . $u4->id);
        $this->command->info('  Email: ' . $u4->email);
        $this->command->info('  2FA Secret Key: JBSWY3DPEHPK3PXP');
        $this->command->info('');
    }
}
