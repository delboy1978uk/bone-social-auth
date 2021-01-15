<?php

namespace BoneTest\SocialAuth\Controller;

use Bone\SocialAuth\Controller\SocialLoginController;
use Bone\SocialAuth\Service\SocialAuthService;
use Codeception\TestCase\Test;
use Hybridauth\Adapter\AdapterInterface;
use Hybridauth\User\Profile;
use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ResponseInterface;

class SocialLogInControllerTest extends Test
{
    /**
     * @var SocialLoginController
     */
    protected $controller;

    protected function _before()
    {
        $service = $this->createMock(SocialAuthService::class);
        $adapter = $this->createMock(AdapterInterface::class);
        $profile = new Profile();
        $adapter->method('isConnected')->willReturn(true);
        $adapter->method('getUserProfile')->willReturn($profile);
        $service->method('getAuthAdapter')->willReturn($adapter);
        $this->controller = new SocialLoginController($service, '/admin');
    }

    protected function _after()
    {
        unset($this->controller);
    }

    public function testLogin()
    {
        $request = new ServerRequest();
        $request = $request->withAttribute('provider', 'github');
        $response = $this->controller->login($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testLoginThrowsException()
    {
        $service = $this->createMock(SocialAuthService::class);
        $adapter = $this->createMock(AdapterInterface::class);
        $adapter->method('isConnected')->willReturn(false);
        $service->method('getAuthAdapter')->willReturn($adapter);
        $this->controller = new SocialLoginController($service, '/admin');

        $request = new ServerRequest();
        $request = $request->withAttribute('provider', 'github');
        $this->expectException(\Exception::class);
        $response = $this->controller->login($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
