<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'status' => 'nullable|in:Pendente,Em Andamento,Concluído'
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título da tarefa é obrigatório.',
            'project_id.required' => 'A tarefa deve pertencer a um projeto.',
            'project_id.exists' => 'O projeto selecionado não existe.',
        ];
    }
}
