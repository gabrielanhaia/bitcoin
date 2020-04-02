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
    /** @var integer $id Identifier of the setting. */
    protected $id;

    /** @var string $name Name of the setting. */
    protected $name;

    /** @var mixed $value Value of the setting. */
    protected $value;

    /**
     * Setting constructor.
     * @param int $id
     * @param string $name
     * @param mixed $value
     */
    public function __construct(int $id, string $name, mixed $value)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Setting
     */
    public function setId(int $id): Setting
    {
        $this->id = $id;
        return $this;
    }

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
