<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        // Foster homes
        $this->db->table('foster_homes')->insertBatch([
            [
                'name'             => 'Sarah & Mike Johnson',
                'email'            => 'sarah.johnson@example.com',
                'phone'            => '214-555-0101',
                'area_zip'         => '75080',
                'species_accepted' => 'dog',
                'size_preference'  => 'small,medium',
                'max_capacity'     => 2,
                'has_kids'         => 1,
                'has_other_pets'   => 0,
                'status'           => 'active',
                'notes'            => 'Great with puppies. Prefer short-term fosters.',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'name'             => 'Linda Patel',
                'email'            => 'linda.patel@example.com',
                'phone'            => '972-555-0202',
                'area_zip'         => '75081',
                'species_accepted' => 'cat',
                'size_preference'  => null,
                'max_capacity'     => 3,
                'has_kids'         => 0,
                'has_other_pets'   => 1,
                'status'           => 'active',
                'notes'            => 'Has two resident cats. Experienced with bottle babies.',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
            [
                'name'             => 'Carlos Rivera',
                'email'            => 'carlos.rivera@example.com',
                'phone'            => '469-555-0303',
                'area_zip'         => '75082',
                'species_accepted' => 'both',
                'size_preference'  => 'large',
                'max_capacity'     => 1,
                'has_kids'         => 0,
                'has_other_pets'   => 0,
                'status'           => 'paused',
                'notes'            => 'On vacation until July 15.',
                'created_at'       => $now,
                'updated_at'       => $now,
            ],
        ]);

        // Animals
        $this->db->table('animals')->insertBatch([
            [
                'name'        => 'Biscuit',
                'species'     => 'dog',
                'breed'       => 'Beagle mix',
                'age_group'   => 'young',
                'size'        => 'small',
                'intake_date' => '2026-06-10',
                'status'      => 'in_foster',
                'notes'       => 'Heartworm positive, on treatment.',
                'photo_url'   => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Marmalade',
                'species'     => 'cat',
                'breed'       => 'Domestic shorthair',
                'age_group'   => 'puppy/kitten',
                'size'        => 'small',
                'intake_date' => '2026-06-14',
                'status'      => 'in_foster',
                'notes'       => 'One of three kittens from the same litter.',
                'photo_url'   => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Duke',
                'species'     => 'dog',
                'breed'       => 'Lab mix',
                'age_group'   => 'adult',
                'size'        => 'large',
                'intake_date' => '2026-06-18',
                'status'      => 'needs_foster',
                'notes'       => 'Very gentle. Good with kids.',
                'photo_url'   => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Luna',
                'species'     => 'cat',
                'breed'       => 'Siamese mix',
                'age_group'   => 'adult',
                'size'        => 'medium',
                'intake_date' => '2026-06-01',
                'status'      => 'needs_foster',
                'notes'       => 'Shy at first, warms up quickly.',
                'photo_url'   => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);

        // Placements (Biscuit → Sarah & Mike, Marmalade → Linda)
        $this->db->table('placements')->insertBatch([
            [
                'animal_id'      => 1,
                'foster_home_id' => 1,
                'start_date'     => '2026-06-11',
                'end_date'       => null,
                'notes'          => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'animal_id'      => 2,
                'foster_home_id' => 2,
                'start_date'     => '2026-06-14',
                'end_date'       => null,
                'notes'          => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);
    }
}
