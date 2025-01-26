<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'web']);

        if (!\App\Models\User::where('email', 'admin@exemplo.com.br')->first()) {
            $user = new \App\Models\User();

            $user->name = "Admin";
            $user->email = 'admin@exemplo.com.br';
            $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
            $user->document = 11111111111;
            $user->email_verified_at = now();
            $user->remember_token = Str::random(10);

            $user->assignRole($roleAdmin);

            $user->save();
        }
    }
}
