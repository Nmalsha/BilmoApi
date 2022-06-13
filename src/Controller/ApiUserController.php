<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
//use FOS\RestBundle\Controller\Annotations as Rest;
// use FOS\RestBundle\Controller\Annotations\View;
//use FOS\RestBundle\Controller\Annotations\QueryParam;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="app_api_users", methods={"GET"})
     *
     *
     */
    public function index(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        // dd(users);
        return new JsonResponse(
            $serializer->serialize($userRepository->findAll(), "json", SerializationContext::create()->setGroups(array('list'))),
            JsonResponse::HTTP_OK,
            [],
            true

        );

    }

    /**
     * @Route("/api/user/{id}", name="app_api_user_show",methods={"GET"})
     */
    public function showUser(User $user, SerializerInterface $serializer)
    {

        return new JsonResponse(
            $serializer->serialize($user, "json", SerializationContext::create()->setGroups(array('details'))),
            JsonResponse::HTTP_OK,
            [],
            true

        );
    }
}
