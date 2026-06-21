<?php

namespace App\Models;

use CodeIgniter\Model;

class Animal extends Model
{
    protected $table         = 'animals';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'name', 'species', 'breed', 'age_group', 'size',
        'intake_date', 'status', 'notes', 'photo_url', 'archived',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'name'        => 'required|max_length[100]',
        'species'     => 'required|in_list[dog,cat,other]',
        'intake_date' => 'required|valid_date',
        'status'      => 'required|in_list[needs_foster,in_foster,adopted]',
    ];
}
