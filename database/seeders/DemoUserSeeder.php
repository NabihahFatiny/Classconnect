<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Demo accounts for testing. Password is stored as plain text (app uses plain-text comparison).
     */
    public function run(): void
    {
        $demoPassword = 'password';

        if (! User::where('username', 'student')->exists()) {
            User::create([
                'name' => 'Demo Student',
                'username' => 'student',
                'email' => 'student@classconnect.demo',
                'mobile_phone' => null,
                'date_of_birth' => null,
                'user_id' => 'DEMO001',
                'user_type' => 'student',
                'class' => '1A',
                'password' => $demoPassword,
            ]);
        }

        if (! User::where('username', 'lecturer')->exists()) {
            User::create([
                'name' => 'Demo Lecturer',
                'username' => 'lecturer',
                'email' => 'lecturer@classconnect.demo',
                'mobile_phone' => null,
                'date_of_birth' => null,
                'user_id' => 'LEC001',
                'user_type' => 'lecturer',
                'class' => null,
                'password' => $demoPassword,
            ]);
        }
    }
}
