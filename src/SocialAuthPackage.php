<?php declare(strict_types=1);

namespace Bone\SocialAuth;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Controller\Init;
use Bone\Router\Router;
use Bone\Router\RouterConfigInterface;
use Bone\SocialAuth\Controller\SocialLoginController;
use Bone\SocialAuth\Service\SocialAuthAdapterFactory;
use Bone\SocialAuth\Service\SocialAuthService;
use Bone\SocialAuth\View\Extension\SocialLogin;
use Bone\View\ViewRegistrationInterface;
use Del\Service\UserService;
use Del\SessionManager;

class SocialAuthPackage implements RegistrationInterface, RouterConfigInterface, ViewRegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {
        $c[SocialLoginController::class] = $c->factory(function (Container $c) {

            $loginRedirectRoute = '/user/home';

            if ($c->has('bone-user')) {
                $options = $c->get('bone-user');
                $loginRedirectRoute = $options['loginRedirectRoute'] ?? '/user/home';
            }

            $uploadsDir = $c->get('uploads_dir');
            $imgDir = $c->get('image_dir');
            $config = $c->has('bone-social-auth') ? $c->get('bone-social-auth') : [];
            $userService = $c->get(UserService::class);
            $service = new SocialAuthService($config, $userService, $uploadsDir, $imgDir, new SocialAuthAdapterFactory());
            $service->setSession($c->get(SessionManager::class));
            $controller = new SocialLoginController($service, $loginRedirectRoute);

            return $controller;
        });
    }

    /**
     * @param Container $c
     * @param Router $router
     */
    public function addRoutes(Container $c, Router $router)
    {
        $router->map('GET', '/user/login/via/{provider:word}', [SocialLoginController::class, 'login']);
    }

    /**
     * @return array
     */
    public function addViews(): array
    {
        return [];
    }

    /**
     * @param Container $c
     * @return array
     */
    public function addViewExtensions(Container $c): array
    {
        $config = $c->has('bone-social-auth') ? $c->get('bone-social-auth') : ['providers' => ''];

        return [
            new SocialLogin($config),
        ];
    }
}
