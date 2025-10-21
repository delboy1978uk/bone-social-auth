<?php declare(strict_types=1);

namespace Bone\SocialAuth\Controller;

use Bone\SocialAuth\Service\SocialAuthService;
use Exception;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SocialLoginController
{
    public function __construct(
        private SocialAuthService $service,
        private string $loginRedirectRoute
    ) {}

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
