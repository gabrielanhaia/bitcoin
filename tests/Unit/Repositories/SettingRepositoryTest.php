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
        $settingName = 'test_setting_name';
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
}
