<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
//use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
//use FOS\RestBundle\Controller\Annotations\QueryParam;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

//use Symfony\Component\Serializer\SerializerInterface;

class ApiUserController extends AbstractController
{

    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager, JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        // $this->jwtEncoder = $jwtEncoder;
    }
    /**
     * @Route("/api/users", name="app_api_users", methods={"GET"})
     * @return JsonResponse
     *
     */
    public function index(JWTTokenManagerInterface $jwtManager, Request $request, UserRepository $userRepository, SerializerInterface $serializer, JWTEncoderInterface $jwtEncoder): Response
    {
        //decoding token
        $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
        //dd($decodedJwtToken);
        //get email of the user
        $userEmail = $decodedJwtToken['email'];
        //load User using email address
        $loadUser = $userRepository->loadUserByIdentifier($userEmail);
        //check if the user is admin or not
        // if($loadUser == "ROLE_ADMIN"){

        // }
        $arrayRoles = $loadUser->getRoles();
        foreach ($arrayRoles as $role) {
            dd($role);
        }

        $bearer = $request->headers->get('Authorization');

        // dd($bearer);

        $decodeToken = $jwtEncoder->decode($bearer);
        dd($decodeToken);

        // $decodedJwtToken = $this->jwtManager->decode($bearer);
        dd('stop');
        if ($decodeToken instanceof TokenInterface) {

            $user = $decodeToken->getUser();
            dd($user);
            return $user;

        } else {
            return null;
        }

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
        try {
            return new JsonResponse(
                $serializer->serialize($user, "json", SerializationContext::create()->setGroups(array('details'))),
                JsonResponse::HTTP_OK,
                [],
                true

            );
        } catch (Exception $e) {

            return new JsonResponse(
                ["error" => $e->getMessage()],
                JsonResponse::HTTP_OK,
                [],
                true

            );
        }

    }

    /**
     * @Route("/api/user", name="app_api_user_create",methods={"POST"})
     * @Rest\View
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function createUser(Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $urlGenarator,
        User $user,
        UserPasswordHasherInterface $passwordHasher

    ) {
        // $data = $this->container->get('jms_serializer')->deserialize($request->getContent(), 'array', 'json');
        // $user = new User;
        // $form = $this->get('form.factory')->create(UserType::class, $user);
        // $form->submit($data);
        $user->setCustomer($manager->getRepository(Customer::class)->findOneBy([]));

        $manager = $this->getDoctrine()->getManager();

        $plaintextPassword = $user->getPassword();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        // dd($hashedPassword);
        $user->setPassword($hashedPassword);

        // $user->setPassword
        $manager->persist($user);
        $manager->flush();

        return new JsonResponse(
            $serializer->serialize($user, "json", SerializationContext::create()->setGroups(array('details'))),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenarator->generate("app_api_user_create", ["id" => $user->getId()])],
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
    /**
     * @Route("/api/user/{id}", name="app_api_user_delete",methods={"DELETE"})
     * @Rest\View
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function deleteUser(User $user, EntityManagerInterface $manager)
    {

        $manager->remove($user);
        $manager->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}
