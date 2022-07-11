<?php

namespace App\Security\Provider;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 * @package App\Security\Provider
 */
class UserProvider implements UserProviderInterface
{

    /**
     * UserProvider constructor.
     * @param UserLoaderInterface $userLoader
     */
    public function __construct(UserLoaderInterface $userLoader)
    {
        $this->userLoader = $userLoader;
    }

    public function loadUserByUsername(string $username)
    {

        return $this->userLoader->loadUserByUsername($username);
    }
    public function loadUserByIdentifier($identifier)
    {

    }

    public function refreshUser(UserInterface $user)
    {

    }

    public function supportsClass(string $class)
    {
        return $class === User::class;
    }
}
