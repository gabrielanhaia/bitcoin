<?php

namespace App\Http\Controllers\Api\V1;

use App\Entities\User;
use App\Exceptions\Api\ConflictException;
use App\Exceptions\Api\InternalServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateUserRequest;
use App\Http\Resources\V1\User as UserResource;
use App\Services\UserService;

/**
 * Class UserController
 * @package App\Http\Controllers\Api\V1
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class UserController extends Controller
{
    /** @var UserService $userService Service related to user operations. */
    protected $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Method responsible for creating new users.
     *
     * @param CreateUserRequest $request Request object with the required fields to create a new user.
     * @return UserResource
     * @throws ConflictException
     * @throws InternalServerErrorException
     */
    public function create(CreateUserRequest $request)
    {
        $userEntity = new User;
        $userEntity->setName($request->post('name'))
            ->setEmail($request->post('email'))
            ->setPassword($request->post('password'));

        $createdUser = $this->userService->createUser($userEntity);

        return new UserResource($createdUser);
    }
}
