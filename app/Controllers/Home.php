<?php

namespace App\Controllers;

use App\Models\Animal;
use App\Models\FosterHome;
use App\Models\Placement;

class Home extends BaseController
{
    public function index(): string
    {
        $db = db_connect();
        $placementModel = new Placement();

        // Active placements: fosters with their current animals
        $activePlacements = $db->table('placements p')
            ->select('p.id as placement_id, p.start_date, a.id as animal_id, a.name as animal_name, a.species, a.breed, a.age_group, a.size, f.id as foster_id, f.name as foster_name, f.max_capacity, f.email as foster_email')
            ->join('animals a', 'a.id = p.animal_id')
            ->join('foster_homes f', 'f.id = p.foster_home_id')
            ->where('p.end_date IS NULL')
            ->orderBy('f.name')
            ->orderBy('a.name')
            ->get()->getResultArray();

        // Group placements by foster
        $fosterGroups = [];
        foreach ($activePlacements as $row) {
            $fid = $row['foster_id'];
            if (!isset($fosterGroups[$fid])) {
                $fosterGroups[$fid] = [
                    'foster_id'    => $fid,
                    'foster_name'  => $row['foster_name'],
                    'foster_email' => $row['foster_email'],
                    'max_capacity' => $row['max_capacity'],
                    'animals'      => [],
                ];
            }
            $fosterGroups[$fid]['animals'][] = $row;
        }

        // Animals needing a foster
        $animalModel = new Animal();
        $needsFoster = $animalModel->where('status', 'needs_foster')->orderBy('intake_date')->findAll();

        // Available fosters (active + under capacity)
        $fosterModel = new FosterHome();
        $activeFosters = $fosterModel->where('status', 'active')->orderBy('name')->findAll();
        $availableFosters = [];
        foreach ($activeFosters as $f) {
            $f['active_count'] = $placementModel->activeCount($f['id']);
            if ($f['active_count'] < $f['max_capacity']) {
                $availableFosters[] = $f;
            }
        }

        return view('home/index', [
            'fosterGroups'     => array_values($fosterGroups),
            'needsFoster'      => $needsFoster,
            'availableFosters' => array_values($availableFosters),
        ]);
    }
}
