<?php

namespace ceresia_adventure\models;

use ceresia_adventure\framework\Model;
use ceresia_adventure\repositories\UserTypeRepository;

class User extends Model
{
    private int $userId;
    private string $pseudo;
    private string $email;
    private string $password;
    private UserType $userType;
    private string $departement;

    /**
     * @param int $userId
     * @param string $pseudo
     * @param string $email
     * @param string $password
     * @param UserType $userType
     * @param string $departement
     */
    public function __construct(int $userId, string $pseudo, string $email, string $password, UserType $userType, string $departement)
    {
        $this->userId = $userId;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->password = $password;
        $this->userType = $userType;
        $this->departement = $departement;
    }

    public static function populate(array $userSql): User
    {
        $userTypeRepository = new UserTypeRepository();

        $userType = $userTypeRepository->findById($userSql['user_type_id'])->row();
        $user = new User($userSql['user_id'], $userSql['pseudo'], $userSql['email'], $userSql['password'], $userType, $userSql['departement']);
        return $user;
    }

    /**
     * @return UserType
     */
    public function getUserType(): UserType
    {
        return $this->userType;
    }

    /**
     * @param UserType $userType
     */
    public function setUserType(UserType $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
}