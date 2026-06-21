<?php

namespace App\Controllers;

use App\Models\Animal;
use App\Models\FosterHome;
use App\Models\Placement;

class Placements extends BaseController
{
    public function new()
    {
        $animalId = $this->request->getGet('animal_id');
        $animalModel = new Animal();
        $animal = $animalModel->find($animalId);

        if (!$animal || !empty($animal['archived']) || $animal['status'] !== 'needs_foster') {
            return redirect()->to('/')->with('error', 'Animal is not available for placement.');
        }

        $placementModel = new Placement();
        $fosterModel = new FosterHome();

        $allActive = $fosterModel->where('status', 'active')->orderBy('name')->findAll();

        $available = [];
        foreach ($allActive as $f) {
            $f['active_count'] = $placementModel->activeCount($f['id']);
            if ($f['active_count'] < $f['max_capacity']) {
                $available[] = $f;
            }
        }

        $q = trim((string) $this->request->getGet('q'));
        if ($q !== '') {
            $qLower = strtolower($q);
            $available = array_values(array_filter($available, function ($f) use ($qLower) {
                return str_contains(strtolower($f['name']), $qLower)
                    || str_contains(strtolower($f['email']), $qLower)
                    || str_contains(strtolower($f['species_accepted']), $qLower);
            }));
        }

        $perPage    = 10;
        $total      = count($available);
        $page       = max(1, (int) $this->request->getGet('page'));
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page       = min($page, $totalPages);

        return view('placements/new', [
            'animal'      => $animal,
            'available'   => array_slice($available, ($page - 1) * $perPage, $perPage),
            'total'       => $total,
            'q'           => $q,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'perPage'     => $perPage,
        ]);
    }

    public function create()
    {
        $animalId     = (int) $this->request->getPost('animal_id');
        $fosterHomeId = (int) $this->request->getPost('foster_home_id');

        $animalModel  = new Animal();
        $fosterModel  = new FosterHome();
        $placementModel = new Placement();

        $animal = $animalModel->find($animalId);
        $foster = $fosterModel->find($fosterHomeId);

        if (!$animal || !$foster) {
            return redirect()->to('/')->with('error', 'Invalid animal or foster home.');
        }

        if (!empty($animal['archived'])) {
            return redirect()->to('/')->with('error', 'Animal is archived and cannot be placed.');
        }

        if ($animal['status'] !== 'needs_foster') {
            return redirect()->to('/')->with('error', 'Animal is already placed or adopted.');
        }

        if ($foster['status'] !== 'active') {
            return redirect()->to('/')->with('error', 'Foster home is not active.');
        }

        // Capacity guard
        if ($placementModel->activeCount($fosterHomeId) >= $foster['max_capacity']) {
            return redirect()->to('/placements/new?animal_id=' . $animalId)
                ->with('error', esc($foster['name']) . ' is already at capacity.');
        }

        $placementModel->insert([
            'animal_id'      => $animalId,
            'foster_home_id' => $fosterHomeId,
            'start_date'     => date('Y-m-d'),
        ]);

        $animalModel->update($animalId, ['status' => 'in_foster']);

        return redirect()->to('/')->with('success', esc($animal['name']) . ' placed with ' . esc($foster['name']) . '.');
    }

    public function end($id)
    {
        $placementModel = new Placement();
        $placement = $placementModel->find($id);

        if (!$placement || $placement['end_date'] !== null) {
            return redirect()->to('/')->with('error', 'Placement not found or already ended.');
        }

        $finalStatus = $this->request->getPost('final_status');
        if (!in_array($finalStatus, ['needs_foster', 'adopted'])) {
            $finalStatus = 'needs_foster';
        }

        $placementModel->update($id, ['end_date' => date('Y-m-d')]);

        $animalModel = new Animal();
        $animalModel->update($placement['animal_id'], ['status' => $finalStatus]);

        $animal = $animalModel->find($placement['animal_id']);
        $msg = esc($animal['name']) . '\'s placement ended.';
        if ($finalStatus === 'adopted') {
            $msg = esc($animal['name']) . ' marked as adopted!';
        }

        return redirect()->to('/')->with('success', $msg);
    }
}
