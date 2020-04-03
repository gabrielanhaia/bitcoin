<?php

namespace App\Services;

use App\Entities\User as UserEntity;
use App\Exceptions\Api\{ConflictException, InternalServerErrorException};
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class UserService
 * @package App\Services
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class UserService
{
    /** @var UserRepository $userRepository Repository of users. */
    private $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Try to find an user by its Id.
     *
     * @param int $userId User identifier to be searched.
     * @return UserEntity
     */
    public function find(int $userId): ?UserEntity
    {
        $userEntity = $this->userRepository->find($userId);

        return $userEntity;
    }

    /**
     * Method responsible for creating a new user.
     *
     * @param UserEntity $userEntity Entity for the user to be created.
     * @return UserEntity
     * @throws ConflictException
     * @throws InternalServerErrorException
     */
    public function createUser(UserEntity $userEntity): UserEntity
    {
        $userExists = $this->userRepository->findByEmail($userEntity->getEmail());

        if (!empty($userExists)) {
            throw new ConflictException('User already exists.');
        }

        $userPassword = Hash::make($userEntity->getPassword());
        $userApiToken = Str::random(60);

        $userEntity->setPassword($userPassword)
            ->setApiToken($userApiToken);

        $this->userRepository->createUser($userEntity);

        return $userEntity;
    }
}
