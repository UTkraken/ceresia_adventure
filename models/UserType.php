<?php

namespace ceresia_adventure\models;

use ceresia_adventure\framework\Model;

class UserType extends Model
{
    private int $userTypeId;
    private string $name;

    /**
     * @param int $user_type_id
     * @param string $name
     */
    public function __construct(int $user_type_id, string $name)
    {
        parent::__construct();
        $this->user_type_id = $user_type_id;
        $this->name = $name;
    }

    public static function populate(array $userTypeSql): UserType
    {
        $userType = new UserType($userTypeSql['user_type_id'], $userTypeSql['name']);
        return $userType;
    }

    /**
     * @return int
     */
    public function getUserTypeId(): int
    {
        return $this->user_type_id;
    }

    /**
     * @param int $user_type_id
     */
    public function setUserTypeId(int $user_type_id): void
    {
        $this->user_type_id = $user_type_id;
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


}