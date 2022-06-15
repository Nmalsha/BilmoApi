<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
//use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
//use FOS\RestBundle\Controller\Annotations\QueryParam;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

//use Symfony\Component\Serializer\SerializerInterface;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="app_api_users", methods={"GET"})
     * @return JsonResponse
     *
     */
    public function index(UserRepository $userRepository, SerializerInterface $serializer): Response
    {

        // dd($userRepository->findAll());
        return new JsonResponse(
            $serializer->serialize($userRepository->findAll(), "json", SerializationContext::create()->setGroups(array('list'))),
            JsonResponse::HTTP_OK,
            [],
            true

        );
        // return new JsonResponse(
        //     $serializer->serialize($userRepository->findAll(), "json", ["groups" => "list"]),
        //     JsonResponse::HTTP_OK,
        //     [],
        //     true
        // );

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
        // $data = $this->container->get('jms_serializer')->deserialize($request->getContent(), 'array', 'json');
        // $user = new User;
        // $form = $this->get('form.factory')->create(UserType::class, $user);
        // $form->submit($data);
        $user->setCustomer($manager->getRepository(User::class)->findOneBy([]));

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

    /**
     * @Route("/api/user/{id}", name="app_api_user_update",methods={"PUT"})
     * @Rest\View
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function updateUser(User $user, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, UserRepository $userRepository)
    {

        $object = $userRepository->findBy(["id" => $user->getId()]);
        $user = new DeserializationContext();
        $user->setAttribute('deserialization-constructor-target', $object);
        $serializer->deserialize(
            $request->getContent(),
            User::class, "json",
            $user
        );
        $manager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
