<?php

namespace App\Service;

use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DecodeToken
{
    // SerializerInterfaceprivate $serializer;

    public function __construct(SerializerInterface $serializer, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository, TokenStorageInterface $tokenStorageInterface)
    {
        $this->serializer = $serializer;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->userRepository = $userRepository;

    }
    public function loadUserInfo()
    {

        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
        return $decodedJwtToken;

    }

    public function userCan($userEmail, $userRole)
    {
        $loadUser = $this->userRepository->loadUserByIdentifier($userEmail);
        $arrayRoles = $loadUser->getRoles();
        foreach ($arrayRoles as $role) {
            if ($role == $userRole) {
                return true;
            }
        }
        return false;

    }

}
