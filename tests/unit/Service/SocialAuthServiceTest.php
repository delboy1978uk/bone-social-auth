<?php

namespace BoneTest\SocialAuth\Service;

use Bone\SocialAuth\Service\SocialAuthAdapterFactory;
use Bone\SocialAuth\Service\SocialAuthService;
use Codeception\Test\Unit;
use Del\Entity\User;
use Del\Service\UserService;
use Del\SessionManager;
use Hybridauth\Adapter\AdapterInterface;
use Hybridauth\Hybridauth;
use Hybridauth\User\Profile;

class SocialAuthServiceTest extends Unit
{
    /**
     * @var SocialAuthService
     */
    protected $service;

    protected function _before()
    {
        $userService = $this->createMock(UserService::class);
        $factory = $this->createMock(SocialAuthAdapterFactory::class);
        $auth = $this->createMock(Hybridauth::class);
        $adapter = $this->createMock(AdapterInterface::class);
        $factory->method('factory')->willReturn($auth);
        $auth->method('authenticate')->willReturn($adapter);
        $config = [
            'callback' => 'https://awesome.scot/user/login/via',
            'providers' => [
                'github' => [
                    'enabled' => true,
                    'keys' => [
                        'id' => 'dfgh',
                        'secret' => 'jhtdjd',
                    ]
                ],
            ]
        ];
        $this->service = new SocialAuthService($config, $userService, 'tests/_data/', 'img/', $factory);
        $this->service->setSession(SessionManager::getInstance());
    }

    protected function _after()
    {
        unset($this->service);
    }

    public function testGetAuthAdapter()
    {
        $provider = $this->service->getAuthAdapter('github');
        $this->assertInstanceOf(AdapterInterface::class, $provider);
    }

    public function testGetAuthAdapterThrowsException()
    {
        $this->expectException(\Exception::class);
        $provider = $this->service->getAuthAdapter('facebook');
    }

    public function testLoginNewUser()
    {
        $profile = new Profile();
        $profile->email = 'man@work.com';
        $profile->photoURL = 'https://raw.githubusercontent.com/delboy1978uk/boneframework/master/public/img/pirate.png';
        $user = $this->service->logInUser($profile);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testLoginUser()
    {
        $profile = new Profile();
        $profile->email = 'man@work.com';
        $mirror = new \ReflectionClass(SocialAuthService::class);
        $prop = $mirror->getProperty('userService');
        $userService = $prop->getValue($this->service);
        $userService->method('findUserByEmail')->willReturn(new User());
        $this->service->logInUser($profile);
        $user = $this->service->logInUser($profile);
        $this->assertInstanceOf(User::class, $user);
    }
}
