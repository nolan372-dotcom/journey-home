<?php

namespace App\Controllers;

use App\Models\FosterHome;
use App\Models\Placement;

class Fosters extends BaseController
{
    public function index()
    {
        $model = new FosterHome();
        $placementModel = new Placement();

        $q       = trim((string) $this->request->getGet('q'));
        $species = $this->request->getGet('species');
        $status  = $this->request->getGet('status');

        if ($q !== '') {
            $model->groupStart()
                  ->like('name', $q)
                  ->orLike('email', $q)
                  ->orLike('phone', $q)
                  ->groupEnd();
        }
        if ($species) {
            $model->where('species_accepted', $species);
        }
        if ($status) {
            $model->where('status', $status);
        }

        $fosters = $model->orderBy('name')->findAll();

        foreach ($fosters as &$f) {
            $f['active_count'] = $placementModel->activeCount($f['id']);
        }

        return view('fosters/index', [
            'fosters' => $fosters,
            'filters' => compact('q', 'species', 'status'),
        ]);
    }

    public function new()
    {
        return view('fosters/create', ['errors' => [], 'old' => []]);
    }

    public function create()
    {
        $model = new FosterHome();

        $data = [
            'name'             => $this->request->getPost('name'),
            'email'            => $this->request->getPost('email'),
            'phone'            => $this->request->getPost('phone'),
            'area_zip'         => $this->request->getPost('area_zip'),
            'species_accepted' => $this->request->getPost('species_accepted'),
            'size_preference'  => $this->request->getPost('size_preference'),
            'max_capacity'     => (int) $this->request->getPost('max_capacity'),
            'has_kids'         => $this->request->getPost('has_kids') ? 1 : 0,
            'has_other_pets'   => $this->request->getPost('has_other_pets') ? 1 : 0,
            'status'           => $this->request->getPost('status') ?? 'active',
            'notes'            => $this->request->getPost('notes'),
        ];

        if (!$model->insert($data)) {
            return view('fosters/create', [
                'errors' => $model->errors(),
                'old'    => $data,
            ]);
        }

        return redirect()->to('/fosters')->with('success', 'Foster home added.');
    }

    public function show($id)
    {
        $model = new FosterHome();
        $foster = $model->find($id);

        if (!$foster) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $placements = db_connect()
            ->table('placements p')
            ->select('p.id as placement_id, p.start_date, a.id as animal_id, a.name, a.species, a.breed, a.age_group, a.size')
            ->join('animals a', 'a.id = p.animal_id')
            ->where('p.foster_home_id', $id)
            ->where('p.end_date IS NULL')
            ->get()->getResultArray();

        return view('fosters/show', ['foster' => $foster, 'placements' => $placements]);
    }

    public function edit($id)
    {
        $model = new FosterHome();
        $foster = $model->find($id);

        if (!$foster) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $returnTo = $this->request->getGet('from') === 'list' ? '/fosters' : '/fosters/' . $id;

        return view('fosters/edit', ['foster' => $foster, 'errors' => [], 'returnTo' => $returnTo]);
    }

    public function update($id)
    {
        $model = new FosterHome();
        $foster = $model->find($id);

        if (!$foster) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'name'             => $this->request->getPost('name'),
            'email'            => $this->request->getPost('email'),
            'phone'            => $this->request->getPost('phone'),
            'area_zip'         => $this->request->getPost('area_zip'),
            'species_accepted' => $this->request->getPost('species_accepted'),
            'size_preference'  => $this->request->getPost('size_preference'),
            'max_capacity'     => (int) $this->request->getPost('max_capacity'),
            'has_kids'         => $this->request->getPost('has_kids') ? 1 : 0,
            'has_other_pets'   => $this->request->getPost('has_other_pets') ? 1 : 0,
            'status'           => $this->request->getPost('status'),
            'notes'            => $this->request->getPost('notes'),
        ];

        if (!$model->update($id, $data)) {
            $returnTo = $this->request->getPost('return_to') ?: '/fosters/' . $id;
            return view('fosters/edit', [
                'foster'   => array_merge($foster, $data),
                'errors'   => $model->errors(),
                'returnTo' => $returnTo,
            ]);
        }

        return redirect()->to('/fosters/' . $id)->with('success', 'Foster home updated.');
    }

    public function updateStatus($id)
    {
        $model = new FosterHome();
        $status = $this->request->getPost('status');

        if (!in_array($status, ['active', 'paused', 'inactive'])) {
            return redirect()->to('/fosters/' . $id);
        }

        $model->update($id, ['status' => $status]);

        return redirect()->to('/fosters/' . $id)->with('success', 'Status updated.');
    }
}
