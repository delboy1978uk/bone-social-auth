<?php

namespace BoneTest\SocialAuth\Service;

use Bone\SocialAuth\Service\SocialAuthAdapterFactory;
use Codeception\Test\Unit;
use Hybridauth\Hybridauth;

class SocialAuthAdapterFactoryTest extends Unit
{
    public function testFactory()
    {
        $factory = new SocialAuthAdapterFactory();
        $auth = $factory->factory([]);
        $this->assertInstanceOf(Hybridauth::class, $auth);
    }
}
