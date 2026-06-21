<?php

namespace App\Models;

use CodeIgniter\Model;

class FosterHome extends Model
{
    protected $table         = 'foster_homes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'name', 'email', 'phone', 'area_zip',
        'species_accepted', 'size_preference', 'max_capacity',
        'has_kids', 'has_other_pets', 'status', 'notes',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'name'  => 'required|max_length[100]',
        'email' => 'required|valid_email|max_length[150]',
        'phone' => 'required|max_length[20]',
        'max_capacity' => 'required|integer|greater_than[0]',
        'status' => 'required|in_list[active,paused,inactive]',
    ];
}
