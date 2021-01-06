<?php declare(strict_types=1);

namespace Bone\SocialAuth;

use Barnacle\Container;
use Barnacle\RegistrationInterface;
use Bone\Router\Router;
use Bone\Router\RouterConfigInterface;
use Bone\SocialAuth\View\Extension\SocialLogin;
use Bone\View\ViewRegistrationInterface;

class SocialAuthPackage implements RegistrationInterface, RouterConfigInterface, ViewRegistrationInterface
{
    /**
     * @param Container $c
     */
    public function addToContainer(Container $c)
    {

    }

    /**
     * @param Container $c
     * @param Router $router
     */
    public function addRoutes(Container $c, Router $router)
    {

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
        $config = $c->has('bone-social-auth') ? $c->get('bone-social-auth') : [];

        return [
            new SocialLogin($config),
        ];
    }
}
