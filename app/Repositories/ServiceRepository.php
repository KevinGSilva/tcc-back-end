<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ServiceRepository
{
    /**
     * @var Service
     */
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * @param array $data
     * @return Service
     * @throws ValidationException
     */
    public function store(array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'name' => 'required|max:255',
        ])->setCustomMessages([
            'user_id.required' => 'Ocorreu um erro. Por favor atualize a página e tente novamente!',
            'name.required' => 'Campo nome é obrigatório!',
            'name.max' => 'O nome não pode ter mais que 255 caracteres!'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $service = $this->service->create($data);

        return $service;
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
            'user_id' => 'required|max:255',
            'name' => 'required|max:255',
        ])->setCustomMessages([
            'user_id.required' => 'Ocorreu um erro. Por favor atualize a página e tente novamente!',
            'name.required' => 'Campo nome é obrigatório!',
            'name.max' => 'O nome não pode ter mais que 255 caracteres!',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $service = $this->service->find($id);
        
        return $service->update($data);
    }

    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }
}
