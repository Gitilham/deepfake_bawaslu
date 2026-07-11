<?php

namespace App\Models;

use CodeIgniter\Model;

class EducationContentModel extends Model
{
    protected $table            = 'education_contents';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $allowedFields = [
        'title',
        'slug',
        'content',
        'is_active',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getActiveContents(): array
    {
        return $this->where('is_active', 1)
            ->where('deleted_at', null)
            ->orderBy('id', 'ASC')
            ->findAll();
    }
}