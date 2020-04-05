<?php

namespace App\Services;

use App\Entities\User as UserEntity;
use App\Helpers\Facades\TokenFacade;
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
     * Method responsible for creating a new user.
     *
     * @param UserEntity $userEntity Entity for the user to be created.
     * @return UserEntity
     * @throws ConflictException
     * @throws InternalServerErrorException
     * @throws \App\Exceptions\Api\UnprocessableEntityException
     */
    public function createUser(UserEntity $userEntity): UserEntity
    {
        $userExists = $this->userRepository->findByEmail($userEntity->getEmail());

        if (!empty($userExists)) {
            throw new ConflictException('User already exists.');
        }

        $userPassword = Hash::make($userEntity->getPassword());
        $userApiToken = TokenFacade::generateApiToken();

        $userEntity->setPassword($userPassword)
            ->setApiToken($userApiToken);

        $this->userRepository->createUser($userEntity);

        return $userEntity;
    }
}
