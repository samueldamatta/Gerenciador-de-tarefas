<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição
     */
    public function authorize(): bool
    {
        return true; // Por enquanto, todos podem criar projetos
    }

    /**
     * Define as regras de validação
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:projects,name'
        ];
    }

    /**
     * Mensagens de erro personalizadas
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do projeto é obrigatório.',
            'name.unique' => 'Já existe um projeto com este nome.',
        ];
    }
}
