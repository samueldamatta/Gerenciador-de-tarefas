<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'project_id'
    ];

    /**
     * Relacionamento: Uma tarefa pertence a um projeto
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}