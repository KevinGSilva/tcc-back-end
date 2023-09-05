<?php

namespace App\Repositories;

use App\Models\Contractor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContractorRepository
{
    /**
     * @var Contractor
     */
    private $contractor;

    public function __construct(Contractor $contractor)
    {
        $this->contractor = $contractor;
    }

    /**
     * @param array $data
     * @return Contractor
     * @throws ValidationException
     */
    public function store(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|unique:contractors|email',            
            'password' => 'required|min:6',
            'document' => 'required|unique:contractors|cpf'
        ])->setCustomMessages([
            'name.required' => 'Campo nome é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres',
            'email.required' => 'Campo e-mail é obrigatório',
            'email.unique' => 'Já existe um usuário com esse email',
            'email.email' => 'Preencha um email válido',
            'password.required' => 'O campo senha é obrigatório',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
            'document.required' => 'O campo Documento é obrigatório',
            'document.unique' => 'Já existe uma conta com este CPF',
            'document.cpf' => 'Informe um CPF válido',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $data['password'] = Hash::make($data['password']);

        $contractor = $this->contractor->create($data);

        if (isset($data['$contractor_photo']) && !empty($data['$contractor_photo']) && $contractor->exists) {
            $contractor->addMedia($data['$contractor_photo'])->toMediaCollection('$contractor_photo');
        }

        return $contractor;
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     * @throws ValidationException
     */
    public function update(array $data, int $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('contractors')->ignore($id),
            ],
            'password' => 'min:6',
            'document' => [
                'required',
                'cpf',
                Rule::unique('contractors')->ignore($id),
            ],
        ])->setCustomMessages([
            'name.required' => 'Campo nome é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres',
            'email.required' => 'Campo e-mail é obrigatório',
            'email.unique' => 'Já existe um usuário com esse email',
            'email.email' => 'Preencha um email válido',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
            'document.required' => 'O campo Documento é obrigatório',
            'document.unique' => 'Já existe uma conta com este CPF',
            'document.cpf' => 'Informe um CPF válido',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $contractor = $this->contractor->find($id);

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['contractor_photo']) && !empty($data['contractor_photo']) && $contractor->exists) {
            $contractor->addMedia($data['contractor_photo'])->toMediaCollection('contractor_photo');
        }
        
        return $contractor->update($data);
    }

    /**
     * @return Contractor
     */
    public function getContractor(): Contractor
    {
        return $this->contractor;
    }
}
