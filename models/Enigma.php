<?php

namespace ceresia_adventure\models;

use ceresia_adventure\framework\Model;
use ceresia_adventure\repositories\EnigmaRepository;
use ceresia_adventure\repositories\TrailRepository;
use ceresia_adventure\repositories\UserRepository;

class Enigma extends Model
{
    private int $enigmaId;
    private string $name;
    private string $image;
    private string $question;
    private string $answer;
    private int $difficulty;
    private int $estimatedTime;
    private String $hint;
    private Trail $trail;

    /**
     * Enigma constructor.
     *
     * @param int    $enigmaId
     * @param string $name
     * @param string $image
     * @param string $question
     * @param string $answer
     * @param int    $difficulty
     * @param int    $estimatedTime
     * @param String $hint
     * @param Trail  $trail
     */
    public function __construct(int $enigmaId, string $name, string $image, string $question, string $answer, int $difficulty, int $estimatedTime, string $hint, Trail $trail)
    {
        $this->enigmaId      = $enigmaId;
        $this->name          = $name;
        $this->image         = $image;
        $this->question      = $question;
        $this->answer        = $answer;
        $this->difficulty    = $difficulty;
        $this->estimatedTime = $estimatedTime;
        $this->hint          = $hint;
        $this->trail         = $trail;
    }


    public static function populate(array $enigmaSql): Enigma
    {
        $userRepository = new UserRepository();
        $enigmaRepository = new EnigmaRepository();
        $trailRepository = new TrailRepository();
//        $nbEnigmas = $enigmaRepository->countEnigmaByEnigma($enigmaSql['trail_id']);
        //$creator = $userRepository->findById($enigmaSql['user_id'])->row();
        $trail = $trailRepository->findById($enigmaSql['trail_id'])->row();
        $enigma = new Enigma(
            $enigmaSql['enigma_id'],
            $enigmaSql['name'],
            $enigmaSql['image_url'],
            $enigmaSql['question'],
            $enigmaSql['answer'],
            5,
            $enigmaSql['estimated_time'],
            $enigmaSql['hint'],
            $trail
            //$enigmaSql['creator'],
        );
        return $enigma;
    }

    /**
     * @return int
     */
    public function getEnigmaId(): int
    {
        return $this->enigmaId;
    }

    /**
     * @param int $enigmaId
     */
    public function setEnigmaId(int $enigmaId): void
    {
        $this->enigmaId = $enigmaId;
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
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @param string $question
     */
    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return int
     */
    public function getDifficulty(): int
    {
        return $this->difficulty;
    }

    /**
     * @param int $difficulty
     */
    public function setDifficulty(int $difficulty): void
    {
        $this->difficulty = $difficulty;
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
     * @return User
     */
    public function getCreator(): User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     */
    public function setCreator(User $creator): void
    {
        $this->creator = $creator;
    }

    /**
     * @return String
     */
    public function getHint(): string
    {
        return $this->hint;
    }

    /**
     * @param String $hint
     */
    public function setHint(string $hint): void
    {
        $this->hint = $hint;
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



}
