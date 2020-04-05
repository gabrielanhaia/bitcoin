<?php


namespace Tests\Unit\Repositories;

use App\Entities\User as UserEntity;
use App\Exceptions\Api\InternalServerErrorException;
use App\Models\User;
use App\Repositories\UserRepository;
use Tests\TestCase;

/**
 * Class UserRepositoryTest
 * @package Tests\Unit\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class UserRepositoryTest extends TestCase
{
    /**
     * Test when trying to find a user by email and the user is not found.
     */
    public function testFindUserByEmailWhenUserNotFound()
    {
        $userEmail = 'USER_EMAIL@TEST.PAXFUL';

        $userEloquentModelMock = \Mockery::mock(User::class);
        $userEloquentModelMock->shouldReceive('where')
            ->once()
            ->with('email', '=', $userEmail)
            ->andReturnSelf();

        $userEloquentModelMock->shouldReceive('first')
            ->once()
            ->withNoArgs()
            ->andReturnNull();

        $userRepository = new UserRepository($userEloquentModelMock);
        $result = $userRepository->findByEmail($userEmail);

        $this->assertNull($result);
    }

    /**
     * Test success finding user by email.
     */
    public function testSuccessFindUSerByEmail()
    {
        $userName = 'USER_NAME_TEST';
        $userEmail = 'USER_EMAIL@TEST.PAXFUL';
        $password = 'PASSWORD_TEST';
        $apiToken = 'API_TOKEN_TEST';
        $userId = 32323231233221231;

        $expectedUserResult = new UserEntity($userId);
        $expectedUserResult->setName($userName)
            ->setEmail($userEmail)
            ->setPassword($password)
            ->setApiToken($apiToken);

        $userEloquentModelMock = \Mockery::mock(User::class);

        $userEloquentModelMock->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($userId);

        $userEloquentModelMock->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn($userName);

        $userEloquentModelMock->shouldReceive('getAttribute')
            ->with('email')
            ->andReturn($userEmail);

        $userEloquentModelMock->shouldReceive('getAttribute')
            ->with('password')
            ->andReturn($password);

        $userEloquentModelMock->shouldReceive('getAttribute')
            ->with('api_token')
            ->andReturn($apiToken);

        $userEloquentModelMock->shouldReceive('where')
            ->once()
            ->with('email', '=', $userEmail)
            ->andReturnSelf();

        $userEloquentModelMock->shouldReceive('first')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $userRepository = new UserRepository($userEloquentModelMock);
        $result = $userRepository->findByEmail($userEmail);
        $this->assertEquals($expectedUserResult, $result);

    }

    /**
     * Test unexpected error trying to create a new user.
     */
    public function testInternalServerErrorCreateUser()
    {
        $userName = 'USER_NAME_TEST';
        $userEmail = 'USER_EMAIL@TEST.PAXFUL';
        $password = 'PASSWORD_TEST';
        $apiToken = 'API_TOKEN_TEST';

        $userEntity = new UserEntity;
        $userEntity->setName($userName)
            ->setEmail($userEmail)
            ->setPassword($password)
            ->setApiToken($apiToken);

        $userEloquentModelMock = \Mockery::mock(User::class);
        $userEloquentModelMock->shouldReceive('create')
            ->once()
            ->with([
                'name' => $userEntity->getName(),
                'email' => $userEntity->getEmail(),
                'password' => $userEntity->getPassword(),
                'api_token' => $userEntity->getApiToken()
            ])
            ->andReturnNull();

        $this->expectException(InternalServerErrorException::class);
        $this->expectExceptionMessage('Error creating user.');

        $userRepository = new UserRepository($userEloquentModelMock);
        $userRepository->createUser($userEntity);
    }


    /**
     * Test success creating a new user.
     */
    public function testSuccessCreateUser()
    {
        $userName = 'USER_NAME_TEST';
        $userEmail = 'USER_EMAIL@TEST.PAXFUL';
        $password = 'PASSWORD_TEST';
        $apiToken = 'API_TOKEN_TEST';
        $userId = 32323231233221231;

        $userEntity = new UserEntity;
        $userEntity->setName($userName)
            ->setEmail($userEmail)
            ->setPassword($password)
            ->setApiToken($apiToken);

        $userEloquentModelMock = \Mockery::mock(User::class);
        $userEloquentModelMock->shouldReceive('create')
            ->once()
            ->with([
                'name' => $userEntity->getName(),
                'email' => $userEntity->getEmail(),
                'password' => $userEntity->getPassword(),
                'api_token' => $userEntity->getApiToken()
            ])
            ->andReturnSelf();

        $userEloquentModelMock->shouldReceive('getAttribute')
            ->once()
            ->with('id')
            ->andReturn($userId);

        $userRepository = new UserRepository($userEloquentModelMock);
        $expectedResult = clone $userEntity;
        $expectedResult->setId($userId);
        $userRepository->createUser($userEntity);

        $this->assertEquals($expectedResult, $userEntity);
    }
}
