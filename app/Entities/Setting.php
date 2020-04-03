<?php


namespace App\Entities;

/**
 * Class Setting
 * @package App\Entities
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class Setting
{
    /** @var string $name Name of the setting. */
    protected $name;

    /** @var mixed $value Value of the setting. */
    protected $value;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Setting
     */
    public function setName(string $name): Setting
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Setting
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
