<?php declare(strict_types=1);

namespace Bone\SocialAuth\Service;

use Bone\Server\SessionAwareInterface;
use Bone\Server\Traits\HasSessionTrait;
use DateTime;
use Del\Entity\User;
use Del\Factory\CountryFactory;
use Del\Person\Entity\Person;
use Del\Service\UserService;
use Del\Value\User\State;
use Exception;
use Hybridauth\Hybridauth;
use Hybridauth\Adapter\AdapterInterface;
use Hybridauth\User\Profile;

class SocialAuthService implements SessionAwareInterface
{
    use HasSessionTrait;

    /** @var array $config */
    private $config;

    /** @var UserService $userService */
    private $userService;

    /** @var string $uploadsDir */
    private $uploadsDir;

    /** @var string $imgDir */
    private $imgDir;

    /** @var SocialAuthAdapterFactory $factory */
    private $factory;

    /**
     * SocialAuthService constructor.
     * @param array $config
     */
    public function __construct(array $config, UserService $userService, string $uploadsDir, string $imgDir, SocialAuthAdapterFactory $factory)
    {
        $this->config = $config;
        $this->userService = $userService;
        $this->uploadsDir = $uploadsDir;
        $this->imgDir = $imgDir;
        $this->factory = $factory;
    }

    /**
     * @param string $provider
     * @return \Hybridauth\Adapter\AdapterInterface
     * @throws \Hybridauth\Exception\InvalidArgumentException
     * @throws \Hybridauth\Exception\UnexpectedValueException
     */
    public function getAuthAdapter(string $provider): AdapterInterface
    {
        if (array_key_exists($provider, $this->config['providers'])) {
            $this->config['callback'] .= '/' . strtolower($provider);
            $hybridauth = $this->factory->factory($this->config);
            $adapter = $hybridauth->authenticate($provider);

            return $adapter;
        }

        throw new Exception('SocialAuth Adapter not found', 404);
    }

    /**
     * @param Profile $profile
     * @return User
     */
    public function logInUser(Profile $profile): User
    {
        $email = $profile->email;

        if($user = $this->userService->findUserByEmail($email)) {
            $user->setLastLogin(new DateTime());
            $this->userService->saveUser($user);
        }

        if (!$user) {
            $user = $this->createUser($profile);
        }

        $this->session->set('user', $user->getId());

        return $user;
    }

    /**
     * @param Profile $profile
     * @return User
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createUser(Profile $profile): User
    {
        $now = new DateTime();
        $user = new User();
        $person = new Person();

        $user->setEmail($profile->email);
        $user->setLastLogin($now);
        $user->setRegistrationDate($now);
        $user->setState(new State(State::STATE_ACTIVATED));
        $user->setPerson($person);

        if ($profile->photoURL) {
            $contents = file_get_contents($profile->photoURL);
            $file = $this->imgDir . md5(microtime()) . '.jpg';
            file_put_contents($this->uploadsDir . $file, $contents);
            $person->setImage($file);
        }

        $person->setFirstname($profile->firstName);
        $person->setLastname($profile->lastName);

        $this->userService->getPersonSvc()->savePerson($person);
        $this->userService->changePassword($user, microtime()); // this saves user too

        return $user;
    }
}
