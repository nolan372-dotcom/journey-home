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
        if ($users->findByCredentials(['email' => 'nolanlee372@gmail.com']) !== null) {
            return;
        }

        $user = new User([
            'username' => 'coordinator',
            'email'    => 'nolanlee372@gmail.com',
            'password' => 'FosterCoord1!',
        ]);

        $users->save($user);
    }
}
