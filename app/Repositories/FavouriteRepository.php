<?php

namespace App\Repositories;

use App\Models\Favourite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FavouriteRepository
{
    /**
     * @var Favourite
     */
    private $favourite;

    public function __construct(Favourite $favourite)
    {
        $this->favourite = $favourite;
    }

    /**
     * @param array $data
     * @return Favourite
     * @throws ValidationException
     */
    public function store(array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'favourite_user_id' => 'required',
        ])->setCustomMessages([
            'user_id.required' => 'Ocorreu um erro. Por favor atualize a página e tente novamente!',
            'favourite_user_id.required' => 'Houve um erro, tente novamente!',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $favourite = $this->favourite->create($data);

        return $favourite;
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
            'user_id' => 'required',
            'favourite_user_id' => 'required',
        ])->setCustomMessages([
            'user_id.required' => 'Ocorreu um erro. Por favor atualize a página e tente novamente!',
            'favourite_user_id.required' => 'Houve um erro, tente novamente!',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $favourite = $this->favourite->find($id);
        
        return $favourite->update($data);
    }

    /**
     * @return Favourite
     */
    public function getFavourite(): Favourite
    {
        return $this->favourite;
    }
}
