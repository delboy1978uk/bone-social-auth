<?php

namespace Bone\SocialAuth\Service;

use Hybridauth\Adapter\AdapterInterface;
use Hybridauth\Hybridauth;

class SocialAuthAdapterFactory
{
    /**
     * @param array $config
     * @return Hybridauth
     * @throws \Hybridauth\Exception\InvalidArgumentException
     */
    public function factory(array $config): Hybridauth
    {
        return new Hybridauth($config);
    }
}