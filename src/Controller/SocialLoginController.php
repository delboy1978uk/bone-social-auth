<?php declare(strict_types=1);

namespace Bone\SocialAuth\Controller;

use Bone\Http\Response\HtmlResponse;
use Bone\SocialAuth\Service\SocialAuthService;
use Exception;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SocialLoginController
{
    /** @var SocialAuthService $service */
    private $service;

    /** @var string $loginRedirectRoute */
    private $loginRedirectRoute;

    /**
     * SocialLoginController constructor.
     * @param SocialAuthService $service
     */
    public function __construct(SocialAuthService $service, string $loginRedirectRoute)
    {
        $this->service = $service;
        $this->loginRedirectRoute = $loginRedirectRoute;
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
            $adapter->disconnect();
            $this->service->logInUser($userProfile);

            return new RedirectResponse($this->loginRedirectRoute);
        }

        throw new Exception('Something went wrong', 500);
    }
}