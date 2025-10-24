<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'sometimes|string|max:255', #Sometimes só valida quando o campo estiver presente
            'descricao' => 'nullable|string',
            'status' => 'sometimes|in:Pendente,Em Andamento,Concluída',
            'project_id' => 'sometimes|exists:projects,id'
        ];
    }
}
