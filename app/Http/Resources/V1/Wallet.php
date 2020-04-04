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
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'user_id' => $this->getUser() ? $this->getUser()->getId() : null
        ];
    }
}
