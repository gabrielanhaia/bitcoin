<?php


namespace App\Entities;

/**
 * Class AbstractEntity
 * @package App\Entities
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
abstract class AbstractEntity
{
    /** @var int|null Entity identifier (database). */
    protected $id;

    /**
     * AbstractEntity constructor.
     * @param int|null $id
     */
    public function __construct(int $id = null)
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return AbstractEntity
     */
    public function setId(?int $id): AbstractEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    abstract public function toArray();
}
