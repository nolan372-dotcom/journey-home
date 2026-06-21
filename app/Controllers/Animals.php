<?php

namespace App\Controllers;

use App\Models\Animal;
use App\Models\Placement;

class Animals extends BaseController
{
    public function index()
    {
        $model = new Animal();

        $q         = trim((string) $this->request->getGet('q'));
        $species   = $this->request->getGet('species');
        $size      = $this->request->getGet('size');
        $status    = $this->request->getGet('status');
        $dateFrom  = $this->request->getGet('date_from');
        $dateTo    = $this->request->getGet('date_to');

        $model->where('archived', 0);

        if ($q !== '') {
            $model->groupStart()
                  ->like('name', $q)
                  ->orLike('breed', $q)
                  ->groupEnd();
        }
        if ($species) {
            $model->where('species', $species);
        }
        if ($size) {
            $model->where('size', $size);
        }
        if ($status) {
            $model->where('status', $status);
        }
        if ($dateFrom) {
            $model->where('intake_date >=', $dateFrom);
        }
        if ($dateTo) {
            $model->where('intake_date <=', $dateTo);
        }

        $animals = $model->orderBy('name')->findAll();

        return view('animals/index', [
            'animals' => $animals,
            'filters' => compact('q', 'species', 'size', 'status', 'dateFrom', 'dateTo'),
        ]);
    }

    public function new()
    {
        return view('animals/create', ['errors' => [], 'old' => []]);
    }

    public function create()
    {
        $model = new Animal();

        $data = [
            'name'        => $this->request->getPost('name'),
            'species'     => $this->request->getPost('species'),
            'breed'       => $this->request->getPost('breed') ?: null,
            'age_group'   => $this->request->getPost('age_group'),
            'size'        => $this->request->getPost('size'),
            'intake_date' => $this->request->getPost('intake_date'),
            'status'      => $this->request->getPost('status') ?? 'needs_foster',
            'notes'       => $this->request->getPost('notes') ?: null,
            'photo_url'   => $this->request->getPost('photo_url') ?: null,
        ];

        if (!$model->insert($data)) {
            return view('animals/create', [
                'errors' => $model->errors(),
                'old'    => $data,
            ]);
        }

        return redirect()->to('/animals')->with('success', 'Animal added.');
    }

    public function show($id)
    {
        $model = new Animal();
        $animal = $model->find($id);

        if (!$animal) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $placement = db_connect()
            ->table('placements p')
            ->select('p.id as placement_id, p.start_date, f.id as foster_id, f.name as foster_name, f.phone, f.email')
            ->join('foster_homes f', 'f.id = p.foster_home_id')
            ->where('p.animal_id', $id)
            ->where('p.end_date IS NULL')
            ->get()->getRowArray();

        return view('animals/show', ['animal' => $animal, 'placement' => $placement]);
    }

    public function edit($id)
    {
        $model = new Animal();
        $animal = $model->find($id);

        if (!$animal) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $returnTo = $this->request->getGet('from') === 'list' ? '/animals' : '/animals/' . $id;

        return view('animals/edit', ['animal' => $animal, 'errors' => [], 'returnTo' => $returnTo]);
    }

    public function update($id)
    {
        $model = new Animal();
        $animal = $model->find($id);

        if (!$animal) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'species'     => $this->request->getPost('species'),
            'breed'       => $this->request->getPost('breed') ?: null,
            'age_group'   => $this->request->getPost('age_group'),
            'size'        => $this->request->getPost('size'),
            'intake_date' => $this->request->getPost('intake_date'),
            'status'      => $this->request->getPost('status'),
            'notes'       => $this->request->getPost('notes') ?: null,
            'photo_url'   => $this->request->getPost('photo_url') ?: null,
        ];

        if (!$model->update($id, $data)) {
            $returnTo = $this->request->getPost('return_to') ?: '/animals/' . $id;
            return view('animals/edit', [
                'animal'   => array_merge($animal, $data),
                'errors'   => $model->errors(),
                'returnTo' => $returnTo,
            ]);
        }

        return redirect()->to('/animals/' . $id)->with('success', 'Animal updated.');
    }

    public function archive($id)
    {
        $model = new Animal();
        $animal = $model->find($id);

        if (!$animal) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // End any active placement so the foster home's capacity is freed
        $active = db_connect()
            ->table('placements')
            ->where('animal_id', $id)
            ->where('end_date IS NULL')
            ->get()->getRowArray();

        if ($active) {
            db_connect()->table('placements')
                ->where('id', $active['id'])
                ->update(['end_date' => date('Y-m-d')]);
        }

        $model->update($id, ['archived' => 1]);

        return redirect()->to('/animals')->with('success', esc($animal['name']) . ' has been archived.');
    }
}
