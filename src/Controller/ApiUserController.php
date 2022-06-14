<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
//use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiUserController extends AbstractFOSRestController
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
     *
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

    /**
     * @Route("/api/user", name="app_api_user_create",methods={"POST"})
     * @Rest\View
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function createUser(Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $urlGenararor,
        User $user
    ) {
        $manager = $this->getDoctrine()->getManager();

        $manager->persist($user);
        $manager->flush();

        return new JsonResponse(
            $serializer->serialize($user, "json", SerializationContext::create()->setGroups(array('details'))),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenararor->generate("app_api_user_create", ["id" => $user->getId()])],
            true

        );
    }
}
