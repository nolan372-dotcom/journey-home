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

        $q          = trim((string) $this->request->getGet('q'));
        $species    = $this->request->getGet('species');
        $status     = $this->request->getGet('status');
        $can_handle = array_filter((array) ($this->request->getGet('can_handle') ?? []));

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
        $validPrefs = ['ok_puppies', 'ok_kittens', 'ok_medical', 'ok_large_dogs', 'ok_behavior', 'has_fenced_yard'];
        foreach ($can_handle as $pref) {
            if ($pref === 'no_other_pets') {
                $model->where('has_other_pets', 0);
            } elseif ($pref === 'no_kids') {
                $model->where('has_kids', 0);
            } elseif (in_array($pref, $validPrefs)) {
                $model->where($pref, 1);
            }
        }

        $fosters = $model->orderBy('name')->findAll();

        foreach ($fosters as &$f) {
            $f['active_count'] = $placementModel->activeCount($f['id']);
        }

        return view('fosters/index', [
            'fosters' => $fosters,
            'filters' => compact('q', 'species', 'status', 'can_handle'),
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
            'has_kids'          => $this->request->getPost('has_kids') ? 1 : 0,
            'has_other_pets'    => $this->request->getPost('has_other_pets') ? 1 : 0,
            'status'            => $this->request->getPost('status') ?? 'active',
            'notes'             => $this->request->getPost('notes'),
            'ok_small'          => $this->request->getPost('ok_small') ? 1 : 0,
            'ok_medium'         => $this->request->getPost('ok_medium') ? 1 : 0,
            'ok_large'          => $this->request->getPost('ok_large') ? 1 : 0,
            'ok_xlarge'         => $this->request->getPost('ok_xlarge') ? 1 : 0,
            'ok_puppies'        => $this->request->getPost('ok_puppies') ? 1 : 0,
            'ok_kittens'        => $this->request->getPost('ok_kittens') ? 1 : 0,
            'ok_medical'        => $this->request->getPost('ok_medical') ? 1 : 0,
            'ok_behavior'       => $this->request->getPost('ok_behavior') ? 1 : 0,
            'has_fenced_yard'   => $this->request->getPost('has_fenced_yard') ? 1 : 0,
            'custom_can_handle' => $this->encodeCustomCanHandle(),
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
            'has_kids'          => $this->request->getPost('has_kids') ? 1 : 0,
            'has_other_pets'    => $this->request->getPost('has_other_pets') ? 1 : 0,
            'status'            => $this->request->getPost('status'),
            'notes'             => $this->request->getPost('notes'),
            'ok_small'          => $this->request->getPost('ok_small') ? 1 : 0,
            'ok_medium'         => $this->request->getPost('ok_medium') ? 1 : 0,
            'ok_large'          => $this->request->getPost('ok_large') ? 1 : 0,
            'ok_xlarge'         => $this->request->getPost('ok_xlarge') ? 1 : 0,
            'ok_puppies'        => $this->request->getPost('ok_puppies') ? 1 : 0,
            'ok_kittens'        => $this->request->getPost('ok_kittens') ? 1 : 0,
            'ok_medical'        => $this->request->getPost('ok_medical') ? 1 : 0,
            'ok_behavior'       => $this->request->getPost('ok_behavior') ? 1 : 0,
            'has_fenced_yard'   => $this->request->getPost('has_fenced_yard') ? 1 : 0,
            'custom_can_handle' => $this->encodeCustomCanHandle(),
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

    private function encodeCustomCanHandle(): ?string
    {
        $items = array_values(array_filter(array_map('trim', (array) ($this->request->getPost('custom_can_handle') ?? []))));
        return !empty($items) ? json_encode($items) : null;
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
