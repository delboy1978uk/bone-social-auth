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
     * @var array $customProviderConfigs
     */
    private $customProviderConfigs;

    /**
     * SocialLogin constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        if (array_key_exists('custom', $this->config)) {
            $this->customProviderConfigs = $this->config['custom'];
            unset ($this->config['custom']);
        }
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

        foreach ($this->config['providers'] as $provider => $data) {
            $html .= $this->addProviderLoginLink(strtolower($provider), $data);
        }

        if (isset($this->customProviderConfigs)) {
            foreach ($this->customProviderConfigs['providers'] as $provider => $data) {
                $html .= $this->addCustomProviderLoginLink(strtolower($provider), $data);
            }
        }
        
        return $html;
    }

    /**
     * @return string
     */
    private function addProviderLoginLink(string $provider, array $data): string
    {
        if (!$data['enabled']) {
            return '';
        }

        $icon = $this->getIcon($provider);
        $color = $this->getColor($provider);

        return '<a class="btn btn-primary rounded-circle m5" style="background-color: ' . $color . '" href="/user/login/via/' . $provider . '">' . $icon  . '</a>';
    }

    /**
     * @return string
     */
    private function addCustomProviderLoginLink(string $provider, array $data): string
    {
        if (!$data['enabled']) {
            return '';
        }

        $icon = $data['icon'];
        $color = $data['color'];

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

