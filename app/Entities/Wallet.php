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

    /** @var User $user User who owns the wallet. */
    protected $user;

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'user' => $this->getUser() ? $this->getUser()->toArray() : null
        ];
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Wallet
     */
    public function setUser(User $user): Wallet
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Wallet
     */
    public function setName(string $name = null): Wallet
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
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
