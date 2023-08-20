<?php

namespace App\Repositories;

use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EmployeeRepository
{
    /**
     * @var Employee
     */
    private $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * @param array $data
     * @return Employee
     * @throws ValidationException
     */
    public function store(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|unique:employees|email',            
            'password' => 'required|min:6',
            'document' => 'required|unique:employees|cpf'
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

        $employee = $this->employee->create($data);

        if (isset($data['$employee_photo']) && !empty($data['$employee_photo']) && $employee->exists) {
            $employee->addMedia($data['$employee_photo'])->toMediaCollection('$employee_photo');
        }

        return $employee;
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
                Rule::unique('employees')->ignore($id),
            ],
            'password' => 'min:6',
            'document' => [
                'required',
                'cpf',
                Rule::unique('employees')->ignore($id),
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

        $employee = $this->employee->find($id);

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['employee_photo']) && !empty($data['employee_photo']) && $employee->exists) {
            $employee->addMedia($data['employee_photo'])->toMediaCollection('employee_photo');
        }
        
        return $employee->update($data);
    }

    /**
     * @return Employee
     */
    public function getEmployee(): Employee
    {
        return $this->employee;
    }
}
