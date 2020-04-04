<?php


namespace Tests\Unit\Repositories;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use Tests\TestCase;

/**
 * Class SettingRepositoryTest
 * @package Tests\Unit\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class SettingRepositoryTest extends TestCase
{
    /**
     * Method responsible for test the method {@see SettingRepository::getSetting()}
     * when the setting is not found.
     */
    public function testGetSettingWhenSettingNotFound()
    {
        $settingName = 'SETTING_NAME_TEST';
        $settingEloquentModelMock = \Mockery::mock(Setting::class);

        $settingEloquentModelMock->shouldReceive('where')
            ->once()
            ->with('name', '=', $settingName)
            ->andReturnSelf();

        $settingEloquentModelMock->shouldReceive('first')
            ->once()
            ->withNoArgs()
            ->andReturnNull();

        $settingRepository = new SettingRepository($settingEloquentModelMock);
        $settingResponse = $settingRepository->getSetting($settingName);

        $this->assertNull($settingResponse);
    }

    /**
     * Method responsible for test the method {@see SettingRepository::getSetting()} when the setting has been found.
     */
    public function testGetSetting()
    {
        $settingName = 'SETTING_NAME_TEST';
        $settingValue = 'SETTING_VALUE_TEST';
        $settingId = 9998988999889;

        $settingEloquentModelMock = \Mockery::mock(Setting::class);

        $expectedResponse = new \App\Entities\Setting($settingId);
        $expectedResponse->setValue($settingValue)
            ->setName($settingName);

        $settingEloquentModelMock->shouldReceive('where')
            ->once()
            ->with('name', '=', $settingName)
            ->andReturnSelf();

        $settingEloquentModelMock->shouldReceive('first')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $settingEloquentModelMock->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($settingId);

        $settingEloquentModelMock->shouldReceive('getAttribute')
            ->with('value')
            ->andReturn($settingValue);

        $settingRepository = new SettingRepository($settingEloquentModelMock);
        $settingResponse = $settingRepository->getSetting($settingName);

        $this->assertEquals($expectedResponse, $settingResponse);
    }
}
