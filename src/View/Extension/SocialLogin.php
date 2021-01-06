<?php declare(strict_types=1);

namespace Bone\SocialAuth\View\Extension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class SocialLogin implements ExtensionInterface
{
    /**
     * @var array $config
     */
    private $config;

    /**
     * SocialLogin constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param Engine $engin
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('socialAuth', [$this, 'socialAuth']);
    }

    public function socialAuth(): string
    {
        $html = '<div>login via whatever, yay!</div>';

        foreach ($this->config as $provider => $data) {
            $html .= $provider . '<br>';
        }

        return $html;
    }
}

