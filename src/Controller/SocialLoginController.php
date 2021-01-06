<?php declare(strict_types=1);

namespace Bone\SocialAuth\Controller;

use Bone\Http\Response\HtmlResponse;
use Bone\SocialAuth\Service\SocialAuthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SocialLoginController
{
    /** @var SocialAuthService $service */
    private $service;

    /**
     * SocialLoginController constructor.
     * @param SocialAuthService $service
     */
    public function __construct(SocialAuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $provider = \ucfirst($request->getAttribute('provider'));
        $adapter = $this->service->getAuthAdapter($provider);

        if ($adapter->isConnected()) {
            $userProfile = $adapter->getUserProfile();
            var_dump($userProfile);
            $adapter->disconnect();
        }

        return new HtmlResponse($provider);
    }
}