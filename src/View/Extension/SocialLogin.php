<?php declare(strict_types=1);

namespace Bone\SocialAuth\View\Extension;

use Del\Icon;
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

    /**
     * @return string
     */
    public function socialAuth(): string
    {
        $html = '';

        foreach ($this->config as $provider => $data) {
            $html .= $this->addProviderLoginLink($provider, $data);
        }

        return $html;
    }

    /**
     * @return string
     */
    private function addProviderLoginLink(string $provider, array $data): string
    {
        $icon = $this->getIcon($provider);
        $color = $this->getColor($provider);

        return '<a class="btn btn-primary rounded-circle m5" style="background-color: ' . $color . '" href="/user/login/via/' . $provider . '">' . $icon  . '</a>';
    }

    /**
     * @param string $provider
     * @return string
     */
    private function getIcon(string $provider): string
    {
        switch ($provider) {
            case 'facebook':
                $icon = Icon::FACEBOOK;
                break;
            case 'github':
                $icon = Icon::GITHUB;
                break;
            case 'twitter':
                $icon = Icon::TWITTER;
                break;
            case 'google':
                $icon = Icon::GOOGLE;
                break;
            default;
                $icon = '';
                break;
        }

        return $icon;
    }

    /**
     * @param string $provider
     * @return string
     */
    private function getColor(string $provider): string
    {
        switch ($provider) {
            case 'facebook':
                $color = '#2d88ff';
                break;
            case 'github':
                $color = '#7c007c';
                break;
            case 'twitter':
                $color = '#1da1f2';
                break;
            case 'google':
                $color = '#ea4335';
                break;
            default;
                $color = '';
                break;
        }

        return $color;
    }
}

