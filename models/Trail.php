<?php

namespace ceresia_adventure\models;

use ceresia_adventure\framework\Model;
use ceresia_adventure\repositories\EnigmaRepository;
use ceresia_adventure\repositories\TrailRepository;
use ceresia_adventure\repositories\UserRepository;

class Trail extends Model
{
    private int $trailId;
    private string $name;
    private string $departement;
    private int $estimatedTime;
    private int $level;
    private float $rating;
    private string $description;
    private string $dateStart;
    private string $dateEnd;
    private bool $visible;
    private User $createur;
    private int $nbEnigmas;

    /**
     * @param int $trailId
     * @param string $name
     * @param string $departement
     * @param int $estimatedTime
     * @param int $level
     * @param float $rating
     * @param string $description
     * @param string $dateStart
     * @param string $dateEnd
     * @param User $createur
     * @param int $nbEnigmas
     * @param bool $visible
     */
    public function __construct(int $trailId, string $name, string $departement, int $estimatedTime, int $level, float $rating, string $description, string $dateStart, string $dateEnd, User $createur, int $nbEnigmas, bool $visible)
    {
        parent::__construct();
        $this->trailId = $trailId;
        $this->name = $name;
        $this->departement = $departement;
        $this->estimatedTime = $estimatedTime;
        $this->level = $level;
        $this->rating = $rating;
        $this->description = $description;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->createur = $createur;
        $this->nbEnigmas = $nbEnigmas;
        $this->visible = $visible;
    }


    public static function populate(array $trailSql): Trail
    {
        $userRepository = new UserRepository();
        $enigmaRepository = new EnigmaRepository();

        $nbEnigmas = $enigmaRepository->countEnigmaByTrail($trailSql['trail_id']);
        $createur = $userRepository->findById($trailSql['user_id'])->row();
        $trail = new Trail(
            $trailSql['trail_id'],
            $trailSql['name'],
            $trailSql['departement'],
            $trailSql['estimated_time'],
            $trailSql['level'],
            5,
            $trailSql['description'],
            $trailSql['date_start'],
            $trailSql['date_end'],
            $createur,
            $nbEnigmas,
            $trailSql['visible']);
        return $trail;
    }

    /**
     * @return int
     */
    public function getTrailId(): int
    {
        return $this->trailId;
    }

    /**
     * @param int $trailId
     */
    public function setTrailId(int $trailId): void
    {
        $this->trailId = $trailId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDepartement(): string
    {
        return $this->departement;
    }

    /**
     * @param string $departement
     */
    public function setDepartement(string $departement): void
    {
        $this->departement = $departement;
    }

    /**
     * @return int
     */
    public function getEstimatedTime(): int
    {
        return $this->estimatedTime;
    }

    /**
     * @param int $estimatedTime
     */
    public function setEstimatedTime(int $estimatedTime): void
    {
        $this->estimatedTime = $estimatedTime;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDateStart(): string
    {
        return $this->dateStart;
    }

    /**
     * @param string $dateStart
     */
    public function setDateStart(string $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return string
     */
    public function getDateEnd(): string
    {
        return $this->dateEnd;
    }

    /**
     * @param string $dateEnd
     */
    public function setDateEnd(string $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return User
     */
    public function getCreateur(): User
    {
        return $this->createur;
    }

    /**
     * @param User $createur
     */
    public function setCreateur(User $createur): void
    {
        $this->createur = $createur;
    }

    /**
     * @return int
     */
    public function getNbEnigmas(): int
    {
        return $this->nbEnigmas;
    }

    /**
     * @param int $nbEnigmas
     */
    public function setNbEnigmas(int $nbEnigmas): void
    {
        $this->nbEnigmas = $nbEnigmas;
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        return $this->rating;
    }

    /**
     * @param float $rating
     */
    public function setRating(float $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

}
