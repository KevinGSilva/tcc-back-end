<?php
namespace App\Services;

use App\Repositories\RatingRepository;

class RatingService {
    
    private $ratingRepository;

    public function __construct(RatingRepository $ratingRepository)
    {
        $this->ratingRepository = $ratingRepository;
    }
    
    public function store($data) {
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $rating = $this->ratingRepository->store($data);

        return $rating;
    }

    public function update($id, $data) {
        $user = auth()->user();
        $data['user_id'] = $user->id;
        $rating = $this->ratingRepository->update($data, $id);

        return $rating;
    }
}