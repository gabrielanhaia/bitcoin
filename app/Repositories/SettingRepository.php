<?php


namespace App\Repositories;

use App\Entities\Setting as SettingEntity;
use App\Models\Setting;

/**
 * Class SettingRepository
 * @package App\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel.com>
 */
class SettingRepository extends Repository
{
    /** @var Setting $settingModel Model of settings. */
    private $settingModel;

    /**
     * SettingRepository constructor.
     * @param Setting $settingModel Model of settings (Eloquent model).
     */
    public function __construct(Setting $settingModel)
    {
        $this->settingModel = $settingModel;
    }

    /**
     * Search of a setting with the exact identifier.
     *
     * @param string $settingName Exact name of the setting.
     * @return SettingEntity|null
     */
    public function getSetting(string $settingName): ?SettingEntity
    {
        $settingResult = $this->settingModel
            ->where('name', '=', $settingName)
            ->first();

        if (empty($settingResult)) {
            return null;
        }

        $settingEntity = new SettingEntity($settingResult->id);
        $settingEntity->setName($settingName)
            ->setValue($settingResult->value);

        return $settingEntity;
    }
}
