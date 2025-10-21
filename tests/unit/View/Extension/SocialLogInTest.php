<?php

namespace BoneTest\SocialAuth\View\Extension;

use Bone\SocialAuth\View\Extension\SocialLogin;
use Bone\View\ViewEngine;
use Codeception\Test\Unit;

class SocialLogInTest extends Unit
{
    /**
     * @var SocialLogin
     */
    protected $extension;

    protected function _before()
    {
        $this->extension = new SocialLogin(['providers' => [
            'facebook' => ['enabled' => true],
            'github' => ['enabled' => true],
            'google' => ['enabled' => true],
            'twitter' => ['enabled' => true],
            'random' => ['enabled' => true],
            'provider' => ['enabled' => false],
        ]]);
    }

    protected function _after()
    {
        unset($this->extension);
    }

    public function testRegister()
    {
        $viewEngine = $this->createMock(ViewEngine::class);
        $viewEngine->expects($this->once())->method('registerFunction');
        $this->extension->register($viewEngine);
    }

    public function testLinks()
    {
        $html = $this->extension->socialAuth();
        $this->assertEquals('<a class="btn btn-primary rounded-circle m5" style="background-color: #2d88ff" href="/user/login/via/facebook"><i class="fa fa-facebook"></i></a><a class="btn btn-primary rounded-circle m5" style="background-color: #7c007c" href="/user/login/via/github"><i class="fa fa-github"></i></a><a class="btn btn-primary rounded-circle m5" style="background-color: #ea4335" href="/user/login/via/google"><i class="fa fa-google"></i></a><a class="btn btn-primary rounded-circle m5" style="background-color: #1da1f2" href="/user/login/via/twitter"><i class="fa fa-twitter"></i></a><a class="btn btn-primary rounded-circle m5" style="background-color: " href="/user/login/via/random"></a>', $html);
    }
}
