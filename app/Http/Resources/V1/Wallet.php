<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Wallet
 * @package App\Http\Resources\V1
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class Wallet extends JsonResource
{

    /** @var array $extraData Additional data to be returned. */
    private $extraData;

    /**
     * Wallet constructor.
     * @param $resource
     * @param array $extraData
     */
    public function __construct($resource, array $extraData = [])
    {
        parent::__construct($resource);

        $this->extraData = $extraData;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'user_id' => $this->getUser() ? $this->getUser()->getId() : null,
            'extra_data' => $this->extraData
        ];

        return $result;
    }
}
