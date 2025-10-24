<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * Os campos que podem ser preenchidos em massa
     */
    protected $fillable = ['name'];

    /**
     * Relacionamento: Um projeto tem várias tarefas
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}