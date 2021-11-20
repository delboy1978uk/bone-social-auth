# bone-social-auth
[![Latest Stable Version](https://poser.pugx.org/delboy1978uk/bone-social-auth/v/stable)](https://packagist.org/packages/delboy1978uk/bone-social-auth)
![build status](https://github.com/delboy1978uk/bone-social-auth/actions/workflows/master.yml/badge.svg) [![Code Coverage](https://scrutinizer-ci.com/g/delboy1978uk/bone-social-auth/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/delboy1978uk/bone-social-auth/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/delboy1978uk/bone-social-auth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/delboy1978uk/bone-social-auth/?branch=master) [![License](https://poser.pugx.org/delboy1978uk/bone-social-auth/license)](https://packagist.org/packages/delboy1978uk/bone-social-auth)



SocialAuth package for Bone Framework using HybridAuth
## installation
Use Composer
```
composer require delboy1978uk/bone-social-auth
```
Simply add to the `config/packages.php` after the Bone User Package
```php
<?php

// use statements here
use Bone\SocialAuth\SocialAuthPackage;

return [
    'packages' => [
        // packages here...,
        SocialAuthPackage::class,
    ],
    // ...
];
```
## settings
Create a settings file in the `config/` folder called `bone-social-auth.php`:
```php
return [
    'bone-social-auth' => [
        'callback' => 'https://awesome.scot/user/login/via',
        'providers' => [
            'Twitter' => [
                'enabled' => false,
                'keys' => [
                    'id' => '...',
                    'secret' => '...',
                ]
            ],
            'Google' => [
                'enabled' => true,
                'keys' => [
                    'id' => '...',
                    'secret' => '...',
                ]
            ],
            'Github' => [
                'enabled' => true,
                'keys' => [
                    'id' => '...',
                    'secret' => '...',
                ]
            ],
            'Facebook' => [
                'enabled' => true,
                'keys' => [
                    'key' => '...',
                    'secret' => '...',
                ]
            ],
        ]
    ],
];
```
### usage
You will probably want to have the social links on your `/user/login` link, so copy the View files from 
`vendor/delboy1978uk/bone-user/View/BoneUser` to the main App view `src/View/BoneUser` and add the followimg
config to `config/views.php` in order to override them:
```php
return [
    'views' => [
        'boneuser' => 'src/App/View/BoneUser',
    ],
];
```
In any view file, and if overriding `bone-user` then particularly `src/App/View/BoneUser/login.php`, you can call 
```php
<?= $this->socialAuth() ?>
```
which will display some links to log you in. Once logged in you will have a standard bone-user.
