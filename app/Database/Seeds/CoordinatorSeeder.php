<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class CoordinatorSeeder extends Seeder
{
    public function run(): void
    {
        $users = auth()->getProvider();

        // Skip if this account already exists
        if ($users->findByCredentials(['email' => 'admin@journeyhome.com']) !== null) {
            return;
        }

        $user = new User([
            'username' => 'coordinator',
            'email'    => 'admin@journeyhome.com',
            'password' => 'JourneyHome!',
        ]);

        $users->save($user);
    }
}
