<?php


namespace App\Entities;

/**
 * Class Currency
 * @package App\Entities
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class Currency  extends AbstractEntity
{

    /** @var string $name Name of the currency. */
    protected $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Currency
     */
    public function setName(string $name): Currency
    {
        $this->name = $name;
        return $this;
    }
}
