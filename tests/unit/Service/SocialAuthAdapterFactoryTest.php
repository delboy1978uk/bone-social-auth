<?php

namespace BoneTest\SocialAuth\Service;

use Bone\SocialAuth\Service\SocialAuthAdapterFactory;
use Codeception\TestCase\Test;
use Hybridauth\Hybridauth;

class SocialAuthAdapterFactoryTest extends Test
{
    public function testFactory()
    {
        $factory = new SocialAuthAdapterFactory();
        $auth = $factory->factory([]);
        $this->assertInstanceOf(Hybridauth::class, $auth);
    }
}
