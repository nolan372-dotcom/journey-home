<?php

namespace App\Models;

use CodeIgniter\Model;

class Placement extends Model
{
    protected $table         = 'placements';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'animal_id', 'foster_home_id', 'start_date', 'end_date', 'notes',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'animal_id'      => 'required|integer',
        'foster_home_id' => 'required|integer',
        'start_date'     => 'required|valid_date',
    ];

    public function activeCount(int $fosterHomeId): int
    {
        return $this->where('foster_home_id', $fosterHomeId)
                    ->where('end_date IS NULL')
                    ->countAllResults();
    }
}
