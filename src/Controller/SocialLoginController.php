<?php declare(strict_types=1);

namespace Bone\SocialAuth\Controller;

use Bone\Http\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SocialLoginController
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $provider = $request->getAttribute('provider');

        return new HtmlResponse($provider);
    }
}