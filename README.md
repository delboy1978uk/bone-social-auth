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