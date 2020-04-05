<?php


namespace Tests\Unit;

use App\Entities\User as UserEntity;
use App\Exceptions\Api\ConflictException;
use App\Helpers\Facades\TokenFacade;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Class UserServiceTest responsible for the test of the class {@see UserService}.
 * @package Tests\Unit
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class UserServiceTest extends TestCase
{
    /**
     * Test error trying to create user (Email already exists).
     */
    public function testCreateUserErrorUserAlreadyExists()
    {
        $userEmail = 'USER@EMAIL.TEST';
        $userEntity = new UserEntity;
        $userEntity->setEmail($userEmail);

        $userRepositoryMock = \Mockery::mock(UserRepository::class);
        $userRepositoryMock->shouldReceive('findByEmail')
            ->once()
            ->with($userEmail)
            ->andReturn($userEntity);

        $this->expectException(ConflictException::class);
        $this->expectExceptionMessage('User already exists.');

        $userService = new UserService($userRepositoryMock);
        $userService->createUser($userEntity);
    }

    /**
     * Test success creating user.
     */
    public function testCreateUserSuccess()
    {
        $userEmail = 'USER@EMAIL.TEST';
        $userPassword = 'USER_PASSWORD';
        $userPasswordHash = 'HASH_USER_PASSWORD';
        $userApiToken = 'USER_API_TOKEN';

        $userEntity = new UserEntity;
        $userEntity->setEmail($userEmail)
            ->setPassword($userPassword);

        $userRepositoryMock = \Mockery::mock(UserRepository::class);
        $userRepositoryMock->shouldReceive('findByEmail')
            ->once()
            ->with($userEmail)
            ->andReturnNull();

        Hash::shouldReceive('make')
            ->once()
            ->with($userPassword)
            ->andReturn($userPasswordHash);

        TokenFacade::shouldReceive('generateApiToken')
            ->once()
            ->withNoArgs()
            ->andReturn($userApiToken);

        $userRepositoryMock->shouldReceive('createUser')
            ->once()
            ->with($userEntity);

        $expectedUserEntity = clone $userEntity;

        $userService = new UserService($userRepositoryMock);
        $userService->createUser($userEntity);

        $expectedUserEntity->setPassword($userPasswordHash)
            ->setApiToken($userApiToken);

        $this->assertEquals($expectedUserEntity, $userEntity);
    }
}
