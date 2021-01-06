<?php declare(strict_types=1);

namespace Bone\SocialAuth\Service;

use Exception;
use Hybridauth\Hybridauth;

class SocialAuthService
{
    /** @var array $config */
    private $config;

    /**
     * SocialAuthService constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getAuthAdapter(string $provider)
    {
        if (array_key_exists($provider, $this->config['providers'])) {
            $hybridauth = new Hybridauth($this->config);
            $adapter = $hybridauth->authenticate($provider);

            return $adapter;
        }

        throw new Exception('SocialAuth Adapter not found', 404);
    }
}
