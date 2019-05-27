<?php

namespace Espricho\Components\Auth;

use Exception;
use Doctrine\ORM\EntityRepository;
use Espricho\Components\Contracts\Authenticatable;
use Espricho\Components\Auth\Events\UserLoggedInEvent;
use function hash_string;
use Lcobucci\JWT\Builder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Espricho\Components\Auth\Exceptions\UserModelNotFound;

use function em;
use function is_null;
use function class_exists;
use function method_exists;

/**
 * Class Auth provides authentication
 *
 * @package Espricho\Components\Auth
 */
class Auth
{
    /**
     * @var Authenticatable
     */
    protected $userModel;

    /**
     * Auth constructor.
     *
     * @throws UserModelNotFound
     */
    public function __construct()
    {
        $userModel = app()->getConfig('auth.model', '');
        if (!class_exists($userModel)) {
            throw new UserModelNotFound("You must specify an auth.model parameter inside auth.yaml file");
        }
        $this->userModel = $userModel;
    }

    /**
     * Try to login a user with a given username and password. If all thing was okay
     * returns a JWT token, otherwise return null
     *
     * @param $username
     * @param $password
     *
     * @return null|string
     */
    public function login($username, $password): ?string
    {
        $repository = $this->getUserRepository();
        if (is_null($repository)) {
            return null;
        }

        $user = $repository->findOneBy(
             [
                  'username' => $username,
                  'password' => hash_string($password),
             ]
        );
        if (is_null($user)) {
            return null;
        }

        try {
            app()->get(EventDispatcher::class)
                 ->dispatch(Authenticatable::EVENT_LOGGED_IN, new UserLoggedInEvent($user))
            ;
        } catch (Exception $e) {
            // there is no dispatcher and it's okay ;)
        }

        return $this->generateToken($user);
    }

    /**
     * Register a new user with given data
     *
     * @param array $data
     * @param bool  $isAdmin
     *
     * @return null|string
     * @throws \Doctrine\ORM\ORMException
     */
    public function register(array $data, bool $isAdmin = false): ?string
    {
        $user = new $this->userModel;

        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setEmail($data['email'] ?? null);
        $user->setIsAdmin($isAdmin);

        em()->persist($user);
        em()->flush($user);

        if ($user->getId()) {
            return $this->generateToken($user);
        }

        return null;
    }

    /**
     * Set custom claims provided by user model into token
     *
     * @param Authenticatable $user
     *
     * @return array
     */
    public function getCustomClaims(Authenticatable $user): array
    {
        if (method_exists($user, 'getJWTCustomClaims')) {
            return $user->getJWTCustomClaims();
        }

        return [];
    }

    /**
     * Get the entity repository
     *
     * @return EntityRepository|null
     */
    protected function getUserRepository(): ?EntityRepository
    {
        try {
            return em()->getRepository($this->userModel);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Generate a token string for a given user
     *
     * @param Authenticatable $user
     *
     * @return string
     */
    protected function generateToken(Authenticatable $user): string
    {
        $time  = time();
        $token = (new Builder())->issuedAt(time())
                                ->expiresAt($time + app()->getConfig('auth.expire_time', 3600))
        ;

        foreach ($this->getCustomClaims($user) as $claim => $value) {
            $token->withClaim($claim, $value);
        }

        return (string)$token->getToken();
    }
}
