<?php

namespace BoneTest\SocialAuth;

use Barnacle\Container;
use Bone\Router\Router;
use Bone\SocialAuth\Controller\SocialLoginController;
use Bone\SocialAuth\SocialAuthPackage;
use Codeception\Test\Unit;
use Del\Service\UserService;
use Del\SessionManager;

class PackageTest extends Unit
{
    /**
     * @var SocialAuthPackage
     */
    protected $package;

    protected function _before()
    {
        $this->package = new SocialAuthPackage();
    }

    protected function _after()
    {
        unset($this->package);
    }

    public function testAddViews()
    {
        $views = $this->package->addViews();
        $this->assertCount(0, $views);
    }

    public function testAddRoutes()
    {
        $container = new Container();
        $router = new Router();
        $this->package->addRoutes($container, $router);
        $this->assertCount(1, $router->getRoutes());
    }

    public function testGetViewExtensions()
    {
        $container = new Container();
        $extensions = $this->package->addViewExtensions($container);
        $this->assertCount(1, $extensions);
    }

    public function testAddToContainer()
    {
        $container = new Container([
            'bone-user' => [
                'loginRedirectRoute' => '/admin',
            ],
            'uploads_dir' => 'data/uploads/',
            'image_dir' => 'img/',
            UserService::class => $this->createMock(UserService::class),
            SessionManager::class => SessionManager::getInstance(),
        ]);
        $this->package->addToContainer($container);
        $this->assertTrue($container->has(SocialLoginController::class));
        $this->assertInstanceOf(SocialLoginController::class, $container->get(SocialLoginController::class));
    }


}
