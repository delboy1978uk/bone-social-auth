# socialauth
SocialAuth package for Bone Mvc Framework
## installation
Use Composer
```
composer require delboy1978uk/bone-social-auth
```
## usage
Simply add to the `config/packages.php`
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
You will probably want to have the social links on  your `/user/login` link, so copy the View files from 
`vendor/delboy1978uk/bone-user/View/BoneUser` to the main App view `src/View/BoneUser` and add the followimg
config to `config/views.php`:
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