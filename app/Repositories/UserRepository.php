<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\SendEmailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class UserRepository
{
    /**
     * @var User
     */
    private $user;
    private $sendEmailService;

    public function __construct(User $user, SendEmailService $sendEmailService)
    {
        $this->user = $user;
        $this->sendEmailService = $sendEmailService;
    }

    /**
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function store(array $data)
    {
        $document_type = '';
        switch(strlen($data['document'])){
            case 11:
                $document_type = 'cpf';
                break;
            case 14:
                $document_type = 'cnpj';
                break;
        }
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|email',            
            'password' => 'required|min:6',
            'document' => 'required|unique:users|cpf',
        ])->setCustomMessages([
            'name.required' => 'Campo nome é obrigatório',
            'name.max' => 'O nome não pode ter mais que 255 caracteres',
            'email.required' => 'Campo e-mail é obrigatório',
            'email.unique' => 'Já existe um usuário com esse email',
            'email.email' => 'Preencha um email válido',
            'password.required' => 'O campo senha é obrigatório',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
            'document.required' => 'O campo Documento é obrigatório',
            'document.unique' => 'Já existe uma conta com este Documento',
            'document.cpf' => 'Informe um CPF válido',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $data['password'] = Hash::make($data['password']);

        $user = $this->user->create($data);

        DB::beginTransaction();

        if ($user)
        {

            $code = Str::random(6);
    
            $user->verificationCodes()->create([
                'code' => $code,
            ]);
    
            if (isset($data['cover']) && !empty($data['cover']) && $user->exists) {
                $user->addMedia($data['cover'])->toMediaCollection('cover');
            }
    
            $this->sendEmailService->sendEmail($user, $code);

            DB::commit();

            return response()->json([
                "message" => "Register successfully",
                "user" => $user,
                "status" => "success"
            ]);
        } else {
            DB::rollBack();
        }
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
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'min:6',
            'document' => [
                'required',
                'cpf',
                Rule::unique('users')->ignore($id),
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

        $user = $this->user->find($id);

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['cover']) && !empty($data['cover']) && $user->exists) {
            $user->addMedia($data['cover'])->toMediaCollection('cover');
        }
        
        $user->update($data);

        return response()->json([
            "message" => "Register successfully",
            "user" => $user,
            "status" => "success"
        ]);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
