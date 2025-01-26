<?php

namespace App\Repositories;

use App\Models\Rating;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RatingRepository
{
    /**
     * @var Rating
     */
    private $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @param array $data
     * @return Rating
     * @throws ValidationException
     */
    public function store(array $data)
    {
        $validator = Validator::make($data, [
        ])->setCustomMessages([
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $rating = $this->rating->create($data);

        /* return $rating; */
        return response()->json([
            "message" => "Register successfully",
            "rating" => $rating,
            "status" => "success"
        ]);
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
        ])->setCustomMessages([
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => 'validation_error',
                "message" => $validator->errors(),
            ], 422);
        }

        $rating = $this->rating->find($id);
        
        return $rating->update($data);
        return response()->json([
            "message" => "Register successfully",
            "rating" => $rating,
            "status" => "success"
        ]);
    }

    /**
     * @return Rating
     */
    public function getRating(): Rating
    {
        return $this->rating;
    }
}
