<?php

namespace App\Helpers;

use BitWasp\Bitcoin\Address\AddressCreator;
use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;
use BitWasp\Bitcoin\Crypto\Random\Random;
use BitWasp\Bitcoin\Key\Factory\PrivateKeyFactory;
use BitWasp\Bitcoin\Key\KeyToScript\Factory\P2pkhScriptDataFactory;
use Illuminate\Support\Str;

/**
 * Class Token
 * @package App\Helpers
 *
 * TODO: This class can be improved in different classes....
 */
class Token
{
    /**
     * Generate a API token.
     *
     * @return string
     */
    public function generateApiToken(): string
    {
        return Str::random(60);
    }

    /**
     * Genereta wallet address.
     *
     * TODO: I am sure that this could in a real system who works with bitcoins
     * TODO: ... is much more complex. I would never put this code in a helper.
     */
    public function generateWalletAddress(): string
    {
        $privateKeyFactory = new PrivateKeyFactory;
        $privateKey = $privateKeyFactory->generateCompressed(new Random());
        $publicKey = $privateKey->getPublicKey();
        $addrCreator = new AddressCreator();
        $factory = new P2pkhScriptDataFactory();
        $scriptPubKey = $factory->convertKey($publicKey)->getScriptPubKey();
        $address = $addrCreator->fromOutputScript($scriptPubKey);

        $addressString = $address ? $address->getAddress() : null;

        return $addressString;
    }

    /**
     * Generate a random string.
     *
     * @param int|null $length
     * @return string
     */
    public function random(int $length = null): string
    {
        return Str::random($length);
    }
}
