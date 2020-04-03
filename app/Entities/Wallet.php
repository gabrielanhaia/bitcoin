<?php


namespace App\Entities;

/**
 * Class Wallet
 * @package App\Entities
 *
 * TODO: Create new fields (User,...)
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class Wallet extends AbstractEntity
{
    /** @var string $name Name of the wallet (identifier created by the user). */
    protected $name;

    /** @var string $address Address of the wallet (hash). */
    protected $address;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Wallet
     */
    public function setName(string $name): Wallet
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Wallet
     */
    public function setAddress(string $address): Wallet
    {
        $this->address = $address;
        return $this;
    }
}
