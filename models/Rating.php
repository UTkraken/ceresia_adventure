<?php

namespace ceresia_adventure\models;

use ceresia_adventure\framework\Model;
use ceresia_adventure\repositories\RatingRepository;
use ceresia_adventure\repositories\TrailRepository;
use ceresia_adventure\repositories\UserRepository;

class Rating extends Model
{
    private int $ratingId;
    private int $rating;
    private Trail $trail;
    private User $user;

    /**
     * Rating constructor
     *
     *  @param int $ratingId
     *  @param int $rating
     *  @param Trail $trail
     *  @param User $user
     */
    public function __construct(int $ratingId, int $rating, Trail $trail, User $user)
    {
        $this->ratingId = $ratingId;
        $this->rating = $rating;
        $this->trail = $trail;
        $this->user = $user;
    }

    public static function populate(array $ratingSql): Rating
    {
        $userRepository = new UserRepository();
        $trailRepository = new TrailRepository();
        $ratingRepository = new RatingRepository();
        $trail = $trailRepository->findById($ratingSql['trail_id'])->row();
        $user = $userRepository->findById($ratingSql['user_id'])->row();
        $rating = new Rating(
            $ratingSql['rating_id'],
            $ratingSql['rating'],
            $trail,
            $user
        );
        return $rating;
    }

    /**
     * @return int
     */
    public function getRatingId(): int
    {
        return $this->ratingId;
    }

    /**
     * @param int $ratingId
     */
    public function setRatingId(int $ratingId): void
    {
        $this->ratingId = $ratingId;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return Trail
     */
    public function getTrail(): Trail
    {
        return $this->trail;
    }

    /**
     * @param Trail $trail
     */
    public function setTrail(Trail $trail): void
    {
        $this->trail = $trail;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}