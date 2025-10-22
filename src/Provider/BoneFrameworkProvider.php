<?php

declare(strict_types=1);

namespace Bone\SocialAuth\Provider;

use Hybridauth\Adapter\OAuth2;
use Hybridauth\Exception\UnexpectedValueException;
use Hybridauth\Data;
use Hybridauth\User;

class BoneFrameworkProvider extends OAuth2
{
    protected $scope = 'basic';
    protected $apiBaseUrl = 'https://boneframework.docker/api';
    protected $authorizeUrl = 'https://boneframework.docker/oauth2/authorize';
    protected $accessTokenUrl = 'https://boneframework.docker/oauth2/token';
    protected $callback = 'https://boneframework.docker/oauth2/token';

    /* optional: set any extra parameters or settings */
    protected function initialize()
    {
        parent::initialize();
        $this->tokenExchangeHeaders = [
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];

        $codeVerifier = $this->getStoredData('codeVerifier');
        if (!$codeVerifier) {
            $codeVerifier = $this->generateCodeVerifier();
            $this->storeData('codeVerifier', $codeVerifier);
        }

        $codeChallenge = $this->getStoredData('codeChallenge');
        if (!$codeChallenge) {
            $codeChallenge = $this->generateCodeChallenge($codeVerifier);
            $this->storeData('codeChallenge', $codeChallenge);
        }

        // Set additional authorize URL parameters required by Twitter
        $this->AuthorizeUrlParameters += [
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
            'response_type' => 'code',
        ];

        if ($this->isRefreshTokenAvailable()) {
            $this->tokenRefreshParameters += [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'refresh_token',
            ];
        }
    }

    protected function generateCodeVerifier()
    {
        $random = random_bytes(64);
        return rtrim(strtr(base64_encode($random), '+/', '-_'), '=');
    }

    protected function generateCodeChallenge($codeVerifier)
    {
        $hash = hash('sha256', $codeVerifier, true);
        return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }

    protected function exchangeCodeForAccessToken($code)
    {
        $parameters = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->callback,
            'code_verifier' => $this->getStoredData('codeVerifier'),
        ];

        $response = $this->httpClient->request(
            $this->accessTokenUrl,
            $this->tokenExchangeMethod,
            $parameters,
            $this->tokenExchangeHeaders,
        );

        $this->validateApiResponse('Unable to exchange code for API access token');

        return $response;
    }

    function getUserProfile()
    {
        $response = $this->apiRequest('/user/profile');
        $data = (new Data\Collection($response))->toArray();
        $userProfile = new User\Profile();
        $userProfile->identifier = $data['id'];
        $userProfile->email = $data['email'];
        $userProfile->displayName = $data['person']->firstname . ' ' . $data['person']->lastname;
        $userProfile->photoURL = $this->apiBaseUrl . 'user/image';

        return $userProfile;
    }
}
