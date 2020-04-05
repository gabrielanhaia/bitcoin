<?php


namespace App\Repositories;

use App\Entities\User as UserEntity;
use App\Exceptions\Api\InternalServerErrorException;
use App\Models\User;

/**
 * Class UserRepository for encapsulate the ORM/Data source...
 * In the future it could be MongoDB, External APIs...
 *
 * @package App\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class UserRepository extends Repository
{
    /** @var User $userModel Current user model. */
    protected $userModel;

    /**
     * UserRepository constructor.
     * @param User $user Model user (DI).
     */
    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    /**
     * Try to find an user by its email.
     *
     * @param string $email User email to be searched.
     * @return UserEntity
     */
    public function findByEmail(string $email): ?UserEntity
    {
        $userResult = $this->userModel->where('email', '=', $email)
            ->first();

        if (empty($userResult)) {
            return null;
        }

        $userEntity = new UserEntity($userResult->id);
        $userEntity->setName($userResult->name)
            ->setEmail($userResult->email)
            ->setPassword($userResult->password)
            ->setApiToken($userResult->api_token);

        return $userEntity;
    }

    /**
     * Create a new user.
     *
     * @param UserEntity $userEntity User to be created.
     * @throws \Exception
     */
    public function createUser(UserEntity $userEntity)
    {
        $userCreatedModel = $this->userModel::create([
            'name' => $userEntity->getName(),
            'email' => $userEntity->getEmail(),
            'password' => $userEntity->getPassword(),
            'api_token' => $userEntity->getApiToken()
        ]);

        if (empty($userCreatedModel)) {
            throw new InternalServerErrorException('Error creating user.');
        }

        $userEntity->setId($userCreatedModel->id);
    }
}
